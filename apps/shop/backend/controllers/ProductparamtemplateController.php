<?php

namespace myerm\shop\backend\controllers;


use myerm\backend\common\controllers\ObjectController;
use myerm\shop\backend\models\Product;
use myerm\shop\common\models\ProductParamTemplate;

/**
 * 商品参数控制器
 * ============================================================================
 * 版权所有 2010-2017 三只象（厦门）网络科技有限公司，并保留所有权利。
 * 网站地址: http://www.myerm.cn
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和
 * 使用；不允许对程序代码以任何形式任何目的的再发布。
 * ============================================================================
 * @author 陈鹭明  <lumingchen@qq.com>
 * @since 2017年12月3日 20:42:21
 * @version v1.0
 */
class ProductparamtemplateController extends ObjectController
{

    public function actionNew()
    {
        return $this->render('new');
    }

    public function actionNewsave()
    {
        if (\Yii::$app->request->isPost) {
            $prams = [];
            $productParams = $_REQUEST['hotword']['sName']; //参数名称
            $prams['value'] = json_encode($productParams);
            $prams['sName'] = $_REQUEST['sDefSearchWord'];
            $prams['dNewDate'] = date("Y-m-d H:i:s", time());
            $prams['dEditDate'] = $prams['dNewDate'];
            ProductParamTemplate::saveData($prams);
            return $this->redirect("/shop/productparamtemplate/new?save=yes");
        }
        return false;
    }

    public function actionView()
    {
        $ID = $_REQUEST['ID'];
        $productParmsTemplate = new ProductParamTemplate();
        $data = $productParmsTemplate::findOne($ID);
        if(!$data){
            throw \Yii::$app->errorHandler->exception = new UserException(\Yii::t('app', "您查看的对象不存在。"));
        }
        $data['value'] = json_decode($data['value']);
        return $this->render('view', ['data' => $data]);
    }

    public function actionEdit()
    {
        $ID = $_REQUEST['ID'];
        $productParmsTemplate = new ProductParamTemplate();
        $data = $productParmsTemplate::findOne($ID);
        if(!$data){
            throw \Yii::$app->errorHandler->exception = new UserException(\Yii::t('app', "您查看的对象不存在。"));
        }
        $data['value'] = json_decode($data['value']);
        return $this->render('edit', ['data' => $data, 'ID' => $ID]);
    }

    public function actionEditsave()
    {
        if (\Yii::$app->request->isPost) {
            $ID = $_REQUEST['ID'];
            $params = [];
            $productParmas = $_REQUEST['hotword']['sName']; //参数名称
            $params['sName'] = $_REQUEST['sDefSearchWord'];
            $params['value'] = json_encode($productParmas);
            $params['dEditDate'] = date("Y-m-d H:i:s", time());
            ProductParamTemplate::updateData($ID, $params);
            return $this->redirect("/shop/productparamtemplate/edit?save=yes");
        }
        return false;
    }

    public function formatListData($arrData)
    {
        foreach ($arrData as $key => $v) {
            $arrData[$key]['value'] = implode(',', json_decode($v['value']));
        }
        return $arrData;
    }

    public function actionDetail()
    {
        $productParmsTemplateModel = new ProductParamTemplate();
        $productModel = new Product();
        //新建
        if ($_REQUEST['productID'] == "" || $_REQUEST['productID'] == 'undefined') {
            $id = $_REQUEST['id'];
            $productParmsData = $productParmsTemplateModel::findOne($id);
            $value = json_decode($productParmsData['value']);
            return $this->renderPartial('detail', ['data' => $value]);
        } else {
            $type = $_REQUEST['type'];
            $productID = $_REQUEST['productID'];  //商品的lID
            $productData = $productModel::findOne($productID);
            $value = json_decode($productData['sParameterArray'], true);//商品表的参数以及值
            //编辑
            if ($type == "edit") {
                $arrProductParms = [];
                $productParmsData = $productParmsTemplateModel::findOne($productData['ProductParamTemplateID']);//获取模板ID,查找模板参数
                $productParmsData = json_decode($productParmsData['value']);
                foreach ($productParmsData as $k => $v) {
                    $arrProductParms[$v] = "";
                }
                foreach ($productParmsData as $v) {
                    foreach ($value as $k => $v1) {
                        if ($v == $k) {
                            $arrParams[$v] = $v1;
                        }
                    }
                }
                //对数组进行合并
                $value = array_merge($arrProductParms,$arrParams);
            }
            return $this->renderPartial('detail', ['data' => $value, 'productID' => $productID, 'type' => $type]);
        }
    }

}