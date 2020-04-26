<?php

use yii\db\Migration;

/**
 * Class m180702_055349_add_sServiceTel_to_Buyer
 */
class m180702_055349_add_sServiceTel_to_Buyer extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function up()
    {
        $this->addColumn('Buyer', 'sServiceTel', $this->string());//子平台客服电话
    }

    /**
     * {@inheritdoc}
     */
    public function down()
    {
        $this->dropColumn('Buyer', 'sServiceTel');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m180702_055349_add_sServiceTel_to_Buyer cannot be reverted.\n";

        return false;
    }
    */
}
