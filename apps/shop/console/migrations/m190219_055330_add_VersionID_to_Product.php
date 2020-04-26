<?php

use yii\db\Migration;

/**
 * Class m190219_055330_add_VersionID_to_Product
 */
class m190219_055330_add_VersionID_to_Product extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function up()
    {
	    $this->addColumn('Product','VersionID',$this->integer()->comment('版本号'));
	    $this->addColumn('Product','dUpgradeDate',$this->dateTime()->comment('更新时间'));
	    $this->addColumn('Product','LSJID',$this->integer()->comment('来三斤ID'));
	    $this->addColumn('Product','LSJStandardID',$this->integer()->comment('来三斤规格ID'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m190219_055330_add_VersionID_to_Product cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m190219_055330_add_VersionID_to_Product cannot be reverted.\n";

        return false;
    }
    */
}
