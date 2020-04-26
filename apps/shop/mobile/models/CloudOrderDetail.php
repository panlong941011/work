<?php

namespace myerm\shop\mobile\models;

/**
 * 订单类
 */
class CloudOrderDetail extends \myerm\shop\common\models\Order
{
    /**
     * 配置数据源
     * @return \yii\db\Connection
     */
    public static function getDb()
    {
        return \Yii::$app->get('ds_cloud');
    }

    public static function tableName()
    {
        return '{{orderdetail}}';
    }

}