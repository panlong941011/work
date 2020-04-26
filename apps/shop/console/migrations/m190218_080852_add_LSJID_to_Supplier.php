<?php

use yii\db\Migration;

/**
 * Class m190218_080852_add_LSJID_to_Supplier
 */
class m190218_080852_add_LSJID_to_Supplier extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function up()
    {
	    $this->addColumn('Supplier','LSJID',$this->integer()->comment('来三斤ID'));
    }

    /**
     * {@inheritdoc}
     */
    public function down()
    {
        echo "m190218_080852_add_LSJID_to_Supplier cannot be reverted.\n";

        return false;
    }
}
