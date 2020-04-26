<?php

use yii\db\Migration;

class m190306_210342_create_Kingdeecertificate extends Migration
{
    /**
     *
     */
    public function up()
    {
        //金蝶财务数据导出
        $this->createTable('Kingdeecertificate',[
            'lID' => $this->primaryKey(),
            'dNewDate'=>$this->date()->notNull()->comment('凭证日期'),
            'lYear'=>$this->integer()->notNull()->defaultValue(2018)->comment('会计年度'),
            'lDuration'=>$this->integer()->notNull()->defaultValue(1)->comment('会计期间'),
            'sChar'=>$this->char(2)->notNull()->defaultValue('记')->comment('凭证字'),
            'lNo'=>$this->integer()->notNull()->defaultValue(1)->comment('凭证号'),
            'sCode'=>$this->string(20)->notNull()->comment('科目代码'),
            'sName'=>$this->string(20)->notNull()->comment('科目名称'),
            'sMoneyCode'=>$this->char(4)->notNull()->defaultValue('RMB')->comment('币别代码'),
            'sMoneyName'=>$this->char(7)->notNull()->defaultValue('人民币')->comment('币别名称'),
            'fMoneyChange'=>$this->decimal(10,4)->notNull()->defaultValue(0.0000)->comment('原币金额'),
            'fDebtorMoney'=>$this->decimal(10,4)->notNull()->defaultValue(0.0000)->comment('借方'),
            'fLenderMoney'=>$this->decimal(10,4)->notNull()->defaultValue(0.0000)->comment('贷方'),
            'sBill'=>$this->char(14)->notNull()->defaultValue('系统')->comment('制单'),
            'sCheck'=>$this->char(4)->notNull()->defaultValue('NONE')->comment('审核'),
            'sStandard'=>$this->char(4)->notNull()->defaultValue('NONE')->comment('标准'),
            'sTeller'=>$this->char(4)->notNull()->defaultValue('NONE')->comment('出纳'),
            'sAgent'=>$this->string(1)->notNull()->defaultValue('')->comment('经办'),
            'sMethod'=>$this->char(2)->notNull()->defaultValue('*')->comment('结算方式'),
            'sCloseNum'=>$this->string(1)->notNull()->defaultValue('')->comment('结算号'),
            'sMark'=>$this->string(50)->notNull()->defaultValue('')->comment('凭证摘要（订单号）'),
            'sNum'=>$this->char(2)->notNull()->defaultValue(0)->comment('数量'),
            'sNumUnit'=>$this->char(2)->notNull()->defaultValue('*')->comment('数量单位'),
            'sPrice'=>$this->char(2)->notNull()->defaultValue(0)->comment('单价'),
            'sRefer'=>$this->string(1)->notNull()->defaultValue('')->comment('参考信息'),
            'dSaileDate'=>$this->date()->notNull()->comment('业务日期'),
            'sSaileCode'=>$this->string(1)->notNull()->defaultValue('')->comment('往来业务编号'),
            'lAccessoryNum'=>$this->integer()->notNull()->defaultValue(0)->comment('附件数'),
            'lSerialNum'=>$this->integer()->notNull()->defaultValue(1)->comment('序号'),
            'sSys'=>$this->string(1)->notNull()->defaultValue('')->comment('系统模块'),
            'sRemark'=>$this->string(1)->notNull()->defaultValue('')->comment('业务秒杀'),
            'lRate'=>$this->integer()->notNull()->defaultValue(1)->comment('汇率'),
            'lRecordNum'=>$this->integer()->notNull()->defaultValue(0)->comment('分录序号'),
            'sItem'=>$this->string(60)->notNull()->defaultValue('')->comment('核算项目'),
            'lCheckBill'=>$this->integer()->notNull()->defaultValue(0)->comment('过账'),
            'sMechanism'=>$this->string(1)->notNull()->defaultValue('')->comment('机制凭证'),
            'sFlow'=>$this->string(1)->notNull()->defaultValue('')->comment('现金流量'),
            'lstatus'=>$this->integer()->notNull()->defaultValue(0)->comment('1已导出'),
            'TypeID'=>$this->integer()->comment(''),
        ]);
        $this->createIndex('dNewDate', 'Kingdeecertificate', 'dNewDate');
    }

    /**
     *
     */
    public function down()
    {
        $this->dropTable('Kingdeecertificate');
    }

}
