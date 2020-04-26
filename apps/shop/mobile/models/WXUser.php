<?php

namespace myerm\shop\mobile\models;

use myerm\common\components\CommonEvent;
use myerm\shop\common\models\MallConfig;
use myerm\shop\common\models\Member;
use myerm\shop\common\models\ShopModel;
use yii\base\Event;


/**
 * 微信用户类
 */
class WXUser extends ShopModel
{
    /**
     * 保存微信用户验证回调的信息
     * @param CommonEvent $event
     */
    public static function saveWXUserInfo(CommonEvent $event)
    {
        $wechatUser = $event->extraData;

        $wxUser = static::find()->where(['sOpenID' => $wechatUser->getId()])->one();

        if (!$wxUser) {
            $wxUser = new static();
            $wxUser->dNewDate = \Yii::$app->formatter->asDatetime(time());

            //设置来源，只能在注册的时候设置
            $fromMember = Member::getFromMember();
            if ($fromMember) {
                $wxUser->FromMemberID = $fromMember->lID;
                \Yii::trace("注册来源会员：" . $fromMember->sName);
            } else {
                \Yii::trace("注册来源会员不存在。");
            }
            \Yii::trace("微信用户记录不存在，要新建记录。");
        } else {
            \Yii::trace("微信用户记录存在，要更新记录。");
        }

        $wxUser->sName = $wechatUser->getName();


        $arrToken = $wechatUser->getAccessToken();
        $wxUser->sUserAccessToken = $arrToken['access_token'];
        $wxUser->sUserRefreshToken = $arrToken['refresh_token'];
        $wxUser->sOpenID = $arrToken['openid'];
        $wxUser->dTokenExpiresTime = \Yii::$app->formatter->asDatetime(time() + $arrToken['expires_in']);
        $wxUser->sScope = $arrToken['scope'];

        $arrMoreInfo = $wechatUser->getOriginal();
        $wxUser->SexID = $arrMoreInfo['sex'];
        $wxUser->sLanguage = $arrMoreInfo['language'];
        $wxUser->sCity = $arrMoreInfo['city'];
        $wxUser->sProvince = $arrMoreInfo['province'];
        $wxUser->sCountry = $arrMoreInfo['country'];
        $wxUser->sAvatar = $wxUser->sName;
        $wxUser->sAvatarPath = $arrMoreInfo['headimgurl'];
        $wxUser->SessionID = \Yii::$app->frontsession->ID;

        $member = $wxUser->member;
        if ($member && $member->lID) {
            $wxUser->MemberID = $member->lID;
        }

        $wxUser->save();
    }

    /**
     * 解绑
     * @param Event $event
     */
    public static function unbindWX(Event $event)
    {
        if (!MallConfig::getValueByKey('bRequireMobileReg')) {
            return false;
        }

        $wxUser = static::find()->where(['sOpenID' => \Yii::$app->frontsession->sOpenID])->one();
        if ($wxUser) {
            $wxUser->MemberID = null;
            $wxUser->save();
        }
    }

    public function findByOpenID($sOpenID)
    {
        return static::find()->where(['sOpenID' => $sOpenID])->one();
    }

    public function findBySessionID()
    {
        return static::find()->where(['SessionID' => \Yii::$app->frontsession->ID])->one();
    }

    /**
     * 关联会员
     * @return \yii\db\ActiveQuery
     */
    public function getMember()
    {
        return $this->hasOne(Member::className(), ['sOpenID' => 'sOpenID']);
    }

    /**
     * 根据OpenID查询用户
     * @author 张正帝  <919059960@qq.com>
     * @time 2018年07月19日
     */
    public static function getOpenIDUser($OpenID)
    {
        return WXUser::find()
            ->where(['sOpenID' => $OpenID])
            ->one();
    }

    /**
     * 新增用户
     * @author 张正帝  <919059960@qq.com>
     * @time 2018年07月20日
     */
    public static function addWXUser($Member)
    {
        $WXUser = new WXUser();
        $WXUser->dNewDate = \Yii::$app->formatter->asDatetime(time());
        $WXUser->sName = $Member->sName;
        $WXUser->sOpenID = $Member->sOpenID;
        $WXUser->MemberID = $Member->lID;
        $WXUser->FromMemberID = $Member->FromMemberID;
        $WXUser->SexID = $Member->SexID;
        $WXUser->sAvatar = $Member->sAvatar;
        $WXUser->sAvatarPath = $Member->sAvatarPath;
        $WXUser->sLanguage = $Member->sLanguage;
        $WXUser->SessionID = $Member->WeChatSessionID;
        $WXUser->sCity = $Member->sCity;
        $WXUser->sProvince = $Member->sProvince;
        $WXUser->sCountry = $Member->sCountry;
        $WXUser->save();

        return $WXUser;
    }
}