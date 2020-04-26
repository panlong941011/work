<?php

use yii\db\Migration;

/**
 * Class m190222_004254_add_LSJID_to_UpgradeVersionLog
 */
class m190222_004254_add_LSJID_to_UpgradeVersionLog extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function up()
    {
	    $this->addColumn('UpgradeVersionLog','LSJID',$this->integer()->defaultValue(0)->comment('来三斤商品ID'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m190222_004254_add_LSJID_to_UpgradeVersionLog cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m190222_004254_add_LSJID_to_UpgradeVersionLog cannot be reverted.\n";

        return false;
    }
    */
}
