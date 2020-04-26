<?php
namespace myerm\shop\common\models;

use myerm\backend\common\libs\NewID;
use myerm\shop\common\models\DealFlow;
use myerm\common\components\CommonEvent;

/**
 * 供应商提现记录类
 */
class Withdraw extends ShopModel
{
    public static function getDb()
    {
        return \Yii::$app->get('ds_cloud');
    }
    //生成交易流水
    const EVENT_NEW_DEAL = 'newdeal';
    
    /**
     *  保存提交申请信息
     *  @param array $param 数据格式如下：
     *  $param = [
     *     'fMoney' => '12', //申请提现的金额
     *     'SupplierBankAccountID' => '1', //供应商提现账户ID
     * ];
     */
    public static function saveValue($param)
    {
        $BankAccount = \Yii::$app->supplierbankaccount->findByID($param['SupplierBankAccountID']);
        $withdraw = new self();
        //获取供应商ID
        $SysUserID = \Yii::$app->backendsession->SysUserID;
        $supplier = \Yii::$app->supplier->findBySysUserID($SysUserID);
        $withdraw -> SupplierID = $supplier['lID'];
        $withdraw -> sName = "W".NewID::make();
        $withdraw -> CheckID = 0;
        $withdraw -> dNewDate = \Yii::$app->formatter->asDatetime(time());
        $withdraw -> NewUserID = $SysUserID;
        $withdraw -> sAccountName = $BankAccount->sAccountName;
        $withdraw -> sAccountNo = $BankAccount->sAccountNo;
        $withdraw -> sOpenBank = $BankAccount->sOpenBank;
        $withdraw -> fMoney = $param['fMoney'];
        $withdraw -> save();
        
        $event = new CommonEvent();
        $event->extraData = $withdraw;
        \Yii::$app->withdraw->trigger(static::EVENT_NEW_DEAL, $event);
        return true;
    }

    /**
     *  提现申请成功后的响应事件
     *  添加交易记录并统计待结算金额
     *  @param CommonEvent $event
     */
    public static function newDeal(CommonEvent $event)
    {
        $deal = $event->extraData;
        $supplier = \Yii::$app->supplier->findByID($deal->SupplierID);
        $param = [];
        $param['sName'] = "供应商【".$supplier['sName']."】提现";
        $param['fMoney'] = - $deal->fMoney;
        $param['MemberID'] = $deal->SupplierID;
        $param['RoleType'] = "supplier";
        $param['TypeID'] = DealFlow::$TypeID['withdraw'];
        $param['DealID'] = $deal->lID;
        \Yii::$app->dealflow->change($param);
    }

}