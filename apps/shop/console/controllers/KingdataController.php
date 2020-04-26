<?php
/**
 * Created by PhpStorm.
 * User: shish
 * Date: 2019年3月4日
 * 示例：导出上个月 php shop kingdata/order  |  导出所有日期 php shop kingdata/order 1
 */

namespace console\controllers;

use myerm\shop\common\models\Kingdeecertificate;
use myerm\shop\common\models\Order;
use myerm\shop\common\models\Refund;
use myerm\shop\common\models\Supplier;
use myerm\shop\common\models\SupplierCode;
use yii\console\Controller;

class KingdataController extends Controller
{
	
	public $type = 0;//0按天导出 1导出所有时间
	public $startDate;
	public $lastDate;
	public $month;

	public $arrBuyerCode = [
        11 => '客户---003---土葩葩（金桥酒店）',
        12 => '客户---004---玖神商贸',
        13 => '客户---005---来瓜分',
        14 => '客户---006---姚明302生活商城',
        15 => '客户---001---福建省安溪供销电子商务有限公司',
        16 => '客户---002---福建省海峡导报股份有限公司',
		17 => '客户---007---厦门创慧谷网络科技有限公司',
		18 => '客户---008---轻松买（厦门）科技有限公司',
    ];

	public function beforeAction($action)
	{
		//指定导上个月
		$time = strtotime(date('Y-m-01'));
		$preMonthLastDay = date('Y-m-d', strtotime('-1 day', $time));
		$preMonthFirstDay = date('Y-m-01', strtotime($preMonthLastDay));
		$this->startDate = $preMonthFirstDay;
		$this->lastDate = $preMonthLastDay;
		$this->month = date("Y-m", strtotime($this->lastDate));
		return parent::beforeAction($action);
	}
	
	/**
	 * 导出的脚本入口
	 * @var date
	 * @author shish
	 * @time 2019年3月4日
	 */
	public function actionOrder($type = 0)
	{
		//设置不超时，大量数据需要处理
		set_time_limit(0);
		//设置足够的内存
		ini_set("memory_limit", "8000M");
		//引入phpexcel
		include \Yii::getAlias("@myerm") . "/backend/common" . "/libs/PHPExcel.php";
		include \Yii::getAlias("@myerm") . "/backend/common" . '/libs/PHPExcel/IOFactory.php';
		
		//导全部时间的
		$this->type = $type;
		if ($this->type == 1) {
			$this->startDate = '2018-06-01';
			$this->lastDate = '2019-03-31';
			$this->month = date("Y-m", strtotime($this->lastDate));
		}
		$this->actionOrderExport();//订单导出
		$this->actionRefundExport();//退款导出
	}
	
	/**
	 * 科目
	 * @var array
	 * @author shish
	 * @time 2019年3月4日
	 */
	public static $arrKemu = [
		'advancesDebtor' => '2203.03.02',//大农云订单收入 借 预收账款
		'advancesLender' => '6001.01.01.05',//大农云订单收入 贷
		'supplierDebtor' => '6401.01.03',//大农云订单成本 借
		'supplierLender' => '2202.02',//大农云订单成本 贷 应付账款－供应商
	];
	
	/**
	 * 批量插入的字段
	 * @var array
	 * @author shish
	 * @time 2019年3月4日
	 */
	public static $keys = [
		'dNewDate',//凭证日期
		'lYear',//会计年度
		'lDuration',//会计期间
		'lNo',//凭证号
		'fMoneyChange',//原币金额
		'sMark',//凭证摘要
		'dSaileDate',//业务日期
		'lSerialNum',//序号
		'lRecordNum',//分录序号
		'sName',//科目名称
		'sCode',//科目代码
		'fDebtorMoney',//借方
		'fLenderMoney',//贷方
		'sItem',//核算项目
		'TypeID',//数据类型
	];
	
	/**
	 * 获取渠道商编码
	 */
	public function getBuyerCode($lBuyerID)
	{
		$arrCode = $this->arrBuyerCode;
		$sCode = isset($arrCode[$lBuyerID]) ? $arrCode[$lBuyerID] : '';
		return $sCode;
	}
	
	/**
	 * 从数据库导出到excel
	 * @author shishaohua
	 * @time 2019/4/1
	 */
	public function actionOrderExport()
	{
		\Yii::$app->db->createCommand('truncate Kingdeecertificate')->execute();
		//导出所有时间的订单
		$arrOrder = Order::find()->select('*')->where("dNewDate>='{$this->startDate} 00:00:00' AND dNewDate<='{$this->lastDate} 23:59:59' AND BuyerID<>9");
		$arrBatchInsert = [];
		$i = 1;//计数
		//整理数据
		foreach ($arrOrder->batch(1000) as $order) {
			foreach ($order as $record) {
				$lRecordNum = 0;//分录号
				
				$currentDate = date("Y-m-d", strtotime($record->dNewDate));
				$lYear = gmdate("Y", strtotime($currentDate) + 8 * 3600);
				$lMonth = gmdate("n", strtotime($currentDate) + 8 * 3600);
				
				$arrTemplate = [
					'dNewDate' => $currentDate,//凭证日期
					'lYear' => $lYear,//会计年度
					'lDuration' => $lMonth,//会计期间
					'lNo' => $i,//凭证号
					'fMoneyChange' => 0,//原币金额
					'sMark' => '订单' . $record->sName,//凭证摘要
					'dSaileDate' => $currentDate,//业务日期
					'lSerialNum' => $i,//序号
					'lRecordNum' => 0,//分录序号
					'sName' => "",//科目名称
					'sCode' => "",//科目代码
					'fDebtorMoney' => 0,//借方
					'fLenderMoney' => 0,//贷方
					'sItem' => "",//核算项目
					'TypeID' => 1,//数据类型
				];
				
				//银行收款
				$fBuyerPaidTotal = $record->fShip + $record->fBuyerProductPaid;
				$fBuyerPaid = round($fBuyerPaidTotal, 2);
				if ($fBuyerPaid > 0) {
					//借方
					$arrDebtor = $arrTemplate;
					$arrDebtor['fMoneyChange'] = $fBuyerPaid;
					$arrDebtor['sCode'] = self::$arrKemu['advancesDebtor'];
					$arrDebtor['fDebtorMoney'] = $fBuyerPaid;
					$arrDebtor['lRecordNum'] = $lRecordNum;
					$arrDebtor['sItem'] = $this->getBuyerCode($record->BuyerID);
					//贷方
					$arrLender = $arrTemplate;
					$arrLender['fMoneyChange'] = $fBuyerPaid;
					$arrLender['sCode'] = self::$arrKemu['advancesLender'];
					$arrLender['fLenderMoney'] = $fBuyerPaid;
					$arrLender['lRecordNum'] = $lRecordNum + 1;
					
					$arrPayment = [$arrDebtor, $arrLender];
					$lRecordNum += 2;
					$arrBatchInsert = array_merge($arrBatchInsert, $arrPayment);
				}
				
				//供应商成本
				$fSupplierIncome = $record->fShip + $record->fSupplierProductIncome;
				$fSupplierIncome = round($fSupplierIncome, 2);
				if ($fSupplierIncome > 0) {
					//借方
					$arrDebtor = $arrTemplate;
					$arrDebtor['fMoneyChange'] = $fSupplierIncome;
					$arrDebtor['sCode'] = self::$arrKemu['supplierDebtor'];
                    $arrDebtor['fDebtorMoney'] = $fSupplierIncome;
					$arrDebtor['lRecordNum'] = $lRecordNum;
					//贷方
					$arrLender = $arrTemplate;
					$arrLender['fMoneyChange'] = $fSupplierIncome;
					$arrLender['sCode'] = self::$arrKemu['supplierLender'];
					$arrLender['fLenderMoney'] = $fSupplierIncome;
					$arrLender['lRecordNum'] = $lRecordNum + 1;
					$arrLender['sItem'] = SupplierCode::getCodeById($record->SupplierID);//供应商编码
					
					$arrSupplier = [$arrDebtor, $arrLender];
					$lRecordNum += 2;
					$arrBatchInsert = array_merge($arrBatchInsert, $arrSupplier);
				}
				$i++;
			}
		}
		if (count($arrBatchInsert)) {
			\yii::$app->db->createCommand()->batchInsert(Kingdeecertificate::tableName(), self::$keys, $arrBatchInsert)->execute();
			$this->actionExport();
		} else {
			echo "order export has no data\n";
		}
	}
	
	/**
	 * 运算导出的退款订单数据到数据库表
	 * @author shish
	 * @time 2019年3月12日15:00:39
	 */
	public function actionRefundExport()
	{
		$typeID = 3;//对应生成文件名 退款 tuikuan
		
		//先清空数据，以免重复
		\Yii::$app->db->createCommand('truncate Kingdeecertificate')->execute();
		
		//导出所有时间的退款订单
		$where = "dCompleteDate>='{$this->startDate} 00:00:00' AND dCompleteDate<='{$this->lastDate} 23:59:59' AND BuyerID<>9";
		
		$arrRefund = Refund::find()->select('OrderID,SupplierID,fBuyerRefund,fSupplierRefund,dCompleteDate,BuyerID')->where($where)->all();
		
		if (empty($arrRefund)) {
			exit("refund export has no data\n");
		}
		$orderIdList = array_column($arrRefund, 'OrderID');
		$orderInfoList = Order::find()->where(['in', 'lID', $orderIdList])->asArray()->all();
		$keyOrderInfo = [];
		foreach ($orderInfoList as $order) {
			$lID = $order['lID'];
			$keyOrderInfo[$lID] = $order;
		}
		$i = 1;//计数
		$arrBatchInsert = [];
		//整理数据
		foreach ($arrRefund as $record) {
			
			$lRecordNum = 0;//分录号
			$orderID = $record->OrderID;
			$sName = isset($keyOrderInfo[$orderID]['sName']) ? $keyOrderInfo[$orderID]['sName'] : '';
			
			$currentDate = date("Y-m-d", strtotime($record->dCompleteDate));
			$lYear = gmdate("Y", strtotime($currentDate) + 8 * 3600);
			$lMonth = gmdate("n", strtotime($currentDate) + 8 * 3600);
			
			$arrTemplate = [
				'dNewDate' => $currentDate,//凭证日期
				'lYear' => $lYear,//会计年度
				'lDuration' => $lMonth,//会计期间
				'lNo' => $i,//凭证号
				'fMoneyChange' => 0,//原币金额
				'sMark' => '退款' . $sName,//凭证摘要
				'dSaileDate' => $currentDate,//业务日期
				'lSerialNum' => $i,//序号
				'lRecordNum' => 0,//分录序号
				'sName' => "",//科目名称
				'sCode' => "",//科目代码
				'fDebtorMoney' => 0,//借方
				'fLenderMoney' => 0,//贷方
				'sItem' => "",//核算项目
				'TypeID' => $typeID,
			];
			//银行收款
			$fBuyerRefund = round($record->fBuyerRefund, 2);
			if ($fBuyerRefund > 0) {
				//借方
				$arrDebtor = $arrTemplate;
				$arrDebtor['fMoneyChange'] = -$fBuyerRefund;
				$arrDebtor['sCode'] = self::$arrKemu['advancesDebtor'];
				$arrDebtor['fDebtorMoney'] = -$fBuyerRefund;
				$arrDebtor['lRecordNum'] = $lRecordNum;
                $arrDebtor['sItem'] = $this->getBuyerCode($record->BuyerID);
				//贷方
				$arrLender = $arrTemplate;
				$arrLender['fMoneyChange'] = -$fBuyerRefund;
				$arrLender['sCode'] = self::$arrKemu['advancesLender'];
				$arrLender['fLenderMoney'] = -$fBuyerRefund;
				$arrLender['lRecordNum'] = $lRecordNum + 1;
				
				$arrPayment = [$arrDebtor, $arrLender];
				$lRecordNum += 2;
				$arrBatchInsert = array_merge($arrBatchInsert, $arrPayment);
			}
			
			//供应商
			$fSupplierRefund = $record->fSupplierRefund ? $record->fSupplierRefund : 0;
			$fSupplierRefund = round($fSupplierRefund, 2);
			if ($fSupplierRefund > 0) {
				//借方
				$arrDebtor = $arrTemplate;
				$arrDebtor['fMoneyChange'] = -$fSupplierRefund;
				$arrDebtor['sCode'] = self::$arrKemu['supplierDebtor'];
				$arrDebtor['fDebtorMoney'] = -$fSupplierRefund;
				$arrDebtor['lRecordNum'] = $lRecordNum;
				//贷方
				$arrLender = $arrTemplate;
				$arrLender['fMoneyChange'] = -$fSupplierRefund;
				$arrLender['sCode'] = self::$arrKemu['supplierLender'];
				$arrLender['fLenderMoney'] = -$fSupplierRefund;
				$arrLender['lRecordNum'] = $lRecordNum + 1;
				$arrLender['sItem'] = SupplierCode::getCodeById($record->SupplierID);//供应商编码
				
				$arrSupplier = [$arrDebtor, $arrLender];
				$lRecordNum += 2;
				$arrBatchInsert = array_merge($arrBatchInsert, $arrSupplier);
			}
			$i++;
		}
		if (count($arrBatchInsert)) {
			\Yii::$app->db->createCommand()->batchInsert(Kingdeecertificate::tableName(), self::$keys, $arrBatchInsert)->execute();
			$this->actionExport();
		}
	}
	
	/**
	 * 导出excel表格
	 */
	public function actionExport()
	{
		//设置不超时，大量数据需要处理
		set_time_limit(0);
		//设置足够的内存
		ini_set("memory_limit", "8000M");
		//如果有制定导出的日期
		$arrHeaderChar = [
			'A' => 'dNewDate',//凭证日期
			'B' => 'lYear',//会计年度
			'C' => 'lDuration',//会计期间
			'D' => 'sChar',//凭证字
			'E' => 'lNo',//凭证号
			'F' => 'sCode',//科目代码
			'G' => 'sName',//科目名称
			'H' => 'sMoneyCode',//币别代码
			'I' => 'sMoneyName',//币别名称
			'J' => 'fMoneyChange',//原币金额
			'K' => 'fDebtorMoney',//借方
			'L' => 'fLenderMoney',//贷方
			'M' => 'sBill',//制单
			'N' => 'sCheck',//审核
			'O' => 'sStandard',//核准
			'P' => 'sTeller',//出纳
			'Q' => 'sAgent',//经办
			'R' => 'sMethod',//结算方式
			'S' => 'sCloseNum',//结算号
			'T' => 'sMark',//凭证摘要
			'U' => 'sNum',//数量
			'V' => 'sNumUnit',//数量单位
			'W' => 'sPrice',//单价
			'X' => 'sRefer',//参考信息
			'Y' => 'dSaileDate',//业务日期
			'Z' => 'sSaileCode',//往来业务编号
			'AA' => 'lAccessoryNum',//附件数
			'AB' => 'lSerialNum',//序号
			'AC' => 'sSys',//系统模块
			'AD' => 'sRemark',//业务描述
			'AE' => 'lRate',//汇率
			'AF' => 'lRecordNum',//分录序号
			'AG' => 'sItem',//核算项目
			'AH' => 'lCheckBill',//过账
			'AI' => 'sMechanism',//机制凭证
			'AJ' => 'sFlow',//现金流量
		];
		
		$inputFileType = \PHPExcel_IOFactory::identify(\Yii::getAlias("@myerm") . "/.." . "/kingdeecert.xls");
		$objReader = \PHPExcel_IOFactory::createReader($inputFileType);
		$objPHPExcel = $objReader->load(\Yii::getAlias("@myerm") . "/.." . "/kingdeecert.xls");
		$lRow = 2;
		$TypeID = "";
		//全部导出
		$cert = Kingdeecertificate::find();
		foreach ($cert->batch(1000) as $arrData) {
			foreach ($arrData as $data) {
				foreach ($arrHeaderChar as $sColIndex => $sCol) {
					
					if ($sCol == 'lNo' || $sCol == 'fMoneyChange' || $sCol == 'fDebtorMoney'
						|| $sCol == 'fLenderMoney' || $sCol == 'lSerialNum' || $sCol == 'lYear'
						|| $sCol == 'lRecordNum' || $sCol == 'lDuration'
					) {
						$sType = \PHPExcel_Cell_DataType::TYPE_NUMERIC;
					} else {
						$sType = \PHPExcel_Cell_DataType::TYPE_STRING;
					}
					
					$objPHPExcel->getActiveSheet()->setCellValueExplicit($sColIndex . $lRow, $data->$sCol,
						$sType);
				}
				$TypeID = $data->TypeID;
				$lRow++;
			}
		}
		
		if (!file_exists(\Yii::getAlias("@myerm") . "/.." . "/kingdeecert")) {
			mkdir(\Yii::getAlias("@myerm") . "/.." . "/kingdeecert", 0777);
		}
		
		$sFileName = "";
		switch ($TypeID) {
			case 1:
				$sFileName = $this->month . "_" . $TypeID . "dingdan";
				break;
			case 3:
				$sFileName = $this->month . "_" . $TypeID . "tuikuan";
				break;
			default:
				$sFileName = $this->month . "_" . $TypeID . 'unknown';
		}
		if ($this->type == 1) {
			$sFileName = 'all_' . $sFileName;
		}
		$objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
		$objWriter->save(\Yii::getAlias("@myerm") . "/.." . "/kingdeecert/" . $sFileName . ".xls");
	}
	
}