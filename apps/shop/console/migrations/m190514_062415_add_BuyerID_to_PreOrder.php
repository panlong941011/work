<?php

use yii\db\Migration;

/**
 * Class m190514_062415_add_BuyerID_to_PreOrder
 */
class m190514_062415_add_BuyerID_to_PreOrder extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function up()
    {
	    $this->addColumn('PreOrder', 'BuyerID', $this->integer(11)->comment('渠道商'));
	    $this->addColumn('PreOrder', 'WholesalerID', $this->integer(11)->comment('下单人'));
	    $this->addColumn('PreOrder', 'fTotal', $this->decimal(11,2)->comment('订单总价'));
	    $this->addColumn('PreOrder', 'fShip', $this->decimal(11,2)->comment('运费'));
    }

    /**
     * {@inheritdoc}
     */
    public function down()
    {
	    $this->dropColumn('PreOrder', 'PurchaseID');
	    $this->dropColumn('PreOrder', 'WholesalerID');
	    $this->dropColumn('PreOrder', 'fTotal');
	    $this->dropColumn('PreOrder', 'fShip');
    }

    
}
