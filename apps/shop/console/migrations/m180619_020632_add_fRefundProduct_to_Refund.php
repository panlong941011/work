<?php

use yii\db\Migration;

/**
 * Class m180619_020632_add_fRefundProduct_to_Refund
 */
class m180619_020632_add_fRefundProduct_to_Refund extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function up()
    {
        $this->addColumn('Refund', 'fRefundProduct', $this->decimal(10, 2));//子平台商品退款金额
        $this->addColumn('Refund', 'fProductPrice', $this->decimal(10, 2));//子平台商品总价
        $this->addColumn('Refund', 'fBuyerRefundProduct', $this->decimal(10, 2));//渠道商商品退款金额
        $this->addColumn('Refund', 'fSupplierRefundProduct', $this->decimal(10, 2));//供应商商品退款金额
    }

    /**
     * {@inheritdoc}
     */
    public function down()
    {
        $this->dropColumn('Refund', 'fRefundProduct');
        $this->dropColumn('Refund', 'fProductPrice');
        $this->dropColumn('Refund', 'fBuyerRefundProduct');
        $this->dropColumn('Refund', 'fSupplierRefundProduct');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m180619_020632_add_fRefundProduct_to_Refund cannot be reverted.\n";

        return false;
    }
    */
}
