<?php

namespace myerm\shop\common\models;

use myerm\shop\common\models\Buyer;
use myerm\shop\common\models\Supplier;

/**
 * 交易记录
 */
class DealFlow extends ShopModel
{
    /**
     * 配置数据源
     * @return \yii\db\Connection
     */
    public static function getDb()
    {
        return \Yii::$app->get('ds_cloud');
    }
    /**
     * @var array $TypeID 交易类型
     * @author hcc
     */
    public static $TypeID = [
        'withdraw' => 'supplier_withdraw', //供应商提现
        'withdraw_deny' => 'supplier_withdraw_deny',//供应商提现驳回
        'income' => 'supplier_income', //供应商订单收入
        'refund' => 'buyer_refund', //渠道商退款
        'recharge' => 'buyer_recharge', //渠道商充值
        'buy' => 'buyer_buyproduct', //渠道商渠道商品
    ];

    /**
     *  统计金额
     * @param array $param 数据格式如下：
     *  $param = [
     *     'MemberID' => '12', //渠道商或供应商ID
     *     'RoleType' => 'buyer', //身份标识，2种，buyer为渠道商，supplier为供应商
     * ];
     * @return int
     * @author hcc
     */
    public function computeDeal($param = [])
    {
        $where = [];
        if ($param['RoleType'] == 'buyer') {
            $where['BuyerID'] = $param['MemberID'];
        } elseif ($param['RoleType'] == 'supplier') {
            $where['SupplierID'] = $param['MemberID'];
        }
        return self::find()->where($where)->sum('fMoney');
    }

    public function change($param)
    {
        $dealFlow = new static();
        $dealFlow->sName = $param['sName'];
        $dealFlow->fMoney = $param['fMoney'];
        $dealFlow->dNewDate = \Yii::$app->formatter->asDatetime(time());
        $dealFlow->TypeID = $param['TypeID'];

        if ($param['TypeID'] == self::$TypeID['withdraw']) {
            $dealFlow->WithdrawID = $param['DealID'];
        } elseif ($param['TypeID'] == self::$TypeID['income']) {
            $dealFlow->OrderID = $param['DealID'];
        } elseif ($param['TypeID'] == self::$TypeID['refund']) {
            $dealFlow->RefundID = $param['DealID'];
        } elseif ($param['TypeID'] == self::$TypeID['recharge']) {
            $dealFlow->RechargeID = $param['DealID'];
        } elseif ($param['TypeID'] == self::$TypeID['buy']) {
            $dealFlow->OrderID = $param['DealID'];
        }

        $data = [];
        $data['RoleType'] = $param['RoleType'];
        $data['MemberID'] = $param['MemberID'];
        $dealFlow->fBalanceBefore = self::computeDeal($data);
        $dealFlow->fBalanceAfter = $dealFlow->fBalanceBefore + $param['fMoney'];
        if ($param['RoleType'] == 'buyer') {
            $dealFlow->BuyerID = $param['MemberID'];
        } elseif ($param['RoleType'] == 'supplier') {
            $dealFlow->SupplierID = $param['MemberID'];
        }
        $dealFlow->save();

        if ($param['RoleType'] == 'buyer') {
            $buyer = Buyer::findByID($param['MemberID']);
            $buyer->fBalance = $dealFlow->fBalanceAfter;
            $buyer->save();
        } elseif ($param['RoleType'] == 'supplier') {
            $supplier = Supplier::findByID($param['MemberID']);
            $supplier->fBalance = $dealFlow->fBalanceAfter;
            $supplier->save();
            $waitMoney=$param['waitMoney'];
            $supplier->computeAccountMoney($waitMoney);
        }
        return true;
    }
}