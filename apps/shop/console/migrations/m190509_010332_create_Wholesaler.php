<?php

use yii\db\Migration;

/**
 * Class m190509_010332_create_Wholeasaler
 */
class m190509_010332_create_Wholesaler extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function up()
    {
	    $this->createTable('Wholesaler', [
		    'lID' => $this->primaryKey(),
		    'sName' => $this->string()->comment('真实姓名'),
		    'sMobile' => $this->string()->comment('手机号'),
		    'SupplierID' => $this->integer(11)->comment('所属渠道商'),
		    'dNewDate' => $this->dateTime()->comment('新建时间'),
		    'bActive' => $this->tinyInteger()->comment('是否启用；1启动，0未启用'),
	    ]);
    }

    /**
     * {@inheritdoc}
     */
    public function down()
    {
        $this->dropTable('Wholesaler');
    }
    
}
