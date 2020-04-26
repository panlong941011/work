<?php

namespace console\controllers;

use myerm\shop\common\models\Refund;
use myerm\shop\common\models\Buyer;
use myerm\shop\common\models\Product;
use myerm\shop\common\models\Supplier;
use myerm\shop\common\models\OrderDetail;
use myerm\shop\common\models\RefundMoneyBack;
use yii\console\Controller;

/**
 * 退货售后处理
 * ============================================================================
 * 版权所有 2010-2017 三只象（厦门）网络科技有限公司，并保留所有权利。
 * 网站地址: http://www.myerm.cn
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和
 * 使用；不允许对程序代码以任何形式任何目的的再发布。
 * ============================================================================
 * @author 陈鹭明  <lumingchen@qq.com>
 * @since 2017年11月10日 09:13:35
 * @version v1.0
 */
class RefundController extends Controller
{
    /**
     * 超时处理
     */
    public function actionTimeout()
    {
        \Yii::$app->refund->timeout();
        return static::EXIT_CODE_NORMAL;
    }

    /**
     * 提交退款申请到第三方支付
     */
    public function actionSubmit()
    {
        RefundMoneyBack::reset();
        RefundMoneyBack::query();
        RefundMoneyBack::submit();
        return static::EXIT_CODE_NORMAL;
    }

    /**
     * API生成退款申请
     */
    public function actionReceiverefund($param)
    {
        try {
            $data = json_decode(base64_decode($param), true);

            /* 获取订单ID */
            $order = \Yii::$app->order->getByClientNoAndProduct($data['sName'], $data['ProductID']);
            $data['OrderID'] = $order->lID;

            /* 获取渠道商ID */
            $data['BuyerID'] = $order->BuyerID;

            //如果订单已关闭，则不接受
            if ($order->StatusID == 'success') {
                return static::EXIT_CODE_NORMAL;
            }

            /* 获取供应商ID 退货地址 */
            $product = Product::findByID($data['ProductID']);
            $data['SupplierID'] = $product->SupplierID;

            $supplier = Supplier::findByID($product->SupplierID);
            $data['sAddress'] = $supplier->sRefundAddress;

            $data['StatusID'] = 'wait';

            /* 获取订单详情ID */
            $orderDetail = OrderDetail::find()->where(['OrderID' => $data['OrderID'], 'ProductID' => $data['ProductID']])->one();
            $data['OrderDetailID'] = $orderDetail->lID;
            $data['fBuyerPaidTotal'] = $orderDetail->fBuyerPaidTotal;
            $data['fSupplierIncomeTotal'] = $orderDetail->fSupplierIncomeTotal;
            $data['ShipTemplateID'] = $orderDetail->ShipTemplateID;
            $data['fBuyerRefund'] = 0;
            $data['fBuyerRefundProduct'] = 0;
            $data['fSupplierRefund'] = 0;
            $data['fSupplierRefundProduct'] = 0;

            /*  如果未发货，计算退款建议值 开始 */
            if (!$orderDetail->sShipNo) {
                /* 根据商品价格占订单中同类运费模板商品总价比例退还运费 */
                $fTotalPrice = \Yii::$app->orderdetail->countProductPrice($orderDetail->OrderID, $orderDetail->ShipTemplateID);
                $fTotalShip = $orderDetail->fShip * ($orderDetail->fBuyerPaidTotal / $fTotalPrice);

                $data['fTotalShip'] = $fTotalShip;
                $data['fBuyerRefund'] = $orderDetail->fBuyerPaidTotal + $fTotalShip;
                $data['fBuyerRefundProduct'] = $orderDetail->fBuyerPaidTotal;
                $data['fSupplierRefund'] = $orderDetail->fSupplierIncomeTotal + $fTotalShip;
                $data['fSupplierRefundProduct'] = $orderDetail->fSupplierIncomeTotal;
            }
            /* 如果未发货，计算退款建议值 结束 */

            \Yii::$app->refund->saveRefund2($data);
            echo json_encode(['code' => 1, 'data' => 'SUCCESS']);
            return static::EXIT_CODE_NORMAL;
        } catch (\Exception $e) {
            echo json_encode(['code' => -1, 'data' => $e->getMessage()]);
            return static::EXIT_CODE_NORMAL;
        }
    }

    /**
     * API修改订单状态
     */
    public function actionSetorderrefund($param)
    {
        $data = json_decode(base64_decode($param), true);
        $order = \Yii::$app->order->getByClientNoAndProduct($data['sName'], $data['ProductID']);
        $OrderDetail = \Yii::$app->orderdetail->findByOandP($order->lID, $data['ProductID']);
        /* 修改订单详情状态为退款中 */
        \Yii::$app->orderdetail->setRefund($OrderDetail->lID);
        $order->setRefund();
        return static::EXIT_CODE_NORMAL;
    }


    /**
     * API撤销退款申请
     */
    public function actionCancelrefund($param)
    {
        try {
            $data = json_decode(base64_decode($param), true);

            $order = \Yii::$app->order->getByClientNoAndProduct($data['sName'], $data['ProductID']);
            $orderDetail = \Yii::$app->orderdetail->findByOandP($order->lID, $data['ProductID']);
            $Refund = \Yii::$app->refund->findByOrderDetailID($orderDetail->lID);
            if ($Refund->lID) {
                \Yii::$app->refund->cancelApply($Refund->lID);
            } else {
                $orderDetail->cancelRefund();
                $order->cancelRefund();
            }

            $result = [];
            $result['code'] = '100';
            $result['data'] = 'success';
            echo json_encode($result);
            return static::EXIT_CODE_NORMAL;
        } catch (\Exception $e) {
            $result = [];
            $result['code'] = '-1';
            $result['data'] = $e;
            echo json_encode($result);
            return static::EXIT_CODE_NORMAL;
        }
    }

}