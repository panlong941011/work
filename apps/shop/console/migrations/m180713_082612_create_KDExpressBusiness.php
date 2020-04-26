<?php

use yii\db\Migration;

/**
 * Class m180713_082612_create_KDExpressBusiness
 */
class m180713_082612_create_KDExpressBusiness extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function up()
    {
        //快递业务表
        $this->createTable('KDExpressBusiness',[
            'lID' => $this->primaryKey(),
            'sName' => $this->string(),
            'sKdBirdCode' => $this->string(),
            'lKdBirdNo' => $this->integer(),
        ]);

        //写入数据
        $this->batchInsert('KDExpressBusiness', ['sName', 'sKdBirdCode','lKdBirdNo'], [
            ['360特惠件', 'DBL','2'],
            ['电商尊享', 'DBL','3'],
            ['特准快件', 'DBL','1'],
            ['标准快递', 'DBL','4'],
            ['汽配专线', 'SF','17'],
            ['汽配吉运', 'SF','18'],
            ['生鲜速配', 'SF','15'],
            ['物流普运', 'SF','13'],
            ['全球顺', 'SF','19'],
            ['冷运宅配', 'SF','14'],
            ['大闸蟹专递', 'SF','16'],
            ['医药常温', 'SF','11'],
            ['医药温控', 'SF','12'],
            ['顺丰宝挂号', 'SF','10'],
            ['顺丰特惠', 'SF','2'],
            ['顺丰标快', 'SF','1'],
            ['顺丰宝平邮', 'SF','9'],
            ['顺丰即日', 'SF','6'],
            ['顺丰次晨', 'SF','5']
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function down()
    {
        $this->dropTable('KDExpressBusiness');
    }
}
