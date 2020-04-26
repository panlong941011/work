<?php

use yii\db\Migration;

/**
 * Class m190315_070422_UpdatePromotionLog
 */
class m190315_070422_UpdatePromotionLog extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function up()
    {
		$this->createTable('UpdatePromotionLog',[
			'lID' => $this->primaryKey(),
			'SalesPromotionID' => $this->integer()->comment('促销活动ID'),
			'StatusID' => $this->integer()->defaultValue(1)->comment('同步状态（1：未完成；2：已完成）'),
			'dNewDate' => $this->dateTime(),
			'dUpdateDate' => $this->dateTime(),
			'bPush' => $this->integer()->defaultValue(1)->comment('是否推送完成'),
			'sPush' => $this->string()->comment('推送平台'),
			'dPushDate' => $this->dateTime()->comment('推送时间'),
			'LSJID' => $this->integer()->comment('来三斤活动ID'),
		]);
    }

    /**
     * {@inheritdoc}
     */
    public function down()
    {
        $this->dropTable('UpdatePromotionLog');
    }
    
}
