<?php

use yii\db\Migration;

/**
 * Class m180620_071605_add_MemberShipTemplateID_to_Product
 */
class m180620_071605_add_MemberShipTemplateID_to_Product extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function up()
    {
        $this->addColumn('Product', 'MemberShipTemplateID', $this->integer());//云端商品表增加买家运费模板
    }

    /**
     * {@inheritdoc}
     */
    public function down()
    {
        $this->dropColumn('Product', 'MemberShipTemplateID');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m180620_071605_add_MemberShipTemplateID_to_Product cannot be reverted.\n";

        return false;
    }
    */
}
