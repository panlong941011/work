<?php

use yii\db\Migration;

/**
 * Class m190509_010531_add_PurchaseID_to_Order
 */
class m190509_010531_add_PurchaseID_to_Order extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function up()
    {
    	return true;
	    $this->addColumn('Order', 'PurchaseID', $this->integer()->comment('渠道商ID'));
	    $this->addColumn('Order', 'WholesalerID', $this->integer()->comment('渠道人员ID'));
    }

    /**
     * {@inheritdoc}
     */
    public function down()
    {
	    $this->dropColumn('Order', 'PurchaseID');
	    $this->dropColumn('Order', 'WholesalerID');
    }

    
}
