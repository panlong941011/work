<?php

namespace myerm\shop\mobile\models;

use myerm\common\components\CommonEvent;
use myerm\common\components\Func;
use myerm\shop\common\models\DealFlow;
use myerm\shop\common\models\OrderAddress;
use myerm\shop\common\models\PreOrder;
use myerm\shop\common\models\SecKill;


/**
 * 订单类
 */
class CloudOrder extends \myerm\shop\common\models\Order
{
    const BUYER = 1;//采购商ID

    /**
     * 配置数据源
     * @return \yii\db\Connection
     */
    public static function getDb()
    {
        return \Yii::$app->get('ds_cloud');
    }

    public static function tableName()
    {
        return '{{order}}';
    }

    /**
     * 保存云端订单
     * @param $order object 订单
     * @author panlong
     * @time 2019年9月16日16:53:35
     */
    public static function saveOrder($order)
    {
        $arrDetail = OrderDetail::find()->where(['OrderID' => $order->lID])->all();
        $address = $order->orderAddress;


        //保存订单主体
        $cloudOrder = new self();
        $cloudOrder->sName = $order->sName;
        $cloudOrder->sClientSN = $order->sName;
        $cloudOrder->dNewDate = $order->dNewDate;
        $cloudOrder->dEditDate = $order->dEditDate;
        $cloudOrder->SupplierID = $order->SupplierID;
        $cloudOrder->BuyerID = 0;
        $cloudOrder->StatusID = 'paid';
        $cloudOrder->fShip = $order->fShip;

        $cloudOrder->fProfit = 0;
        $cloudOrder->sMessage = $order->sMessage;
        $cloudOrder->PurchaseID = 0;
        $cloudOrder->WholesalerID = 0;
        $cloudOrder->TypeID = 'group';
        //sBuyerSource,sShop,sBuyer
        $sellerOrder = $order->seller;
        if ($sellerOrder->UpSellerID == 44) {
            $cloudOrder->sBuyerSource = '黄则和';
        }
        $cloudOrder->sShop = $sellerOrder->seller->sName;
        if ($sellerOrder->SellerID == $order->MemberID) {
            $cloudOrder->sBuyer = $sellerOrder->seller->sName;
        } else {
            $cloudOrder->sBuyer = $order->member->sName;
        }

        $cloudOrder->save();

        //保存订单详情
        foreach ($arrDetail as $detail) {
            $product = $detail->product;
            $orderDetail = new CloudOrderDetail();
            $orderDetail->sName = $detail->sName;
            $orderDetail->OrderID = $cloudOrder->lID;
            $orderDetail->dNewDate = Func::getDate();
            $orderDetail->dEditDate = Func::getDate();
            $orderDetail->BuyerID = 0;
            $orderDetail->ProductID = $detail->ProductID;
            $orderDetail->ShipTemplateID = $detail->ShipTemplateID;
            $orderDetail->sPic = $detail->sPic;
            $orderDetail->lQuantity = $detail->lQuantity;
            $orderDetail->fBuyerSalePrice = $product->fSupplierPrice;
            $orderDetail->fBuyerPrice = $product->fSupplierPrice;
            $orderDetail->fSupplierPrice = $product->fSupplierPrice;
            $orderDetail->fBuyerPaidTotal = $product->fSupplierPrice;
            $orderDetail->fSupplierIncomeTotal = $product->fSupplierPrice;
            $orderDetail->save();

            $cloudOrder->fSupplierProductIncome += $product->fSupplierPrice * $detail->lQuantity;
        }
        $cloudOrder->fBuyerPaid = $order->fSumOrder;
        $cloudOrder->fSupplierIncome = $cloudOrder->fSupplierProductIncome + $order->fShip;
        $cloudOrder->fBuyerProductPaid = $cloudOrder->fSupplierIncome;
        $cloudOrder->save();
        //保存收货地址
        $orderAddress = new CloudOrderAddress();
        $orderAddress->sName = $address->sName;
        $orderAddress->dNewDate = Func::getDate();
        $orderAddress->dEditDate = Func::getDate();
        $orderAddress->OrderID = $cloudOrder->lID;
        $orderAddress->ProvinceID = $address->ProvinceID;
        $orderAddress->CityID = $address->CityID;
        $orderAddress->AreaID = $address->AreaID;
        $orderAddress->sAddress = $address->sAddress;
        $orderAddress->sMobile = $address->sMobile;
        $orderAddress->save();

        //保存引用订单收货的地址
        $cloudOrder->OrderAddressID = $orderAddress->lID;
        $cloudOrder->save();
        return 1;
    }

    //订单详情
    public function getDetail()
    {
        return $this->hasOne(CloudOrderDetail::className(), ['OrderID' => 'lID']);
    }

    public function getAddress()
    {
        return $this->hasOne(CloudOrderAddress::className(), ['OrderID' => 'lID'])->with('province')->with('city')->with('area');
    }

    /**
     * 确认收货
     */
    public function confirmReceive()
    {

        $this->dReceiveDate = \Yii::$app->formatter->asDatetime(time());
        $this->StatusID = static::STATUS_SUCCESS;
        $this->save();

        /* 生成供应商账户流水记录 */
        $data = [];
        $data['sName'] = '订单号' . $this->sName . '收入';
        $fMoney = $this->fSupplierIncome - $this->fSupplierRefund;
        if ($fMoney < 0) {
            $fMoney = 0;
        }
        $data['fMoney'] = $fMoney;//变动金额
        $data['order'] = $this->sName;
        $data['MemberID'] = $this->SupplierID;//供应商ID
        $data['RoleType'] = 'supplier';//身份标识
        $data['TypeID'] = DealFlow::$TypeID['income'];//交易类型
        $data['DealID'] = $this->lID;//对应流水类型ID
        $SupplierID = $this->SupplierID;
        $waitMoney = self::find()
            ->where("StatusID='delivered' and SupplierID='$SupplierID'")
            ->sum('fSupplierIncome');
        $data['waitMoney'] = $waitMoney;
        \Yii::$app->dealflow->change($data);
    }
}