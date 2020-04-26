<?php

use yii\db\Migration;

/**
 * Handles the creation of table `Announcement`.
 */
class m180629_011029_create_Announcement_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function Up()
    {
        //创建公告管理表
        $this->createTable('Announcement', [
            'lID' => $this->primaryKey(),
            'sName' => $this->string(),
            'lSort' => $this->integer(),
            'sContent' => $this->text(),
            'dNewDate' => $this->dateTime(),
            'dEditDate' => $this->dateTime(),
        ]);
        $this->createIndex('lSort', 'Announcement', 'lSort',true);//增加新索引
        $this->createIndex('dNewDate', 'Announcement', 'dNewDate',true);//增加新索引
    }

    /**
     * {@inheritdoc}
     */
    public function Down()
    {
        $this->dropTable('Announcement');
    }
}
