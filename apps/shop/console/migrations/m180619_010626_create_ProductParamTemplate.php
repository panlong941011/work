<?php

use yii\db\Migration;

/**
 * Class m180619_010626_create_ProductParamTemplate
 */
class m180619_010626_create_ProductParamTemplate extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function up()
    {
        //创建商品参数模板表
        $this->createTable('ProductParamTemplate', [
            'lID' => $this->primaryKey(),
            'sName' => $this->string(),
            'NewUserID' => $this->integer(),
            'EditUserID' => $this->integer(),
            'dNewDate' => $this->dateTime(),
            'dEditDate' => $this->dateTime(),
            'value' => $this->string(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function down()
    {
        $this->dropTable('ProductParamTemplate');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m180619_010626_create_ProductParamTemplate cannot be reverted.\n";

        return false;
    }
    */
}
