<?php

use yii\db\Migration;

/**
 * Class m180623_060438_create_Index
 */
class m180623_060438_create_Index extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function up()
    {
        $this->dropIndex('OwnerID', 'ProductStockChange');//删除旧索引
        $this->dropIndex('dNewDate', 'ProductStockChange');//删除旧索引
        $this->dropIndex('OrderID', 'ProductStockChange');//删除旧索引
        $this->dropIndex('dCloseDate', 'ProductStockChange');//删除旧索引
        $this->createIndex('OrderID', 'ProductStockChange', 'OrderID,dCloseDate');//增加新索引
        $this->createIndex('sName', 'ProductStockChange', 'sName');//增加新索引
    }

    /**
     * {@inheritdoc}
     */
    public function down()
    {
        $this->createIndex('OwnerID', 'ProductStockChange', 'OwnerID,dNewDate');
        $this->createIndex('dNewDate', 'ProductStockChange', 'dNewDate');
        $this->createIndex('OrderID', 'ProductStockChange', 'OrderID');
        $this->createIndex('dCloseDate', 'ProductStockChange', 'dCloseDate');
        $this->dropIndex('OrderID', 'ProductStockChange');
        $this->dropIndex('sName', 'ProductStockChange');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m180623_060438_create_Index cannot be reverted.\n";

        return false;
    }
    */
}
