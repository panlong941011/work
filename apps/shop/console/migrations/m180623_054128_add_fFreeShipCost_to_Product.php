<?php

use yii\db\Migration;

/**
 * Class m180623_054128_add_fFreeShipCost_to_Product
 */
class m180623_054128_add_fFreeShipCost_to_Product extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function Up()
    {
        $this->addColumn('Product','fFreeShipCost',$this->decimal(19,2));
        $this->addColumn('Product','fShipAdjust',$this->decimal(19,2));
        $this->addColumn('Product','fCostControl',$this->decimal(19,2));
        $this->addColumn('Product','MemberShipTemplateID',$this->integer(11));
    }

    /**
     * {@inheritdoc}
     */
    public function Down()
    {
        $this->dropColumn('Product','fFreeShipCost');
        $this->dropColumn('Product','fShipAdjust');
        $this->dropColumn('Product','fCostControl');
        $this->dropColumn('Product','MemberShipTemplateID');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m180623_054128_add_fFreeShipCost_to_Product cannot be reverted.\n";

        return false;
    }
    */
}
