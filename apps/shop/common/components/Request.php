<?php

namespace myerm\shop\common\components;

use myerm\shop\common\models\MallConfig;
use yii\helpers\Url;

/**
 *  继承公共的Request类，提供更适用于ShopERM的请求类
 * ============================================================================
 * 版权所有 2010-2017 三只象（厦门）网络科技有限公司，并保留所有权利。
 * 网站地址: http://www.myerm.cn
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和
 * 使用；不允许对程序代码以任何形式任何目的的再发布。
 * ============================================================================
 * @author 陈鹭明  <lumingchen@qq.com>
 * @since 2017年9月4日 23:24:12
 * @version v2.0
 */
class Request extends \myerm\common\components\Request
{
    /**
     * 获取当前店铺的根URL
     * @return string
     */
    public function getShopUrl()
    {
        if (isset($_GET['sShopUrl'])) {
            return $_GET['sShopUrl'];
        } elseif (\Yii::$app->frontsession->sShopUrl) {
            return \Yii::$app->frontsession->sShopUrl;
        } elseif (\Yii::$app->frontsession->sellerShop) {
            return "shop".\Yii::$app->frontsession->sellerShop->lID;
        } else {
            return 'shop0';
        }
    }


    public function getShopID()
    {
        return str_ireplace("shop", "", $this->shopUrl);
    }

    /**
     * 获取当前店铺的根URL
     * @return string
     */
    public function getShopRootUrl()
    {
        return $this->rootUrl . "/" . $this->shopUrl;
    }

    /**
     * 获取上一页的URL，默认返回浏览器的上一页，如果不存在，返回商城的首页
     */
    public function getLastUrl()
    {
        if ($this->referrer) {

            //如果url不是商城内的链接，跳转到首页.
            if (!stristr($this->referrer, $this->rootUrl)) {
                return $this->mallHomeUrl;
            }

            return $this->referrer;
        } else {
            return $this->mallHomeUrl;
        }
    }

    /**
     * 获取商城首页
     */
    public function getMallHomeUrl()
    {
        return Url::toRoute([$this->shopUrl . '/home'], true);
    }

    /**
     * 获取商城的根URL
     * @return mixed|null|string
     */
    public function getRootUrl()
    {
        return \Yii::$app->request->hostInfo;
    }

    /**
     * 获取图片的根Url
     */
    public function getImgUrl()
    {
        return MallConfig::getValueByKey('sImgRootUrl');
    }

    /**
     * 获取JS、CSS的随机码
     */
    public function getSRandomKey()
    {
        return MallConfig::getValueByKey('sRandomKey');
    }

    /**
     * 获取商城的名称
     * @return mixed
     */
    public function getSMallName()
    {
        if (\Yii::$app->frontsession->urlSellerShop) {
            return \Yii::$app->frontsession->urlSellerShop->sName;
        } else {
            return MallConfig::getValueByKey("sMallName");
        }
    }
}