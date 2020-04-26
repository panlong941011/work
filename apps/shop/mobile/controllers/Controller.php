<?php

namespace myerm\shop\mobile\controllers;


use myerm\shop\common\controllers\ShopERMController;
use yii\helpers\Url;

/**
 * 这是ShopERM的移动端总控制器，所有ShopERM的移动端控制器必须继承于它
 */
class Controller extends ShopERMController
{
    public function beforeAction($action)
    {
        if (parent::beforeAction($action)) {
            //在微信端下，任何界面都必须要登录
            if (\Yii::$app->params['isWeChat'] && !\Yii::$app->frontsession->bWXLogin && $action->id != "loginpost") {
                $this->redirect(Url::toRoute([
                    \Yii::$app->request->shopUrl . '/member/loginpost',
                    'sReturnUrl' => \Yii::$app->request->rootUrl . \Yii::$app->request->url
                ], true));
            }

            //判断当前用户是否经销商，如果是，则要跳转到他自己的链接
            if (\Yii::$app->frontsession->bLogin && isset($_GET['sShopUrl'])) {
                $sellerShop = \Yii::$app->sellershop->findByID(\Yii::$app->frontsession->MemberID);
                if ($sellerShop && $sellerShop->lID != \Yii::$app->request->shopID) {
                    $this->redirect(str_ireplace($_GET['sShopUrl'], "shop" . $sellerShop->lID,
                        \Yii::$app->request->url));
                }
            }

            //如果店铺的链接是不存在的，或者禁用，跳转成shop0
            if (\Yii::$app->request->shopUrl != 'shop0' && !\Yii::$app->frontsession->urlSeller) {
                $this->redirect(str_ireplace(\Yii::$app->request->shopUrl, "shop0", \Yii::$app->request->url));
            }
            if ($_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'] == 'yl.aiyolian.cn/') {
                $this->redirect('https://yl.aiyolian.cn/shop0/home');
            }

        } else {
            return false;
        }
        return true;
    }

    public function render($view, $params = [])
    {
        if ($this->sDevViewPath && is_file(\Yii::getAlias($this->sDevViewPath) . '/' . $view . ".php")) {
            $this->viewPath = \Yii::getAlias($this->sDevViewPath);
        }

        $content = $this->getView()->render($view, $params, $this);
        return $this->renderContent($content);
    }
}