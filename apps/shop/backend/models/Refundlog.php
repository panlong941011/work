<?php

namespace myerm\shop\backend\models;

/**
 * 订单类退款日志
 */
class Refundlog extends \myerm\shop\common\models\RefundLog
{
    /**
     * 配置数据源
     * @return \yii\db\Connection
     */
    public static function getDb()
    {
        return \Yii::$app->get('ds_cloud');
    }
    /**
     * 保存订单付款日志
     * @param $data
     * @return bool
     */
    public static function saveLog($data)
    {
        $log = new static();
        $log->RefundID = $data['RefundID'];
        $log->sWhoDo = $data['sWhoDo'];
        $log->StatusID = $data['StatusID'];
        $log->dNewDate = \Yii::$app->formatter->asDatetime(time());
        $log->sMessage = $data['sMessage'];
        $log->save();

        return true;
    }
}