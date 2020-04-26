<?php

namespace myerm\shop\common\models;

use myerm\shop\backend\models\Refund;
class Importreturn extends ShopModel
{
    /*
     * 订单退款导入数据验证
     */
    public static function checkImportRecord($arr)
    {

//        unpaid=渠道款余额不足
//paid=已扣渠道款
//closed=订单关闭
//delivered=已发货
//success=交易成功
//exception=付款异常
        $arr['status'] = 1;
        $res = (new \yii\db\Query())
            ->select('o.lID,o.sName,o.StatusID,o.BuyerID,o.fBuyerPaid,o.SupplierID,d.fBuyerPaidTotal,o.fShip,o.RefundStatusID ')
            ->from('order o')
            ->leftJoin('orderdetail d', 'd.OrderID=o.lID')
            ->where(['o.sClientSN' => $arr['orderName']])
            ->andWhere(['d.ProductID' => $arr['PrductID']])
            ->andWhere(['o.BuyerID' => $arr['buyerID']])
            ->one();
        if (!$res) {
            $arr['sRemark'] .= "请核对有链商品ID及订单编号；";
            $arr['status'] = 0;
        } else {
            $arr['OrderID'] = $res['lID'];
            $arr['sName'] = $res['sName'];
            //如果订单已交易成功或者有退款中
            if ($res['RefundStatusID']) {
                $arr['sRemark'] .= "该订单已申请退款，请勿重新申请";
                $arr['status'] = 0;
            } elseif ($res['StatusID'] == 'success' || $res['StatusID'] == 'closed') {
                $arr['sRemark'] .= "该商品对应的订单已交易完成";
                $arr['status'] = 0;
            } elseif ($res['StatusID'] == 'paid') {
                if ($arr['lNum'] < 0 || $arr['lTotalNum'] < 0) {
                    $arr['sRemark'] .= "退款商品数量和商品总数量不能小于0";
                    $arr['status'] = 0;
                } else {
                    //已付款
                    $fProduct = number_format($res['fBuyerPaidTotal'] * $arr['lNum'] / $arr['lTotalNum'], 2);
                    $fShip = number_format($res['fShip'] * $res['fBuyerPaidTotal'] / $res['fBuyerPaid'], 2);
                    $arr['fMoney'] = $fProduct + $fShip;
                }
            } elseif ($res['StatusID'] == 'delivered') {
                //已发货
                if ($arr['lNum'] < 0 || $arr['lTotalNum'] < 0) {
                    $arr['sRemark'] .= "退款商品数量和商品总数量不能小于0";
                    $arr['status'] = 0;
                } elseif (empty($arr['sPic1']) && empty($arr['sPic2']) && empty($arr['sPic3'])) {
                    $arr['sRemark'] .= "该商品已发货，需提供退款图片";
                    $arr['status'] = 0;
                } else {
                    //已付款
                    $fProduct = number_format($res['fBuyerPaidTotal'] * $arr['lNum'] / $arr['lTotalNum'], 2);
                    $fShip = number_format($res['fShip'] * $res['fBuyerPaidTotal'] / $res['fBuyerPaid'], 2);
                    $arr['fMoney'] = $fProduct + $fShip;
                }
            }
        }
        if ($arr['status'] != 0) {
            $arr['status'] = 1;
            $arr['sRemark'] = '导入成功';
            //同步退款记录
            if ($res['StatusID'] == 'paid') {
                //未发货 全额退款
                $res = Refund::saveRefund([
                    'TypeID' => 'onlymoney',
                    'sReason' => '未发货退款',
                    'OrderID' => $res['lID'],
                    'SupplierID' =>$res['SupplierID'],
                    'fBuyerPaid' =>$res['fBuyerPaid'],
                    'fRefundApply' => $res['fBuyerPaid'],
                    'sExplain' => '未发货退款',
                    'imgList' => '',
                    'lItemTotal' => 1,
                    'lRefundItem' => 1,
                    'fRefundReal' => $res['fBuyerPaid'],
                    'fRefundProduct' => $res['fBuyerPaid'],
                    'BuyerID' => $res['BuyerID']
                ]);
            } elseif ($res['StatusID'] == 'delivered') {
                $res = Refund::saveRefund([
                    'TypeID' => 'onlymoney',
                    'sReason' => $_POST['sReason'],
                    'OrderID' => $res['lID'],
                    'SupplierID' => $res['SupplierID'],
                    'fBuyerPaid' => $res['fBuyerPaid'],
                    'fRefundApply' => $arr['fMoney'],
                    'sExplain' => $arr['sContent'],
                    'imgList' => '',
                    'lItemTotal' => $arr['lTotalNum'],
                    'lRefundItem' => $arr['lNum'],
                    'fRefundReal' =>$arr['fMoney'],
                    'fRefundProduct' => $arr['fMoney'],
                    'BuyerID' => $res['BuyerID']
                ]);
            }
        }
        return $arr;
    }
}