<?php

namespace myerm\shop\backend\controllers;

use myerm\shop\common\models\Refund;
use myerm\shop\common\models\RefundReturn;
use myerm\shop\common\models\Returns;


class RefundreturnController extends BackendController
{
    const APPLY = 'wait';
    const RETURNSTYPE = 'moneyandproduct';
    const DELIVERED = 'delivered';
    const RECEIVED = 'received';
    const REFUSE = 'refuse';
    const API_FAILED = -1;

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

    public function formatListData($arrData)
    {
        $arrOrderID = [];
        foreach ($arrData as $data) {
            $arrOrderID[] = $data['lID'];
        }

        //数据输出到列表
        foreach ($arrData as $key => $data) {
            $arrData[$key]['sShipNo'] = "<a href='javascript:;'onclick=\"parent.addTab('查询物流信息', '/shop/express/query?CompanyID=" . $data['ShipCompanyID']['ID'] . "&sNo=" . $data['sShipNo'] . "')\"> " . $data['sShipNo'] . "&nbsp;</a>";
        }

        return $arrData;
    }

    public function actionView($ID)
    {
        $data = [];
        $returns = RefundReturn::findByID($ID);

        $data['data'] = $returns;
        $data['ShipCompany'] = $returns->shipCompany->sName;

        if ($this->checkHasOperaPower('confirmreceive')) {
            $data['bHasPower'] = true;
        } else {
            $data['bHasPower'] = false;
        }

        return $this->render("view", $data);
    }

    /**
     * 确认收货
     * @param array $arrData 退货信息
     * @author panlong
     * @time 2018-06-05
     */
    public function actionAgreereceive($ID)
    {
        if (\Yii::$app->request->isPost) {

            $returns = RefundReturn::findByID($_GET['ID']);
            if ($returns->StatusID != static::DELIVERED) {
                return $this->asJson(['status' => false, 'message' => '该申请已审核']);
            }

            //确认收货
            $returns->receive();
            \Yii::$app->refund->agreeReceive($returns->RefundID);

            return $this->asJson(['status' => true, 'message' => '确认收货成功']);
        } else {
            $data = [];
            $data['returns'] = RefundReturn::findByID($ID);
            return $this->renderPartial("agreereceive", $data);
        }
    }

    /**
     * 拒绝收货
     * @param array $arrData 退货信息
     * @author panlong
     * @time 2018-06-06
     */
    public function actionDenyreceive($ID)
    {
        if (\Yii::$app->request->isPost) {
            $returns = RefundReturn::findByID($ID);
            $returns->denyReceive();
            \Yii::$app->refund->denyReceive([
                'id' =>$returns->RefundID,
                'sDenyReceiveExplain' => $_POST['sDenyReceiveExplain']
            ]);
            return $this->asJson(['status' => true, 'message' => '拒绝确认收货成功']);
        } else {
            $data = [];
            $data['returns'] = Returns::findByID($ID);
            return $this->renderPartial("denyreceive", $data);
        }
    }
}