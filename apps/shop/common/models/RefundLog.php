<?php

namespace myerm\shop\common\models;

use myerm\backend\system\models\SysField;

/**
 * 退款协商记录
 */
class RefundLog extends ShopModel
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

    /**
     * 获取状态
     * @return mixed
     */
    public function getStatus()
    {
        $arr = ['wait' => '供应商待确认',
            'apply' => '供应商驳回',
            'appeal' => '渠道商申诉',
            'success' => '退款成功',
            'closed' => '退款关闭'
        ];
        return $arr[$this->StatusID];
    }

}