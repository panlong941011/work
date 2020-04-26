<?php

namespace myerm\shop\mobile\controllers;

use myerm\common\components\CommonEvent;
use myerm\shop\common\models\Seller;
use myerm\shop\mobile\models\WXUser;
use myerm\shop\common\models\Member;

/**
 *微信信息
 */
class WechatController extends Controller
{
    public $enableCsrfValidation = false;

    //定义Token
    public $Token = 'TOKEN_DANONGYUN';

    //邀请好友推送消息
    const INVITE_FRIENDS = 'invitefriends';

    //邀请开店推送消息
    const INVITE_SHOP = 'inviteshop';

    /**
     * 公测版使用正式站的微信公众号
     * @return \yii\web\Response
     * @author oyyz <oyyz@3elephant.com>
     * @time 2018-6-20 16:54:01
     */
    public function actionCallback()
    {
        $url = 'http://m.beta.dny.group/member/wechatcallback?code=' . $_GET['code'] . "&state=" . $_GET['state'];
        return $this->redirect($url);
    }

    /**
     * 微信参数接收处理
     * @author 张正帝  <919059960@qq.com>
     * @time 2018年07月17日
     */
    public function actionResponse()
    {
        $server = \Yii::$app->wechat->server;
        $server->setMessageHandler(function ($objData) {
            if ($objData->MsgType == 'event') {
                //参数处理
                $arrParam = explode(',', $objData->EventKey);
                $actionName = $arrParam[0];
                $InviterID = $arrParam[1];
                $InviterOpenID = $arrParam[3];

                //注册会员
                $OpenID = (string)$objData->FromUserName;
                $bNewMember = false;
                $Member = Member::getOpenIDUser($OpenID);
                if (!$Member) {
                    //新建用户
                    $bNewMember = true;
                    $User = \Yii::$app->wechat->getApp()->user->get($OpenID);
                    $Member = Member::addMember($User, $InviterID);
                }
                $WXUser = WXUser::getOpenIDUser($OpenID);
                if (!$WXUser) {
                    //新建微信用户
                    WXUser::addWXUser($Member);
                }
                $Seller = Seller::getSellerUser($Member->lID);

                //推送消息
                $event = new CommonEvent();
                $event->extraData = $Member;
                if ($actionName == 'inviteFriends' && $bNewMember) {
                    $event->sMessage = $InviterOpenID;
                    \Yii::$app->inviteconfig->trigger(static::INVITE_FRIENDS, $event);
                } elseif ($actionName == 'inviteShop' && !$Seller) {
                    $event->sMessage = $InviterID;
                    \Yii::$app->inviteconfig->trigger(static::INVITE_SHOP, $event);
                }
            }
        });
        $response = $server->serve();
        $response->send();
        return false;
    }
}