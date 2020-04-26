<?php

namespace myerm\shop\mobile\models;

use myerm\common\components\CommonEvent;
use myerm\common\components\Func;
use myerm\shop\common\models\Member;
use myerm\shop\common\models\OrderAddress;
use myerm\shop\common\models\PreOrder;
use myerm\shop\common\models\SecKill;
use myerm\shop\common\models\Seller;
use myerm\shop\common\models\SellerFlow;
use myerm\shop\common\models\SellerOrder;


/**
 * 订单类
 */
class Order extends \myerm\shop\common\models\Order
{
//unpaid=未付款
//paid=已付款
//delivered=已发货
//success=交易成功
//closed=交易关闭
//exception=付款异常
    /**
     * 保存订单
     */
    public function saveOrder($arrParam)
    {
        $return = [];
        //把失效的商品去除
        $checkoutProduct = \Yii::$app->ordercheckout->arrCheckoutProduct;
        $address = $arrParam['address'];
        $arrData = [];
        foreach ($checkoutProduct as $cProduct) {
            if ($cProduct->product->lStock < 1) {
                $return['status'] = false;
                $return['strTradeNo'] = '';
                $return['message'] = $cProduct->product->sName . '库存不足！';
                return $return;
            }
            $arrData[$cProduct->product->SupplierID][] = $cProduct;
        }
        //根据供应商分组生成订单

//        $product = $checkoutProduct->product;
//        $fPrice = $product->fSalePrice;
//        $fSumProduct = $fPrice * $checkoutProduct->lQuantity;
//
//        //服务费收取供应商供货价的 1%
//        $fService = Func::numbleFormat($product->fSupplierPrice * $checkoutProduct->lQuantity * 0.01);

        $strTradeNo = 'ACT' . date('YmdHis') . rand(10000, 99999);
        //保存订单主体
        foreach ($arrData as $key => $checkoutProduct) {
            $order = new static();
            $order->sTradeNo = $strTradeNo;
            $order->sName = date('YmdHis') . rand(10000, 99999);
            $order->dNewDate = \Yii::$app->formatter->asDatetime(time());
            $order->dEditDate = $order->dNewDate;
            $order->MemberID = \Yii::$app->frontsession->MemberID;
            $order->SupplierID = $key;
            $order->StatusID = static::STATUS_UNPAID;
            $order->fShip = $arrParam['fShipMoney'][$key];//渠道商品 客户运费与供应商运费一致@TODO
            $order->fSumOrder = $order->fShip;
            $order->fSupplierShip = $order->fShip;
            $order->fPaid = 0;//实付款，它等于实际付款的金额
            $order->sMessage = $arrParam['arrMessage'];
            $order->bCloud = 1;
            $order->sIP = \Yii::$app->request->userIP;
            $order->bHasSaved = 0;//Mars，2019年3月29日14:50:40，设置成0，数据库查询会更高效
            $order->OrderType = $arrParam['OrderType'];
            $order->fService = 0;
            $order->save();
            //@TODO 此处为应急处理暂时只上 冻品在线商品
            foreach ($checkoutProduct as $cProduct) {
                $product = $cProduct->product;
                $orderDetail = new OrderDetail();
                $orderDetail->sName = $product->sName;
                $orderDetail->ProductID = $product->lID;
                $orderDetail->OrderID = $order->lID;
                $orderDetail->sPic = $cProduct->sPic;
                $orderDetail->sSKU = $cProduct->sSKU;
                $orderDetail->lQuantity = $cProduct->lQuantity;
                $orderDetail->fPrice = $product->fGroupPrice;

                $orderDetail->fTotal = $product->fGroupPrice * $cProduct->lQuantity;
                $orderDetail->fProfit = ($product->fPrice - $product->fSelfProfit - $product->fSupplierPrice) * $cProduct->lQuantity;
                $order->fProfit += $orderDetail->fProfit;
                //$orderDetail->fShip = $arrParam['fShip'];
                //$orderDetail->fSupplierShip = $arrParam['fShip'];

                $order->fSumOrder += $orderDetail->fTotal;//商品总金额+运费
                $order->fSumProduct += $orderDetail->fTotal;//商品总金额=商品总金额
                //商品抵用券
                $redProduct = Redbagproduct::find()->where(['and', ['MemberID' => $order->MemberID], ['>', 'dEnd', $order->dNewDate], ['ProductID' => $product->lID], ['>', 'lCount', 0]])->one();
                if ($redProduct) {
                    //判断是否是代理，代理不限制抵用券
                    $lCount = $orderDetail->lQuantity;
                    $seller = Seller::findOne(['lID' => $order->MemberID]);
                    if (!$seller) {
                        $lCount = $redProduct->lCount > $orderDetail->lQuantity ? $orderDetail->lQuantity : $redProduct->lCount;
                    }
                    $order->fSumOrder -= $redProduct->fChange * $lCount;//抵扣金额
                }
                $orderDetail->save();
                $product->lStock -= $orderDetail->lQuantity;
                if ($product->lStock < 0) {
                    $product->lStock = 0;
                }
                $product->save();
                $event = new CommonEvent();
                $event->extraData = $orderDetail;//传值订单明细
                \Yii::$app->orderdetail->trigger(static::EVENT_SAVE_ORDER, $event);
                //清除购物车
                $cart = Cart::findOne($cProduct->CartID);
                if ($cart) {
                    $cart->delete();
                }
            }
            $order->fDue = $order->fSumOrder;//应付款，它等于订单总金额-实际付款的金额
            //满减券
            if ($arrParam['redbagID']) {
                $redbagID = $arrParam['redbagID'];
                $redbag = Redbag::find()->where(['and', ['MemberID' => \Yii::$app->frontsession->MemberID], ['bUserd' => 0], ['lID' => $redbagID]])->one();
                if ($redbag && 0) {
                    $order->fSumOrder -= $redbag->fChange;
                    $order->fDue -= $redbag->fChange;
                    $redbag->bUserd = 1;
                    $redbag->OrderID = $order->lID;
                    $redbag->save();
                }
            }
            $order->save();
            $orderAddress = new OrderAddress();
            $orderAddress->sName = $address->sName;
            $orderAddress->OrderID = $order->lID;
            $orderAddress->MemberID = \Yii::$app->frontsession->MemberID;
            $orderAddress->ProvinceID = $address->ProvinceID;
            $orderAddress->CityID = $address->CityID;
            $orderAddress->AreaID = $address->AreaID;
            $orderAddress->sAddress = $address->sAddress;
            $orderAddress->sMobile = $address->sMobile;
            $orderAddress->save();

            //保存引用订单收货的地址
            $order->OrderAddressID = $orderAddress->lID;
            $order->save();
            // 团购
            if ($checkoutProduct[0]['GroupID']) {
                $groupMember = new GroupMember();
                $groupMember->MemberID = $order->MemberID;
                $groupMember->GroupID = $checkoutProduct[0]['GroupID'];
                $groupMember->OrderID = $order->lID;
                $groupMember->save();
            }
            $event = new CommonEvent();
            $event->extraData = $order;//传值订单明细
            \Yii::$app->order->trigger(static::EVENT_SAVE_ORDER, $event);
        }
        $return['status'] = true;
        $return['strTradeNo'] = $strTradeNo;
        return $return;
    }

    /**
     * 微信支付成功处理
     * @param $arrNotify
     */
    public function wxPaySuccess()
    {
        //发放待结算流水 panlong 2019年9月17日09:16:56
        $sellerOrder = SellerOrder::findOne(['OrderID' => $this->lID]);
        if ($sellerOrder) {
            //发放vip待结算提成流水
            if ($sellerOrder->fSellerCommission > 0 || $sellerOrder->fUpSellerCommission > 0) {
                SellerFlow::setWithdrawFlow([
                    'fMoney' => $sellerOrder->fSellerCommission ? $sellerOrder->fSellerCommission : $sellerOrder->fUpSellerCommission,
                    'order' => $this,
                    'seller' => $sellerOrder->seller
                ]);
            }

        }
        //真实库存
        $arrDetail = $this->arrDetail;
        foreach ($arrDetail as $detail) {
            $product = $detail->product;
            $product->lStockReal -= $detail->lQuantity;
            if ($product->lStockReal < 10 && $product->lStock > $product->lStockReal) {
                $product->lStock = $product->lStockReal;
            }
            if ($product->lStock < 0) {
                $product->lStock = 0;
            }
            $product->save();
            //判断是否有使用商品券
            if ($this->fSumProduct > $this->fSumOrder) {
                $seller = Seller::findOne(['lID' => $this->MemberID]);
                if (!$seller) {
                    $redProduct = Redbagproduct::find()->where(['and', ['MemberID' => $this->MemberID], ['>', 'dEnd', $this->dNewDate], ['ProductID' => $product->lID], ['>', 'lCount', 0]])->one();
                    if ($redProduct) {
                        $redProduct->lCount -= (int)($this->fSumProduct - $this->fSumOrder) / $redProduct->fChange;
                        $redProduct->save();
                    }
                }
            }
        }
        return true;
    }

    /**
     * 金币支付成功处理
     * @param $arrNotify
     */
    public function goldPaySuccess($sTradeNo)
    {
        $this->sTradeNo = $sTradeNo;
        $this->StatusID = static::STATUS_PAID;
        $this->fDue = 0;
        $this->fPaid = $this->fSumOrder;
        $this->PaymentID = 'gold';
        $this->dPayDate = \Yii::$app->formatter->asDatetime(time());
        $this->save();

        return true;
    }

    /**
     * 微信支付失败处理
     * @param $arrNotify
     */
    public function wxPayFail($arrNotify)
    {
        $this->sTradeNo = $arrNotify['out_trade_no'];
        $this->StatusID = static::STATUS_EXCEPTION;
        $this->save();

        return true;
    }

    /**
     *  个人中心里，按会员来取数据
     */
    public function memberList($config)
    {
        $sKeyword = $config['sKeyword'];
        $MemberID = $config['MemberID'];
        $StatusID = $config['StatusID'];
        $lPage = intval($config['lPage']) > 1 ? intval($config['lPage']) : 1;

        $r = static::find()->where(['MemberID' => $MemberID]);

        if ($StatusID) {
            $r->andWhere(['StatusID' => $StatusID]);
            if ($StatusID == 'paid') {
                $r->andWhere(['RefundStatusID' => null]);
            }
        }

        if (strlen($sKeyword) > 0) {
            $r->andWhere("lID IN (SELECT OrderID FROM OrderAddress WHERE MemberID='$MemberID' AND (sName='" . addslashes($sKeyword) . "' OR sMobile='" . addslashes($sKeyword) . "'))");
        }

        $lCount = $r->count();

        $r->offset(($lPage - 1) * 10)->limit(10);
        $r->with('arrDetail');
        $r->orderBy("dNewDate DESC");
        $arrOrder = $r->all();

        return [$lCount, $arrOrder];
    }

    /**
     * 判断是否云订单
     * @param string 交易号
     * @return bool
     * */
    public function isCloudOrder($sTradeNo)
    {
        $isCloudOrder = static::find()->where(['and', ['=', 'sTradeNo', $sTradeNo], ['=', 'bCloud', 1], ['is', 'bHasSaved', null]])->one();
        if (!empty($isCloudOrder->lID)) {
            return $isCloudOrder->lID;
        } else {
            return false;
        }
    }

    /**
     * 修改升级大礼包订单数据
     * @param $No string 微信支付交易号
     * @author panlong
     * @time 2018-6-27 16:10:26
     */
    public function editUpgrade($config)
    {
        $this->MemberID = $config['MemberID'];
        $this->fShip = 0;
        $this->fSupplierShip = 0;
        $this->fProfit = 0;
        $this->fPaid = $this->fSumProduct = $this->fSumOrder = \Yii::$app->orderdetail->sumTotal($this->lID);
        $this->save();
    }

    /**
     * 是否大礼包订单
     * @author panlong
     * @time 2018-7-2 16:36:03
     */
    public function getBGift()
    {
        if (strstr($this->sName, 'J')) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * 云订单扣款
     * @author Mars
     * @time 2019年3月29日14:26:37
     */
    public function cloudOrderConfirmPay()
    {
        $arrResult = [];
        $arrCloudOrder = static::find()->select(['lID', 'sName', 'bHasSaved'])->where("dPayDate IS NOT NULL AND bCloud=1 AND bHasSaved=0")->all();
        foreach ($arrCloudOrder as $cloudOrder) {
            $result = \Yii::$app->dnyapi->orderConfirmPay($cloudOrder->sName);

            if ($result['status'] == 10000 || $result['status'] == 10046) {
                $cloudOrder->bHasSaved = 1;
                $cloudOrder->save();

                $arrResult['success'][] = $cloudOrder->sName;
            } elseif ($result['status'] == 10001) {//超时未付款，云订单已关闭，退款给客户
                foreach ($cloudOrder->arrDetail as $orderDetail) {
                    $fTotalPrice = \Yii::$app->orderdetail->countProductPrice($orderDetail->OrderID, $orderDetail->ShipTemplateID);//订单里相同运费模板的商品总价
                    $fRefundMoney = $orderDetail->fTotal + ($orderDetail->fTotal / $fTotalPrice * $orderDetail->fShip);//退款金额，商品总价加上相应比例的运费
                    //生成退款申请
                    $arrRefund = \Yii::$app->refund->saveRefund([
                        'TypeID' => 'onlymoney',
                        'sReason' => '超时未付款，云订单已关闭',
                        'OrderDetailID' => $orderDetail->lID,
                        'fRefundApply' => $fRefundMoney,
                        'sExplain' => '',
                        'imgList' => '',
                        'lItemTotal' => $orderDetail->lQuantity,
                        'lRefundItem' => $orderDetail->lQuantity,
                        'fRefundReal' => $fRefundMoney,
                    ]);
                    //同意退款申请
                    \Yii::$app->refund->agreeApply($arrRefund['RefundID']);
                }

                $cloudOrder->bHasSaved = 1;
                $cloudOrder->save();

                $arrResult['refund'][] = $cloudOrder->sName;
            } else {
                $arrResult['error'][] = $cloudOrder->sName;
            }
        }

        return $arrResult;
    }

    /*
     * 获取订单详情
     */
    public function getOrderDetail()
    {
        return OrderDetail::findOne(['OrderID' => $this->lID]);
    }

    /*
     * 获取订单详情
     */
    public function getPayLog()
    {
        return OrderPayLog::findOne(['sOrderID' => $this->lID]);
    }

    public function getDetail()
    {
        return $this->hasOne(OrderDetail::className(), ['OrderID' => 'lID']);
    }

    public function getSellerFlow()
    {
        return $this->hasOne(SellerFlow::className(), ['OrderID' => 'lID']);
    }

    public function getAddress()
    {
        return $this->hasOne(OrderAddress::className(), ['OrderID' => 'lID'])->with('province')->with('city')->with('area');
    }

    /**
     *  个人中心里，发货提醒
     */
    public function memberShipOrder($supplerID)
    {
        return self::find()
            ->where(['StatusID' => 'paid', 'SupplierID' => $supplerID])
            ->with('detail')
            ->with('address')
            ->all();
    }

    /**
     *  个人中心里，收益订单
     */
    public function getProfitorder($config)
    {
        $MemberID = $config['MemberID'];
        $StatusID = $config['StatusID'];
        $lPage = intval($config['lPage']) > 1 ? intval($config['lPage']) : 1;
        $res = SellerOrder::find()->select('OrderID')
            ->where(['or', ['SellerID' => $MemberID], ['UpSellerID' => $MemberID]])
            ->asArray()
            ->offset(($lPage - 1) * 10)
            ->limit(10)
            ->orderBy('OrderID desc');
        if ($StatusID) {
            $res->andWhere(['StatusID' => $StatusID]);
        }
        $res->andWhere(['<>', 'StatusID', 'closed']);

        $sellerOrder = $res->all();
        $orderIDList = array_column($sellerOrder, 'OrderID');
        $r = static::find()->where(['lID' => $orderIDList]);

        if ($StatusID) {
            $r->andWhere(['StatusID' => $StatusID]);
        }
        $r->with('arrDetail');
        $r->with('member');
        $r->with('seller');
        $this->MemberID = $MemberID;
        $r->with(['sellerFlow' => function ($query) {
            $query->where(['SellerID' => $this->MemberID]);
        }]);
        $r->orderBy("dNewDate DESC");
        $arrOrder = $r->all();

        return $arrOrder;
    }

    public function getMember()
    {
        return $this->hasOne(Member::className(), ['lID' => 'MemberID']);
    }

    public function getSeller()
    {
        return $this->hasOne(SellerOrder::className(), ['OrderID' => 'lID'])->with('seller');
    }

    /**
     *  个人中心里，收益订单
     */
    public function getSellerOrder($memberID, $lPage)
    {
        return SellerFlow::find()
            ->where(['TypeID' => 1, 'SellerID' => $memberID])
            ->with('sellerOrder')
            ->with('order')
            ->offset(($lPage - 1) * 10)
            ->limit(10)
            ->orderBy('lID desc')
            ->all();
    }
}