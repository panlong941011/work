<?php

namespace myerm\shop\common\models;

use myerm\common\components\CommonEvent;


/**
 * 经销商订单
 */
class SellerOrder extends ShopModel
{
    const SERVICE_PRECENT = 0.01;//服务费百分比

    /**
     *  订单成功后的响应事件
     * @param CommonEvent $event
     */
    public static function saveOrder(CommonEvent $event)
    {
        $order = $event->extraData;

        $sellerOrder = new static();
        $sellerOrder->OrderID = $order->lID;
        $sellerOrder->dNewDate = $order->dNewDate;
        $sellerOrder->StatusID = $order->StatusID;

        //首先取链接的经销商
        $seller = \Yii::$app->frontsession->urlSeller;

        //如果是在平台下单，去取注册来源
        if (!$seller) {
            if ($order->member->FromMemberID) {
                $seller = \Yii::$app->seller->findByID($order->member->FromMemberID);
            }
        }

        if ($seller) {
            $sellerOrder->SellerID = $seller->lID;
            $sellerOrder->PathID = $seller->PathID;

            if ($seller->type && $seller->type->lSaleRate) {
                $sellerOrder->fSellerCommission = $order->fProfit * $seller->type->lSaleRate / 100;
                $sellerOrder->lSellerCommissionRate = $seller->type->lSaleRate;
            }

            if ($seller->UpID) {
                $sellerOrder->UpSellerID = $seller->UpID;
            }


            //计算代理提出
            $sellerOrder->fSellerCommission = $order->fProfit * 0.4;

            /* 计算代理分销提成，供应商结算金额 panlong 2019年9月11日11:30:54 开始 */
//            if ($seller->TypeID == Seller::JUNIOR) {
//                $fVipPrice = ($product->fPrice - $product->fSupplierPrice) * 0.4 + $product->fSupplierPrice;
//
//                //不是VIP自己下单，才会给VIP发放分成
//                if ($order->MemberID != $seller->MemberID) {
//                    //VIP提成为售价 - VIP价
//                    $fVipPrice = ($product->fPrice - $product->fSupplierPrice) * 0.4 + $product->fSupplierPrice;
//                    $sellerOrder->fSellerCommission = ($product->fPrice - $fVipPrice)*$lQuantity;
//                }
//
//                //顶级提成为VIP价-进货价-服务费（订单总价1%）
//                $fPrice = $fVipPrice - $product->fSupplierPrice;
//                $sellerOrder->fUpSellerCommission = $fPrice*$lQuantity;
//            }
//            elseif ($seller->TypeID == Seller::TOP) {
//                //顶级代理自己下单不计算提成
//                if ($order->MemberID != $seller->MemberID) {
//                    $fPrice = $product->fPrice - $product->fSupplierPrice;
//                    $sellerOrder->fSellerCommission = $fPrice*$lQuantity;
//                }
//            }
            /* 计算代理分销提成，供应商结算金额 panlong 2019年9月11日11:30:54 结束 */
        }

        $sellerOrder->save();
    }

    /**
     * 退款成功事件，需要重新计算提成
     * @param CommonEvent $event
     */
    public static function refundSuccess(CommonEvent $event)
    {
        $refund = $event->extraData;

        $sellerOrder = static::findOne($refund->OrderID);

        if ($sellerOrder->SellerID) {
            $sellerOrder->fSellerCommission = $refund->order->fProfit * $sellerOrder->lSellerCommissionRate / 100;
        }

        if ($sellerOrder->UpSellerID) {
            $sellerOrder->fUpSellerCommission = $refund->order->fProfit * $sellerOrder->lUpSellerCommissionRate / 100;
        }

        if ($sellerOrder->UpUpSellerID) {
            $sellerOrder->fUpUpSellerCommission = $refund->order->fProfit * $sellerOrder->lUpUpSellerCommissionRate / 100;
        }

        $sellerOrder->save();
    }

    /**
     * 获取订单明细
     */
    public function getArrDetail()
    {
        return $this->hasMany(OrderDetail::className(), ['OrderID' => 'OrderID']);
    }

    /**
     * 关联订单
     * @return \yii\db\ActiveQuery
     */
    public function getOrder()
    {
        return $this->hasOne(Order::className(), ['lID' => 'OrderID'])->with('arrDetail')->with('member')->with('orderAddress');
    }

    /**
     * 获取订单来源
     */
    public function getSeller()
    {
        return $this->hasOne(Seller::className(), ['lID' => 'SellerID']);
    }


    public function getUpSeller()
    {
        return $this->hasOne(Seller::className(), ['lID' => 'UpSellerID']);
    }

    public function getUpUpSeller()
    {
        return $this->hasOne(Seller::className(), ['lID' => 'UpUpSellerID']);
    }

    /**
     * 实时计算未结算金额，状态是已付款和递送中的订单
     */
    public function computeUnsettlement($SellerID, $sType = 'all')
    {
        $fCommission = 0.00;

        if ($sType == 'all' || $sType == 'seller') {
            $fCommission += static::find()->where([
                'SellerID' => $SellerID,
                'StatusID' => ['paid', 'delivered']
            ])->sum('fSellerCommission');
        }

        if ($sType == 'all' || $sType == 'first') {
            $fCommission += static::find()->where([
                'UpSellerID' => $SellerID,
                'StatusID' => ['paid', 'delivered']
            ])->sum('fUpSellerCommission');
        }

        if ($sType == 'all' || $sType == 'second') {
            $fCommission += static::find()->where([
                'UpUpSellerID' => $SellerID,
                'StatusID' => ['paid', 'delivered']
            ])->sum('fUpUpSellerCommission');
        }

        return floatval($fCommission);
    }


    /**
     * 实时计算已结算金额，状态是交易成功的订单
     */
    public function computeSttlement($SellerID, $sType = 'all')
    {
        $fCommission = 0.00;

        if ($sType == 'all' || $sType == 'seller') {
            $fCommission += static::find()->where([
                'SellerID' => $SellerID,
                'StatusID' => 'success'
            ])->sum('fSellerCommission');
        }

        if ($sType == 'all' || $sType == 'first') {
            $fCommission += static::find()->where([
                'UpSellerID' => $SellerID,
                'StatusID' => 'success'
            ])->sum('fUpSellerCommission');
        }

        if ($sType == 'all' || $sType == 'second') {
            $fCommission += static::find()->where([
                'UpUpSellerID' => $SellerID,
                'StatusID' => 'success'
            ])->sum('fUpUpSellerCommission');
        }

        return floatval($fCommission);
    }

    /**
     * 待结算列表
     */
    public function unsettlementList($config)
    {
        $orderSearch = static::find();

        if ($config['type'] == '全部' || !$config['type']) {
            $orderSearch->andWhere([
                'OR',
                ['SellerID' => \Yii::$app->frontsession->seller->lID],
                ['UpSellerID' => \Yii::$app->frontsession->seller->lID],
                ['UpUpSellerID' => \Yii::$app->frontsession->seller->lID],
            ]);
        } elseif ($config['type'] == '销售提成') {
            $orderSearch->andWhere(['SellerID' => \Yii::$app->frontsession->seller->lID]);
        } elseif ($config['type'] == '一级团队提成') {
            $orderSearch->andWhere(['UpSellerID' => \Yii::$app->frontsession->seller->lID]);
        } elseif ($config['type'] == '二级团队提成') {
            $orderSearch->andWhere(['UpUpSellerID' => \Yii::$app->frontsession->seller->lID]);
        }

        $orderSearch->andWhere(['StatusID' => ['paid', 'delivered']]);

        $orderSearch->limit(10);

        $page = $config['page'] ? intval($config['page']) : 1;
        $orderSearch->offset(($page - 1) * 10);
        $orderSearch->orderBy('dNewDate DESC');
        $orderSearch->with('arrDetail');

        return $orderSearch->all();
    }

    /**
     * 已结算列表
     * @param $config
     * @return array|\yii\db\ActiveRecord[]
     */
    public function settlementList($config)
    {
        $orderSearch = static::find();

        if ($config['type'] == '全部' || !$config['type']) {
            $orderSearch->andWhere([
                'OR',
                ['SellerID' => \Yii::$app->frontsession->seller->lID],
                ['UpSellerID' => \Yii::$app->frontsession->seller->lID],
                ['UpUpSellerID' => \Yii::$app->frontsession->seller->lID],
            ]);
        } elseif ($config['type'] == '销售提成') {
            $orderSearch->andWhere(['SellerID' => \Yii::$app->frontsession->seller->lID]);
        } elseif ($config['type'] == '一级团队提成') {
            $orderSearch->andWhere(['UpSellerID' => \Yii::$app->frontsession->seller->lID]);
        } elseif ($config['type'] == '二级团队提成') {
            $orderSearch->andWhere(['UpUpSellerID' => \Yii::$app->frontsession->seller->lID]);
        }

        $orderSearch->andWhere(['StatusID' => 'success']);

        $orderSearch->limit(10);

        $page = $config['page'] ? intval($config['page']) : 1;
        $orderSearch->offset(($page - 1) * 10);
        $orderSearch->orderBy('dNewDate DESC');
        $orderSearch->with('arrDetail');

        return $orderSearch->all();
    }


    /**
     *  经销中心，按客户来取数据
     */
    public function orderList($config)
    {
        $sKeyword = $config['sKeyword'];
        $StatusID = $config['StatusID'];
        $lPage = intval($config['lPage']) > 1 ? intval($config['lPage']) : 1;

        $r = static::find();

        if ($config['sRange'] == 'all') {
            $r->andWhere([
                'OR',
                ['SellerID' => \Yii::$app->frontsession->seller->lID],
                ['UpSellerID' => \Yii::$app->frontsession->seller->lID],
                ['UpUpSellerID' => \Yii::$app->frontsession->seller->lID],
            ]);
        } else {
            $r->andWhere(['SellerID' => \Yii::$app->frontsession->seller->lID]);
        }

        if ($StatusID) {
            $r->andWhere(['StatusID' => $StatusID]);
        }

        if (strlen($sKeyword) > 0) {
            $r->andWhere("OrderID IN (SELECT OrderID FROM OrderAddress WHERE OrderID=SellerOrder.OrderID AND (sName='" . addslashes($sKeyword) . "' OR sMobile='" . addslashes($sKeyword) . "'))");
        }


        $r->offset(($lPage - 1) * 10)->limit(10);
        $r->with('order')->with('arrDetail')->with('seller');
        $r->orderBy("dNewDate DESC");

        return $r->all();
    }

    /**
     * 关联订单
     * @return \yii\db\ActiveQuery
     */
    public function getAgentOrder()
    {
        return $this->hasOne(Order::className(), ['lID' => 'OrderID'])->with('arrDetail')->with('member');
    }
}