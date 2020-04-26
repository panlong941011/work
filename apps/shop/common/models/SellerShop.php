<?php

namespace myerm\shop\common\models;

use myerm\common\components\CommonEvent;
use myerm\common\components\Image;
use myerm\common\libs\File;
use myerm\common\libs\NewID;


/**
 * 经销商店铺
 */
class SellerShop extends ShopModel
{
    /**
     * 入驻成功
     */
    public static function joinSuccess(CommonEvent $event)
    {
        $sellerJoin = $event->extraData;

        $shop = new static();
        $shop->lID = $sellerJoin->MemberID;
        $shop->sName = MallConfig::getValueByKey('sMallName');
        $shop->SellerID = $sellerJoin->MemberID;
        $shop->sPhone = $sellerJoin->sMobile;
        $shop->dNewDate = $sellerJoin->dJoinDate;
        $shop->save();
    }

    /**
     * 店铺名称：默认为商城名称；此外，当后台【经销商类型设置】店铺设置为否时，直接调用商城名称。
     */
    public function getSShopName()
    {
        if ($this->sellerType->bShopConfig) {
            return $this->sName;
        } else {
            return MallConfig::getValueByKey('sMallName');
        }
    }

    /**
     * 店铺LOGO：默认为商城LOGO；此外，当后台【经销商类型设置】店铺设置为否时，直接调用商城LOGO。
     */
    public function getSShopLogo()
    {
        if ($this->sellerType->bShopConfig && $this->sLogoPath) {
            return $this->sLogoPath;
        } else {
            return MallConfig::getValueByKey('sMallLogo');
        }
    }

    /*
     * 关联经销商
     */
    public function getSeller()
    {
        return $this->hasOne(Seller::className(), ['lID' => 'SellerID']);
    }

    /**
     * 关联经销商的类别
     */
    public function getSellerType()
    {
        return $this->seller->type;
    }

    /**
     * 店铺设置
     * @param $sShopName
     * @param $sLogo
     */
    public function shopConfig($sShopName, $sLogo)
    {
        $data['sName'] = $sShopName;

        if (substr($sLogo, 0, 5) == 'data:') {
            $arrFileInfo = File::parseImageFromBase64($sLogo);
            $sFileName = NewID::make() . "." . $arrFileInfo[0];
            Image::resize($arrFileInfo[1], 320, File::getUploadDir() . "/" . $sFileName);
            $data['sLogo'] = $data['sName'];
            $data['sLogoPath'] = str_ireplace(\Yii::$app->params['sUploadDir'] . "/", "",
                File::getUploadDir() . "/" . $sFileName);
        }

        static::updateAll($data, ['lID' => \Yii::$app->frontsession->MemberID]);
    }

    public function getShop($SellerID)
    {
        return self::findOne(['SellerID' => $SellerID]);
    }
}