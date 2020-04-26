<?php

use yii\db\Migration;

/**
 * Class m190225_074628_add_LSJID_to_ShipTemplate
 */
class m190225_074628_add_LSJID_to_ShipTemplate extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function up()
    {
	    $this->addColumn('ShipTemplate', 'LSJID', $this->integer()->defaultValue(0)->comment('来三斤运费模板ID'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m190225_074628_add_LSJID_to_ShipTemplate cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m190225_074628_add_LSJID_to_ShipTemplate cannot be reverted.\n";

        return false;
    }
    */
}
