<?php
/**
 * Created by PhpStorm.
 * User: ouyangyz <ouyangyanzhong@163.com>
 * Date: 2018-08-13
 * Time: 上午 11:16
 */

namespace myerm\shop\mobile\models;

use myerm\common\components\CommonEvent;
use myerm\common\components\Func;
use myerm\shop\common\models\ShopModel;
use myerm\shop\mobile\form\WholesalerFlowQueryForm;

/**
 * Class WholesaleFlow
 * @package myerm\shop\mobile\models
 * @property string $dNewDate 变动时间
 * @property float(10,2) $fChange 变动金额
 * @property float(10,2) $fChangeAfter 变动后金额
 * @property float(10,2) $fChangeBefore 变动前金额
 * @property float(10,2) $fWithdraw 可提现余额
 * @property int(11) $lID lID
 * @property int(32) $MemberIDSellerID 用户昵称
 * @property string(250) $sCommissionType 提成类型
 * @property int(32) $SellerID 经销商真实姓名
 * @property string(50) $sMobile 联系方式
 * @property string(255) $sName 名称
 * @property int(32) $TypeID 收支类型 (收入:1;支出:2)
 * @property int(32) $WholesaleOrderID 渠道订单
 * @property int(32) $WholesalerID 真实姓名
 * @author ouyangyz <ouyangyanzhong@163.com>
 * @time 2018-8-13 11:17:35
 */
class WholesaleFlow extends ShopModel
{
	const TYPE_INCOME = '1';//收入
	const TYPE_EXPENSES = '2';//支出
	
	const EVENT_CHANGE = 'change';
	
	/**
	 * 订单交易成功
	 * @param CommonEvent $event
	 * @author ouyangyz <ouyangyanzhong@163.com>
	 * @time 2018-8-13 11:18:35
	 */
	public static function orderSuccess(CommonEvent $event)
	{
		$order = $event->extraData;
		$wholesaleOrder = \Yii::$app->wholesaleorder->find()->where(['OrderID'=>$order->lID])->one();
		/* @var WholesaleOrder $wholesaleOrder*/
		if ($wholesaleOrder) {
			$flow = new static();
			$flow->sName = "订单【" . $order->sName . "】交易成功，获得渠道利润";
			$flow->sMobile = $wholesaleOrder->wholesaler->sMobile;
			$flow->dNewDate = $order->dReceiveDate;
			$flow->WholesalerID = $wholesaleOrder->WholesalerID;
			$flow->fChange = $wholesaleOrder->fWholesalerProfit;
			$flow->fChangeBefore = $wholesaleOrder->wholesaler->computeWithdraw;
			$flow->fChangeAfter = $flow->fChangeBefore + $flow->fChange;
			$flow->fWithdraw = $flow->fChangeAfter;
			$flow->WholesaleOrderID = $wholesaleOrder->lID;
			$flow->sCommissionType = "渠道利润";
			$flow->SellerID = $wholesaleOrder->SellerID;
			$flow->TypeID = static::TYPE_INCOME;
			$flow->save();
			$event = new CommonEvent();
			$event->extraData = $flow;//传值交易流水
			\Yii::$app->wholesaleflow->trigger(static::EVENT_CHANGE, $event);
		}
	}
	
	
	/**
	 * 实时统计$WholesalerID的可提现余额
	 * @param $WholesalerID
	 * @return mixed
	 */
	public function computeWithdraw($WholesalerID)
	{
		return static::find()->where(['WholesalerID' => $WholesalerID])->sum("fChange");
	}
	
	/**
	 * 实时统计$WholesalerID的已提现金额
	 * @param $WholesalerID
	 * @return mixed
	 */
	public function computeWithdrawed($WholesalerID)
	{
		return abs(static::find()->where(['WholesalerID' => $WholesalerID, 'TypeID' => static::TYPE_EXPENSES])->sum("fChange"));
	}
	
	
	/**
	 * 实时统计$WholesalerID的累计收入
	 * @param $WholesalerID
	 * @return mixed
	 */
	public function computeSumIncome($WholesalerID)
	{
		return static::find()->where(['WholesalerID' => $WholesalerID, 'TypeID' => static::TYPE_INCOME])->sum("fChange");
	}
	
	/**
	 * 流水列表
	 */
	public function flowList($config)
	{
		$flow = new WholesalerFlowQueryForm();
		$flow->page = $config['page'] ? intval($config['page']) : 1;
		$flow->WholesalerID = \Yii::$app->frontsession->wholesaler->lID;
		$flow->TypeID = $config['type'];
		$flow->Date = $config['date'];
		return $flow->run();
	}
	
	public function getWholesaleOrder()
	{
		return $this->hasOne(\Yii::$app->wholesaleorder::className(),['lID'=>'WholesaleOrderID']);
	}
	
	/**
	 * 提现付款成功
	 * @param CommonEvent $event
	 * @author ouyangyz <ouyangyanzhong@163.com>
	 * @time 2018-8-14 21:07:44
	 */
	public static function withdrawPaySuccess(CommonEvent $event)
	{
		$log = $event->extraData;
		
		$flow = new static();
		$flow->sName = "申请提现";
		$flow->WholesalerID = $log->WholesalerID;
		$flow->TypeID = 2;
		
		$flow->fChange = -$log->fMoney;
		$flow->fChangeBefore = $log->seller->computeWithdraw;
		$flow->fChangeAfter = $flow->fChangeBefore + $flow->fChange;
		$flow->fWithdraw = $flow->fChangeAfter;
		$flow->dNewDate = $log->dCompleteDate;
		$flow->WithdrawID = $log->lID;
		$flow->save();
	}
}