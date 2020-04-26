<?php

namespace myerm\shop\common\models;

use myerm\backend\system\models\SysField;
use myerm\common\components\CommonEvent;
use myerm\common\libs\NewID;
use myerm\shop\mobile\models\OrderPayLog;

/**
 * 退款协商记录
 */
class RefundMoneyBack extends ShopModel
{
    /**
     * 处理退款申请
     * @param $data
     * @return bool
     */
    public static function refundSuccess(CommonEvent $event)
    {
        $refund = $event->extraData;

        $payLog = OrderPayLog::findOne(['sTradeNo' => $refund->order->sTradeNo]);

        $back = new static();
        $back->sName = "B" . NewID::make();
        $back->RefundID = $refund->lID;
        $back->OrderID = $refund->OrderID;
        $back->OrderDetailID = $refund->OrderDetailID;
        $back->TypeID = $payLog->PaymentID;
        $back->fRefund = $refund->fRefundReal;
        $back->dNewDate = \Yii::$app->formatter->asDatetime(time());
        $back->MemberID = $refund->MemberID;
        $back->StatusID = 'pending';//退款待处理
        $back->save();

        return true;
    }

    /**
     * 提交退款申请到第三方支付
     */
    public static function submit()
    {
        $arrBack = static::find()->where(['StatusID' => 'pending'])->with('order')->all();
        foreach ($arrBack as $back) {
            $payLog = OrderPayLog::findOne(['sTradeNo' => $back->order->sTradeNo]);
            if ($payLog->PaymentID == 'wx') {//微信退款
                $result = \Yii::$app->wechat->payment->refundByTransactionId($payLog->sTransactionID, $back->sName,
                    $payLog->fPaid * 100, $back->fRefund * 100);
                \Yii::trace(json_encode($result->toArray()));

                if ($result->return_code == 'FAIL') {
                    $back->StatusID = 'fail';
                    $back->sError = $result->return_msg;
                } elseif ($result->result_code == 'FAIL') {
                    $back->StatusID = 'fail';
                    $back->sError = $result->err_code_des;
                } elseif ($result->result_code == 'SUCCESS') {
                    $back->StatusID = 'processing';
                    $back->dProcessingDate = \Yii::$app->formatter->asDatetime(time());
                }

                $back->sInfo = json_encode($result->toArray());
                $back->save();
            } elseif ($payLog->PaymentID == 'gold') {//退回到个人的金币

                \Yii::$app->goldflow->change([
                    'fChange' => $back->fRefund,
                    'sName' => '【' . $back->order->sName . '】' . mb_substr($back->orderDetail->sName, 0, 6) . '...退款成功',
                    'MemberID' => $back->MemberID,
                    'NewUserID' => null,
                    'arrOrderID' => [$back->OrderID],
                    'OrderDetailID' => $back->OrderDetailID,
                    'RefundID' => $back->RefundID,
                    'TypeID' => 1
                ]);

                $back->dProcessingDate = \Yii::$app->formatter->asDatetime(time());
                $back->StatusID = 'success';
                $back->dSuccessDate = \Yii::$app->formatter->asDatetime(time());
                $back->sRefundAccout = "金币";
                $back->save();
            }
        }
    }

    public static function query()
    {
        $arrBack = static::find()->where(['StatusID' => 'processing'])->with('order')->all();
        foreach ($arrBack as $back) {
            if ($back->TypeID == 'wx') {
                $result = \Yii::$app->wechat->payment->queryRefundByRefundNo($back->sName);
                if ($result->refund_status_0 == 'SUCCESS') {
                    $back->StatusID = 'success';
                    $back->dSuccessDate = $result->refund_success_time_0;
                    $back->sRefundAccout = $result->refund_recv_accout_0;
                    $back->save();
                }
            }
        }
    }

    public static function reset()
    {
        $arrBack = static::find()->where(['StatusID' => 'fail'])->all();
        foreach ($arrBack as $back) {
            $back->StatusID = 'pending';
            $back->save();
        }
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
     * 关联订单明细
     * @return \yii\db\ActiveQuery
     */
    public function getOrderDetail()
    {
        return $this->hasOne(OrderDetail::className(), ['lID' => 'OrderDetailID']);
    }

    /**
     * 关联退款
     * @return \yii\db\ActiveQuery
     */
    public function getRefund()
    {
        return $this->hasOne(Refund::className(), ['lID' => 'RefundID']);
    }


    /**
     * 获取状态
     * @return mixed
     */
    public function getSStatus()
    {
        $StatusID = $this->StatusID;
        return \Yii::$app->cache->getOrSet("refundstatus", function () {
            $field = SysField::findOne(['sObjectName' => 'Shop/Refund', 'sFieldAs' => 'StatusID']);
            return parse_ini_string($field->sEnumOption);
        })[$StatusID];
    }

}