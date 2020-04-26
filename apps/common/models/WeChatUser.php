<?php

namespace myerm\common\models;

/**
 * 微信用户公共类
 * ============================================================================
 * 版权所有 2010-2017 三只象（厦门）网络科技有限公司，并保留所有权利。
 * 网站地址: http://www.myerm.cn
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和
 * 使用；不允许对程序代码以任何形式任何目的的再发布。
 * ============================================================================
 * @author 陈鹭明  <lumingchen@qq.com>
 * @since 2017年9月4日 22:22:57
 * @version v2.0
 */
class WeChatUser extends MyERMModel
{

    public static function saveUser(\Overtrue\Socialite\User $wechatUser)
    {
        $weChat = static::findOne(['SessionID' => \Yii::$app->frontsession->ID]);
        if (!$weChat) {
            if (\Yii::$app->frontsession->MemberID) {
                $weChat = static::findOne(['MemberID' => \Yii::$app->frontsession->MemberID]);
            }

            if (!$weChat) {
                $weChat = static::findOne(['sOpenID' => $wechatUser->getId()]);
            }
        }

        if (!$weChat) {//如果已存在记录
            $weChat = new static();
            $weChat->dNewDate = \Yii::$app->formatter->asDatetime(time());
        }

        $weChat->dEditDate = \Yii::$app->formatter->asDatetime(time());
        $weChat->SessionID = \Yii::$app->frontsession->ID;
        $weChat->MemberID = \Yii::$app->frontsession->MemberID;
        $weChat->sName = $wechatUser->getName();
        $weChat->sNickName = $wechatUser->getNickname();

        $arrToken = $wechatUser->getAccessToken();
        $weChat->sUserAccessToken = $arrToken['access_token'];
        $weChat->sUserRefreshToken = $arrToken['refresh_token'];
        $weChat->sOpenID = $arrToken['openid'];
        $weChat->dTokenExpiresTime = \Yii::$app->formatter->asDatetime(time() + $arrToken['expires_in']);
        $weChat->sScope = $arrToken['scope'];

        $arrMoreInfo = $wechatUser->getOriginal();
        $weChat->SexID = $arrMoreInfo['sex'];
        $weChat->sLanguage = $arrMoreInfo['language'];
        $weChat->sCity = $arrMoreInfo['city'];
        $weChat->sProvince = $arrMoreInfo['province'];
        $weChat->sCountry = $arrMoreInfo['country'];
        $weChat->sAvatar = $arrMoreInfo['headimgurl'];
        $weChat->save();
    }

}