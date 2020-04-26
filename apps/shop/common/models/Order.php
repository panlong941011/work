<?php

namespace myerm\shop\common\models;

use myerm\backend\system\models\SysField;
use myerm\common\components\CommonEvent;
use myerm\common\components\Func;
use myerm\shop\mobile\models\CloudOrder;
use myerm\shop\mobile\models\OrderAddress;

/**
 * 订单类
 */
class Order extends ShopModel
{
    //未付款
    const STATUS_UNPAID = 'unpaid';

    //已付款
    const STATUS_PAID = 'paid';

    //已发货
    const STATUS_DELIVERED = 'delivered';

    //交易成功
    const STATUS_SUCCESS = 'success';

    //交易关闭
    const STATUS_CLOSED = 'closed';

    //付款异常
    const STATUS_EXCEPTION = 'exception';

    //付款成功
    const EVENT_PAY_SUCCESS = 'paysuccess';

    //下单
    const EVENT_SAVE_ORDER = 'saveorder';

    //自动关闭
    const EVENT_AUTO_CLOSE = 'autoclose';

    //确认收货，交易成功
    const EVENT_SUCCESS = 'success';

    //渠道订单
    const ORDER_TYPE_WHOLESALE = 'wholesale';
    const APPID = 'wxde428775a97e86be';//公众号appid
    const XCXAPPID = 'wxb7ad5f05ff005ccc';//小程序appid
    const MCHID = '1549064271';//商户号
    const KEY = '6sAlW88V7GWSCAZXCBo3SBiSIvXLVUZ6';//商户号密钥

    /**
     * 生成订单号，长格式的时间+随机码
     */
    public static function makeOrderCode()
    {
        return date('YmdHis') . rand(10000, 99999);
    }

    /**
     * 退款成功
     */
    public static function refundSuccess(CommonEvent $event)
    {

        $refund = $event->extraData;

        //把明细的退款金额统计，赋值订单主体的退款总金额
        $fRefundTotal = OrderDetail::find()->where([
            'OrderID' => $refund->OrderID,
            'StatusID' => 'success'
        ])->sum('fRefund');
        $refund->order->fRefund = $fRefundTotal;


        $bRefunding = false;
        $bAllRefunded = true;
        foreach ($refund->order->arrDetail as $detail) {
            if ($detail->bRefunding) {
                $bRefunding = true;
            }

            if ($detail->StatusID != 'success') {
                $bAllRefunded = false;
            }

            /* 如果有商品仅部分退款，则不关闭订单 */
            if ($detail->StatusID == 'success') {
                if (!$detail->refund->BFullRefund) {
                    $bAllRefunded = false;
                }
            }
        }

        /* 如果该商品只是部分退款，则不关闭订单 */
        if ($refund->lRefundItem != $refund->lItemTotal) {
            $bAllRefunded = false;
        }

        if (!$bRefunding) {
            $refund->order->RefundStatusID = null;
        }

        //如果全部退款成功了，订单的状态改为关闭
        if ($bAllRefunded) {
            $refund->order->StatusID = 'closed';
            $refund->order->dCloseDate = \Yii::$app->formatter->asDatetime(time());
            $refund->order->sCloseReson = '全额退款';
        }

        $refund->order->save();

        //更新订单状态是否为已发货
        $refund->order->updateShipStatus();

        //更新供应商待结算
        \Yii::$app->supplier->computeWaitMoney($refund->SupplierID);

    }

    /**
     * 退款关闭
     */
    public static function refundClosed(CommonEvent $event)
    {
        $refund = $event->extraData;
        foreach ($refund->order->arrDetail as $detail) {
            if ($detail->bRefunding) {
                return false;
            }
        }

        $refund->order->RefundStatusID = null;
        $refund->order->save();
    }

    /**
     * 退款申请
     */
    public static function refundApply(CommonEvent $event)
    {
        $refund = $event->extraData;
        $refund->order->RefundStatusID = 'refunding';
        $refund->order->save();
    }

    /**
     * 更新利润
     */
    public function updateProfit()
    {
//        $fProfit = OrderDetail::find()->where(['OrderID' => $this->lID])->sum("fProfit");
//        $this->fProfit = $fProfit;
//        $this->save();
    }

    /**
     * 自动关闭未付款的订单，计划任务
     */
    public function autoCloseUnpaid()
    {
        $lOrderAutoCloseTime = MallConfig::getValueByKey('lOrderAutoCloseTime');
        $arrOrder = static::find()->andWhere(['StatusID' => 'unpaid'])
            ->andWhere(['<', 'dNewDate', \Yii::$app->formatter->asDatetime(time() - $lOrderAutoCloseTime * 3600)])
            ->with('arrDetail')
            ->all();
        foreach ($arrOrder as $order) {
            foreach ($order->arrDetail as $detail) {
                $event = new CommonEvent();
                $event->extraData = $detail;//传值订单明细
                \Yii::$app->orderdetail->trigger(Order::EVENT_AUTO_CLOSE, $event);
            }

            $event = new CommonEvent();
            $event->extraData = $order;//传值订单明细
            \Yii::$app->order->trigger(Order::EVENT_AUTO_CLOSE, $event);
        }

        static::updateAll(
            [
                'StatusID' => Order::STATUS_CLOSED,
                'dCloseDate' => \Yii::$app->formatter->asDatetime(time()),
                'sCloseReson' => '自动关闭'
            ],
            [
                'AND',
                ['StatusID' => 'unpaid'],
                ['<', 'dNewDate', \Yii::$app->formatter->asDatetime(time() - $lOrderAutoCloseTime * 3600)]
            ]);
        return true;
    }

    /**
     * 自动确定签收的时间，计划任务
     */
    public function autoCheckSign()
    {
        if (MallConfig::getValueByKey('sOrderCompleteDependOn') == 'ship') {
            return true;
        }

        //查找所有未签收的订单明细
        $arrOrder = [];
        $arrOrderDetail = OrderDetail::find()->select([
            'OrderID',
            'sShipNo'
        ])->groupBy([
            'OrderID',
            'sShipNo'
        ])->where("OrderID in (SELECT lID FROM `Order` where StatusID in ('paid', 'delivered')) AND dShipDate IS NOT NULL AND dSignDate IS NULL")->with('order')->all();
        foreach ($arrOrderDetail as $detail) {
            $trace = \Yii::$app->expresstrace->getByNo($detail->sShipNo);
            if ($trace->dSignDate) {
                OrderDetail::updateAll(['dSignDate' => $trace->dSignDate], ['sShipNo' => $detail->sShipNo]);
                $arrOrder[$detail->OrderID] = $detail->order;
            }
        }

        if ($arrOrder) {
            foreach ($arrOrder as $order) {
                $order->updateSignDate();
            }
        }
    }

    /**
     * 计划任务自动确认收货
     */
    public function autoConfirmReceive()
    {
        if (MallConfig::getValueByKey('sOrderCompleteDependOn') == 'ship') {
            return true;
        }

        $arrOrder = static::find()->where(['StatusID' => static::STATUS_DELIVERED])->andWhere("dSignDate IS NOT NULL")->all();
        foreach ($arrOrder as $order) {
            if ($order->lAutoReceiveRemain < 0) {
                $order->confirmReceive();
            }
        }
    }

    /**
     * 确认收货
     */
    public function confirmReceive_old()
    {
    }

    /**
     * 获取自动签收剩余的时间（秒）
     */
    public function getLAutoReceiveRemain()
    {
        $lLastSignTime = 0;

        //以物流的状态做为签收的判断依据
        if (MallConfig::getValueByKey('sOrderCompleteDependOn') == 'wuliu') {
            foreach ($this->arrDetail as $detail) {
                if (!$detail->dSignDate || $detail->bRefunding) {
                    return false;//有商品未签收或者还在退款中，则还不能进入自动签收的倒计时。
                } elseif ($detail->bRefunded) {//退款已完成
                    $lLastSignTime = strtotime($detail->dRefundCompleteDate);
                } elseif ($detail->dSignDate) {
                    if ($lLastSignTime < strtotime($detail->dSignDate)) {
                        $lLastSignTime = strtotime($detail->dSignDate);
                    }
                }
            }
        } else {//以发货时间做为签收的判断依据
            foreach ($this->arrDetail as $detail) {
                if (!$detail->dShipDate || $detail->bRefunding) {
                    return false;//有商品未发货或者还在退款中，则还不能进入自动签收的倒计时。
                } elseif ($detail->bRefunded) {//退款已完成
                    $lLastSignTime = strtotime($detail->dRefundCompleteDate);
                } elseif ($detail->dShipDate) {
                    if ($lLastSignTime < strtotime($detail->dShipDate)) {
                        $lLastSignTime = strtotime($detail->dShipDate);
                    }
                }
            }
        }

        return $lLastSignTime + MallConfig::getValueByKey("lAutoConfirmReceive") * 86400 - time();
    }

    /**
     * 更新签收时间，若第三方物流，调用快递100的签收时间；若自配，已发货时间为签收时间；
     * 若商品分多次发货，则以最后一次签收商品的签收时间为订单签收时间
     */
    public function updateSignDate()
    {
        $dSignDate = OrderDetail::find()->select(['dSignDate'])->where(['OrderID' => $this->lID])->max('dSignDate');
        $this->dSignDate = $dSignDate;
        $this->save();
    }

    /**
     * 关联订单明细
     */
    public function getArrDetail()
    {
        return $this->hasMany(OrderDetail::className(), ['OrderID' => 'lID'])->with("product");
    }

    /**
     * 关联已发货的订单明细
     */
    public function getArrShipDetail()
    {
        //修改为以订单物流表为基准 by hcc on 2018/7/16
//        return $this->hasMany(OrderDetail::className(), ['OrderID' => 'lID'])->andWhere([
//            'not',
//            ['dShipDate' => null]
//        ])->with("product");

        return $this->hasMany(OrderLogistics::className(), ['OrderID' => 'lID'])->andWhere([
            'not',
            ['dShipDate' => null]
        ]);
    }

    /**
     * 通过订单编号获取
     */
    public function getByNo($sNo)
    {
        return static::findOne(['sName' => $sNo]);
    }

    /**
     * 通过渠道端的订单号和商品，查找订单
     */
    public function getByClientNoAndProduct($sNo, $ProductID)
    {
        $OrderID = static::getDb()->createCommand("SELECT `Order`.lID FROM `Order` INNER JOIN `OrderDetail` ON `OrderDetail`.OrderID=`Order`.lID AND `OrderDetail`.ProductID='$ProductID' WHERE `Order`.sClientSN='$sNo'")->queryScalar();
        return static::findOne($OrderID);
    }


    /**
     * 是否有发货的商品
     * @return $this
     */
    public function getBHasShip()
    {
        foreach ($this->arrDetail as $detail) {
            if ($detail->dShipDate) {
                return true;
            }
        }

        return false;
    }

    public function getOrderAddress()
    {
        return $this->hasOne(OrderAddress::className(),
            ['lID' => 'OrderAddressID'])->with('province')->with('city')->with('area');
    }

    public function getSupplier()
    {
        return $this->hasOne(Supplier::className(), ['lID' => 'SupplierID']);
    }

    public function getMember()
    {
        return $this->hasOne(Member::className(), ['lID' => 'MemberID']);
    }

    /**
     * 更新订单的发货状态，判断到全部商品（除去退款成功商品）已发货
     */
    public function updateShipStatus()
    {
        //只有订单状态是已付款，才处理
        if ($this->StatusID != static::STATUS_PAID) {
            return true;
        }

        //退款关闭、退款成功、没有售后状态的商品，如果没有物流单号，则判定订单状态还不能是已发货
        $arrDetail = $this->arrDetail;

        //去掉所有退款成功的明细
        foreach ($arrDetail as $key => $detail) {
            if ($detail->StatusID == 'success') {
                unset($arrDetail[$key]);
            }
        }

        //如果还有明细，说明不是全部退款confirmOrder
        if ($arrDetail) {

            $bAllShiped = true;
            foreach ($arrDetail as $key => $detail) {
                if (!$detail->bShiped) {//去掉没有发货的
                    $bAllShiped = false;
                }
            }

            //如果全部发货了，更新订单的状态为已发货
            if ($bAllShiped) {
                $this->StatusID = static::STATUS_DELIVERED;
                $this->dShipDate = \Yii::$app->formatter->asDatetime(time());
                $this->save();
            }

        } else {
            return true;
        }

        return true;
    }

    /**
     * 获取状态中文名
     */
    public function getSStatus()
    {
        $StatusID = $this->StatusID;
        return \Yii::$app->cache->getOrSet("orderstatus", function () {
            $field = SysField::findOne(['sObjectName' => 'Shop/Order', 'sFieldAs' => 'StatusID']);
            return parse_ini_string($field->sEnumOption);
        })[$StatusID];
    }

    /**
     * 获取该会员各种状态的订单数目
     * @param $MemberID
     */
    public function getStatusCount($MemberID)
    {
        $data = [];

        //未付款订单
        $data['lUnpaidCount'] = static::find()->where([
            'MemberID' => $MemberID,
            'StatusID' => static::STATUS_UNPAID
        ])->count();

        //已付款订单
        $data['lPaidCount'] = static::find()->leftJoin('sellerorder s','`order`.lID=s.OrderID')->where([
            '`order`.StatusID' => static::STATUS_PAID,'RefundStatusID' => null
        ])->andWhere(['or',['MemberID' => $MemberID],['s.SellerID' => $MemberID]])->count();

        //已发货订单
        $data['lShipCount'] = static::find()->leftJoin('sellerorder s','`order`.lID=s.OrderID')->where([
            '`order`.StatusID' => static::STATUS_DELIVERED
        ])->andWhere(['or',['MemberID' => $MemberID],['s.SellerID' => $MemberID]])->count();

        //已完成订单
        $data['lSuccessCount'] = static::find()->leftJoin('sellerorder s','`order`.lID=s.OrderID')->where([
            '`order`.StatusID' => static::STATUS_SUCCESS
        ])->andWhere(['or',['MemberID' => $MemberID],['s.SellerID' => $MemberID]])->count();
        //退款中的订单
        $data['lRefundCount'] = \Yii::$app->refund->lRefundCount;


        return $data;
    }

    /**
     * 提取所有的物流信息
     */
    public function getArrTrace()
    {
        $arrTrace = [];
        $detail = $this->detail;
        if ($detail->sShipNo) {
            $result = \Yii::$app->expresstrace->queryFree($detail->ShipCompanyID, $detail->sShipNo);
            $trace = [];
            if ($result['status']) {
                $trace['sStatus'] = $result['state'];
                $trace['arrTraceInfo'] = $result['data'];
            } else {
                $trace = [];
                $trace['sStatus'] = "查无信息";
            }

            $trace['sCompany'] = $detail->shipCompany->sName;
            $trace['ShipID'] = $detail->ShipID;
            $trace['sShipNo'] = $detail->sShipNo;
            $arrTrace[$detail->sShipNo] = $trace;
        }

        return array_values($arrTrace);
    }

    /**
     * 统计会员的累计消费
     */
    public function computeMemberConsume($MemberID)
    {
        return static::getDb()->createCommand("SELECT SUM(fPaid) - SUM(fRefund) FROM `Order` WHERE MemberID='$MemberID'")->queryScalar();
    }


    /**
     * 保存云订单
     * @author hcc
     * @time 2018-5-17
     * */
    public static function saveCloudOrder($arrParam)
    {
        foreach ($arrParam as $orderName => $orderInfo) {
            //保存订单主体
            $product = Product::findByID($orderInfo['product'][0]['lProductCloudID']);
            if (!$product) {
                throw new \Exception("云商品不存在");
            }

            /* 订单号不能重复 panlong 2019-2-12 15:41:06 开始 */
            $oldOrder = Order::findOne(['sName' => $orderName]);
            if ($oldOrder) {
                continue;
            }
            /* 订单号不能重复 panlong 2019-2-12 15:41:06 结束 */

            $order = new static();
            $order->sName = $orderName;
            $order->SupplierID = $product->SupplierID;
            $order->BuyerID = Buyer::findBysIP($orderInfo['sIP'])->lID;
            $order->StatusID = 'unpaid';
            $order->dNewDate = \Yii::$app->formatter->asDatetime(time());

            $num = 0;
            $weight = 0;
            $fBuyerTotal = 0;
            $fSupplierTotal = 0;
            foreach ($orderInfo['product'] as $productInfo) {
                $Product = Product::findByID($productInfo['lProductCloudID']);

                //如果有活动，取活动价格 panlong 2019年3月14日16:09:37
                if ($Product->secKill) {
                    //取商品本身的渠道价和进货价
                    $fBuyerTotal += $Product->secKill->fBuyerPrice * $productInfo['lProductCloudQuantity'];
                    $fSupplierTotal += $Product->secKill->fWholesale * $productInfo['lProductCloudQuantity'];
                } else {
                    //判断是否有规格 by hcc on 2018/6/6
                    if ($productInfo['sSKU']) {
                        $productSKU = ProductSKU::findByProductIDAndSKU($productInfo['lProductCloudID'], $productInfo['sSKU']);
                        //取规格的渠道价和进货价
                        $fBuyerTotal += $productSKU->fBuyerPrice * $productInfo['lProductCloudQuantity'];
                        $fSupplierTotal += $productSKU->fCostPrice * $productInfo['lProductCloudQuantity'];
                    } else {
                        //取商品本身的渠道价和进货价
                        $fBuyerTotal += $Product->fBuyerPrice * $productInfo['lProductCloudQuantity'];
                        $fSupplierTotal += $Product->fSupplierPrice * $productInfo['lProductCloudQuantity'];
                    }
                }

                $num += $productInfo['lProductCloudQuantity'];
                $weight += $productInfo['lProductCloudQuantity'] * $Product['lWeight'];
            }

            $shipArray = [
                'CityID' => $orderInfo['address']['CityID'],
                'ShipTemplateID' => $product->ShipTemplateID,
                'Number' => $num,
                'fTotalMoney' => $fSupplierTotal,
                'Weight' => $weight,
            ];
            $ship = \Yii::$app->shiptemplate->computeShip($shipArray);
            $order->fShip = $ship['fShipMoney'];
            $order->fBuyerProductPaid = $fBuyerTotal;
            $order->fSupplierProductIncome = $fSupplierTotal;
            $order->fBuyerPaid = $order->fBuyerProductPaid + $order->fShip;
            $order->fSupplierIncome = $order->fSupplierProductIncome + $order->fShip;
            $order->fProfit = $order->fBuyerProductPaid - $order->fSupplierProductIncome;
            $order->sMessage = $orderInfo['message'];
            $order->save();

            //保存订单明细
            foreach ($orderInfo['product'] as $productInfo) {
                $productDetail = Product::findByID($productInfo['lProductCloudID']);
                $orderDetail = new OrderDetail();

                //如果有活动，取活动价格 panlong 2019年3月14日16:43:59
                if ($productDetail->secKill) {
                    $orderDetail->fBuyerPrice = $productDetail->secKill->fBuyerPrice;
                    $orderDetail->fSupplierPrice = $productDetail->secKill->fWholesale;
                } else {
                    //判断是否有规格
                    if ($productInfo['sSKU']) {
                        $productSKU = ProductSKU::findByProductIDAndSKU($productInfo['lProductCloudID'], $productInfo['sSKU']);
                        //取规格的渠道价和进货价
                        $orderDetail->fBuyerPrice = $productSKU->fBuyerPrice;
                        $orderDetail->fSupplierPrice = $productSKU->fCostPrice;
                        $orderDetail->sSKU = $productInfo['sSKU'];
                    } else {
                        //取商品本身的渠道价和进货价
                        $orderDetail->fBuyerPrice = $productDetail->fBuyerPrice;
                        $orderDetail->fSupplierPrice = $productDetail->fSupplierPrice;
                    }
                }

                $orderDetail->sName = $productDetail->sName;
                $orderDetail->BuyerID = $order->BuyerID;
                $orderDetail->ProductID = $productInfo['lProductCloudID'];
                $orderDetail->OrderID = $order->lID;
                $orderDetail->ShipTemplateID = $product->ShipTemplateID;
                $orderDetail->sPic = $productDetail->sMasterPic;
                $orderDetail->lQuantity = $productInfo['lProductCloudQuantity'];
                $orderDetail->fBuyerSalePrice = $productInfo['fPrice'];
                $orderDetail->fBuyerPaidTotal = $orderDetail->fBuyerPrice * $orderDetail->lQuantity;
                $orderDetail->fSupplierIncomeTotal = $orderDetail->fSupplierPrice * $orderDetail->lQuantity;
                $orderDetail->fShip = $order->fShip;
                $orderDetail->save();
            }

            //保存订单地址
            $orderAddress = new OrderAddress();
            $orderAddress->sName = $orderInfo['address']['sName'];
            $orderAddress->OrderID = $order->lID;
            $orderAddress->ProvinceID = $orderInfo['address']['ProvinceID'];
            $orderAddress->CityID = $orderInfo['address']['CityID'];
            $orderAddress->AreaID = $orderInfo['address']['AreaID'];
            $orderAddress->sAddress = $orderInfo['address']['sAddress'];
            $orderAddress->sMobile = $orderInfo['address']['sMobile'];
            $orderAddress->save();

            $order->OrderAddressID = $orderAddress->lID;
            $order->save();

            //商品变动记录保存订单ID
            $productStockChange = \Yii::$app->productstockchange->find()->where(['sName' => $order->sName])->all();
            foreach ($productStockChange as $productStockChange) {
                $productStockChange->OrderID = $order->lID;
                $productStockChange->save();
            }

            //扣除渠道款
            if (empty($order->lID)) {
                throw new \Exception("订单不存在");
            }
            \Yii::$app->order->orderPayment($order->lID);

            //统计供应商待结算金额
            if (empty($order->SupplierID)) {
                throw new \Exception("供应商不存在");
            }
            \Yii::$app->supplier->computeWaitMoney($order->SupplierID);

            return $order->fShip;
        }
    }


    /**
     * 修改订单中退款金额
     * @param $OrderID
     * @param $lID
     */
    public function changeOrderRefund($id, $fBuyerRefund, $fSupplierRefund)
    {
        $order = static::findByID($id);
        $order->fBuyerRefund = $fBuyerRefund;
        $order->fSupplierRefund = $fSupplierRefund;
        $order->fBuyerPaid = $order->fBuyerProductPaid + $order->fShip - $fBuyerRefund;
        $order->fSupplierIncome = $order->fSupplierProductIncome + $order->fShip - $fSupplierRefund;
        $order->fProfit = $order->fBuyerPaid - $order->fSupplierIncome;
        $order->save();
    }

    /**
     * 渠道商订单扣款
     * @param $lID
     */
    public function orderPayment($id)
    {
        $order = static::findByID($id);
        $Buyer = Buyer::findByID($order->BuyerID);
        $fBalance = $Buyer->fBalance;
        if ($fBalance >= $order->fBuyerPaid) {
            $order->StatusID = 'paid';
            $order->save();
            $data = [];
            $data['sName'] = '订单' . $order->sName . '扣款';
            $data['fMoney'] = -($order->fBuyerPaid);
            $data['MemberID'] = $order->BuyerID;
            $data['RoleType'] = 'buyer';
            $data['TypeID'] = DealFlow::$TypeID['buy'];
            $data['DealID'] = $order->lID;
            \Yii::$app->dealflow->change($data);

        }
    }

    /**
     * 获取渠道商未付款订单
     * @param $lID
     * return array
     */
    public function getUnpaid($BuyerID)
    {
        return static::find()->select('lID')->where(['StatusID' => 'unpaid', 'BuyerID' => $BuyerID])->All();
    }

    /**
     * 根据订单号获取lID
     * @param $lID
     * return array
     */
    public function getlIDBySname($sName)
    {
        $order = static::find()->where(['sName' => $sName])->one();
        return $order->lID;
    }

    /**
     * 退款关闭
     */
    public function cancelRefund()
    {
        foreach ($this->arrDetail as $detail) {
            if ($detail->bRefunding) {
                return false;
            }
        }

        $this->RefundStatusID = null;
        $this->save();
    }

    /**
     * 关联订单明细
     */
    public function getDetail()
    {
        return $this->hasOne(OrderDetail::className(), ['OrderID' => 'lID']);
    }

    /***********交易成功分账********开始***********/
    /**
     * 订单交易成功分佣
     */
    public function confirmReceive()
    {
        //调整订单佣金流水
        $this->dReceiveDate = \Yii::$app->formatter->asDatetime(time());
        $this->StatusID = 'success';
        $this->save();
        /* 发放代理销售提成 panlong 2019年9月17日11:24:56 开始 */
        $sellerOrder = SellerOrder::findOne(['OrderID' => $this->lID]);
        if ($sellerOrder) {
            //发放vip提成
            if ($sellerOrder->fSellerCommission > 0) {
                $seller = $sellerOrder->seller;
                //查询待结算金额
                $fChange = SellerFlow::find()->where(['SellerID' => $seller->lID, 'OrderID' => $this->lID])->sum('fChange');
                $sellerOrder->fSellerCommission = $fChange;
                //增加可提现
                SellerFlow::setCashFlow([
                    'fMoney' => $fChange,
                    'order' => $this,
                    'seller' => $seller
                ]);
            }

            //发放顶级代理提成
            if ($sellerOrder->fUpSellerCommission > 0) {
                $seller = $sellerOrder->upSeller;
                $fChange = SellerFlow::find()->where(['SellerID' => $seller->lID, 'OrderID' => $this->lID])->sum('fChange');
                $sellerOrder->fUpSellerCommission = $fChange;
                //增加可提现
                SellerFlow::setCashFlow([
                    'fMoney' => $fChange,
                    'order' => $this,
                    'seller' => $sellerOrder->upSeller
                ]);
            }
            $sellerOrder->StatusID = $this->StatusID;
            $sellerOrder->save();
            //$this->sPlitMoney($this, $sellerOrder);
        }
        $cloudOrder=CloudOrder::findOne(['sName'=>$this->sName]);
        $cloudOrder->confirmReceive();

    }
    /**
     * 增加分账接收方账户接口
     * @return string
     * @author hechengcheng
     * @time 2019年9月18日14:32:29
     */
    private function addWxAccount($appID = '', $openID = '')
    {
        //加密数组
        $sign = [];
        $sign['appid'] = $appID; //服务号ID;
        $sign['mch_id'] = static::MCHID;
        $sign['nonce_str'] = Func::getNonce();
        $sign['receiver'] = json_encode([
            'account' => $openID,
            'relation_type' => 'STAFF',
            'type' => 'PERSONAL_OPENID',
        ]);
        $sign['sign_type'] = 'HMAC-SHA256';

        //加密结果
        $data = [];
        $data['appid'] = $appID; //服务号ID;
        $data['mch_id'] = static::MCHID;
        $data['nonce_str'] = $sign['nonce_str'];
        $data['sign'] = strtoupper(hash_hmac("sha256", Func::arrayToString($sign) . '&key=' . static::KEY, static::KEY));

        $data['receiver'] = json_encode([
            'account' => $openID,
            'relation_type' => 'STAFF',
            'type' => 'PERSONAL_OPENID',
        ]);
        $data['sign_type'] = 'HMAC-SHA256';

        $url = 'https://api.mch.weixin.qq.com/pay/profitsharingaddreceiver';

        $res = Func::HttpRequest($url, $data);

    }

    /**
     * 调用分账接口
     * @return string
     * @author hechengcheng
     * @time 2019年9月18日14:32:29
     */
    private function sPlitMoney($order, $sellerOrder)
    {
        $appid = $order->PaymentID == 'xcx' ? static::XCXAPPID : static::APPID;
        if ($order->StatusID != 'success') {
            return json_encode(['status' => false, 'msg' => '未交易成功订单不可分账']);
        }

        $arrReceiver = [];
        if($order->fService>0){
            $data = [];
            $data['type'] = 'PERSONAL_OPENID';
            $data['account'] = 'oMwD002UvzWOd3S9RLb3CF_P-SCU';
            $data['amount'] = $order->fService * 100;
            $data['description'] = utf8_encode('达咖收益到账'.$order->sName );
            $arrReceiver[] = $data;
        }

        if ($sellerOrder->fSellerCommission > 0) {
            //添加分账接收方
            $member = Member::findOne($sellerOrder->SellerID);
            $openID = $order->PaymentID == 'xcx' ? $member->sXopenID : $member->sOpenID;
            $this->addWxAccount($appid, $openID);
            $data = [];
            $data['type'] = 'PERSONAL_OPENID';
            $data['account'] = $openID;
            $data['amount'] = $sellerOrder->fSellerCommission * 100;
            $data['description'] = utf8_encode('达咖收益到账' . $order->sName);
            $arrReceiver[] = $data;
        }

        if ($sellerOrder->fUpSellerCommission > 0) {
            //添加分账接收方
            $member = Member::findOne($sellerOrder->UpSellerID);
            $openID = $order->PaymentID == 'xcx' ? $member->sXopenID : $member->sOpenID;
            $this->addWxAccount($appid, $openID);

            $data = [];
            $data['type'] = 'PERSONAL_OPENID';
            $data['account'] = $openID;
            $data['amount'] = $sellerOrder->fUpSellerCommission * 100;
            $data['description'] = utf8_encode('达咖收益到账：' . $order->sName);
            $arrReceiver[] = $data;
        }

        //加密数组
        $nonce_str = Func::getNonce();
        $out_order_no = $order->sName;
        $sign = [];
        $sign['appid'] = $appid; //服务号ID;
        $sign['mch_id'] = static::MCHID;
        $sign['nonce_str'] = $nonce_str;
        $sign['out_order_no'] = $out_order_no;
        $sign['receivers'] = json_encode($arrReceiver);
        $sign['sign_type'] = 'HMAC-SHA256';
        $sign['transaction_id'] = $order->payLog->sTransactionID;

        //加密结果
        $arrData = [];
        $arrData['appid'] = $appid; //服务号ID;
        $arrData['mch_id'] = static::MCHID;
        $arrData['nonce_str'] = $nonce_str;
        $arrData['out_order_no'] = $out_order_no;
        $arrData['receivers'] = $sign['receivers'];
        $arrData['sign_type'] = 'HMAC-SHA256';
        $arrData['transaction_id'] = $sign['transaction_id'];
        $arrData['sign'] = strtoupper(hash_hmac("sha256", Func::arrayToString($sign) . '&key=' . static::KEY, static::KEY));

        $url = 'https://api.mch.weixin.qq.com/secapi/pay/multiprofitsharing';

        $res = Func::HttpRequestShare($url, $arrData);
    }
    /***********交易成功分账********结束***********/
}