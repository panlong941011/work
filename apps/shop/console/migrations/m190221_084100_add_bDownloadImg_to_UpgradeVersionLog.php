<?php

use yii\db\Migration;

/**
 * Class m190221_084100_add_bDownloadImg_to_UpgradeVersionLog
 */
class m190221_084100_add_bDownloadImg_to_UpgradeVersionLog extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function up()
    {
	    $this->addColumn('UpgradeVersionLog','bDownloadImg',$this->integer()->defaultValue(0)->comment('是否下载图片'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m190221_084100_add_bDownloadImg_to_UpgradeVersionLog cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m190221_084100_add_bDownloadImg_to_UpgradeVersionLog cannot be reverted.\n";

        return false;
    }
    */
}
