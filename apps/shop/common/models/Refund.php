<?php

namespace myerm\shop\common\models;

use myerm\backend\system\models\SysField;
use myerm\common\components\CommonEvent;
use myerm\common\models\ExpressCompany;
use myerm\common\libs\File;
use myerm\common\libs\NewID;

/**
 * 退款售后
 */

/**
 * Class Refund
 * @package myerm\shop\common\models
 * @author panlong
 * @time 2018-6-13 10:13:41
 */
class Refund extends \myerm\shop\common\models\ShopModel
{
    /**
     * 配置数据源
     * @return \yii\db\Connection
     */
    public static function getDb()
    {
        return \Yii::$app->get('ds_cloud');
    }

    //退款申请事件
    const EVENT_APPLY = 'apply';

    //退款申请超时
    const EVENT_SUCCESS = 'success';

    //卖家同意退款，买家请退货。
    const EVENT_AGREE = 'agree';

    //拒绝退款申请
    const EVENT_DENY_APPLY = 'denyapply';

    //退款关闭
    const EVENT_CLOSE = 'closed';

    //修改申请
    const EVENT_MODIFY_APPLY = 'modifyapply';

    //拒绝确认收货
    const EVENT_DENY_RECEIVE = 'denyreceive';

    //确认收货
    const EVENT_AGREE_RECEIVE = 'agreereceive';

    /**
     * 同意退款，通知买家
     * @param CommonEvent $event
     */
    public static function agreeNotify(CommonEvent $event)
    {
        $refund = $event->extraData;

        $notifyConfig = NotifyConfig::findOne(['sName' => '请退货']);
        if ($notifyConfig->bActive) {

            $sTpl = $notifyConfig->sContent;
            $sSmsContent = str_replace('--', MallConfig::getValueByKey('lRefundAgreeTimeOut'), $sTpl);

            //手机短信通知
            \Yii::$app->sms->send($refund->order->orderAddress->sMobile, $sSmsContent);
            try {
                //公众号消息推送
                $r = \Yii::$app->wechat->notice->send([
                    'touser' => $refund->order->member->sOpenID,
                    'template_id' => $notifyConfig->sWXTplID,
                    'url' => MallConfig::getValueByKey("sMallRootUrl") . "/member/refunddetail?id=" . $refund->lID,
                    'data' => [
                        'first' => $sSmsContent,
                        "orderProductPrice" => "¥" . number_format($refund->fRefundReal, 2),
                        "orderProductName" => $refund->orderDetail->sName,
                        "orderName" => $refund->order->sName,
                    ],
                ]);
                \Yii::trace($r, "微信消息返回信息");
            } catch (\Exception $e) {
                \Yii::error($e->getMessage(), "微信消息返回信息");
            }
        }
    }

    /**
     * 卖家拒绝退款，通知买家
     * @param CommonEvent $event
     */
    public static function denyApplyNotify(CommonEvent $event)
    {
        $refund = $event->extraData;

        $notifyConfig = NotifyConfig::findOne(['sName' => '拒绝退款通知']);
        if ($notifyConfig->bActive) {
            $sSmsContent = $notifyConfig->sContent;

            //手机短信通知
            \Yii::$app->sms->send($refund->order->orderAddress->sMobile, $sSmsContent);

            //公众号消息推送
            try {
                $r = \Yii::$app->wechat->notice->send([
                    'touser' => $refund->order->member->sOpenID,
                    'template_id' => $notifyConfig->sWXTplID,
                    'url' => MallConfig::getValueByKey("sMallRootUrl") . "/member/refunddetail?id=" . $refund->lID,
                    'data' => [
                        'first' => $sSmsContent,
                        "orderProductPrice" => "¥" . number_format($refund->fRefundReal, 2),
                        "orderProductName" => $refund->orderDetail->sName,
                        "orderName" => $refund->order->sName,
                    ],
                ]);
                \Yii::trace($r, "微信消息返回信息");
            } catch (\Exception $e) {
                \Yii::error($e->getMessage(), "微信消息返回信息");
            }
        }
    }

    /**
     * 卖家拒绝确认收货，通知买家
     * @param CommonEvent $event
     */
    public static function denyReceiveNotify(CommonEvent $event)
    {
        $refund = $event->extraData;

        $notifyConfig = NotifyConfig::findOne(['sName' => '拒绝确认收货']);
        if ($notifyConfig->bActive) {

            $sTpl = $notifyConfig->sContent;
            $sSmsContent = str_replace('--', MallConfig::getValueByKey('lRefundDenyReceiveTimeOut'), $sTpl);

            //手机短信通知
            \Yii::$app->sms->send($refund->order->orderAddress->sMobile, $sSmsContent);

            //公众号消息推送
            try {
                $r = \Yii::$app->wechat->notice->send([
                    'touser' => $refund->order->member->sOpenID,
                    'template_id' => $notifyConfig->sWXTplID,
                    'url' => MallConfig::getValueByKey("sMallRootUrl") . "/member/refunddetail?id=" . $refund->lID,
                    'data' => [
                        'first' => $sSmsContent,
                        "orderProductPrice" => "¥" . number_format($refund->fRefundReal, 2),
                        "orderProductName" => $refund->orderDetail->sName,
                        "orderName" => $refund->order->sName,
                    ],
                ]);
                \Yii::trace($r, "微信消息返回信息");
            } catch (\Exception $e) {
                \Yii::error($e->getMessage(), "微信消息返回信息");
            }
        }
    }

    /**
     * 卖家拒绝退款，通知买家
     * @param CommonEvent $event
     */
    public static function successNotify(CommonEvent $event)
    {
        $refund = $event->extraData;

        $notifyConfig = NotifyConfig::findOne(['sName' => '退款成功通知']);
        if ($notifyConfig->bActive) {
            $sSmsContent = $notifyConfig->sContent;

            //手机短信通知
            \Yii::$app->sms->send($refund->order->orderAddress->sMobile, $sSmsContent);

            //公众号消息推送
            try {
                $r = \Yii::$app->wechat->notice->send([
                    'touser' => $refund->order->member->sOpenID,
                    'template_id' => $notifyConfig->sWXTplID,
                    'url' => MallConfig::getValueByKey("sMallRootUrl") . "/member/refunddetail?id=" . $refund->lID,
                    'data' => [
                        'first' => $sSmsContent,
                        "orderProductPrice" => "¥" . number_format($refund->fRefundReal, 2),
                        "orderProductName" => $refund->orderDetail->sName,
                        "orderName" => $refund->order->sName,
                    ],
                ]);
                \Yii::trace($r, "微信消息返回信息");
            } catch (\Exception $e) {
                \Yii::error($e->getMessage(), "微信消息返回信息");
            }
        }
    }

    public function getSStatus()
    {
        $StatusID = $this->StatusID;
        return \Yii::$app->cache->getOrSet("refundstatus", function () {
            $field = SysField::findOne(['sObjectName' => 'Shop/Refund', 'sFieldAs' => 'StatusID']);
            return parse_ini_string($field->sEnumOption);
        })[$StatusID];
    }

    /**
     * 关联订单
     * @return \yii\db\ActiveQuery
     */
    public function getSupplier()
    {
        return $this->hasOne(Supplier::className(), ['lID' => 'SupplierID']);
    }

    /**
     * 关联订单
     * @return \yii\db\ActiveQuery
     */
    public function getOrder()
    {
        if($this->sResource=='dkxh') {
            return $this->hasOne(Order::className(), ['sName' => 'sOrderName'])->with('supplier');
        }
        else{
            return $this->hasOne(Order::className(), ['lID' => 'OrderID'])->with('supplier');
        }
    }

    /**
     * 关联订单明细
     * @return \yii\db\ActiveQuery
     */
    public function getOrderDetail()
    {
        return $this->hasOne(OrderDetail::className(), ['lID' => 'OrderDetailID']);
    }

    public function getSType()
    {
        return $this->TypeID == 'onlymoney' ? "仅退款" : "退款退货";
    }

    public function getExpressCompany()
    {
        return $this->hasOne(ExpressCompany::className(), ['ID' => 'ShipCompanyID']);
    }

    /**
     * 计划任务处理超时的退款记录
     */
    public function timeout()
    {
        //买家申请退款，若卖家x天未处理，系统自动同意退款
        $lTime = time() - MallConfig::getValueByKey('lRefundApplyTimeOut') * 86400;

        $arrApplyTimeout = static::find()->where(['StatusID' => 'wait'])->andWhere([
            '<',
            'dEditDate',
            \Yii::$app->formatter->asDatetime($lTime)
        ])->all();
        foreach ($arrApplyTimeout as $applyRefund) {
            $applyRefund->applyTimeout();
        }

        //由于业务流程与来电了不同，删除无效计划任务 panlong 2018-7-16 09:19:42
    }

    /**
     * 撤销申请
     */
    public function cancelApply($ID)
    {
        $refund = static::findOne($ID);

        $refund->dCompleteDate = \Yii::$app->formatter->asDatetime(time());
        $refund->StatusID = 'closed';
        $refund->save();

        RefundLog::saveLog([
            'RefundID' => $refund->lID,
            'sWhoDo' => '买家',
            'StatusID' => 'closed',
            'sMessage' => json_encode([
                '关闭原因' => '买家撤销申请'
            ])
        ]);
    }

    /**
     * 处理买家退货处理超时
     */
    public function agreeTimeout()
    {
        $this->dCompleteDate = \Yii::$app->formatter->asDatetime(time());
        $this->StatusID = 'closed';
        $this->save();

        RefundLog::saveLog([
            'RefundID' => $this->lID,
            'sWhoDo' => '系统',
            'StatusID' => 'closed',
            'sMessage' => json_encode([
                '关闭原因' => '买家超过' . MallConfig::getValueByKey('lRefundAgreeTimeOut') . "天未处理，系统自动关闭退款申请"
            ])
        ]);

        $event = new CommonEvent();
        $event->extraData = $this;
        \Yii::$app->refund->trigger(static::EVENT_CLOSE, $event);
    }

    /**
     * 确认收货超时
     */
    public function agreeReceiveTimeout()
    {
        $this->refundSuccess();
        RefundLog::saveLog([
            'RefundID' => $this->lID,
            'sWhoDo' => '系统',
            'StatusID' => 'success',
            'sMessage' => json_encode([
                '退款金额' => '¥' . number_format($this->fRefundReal, 2)
            ])
        ]);
    }

    /**
     * 退款成功
     */
    private function refundSuccess()
    {
        $this->dCompleteDate = \Yii::$app->formatter->asDatetime(time());
        $this->StatusID = 'success';
        $this->save();

        $event = new CommonEvent();
        $event->extraData = $this;
        \Yii::$app->refund->trigger(static::EVENT_SUCCESS, $event);
    }

    /**
     * 买家处理拒绝确认收货超时
     */
    public function denyReceiveTimeout()
    {
        $this->dCompleteDate = \Yii::$app->formatter->asDatetime(time());
        $this->StatusID = 'closed';
        $this->save();

        RefundLog::saveLog([
            'RefundID' => $this->lID,
            'sWhoDo' => '系统',
            'StatusID' => 'closed',
            'sMessage' => json_encode([
                '关闭原因' => '买家超过' . MallConfig::getValueByKey('lRefundDenyReceiveTimeOut') . "天未处理，系统自动关闭退款申请"
            ])
        ]);

        $event = new CommonEvent();
        $event->extraData = $this;
        \Yii::$app->refund->trigger(static::EVENT_CLOSE, $event);
    }

    /**
     * 卖家处理拒绝退款申请
     */
    public function denyApply($data)
    {
        $refund = static::findOne($data['id']);

        $refund->StatusID = 'closed';
        $refund->dDenyApplyDate = \Yii::$app->formatter->asDatetime(time());
        $refund->sDenyApplyReason = $data['sReason'];
        $refund->sDenyApplyExplain = $data['sExplain'];
        $refund->save();

        RefundLog::saveLog([
            'RefundID' => $refund->lID,
            'sWhoDo' => '卖家',
            'StatusID' => 'closed',
            'sMessage' => json_encode([
                '拒绝原因' => $data['sReason'],
                '拒绝说明' => $data['sExplain'],
            ])
        ]);

        $event = new CommonEvent();
        $event->extraData = $refund;
        \Yii::$app->refund->trigger(static::EVENT_CLOSE, $event);
    }

    /**
     * 售后拒绝退款申请
     */
    public function aftersaledeny($data)
    {
        $refund = static::findOne($data['id']);

        $refund->StatusID = 'closed';
        $refund->dDenyApplyDate = \Yii::$app->formatter->asDatetime(time());
        $refund->sDenyApplyReason = $data['sReason'];
        $refund->sDenyApplyExplain = $data['sExplain'];
        $refund->dAftersaleCheck = \Yii::$app->formatter->asDatetime(time());
        $refund->lAftersaleUserID = \Yii::$app->backendsession->SysUserID;
        $refund->save();

        RefundLog::saveLog([
            'RefundID' => $refund->lID,
            'sWhoDo' => '卖家',
            'StatusID' => 'closed',
            'sMessage' => json_encode([
                '拒绝原因' => $data['sReason'],
                '拒绝说明' => $data['sExplain'],
            ])
        ]);

        $event = new CommonEvent();
        $event->extraData = $refund;
        \Yii::$app->refund->trigger(static::EVENT_CLOSE, $event);
    }


    /**
     * 卖家确认收货
     */
    public function agreeReceive($ID)
    {
        $refund = static::findOne($ID);
        $refund->StatusID = 'wait';
        $refund->save();

        RefundLog::saveLog([
            'RefundID' => $refund->lID,
            'sWhoDo' => '供应商',
            'StatusID' => 'wait',
            'sMessage' => json_encode([
                '退款金额' => '¥' . number_format($refund->fRefundReal, 2)
            ])
        ]);
    }

    /**
     * 卖家拒绝确认收货
     */
    /**
     * @param $data
     * @author panlong
     * @time
     */
    public function denyReceive($data)
    {
        $refund = static::findOne($data['id']);
        $refund->dDenyReceiveDate = \Yii::$app->formatter->asDatetime(time());
        $refund->sDenyReceiveExplain = $data['sDenyReceiveExplain'];
        $refund->StatusID = 'denyconfirmreceive';
        $refund->save();

        RefundLog::saveLog([
            'RefundID' => $refund->lID,
            'sWhoDo' => '卖家',
            'StatusID' => 'denyconfirmreceive',
            'sMessage' => json_encode([
                '拒绝说明' => $data['sDenyReceiveExplain'],
            ])
        ]);

//        $event = new CommonEvent();
//        $event->extraData = $refund;
//        \Yii::$app->refund->trigger(static::EVENT_DENY_RECEIVE, $event);
    }

    /**
     * 卖家处理同意退款申请
     */
    public function agreeApply($ID)
    {
        $refund = static::findOne($ID);

        /* 修改订单明细中的退款信息 */
        \Yii::$app->orderdetail->changeRefund($refund->OrderDetailID, $refund->fBuyerRefund, $refund->fSupplierRefund);

        /* 修改订单表的退款信息 */
        $fBuyerRefund = \Yii::$app->orderdetail->countBuyerRefund($refund->OrderID);
        $fSupplierRefund = \Yii::$app->orderdetail->countSupplierRefund($refund->OrderID);
        \Yii::$app->order->changeOrderRefund($refund->OrderID, $fBuyerRefund, $fSupplierRefund);

        /* 生成渠道商账户流水记录 */
        $data = [];
        $order = \Yii::$app->order->findByID($refund->OrderID);
        $OrdersName = $order->sName;
        $data['sName'] = '订单' . $OrdersName . '退款';
        $data['fMoney'] = $refund->fBuyerRefund;//变动金额
        $data['MemberID'] = $refund->BuyerID;//渠道商ID
        $data['RoleType'] = 'buyer';//身份标识
        $data['TypeID'] = DealFlow::$TypeID['refund'];//交易类型
        $data['DealID'] = $refund->lID;//对应流水类型ID
        \Yii::$app->dealflow->change($data);

        //修改流程，云平台直接审核即可，不需经买家处理 panlong 2018-6-13 10:10:05
        $refund->refundSuccess();
        RefundLog::saveLog([
            'RefundID' => $refund->lID,
            'sWhoDo' => '卖家',
            'StatusID' => 'success',
            'sMessage' => json_encode([
            ])
        ]);
    }


    /**
     * 售后同意退款申请
     */

    /**
     * @param $arrData
     * @author panlong
     * @time 2018-6-13 10:13:30
     */
    public function aftersaleagree($arrData)
    {
        $refund = static::findOne($arrData['ID']);

        $refund->fBuyerRefund = $arrData['BuyerRefund'];
        $refund->fBuyerRefundProduct = $arrData['BuyerRefundProduct'];
        $refund->fSupplierRefund = $arrData['SupplierRefund'];
        $refund->fSupplierRefundProduct = $arrData['SupplierRefundProduct'];

        $refund->dAftersaleCheck = \Yii::$app->formatter->asDatetime(time());
        $refund->lAftersaleUserID = \Yii::$app->backendsession->SysUserID;
        $refund->save();

        RefundLog::saveLog([
            'RefundID' => $refund->lID,
            'sWhoDo' => '售后',
            'StatusID' => 'agreeapply',
            'sMessage' => json_encode([
                '退货地址' => $refund->supplier->sRefundAddress
            ])
        ]);
    }

    public function getRefundMoneyBack()
    {
        return $this->hasOne(RefundMoneyBack::className(), ['RefundID' => 'lID']);
    }

    /**
     * 买家超时未处理被拒绝的申请
     */
    private function denyTimeout()
    {
        $this->dCompleteDate = \Yii::$app->formatter->asDatetime(time());
        $this->StatusID = 'closed';
        $this->save();

        RefundLog::saveLog([
            'RefundID' => $this->lID,
            'sWhoDo' => '系统',
            'StatusID' => 'closed',
            'sMessage' => json_encode([
                '关闭原因' => '买家超过' . MallConfig::getValueByKey('lRefundDenyApplyTimeOut') . "天未处理，系统自动关闭退款申请"
            ])
        ]);

        $event = new CommonEvent();
        $event->extraData = $this;
        \Yii::$app->refund->trigger(static::EVENT_CLOSE, $event);
    }

    /**
     * 申请超时处理
     */
    private function applyTimeout()
    {
        //如果售后未审核，则自动计算退款金额
        if (!$this->fBuyerRefund) {
            /* 根据商品价格占订单中同类运费模板商品总价比例退还运费 */
            $fTotalPrice = \Yii::$app->orderdetail->countProductPrice($this->orderDetail->OrderID, $this->orderDetail->ShipTemplateID);
            $fTotalShip = $this->orderDetail->fShip * ($this->orderDetail->fBuyerPaidTotal / $fTotalPrice);

            $this->fBuyerRefund = ($this->orderDetail->fBuyerPaidTotal + $fTotalShip) * ($this->fRefundProduct / $this->fProductPrice);
            $this->fBuyerRefundProduct = $this->orderDetail->fBuyerPaidTotal * ($this->fRefundProduct / $this->fProductPrice);
            $this->fSupplierRefund = ($this->orderDetail->fSupplierIncomeTotal + $fTotalShip) * ($this->fRefundProduct / $this->fProductPrice);
            $this->fSupplierRefundProduct = $this->orderDetail->fSupplierIncomeTotal * ($this->fRefundProduct / $this->fProductPrice);
        }

        /* 生成渠道商账户流水记录 */
        $data = [];
        $data['sName'] = '订单' . $this->order->sName . '退款';
        $data['fMoney'] = $this->fBuyerRefund;//变动金额
        $data['MemberID'] = $this->BuyerID;//渠道商ID
        $data['RoleType'] = 'buyer';//身份标识
        $data['TypeID'] = DealFlow::$TypeID['refund'];//交易类型
        $data['DealID'] = $this->lID;//对应流水类型ID
        \Yii::$app->dealflow->change($data);

        $this->save();
        $this->refundSuccess();

        RefundLog::saveLog([
            'RefundID' => $this->lID,
            'sWhoDo' => '系统',
            'StatusID' => 'success',
            'sMessage' => json_encode([
            ])
        ]);
    }

    /**
     * 保存退款记录
     */
    public function saveRefund2($data)
    {
        $refund = new static();
        $refund->sName = "R" . $data['ProductID'] . str_replace(['-', ' ', ':'],
                "", \Yii::$app->formatter->asDatetime(time()));
        $refund->dNewDate = \Yii::$app->formatter->asDatetime(time());
        $refund->dEditDate = \Yii::$app->formatter->asDatetime(time());
        $refund->BuyerID = $data['BuyerID'];
        $refund->SupplierID = $data['SupplierID'];
        $refund->StatusID = $data['StatusID'];
        $refund->TypeID = $data['TypeID'];
        $refund->OrderID = $data['OrderID'];
        $refund->OrderDetailID = $data['OrderDetailID'];
        $refund->fBuyerPaidTotal = $data['fBuyerPaidTotal'];
        $refund->fSupplierIncomeTotal = $data['fSupplierIncomeTotal'];
        if (!empty($data['fBuyerRefund'])) {
            $refund->fBuyerRefund = $data['fBuyerRefund'];
        }
        if (!empty($data['fSupplierRefund'])) {
            $refund->fSupplierRefund = $data['fSupplierRefund'];
        }
        $refund->sReason = $data['sReason'];
        $refund->sExplain = $data['sExplain'];
        $refund->sAddress = $data['sAddress'];
        $refund->ShipTemplateID = $data['ShipTemplateID'];
        $refund->lRefundItem = $data['lRefundItem'];
        $refund->lItemTotal = $data['lItemTotal'];
        $refund->fRefundProduct = $data['fRefundProduct'];
        $refund->fProductPrice = $data['fProductPrice'];
        $refund->fBuyerRefundProduct = $data['fBuyerRefundProduct'];
        $refund->fSupplierRefundProduct = $data['fSupplierRefundProduct'];

        $refund->sRefundVoucher = json_encode($data['imgList']);

        $refund->save();


        RefundLog::saveLog([
            'RefundID' => $refund->lID,
            'sWhoDo' => '买家',
            'StatusID' => 'apply',
            'sMessage' => json_encode([
                '退款类型' => $data['TypeID'] == 'onlymoney' ? "仅退款" : "退款退货",
                '退款原因' => $data['sReason'],
                '退款说明' => $data['sExplain'],
                '退款凭证' => $data['imgList'] ? $data['imgList'] : "--"
            ])
        ]);

        $event = new CommonEvent();
        $event->extraData = $refund;
        \Yii::$app->refund->trigger(static::EVENT_APPLY, $event);

    }

    /**
     * 根据OrderDetailID查询
     */
    public function findByOrderDetailID($id)
    {
        return static::find()->where(['OrderDetailID' => $id])->one();
    }

    /**
     * 是否全额退款
     */
    public function getBFullRefund()
    {
        if ($this->lItemTotal > $this->lRefundItem) {
            return false;
        } else {
            return true;
        }
    }
}