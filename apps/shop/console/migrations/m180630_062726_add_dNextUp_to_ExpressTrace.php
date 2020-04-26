<?php

use yii\db\Migration;

/**
 * Class m180630_062726_add_dNextUp_to_ExpressTrace
 */
class m180630_062726_add_dNextUp_to_ExpressTrace extends Migration
{
    public function up()
    {
        $this->addColumn("ExpressTrace", "dNextUp", $this->dateTime());
        $this->addColumn("ExpressTrace", "sCompanyCode", $this->string(50));
        $this->createIndex('dNextUp', 'ExpressTrace', 'dNextUp,sStatus');//增加新索引
        Yii::$app->cache->flush();
    }

    public function down()
    {
        $this->dropColumn("ExpressTrace", "dNextUp");
        $this->dropColumn("ExpressTrace", "sCompanyCode");
        $this->dropIndex('dNextUp', 'ExpressTrace');
        return true;
    }
}
