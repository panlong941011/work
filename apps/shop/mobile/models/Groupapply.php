<?php
/**
 * Created by PhpStorm.
 * User: oyyz <oyyz@3elephant.com>
 * Date: 2017/10/7 0007
 * Time: 下午 3:59
 */

namespace myerm\shop\mobile\models;
use myerm\shop\common\models\ShopModel;


/**
 * 地区类
 */
class Groupapply extends ShopModel
{
    public static function getDb()
    {
        return \Yii::$app->get('ds_cloud');
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

    public static function newAddress($data)
    {
        $address = new static();
        $address->sName = $data['sName'];
        $address->MemberID = \Yii::$app->frontsession->MemberID;

        $province = \Yii::$app->area->getProvinceByName($data['sProvince']);
        $address->ProvinceID = $province->ID;

        $city = \Yii::$app->area->getCityByName($data['sProvince'], $data['sCity']);
        $address->CityID = $city->ID;

        $area = \Yii::$app->area->getAreaByName($data['sProvince'], $data['sCity'], $data['sArea']);
        $address->AreaID = $area->ID;

        $address->sAddress = $data['sAddress'];
        $address->sMobile = $data['sMobile'];
        $address->bGroup = $data['bGroup'];
        $address->bShop = $data['bShop'];
        $address->bHas = $data['bHas'];
        $address->sMsg = $data['sMsg'];
        $address->dNewDate=date('Y-m-d h:i:s');
        $address->save();
        return $address->lID;
    }

    public function getTel(){
        return  substr($this->sMobile, 0, 3).'****'.substr($this->sMobile, 7);
    }
}