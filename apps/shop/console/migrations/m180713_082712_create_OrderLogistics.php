<?php

use yii\db\Migration;

/**
 * Class m180713_082712_create_OrderLogistics
 */
class m180713_082712_create_OrderLogistics extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function up()
    {
        //订单物流表
        $this->createTable('OrderLogistics',[
            'lID' => $this->primaryKey(),
            'sName' => $this->string(),
            'sProductInfo' => $this->text(),
            'sOrderDetailID' => $this->string(),
            'OrderID' => $this->integer(),
            'sExpressNo' => $this->string(),
            'sExpressCompany' => $this->string(),
            'dNewDate' => $this->dateTime(),
            'sReturnedTemplate' => $this->text(),
            'sExpressOrderInfo' => $this->text(),
            'ExpressOrderStatusID' => $this->string(),
            'SupplierID' => $this->integer(),
            'sReason' => $this->string(),
            'Ship' => $this->string(50),
            'dShipDate' => $this->dateTime()
        ]);

        $this->createIndex('OrderID','OrderLogistics','OrderID');
        $this->createIndex('sExpressNo','OrderLogistics','sExpressNo');
    }

    /**
     * {@inheritdoc}
     */
    public function down()
    {
        $this->dropTable('OrderLogistics');
    }
}
