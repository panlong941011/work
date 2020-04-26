<?php

use yii\db\Migration;

/**
 * Class m190308_024009_create_SalesPromotionDetail
 */
class m190308_024009_create_SalesPromotionDetail extends Migration
{
	/**
	 * 创建促销活动明细表
	 * @return bool|void
	 * @author hechengcheng
	 * @time 2019/3/12 9:14
	 */
	public function up()
	{
		$this->createTable('SalesPromotionDetail', [
			'lID' => $this->primaryKey(),
			'CostControl' => $this->decimal(19, 2)->comment('成本控制'),
			'dEditDate' => $this->dateTime()->comment('编辑时间'),
			'dNewDate' => $this->dateTime()->comment('新建时间'),
			'dSellout' => $this->dateTime()->comment('售罄时间'),
			'EditUserID' => $this->integer(11)->comment('编辑人'),
			'fCommunityBuyCommission' => $this->decimal(19, 2)->comment('社区佣金'),
			'fFreeShippingCost' => $this->decimal(19, 2)->comment('免邮成本'),
			'fPrice' => $this->decimal(19, 2)->comment('促销价'),
			'FreightRegulation' => $this->decimal(19, 2)->comment('运费调节'),
			'fShipping' => $this->decimal(19, 2)->comment('促销运费'),
			'fWholesale' => $this->decimal(19, 2)->comment('促销进货价'),
			'lQuantity' => $this->integer(11)->comment('促销库存'),
			'lSold' => $this->integer(11)->comment('已售数量'),
			'lSort' => $this->integer(11)->comment('排序'),
			'NewUserID' => $this->integer(11)->comment('新建人'),
			'OwnerID' => $this->integer(11)->comment('拥有者'),
			'ProductID' => $this->integer(11)->comment('促销商品'),
			'productstandardID' => $this->integer(11)->comment('商品规格'),
			'sAd' => $this->string(500)->comment('广告语'),
			'sAdUrl' => $this->string(500)->comment('广告链接'),
			'SalesPromotionID' => $this->integer(11)->comment('参照父对象'),
			'ShipTemplateID' => $this->integer(11)->comment('促销运费模板'),
			'sName' => $this->string()->comment('名称'),
			'SupplierShipTemplateID' => $this->integer(11)->comment('供应商运费模板'),
		]);
	}
	
	/**
	 * {@inheritdoc}
	 */
	public function down()
	{
		$this->dropTable('SalesPromotion');
	}
	
}
