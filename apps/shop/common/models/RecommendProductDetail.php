<?php

namespace myerm\shop\common\models;

/**
 * 会员类
 */
class RecommendProductDetail extends ShopModel
{
    public static function getDb()
    {
        return \Yii::$app->get('ds_cloud');
    }

    /**
     * 关联商品
     */
    public function getProduct()
    {
        return $this->hasOne(Product::className(), ['lID' => 'ProductID']);
    }
}