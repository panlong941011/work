<?php

use yii\db\Migration;

/**
 * Class m180619_010338_add_ProductParamTemplateID_to_Product
 */
class m180619_010338_add_ProductParamTemplateID_to_Product extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function up()
    {
        $this->addColumn('Product', 'ProductParamTemplateID', $this->integer());//商品参数模板ID
        $this->addColumn('Product', 'sParameterArray', $this->string(1000));//商品参数
    }

    /**
     * {@inheritdoc}
     */
    public function down()
    {
        $this->dropColumn('Product', 'ProductParamTemplateID');
        $this->dropColumn('Product', 'sParameterArray');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m180619_010338_add_ProductParamTemplateID_to_Product cannot be reverted.\n";

        return false;
    }
    */
}
