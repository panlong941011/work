<?php
/**
 * Created by PhpStorm.
 * User: ouyangyz <ouyangyanzhong@163.com>
 * Date: 2018-08-09
 * Time: 下午 4:52
 */

namespace myerm\shop\mobile\models;


use myerm\common\components\CommonEvent;
use myerm\common\components\Func;
use myerm\shop\common\models\ShopModel;
use myerm\shop\mobile\form\WholesaleOrderQueryForm;

/**
 * Class WholesaleOrder
 * @package myerm\shop\mobile\models
 * @property string $dNewDate 下单时间
 * @property float(10,2) $fCostPrice 供货价
 * @property float(10,2) $fPrice 售价
 * @property float(10,2) $fFinalPrice 成交价
 * @property float(10,2) $fSellerProfit 经销商利润
 * @property float(10,2) $fWholesalePrice 渠道价
 * @property float(10,2) $fWholesalerProfit 渠道商利润
 * @property int(11) $lID 渠道订单ID
 * @property int(32) $lQty 数量
 * @property int(32) $MemberID 买家
 * @property int(32) $OrderID 订单
 * @property int(32) $ProductID 商品
 * @property int(32) $SellerID 经销商
 * @property string(255) $sName 渠道订单号
 * @property int(32) $WholesaleID 商品渠道
 * @property int(32) $WholesalerID 渠道商
 *
 * 构建字段
 * @param object wholesale 关联渠道商品
 * @property object wholesaler 关联渠道商
 * @property object seller 关联渠道经销商
 * @property object member 关联买家
 * @author ouyangyz <ouyangyanzhong@163.com>
 * @time 2018-8-9 16:52:33
 */
class WholesaleOrder extends ShopModel
{
	/**
	 * 处理下单后的事件
	 * @param CommonEvent $event
	 * @throws \yii\base\InvalidConfigException
	 * @author ouyangyz <ouyangyanzhong@163.com>
	 * @time 2018-8-9 17:26:34
	 */
	public static function saveOrder(CommonEvent $event)
	{
		/* @var OrderDetail $orderDetail */
		$orderDetail = $event->extraData['orderDetail'];
		
		/* @var Wholesale $wholesale */
		$wholesale = $event->extraData['wholesale'];
		
		//保存数据
		$data = new static();
		$data->sName = static::makeCode();
		$data->WholesaleID = $wholesale->lID;
		$data->OrderID = $orderDetail->OrderID;
		$data->WholesalerID = $wholesale->WholesalerID;
		$data->SellerID = $wholesale->SellerID;
		$data->MemberID = \Yii::$app->frontsession->MemberID;
		$data->dNewDate = \Yii::$app->formatter->asDatetime(time());
		$data->ProductID = $orderDetail->ProductID;
		$data->fCostPrice = $wholesale->fCostPrice;
		$data->fWholesalePrice = $wholesale->fWholesalePrice;
		$data->fPrice = $wholesale->product->fPrice;
		$data->fFinalPrice = $wholesale->salePrice;
		$data->lQty = $orderDetail->lQuantity;
		$data->save();
	}
	
	/**
	 * 生成编号，长格式的时间+随机码
	 */
	public static function makeCode()
	{
		return str_replace(['-', ' ', ':'], "", \Yii::$app->formatter->asDatetime(time())) . rand(10000, 99999);
	}
	
	/**
	 * 订单交易成功
	 * @param CommonEvent $event
	 * @author ouyangyz <ouyangyanzhong@163.com>
	 * @time 2018-8-13 11:18:35
	 */
	public static function orderSuccess(CommonEvent $event)
	{
		$order = $event->extraData;
		$wholesaleOrder = \Yii::$app->wholesaleorder->find()->where(['OrderID' => $order->lID])->one();
		if ($wholesaleOrder) {
			//更新渠道订单利润
			$wholesaleOrder->fWholesalerProfit = \Yii::$app->wholesaleorder->computeProfit($wholesaleOrder)['fWholesalerProfit'];
			$wholesaleOrder->fSellerProfit = \Yii::$app->wholesaleorder->computeProfit($wholesaleOrder)['fSellerProfit'];
			$wholesaleOrder->save();
			
			//更新渠道商品已售
			$wholesaleOrder->wholesale->lSale += $wholesaleOrder->lQty;
			$wholesaleOrder->wholesale->save();
		}
		
	}
	
	/**
	 * 渠道商利润
	 * @return mixed
	 * @author ouyangyz <ouyangyanzhong@163.com>
	 * @time 2018-8-14 18:17:21
	 */
	public function getWholesalerProfit()
	{
		$result = \Yii::$app->wholesaleorder->computeProfit($this);
		return $result['fWholesalerProfit'];
	}
	
	/**
	 * 经销商利润
	 * @return mixed
	 * @author ouyangyz <ouyangyanzhong@163.com>
	 * @time 2018-8-14 18:17:41
	 */
	public function getSellerProfit()
	{
		$result = \Yii::$app->wholesaleorder->computeProfit($this);
		return $result['fSellerProfit'];
	}
	
	/**
	 *
	 * 计算利润
	 * @param $WholesaleOrder
	 * @return array
	 * @author ouyangyz <ouyangyanzhong@163.com>
	 * @time 2018-8-16 16:30:57
	 */
	public function computeProfit($WholesaleOrder)
	{
		/* @var WholesaleOrder $WholesaleOrder */
		$fWholesalerProfit = ($WholesaleOrder->fPrice - $WholesaleOrder->fCostPrice) * $WholesaleOrder->lQty;
		$fSellerProfit = ($WholesaleOrder->fPrice - $WholesaleOrder->fWholesalePrice) * $WholesaleOrder->lQty;
		
		$wholesaler = \Yii::$app->wholesaler->findByID($WholesaleOrder->MemberID);
		//渠道商购买，利润为0
		if ($wholesaler) {
			$fWholesalerProfit = 0;
			$fSellerProfit = 0;
		}
		
		//经销商自己买，渠道利润=渠道价-供货价，经销商利润为0
		if (!$WholesaleOrder->SellerID) {
			$fSellerProfit = 0;
		} elseif ($WholesaleOrder->SellerID == $WholesaleOrder->MemberID) {
			$fWholesalerProfit = ($WholesaleOrder->fWholesalePrice - $WholesaleOrder->fCostPrice) * $WholesaleOrder->lQty;
			$fSellerProfit = 0;
		}
		
		//利润扣除退款
		$arrRefund = \Yii::$app->refund->find()
			//->select(['lID','fRefundProduct', 'lItemTotal'])
			->where(['OrderID' => $WholesaleOrder->order->lID, 'StatusID' => \Yii::$app->refund::STATUS_SUCCESS])
			->all();
		if ($arrRefund) {
			foreach ($arrRefund as $refund) {
				$fPercent = $refund->fRefundProduct / $refund->orderDetail->fTotal;
				$fWholesalerProfit -= $fWholesalerProfit * $fPercent;
				$fSellerProfit -= $fSellerProfit * $fPercent;
			}
		}
		return ['fWholesalerProfit' => Func::numbleFormat($fWholesalerProfit), 'fSellerProfit' => Func::numbleFormat($fSellerProfit)];
	}
	
	/**
	 * 实时计算未结算金额，状态是已付款和递送中的订单
	 */
	public function computeUnsettlement($WholesalerID, $sType = 'all')
	{
		$arrWholesaleOrder = static::find()
			->where(['WholesalerID' => $WholesalerID])
			->with('order')
			->all();
		$fCommission = 0.00;
		foreach ($arrWholesaleOrder as $wholesaleOrder) {
			if (in_array($wholesaleOrder->order->StatusID, [\Yii::$app->order::STATUS_PAID, \Yii::$app->order::STATUS_DELIVERED])) {
				$fCommission += $wholesaleOrder->wholesalerProfit;
			}
		}
		return floatval($fCommission);
	}
	
	
	/**
	 * 实时计算已结算金额，状态是交易成功的订单
	 */
	public function computeSttlement($WholesalerID, $sType = 'all')
	{
		$arrWholesaleOrder = static::find()
			->where(['WholesalerID' => $WholesalerID])
			->with('order')
			->all();
		$fCommission = 0.00;
		foreach ($arrWholesaleOrder as $wholesaleOrder) {
			if ($wholesaleOrder->order->StatusID == \Yii::$app->order::STATUS_SUCCESS) {
				$fCommission += $wholesaleOrder->wholesalerProfit;
			}
		}
		return floatval($fCommission);
	}
	
	/**
	 * 关联商品渠道
	 * @return \yii\db\ActiveQuery
	 * @author ouyangyz <ouyangyanzhong@163.com>
	 * @time 2018-8-13 11:35:25
	 */
	public function getWholesale()
	{
		return $this->hasOne(\Yii::$app->wholesale::className(), ['lID' => 'WholesaleID']);
	}
	
	/**
	 * 关联渠道商
	 * @return \yii\db\ActiveQuery
	 * @author ouyangyz <ouyangyanzhong@163.com>
	 * @time 2018-8-13 15:18:55
	 */
	public function getWholesaler()
	{
		return $this->hasOne(\Yii::$app->wholesaler::className(), ['lID' => 'WholesalerID']);
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

	/**
	 * 关联买家
	 * @return \yii\db\ActiveQuery
	 * @author ouyangyz <ouyangyanzhong@163.com>
	 * @time 2018-8-13 15:18:16
	 */
	public function getMember()
	{
		return $this->hasOne(\Yii::$app->member::className(), ['lID' => 'MemberID']);
	}
	
	public function getOrder()
	{
		return $this->hasOne(\Yii::$app->order::className(), ['lID' => 'OrderID']);
	}
	
	public function getArrOrderDetail()
	{
		return $this->hasMany(OrderDetail::className(), ['OrderID' => 'OrderID']);
	}
	
	/**
	 * 待结算列表
	 * @param $config
	 * @return array|\yii\db\ActiveRecord[]
	 * @author ouyangyz <ouyangyanzhong@163.com>
	 * @time 2018-8-14 21:18:58
	 */
	public function getList($config)
	{
		$form = new WholesaleOrderQueryForm();
		$form->StatusID = $config['StatusID'];
		$form->page = $config['page'] ? intval($config['page']) : 1;
		$form->WholesalerID = \Yii::$app->frontsession->wholesaler->lID;
		$arrWholesaleOrder = $form->run();
		return $arrWholesaleOrder;
		$lTotal = $form->count();
		
		$orderSearch = static::find();
		
		if ($config['type'] == '全部' || !$config['type']) {
			$orderSearch->andWhere([
				'OR',
				['SellerID' => \Yii::$app->frontsession->seller->lID],
				['UpSellerID' => \Yii::$app->frontsession->seller->lID],
				['UpUpSellerID' => \Yii::$app->frontsession->seller->lID],
			]);
		} elseif ($config['type'] == '销售提成') {
			$orderSearch->andWhere(['SellerID' => \Yii::$app->frontsession->seller->lID]);
		} elseif ($config['type'] == '一级团队提成') {
			$orderSearch->andWhere(['UpSellerID' => \Yii::$app->frontsession->seller->lID]);
		} elseif ($config['type'] == '二级团队提成') {
			$orderSearch->andWhere(['UpUpSellerID' => \Yii::$app->frontsession->seller->lID]);
		}
		
		$orderSearch->andWhere(['StatusID' => ['paid', 'delivered']]);
		
		$orderSearch->limit(10);
		
		$orderSearch->offset(($page - 1) * 10);
		$orderSearch->orderBy('dNewDate DESC');
		$orderSearch->with('arrDetail');
		
		return $orderSearch->all();
	}
}