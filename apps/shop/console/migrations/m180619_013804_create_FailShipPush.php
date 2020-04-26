<?php

use yii\db\Migration;

/**
 * Class m180619_013804_create_FailShipPush
 */
class m180619_013804_create_FailShipPush extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function up()
    {
        //发货推送失败记录表
        $this->createTable('FailShipPush', [
            'lID' => $this->primaryKey(),
            'sName' => $this->string(),//订单编号
            'ProductID' => $this->integer(),//商品ID
            'OrderID' => $this->integer(),//订单ID
            'OrderDetailID' => $this->integer(),//订单详情ID
            'sShipNo' => $this->string(),//快递单号
            'ShipCompanyID' => $this->string(),//快递公司
            'BuyerID' => $this->integer(),//渠道商ID
            'sCloudShipApiUrl' => $this->string(),//子平台推送API接口
            'dNewDate' => $this->dateTime(),//新建时间
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function down()
    {
        $this->dropTable('FailShipPush');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m180619_013804_create_FailShipPush cannot be reverted.\n";

        return false;
    }
    */
}
