<?php
namespace myerm\shop\common\models;


/**
 * 渠道商类
 */
class Buyer extends ShopModel
{
    public static function getDb()
    {
        return \Yii::$app->get('ds_cloud');
    }

    public function findBySysUserID($SysUserID)
    {
        return static::find()->where(['SysUserID' => $SysUserID])->one();
    }

    public static function findBysIP($sIP){
        return static::find()->where(['sIP' => $sIP])->one();
    }

    public static function getsName($id){
        $Buyer = static::findByID($id);
        return $Buyer->sName;
    }
    public function getBuyerByMobile($sMobile)
    {
        return static::find()->where(['sMobile' => $sMobile])->one();
    }
}