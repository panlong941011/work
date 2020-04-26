<?php

namespace myerm\shop\backend\models;

/**
 * 订单类
 */
class Order extends \myerm\shop\common\models\Order
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