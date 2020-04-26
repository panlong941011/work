<?php

use yii\db\Migration;

/**
 * Class m190222_013502_add_LSJID_to_ProductBrand
 */
class m190222_013502_add_LSJID_to_ProductBrand extends Migration
{
	/**
	 * {@inheritdoc}
	 */
	public function up()
	{
		$this->addColumn('ProductBrand', 'LSJID', $this->integer()->defaultValue(0)->comment('来三斤品牌ID'));
	}
	
	/**
	 * {@inheritdoc}
	 */
	public function safeDown()
	{
		echo "m190222_013502_add_LSJID_to_ProductBrand cannot be reverted.\n";
		
		return false;
	}
	
	/*
	// Use up()/down() to run migration code without a transaction.
	public function up()
	{

	}

	public function down()
	{
		echo "m190222_013502_add_LSJID_to_ProductBrand cannot be reverted.\n";

		return false;
	}
	*/
}
