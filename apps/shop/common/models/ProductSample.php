<?php

namespace myerm\shop\common\models;

use myerm\shop\backend\models\ProductModifyLog;
use yii\base\UserException;

class ProductSample extends ShopModel
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