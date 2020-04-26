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

class WholesaleQueryForm extends Form
{
	
	public $WholesalerID;
	
	public $SellerID;
	
	public $CatID;
	
	public $Keyword;
	
	public $Brand;
	
	public $Tag;
	
	public $orderBy;
	
	public $HaveSeller;
	
	public $sortby;
	
	public $ascdesc;
	
	//排序
	public $GroupBy;
	
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
		$this->buildSeller();
		$this->buildGroupBy();
		$this->buildWholesaler();
		$this->buildHaveSeller();
		$result = $this->getQuery()->leftJoin('Product', '`Product`.lID = Wholesale.ProductID')->all();
		return $result;
	}
	
	public function count()
	{
		$this->buildSeller();
		$this->buildGroupBy();
		$this->buildWholesaler();
		$this->buildHaveSeller();
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
			$this->_query = \Yii::$app->wholesale::find();
		}
		return $this->_query;
	}
	
	public function buildWholesaler()
	{
		if ($this->WholesalerID) {
			$this->getQuery()->andWhere(['Wholesale.WholesalerID' => $this->WholesalerID]);
		}
	}
	
	
	public function buildSeller()
	{
		if ($this->SellerID) {
			$this->getQuery()->andWhere(['Wholesale.SellerID' => $this->SellerID]);
		}
	}
	
	public function buildHaveSeller()
	{
		if ($this->HaveSeller) {
			$this->getQuery()->andWhere('Wholesale.SellerID IS NOT NULL');
		}
	}
	
	public function buildOrderBy()
	{
		if ($this->orderBy) {
			$this->getQuery()->OrderBy($this->orderBy);
		} elseif ($this->sortby) {
			if ($this->sortby == 'sale') {
				$sOrderBy = "lSaleShow DESC";
			} elseif ($this->sortby == 'price') {
				$sOrderBy = "fPrice";
				if ($this->ascdesc == "down") {
					$sOrderBy .= " DESC";
				}
			} else {
				$sOrderBy = "dEditDate DESC";
			}
			$this->getQuery()->OrderBy('Product.'.$sOrderBy);
		} else {
			$this->getQuery()->OrderBy('Wholesale.dNewDate DESC');
		}
	}
	
	public function buildKeyword()
	{
		if ($this->Keyword) {
			$this->getQuery()->andWhere("Product.sName LIKE '%" . addslashes($this->Keyword) . "%'");
		}
	}
	
	public function buildCatID()
	{
		if ($this->CatID) {
			$productCat = \Yii::$app->productcat->findByID($this->CatID);
			if ($productCat->PathID) {
				$this->getQuery()->andWhere(["Product.PathID LIKE '{$productCat->PathID}%'"]);
			}
		}
	}
	
	public function buildBrand()
	{
		if ($this->Brand) {
			$this->getQuery()->andWhere(['Product.ProductBrandID' => $this->Brand]);
		}
	}
	
	public function buildTag()
	{
		if ($this->Tag) {
			$arrTagCond = [];
			foreach ($this->Tag as $TagID) {
				$arrTagCond[] = "Product.ProductTagID LIKE '%;$TagID;%'";
			}
			$this->getQuery()->andWhere("(" . implode(" OR ", $arrTagCond) . ")");
		}
	}
	
	public function buildGroupBy()
	{
		if ($this->GroupBy) {
			$this->getQuery()->groupBy('Wholesale.' . $this->GroupBy);
		}
	}
}