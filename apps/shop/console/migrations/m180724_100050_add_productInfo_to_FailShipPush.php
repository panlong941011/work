<?php

use yii\db\Migration;

/**
 * Class m180724_100050_add_productInfo_to_FailShipPush
 */
class m180724_100050_add_productInfo_to_FailShipPush extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function up()
    {
        $this->dropColumn('FailShipPush','ProductID');
        $this->alterColumn('FailShipPush','OrderID',$this->integer());
        $this->alterColumn('FailShipPush','OrderDetailID',$this->string());
        $this->addColumn('FailShipPush','sProductInfo',$this->string());
        $this->addColumn('FailShipPush','lNum',$this->integer());
        $this->addColumn('FailShipPush','sFailReason',$this->string());
        $this->addColumn('FailShipPush','OrderLogisticsID',$this->integer());
        $this->addColumn('FailShipPush','lChildID',$this->integer());
    }

    /**
     * {@inheritdoc}
     */
    public function down()
    {
        $this->addColumn('FailShipPush','ProductID',$this->integer());
        $this->alterColumn('FailShipPush','OrderID',$this->string());
        $this->alterColumn('FailShipPush','OrderDetailID',$this->integer());
        $this->dropColumn('FailShipPush','sProductInfo');
        $this->dropColumn('FailShipPush','lNum');
        $this->dropColumn('FailShipPush','sFailReason');
        $this->dropColumn('FailShipPush','OrderLogisticsID');
        $this->dropColumn('FailShipPush','lChildID');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m180724_100050_add_productInfo_to_FailShipPush cannot be reverted.\n";

        return false;
    }
    */
}
