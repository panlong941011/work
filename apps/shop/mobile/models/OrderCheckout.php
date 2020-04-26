<?php

namespace myerm\shop\mobile\models;

use myerm\common\libs\NewID;
use myerm\shop\common\models\SecKill;
use myerm\shop\common\models\ShopModel;

/**
 * 订单确认商品
 * ============================================================================
 * 版权所有 2010-2017 三只象（厦门）网络科技有限公司，并保留所有权利。
 * 网站地址: http://www.myerm.cn
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和
 * 使用；不允许对程序代码以任何形式任何目的的再发布。
 * ============================================================================
 * @author 陈鹭明
 * @time 2017年10月19日 22:18:43
 * @version v1.0
 */
class OrderCheckout extends ShopModel
{
	
	/**
	 * 保存确认要购买的商品
	 */
	public function add($data)
	{
		$product = \Yii::$app->product->findByID($data['ProductID']);
		$product->sSKU = $data['sSKU'];
		
		$checkout = new static();
		$checkout->ID = NewID::make();
		$checkout->SessionID = \Yii::$app->frontsession->ID;
		$checkout->ProductID = $data['ProductID'];
		$checkout->WholesaleID = $data['WholesaleID']; //渠道商品 ouyangyz 2018-8-9 16:08:42
		$checkout->lQuantity = $data['lQty'];
		$checkout->sSKU = $data['sSKU'];
		$checkout->sPic = $product->sMasterPic;
		$checkout->dNewDate = $data['dNewDate'] ? $data['dNewDate'] : \Yii::$app->formatter->asDatetime(time());
        $checkout->GroupID = $data['GroupID'];
        $checkout->bGroup = $data['bGroup'];
		if ($data['CartID']) {
			$checkout->CartID = $data['CartID'];
		}
		
		$checkout->save();
		
		
		return true;
	}
	
	/**
	 * 清空购物车中确认的商品
	 */
	public function clear($SessionID = null)
	{
		if ($SessionID) {
			static::deleteAll(['SessionID' => $SessionID]);
		} else {
			static::deleteAll(['SessionID' => \Yii::$app->frontsession->ID]);
		}
	}
	
	/**
	 * 获取确认购买的购物车商品
	 */
	public function getArrCheckoutProduct()
	{
		return static::find()->where(['SessionID' => \Yii::$app->frontsession->ID])->orderBy("dNewDate DESC")->with('product')->all();
	}
	
	/**
	 * 关联商品
	 * @return \yii\db\ActiveQuery
	 */
	public function getProduct()
	{
		return $this->hasOne(\Yii::$app->product::className(),
			['lID' => 'ProductID'])->with('shiptemplate')->with('nodelivery')->with('supplier');
	}
	
	/**
	 * 获取成交价
	 * @return mixed
	 */
	public function getFPrice()
	{
		//判断渠道商品 ouyangyz 2018-8-9 16:23:31
		if ($this->WholesaleID) {
			return $this->wholesale->salePrice;
		}
		
		return $this->product->fSalePrice;
	}
	
	/**
	 * 获取小计
	 * @return mixed
	 */
	public function getFTotal()
	{
		return $this->fPrice * $this->lQuantity;
	}
	
	/**
	 * 获取供货价
	 * @return mixed
	 */
	public function getFCostPrice()
	{
		//如果有秒杀活动，返回活动供货价 panlong 2019年3月14日14:34:38
		if ($this->product->secKill) {
			$fWholeSale = $this->product->secKill->fWholesale ? $this->product->secKill->fWholesale : $this->product->costPrice;
			return $fWholeSale;
		}
		
		return $this->product->costPrice;
	}
	
	/**
	 * 获得订单确认中商品的失效状态，如果返回空，表示有效商品
	 */
	protected function getSInvalidStatus()
	{
		$invalid_state = "";
		
		$product = $this->product;
		$product->sSKU = $this->sSKU;
		
		$secKill = $product->secKill;
		if ($secKill && $secKill->sStatus == '未开始') {
			$secKill = null;
		}
		
		if (!$product->bSale) {
			$invalid_state = Product::STATUS_OFFSALE;
		} elseif ($product->bDel) {
			$invalid_state = Product::STATUS_DEL;
		} elseif (!$secKill && $product->bSaleOut) {
			$invalid_state = Product::STATUS_SALEOUT;
		} elseif ($secKill && $product->secKill->bSaleOut) {
			$invalid_state = SecKill::STATUS_SALEOUT;
		} elseif ($product->sku && $product->stock < $this->lQuantity) {
			$invalid_state = Product::STATUS_SPEC_LOW_STOCK;
		} elseif ($secKill && $product->sku && $product->stock < $this->lQuantity) {
			$invalid_state = Product::STATUS_SPEC_LOW_STOCK;
		} elseif ($secKill && !$product->sku && $product->stock < $this->lQuantity) {
			$invalid_state = Product::STATUS_LOW_STOCK;
		} elseif (!$secKill && $product->lStock < $this->lQuantity) {
			$invalid_state = Product::STATUS_LOW_STOCK;
		} elseif ($this->sSKU && !$product->sku) {
			$invalid_state = Product::STATUS_SPEC_NOEXISTS;
		} elseif (!$this->sSKU && $product->arrSku) {
			$invalid_state = Product::STATUS_SPEC_NOSELECTED;
		} elseif ($this->WholesaleID && $this->wholesale->bOffSale) {//判断渠道商品是否失效 ouyangyz 2018-8-20 16:14:31
			$invalid_state = Product::STATUS_OFFSALE;
		}
		
		return $invalid_state;
	}
	
	/**
	 * 关联渠道商品
	 * @return \yii\db\ActiveQuery
	 * @author ouyangyz <ouyangyanzhong@163.com>
	 * @time 2018-8-9 16:22:46
	 */
	public function getWholesale()
	{
		return $this->hasOne(\Yii::$app->wholesale::className(), ['lID' => 'WholesaleID']);
	}
	
	/**
	 * 获取免邮成本
	 * @return mixed
	 * @author panlong
	 * @time 2019年3月14日15:10:01
	 */
	public function getFFreeShipCost()
	{
		//如果有秒杀活动，返回活动免邮成本 panlong 2019年3月14日14:34:38
		if ($this->product->secKill) {
			$fFreeShipCost = $this->product->secKill->fFreeShippingCost ? $this->product->secKill->fFreeShippingCost : $this->product->fFreeShipCost;
			return $fFreeShipCost;
		}
		
		return $this->product->fFreeShipCost;
	}
	
	/**
	 * 获取成本控制
	 * @return mixed
	 * @author panlong
	 * @time 2019年3月14日15:10:01
	 */
	public function getFCostControl()
	{
		//如果有秒杀活动，返回活动成本控制 panlong 2019年3月14日14:34:38
		if ($this->product->secKill) {
			$fCostControl = $this->product->secKill->fCostControl ? $this->product->secKill->fCostControl : $this->product->fCostControl;
			return $fCostControl;
		}
		
		return $this->product->fCostControl;
	}
	
	/**
	 * 获取运费调节
	 * @return mixed
	 * @author panlong
	 * @time 2019年3月14日15:10:01
	 */
	public function getFShipAdjust()
	{
		//如果有秒杀活动，返回活动成本控制 panlong 2019年3月14日14:34:38
		if ($this->product->secKill) {
			$fShipAdjust = $this->product->secKill->fFreightRegulation ? $this->product->secKill->fFreightRegulation : $this->product->fShipAdjust;
			return $fShipAdjust;
		}
		
		return $this->product->fShipAdjust;
	}
	
	/**
	 * 获取确认购买的购物车商品
	 */
	public function getCheckoutProduct()
	{
		return static::find()->where(['SessionID' => \Yii::$app->frontsession->ID])->orderBy("dNewDate DESC")->with('product')->one();
	}
}