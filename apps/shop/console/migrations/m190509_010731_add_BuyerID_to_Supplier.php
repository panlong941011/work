<?php

use yii\db\Migration;

/**
 * Class m190509_010731_add_BuyerID_to_Supplier
 */
class m190509_010731_add_BuyerID_to_Supplier extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function up()
    {
	    $this->addColumn('Supplier', 'BuyerID', $this->integer()->comment('渠道商ID'));
    }

    /**
     * {@inheritdoc}
     */
    public function down()
    {
	    $this->dropColumn('Supplier', 'BuyerID');
    }

    
}
