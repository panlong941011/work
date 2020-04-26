<?php
namespace myerm\shop\backend\controllers;

use myerm\backend\common\controllers\ObjectController;
use myerm\backend\common\libs\File;
use myerm\backend\common\libs\NewID;
use myerm\shop\common\models\Recharge;
use myerm\shop\common\models\Buyer;
use myerm\shop\common\models\DealFlow;

/**
 * 采购商充值

 */
class RechargeController extends BackendController
{
    public function beforeObjectNewSave($sysObject, $arrObjectData)
    {
        if ($_POST['arrObjectData']['Shop/Recharge']['sPicBase64']) {
            $arrFileInfo = File::parseImageFromBase64($_POST['arrObjectData']['Shop/Recharge']['sPicBase64']);

            $sFileName = NewID::make() . "." . $arrFileInfo[0];
            $arrObjectData['sPicPath'] = File::putContentToUploadDir($sFileName, $arrFileInfo[1]);
        }

        //获取渠道商ID
        $arrObjectData['BuyerID'] = $this->BuyerID;;
        $arrObjectData['sName'] = "RE" . NewID::make();
        $arrObjectData['CheckID'] = 0;
        return parent::beforeObjectNewSave($sysObject, $arrObjectData);
    }

    public function getListTableLineButton($data)
    {
        return $this->renderPartial("listtablelinebutton", ['data' => $data]);
    }

    public function actionJs()
    {
        return $this->renderPartial("js");
    }

    public function actionCheck($ID)
    {
        $data = [];
        $data['ID'] = $ID;
        return $this->renderPartial("check", $data);
    }

    public function actionResult()
    {
        $data = \Yii::$app->request->post();
        $Recharge = Recharge::findByID($data['ID']);
        $res = [];
        //判断是否已审核
        if ($Recharge->CheckID == 0) {
            $param = [];
            $param['RoleType'] = 'buyer';
            $param['MemberID'] = $Recharge['BuyerID'];
            //记录审核结果
            $Recharge->fBalanceBefore = \Yii::$app->dealflow->computeDeal($param);
            $Recharge->fBalanceAfter = $Recharge['fBalanceBefore'] + $Recharge['fMoney'];
            $Recharge->CheckID = $data['check'];
            $Recharge->CheckUserID = \Yii::$app->backendsession->SysUserID;
            $Recharge->dCheckDate = \Yii::$app->formatter->asDatetime(time());
            if ($data['check'] == -1 && $data['fail_reason'] != "") {
                $Recharge->sFailReason = $data['fail_reason'];
            }
            $Recharge->save();

            if ($data['check'] == 1) {
                //添加交易流水
                $buyer = Buyer::findByID($Recharge['BuyerID']);
                $deal = [];
                $deal['sName'] = "渠道商【" . $buyer['sName'] . "】充值成功";
                $deal['fMoney'] = $Recharge['fMoney'];
                $deal['MemberID'] = $Recharge['BuyerID'];
                $deal['RoleType'] = "buyer";
                $deal['TypeID'] = DealFlow::$TypeID['recharge'];
                $deal['DealID'] = $data['ID'];
                \Yii::$app->dealflow->change($deal);

                //重置渠道款不足的订单
                $OrderArr = \Yii::$app->order->getUnpaid($Recharge['BuyerID']);
                foreach ($OrderArr as $key => $value) {
                    \Yii::$app->order->orderPayment($value->lID);
                }

            }
            $res['type'] = 'success';
            $res['msg'] = '操作成功';

        } else {
            $res['type'] = 'false';
            $res['msg'] = '该充值记录已审核，不可重复审核';
        }
        return json_encode($res);
    }

    public function getViewButton()
    {
        $data = [];
        return $this->renderPartial('viewbutton', $data);
    }

    public function getListButton()
    {
        $data = [];
        return $this->renderPartial('listbutton', $data);
    }

    public function listDataConfig($sysList, $arrConfig)
    {
        if(\Yii::$app->backendsession->SysRoleID==1){

        }
        else {
            $BuyerID = $this->BuyerID;
            $arrConfig['arrFlt'][] = [
                'sField' => 'BuyerID',
                'sOper' => 'equal',
                'sValue' => $BuyerID,
                'sSQL' => "BuyerID ='" . $BuyerID . "'"
            ];
        }
        return parent::listDataConfig($sysList, $arrConfig);
    }
}