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

class WholesalerFlowQueryForm extends Form
{
	
	public $WholesalerID;
	
	public $TypeID;
	
	public $Date;
	
	//排序
	public $orderBy;
	
	//分页
	public $page = 1;
	
	//条数
	public $limit = 10;
	
	/**
	 * 初始化
	 * @author YanZhongOuYang
	 * @time: 2017-3-8 19:02:56
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
		$this->buildType();
		$this->buildDate();
		$result = $this->getQuery()->with('wholesaleOrder')->all();
		return $result;
	}
	
	public function count()
	{
		$this->buildType();
		$this->buildDate();
		$this->buildWholesaler();
		$result = $this->getQuery()->count();
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
			$this->_query = \Yii::$app->wholesaleflow
				::find();
		}
		return $this->_query;
	}
	
	public function buildWholesaler()
	{
		if ($this->WholesalerID) {
			$this->getQuery()->andWhere(['WholesalerID'=>$this->WholesalerID]);
		}
	}
	
	
	public function buildType()
	{
		if ($this->TypeID) {
			$this->getQuery()->andWhere(['TypeID'=>$this->TypeID]);
		}
	}
	
	public function buildDate()
	{
		if ($this->Date) {
			$this->getQuery()->andWhere(['>=', 'dNewDate', $this->Date . "-01 00:00:00"]);
			$this->getQuery()->andWhere(['<=', 'dNewDate', $this->Date . "-31 23:59:59"]);
		}
	}
	
	public function buildOrderBy()
	{
		if ($this->orderBy) {
			$this->getQuery()->OrderBy($this->orderBy);
		} else {
			$this->getQuery()->OrderBy('dNewDate DESC');
		}
	}
}