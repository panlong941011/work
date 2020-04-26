<?php

use yii\db\Migration;

/**
 * Class m190215_055357_add_sKeyword_to_Product
 */
class m190215_055357_add_sKeyword_to_Product extends Migration
{
	/**
	 * {@inheritdoc}
	 */
	public function up()
	{
		$this->addColumn('Product', 'sKeyword', $this->string()->comment('商品关键字'));
	}
	
	/**
	 * {@inheritdoc}
	 */
	public function down()
	{
		$this->dropColumn('Product', 'sKeyword');
	}
	
}
