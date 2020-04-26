<?php

namespace myerm\shop\mobile\models;

/**
 * 订单类
 */
class CloudOrderAddress extends \myerm\shop\common\models\Order
{
    /**
     * 配置数据源
     * @return \yii\db\Connection
     */
    public static function getDb()
    {
        return \Yii::$app->get('ds_cloud');
    }

    public static function tableName()
    {
        return '{{orderaddress}}';
    }
    public function getProvince() {
        return $this->hasOne(Area::className(), ['ID'=>'ProvinceID']);
    }

    public function getCity() {
        return $this->hasOne(Area::className(), ['ID'=>'CityID']);
    }

    public function getArea() {
        return $this->hasOne(Area::className(), ['ID'=>'AreaID']);
    }
}