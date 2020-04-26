<?php

namespace myerm\shop\backend\controllers;

use myerm\backend\common\controllers\ObjectController;
use myerm\backend\common\libs\File;
use myerm\backend\common\libs\NewID;
use myerm\common\components\Image;
use myerm\shop\common\models\GoldRechargeConfig;
use myerm\shop\common\models\MallConfig;


/**
 * 商城配置的控制器
 * ============================================================================
 * 版权所有 2010-2017 三只象（厦门）网络科技有限公司，并保留所有权利。
 * 网站地址: http://www.myerm.cn
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和
 * 使用；不允许对程序代码以任何形式任何目的的再发布。
 * ============================================================================
 * @author 陈鹭明  <lumingchen@qq.com>
 * @since 2017年9月19日 22:18:10
 * @version v1.0
 */
class MallconfigController extends ObjectController
{
    public function actionWechat()
    {
        $data = [];

        if (\Yii::$app->request->isPost) {
            MallConfig::setValue("sAppID", $_POST['sAppID']);
            MallConfig::setValue("sAppSecret", $_POST['sAppSecret']);
            MallConfig::setValue("sToken", $_POST['sToken']);
            MallConfig::setValue("sMerchantID", $_POST['sMerchantID']);
            MallConfig::setValue("sWXPayKey", $_POST['sWXPayKey']);

            MallConfig::updateCache();

            return $this->redirect("/shop/mallconfig/wechat");
        } else {
            $data['sAppID'] = MallConfig::getValueByKey("sAppID");
            $data['sAppSecret'] = MallConfig::getValueByKey("sAppSecret");
            $data['sToken'] = MallConfig::getValueByKey("sToken");
            $data['sMerchantID'] = MallConfig::getValueByKey("sMerchantID");
            $data['sWXPayKey'] = MallConfig::getValueByKey("sWXPayKey");
        }

        return $this->render('wechat', $data);
    }

    public function actionHomeconfig()
    {
        $data = [];

        if (\Yii::$app->request->isPost) {

            $arrScrollImage = ['lScrollSpeed' => $_POST['homeConfig']['scrollimage']['lScrollSpeed'], 'arrPic' => ''];

            asort($_POST['homeConfig']['scrollimage']['lPos']);
            foreach ($_POST['homeConfig']['scrollimage']['lPos'] as $i => $lPos) {
                if ($_POST['homeConfig']['scrollimage']['sPic'][$i]) {

                    if (substr($_POST['homeConfig']['scrollimage']['sPic'][$i], 0, 5) == 'data:') {
                        $arrFileInfo = File::parseImageFromBase64($_POST['homeConfig']['scrollimage']['sPic'][$i]);
                        $sFileName = NewID::make() . "." . $arrFileInfo[0];
                        $sPic = File::putContentToUploadDir($sFileName, $arrFileInfo[1]);
                    } else {
                        $sPic = $_POST['homeConfig']['scrollimage']['sPic'][$i];
                    }

                    $arrScrollImage['arrPic'][] = [
                        'sPic' => $sPic,
                        'lPos' => $lPos,
                        'sLink' => $_POST['homeConfig']['scrollimage']['sLink'][$i],
                    ];
                }
            }

            MallConfig::setValue("sScrollImageConfig", json_encode($arrScrollImage));


            $arrShortcut = [
                'bActive' => $_POST['homeConfig']['shortcut']['bActive'],
                'sBgPic' => $_POST['homeConfig']['shortcut']['sBgPic'],
                'arrPic' => ''
            ];

            if (substr($arrShortcut['sBgPic'], 0, 5) == 'data:') {
                $arrFileInfo = File::parseImageFromBase64($arrShortcut['sBgPic']);
                $sFileName = NewID::make() . "." . $arrFileInfo[0];
                $arrShortcut['sBgPic'] = File::putContentToUploadDir($sFileName, $arrFileInfo[1]);
            }

            asort($_POST['homeConfig']['shortcut']['lPos']);
            foreach ($_POST['homeConfig']['shortcut']['lPos'] as $i => $lPos) {
                if ($_POST['homeConfig']['shortcut']['sPic'][$i]) {
                    if (substr($_POST['homeConfig']['shortcut']['sPic'][$i], 0, 5) == 'data:') {
                        $arrFileInfo = File::parseImageFromBase64($_POST['homeConfig']['shortcut']['sPic'][$i]);
                        $sFileName = NewID::make() . "." . $arrFileInfo[0];
                        $sPic = File::putContentToUploadDir($sFileName, $arrFileInfo[1]);
                    } else {
                        $sPic = $_POST['homeConfig']['shortcut']['sPic'][$i];
                    }

                    $arrShortcut['arrPic'][] = [
                        'sPic' => $sPic,
                        'lPos' => $lPos,
                        'sLink' => $_POST['homeConfig']['shortcut']['sLink'][$i],
                        'sName' => $_POST['homeConfig']['shortcut']['sName'][$i],
                    ];
                }
            }
            MallConfig::setValue("sShortcutConfig", json_encode($arrShortcut));

            $arrWindow = [
                'bActive' => $_POST['homeConfig']['window']['bActive'],
                'arrPic' => ''
            ];
            foreach ($_POST['homeConfig']['window']['sPic'] as $i => $sPic) {
                if ($sPic) {
                    if (substr($sPic, 0, 5) == 'data:') {
                        $arrFileInfo = File::parseImageFromBase64($sPic);
                        $sFileName = NewID::make() . "." . $arrFileInfo[0];
                        $sPic = File::putContentToUploadDir($sFileName, $arrFileInfo[1]);
                    }

                    $arrWindow['arrPic'][] = [
                        'sPic' => $sPic,
                        'lPos' => $i,
                        'sLink' => $_POST['homeConfig']['window']['sLink'][$i],
                    ];
                }
            }
            MallConfig::setValue("sWindowConfig", json_encode($arrWindow));

            MallConfig::updateCache();

            return $this->redirect("/shop/mallconfig/homeconfig?save=yes&currtab=" . $_POST['currtab']);
        } else {
            $data['arrScrollImage'] = json_decode(MallConfig::getValueByKey("sScrollImageConfig"), true);
            if (!$data['arrScrollImage']['arrPic']) {
                $data['arrScrollImage']['arrPic'] = [];
                $data['arrScrollImage']['arrPic'][] = [];
            }

            $data['arrShortcut'] = json_decode(MallConfig::getValueByKey("sShortcutConfig"), true);
            if (!$data['arrShortcut']['arrPic']) {
                $data['arrShortcut']['arrPic'] = [];
                $data['arrShortcut']['arrPic'][] = [];
            }

            $data['arrWindow'] = json_decode(MallConfig::getValueByKey("sWindowConfig"), true);


        }

        return $this->render('homeconfig', $data);
    }

    public function actionBaseinfoconfig()
    {
        if (\Yii::$app->request->isPost) {

            $arrMallConfig = $_POST['MallConfig'];
            $arrMallConfig['sAbout'] = htmlspecialchars_decode($arrMallConfig['sAbout']);

            if (substr($arrMallConfig['sMallLogo'], 0, 5) == 'data:') {
                $arrFileInfo = File::parseImageFromBase64($arrMallConfig['sMallLogo']);
                $sFileName = NewID::make() . ".jpg";
                Image::resize($arrFileInfo[1], 320, File::getUploadDir() . "/" . $sFileName);
                $arrMallConfig['sMallLogo'] = str_ireplace(\Yii::$app->params['sUploadDir'] . "/", "",
                    File::getUploadDir() . "/" . $sFileName);
            } else {
                $arrMallConfig['sMallLogo'] = str_ireplace(\Yii::$app->params['sUploadUrl'] . "/", "",
                    $arrMallConfig['sMallLogo']);
            }

            foreach ($arrMallConfig as $sKey => $sValue) {
                MallConfig::setValue($sKey, $sValue);
            }

            MallConfig::updateCache();

            return $this->redirect("/shop/mallconfig/baseinfoconfig?save=yes");
        } else {
            $data = MallConfig::getAllConfig();

            return $this->render('baseinfoconfig', $data);
        }
    }

    public function actionSearchconfig()
    {
        if (\Yii::$app->request->isPost) {

            $arrHotWord = [];
            asort($_POST['hotword']['lPos']);
            if ($_POST['hotword']['lPos']) {
                foreach ($_POST['hotword']['lPos'] as $i => $lPos) {
                    if ($_POST['hotword']['sName'][$i]) {
                        $arrHotWord[] = ['sName' => $_POST['hotword']['sName'][$i], 'lPos' => $lPos];
                    }
                }
            }
            MallConfig::setValue("sHotSearchWord", json_encode($arrHotWord));

            MallConfig::setValue("sDefSearchWord", $_POST['sDefSearchWord']);
            MallConfig::updateCache();

            return $this->redirect("/shop/mallconfig/searchconfig?save=yes");
        } else {
            $data = MallConfig::getAllConfig();

            $data['arrHotWord'] = json_decode(MallConfig::getValueByKey("sHotSearchWord"), true);

            return $this->render('searchconfig', $data);
        }
    }

    public function actionHotsaleconfig()
    {
        if (\Yii::$app->request->isPost) {
            $data = [];
            asort($_POST['arrObjectData']['Shop/MallConfig']['lPos']);
            foreach ($_POST['arrObjectData']['Shop/MallConfig']['lPos'] as $i => $lPos) {
                $data[] = [
                    'ProductID' => $_POST['arrObjectData']['Shop/MallConfig']['sProductDetail'][$i],
                    'lPos' => $lPos
                ];
            }
            MallConfig::setValue("sHotSaleConfig", json_encode($data));
            MallConfig::updateCache();
            \Yii::$app->cache->delete("hotsale");

            return $this->redirect("/shop/mallconfig/hotsaleconfig?save=yes");
        } else {
            $data = MallConfig::getAllConfig();

            $data['arrHotSale'] = json_decode(MallConfig::getValueByKey("sHotSaleConfig"), true);

            return $this->render('hotsale', $data);
        }
    }

    public function actionOrderstatus()
    {
        if (\Yii::$app->request->isPost) {

            MallConfig::setValue("lOrderAutoCloseTime", $_POST['lOrderAutoCloseTime']);
            MallConfig::setValue("lAutoConfirmReceive", $_POST['lAutoConfirmReceive']);
            MallConfig::updateCache();

            return $this->redirect("/shop/mallconfig/orderstatus?save=yes");
        } else {
            $data = [];
            $data['lOrderAutoCloseTime'] = MallConfig::getValueByKey('lOrderAutoCloseTime');
            $data['sOrderCompleteDependOn'] = MallConfig::getValueByKey('sOrderCompleteDependOn');
            $data['lAutoConfirmReceive'] = MallConfig::getValueByKey('lAutoConfirmReceive');
            return $this->render('orderstatus', $data);
        }
    }

    public function actionAftersale()
    {
        if (\Yii::$app->request->isPost) {

            MallConfig::setValue("lRefundApplyTimeOut", $_POST['lRefundApplyTimeOut']);
            MallConfig::setValue("lRefundAgreeTimeOut", $_POST['lRefundAgreeTimeOut']);
            MallConfig::setValue("lRefundDenyApplyTimeOut", $_POST['lRefundDenyApplyTimeOut']);
            MallConfig::setValue("lRefundShipTimeOut", $_POST['lRefundShipTimeOut']);
            MallConfig::setValue("lRefundDenyReceiveTimeOut", $_POST['lRefundDenyReceiveTimeOut']);
            MallConfig::setValue("sAfterSaleNote", htmlspecialchars_decode($_POST['sAfterSaleNote']));
            MallConfig::updateCache();

            return $this->redirect("/shop/mallconfig/aftersale?save=yes");
        } else {
            $data = [];
            return $this->render('aftersale', $data);
        }
    }

    public function actionReglogin()
    {
        if (\Yii::$app->request->isPost) {

            MallConfig::setValue("bRequireMobileReg", $_POST['bRequireMobileReg']);
            MallConfig::setValue("bSupplierLoginBackend", $_POST['bSupplierLoginBackend']);
            MallConfig::setValue("sMemberContract", htmlspecialchars_decode($_POST['sMemberContract']));
            MallConfig::updateCache();

            return $this->redirect("/shop/mallconfig/reglogin?save=yes");
        } else {
            $data = MallConfig::getAllConfig();


            return $this->render('reglogin', $data);
        }
    }

    /**
     * 经销商入驻申请
     * @return string|\yii\web\Response
     */
    public function actionSellerjoin()
    {
        if (\Yii::$app->request->isPost) {

            MallConfig::setValue("sSellerJoinShareTitle", $_POST['sSellerJoinShareTitle']);
            MallConfig::setValue("sSellerJoinDesc", htmlspecialchars_decode($_POST['sSellerJoinDesc']));
            MallConfig::setValue("sSellerJoinContract", htmlspecialchars_decode($_POST['sSellerJoinContract']));
            MallConfig::updateCache();

            return $this->redirect("/shop/mallconfig/sellerjoin?save=yes");
        } else {
            $data = MallConfig::getAllConfig();


            return $this->render('sellerjoin', $data);
        }
    }

    /**
     * 经销商提现设置
     * @return string|\yii\web\Response
     */
    public function actionWithdraw()
    {
        if (\Yii::$app->request->isPost) {

            MallConfig::setValue("lWithdrawMin", $_POST['lWithdrawMin']);
            MallConfig::updateCache();

            return $this->redirect("/shop/mallconfig/withdraw?save=yes");
        } else {
            return $this->render('withdraw');
        }
    }

    /**
     * 金币充值设置
     */
    public function actionGoldrechargeconfig()
    {
        if (\Yii::$app->request->isPost) {

            GoldRechargeConfig::deleteAll();

            foreach ($_POST['full'] as $i => $v) {
                if ($v && $_POST['give'][$i]) {
                    $config = new GoldRechargeConfig();
                    $config->fFull = $v;
                    $config->fGive = $_POST['give'][$i];
                    $config->save();
                }
            }

            return $this->redirect("/shop/mallconfig/goldrechargeconfig?save=yes");
        } else {
            return $this->render('goldrechargeconfig', ['arrConfig' => GoldRechargeConfig::all()]);
        }
    }
}