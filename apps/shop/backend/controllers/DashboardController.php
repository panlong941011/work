<?php

namespace myerm\shop\backend\controllers;

use myerm\shop\common\models\Buyer;
use myerm\shop\common\models\Order;
use myerm\shop\common\models\Recharge;
use myerm\shop\common\models\Refund;
use myerm\shop\common\models\Withdraw;
use myerm\shop\mobile\models\Supplier;


/**
 * 外框架
 */
class DashboardController extends BackendController
{
	public function actionHome()
	{
		$data = [];
		if ($this->supplier) {
			//供应商
			
			//待发货订单
			$data['lShipOrders'] = Order::find()->where([
				'SupplierID' => $this->supplier->lID,
				'StatusID' => 'paid'
			])->andWhere("IFNULL(RefundStatusID, '')<>'refunding'")->count();
			
			//退款中订单
			$data['lRefundingOrders'] = Order::find()->where([
				'SupplierID' => $this->supplier->lID,
				'RefundStatusID' => 'refunding'
			])->count();
			
			//今日已完成订单
			$data['lSuccessOrders'] = Order::find()->where([
				'SupplierID' => $this->supplier->lID,
				'StatusID' => 'success'
			])->andWhere([
				'>=',
				'dReceiveDate',
				\Yii::$app->formatter->asDate(time()) . " 00:00:00"
			])->andWhere([
				'<',
				'dReceiveDate',
				\Yii::$app->formatter->asDate(time()) . " 23:59:59"
			])->count();
			
			//今日成交额
			$data['fProfit'] = Order::find()->where([
				'SupplierID' => $this->supplier->lID,
				'StatusID' => 'success'
			])->andWhere([
				'>=',
				'dReceiveDate',
				\Yii::$app->formatter->asDate(time()) . " 00:00:00"
			])->andWhere([
				'<',
				'dReceiveDate',
				\Yii::$app->formatter->asDate(time()) . " 23:59:59"
			])->sum('fSupplierIncome');
			
			$data['sToday'] = \Yii::$app->formatter->asDate(time()) . " - " . \Yii::$app->formatter->asDate(time());
			
			$Supplier = Supplier::findByID($this->supplier->lID);
			//可提现
			$data['fBalance'] = $Supplier->fBalance;
			//待结算
			$data['fUnsettlement'] = $Supplier->fUnsettlement;
			//已提现
			$data['fWithdrawed'] = $Supplier->fWithdrawed;
			//累积收入
			$data['fSumIncome'] = $Supplier->fSumIncome;
			
			return $this->render("supplierhome", $data);
		} elseif ($this->buyer) {
			//渠道商
			//待发货订单
			$data['lShipOrders'] = Order::find()->where([
				'BuyerID' => $this->BuyerID,
				'StatusID' => 'paid'
			])->andWhere("IFNULL(RefundStatusID, '')<>'refunding'")->count();
			
			//退款中订单
			$data['lRefundingOrders'] = Order::find()->where([
				'SupplierID' => $this->supplier->lID,
				'RefundStatusID' => 'refunding'
			])->count();
			
			//今日已完成订单
			$data['lSuccessOrders'] = Order::find()->where([
				'BuyerID' => $this->buyer->lID,
				'StatusID' => 'success'
			])->andWhere([
				'>=',
				'dReceiveDate',
				\Yii::$app->formatter->asDate(time()) . " 00:00:00"
			])->andWhere([
				'<',
				'dReceiveDate',
				\Yii::$app->formatter->asDate(time()) . " 23:59:59"
			])->count();
			
			//今日成交额
			$data['fProfit'] = Order::find()->where([
				'BuyerID' => $this->buyer->lID,
				'StatusID' => 'success'
			])->andWhere([
				'>=',
				'dReceiveDate',
				\Yii::$app->formatter->asDate(time()) . " 00:00:00"
			])->andWhere([
				'<',
				'dReceiveDate',
				\Yii::$app->formatter->asDate(time()) . " 23:59:59"
			])->sum('fBuyerPaid');
			
			$data['sToday'] = \Yii::$app->formatter->asDate(time()) . " - " . \Yii::$app->formatter->asDate(time());
			
			//渠道款余额
			$data['fBalance'] = Buyer::findByID($this->buyer->lID)->fBalance;
			
			return $this->render("buyerhome", $data);
		} elseif ($this->wholesalerSupplier) {
			//渠道供应商
			$this->buyer->lID = $this->BuyerID;
			//待发货订单
			$data['lShipOrders'] = Order::find()->where([
				'BuyerID' => $this->buyer->lID,
				'StatusID' => 'paid'
			])->andWhere("IFNULL(RefundStatusID, '')<>'refunding'")->count();
			
			//退款中订单
			$data['lRefundingOrders'] = Order::find()->where([
				'SupplierID' => $this->supplier->lID,
				'RefundStatusID' => 'refunding'
			])->count();
			
			//今日已完成订单
			$data['lSuccessOrders'] = Order::find()->where([
				'BuyerID' => $this->buyer->lID,
				'StatusID' => 'success'
			])->andWhere([
				'>=',
				'dReceiveDate',
				\Yii::$app->formatter->asDate(time()) . " 00:00:00"
			])->andWhere([
				'<',
				'dReceiveDate',
				\Yii::$app->formatter->asDate(time()) . " 23:59:59"
			])->count();
			
			//今日成交额
			$data['fProfit'] = Order::find()->where([
				'BuyerID' => $this->buyer->lID,
				'StatusID' => 'success'
			])->andWhere([
				'>=',
				'dReceiveDate',
				\Yii::$app->formatter->asDate(time()) . " 00:00:00"
			])->andWhere([
				'<',
				'dReceiveDate',
				\Yii::$app->formatter->asDate(time()) . " 23:59:59"
			])->sum('fBuyerPaid');
			
			$data['sToday'] = \Yii::$app->formatter->asDate(time()) . " - " . \Yii::$app->formatter->asDate(time());
			
			//渠道款余额
			$data['fBalance'] = Buyer::findByID($this->BuyerID)->fBalance;
			
			return $this->render("buyerhome", $data);
		} else {
			//待发货订单
			$data['lPaidOrders'] = Order::find()
				->where(['StatusID' => 'paid'])
				//->andWhere(['<>','RefundStatusID','refunding'])
				->andWhere(['<>', "IFNULL(RefundStatusID, '')", 'refunding'])
				->count();
			
			//等待卖家确认退款
			$data['lRefundWaitApply'] = Refund::find()->where(['StatusID' => 'wait'])->count();
			
			//平台今日利润
			$data['fProfit'] = Order::find()->where([
				'StatusID' => 'success'
			])->andWhere([
				'>=',
				'dReceiveDate',
				\Yii::$app->formatter->asDate(time()) . " 00:00:00"
			])->andWhere([
				'<',
				'dReceiveDate',
				\Yii::$app->formatter->asDate(time()) . " 23:59:59"
			])->sum('fProfit');
			
			//提现申请
			$data['lWithdraw'] = Withdraw::find()->where(['CheckID' => 0])->count();
			
			//充值申请
			$data['lRecharge'] = Recharge::find()->where(['CheckID' => 0])->count();
			
			$data['sToday'] = \Yii::$app->formatter->asDate(time()) . " - " . \Yii::$app->formatter->asDate(time());
			
			//管理员信息
			
			//昨日成交单数
			$data['lYesOrders'] = Order::find()->where([
				'>=',
				'dReceiveDate',
				\Yii::$app->formatter->asDate(time() - 86400) . " 00:00:00"
			])->andWhere([
				'<',
				'dReceiveDate',
				\Yii::$app->formatter->asDate(time() - 86400) . " 23:59:59"
			])->count();
			
			//昨日成交金额
			$data['fYesMoney'] = Order::find()->where([
				'StatusID' => 'success'
			])->andWhere([
				'>=',
				'dReceiveDate',
				\Yii::$app->formatter->asDate(time() - 86400) . " 00:00:00"
			])->andWhere([
				'<',
				'dReceiveDate',
				\Yii::$app->formatter->asDate(time() - 86400) . " 23:59:59"
			])->sum('fBuyerPaid');
			
			//昨日退款金额
			$data['fYesRefund'] = Refund::find()->where([
				'>=',
				'dCompleteDate',
				\Yii::$app->formatter->asDate(time() - 86400) . " 00:00:00"
			])->andWhere([
				'<',
				'dCompleteDate',
				\Yii::$app->formatter->asDate(time() - 86400) . " 23:59:59"
			])->sum('fBuyerRefund');
			
			//昨日平台利润
			$data['fYesProfit'] = Order::find()->where([
				'StatusID' => 'success'
			])->andWhere([
				'>=',
				'dReceiveDate',
				\Yii::$app->formatter->asDate(time() - 86400) . " 00:00:00"
			])->andWhere([
				'<',
				'dReceiveDate',
				\Yii::$app->formatter->asDate(time() - 86400) . " 23:59:59"
			])->sum('fProfit');
			
			return $this->render("home", $data);
		}
	}
	
	
	public function actionQrcode()
	{
		return $this->renderPartial("qrcode", []);
	}
	
	public function actionJs()
	{
		return parent::actionJs(); // TODO: Change the autogenerated stub
	}
}