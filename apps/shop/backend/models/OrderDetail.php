<?php

namespace myerm\shop\backend\models;

/**
 * 订单明细
 */
class OrderDetail extends \myerm\shop\common\models\OrderDetail
{
    /**
     * 配置数据源
     * @return \yii\db\Connection
     */
    public static function getDb()
    {
        return \Yii::$app->get('ds_cloud');
    }
}