<?php

namespace myerm\shop\common\models;

use myerm\shop\common\models\Product;
use myerm\shop\common\models\ProductSKU;
use myerm\shop\common\models\Buyer;

/**
 * 商品库存变动记录类
 * ============================================================================
 * 版权所有 2010-2017 三只象（厦门）网络科技有限公司，并保留所有权利。
 * 网站地址: http://www.myerm.cn
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和
 * 使用；不允许对程序代码以任何形式任何目的的再发布。
 * ============================================================================
 * @author 何城城
 * @time 2018年05月08日 18:50:58
 * @version v1.0
 */
class ProductStockChange extends ShopModel
{
	/**
	 * 配置数据源
	 * @return null|object|\yii\db\Connection
	 * @throws \yii\base\InvalidConfigException
	 * @author hechengcheng
	 * @time 2019/5/9 17:05
	 */
	public static function getDb()
	{
		return \Yii::$app->get('ds_cloud');
	}
	
    /**
     * 生成商品库存变动记录
     * @arrParam array $arrParam
     * @return string
     * @author hcc
     * @time 2018-5-15
     * */
    public function productStockChange($arrParam)
    {
        //判断子平台抛过来的数据中是否存在sName by hcc on 2018/7/2
        if ($arrParam->sName) {
            $sName = $arrParam->sName;
            unset($arrParam->sName);
        } else {
            $sName = static::makeOrderCode();
        }

        foreach ($arrParam as $productID => $productInfo) {
            $productStockChange = new static();
            $buyer = Buyer::findBysIP($productInfo->sIP);
            $product = Product::findByID($productID);

            //判断是否有规格
            if ($productInfo->sSKU) {
                $productSKU = ProductSKU::findByProductIDAndSKU($productID, $productInfo->sSKU);
                $productSKU->lStock -= $productInfo->lQuantity;
                $productSKU->save();

                $lChangeBefore = $productSKU->lStock;
            } else {
                $lChangeBefore = $product->lStock;
            }

            $product->lStock -= $productInfo->lQuantity;
            $product->save();

            $productStockChange->sName = $sName;
            $productStockChange->dNewDate = \Yii::$app->formatter->asDatetime(time());
            $productStockChange->BuyerID = $buyer->lID;
            $productStockChange->ProductID = $productID;
            $productStockChange->sSKU = $productInfo->sSKU;
            $productStockChange->lChange = $productInfo->lQuantity;
            $productStockChange->lChangeBefore = $lChangeBefore;
            $productStockChange->lChangeAfter = $productStockChange->lChangeBefore - $productStockChange->lChange;
            $productStockChange->save();
        }

        return $sName;
    }

    /**
     * 生成订单号，长格式的时间+随机码
     */
    public static function makeOrderCode()
    {
        return str_replace(['-', ' ', ':'], "", \Yii::$app->formatter->asDatetime(time())) . rand(10000, 99999);
    }

    /**
     * 查找未付款的库存变动记录
     * @author panlong
     * @time 2018-06-11
     */
    public static function getUnpaidInfo()
    {
        return static::find()->where("OrderID is null and dCloseDate is null ")->All();
    }
	
	/**
	 * 关联商品表
	 * @return \yii\db\ActiveQuery
	 * @author hechengcheng
	 * @time 2019/5/13 15:09
	 */
	public function getProduct()
	{
		return $this->hasOne(Product::className(), ['lID' => 'ProductID']);
	}
}