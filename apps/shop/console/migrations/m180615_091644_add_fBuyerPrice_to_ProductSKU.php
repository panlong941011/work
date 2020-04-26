<?php

use yii\db\Migration;

/**
 * Class m180615_091644_add_fBuyerPrice_to_ProductSKU
 */
class m180615_091644_add_fBuyerPrice_to_ProductSKU extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function up()
    {
        $this->addColumn('ProductSKU', 'fBuyerPrice', $this->decimal(10, 2));//商品规格渠道价
    }

    /**
     * {@inheritdoc}
     */
    public function down()
    {
        $this->dropColumn('ProductSKU', 'fBuyerPrice');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m180615_091644_add_fBuyerPrice_to_ProductSKU cannot be reverted.\n";

        return false;
    }
    */
}
