<?php
/**
 * Created by PhpStorm.
 * User: ouyangyz <ouyangyanzhong@163.com>
 * Date: 2019/3/13
 * Time: 18:33
 */

namespace myerm\shop\mobile\controllers;

use myerm\common\components\Func;
use myerm\shop\common\models\MallConfig;
use yii\helpers\ArrayHelper;
use Yii;

/**
 * 商城报表控制器
 * Class MallreportController
 * @package myerm\shop\mobile\controllers
 * @author ouyangyz <ouyangyanzhong@163.com>
 * @time 2019/3/13 18:33
 */
class MallreportController extends Controller
{
	public function beforeAction($action)
	{
		if (parent::beforeAction($action)) {
			//有权限的人才能查看报表
			$sAbleMobileReport = MallConfig::getValueByKey("sAbleMobileReport");
			$arrAble = explode(',', $sAbleMobileReport);
			if (!\Yii::$app->frontsession->bLogin || !$arrAble || !in_array(\Yii::$app->frontsession->MemberID, $arrAble)) {
				$this->redirect(\Yii::$app->request->mallHomeUrl);
				\Yii::$app->end();
			}
		} else {
			return false;
		}
		
		return true;
	}
	
	/**
	 * 首页导航
	 * @return string
	 * @author ouyangyz <ouyangyanzhong@163.com>
	 * @time 2019/3/15 11:36
	 */
	public function actionIndex()
	{
		$this->getView()->title = '商城统计报表';
		return $this->render('index');
	}
	
	/**
	 * 商城日报表
	 * @return string
	 * @author ouyangyz <ouyangyanzhong@163.com>
	 * @time 2019/3/13 18:39
	 */
	public function actionMalldayreport()
	{
		$this->getView()->title = "商城日报表";
		$data = [];
		//指定日期
		$dDate = \Yii::$app->request->get('dDate', date('Y-m-d', time()));
		$data['dDate'] = $dDate;
		
		//上一天日期
		$dLastDate = date('Y-m-d', strtotime($dDate) - 86400);
		
		//下一天日期
		$dNextDate = date('Y-m-d', strtotime($dDate) + 86400);
		//如果当前时间为今天，则无法跳至下一天
		if ($dNextDate > Func::dayStart()) {
			$data['bToday'] = true;
		}
		
		$data['dLastDate'] = $dLastDate;
		$data['dNextDate'] = $dNextDate;
		
		$data['report'] = \Yii::$app->malldayreport::findOne(['sDate' => $dDate]);
		$data['lastDayReport'] = \Yii::$app->malldayreport::findOne(['sDate' => $dLastDate]);
		return $this->render('malldayreport', $data);
	}
	
	/**
	 * 商城周报表
	 * @return string
	 * @author ouyangyz <ouyangyanzhong@163.com>
	 * @time 2019/3/13 18:39
	 */
	public function actionMallweekreport()
	{
		$this->getView()->title = "商城周报表";
		$data = [];
		if ($_GET['dDate']) {
			$dDate = $_GET['dDate'];
		} else {
			$dDate = Func::getDate();
		}
		
		$time = strtotime($dDate);
		$dStart = Func::weekStart($time);//当周开始时间
		$dEnd = Func::weekEnd($time);////当周结束时间
		$data['dDate'] = date('Y-m-d', strtotime($dStart));
		
		//当前周结束日期
		$data['dEndDate'] = date('Y-m-d', strtotime($dEnd));
		
		//上一周开始日期
		$dLastDate = Func::weekStart(strtotime($dStart) - 86400 * 2);
		$dLastDate = date('Y-m-d', strtotime($dLastDate));
		
		//上一周结束日期
		$dLastEndDate = Func::weekEnd(strtotime($dStart) - 86400 * 2);
		$dLastEndDate = date('Y-m-d', strtotime($dLastEndDate));
		
		//下一周日期
		$dNextDate = Func::weekStart(strtotime($dEnd) + 86400 * 2);
		$dNextDate = date('Y-m-d', strtotime($dNextDate));;
		//如果当前时间为今天，则无法跳至下一天
		if ($dNextDate > Func::dayStart()) {
			$data['bToday'] = true;
		}
		
		$data['dLastDate'] = $dLastDate;
		$data['dLastEndDate'] = $dLastEndDate;
		$data['dNextDate'] = $dNextDate;
		$data['report'] = \Yii::$app->mallweekreport::findOne(['sStartDate' => $data['dDate'], 'sEndDate' => $data['dEndDate']]);
		$data['lastDayReport'] = \Yii::$app->mallweekreport::findOne(['sStartDate' => $data['dLastDate'], 'sEndDate' => $data['dLastEndDate']]);
		return $this->render('mallweekreport', $data);
	}
	
	/**
	 * 商城月报表
	 * @return string
	 * @author ouyangyz <ouyangyanzhong@163.com>
	 * @time 2019/3/13 18:39
	 */
	public function actionMallmonthreport()
	{
		$this->getView()->title = "商城月报表";
		
		//指定日期
		$thisMonth = date('Y-m');
		$dDate = $_GET['dDate'] ? $_GET['dDate'] : $thisMonth;
		$data['dDate'] = $dDate;
		
		//上一月日期
		$dLastDate = date('Y-m', strtotime('-1 month', strtotime($dDate)));
		
		//下一月日期
		$dNextDate = date('Y-m', strtotime('+1 month', strtotime($dDate)));
		
		//如果当前时间为今天，则无法跳至下一月
		if ($dNextDate > Func::dayStart()) {
			$data['bToday'] = true;
		}
		
		$data['dLastDate'] = $dLastDate;
		$data['dNextDate'] = $dNextDate;
		$data['report'] = \Yii::$app->mallmonthreport::findOne(['sMonth' => $dDate]);
		$data['lastDayReport'] = \Yii::$app->mallmonthreport::findOne(['sMonth' => $data['dLastDate']]);
		return $this->render('mallmonthreport', $data);
	}
	
	/**
	 * 代理商日报表
	 * @return string
	 * @author ouyangyz <ouyangyanzhong@163.com>
	 * @time 2019/3/14 11:22
	 */
	public function actionSellerdayreport()
	{
		$this->getView()->title = "代理商日业绩排行";
		$data = [];
		//指定日期
		$dDate = \Yii::$app->request->get('dDate', date('Y-m-d', time()));
		$data['dDate'] = $dDate;
		
		//上一天日期
		$dLastDate = date('Y-m-d', strtotime($dDate) - 86400);
		
		//下一天日期
		$dNextDate = date('Y-m-d', strtotime($dDate) + 86400);
		//如果当前时间为今天，则无法跳至下一天
		if ($dNextDate > Func::dayStart()) {
			$data['bToday'] = true;
		}
		
		$data['dLastDate'] = $dLastDate;
		$data['dNextDate'] = $dNextDate;
		
		$data['report'] = \Yii::$app->sellerdayreport->getSellerRanking($dDate);
		$data['type'] = ['天', 'day'];
		return $this->render('sellerdayreport', $data);
	}
	
	/**
	 * 代理商周业绩排行
	 * @return string
	 * @author ouyangyz <ouyangyanzhong@163.com>
	 * @time 2019/3/14 11:29
	 */
	public function actionSellerweekreport()
	{
		$this->getView()->title = "代理商周业绩排行";
		
		$data = [];
		if ($_GET['dDate']) {
			$dDate = $_GET['dDate'];
		} else {
			$dDate = Func::getDate();
		}
		
		$time = strtotime($dDate);
		$dStart = Func::weekStart($time);//当周开始时间
		$dEnd = Func::weekEnd($time);////当周结束时间
		$data['dDate'] = date('Y-m-d', strtotime($dStart));
		
		//当前周结束日期
		$data['dEndDate'] = date('Y-m-d', strtotime($dEnd));
		
		//上一周开始日期
		$dLastDate = Func::weekStart(strtotime($dStart) - 86400 * 2);
		$dLastDate = date('Y-m-d', strtotime($dLastDate));
		
		//上一周结束日期
		$dLastEndDate = Func::weekEnd(strtotime($dStart) - 86400 * 2);
		$dLastEndDate = date('Y-m-d', strtotime($dLastEndDate));
		
		//下一周日期
		$dNextDate = Func::weekStart(strtotime($dEnd) + 86400 * 2);
		$dNextDate = date('Y-m-d', strtotime($dNextDate));;
		//如果当前时间为今天，则无法跳至下一天
		if ($dNextDate > Func::dayStart()) {
			$data['bToday'] = true;
		}
		
		$data['dLastDate'] = $dLastDate;
		$data['dLastEndDate'] = $dLastEndDate;
		$data['dNextDate'] = $dNextDate;
		$data['report'] = \Yii::$app->sellerweekreport->getSellerRanking($data['dDate'], $data['dEndDate']);
		$data['type'] = ['周', 'week'];
		return $this->render('sellerdayreport', $data);
	}
	
	/**
	 * 代理商月业绩排行
	 * @return string
	 * @author ouyangyz <ouyangyanzhong@163.com>
	 * @time 2019/3/14 11:59
	 */
	public function actionSellermonthreport()
	{
		$this->getView()->title = "代理商月业绩排行";
		
		//指定日期
		$thisMonth = date('Y-m');
		$dDate = $_GET['dDate'] ? $_GET['dDate'] : $thisMonth;
		$data['dDate'] = $dDate;
		
		//上一月日期
		$dLastDate = date('Y-m', strtotime('-1 month', strtotime($dDate)));
		
		//下一月日期
		$dNextDate = date('Y-m', strtotime('+1 month', strtotime($dDate)));
		
		//如果当前时间为今天，则无法跳至下一月
		if ($dNextDate > Func::dayStart()) {
			$data['bToday'] = true;
		}
		
		$data['dLastDate'] = $dLastDate;
		$data['dNextDate'] = $dNextDate;
		$data['report'] = \Yii::$app->sellermonthreport->getSellerRanking($data['dDate']);
		$data['type'] = ['月', 'month'];
		return $this->render('sellerdayreport', $data);
	}
	
	/**
	 * 商品销售日排行
	 * @return string
	 * @author ouyangyz <ouyangyanzhong@163.com>
	 * @time 2019/3/14 14:41
	 */
	public function actionProductrank()
	{
		$this->getView()->title = "商品销售日排行";
		$data = [];
		$dStart = Func::dayStart();//当周开始时间
		$dEnd = Func::dayEnd();////当周结束时间
		$type = \Yii::$app->request->get('type', 'fTotal');
		$data['sType'] = $type;
		$result = \Yii::$app->order->getSaleRank($dStart, $dEnd);
		ArrayHelper::multisort($result, $type, SORT_DESC);
		$arrRank = array_slice($result, 0, 20);
		$data['arrRank'] = $arrRank;
		return $this->render('productrank', $data);
	}
}