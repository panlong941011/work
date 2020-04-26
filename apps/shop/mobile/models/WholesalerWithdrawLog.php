<?php
/**
 * Created by PhpStorm.
 * User: ouyangyz <ouyangyanzhong@163.com>
 * Date: 2018-08-13
 * Time: 下午 6:26
 */

namespace myerm\shop\mobile\models;


use myerm\common\components\CommonEvent;
use myerm\shop\common\models\MallConfig;
use myerm\shop\common\models\ShopModel;

/**
 * 渠道商提现记录
 * Class WholesalerWithdrawLog
 * @package myerm\shop\mobile\models
 * @property string $dAcceptDate 受理时间
 * @property string $dCompleteDate 到账时间
 * @property string $dNewDate 申请时间
 * @property float(11,2) $fMoney 提现金额
 * @property int(11) $lID 提现ID
 * @property string(255) $sBank 银行
 * @property string(255) $sBankAccount 持卡人姓名
 * @property string(255) $sBankNo 银行卡号
 * @property string(255) $sFailReason 失败原因
 * @property string(255) $sName 提现编号
 * @property int(11) $TypeID 提现状态 (提现中:1;已提现:2;提现失败:-1)
 * @property int(32) $WholesalerID 渠道商姓名
 * @author ouyangyz <ouyangyanzhong@163.com>
 * @time 2018-8-13 18:27:01
 */
class WholesalerWithdrawLog extends ShopModel
{
	/**
	 * 计算冻结金额
	 * @param $WholesalerID
	 * @return mixed
	 * @author ouyangyz <ouyangyanzhong@163.com>
	 * @time 2018-8-14 16:40:04
	 */
	public function computeFrozen($WholesalerID)
	{
		return static::find()->where(['WholesalerID' => $WholesalerID, 'TypeID' => [0, 1]])->sum('fMoney');
	}
	
	/**
	 * 提现申请
	 * @param $fMoney
	 * @return array
	 * @throws \yii\base\InvalidConfigException
	 * @author ouyangyz <ouyangyanzhong@163.com>
	 * @time 2018-8-14 21:02:34
	 */
	public function withdraw($fMoney)
	{
		$wholesaler = \Yii::$app->frontsession->wholesaler;
		
		if ($fMoney < MallConfig::getValueByKey('lWithdrawMin')) {
			return ['status' => false, 'message' => "金额低于" . MallConfig::getValueByKey('lWithdrawMin') . "元时不可提现"];
		}
		
		if ($fMoney > $wholesaler->computeWithdraw) {
			return ['status' => false, 'message' => '可提现金额不足'];
		}
		
		$log = new static();
		$log->sName = "W" . $this->makeTradeNo();
		$log->WholesalerID = $wholesaler->lID;
		$log->TypeID = 1;
		$log->fMoney = $fMoney;
		$log->sBank = $wholesaler->sBank;
		$log->sBankAccount = $wholesaler->sBankRealName;
		$log->sBankNo = $wholesaler->sBankAccount;
		$log->dNewDate = \Yii::$app->formatter->asDatetime(time());
		$log->sOpenID = \Yii::$app->frontsession->sOpenID;
		$log->save();
		
		$event = new CommonEvent();
		$event->extraData = $log;//传值订单明细
		\Yii::$app->wholesalerwithdrawlog->trigger("success", $event);
		
		return ['status' => true];
	}
	
	/**
	 * 生成订单号，长格式的时间+随机码
	 */
	public function makeTradeNo()
	{
		return str_replace(['-', ' ', ':'], "", \Yii::$app->formatter->asDatetime(time())) . rand(10000, 99999);
	}
	
	/**
	 * 提现记录列表
	 * @param $page
	 * @param $TypeID
	 * @return array|\yii\db\ActiveRecord[]
	 * @author ouyangyz <ouyangyanzhong@163.com>
	 * @time 2018-8-14 21:15:55
	 */
	public function withdrawList($page, $TypeID)
	{
		$page = $page ? intval($page) : 1;
		
		if ($TypeID == 1) {
			return static::find()->where(['WholesalerID' => \Yii::$app->frontsession->wholesaler->lID, 'TypeID' => [0, 1]])
				->orderBy('dNewDate DESC')->limit(10)->offset(($page - 1) * 10)->all();
		} elseif ($TypeID == 2) {
			return static::find()->where(['WholesalerID' => \Yii::$app->frontsession->wholesaler->lID, 'TypeID' => $TypeID])
				->orderBy('dNewDate DESC')->limit(10)->offset(($page - 1) * 10)->all();
		} else {
			return static::find()->where(['WholesalerID' => \Yii::$app->frontsession->wholesaler->lID])
				->orderBy('dNewDate DESC')->limit(10)->offset(($page - 1) * 10)->all();
		}
	}
	
	/**
	 * 获取状态
	 * @return string
	 * @author ouyangyz <ouyangyanzhong@163.com>
	 * @time 2018-8-14 21:53:11
	 */
	public function getSStatus()
	{
		if ($this->TypeID == 1) {
			return "提现中";
		} elseif ($this->TypeID == 2) {
			return "已提现";
		} else {
			return "提现失败";
		}
	}
}