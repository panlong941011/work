<?php

namespace myerm\shop\common\models;

use myerm\common\components\CommonEvent;
use myerm\shop\mobile\models\Redbag;
use yii\base\Event;

/**
 * 会员类
 */
class Member extends ShopModel
{
    /**
     * 注册事件
     */
    const EVENT_WX_CREATE = 'wx_create';

    /**
     * 登录事件
     */
    const EVENT_WX_LOGIN = 'wx_login';

    public static function getDb()
    {
        return \Yii::$app->get('db_wholesaler');
    }

    /**
     * 通过微信验证回调，创建或更新会员信息.
     * @param \Overtrue\Socialite\User $wechatUser
     * @time 2017年9月20日 13:53:27
     * @author 陈鹭明
     */
    public static function saveWXMemberInfo(CommonEvent $event)
    {
        $wechatUser = $event->extraData;

        $member = null;

        if (\Yii::$app->frontsession->MemberID) {
            $member = static::findOne(['lID' => \Yii::$app->frontsession->MemberID]);
        }

        if (!$member) {
            $member = static::find()->where(['sOpenID' => $wechatUser->getId()])->one();
        }

        if (!$member) {
            $wxUser = \Yii::$app->wxuser->findByOpenID($wechatUser->getId());
            if ($wxUser && $wxUser->MemberID) {
                $member = static::findOne(['lID' => $wxUser->MemberID]);
            }
        }

        //如果启用了手机注册，没有找到会员记录则返回。V1.2
//        if (!$member && MallConfig::getValueByKey('bRequireMobileReg')) {
//            return true;
//        }

        //是否新建会员，如果是会触发事件。
        $bCreate = false;
        if (!$member) {//如果不存在记录
//            $member = static::find()->where(['and',['unionid' => $wechatUser->token->unionid],[]])->one();
//            if(!$member) {
                $member = new static();
            //}
            $member->dNewDate = \Yii::$app->formatter->asDatetime(time());
            $member->dEditDate = \Yii::$app->formatter->asDatetime(time());
            $member->bActive = 1;

            //设置来源，只能在注册的时候设置
            $fromMember = static::getFromMember();
            if ($fromMember) {
                $member->FromMemberID = $fromMember->lID;
                \Yii::trace("注册来源会员：" . $fromMember->sName);
            } else {
                \Yii::trace("注册来源会员不存在。");
            }

            $bCreate = true;

            \Yii::trace("会员记录不存在，要新建记录。");
        } else {
            \Yii::trace("会员记录存在，要更新记录。");
        }

        $member->sName = $wechatUser->getName();
        $member->dLastLoginDate = \Yii::$app->formatter->asDatetime(time());
        $member->sLastLoginIP = \Yii::$app->request->userIP;
        $member->WeChatSessionID = \Yii::$app->frontsession->ID;

        $arrToken = $wechatUser->getAccessToken();

        $member->sUserAccessToken = $arrToken['access_token'];
        $member->sUserRefreshToken = $arrToken['refresh_token'];
        $member->sOpenID = $arrToken['openid'];
        $member->dTokenExpiresTime = \Yii::$app->formatter->asDatetime(time() + $arrToken['expires_in']);
        $member->sScope = $arrToken['scope'];

        $arrMoreInfo = $wechatUser->getOriginal();
        $member->SexID = isset($arrMoreInfo['sex']) ? $arrMoreInfo['sex'] : null;
        $member->sLanguage = $arrMoreInfo['language'];
        $member->sCity = $arrMoreInfo['city'];
        $member->sProvince = $arrMoreInfo['province'];
        $member->sCountry = $arrMoreInfo['country'];
        $member->sAvatar = $member->sName;
        $member->sAvatarPath = $arrMoreInfo['headimgurl'];
        $member->unionid = $wechatUser->token->unionid;
        $member->save();

        if ($bCreate) {
            //添加满减券
            self::AddRedBag($member->lID);
            $event = new CommonEvent;
            $event->extraData = $member;
            \Yii::$app->member->trigger(self::EVENT_WX_CREATE, $event);
        }

        //触发登录事件
        $event = new CommonEvent;
        $event->extraData = $member;
        \Yii::$app->member->trigger(self::EVENT_WX_LOGIN, $event);
    }

    public static function AddRedBag($userID)
    {
        return 0;
        //88元满减券：满500抵50，满300抵30，满99抵10，满50抵5，满20抵2，满10抵1
        $redbag = Redbag::find()->where(['MemberID'=>$userID])->one();
        if ($redbag) {
            return;
        }
        $redbag = new  Redbag();
        $redbag->fChange = 50;
        $redbag->MemberID = $userID;
        $redbag->fTopMoney = 500;
        $redbag->save();

        $redbag = new Redbag();
        $redbag->fChange = 20;
        $redbag->MemberID = $userID;
        $redbag->fTopMoney = 200;
        $redbag->save();

        $redbag = new Redbag();
        $redbag->fChange = 10;
        $redbag->MemberID = $userID;
        $redbag->fTopMoney = 99;
        $redbag->save();

        $redbag = new Redbag();
        $redbag->fChange = 5;
        $redbag->MemberID = $userID;
        $redbag->fTopMoney = 50;
        $redbag->save();

        $redbag = new Redbag();
        $redbag->fChange = 2;
        $redbag->MemberID = $userID;
        $redbag->fTopMoney = 20;
        $redbag->save();

        $redbag = new Redbag();
        $redbag->fChange = 1;
        $redbag->MemberID = $userID;
        $redbag->fTopMoney = 10;
        $redbag->save();
    }

    /**
     * 通过当前的绝对路径，获取到来源的会员信息。例如：路径是/shop1/xxxx/xxxx，那么会员的ID就是1
     * @time 2017年9月20日 13:53:27
     * @author 陈鹭明
     */
    public static function getFromMember()
    {
        return static::findOne(\Yii::$app->request->shopID);
    }

    /**
     * 保存手机号注册的用户信息
     */
    public static function saveMobileRegMemberInfo($sMobile, $sPass)
    {

    }

    /**
     * 微信解绑
     */
    public static function unbindWX(Event $event)
    {
        if (!MallConfig::getValueByKey('bRequireMobileReg')) {
            return false;
        }

        $member = static::findOne(\Yii::$app->frontsession->MemberID);

        if ($member) {
            $member->sUserAccessToken = null;
            $member->sUserRefreshToken = null;
            $member->sOpenID = null;
            $member->dTokenExpiresTime = null;
            $member->sScope = null;
            $member->SexID = null;
            $member->sLanguage = null;
            $member->sCity = null;
            $member->sProvince = null;
            $member->sCountry = null;
            $member->sAvatar = null;
            $member->sAvatarPath = null;
            $member->save();
        }
    }

    /**
     * 通过手机号查找会员
     */
    public function findByMobile($sMobile)
    {
        return static::find()->where(['sMobile' => $sMobile])->one();
    }

    /**
     * 通过OpenID查找会员
     */
    public function findByOpenID($sOpenID)
    {
        return static::find()->where(['sOpenID' => $sOpenID])->one();
    }

    /**
     * 注册保存
     */
    public function reg($sMobile, $sPass)
    {
        $member = \Yii::$app->frontsession->member;
        if ($member) {
            $member->sMobile = $sMobile;
            $member->dEditDate = \Yii::$app->formatter->asDatetime(time());
            $member->sPass = $sPass;
            $member->save();
            return true;
        }
        $member = new static();
        $member->dNewDate = \Yii::$app->formatter->asDatetime(time());
        $member->dEditDate = \Yii::$app->formatter->asDatetime(time());

        //设置来源，只能在注册的时候设置
        $fromMember = static::getFromMember();
        if ($fromMember) {
            $member->FromMemberID = $fromMember->lID;
            \Yii::trace("注册来源会员：" . $fromMember->sName);
        } else {
            \Yii::trace("注册来源会员不存在。");
        }

        $wxUser = \Yii::$app->frontsession->wxUser;
        if ($wxUser) {
            $member->sName = $wxUser->sName;
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
        } else {
            $member->sName = $sMobile;
        }

        $member->sMobile = $sMobile;
        $member->sPass = $sPass;
        $member->dLastLoginDate = \Yii::$app->formatter->asDatetime(time());
        $member->sLastLoginIP = \Yii::$app->request->userIP;
        $member->WeChatSessionID = \Yii::$app->frontsession->ID;
        $member->save();

        \Yii::$app->frontsession->mobileLogin($sMobile, $sPass);

        return true;
    }

    /**
     * 消费累计：付款记录金额-退款成功的退款记录金额
     */
    public function getFSumConsume()
    {
        return \Yii::$app->order->computeMemberConsume($this->lID);
    }


    /**
     * 计算渠道经销商收入
     * @return mixed
     * @author ouyangyz <ouyangyanzhong@163.com>
     * @time 2018-8-22 13:44:01
     */
    public function getWholesaleSellerIncome()
    {
        return \Yii::$app->wholesaleorder::find()
            ->where(['SellerID' => $this->lID])
            ->sum('fSellerProfit');
    }

    /**
     * 修改密码
     * @param $sNewPass
     */
    public function modifyPass($sNewPass)
    {
        $this->sPass = $sNewPass;
        $this->save();
    }

    /**
     * 客户管理
     */
    public function customerList($config)
    {
        $lPage = intval($config['lPage']) > 1 ? intval($config['lPage']) : 1;


        $memberSearch = static::find()->where(['FromMemberID' => \Yii::$app->frontsession->MemberID]);

        if ($config['sType'] == 'member') {
            $memberSearch->andWhere("lID NOT IN (SELECT Member.lID FROM Member INNER JOIN Seller ON Seller.lID=Member.lID WHERE FromMemberID='" . \Yii::$app->frontsession->MemberID . "')");
        }

        $lCount = $memberSearch->count();

        $memberSearch->offset(($lPage - 1) * 10)->limit(10);
        $memberSearch->orderBy($config['sOrderBy'] . " " . $config['sOrderByDir']);

        return [$lCount, $memberSearch->all()];
    }

    /**
     * 获取头像
     */
    public function getAvatar()
    {
        if ($this->sAvatarPath) {
            return $this->sAvatarPath;
        } else {
            return "/images/order/person.png";
        }
    }

    /**
     * 实时统计金币余额
     */
    public function computeGold()
    {
        return \Yii::$app->goldflow->computeGold($this->lID);
    }

    /**
     * 根据OpenID查询用户
     * @author 张正帝  <919059960@qq.com>
     * @time 2018年07月19日
     */
    public static function getOpenIDUser($OpenID)
    {
        return Member::find()
            ->where(['sOpenID' => $OpenID])
            ->one();
    }

    /**
     * 查询邀请人用户
     * @author 张正帝  <919059960@qq.com>
     * @time 2018年07月19日
     */
    public static function getInviterUser($ID)
    {
        return Member::find()
            ->where(['lID' => $ID])
            ->one();
    }

    /**
     * 新增用户
     * @author 张正帝  <919059960@qq.com>
     * @time 2018年07月19日
     */
    public static function addMember($User, $fromMemberID)
    {
        $Member = new Member();
        $Member->dNewDate = \Yii::$app->formatter->asDatetime(time());;
        $Member->dEditDate = \Yii::$app->formatter->asDatetime(time());
        $Member->dLastLoginDate = \Yii::$app->formatter->asDatetime(time());
        $Member->WeChatSessionID = \Yii::$app->frontsession->ID;
        $Member->sLastLoginIP = \Yii::$app->request->userIP;
        $Member->FromMemberID = $fromMemberID;
        $Member->sOpenID = $User->openid;
        $Member->sName = $User->nickname;
        $Member->sAvatar = $User->nickname;
        $Member->sAvatarPath = $User->headimgurl;
        $Member->sCity = $User->city;
        $Member->sProvince = $User->province;
        $Member->sCountry = $User->country;
        $Member->sLanguage = $User->language;
        $Member->SexID = isset($User->sex) ? $User->sex : null;
        $Member->save();
        self::AddRedBag($Member->lID);
        return $Member;
    }

    /**
     * 判断用户是否渠道经销商
     * @return bool
     * @author ouyangyz <ouyangyanzhong@163.com>
     * @time 2018-8-16 15:15:31
     */
    public function getBWholesaleSeller()
    {
        $result = \Yii::$app->wholesale->find()->where(['SellerID' => $this->lID])->count();
        if ($result > 0) {
            return true;
        }
        return false;
    }

    /*
     *获取认证信息
     */
    public function getCert()
    {
        $supplier = Supplierapply::find()->where(['MemberID' => $this->lID])->one();
        if ($supplier) {
            return $supplier->StatusID;
        } else {
            $provider = Providerapply::find()->where(['MemberID' => $this->lID])->one();
            if ($provider) {
                return $supplier->StatusID;
            } else {
                return 0;
            }
        }

    }

    /**
     * 判断用户是否代理
     * @return bool
     * @author panlong
     * @time 2019年9月10日10:38:14
     */
    public function getBAgent()
    {
        $seller = Seller::findOne(['MemberID' => $this->lID]);
        if ($seller) {
            return true;
        }

        return false;
    }

    /**
     * 获取会员设定的价格
     */
    public function getViprice()
    {
        return $this->hasOne(MemberProduct::className(), ['MemberID' => 'lID']);
    }
}