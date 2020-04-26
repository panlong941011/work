<?php

use yii\db\Migration;

/**
 * Class m180630_044139_update_mallconfig_ordercomplete
 */
class m180630_044139_update_mallconfig_ordercomplete extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function Up()
    {
        $this->execute("UPDATE MallConfig SET sValue='wuliu' WHERE sKey='sOrderCompleteDependOn'");
        Yii::$app->cache->flush();
    }

    /**
     * {@inheritdoc}
     */
    public function Down()
    {
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m180630_044139_update_mallconfig_ordercomplete cannot be reverted.\n";

        return false;
    }
    */
}
