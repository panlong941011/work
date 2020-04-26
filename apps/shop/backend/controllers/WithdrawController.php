<?php
namespace myerm\shop\backend\controllers;

use myerm\backend\common\controllers\ObjectController;

use myerm\shop\common\models\Withdraw;
use myerm\shop\common\models\DealFlow;
use myerm\shop\common\models\Supplier;

/**
 * 供应商提现
 * ============================================================================
 * 版权所有 2010-2017 三只象（厦门）网络科技有限公司，并保留所有权利。
 * 网站地址: http://www.myerm.cn
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和
 * 使用；不允许对程序代码以任何形式任何目的的再发布。
 * ============================================================================
 * @author 何城城
 * @since 2018年05月04日 18:44:17
 * @version v1.0
 */
class WithdrawController extends ObjectController
{
    public function getViewButton()
    {
        $data = [];
        return $this->renderPartial('viewbutton', $data);
    }

    public function getListTableLineButton($data)
    {
        return $this->renderPartial("listtablelinebutton", ['data' => $data]);
    }

    public function actionJs()
    {
        return $this->renderPartial("js");
    }
    
    public function actionNew()
    {
        if (\Yii::$app->request->isPost)
        {
            $param = \Yii::$app->request->post();
            Withdraw::saveValue($param);

            return $this->redirect("/shop/withdraw/new?save=yes");
        } else {
            //获取供应商ID
            $SysUserID = \Yii::$app->backendsession->SysUserID;
            $supplier = \Yii::$app->supplier->findBySysUserID($SysUserID);
            
            $data = [];
            //获取供应商可提现金额
            $SupplierInfo = [];
            $SupplierInfo['MemberID'] = $supplier['lID'];
            $SupplierInfo['RoleType'] = 'supplier';
            $fBalance = \Yii::$app->dealflow->computeDeal($SupplierInfo);
            $data['fBalance'] = $fBalance > 0 ? $fBalance : 0;
            //获取供应商提现账户信息
            $data['BankAccount'] = \Yii::$app->supplierbankaccount->findBankAccountByID($supplier['lID']);
            return $this->render('withdraw',$data);
        }
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

        $Withdraw = Withdraw::findByID($data['ID']);
        
        //判断是否审核过
        $res = [];
        if($Withdraw->CheckID == 0){
            $param = [];
            $param['RoleType'] = 'supplier';
            $param['MemberID'] = $Withdraw['SupplierID'];
            //记录审核结果
            $Withdraw->CheckID = $data['check'];
            $Withdraw->CheckUserID = \Yii::$app->backendsession->SysUserID;
            $Withdraw->dCheckDate = \Yii::$app->formatter->asDatetime(time());
            if($data['check'] == 1){
                $Withdraw->fBalanceAfter = \Yii::$app->dealflow->computeDeal($param);
                $Withdraw->fBalanceBefore = $Withdraw['fBalanceAfter'] + $Withdraw['fMoney'];
            }elseif($data['check'] == -1){
                $Withdraw->fBalanceBefore = \Yii::$app->dealflow->computeDeal($param);
                $Withdraw->fBalanceAfter = $Withdraw['fBalanceBefore'] + $Withdraw['fMoney'];
                $Withdraw->sFailReason = $data['fail_reason'];
                //再添加交易流水，将供应商申请提现金额倒加回去
                $supplier = Supplier::findByID($Withdraw['SupplierID']);

                $deal = [];
                $deal['sName'] = "供应商【".$supplier['sName']."】提现失败";
                $deal['fMoney'] = $Withdraw['fMoney'];
                $deal['MemberID'] = $Withdraw['SupplierID'];
                $deal['RoleType'] = "supplier";
                $deal['TypeID'] = DealFlow::$TypeID['withdraw_deny'];
                $deal['DealID'] = $data['ID'];
                \Yii::$app->dealflow->change($deal);
            }
            $Withdraw->save();
            \Yii::$app->supplier->computeAccountMoney($Withdraw['SupplierID']);

            $res['type'] = 'success';
            $res['msg'] = '操作成功';
        }else{
            $res['type'] = 'false';
            $res['msg'] = '该提现记录已审核，不可重复审核';
        }
        return json_encode($res);
    }

    public function getListButton()
    {
        $data = [];
        return $this->renderPartial('listbutton', $data);
    }
}