<?php

namespace myerm\shop\common\models;

use myerm\common\components\CommonEvent;
use myerm\common\models\ExpressCompany;
use myerm\common\models\ExpressTrace;
use myerm\shop\common\models\Buyer;
use myerm\shop\common\models\SellerConfig;


/**
 * 订单明细
 */
class OrderDetail extends ShopModel
{
    /**
     * 响应退款申请的事件
     * @param CommonEvent $event
     */
    public static function refundApply(CommonEvent $event)
    {
        $refund = $event->extraData;

        //更新订单明细的售后状态
        $refund->orderDetail->RefundID = $refund->lID;
        $refund->orderDetail->StatusID = 'refunding';
        $refund->orderDetail->save();
    }

    /**
     * 退款成功
     */
    public static function refundSuccess(CommonEvent $event)
    {
        $refund = $event->extraData;

        //更新订单明细的售后状态
        $refund->orderDetail->StatusID = 'success';

        $refund->orderDetail->dRefundCompleteDate = $refund->dCompleteDate;

        //计算利润，这里的规则还要商榷

        $refund->orderDetail->save();
    }

    /**
     * 退款关闭
     */
    public static function refundClosed(CommonEvent $event)
    {
        $refund = $event->extraData;

        //更新订单明细的售后状态
        $refund->orderDetail->StatusID = 'closed';
        $refund->orderDetail->dRefundCompleteDate = $refund->dCompleteDate;
        $refund->orderDetail->save();
    }

    /**
     * 关联商品
     * @return \yii\db\ActiveQuery
     */
    public function getProduct()
    {
        return $this->hasOne(\Yii::$app->product::className(), ['lID' => 'ProductID']);
    }

    /**
     * 获取售后状态的中文解释
     */
    public function getSStatus()
    {
        if (!$this->StatusID) {
            return "";
        } elseif ($this->StatusID == 'success') {
            return "退款成功";
        } elseif ($this->StatusID == 'closed') {
            return "退款关闭";
        } else {
            return "退款中";
        }
    }

    /**
     * 是否退款中
     */
    public function getBRefunding()
    {
        if (!$this->StatusID || in_array($this->StatusID, ['closed', 'success'])) {
            return false;
        } else {
            return true;
        }
    }

    /**
     * 是否退款完成
     */
    public function getBRefunded()
    {
        if (in_array($this->StatusID, ['closed', 'success'])) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * 发货方式
     */
    public function getSShip()
    {
        if ($this->ShipID == 'wuliu') {
            return '物流';
        } elseif ($this->ShipID == 'self') {
            return '自配';
        } elseif ($this->ShipID == 'overseas') {
            return '海外直邮';
        }
    }

    public function getShipCompany()
    {
        return $this->hasOne(ExpressCompany::className(), ['ID' => 'ShipCompanyID']);
    }

    public function getOrder()
    {
        return $this->hasOne(Order::className(), ['lID' => 'OrderID']);
    }

    public function getRefund()
    {
        return $this->hasOne(Refund::className(), ['lID' => 'RefundID']);
    }

    public function getTrace()
    {
        $result = \Yii::$app->expresstrace->queryFree($this->ShipCompanyID, $this->sShipNo);
        if ($result['status']) {
            $trace = new ExpressTrace;
            $trace->sNo = $this->sShipNo;
            $trace->sStatus = $result['state'];
            $trace->sTraceInfo = json_encode($result['data']);
            return $trace;
        } else {
            return null;
        }
    }

    /**
     * 是否已发货
     */
    public function getBShiped()
    {
        if ($this->dShipDate) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * 更改物流
     */
    public function modifyShip($data)
    {
        if ($data['ShipID'] == 'self') {
            $this->ShipID = $data['ShipID'];
            $this->ShipCompanyID = null;
            $this->sShipNo = null;
            $this->dShipDate = \Yii::$app->formatter->asDatetime(time());
            $this->dSignDate = $this->dShipDate;
            $this->save();

            //当若自配，已发货时间为签收时间
            $this->order->updateSignDate();
        } else {
            $this->ShipID = $data['ShipID'];
            $this->sShipNo = $data['sShipNo'];
            $this->ShipCompanyID = $data['ShipCompanyID'];
            $this->dShipDate = \Yii::$app->formatter->asDatetime(time());
            $this->save();
        }

        return true;
    }

    /**
     * 发货
     */
    public function ship($data)
    {
        if ($data['ShipID'] == 'self') {
            $this->ShipID = $data['ShipID'];
            $this->dShipDate = \Yii::$app->formatter->asDatetime(time());
            $this->dSignDate = $this->dShipDate;
            $this->save();

            //当若自配，已发货时间为签收时间
            $this->order->updateSignDate();
        } else {
            $this->ShipID = $data['ShipID'];
            $this->sShipNo = $data['sShipNo'];
            $this->ShipCompanyID = $data['ShipCompanyID'];
            $this->dShipDate = \Yii::$app->formatter->asDatetime(time());
            $this->save();
        }

        return true;
    }

    /**
     * 是否可以发货
     */
    public function getBCanShip()
    {
        if ($this->bShiped || $this->StatusID && $this->StatusID != 'closed') {
            return false;
        }

        return true;
    }

    /**
     * 是否可以退款
     */
    public function getCanRefund()
    {
        if ($this->StatusID && $this->StatusID != 'closed') {
            return false;
        }

        if ($this->order->StatusID == 'paid') {
            return true;
        } elseif ($this->order->StatusID == 'delivered') {
            return true;
        }

        return false;
    }

    /**
     * 关联秒杀活动，如果没有关联，返回null
     */
    public function getSecKill()
    {
        if (!$this->SecKillID) {
            return null;
        } else {
            return \Yii::$app->seckillproduct->findProduct($this->SecKillID, $this->ProductID);
        }
    }

    /**
     * 计算当前会员购买了多少件秒杀的商品
     * @param $SecKillID
     * @param $ProductID
     * @return int|mixed
     */
    public function countSecKillProduct($SecKillID, $ProductID)
    {
        return static::getDb()->createCommand("SELECT SUM(OrderDetail.lQuantity) FROM OrderDetail
                                              INNER JOIN `Order` O ON O.lID=OrderDetail.OrderID AND O.MemberID='" . \Yii::$app->frontsession->MemberID . "' AND O.StatusID<>'closed'
                                              WHERE OrderDetail.SecKillID='$SecKillID' AND OrderDetail.ProductID='$ProductID'")->queryScalar();
    }

    /**
     * 判断是否最后一类运费模板商品
     */
    public function isLastProduct($OrderDetailID, $OrderID, $ShipTemplateID)
    {
        //查询同一订单下相同运费模板的订单详情
        $OrderDetailArr = static::find()->where(['and', ['=', 'OrderID', $OrderID], ['<>', 'lID', $OrderDetailID], ['=', 'ShipTemplateID', $ShipTemplateID]])->asArray()->all();
        $isLast = 0;
        $count = 0;
        $refundAll = 0;
        foreach ($OrderDetailArr as $value) {
            //如果是退款完成，且全额退款，则不为最后一类
            if ($value['StatusID'] == 'success') {
                $Refund = \Yii::$app->refund->findByOrderDetailID($value['lID']);
                if ($Refund->lItemTotal == $Refund->lRefundItem) {
                    $refundAll++;
                }
            }
            $count++;
        }
        if ($count == $refundAll) {
            $isLast = 1;
        }
        return $isLast;
    }

    /**
     * 根据订单ID获取所有订单详情
     */
    public function findByOrderID($OrderID)
    {
        return static::findAll(['OrderID' => $OrderID]);
    }

    /**
     * 根据订单ID及运费模板ID统计相同运费模板商品总价
     */
    public function countProductPrice($OrderID, $ShipTemplateID)
    {
        return static::find()->where(['OrderID' => $OrderID, 'ShipTemplateID' => $ShipTemplateID])->sum('fBuyerPaidTotal');
    }

    /**
     * 根据订单ID、商品ID查询订单详情
     * @param $OrderID
     * @param $ProductID
     * @return object
     */
    public static function findByOandP($OrderID, $ProductID)
    {
        return static::find()->where(['OrderID' => $OrderID, 'ProductID' => $ProductID])->one();
    }

    /**
     * 根据订单ID统计订单总额
     * @param $OrderID 订单ID
     * @return mixed
     * @author panlong
     * @time 2018-6-28 09:15:10
     */
    public function sumTotal($OrderID)
    {
        return static::find()->where(['OrderID' => $OrderID])->sum('fBuyerPaidTotal');
    }

    /**
     * 获取没有物流跟踪的订单明细
     */
    public function getArrNoTrace()
    {
        return static::find()->select(['sShipNo', 'ShipCompanyID'])->where("dShipDate IS NOT NULL AND dSignDate IS NULL AND sShipNo NOT IN (SELECT sNo FROM ExpressTrace)")->groupBy("sShipNo")->all();
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
     * 修改订单详情中退款状态
     * @param $OrderID
     * @param $lID
     */
    public function setRefund($id)
    {
        $orderdetail = static::findByID($id);
        $orderdetail->StatusID = 'refunding';
        $orderdetail->save();
    }

    /**
     * 修改订单详情中退款金额
     * @param $OrderID
     * @param $lID
     */
    public function changeRefund($id, $fBuyerRefund, $fSupplierRefund)
    {
        $orderdetail = static::findByID($id);
        $orderdetail->fBuyerRefund = $fBuyerRefund;
        $orderdetail->fSupplierRefund = $fSupplierRefund;
        $orderdetail->dRefundCompleteDate = \Yii::$app->formatter->asDatetime(time());
        $orderdetail->StatusID = 'success';
        $orderdetail->save();
    }


    /**
     * 统计订单的渠道商退款金额
     */
    public static function countBuyerRefund($ID)
    {
        return static::find()->where(['OrderID' => $ID])->sum('fBuyerRefund');
    }

    /**
     * 统计订单的供应商商退款金额
     */
    public static function countSupplierRefund($ID)
    {
        return static::find()->where(['OrderID' => $ID])->sum('fSupplierRefund');
    }


    /**
     * 通过OrderID查找订单详情，转为数组
     * @param $OrderID
     * @return array|\yii\db\ActiveRecord[]
     * @author hechengcheng
     * @time 2018年7月12日15:04:28
     */
    public static function findArrByOrderID($OrderID)
    {
        return static::find()->where(['OrderID' => $OrderID])->asArray()->all();
    }

    /**
     * 通过OrderID查找订单详情，转为数组
     * @param $expressNo 快递单号
     * @param $expressCompany 快递公司
     * @param $shipID  发货方式
     * @return array|\yii\db\ActiveRecord[]
     * @author cgq
     * @time 2018年7月17日
     */
    public function shipNotify($expressNo, $expressCompany, $shipID)
    {
        $order = Order::findByID($this->OrderID);
        if ($shipID == 'wuliu') {
            $notifyConfig = NotifyConfig::findOne(['sName' => '物流发货通知']);
            if ($notifyConfig->bActive) {

                $sTpl = $notifyConfig->sContent;
                $sNameCut = mb_substr($this->sName, 0, 6);
                $sSmsContent = str_replace('<商品…>', "<{$sNameCut}...>", $sTpl);
                $sSmsContent = str_replace('<物流公司>', ExpressCompany::findOne(['sKdBirdCode' => $expressCompany])->sName,
                    $sSmsContent);
                $sSmsContent = str_replace('<运单号>', $expressNo, $sSmsContent);

                if ($notifyConfig->bSms) {
                    //手机短信通知
                    \Yii::$app->sms->send($order->orderAddress->sMobile, $sSmsContent);
                }

                if ($notifyConfig->bWXNotify) {
                    //公众号消息推送
                    try {
                        $r = \Yii::$app->wechat->notice->send([
                            'touser' => $order->member->sOpenID,
                            'template_id' => $notifyConfig->sWXTplID,
                            'url' => MallConfig::getValueByKey("sMallRootUrl") . "/member/trace?id=" . $order->lID,
                            'data' => [
                                'first' => "你好，您购买的" . $sNameCut . "...已发货",
                                "keyword1" => $order->sName,
                                "keyword2" => ExpressCompany::findOne($_POST['CompanyID'])->sName,
                                "keyword3" => $_POST['sShipNo'],
                                "keyword4" => $order->fSumOrder,
                                "keyword5" => $order->orderAddress->province->sName . " " . $order->orderAddress->city->sName . " " . $order->orderAddress->area->sName . " " . $order->orderAddress->sAddress,
                                "remark" => $order->sMessage,
                            ],
                        ]);

                        \Yii::trace($r, "微信消息返回信息");
                    } catch (\Exception $e) {
                        \Yii::error($e->getMessage(), "微信消息返回信息");
                    }
                }
            }
        } else {
            $notifyConfig = NotifyConfig::findOne(['sName' => '自配发货通知']);
            if ($notifyConfig->bActive) {
                $sTpl = $notifyConfig->sContent;
                $sNameCut = mb_substr($this->sName, 0, 6);
                $sSmsContent = str_replace('<商品…>', "<{$sNameCut}...>", $sTpl);

                if ($notifyConfig->bSms) {
                    //手机短信通知
                    \Yii::$app->sms->send($order->orderAddress->sMobile, $sSmsContent);
                }

                if ($notifyConfig->bWXNotify) {
                    //公众号消息推送
                    try {
                        $r = \Yii::$app->wechat->notice->send([
                            'touser' => $order->member->sOpenID,
                            'template_id' => $notifyConfig->sWXTplID,
                            'url' => MallConfig::getValueByKey("sMallRootUrl") . "/member/trace?id=" . $order->lID,
                            'data' => [
                                'first' => "你好，您购买的" . $sNameCut . "...已发货",
                                "keyword1" => $order->sName,
                                "keyword2" => "自配",
                                "keyword3" => "",
                                "keyword4" => $order->fSumOrder,
                                "keyword5" => $order->orderAddress->province->sName . " " . $order->orderAddress->city->sName . " " . $order->orderAddress->area->sName . " " . $order->orderAddress->sAddress,
                                "remark" => $order->sMessage,
                            ],
                        ]);
                        \Yii::trace($r, "微信消息返回信息");
                    } catch (\Exception $e) {
                        \Yii::error($e->getMessage(), "微信消息返回信息");
                    }
                }
            }
        }

    }

    /**
     * 退款关闭
     */
    public function cancelRefund()
    {
        //更新订单明细的售后状态
        $this->StatusID = 'closed';
        $this->dRefundCompleteDate = \Yii::$app->formatter->asDatetime(time());
        $this->save();
    }
}