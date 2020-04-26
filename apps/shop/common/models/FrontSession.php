<?php

namespace myerm\shop\common\models;

use myerm\common\components\CommonEvent;
use myerm\shop\mobile\models\Redbag;
use myerm\shop\mobile\models\WXUser;
use Yii;
use yii\base\Event;

/**
 * ShopERM公共会话类
 * ============================================================================
 * 版权所有 2010-2017 三只象（厦门）网络科技有限公司，并保留所有权利。
 * 网站地址: http://www.myerm.cn
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和
 * 使用；不允许对程序代码以任何形式任何目的的再发布。
 * ============================================================================
 * @author 陈鹭明  <lumingchen@qq.com>
 * @time 2017年9月4日 22:22:57
 * @version v1.0
 */
class FrontSession extends ShopModel
{
    /**
     * 登录事件
     */
    const EVENT_LOGIN = 'login';
    /**
     * 会话开始事件
     */
    const EVENT_START = 'start';
    /**
     * 会话结束事件
     */
    const EVENT_END = 'end';
    /**
     * 会话的过期时间
     */
    public $lExpireTime = 1800;

    /**
     * 执行微信登录动作
     * @time 2017年9月20日 15:48:12
     * @author 陈鹭明
     */
    public static function wxLogin(CommonEvent $event)
    {
        $wxUser = $event->extraData;

        $arrToken = $wxUser->getAccessToken();

        $member = \Yii::$app->member->findByOpenID($arrToken['openid']);

        $session = static::findOne(\Yii::$app->frontsession->ID);

        //要求手机号注册，如果没有手机号，就不能登录
//        if ($member && MallConfig::getValueByKey('bRequireMobileReg') && $member->sMobile) {
//            $session->MemberID = $member->lID;
//        }
        $session->MemberID = $member->lID;
        $session->sOpenID = $arrToken['openid'];
        $session->dLoginTime = \Yii::$app->formatter->asDatetime(time());
        $session->sLoginIP = \Yii::$app->request->userIP;
        $session->save();
    }

    /**
     * 退出
     * @param Event $event
     */
    public static function logout(Event $event)
    {
        $session = static::findOne(\Yii::$app->frontsession->ID);
        $session->MemberID = null;
        $session->save();
    }

    /**
     * 用手机号登录
     * @param $sMobile
     * @param $sPass
     */
    public function mobileLogin($sMobile, $sPass)
    {
        $member = Yii::$app->member->findByMobile($sMobile);
        if (!$member) {
            return ['status' => false, 'message' => '用户不存在或密码不正确'];
        } elseif ($member->sPass != $sPass) {
            return ['status' => false, 'message' => '用户不存在或密码不正确'];
        }

        if ($member->sOpenID) {
            $wxUser = Yii::$app->wxuser->findByOpenID($member->sOpenID);
        } else {
            $wxUser = Yii::$app->wxuser->findBySessionID($member->sOpenID);
        }

        if ($wxUser) {
            $member->sUserAccessToken = $wxUser->sUserAccessToken;
            $member->sUserRefreshToken = $wxUser->sUserRefreshToken;
            $member->sOpenID = $wxUser->sOpenID;
            $member->dTokenExpiresTime = $wxUser->dTokenExpiresTime;
            $member->sScope = $wxUser->sScope;
            $member->SexID = $wxUser->SexID;
            $member->sLanguage = $wxUser->sLanguage;
            $member->sCity = $wxUser->sCity;
            $member->sProvince = $wxUser->sProvince;
            $member->sCountry = $wxUser->sCountry;
            $member->sAvatar = $wxUser->sAvatar;
            $member->sAvatarPath = $wxUser->sAvatarPath;
            $member->WeChatSessionID = \Yii::$app->frontsession->ID;
            $member->save();

            $wxUser->MemberID = $member->lID;
            $wxUser->save();
        }

        $session = static::findOne(\Yii::$app->frontsession->ID);
        $session->MemberID = $member->lID;
        $session->dLoginTime = \Yii::$app->formatter->asDatetime(time());
        $session->sLoginIP = \Yii::$app->request->userIP;
        $session->save();
        Member::AddRedBag($member->lID);
        return ['status' => true];
    }

    /**
     * 创建会话
     */
    public function start()
    {
        $cookies = Yii::$app->request->cookies;
        $sSessionID = $cookies->getValue("ShopERMFrontSessionID");

        if (!$sSessionID) {//新建cookie
            Yii::trace($sSessionID, 'cookie中ShopERMFrontSessionID不存在，新建ShopERMFrontSessionID');
            $sSessionID = $this->buildNounceString(12);
        }

        $session = static::find()->where(['ID' => $sSessionID])->one();
        if (empty($session)) {//新建session
            $session = new FrontSession();
            $session->ID = $sSessionID;
            $session->sUserIP = Yii::$app->request->userIP;
        }

        if ($session->bWXLogin && !$session->bLogin && $session->wxUser->MemberID) {
            $session->MemberID = $session->wxUser->MemberID;
        }

        \Yii::$app->frontsession->MemberID = $session->MemberID;

        //如果URL带有shop的链接,且不是默认的平台链接
        if (\Yii::$app->request->shopUrl != 'shop0' && \Yii::$app->frontsession->urlSellerShop) {
            $session->sShopUrl = \Yii::$app->request->shopUrl;
        }

        \Yii::$app->frontsession->sOpenID = $session->sOpenID;

        $session->dLastActivity = \Yii::$app->formatter->asDatetime(time());
        $session->save();

        \Yii::$app->frontsession->setAttributes($session->getAttributes(), false);

        $arrServerName = explode(".", $_SERVER['HTTP_HOST']);
        $sServerName = $arrServerName[1] . '.' . $arrServerName[2];
        Yii::$app->response->cookies->add(new \yii\web\Cookie([
            'name' => "ShopERMFrontSessionID",
            'value' => $sSessionID,
        ]));
        if ($session->MemberID) {
            Member::AddRedBag($session->MemberID);
        }

        //触发事件
        \Yii::trace("触发会话开始事件");
        $event = new CommonEvent();
        $event->extraData = $session;
        $this->trigger(self::EVENT_START, $event);
    }

    /**
     * 计算随机数
     * @param int $lLength
     * @return string
     */
    private function buildNounceString($lLength = 10)
    {
        $sNounce = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
        $lNounce = strlen($sNounce) - 1;

        $arrReturn = [];
        for ($i = 0; $i < $lLength; $i++) {
            $number = rand(0, $lNounce);
            $arrReturn[] = $sNounce[$number];
        }
        $sReturn = implode('', $arrReturn);

        return $sReturn;
    }

    /**
     * 会话结束，更新最近访问的时间
     */
    public function end()
    {
        //触发事件
        \Yii::trace("触发会话结束事件");
        $event = new CommonEvent();
        $event->extraData = $this;
        $this->trigger(self::EVENT_END, $event);
    }

    /**
     *  清除过期的会话
     * @return boolean
     */
    public function clearExpire()
    {
        static::deleteAll(['<', 'dLastActivity', \Yii::$app->formatter->asDatetime(time() - $this->lExpireTime)]);
        return true;
    }

    /**
     *  清空所有会话数据
     * @return boolean
     */
    public function clearAll()
    {
        static::deleteAll();
        return true;
    }

    /**
     *  是否登陆
     * @return boolean
     */
    public function getBLogin()
    {
        return !!$this->MemberID;
    }

    /**
     * 关联微信用户
     * @return \yii\db\ActiveQuery
     */
    public function getWxUser()
    {
        return $this->hasOne(WXUser::className(), ['sOpenID' => 'sOpenID']);
    }

    /**
     * 是否微信登录了
     */
    public function getBWXLogin()
    {
        if ($this->wxUser) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * 关联会员
     */
    public function getMember()
    {
        return $this->hasOne(Member::className(), ['lID' => 'MemberID']);
    }

    /**
     * 关联当前登录的经销商
     */
    public function getSeller()
    {
        return $this->hasOne(Seller::className(), ['lID' => 'MemberID']);
    }

    public function getShopID()
    {
        return \Yii::$app->request->shopID;
    }

    /**
     * 关联当前登录的经销商店铺
     */
    public function getSellerShop()
    {
        return $this->hasOne(SellerShop::className(), ['lID' => 'MemberID']);
    }

    /**
     * 关联当前链接的经销商
     */
    public function getUrlSeller()
    {
        $res = $this->hasOne(Seller::className(), ['lID' => 'shopID']);
        return $res;
    }

    /**
     * 当前链接所属的经销商店铺
     */
    public function getUrlSellerShop()
    {
        return $this->hasOne(SellerShop::className(), ['lID' => 'shopID']);
    }
}