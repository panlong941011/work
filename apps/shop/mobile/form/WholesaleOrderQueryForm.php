<?php
/**
 * Created by PhpStorm.
 * User: ouyangyz <ouyangyanzhong@163.com>
 * Date: 2018-08-08
 * Time: 上午 11:20
 */

namespace myerm\shop\mobile\form;

use myerm\shop\common\components\Form;
use yii\db\ActiveQuery;

class WholesaleOrderQueryForm extends Form
{
	
	//关键词搜索
	public $StatusID;
	
	public $WholesalerID;
	
	public $SellerID;
	
	public $DateBegin;
	
	public $DateEnd;
	
	//排序
	public $orderBy;
	
	//分页
	public $page = 1;
	
	//条数
	public $limit = 10;
	
	/**
	 * 初始化
	 * @author ouyangyz <ouyangyanzhong@163.com>
	 * @time 2018-8-14 17:55:23
	 */
	public function init()
	{
		parent::init();
		
	}
	
	public function run()
	{
		$this->buildPage();
		$this->buildOrderBy();
		$this->buildWholesaler();
		$this->buildSeller();
		$this->buildStatus();
		$this->buildDateBegin();
		$this->buildDateEnd();
		$result = $this->getQuery()->leftJoin('Order', '`Order`.lID = WholesaleOrder.OrderID')->all();
		return $result;
	}
	
	public function count()
	{
		$this->buildOrderBy();
		$this->buildWholesaler();
		$this->buildSeller();
		$this->buildStatus();
		$this->buildDateBegin();
		$this->buildDateEnd();
		$result = $this->getQuery()->count('*');
		return $result;
	}
	
	public function countProfit()
	{
		$this->buildOrderBy();
		$this->buildWholesaler();
		$this->buildSeller();
		$this->buildStatus();
		$this->buildDateBegin();
		$this->buildDateEnd();
		$result = [];
		$result['fWholesalerProfit'] = $this->getQuery()->sum('fWholesalerProfit');
		$result['fWholesalerProfit'] = $result['fWholesalerProfit'] ? $result['fWholesalerProfit'] : '0.00';
		$result['fSellerProfit'] = $this->getQuery()->sum('fSellerProfit');
		$result['fSellerProfit'] = $result['fSellerProfit'] ? $result['fSellerProfit'] : '0.00';
		return $result;
	}
	
	/**
	 * @var ActiveQuery $_query 查询构造器
	 */
	private $_query;
	
	/**
	 * 获取查询构造器
	 * @return ActiveQuery
	 * @author YanZhongOuYang
	 * @time: 2017-3-8 19:09:16
	 */
	public function getQuery()
	{
		if (!isset($this->_query)) {
			$this->_query = \Yii::$app->wholesaleorder::find();
		}
		return $this->_query;
	}
	
	public function buildStatus()
	{
		if ($this->StatusID) {
			$this->getQuery()->andWhere(['Order.StatusID' => $this->StatusID]);
		}
	}
	
	public function buildWholesaler()
	{
		if ($this->WholesalerID) {
			$this->getQuery()->andWhere(['WholesaleOrder.WholesalerID' => $this->WholesalerID]);
		}
	}
	
	public function buildSeller()
	{
		if ($this->SellerID) {
			$this->getQuery()->andWhere(['WholesaleOrder.SellerID' => $this->SellerID]);
		}
	}
	
	public function buildOrderBy()
	{
		if ($this->orderBy) {
			$this->getQuery()->OrderBy($this->orderBy);
		} else {
			$this->getQuery()->OrderBy('WholesaleOrder.dNewDate DESC');
		}
	}
	
	public function buildDateBegin()
	{
		if ($this->DateBegin) {
			$this->getQuery()->andWhere(['>=','WholesaleOrder.dNewDate',$this->DateBegin." 00:00:00"]);
		}
	}
	
	public function buildDateEnd()
	{
		if ($this->DateEnd) {
			$this->getQuery()->andWhere(['<=','WholesaleOrder.dNewDate',$this->DateEnd." 23:59:59"]);
		}
	}
}