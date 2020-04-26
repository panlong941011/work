<?php
/**
 * Created by PhpStorm.
 * User: ouyangyz <ouyangyanzhong@163.com>
 * Date: 2018-08-09
 * Time: 上午 11:11
 */

namespace myerm\shop\mobile\models;

use myerm\common\components\Func;
use myerm\shop\common\models\ShopModel;

/**
 * 商品渠道
 * Class Wholesale
 * @property string $dNewDate 新建时间
 * @property float(10,2) $fCostPrice 供货价
 * @property float(10,2) $fPrice 售价
 * @property float(10,2) $fSellerProfit 经销商利润
 * @property float(10,2) $fWholesalePrice 渠道价
 * @property float(10,2) $fWholesalerProfit 渠道商利润
 * @property int(11) $lID lID
 * @property int(32) $lSale 售出数量
 * @property int(32) $ProductID 商品
 * @property int(32) $SellerID 经销商\Yii::$app->formatter
 * @property string(255) $sName 名称
 * @property int(32) $WholesalerID 渠道商
 * @package myerm\shop\mobile\models
 * @author ouyangyz <ouyangyanzhong@163.com>
 * @time 2018-8-9 11:15:58
 */
class Wholesale extends ShopModel
{
	public function newOne($arr)
	{
		$data = new static();
		$data->loadData($arr);
		$data->sName = static::makeSholesaleCode();
		$data->dNewDate = \Yii::$app->formatter->asDatetime(time());
		
		$product = \Yii::$app->product->findByID($data->ProductID);
		$data->fCostPrice = $product->wholesaleCostPrice;
		$data->save();
		return $data;
	}
	
	/**
	 * 生成编号，长格式的时间+随机码
	 */
	public static function makeSholesaleCode()
	{
		return str_replace(['-', ' ', ':'], "", \Yii::$app->formatter->asDatetime(time())) . rand(10000, 99999);
	}
	
	/**
	 * 关联商品
	 * @return \yii\db\ActiveQuery
	 * @author ouyangyz <ouyangyanzhong@163.com>
	 * @time 2018-8-9 14:11:19
	 */
	public function getProduct()
	{
		return $this->hasOne(\Yii::$app->product::className(),['lID'=>'ProductID']);
	}
	
	/**
	 * 显示售价
	 * @author ouyangyz <ouyangyanzhong@163.com>
	 * @time 2018-8-9 14:34:38
	 */
	public function getShowSalePrice()
	{
		return $this->salePrice;
	}
	
	/**
	 * 实际售价
	 * @return float
	 * @author ouyangyz <ouyangyanzhong@163.com>
	 * @time 2018-8-9 16:13:44
	 */
	public function getSalePrice()
	{
		if (\Yii::$app->frontsession->wholesaler) {
			return $this->fCostPrice;
		} elseif(\Yii::$app->frontsession->MemberID == $this->SellerID){
			return $this->fWholesalePrice;
		}else {
			return $this->product->fShowSalePrice;
		}
	}
	
	/**
	 * 市场价
	 * @author ouyangyz <ouyangyanzhong@163.com>
	 * @time 2018-8-9 14:34:38
	 */
	public function getShowPrice()
	{
		return $this->product->fShowPrice;
	}
	
	/**
	 * 渠道商品是否下架
	 * @return bool
	 * @author ouyangyz <ouyangyanzhong@163.com>
	 * @time 2018-8-9 15:50:43
	 */
	public function getBOffSale()
	{
		$product = $this->product;
		return !$product->bDel && !$product->bSale || ($this->fCostPrice != $product->wholesaleCostPrice);
	}
	
	/**
	 * 关联渠道经销商
	 * @return \yii\db\ActiveQuery
	 * @author ouyangyz <ouyangyanzhong@163.com>
	 * @time 2018-8-13 15:18:01
	 */
	public function getSeller()
	{
		return $this->hasOne(\Yii::$app->member::className(), ['lID' => 'SellerID']);
	}
	
}