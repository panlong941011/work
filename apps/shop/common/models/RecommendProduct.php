<?php

namespace myerm\shop\common\models;

/**
 * 会员类
 */
class RecommendProduct extends ShopModel
{
    const RECOMMEND = 1;//店长推荐
    const SELF = 3;//自有商品

    public static function getDb()
    {
        return \Yii::$app->get('ds_cloud');
    }
}