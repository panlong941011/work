<?php

use yii\db\Migration;

/**
 * Class m180713_082254_create_KDShipAddress
 */
class m180713_082254_create_KDShipAddress extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function up()
    {
        //发货地址表
        $this->createTable('KDShipAddress', [
            'lID' => $this->primaryKey(),
            'sName' => $this->string(),
            'OwnerID' => $this->integer(),
            'NewUserID' => $this->integer(),
            'EditUserID' => $this->integer(),
            'dNewDate' => $this->dateTime(),
            'dEditDate' => $this->dateTime(),
            'bDefault' =>$this->tinyInteger(),
            'ProvinceID' => $this->string(32),
            'CityID' => $this->string(32),
            'AreaID' => $this->string(32),
            'sAddress' => $this->string(500),
            'sExpressCode' => $this->string(500),
            'sExpressKey' => $this->string(500),
            'sExpressName' => $this->string(500),
            'sExpressPassword' => $this->string(500),
            'sExpressSendSite' => $this->string(500),
            'sKdBirdCode' => $this->string(500),
            'sMobile' => $this->string(500),
            'sPostCode' => $this->string(500),
            'sShipper' => $this->string(500),
            'SupplierID' => $this->integer(),
            'ExpressBusinessID' => $this->string(32),
            'SettlementMethodID' => $this->string(32),
            'sExpressCompany' => $this->string(32),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function down()
    {
        $this->dropTable('KDShipAddress');
    }
}
