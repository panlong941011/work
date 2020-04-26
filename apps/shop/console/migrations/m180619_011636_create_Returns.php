<?php

use yii\db\Migration;

/**
 * Class m180619_011636_create_Returns
 */
class m180619_011636_create_Returns extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function up()
    {
        //退货申请表
        $this->createTable('Returns', [
            'lID' => $this->primaryKey(),
            'sName' => $this->string(),//退货编号
            'ProductID' => $this->integer(),//商品ID
            'OrderID' => $this->integer(),//订单ID
            'OrderDetailID' => $this->integer(),//订单详情ID
            'SupplierID' => $this->integer(),//供应商ID
            'BuyerID' => $this->integer(),//渠道商ID
            'StatusID' => $this->string(),//退货状态
            'lRefundItem' => $this->integer(),//退货数量
            'lItemTotal' => $this->integer(),//总数量
            'fRefundPrice' => $this->decimal(10, 2),//子平台商品退款价格
            'fTotalPrice' => $this->decimal(10, 2),//子平台商品总价
            'sShipVoucher' => $this->text(),//退货物流凭证
            'sRefundVoucher' => $this->text(),//退款凭证
            'ShipCompanyID' => $this->string(),//快递公司
            'sShipNo' => $this->string(),//快递单号
            'sMobile' => $this->string(),//买家手机号
            'sReason' => $this->string(),//退款原因
            'sExplain' => $this->string(),//退款说明
            'dNewDate' => $this->dateTime(),//新建时间
            'dEditDate' => $this->dateTime(),//编辑时间
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function down()
    {
        $this->dropTable('Returns');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m180619_011636_create_Returns cannot be reverted.\n";

        return false;
    }
    */
}
