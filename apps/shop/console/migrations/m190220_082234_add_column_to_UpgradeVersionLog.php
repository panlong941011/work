<?php

use yii\db\Migration;

/**
 * 云端版本更新记录
 * Class m190220_082234_add_column_to_UpgradeVersionLog
 * @author ouyangyz
 * @time 2019-2-20 16:28:02
 */
class m190220_082234_add_column_to_UpgradeVersionLog extends Migration
{
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {
    	//增加推送到子平台相关记录信息
		$this->addColumn('UpgradeVersionLog','bPush',$this->boolean()->defaultValue('1')->comment('是否推送'));
		$this->addColumn('UpgradeVersionLog','sPush',$this->string()->comment('未推送子平台ID'));
		$this->addColumn('UpgradeVersionLog','dPush',$this->dateTime()->comment('推送时间'));
    }

    public function down()
    {
    	$this->dropColumn('UpgradeVersionLog','bPush');
    	$this->dropColumn('UpgradeVersionLog','sPush');
    	$this->dropColumn('UpgradeVersionLog','dPush');
        return false;
    }
}
