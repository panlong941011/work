<?php

namespace myerm\shop\mobile\models;

use myerm\common\components\CommonEvent;
use myerm\common\components\simple_html_dom;
use myerm\shop\backend\models\ProductModifyLog;
use myerm\shop\common\models\MallConfig;
use myerm\shop\common\models\ProductRecommend;
use myerm\shop\common\models\ProductSample;
use myerm\shop\common\models\Seller;
use myerm\shop\common\models\ShopModel;

/**
 * 商品类
 */
class Material extends ShopModel
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
     * 获取对应的详情
     * @return \yii\db\ActiveQuery
     */
    public function getDetail()
    {
        return $this->hasMany(MaterialDetail::className(), ['MaterialID' => 'lID']);
    }

    /**
     * 获取对应的详情
     * @return \yii\db\ActiveQuery
     */
    public function getProduct()
    {
        return $this->hasOne(Product::className(), ['lID' => 'ProductID']);
    }
}