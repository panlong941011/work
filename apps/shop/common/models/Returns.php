<?php

namespace myerm\shop\common\models;

use myerm\kuaidi100\models\ExpressCompany;

/**
 * 退货信息
 */
class Returns extends ShopModel
{
    const Delivered = 'delivered';
    const RECEIVED = 'received';
    const REFUSE = 'refuse';
    const CANCEL = 'cancel';
    const APPLY = 'wait';
    const RETURNSTYPE = 'moneyandproduct';


    /**
     * 关联订单明细
     * @return \yii\db\ActiveQuery
     */
    public function getOrderDetail()
    {
        return $this->hasOne(OrderDetail::className(), ['lID' => 'OrderDetailID']);
    }

    /**
     * 关联订单
     * @return \yii\db\ActiveQuery
     */
    public function getOrder()
    {
        return $this->hasOne(Order::className(), ['lID' => 'OrderID']);
    }


    /**
     * 关联供应商
     * @return \yii\db\ActiveQuery
     */
    public function getSupplier()
    {
        return $this->hasOne(Supplier::className(), ['lID' => 'SupplierID']);
    }

    /**
     * 关联渠道商
     * @return \yii\db\ActiveQuery
     */
    public function getBuyer()
    {
        return $this->hasOne(Buyer::className(), ['lID' => 'BuyerID']);
    }

    /**
     * 关联商品
     * @return \yii\db\ActiveQuery
     */
    public function getProduct()
    {
        return $this->hasOne(Product::className(), ['lID' => 'ProductID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     * @author panlong
     * @time 2018-6-13 15:14:06
     */
    public function getShipCompany()
    {
        return $this->hasOne(ExpressCompany::className(), ['ID' => 'ShipCompanyID']);
    }


    /**
     * 保存退货申请信息
     * @param array $arrData 退货信息
     * @author panlong
     * @time 2018-06-05
     */
    public function saveReturns($arrData)
    {
        $order = \Yii::$app->order->getByClientNoAndProduct($arrData['sName'], $arrData['ProductID']);
        if (!$order) {
            throw new \Exception('订单不存在');
        }

        $orderDetail = \Yii::$app->orderdetail->findByOandP($order->lID, $arrData['ProductID']);
        if (!$orderDetail) {
            throw new \Exception('订单详情不存在');
        }

        $returns = new static();
        $returns->sName = $order->sName;
        $returns->ProductID = $arrData['ProductID'];
        $returns->OrderID = $order->lID;
        $returns->OrderDetailID = $orderDetail->lID;
        $returns->SupplierID = $orderDetail->product->SupplierID;
        $returns->BuyerID = $orderDetail->order->BuyerID;
        $returns->StatusID = static::Delivered;
        $returns->lRefundItem = $arrData['lRefundItem'];
        $returns->lItemTotal = $arrData['lItemTotal'];
        $returns->fRefundPrice = $arrData['fRefundPrice'];
        $returns->fTotalPrice = $arrData['fTotalPrice'];
        $returns->sShipVoucher = json_encode($arrData['imgShipList']);
        $returns->sRefundVoucher = json_encode($arrData['imgRefundList']);
        $returns->sShipNo = $arrData['sShipNo'];
        $returns->ShipCompanyID = $arrData['ShipCompanyID'];
        $returns->sMobile = $arrData['sMobile'];
        $returns->sReason = $arrData['sReason'];
        $returns->sExplain = $arrData['sExplain'];
        $returns->dNewDate = \Yii::$app->formatter->asDatetime(time());
        $returns->dEditDate = \Yii::$app->formatter->asDatetime(time());
        $returns->save();
    }

    /**
     * 修改退货申请信息
     * @param array $arrData 退货信息
     * @author panlong
     * @time 2018-06-05
     */
    public function modifyReturns($arrData)
    {
        $order = \Yii::$app->order->getByClientNoAndProduct($arrData['sName'], $arrData['ProductID']);
        if (!$order) {
            throw new \Exception('订单不存在');
        }

        $orderDetail = \Yii::$app->orderdetail->findByOandP($order->lID, $arrData['ProductID']);
        if (!$orderDetail) {
            throw new \Exception('订单详情不存在');
        }

        $returns = static::find()->where(['OrderDetailID' => $orderDetail->lID])->one();
        $returns->StatusID = static::Delivered;
        $returns->sShipVoucher = json_encode($arrData['imgShipList']);
        $returns->sShipNo = $arrData['sShipNo'];
        $returns->ShipCompanyID = $arrData['ShipCompanyID'];
        $returns->dEditDate = \Yii::$app->formatter->asDatetime(time());
        $returns->save();
    }

    /**
     * 撤销退货申请信息
     * @param array $arrData 退货信息
     * @author panlong
     * @time 2018-06-05
     */
    public function cancelReturns($arrData)
    {
        $order = \Yii::$app->order->getByClientNoAndProduct($arrData['sName'], $arrData['ProductID']);
        if (!$order) {
            throw new \Exception('订单不存在');
        }

        $orderDetail = \Yii::$app->orderdetail->findByOandP($order->lID, $arrData['ProductID']);
        if (!$orderDetail) {
            throw new \Exception('订单详情不存在');
        }

        $orderDetail->StatusID = 'closed';
        $orderDetail->save();

        $order->RefundStatusID = '';
        $order->save();

        $returns = static::find()->where(['OrderDetailID' => $orderDetail->lID])->one();
        if ($returns) {
            $returns->StatusID = static::CANCEL;
            $returns->dEditDate = \Yii::$app->formatter->asDatetime(time());
            $returns->save();
        }
    }

    /**
     * 确认收货
     * @param $ID 退货ID
     * @author panlong
     * @time 2018-06-06
     */
    public function receive($ID)
    {
        if ($ID) {
            $returns = static::findByID($ID);
            $returns->StatusID = static::RECEIVED;
            $returns->save();
        } else {
            $this->StatusID = static::RECEIVED;
            $this->save();
        }
    }

    /**
     * 拒绝收货
     * @param $ID 退货ID
     * @author panlong
     * @time 2018-06-06
     */
    public function denyReceive($ID)
    {
        $returns = static::findByID($ID);
        $returns->StatusID = static::REFUSE;
        $returns->save();
    }

    /**
     * 超时计划任务
     * @author panlong
     * @time 2018-7-13 17:04:44
     */
    public function timeout()
    {
        //买家提交退货单，若卖家x天未处理，该退款申请将自动同意并退款
        $lTime = time() - MallConfig::getValueByKey('lRefundShipTimeOut') * 86400;
        $arrAgreeTimeout = static::find()->where(['StatusID' => 'delivered'])->andWhere([
            '<',
            'dEditDate',
            \Yii::$app->formatter->asDatetime($lTime)
        ])->all();
        foreach ($arrAgreeTimeout as $returns) {
            //确认收货
            $returns->receive();

            //将退货信息提取生成退款申请
            \Yii::$app->refund->saveRefund2([
                'ProductID' => $returns->ProductID,
                'BuyerID' => $returns->BuyerID,
                'SupplierID' => $returns->SupplierID,
                'StatusID' => static::APPLY,
                'TypeID' => static::RETURNSTYPE,
                'OrderID' => $returns->OrderID,
                'OrderDetailID' => $returns->OrderDetailID,
                'fBuyerPaidTotal' => $returns->orderDetail->fBuyerPaidTotal,
                'fSupplierIncomeTotal' => $returns->orderDetail->fSupplierIncomeTotal,
                'sReason' => $returns->sReason,
                'sExplain' => $returns->sExplain,
                'sAddress' => $returns->supplier->sRefundAddress,
                'ShipTemplateID' => $returns->product->ShipTemplateID,
                'lRefundItem' => $returns->lRefundItem,
                'lItemTotal' => $returns->lItemTotal,
                'imgList' => json_decode($returns->sRefundVoucher),
                'fRefundProduct' => $returns->fRefundPrice,
                'fProductPrice' => $returns->fTotalPrice,
            ]);
        }
    }
}