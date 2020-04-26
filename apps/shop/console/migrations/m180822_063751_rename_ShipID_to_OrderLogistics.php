<?php

use yii\db\Migration;

/**
 * Class m180822_063751_rename_ShipID_to_OrderLogistics
 */
class m180822_063751_rename_ShipID_to_OrderLogistics extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function Up()
    {
        $this->renameColumn('OrderLogistics','Ship','ShipID');
        $this->addColumn('OrderLogistics','lChildID',$this->integer());
    }

    /**
     * {@inheritdoc}
     */
    public function Down()
    {
        $this->renameColumn('OrderLogistics','ShipID','Ship');
        $this->dropColumn('OrderLogistics','lChildID');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m180822_063751_rename_ShipID_to_OrderLogistics cannot be reverted.\n";

        return false;
    }
    */
}
