<?php

use yii\db\Migration;

/**
 * Class m180928_020734_update_ExpressCompany
 */
class m180928_020734_update_ExpressCompany extends Migration
{

    // Use up()/down() to run migration code without a transaction.
    public function up()
    {
        $this->update('ExpressCompany',['sKdBirdCode' => 'HTKD'],['ID'=>'HTKY']);
    }

    public function down()
    {
        $this->update('ExpressCompany',['sKdBirdCode' => 'HTKY'],['ID'=>'HTKY']);
    }

}
