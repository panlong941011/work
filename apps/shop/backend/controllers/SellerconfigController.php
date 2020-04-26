<?php

namespace myerm\shop\backend\controllers;


use myerm\backend\common\controllers\ObjectController;
use myerm\backend\common\libs\NewID;
use myerm\common\libs\File;
use myerm\shop\common\models\SellerConfig;


/**
 * 经销商配置
 * ============================================================================
 * 版权所有 2010-2017 三只象（厦门）网络科技有限公司，并保留所有权利。
 * 网站地址: http://www.myerm.cn
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和
 * 使用；不允许对程序代码以任何形式任何目的的再发布。
 * ============================================================================
 * @author 陈鹭明  <lumingchen@qq.com>
 * @since 2018年1月15日 14:59:22
 * @version v1.0
 */
class SellerconfigController extends ObjectController
{
    /**
     * 经销商推广二维码设置
     */
    public function actionQrcode()
    {
        if (\Yii::$app->request->isPost) {

            if (substr($_POST['sQrcode'], 0, 5) == 'data:') {
                $arrFileInfo = File::parseImageFromBase64($_POST['sQrcode']);
                $sFileName = NewID::make() . "." . $arrFileInfo[0];;
                $sQrcode = File::putContentToUploadDir($sFileName, $arrFileInfo[1]);
            } else {
                $sQrcode = str_ireplace(\Yii::$app->params['sUploadUrl'] . "/", "",
                    $_POST['sQrcode']);
            }

            SellerConfig::set('sQrcode', $sQrcode);

            return $this->redirect("/shop/sellerconfig/qrcode?save=yes");
        } else {
            return $this->render("qrcode", ['config' => SellerConfig::all()]);
        }
    }

}