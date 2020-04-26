<?php

use yii\db\Migration;

/**
 * Class m180619_020427_add_sReturns_to_Buyer
 */
class m180619_020427_add_sReturns_to_Buyer extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function up()
    {
        $this->addColumn('Buyer', 'sReturns', $this->string(500));//子平台发货推送API接口
    }

    /**
     * {@inheritdoc}
     */
    public function down()
    {
        $this->dropColumn('Buyer', 'sReturns');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m180619_020427_add_sReturns_to_Buyer cannot be reverted.\n";

        return false;
    }
    */
}
