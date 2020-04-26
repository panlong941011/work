<?php

use yii\db\Migration;

class m190306_210359_create_Suppliercode extends Migration
{

    public function Up()
    {
        //供应商编码表
        $this->createTable('SupplierCode',[
            'lID' => $this->primaryKey(),
            'sCode' => $this->string(100)
        ]);
    }

    public function Down()
    {
        $this->dropTable('SupplierCode');
    }

}
