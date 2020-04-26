<?php

namespace myerm\shop\backend\models;

class Orderfront extends \myerm\shop\common\models\Order
{
    /**
     * 配置数据源
     * @return \yii\db\Connection
     */
    public static function getDb()
    {
        return \Yii::$app->get('db_wholesaler');
    }
    public static function tableName()
    {
        return 'order';
    }
}