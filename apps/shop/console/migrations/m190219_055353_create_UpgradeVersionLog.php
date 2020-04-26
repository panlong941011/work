<?php

use yii\db\Migration;

/**
 * Class m190219_055353_create_UpgradeVersionLog
 */
class m190219_055353_create_UpgradeVersionLog extends Migration
{
	/**
	 * {@inheritdoc}
	 */
	public function up()
	{
		$this->createTable('UpgradeVersionLog', [
			'lID' => $this->primaryKey(),
			'ProductID' => $this->integer()->comment('商品ID'),
			'StandardID' => $this->integer()->comment('来三斤规格ID'),
			'VersionID' => $this->integer()->comment('更新版本号'),
			'StatusID' => $this->integer()->comment('状态'),
			'dNewDate' => $this->dateTime()->comment('新建时间'),
			'dUpgradeDate' => $this->dateTime()->comment('更新时间'),
			'fPrice' => $this->decimal(10, 2)->comment('指导售价'),
			'fBuyerPrice' => $this->decimal(10, 2)->comment('渠道商进货价'),
			'fSupplierPrice' => $this->decimal(10, 2)->comment('供应商结算价'),
			'fCostControl' => $this->decimal(10, 4)->comment('成本控制'),
			'fShipAdjust' => $this->decimal(10, 4)->comment('运费调节'),
			'MemberShipTemplateID' => $this->integer()->comment('商城运费模板'),
			'ShipTemplateID' => $this->integer()->comment('供应商运费模板'),
		]);
	}
	
	/**
	 * {@inheritdoc}
	 */
	public function safeDown()
	{
		echo "m190219_055353_create_UpgradeVersionLog cannot be reverted.\n";
		
		return false;
	}
	
	/*
	// Use up()/down() to run migration code without a transaction.
	public function up()
	{

	}

	public function down()
	{
		echo "m190219_055353_create_UpgradeVersionLog cannot be reverted.\n";

		return false;
	}
	*/
}
