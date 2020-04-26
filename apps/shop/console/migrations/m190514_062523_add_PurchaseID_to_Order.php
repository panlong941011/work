<?php

use yii\db\Migration;

/**
 * Class m190514_062523_add_PurchaseID_to_Order
 */
class m190514_062523_add_PurchaseID_to_Order extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function up()
    {
	    $this->addColumn('Order', 'PurchaseID', $this->integer(11)->comment('渠道商ID'));
	    $this->addColumn('Order', 'WholesalerID', $this->integer(11)->comment('渠道人员ID'));
	    $this->addColumn('Order', 'TypeID', $this->string()->comment('订单类型'));
    }

    /**
     * {@inheritdoc}
     */
    public function down()
    {
	    $this->dropColumn('Order', 'PurchaseID');
	    $this->dropColumn('Order', 'WholesalerID');
	    $this->dropColumn('Order', 'TypeID');
    }
    
}
