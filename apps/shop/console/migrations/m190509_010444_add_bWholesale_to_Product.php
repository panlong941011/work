<?php

use yii\db\Migration;

/**
 * Class m190509_010444_add_bWholesale_to_Product
 */
class m190509_010444_add_bWholesale_to_Product extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function up()
    {
	    $this->addColumn('Product', 'bWholesale', $this->tinyInteger()->defaultValue(0)->comment('商品类型；0非渠道商品，1渠道商品'));
    }

    /**
     * {@inheritdoc}
     */
    public function down()
    {
	    $this->dropColumn('Product', 'bWholesale');
    }

    
}
