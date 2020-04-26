<?php

use yii\db\Migration;

/**
 * Class m180619_010121_add_sSKU_to_ProductStockChange
 */
class m180619_010121_add_sSKU_to_ProductStockChange extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function up()
    {
        $this->addColumn('ProductStockChange', 'sSKU', $this->string());//商品规格信息
    }

    /**
     * {@inheritdoc}
     */
    public function down()
    {
        $this->dropColumn('ProductStockChange', 'sSKU');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m180619_010121_add_sSKU_to_ProductStockChange cannot be reverted.\n";

        return false;
    }
    */
}
