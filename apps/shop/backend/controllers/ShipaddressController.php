<?php

namespace myerm\shop\backend\controllers;

use myerm\backend\common\controllers\ObjectController;
use myerm\shop\common\models\ShipAddress;
use myerm\common\models\ExpressCompany;
use myerm\shop\common\models\Area;
use myerm\shop\common\models\ExpressBusiness;
use myerm\shop\common\models\Supplier;

/**
 * 发货地址管理
 * ============================================================================
 * 版权所有 2010-2017 三只象（厦门）网络科技有限公司，并保留所有权利。
 * 网站地址: http://www.myerm.cn
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和
 * 使用；不允许对程序代码以任何形式任何目的的再发布。
 * ============================================================================
 * @author 何城城
 * @time 2018年5月30日 09:28:35
 * @version v1.0
 */
class ShipaddressController extends BackendController
{
    public function listDataConfig($sysList, $arrConfig)
    {
        if ($this->supplier) {
            $arrConfig['arrFlt'][] = [
                'sField' => 'SupplierID',
                'sOper' => 'equal',
                'sValue' => $this->supplier->lID,
                'sSQL' => "SupplierID='" . $this->supplier->lID . "'"
            ];
        }

        return parent::listDataConfig($sysList, $arrConfig);
    }

    public function actionNew()
    {
        if (\Yii::$app->request->isPost) {
            $param = \Yii::$app->request->post();
            $result = ShipAddress::saveValue($param);

            return $this->redirect("/shop/shipaddress/view?ID=" . $result);
        } else {
            $data = [];
            $data['supplier'] = $this->supplier;
            $data['ExpressCompany'] = ExpressCompany::findAll(['bKdBird' => 1]);
            $data['Province'] = Area::findAll(['sType' => 'Province']);

            return $this->render('new', $data);
        }
    }

    public function actionEdit()
    {
        if (\Yii::$app->request->isPost) {
            $param = \Yii::$app->request->post();
            $result = ShipAddress::editSave($param);

            return $this->redirect(\Yii::$app->homeUrl . "/shop/shipaddress/view?ID=" . $result);
        } else {
            $data = [];
            $data['ShipAddressInfo'] = ShipAddress::getShipAddressInfo($_GET['ID']);
            $data['ShipAddressInfo']['SupplierName'] = Supplier::findByID($data['ShipAddressInfo']['SupplierID'])->sName;
            $data['supplier'] = $this->supplier;
            $data['ExpressCompany'] = ExpressCompany::findAll(['bKdBird' => 1]);
            $data['Province'] = Area::findAll(['sType' => 'Province']);
            $data['City'] = Area::findAll(['UpID' => $data['ShipAddressInfo']['ProvinceID']]);
            $data['Area'] = Area::findAll(['UpID' => $data['ShipAddressInfo']['CityID']]);

            return $this->render('edit', $data);
        }
    }


    /**
     * 省-市-区/县联动查询
     * @author hcc
     * @time 2018/5/30
     * */
    public function actionSubareas()
    {
        $data = \Yii::$app->request->post();
        $res = Area::getSubAreas($data['ID']);

        //因为Area的M层已有getSubAreas方法，避免影响到原有功能，故不对方法做修改
        //$res为getSubAreas方法查询出来的对象结果集，无法通过json_encode转换为正确的json数据
        //在该处增加将对象结果转为数组的代码
        $newData = [];
        foreach ($res as $key => $val) {
            $newData[$key] = $val->attributes;
        }

        echo json_encode($newData);
    }

    /**
     * ajax查询快递业务
     * @author hcc
     * @time 2018/5/30
     * */
    public function actionExpressbusiness()
    {
        $data = \Yii::$app->request->post();
        $res = ExpressBusiness::getExpressBusiness($data['sKdBirdCode']);
        echo json_encode($res);
    }
}
