<?php

namespace myerm\shop\backend\controllers;

use myerm\backend\common\controllers\ObjectController;
use myerm\shop\common\models\OrderLogistics;
use myerm\common\models\ExpressCompany;
use yii\base\UserException;
use yii\helpers\ArrayHelper;

/**
 * 订单物流管理
 * ============================================================================
 * 版权所有 2010-2017 三只象（厦门）网络科技有限公司，并保留所有权利。
 * 网站地址: http://www.myerm.cn
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和
 * 使用；不允许对程序代码以任何形式任何目的的再发布。
 * ============================================================================
 * @author 何城城
 * @since 2018年7月10日11:57:35
 * @version v1.4
 */
class OrderlogisticsController extends BackendController
{
    public function getHomeTabs()
    {
        $data = [];
        $data['arrList'] = [];

        $sListKey = "Main.Shop.OrderLogistics.List.All";

        $list = [];

        $list['ID'] = 'all';
        $list['sName'] = '全部';
        $list['sKey'] = $sListKey;
        $list['sLinkUrl'] = \Yii::$app->homeUrl . '/' . strtolower($this->sObjectName) . '/getlisttable?sListKey=' . $sListKey . '&sTabID=all&sExtra=all';
        $data['arrList'][] = $list;

        $list['ID'] = 'success';
        $list['sName'] = '已打印电子面单';
        $list['sKey'] = $sListKey;
        $list['sLinkUrl'] = \Yii::$app->homeUrl . '/' . strtolower($this->sObjectName) . '/getlisttable?sListKey=' . $sListKey . '&sTabID=success&sExtra=success';
        $data['arrList'][] = $list;

        $list['ID'] = 'closed';
        $list['sName'] = '已获取快递单号';
        $list['sKey'] = $sListKey;
        $list['sLinkUrl'] = \Yii::$app->homeUrl . '/' . strtolower($this->sObjectName) . '/getlisttable?sListKey=' . $sListKey . '&sTabID=closed&sExtra=closed';
        $data['arrList'][] = $list;


        return $this->renderPartial('@app/common/views/hometabs', $data);
    }

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

        if ($_POST['sExtra'] == 'all') {

        } elseif ($_POST['sExtra'] == 'success') {
            $arrConfig['arrFlt'][] = ['sField' => 'ExpressOrderStatusID', 'sOper' => 'equal', 'sValue' => '2'];
        } elseif ($_POST['sExtra'] == 'closed') {
            $arrConfig['arrFlt'][] = ['sField' => 'ExpressOrderStatusID', 'sOper' => 'equal', 'sValue' => '1'];
        }

        return parent::listDataConfig($sysList, $arrConfig);
    }

    public function formatListData($arrData)
    {
        $arrID = ArrayHelper::getColumn($arrData,'lID');

        $arrOrderLogistics = OrderLogistics::findAll(['lID' => $arrID]);

        $arrDetailData = [];
        foreach ($arrOrderLogistics as $orderLogistics) {
            $arrInfo = json_decode($orderLogistics->sProductInfo);
            foreach ($arrInfo as $v){
                $arrDetailData[$orderLogistics->lID]['sProductName'][] = $v->sName;
                $arrDetailData[$orderLogistics->lID]['lQuantity'][] = $v->lQuantity;
                $arrDetailData[$orderLogistics->lID]['sSKU'][] = $v->sSKU;
            }
        }

        //数据输出到列表
        foreach ($arrData as $key => $data) {
            $arrData[$key]['sProductName'] = $this->listDataTemplate($arrDetailData[$data['lID']]['sProductName']);
            $arrData[$key]['lQuantity'] = $this->listDataTemplate($arrDetailData[$data['lID']]['lQuantity']);
            $arrData[$key]['sSKU'] = $this->listDataTemplate($arrDetailData[$data['lID']]['sSKU']);
            $arrData[$key]['sExpressCompany'] = $this->getExpressCompanyName($data['sExpressCompany']);
        }

        return $arrData;
    }

    public function listDataTemplate($arrData)
    {
        $sHtml = [];
        $count = count($arrData);

        if ($count == 1) {
            return $arrData[0];
        }

        foreach ($arrData as $key => $data) {
            $data = $data ? $data : '&nbsp;';
            $sHtml[] = \yii\helpers\Html::tag('div', $data, [
                'style' => [
                    'padding' => '5px 0',
                    'border-bottom' => $key < ($count - 1) ? '1px solid #E7ECF1' : '0'
                ]
            ]);
        }
        return implode('', $sHtml);
    }

    /**
     * 保存订单物流
     * @return \yii\web\Response
     * @author hcc
     * @time 2018年7月11日15:16:28
     */
    public function actionSeparateexpress()
    {
        $arrParam = \Yii::$app->request->post();
        
        $result = OrderLogistics::saveValue($arrParam);

        if($result){
            return $this->asJson(['status' => true, 'msg' => '订单物流拆分成功']);
        }else{
            return $this->asJson(['status' => false, 'msg' => '订单物流拆分失败']);
        }
    }

    public function getListButton()
    {
        $data = [];
        return $this->renderPartial("listbutton", $data);
    }

    //打印电子面单弹出窗口
    public function actionAlertexpress()
    {
        $ids = $_REQUEST['sSelectedID'];
        $arrOrderID = explode(';', $ids);
        $expressCompany = []; //快递公司

        $data = [];//快递单号,子订单号，快递公司

        $expressOrderInfo = [];  //模板数据

        //判断是否是同一家物流公司
        foreach ($arrOrderID as $k => $lID) {
            $result = OrderLogistics::findOne($lID);
            if($result['ExpressOrderStatusID']== 0){
                return $this->asJson(['bSuccess' => false, 'sMsg' => "子订单号" . $result['sName'] . "未获取快递单号"]);
            }elseif ($result['ExpressOrderStatusID']== 3){
                return $this->asJson(['bSuccess' => false, 'sMsg' => "子订单号" . $result['sName'] . "不是快递面单"]);
            }
            $expressCompany[$k] = $result['sExpressCompany'];

            $couriername = ExpressCompany::findOne(['sKdBirdCode' => $result['sExpressCompany']]);
            $data[$k]['sName'] = $result['sName'];
            $data[$k]['sExpressCompany'] = $couriername['sName'];
            $data[$k]['sExpressNo'] = $result['sExpressNo'];

            if ($result['sReturnedTemplate']) {
                $expressOrderInfo[] = $result['sReturnedTemplate'];
            } else {
                $expressOrderInfo[] = json_decode($result['sExpressOrderInfo'], true);
            }
        }

        $arrCount = array_unique($expressCompany);
        if (count($arrCount) >= 2) {
            return $this->asJson(['bSuccess' => false, 'sMsg' => "请选择同一家物流公司"]);
        }

        $companyName = $arrCount[0];
        //传入模板文件
        if ($companyName == "HTKY") {//百世
            $html = $this->renderPartial("bs", ['data' => $expressOrderInfo]);
        } elseif ($companyName == "YD") {//韵达
            $html = $this->renderPartial("yd", ['data' => $expressOrderInfo]);
        } elseif ($companyName == "YZPY"){//邮政
            $html = $this->renderPartial("yzbg", ['data' => $expressOrderInfo]);
        } elseif ($companyName == "JD"){//京东
            $html = $this->renderPartial("jd", ['data' => $expressOrderInfo]);
        } elseif ($companyName == "ZTO"){//中通
            $html = $this->renderPartial("yz", ['data' => $expressOrderInfo]);
        } elseif ($companyName == "YTO"){//圆通
            $html = $this->renderPartial("yt", ['data' => $expressOrderInfo]);
        } elseif ($companyName == "STO") {//申通
	        $html = $this->renderPartial("st", ['data' => $expressOrderInfo]);
        } else {
	        $html = "";
        }

        $expressOrderInfoJson = json_encode($expressOrderInfo);
        $data = $this->renderPartial("print", ['data' => $data, 'html' => $html, 'companyName' => $companyName, 'expressOrderInfoJson' => $expressOrderInfoJson,'ids'=>$ids]);
        return $this->asJson(['bSuccess' => true, 'data' => $data]);
    }

    /**
     * @param id
     * 更改面单状态
     * 2018.7.12 cgq
     */
    public function actionChangestatus()
    {
        $ids = $_REQUEST['id'];
        $arrOrderID = explode(';', $ids);
        foreach ($arrOrderID as $orderID){
            OrderLogistics::changeStatus($orderID);
        }
    }

    /**
     * @param $express
     * 从快递编号获取快递名字
     * 2018.7.12 cgq
     */
    public function getExpressCompanyName($express)
    {
        if(!$express){
            $data = "";
        }else{
            $result = ExpressCompany::findOne(['sKdBirdCode'=>$express]);
            $data = $result['sName'];
        }
        return $data;
    }

    /**
     * 保存一键拆单数据
     * @return \yii\web\Response
     * @throws \yii\base\InvalidConfigException
     * @author hcc
     * @time 2018年7月20日17:17:50
     */
    public function actionQuickseparate()
    {
        $arrParam = \Yii::$app->request->post();

        $result = OrderLogistics::saveQuickSeparateValue($arrParam);

        if ($result) {
            return $this->asJson(['status' => true, 'msg' => '订单物流拆分成功']);
        } else {
            return $this->asJson(['status' => false, 'msg' => '订单物流拆分失败']);
        }

    }
}