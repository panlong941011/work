<?php

namespace myerm\backend\system\controllers;

use myerm\backend\common\controllers\ObjectController;
use myerm\backend\system\models\SysField;

/**
 * 个人信息控制器
 * ============================================================================
 * 版权所有 2010-2017 三只象（厦门）网络科技有限公司，并保留所有权利。
 * 网站地址: http://www.myerm.cn
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和
 * 使用；不允许对程序代码以任何形式任何目的的再发布。
 * ============================================================================
 *
 * @author 陈鹭明 <lumingchen@qq.com>
 * @since 2016-1-19 10:54
 * @version v2.0
 */
class ProfileController extends ObjectController
{
    /**
     * 个人信息主页
     * @see \myerm\backend\common\controllers\ObjectController::actionHome()
     */
    public function actionHome()
    {
        $arrData = [];

        $field = SysField::findOne(['sObjectName' => 'System/SysUser', 'sFieldAs' => 'LanguageID']);
        $arrData['arrLanguage'] = parse_ini_string($field->sEnumOption);

        return $this->render('home', $arrData);
    }


    public function actionJs()
    {
        $arrData = [];
        return $this->renderPartial('js', $arrData);
    }

    public function actionSaveprofile()
    {
        foreach ($_POST['profile'] as $sField => $sValue) {
            \Yii::$app->backendsession->sysuser->{$sField} = $sValue;
        }
        \Yii::$app->backendsession->sysuser->save();

        return json_encode(['bSuccess' => true, 'sMsg' => \Yii::t('app', '个人信息保存成功')]);
    }

    public function actionSavepassword()
    {
        $arrData = [];

        if (\Yii::$app->backendsession->sysuser->sPassword != $_POST['sCurrPass']) {
            $arrData = ['bSuccess' => false, 'sMsg' => \Yii::t('app', '当前密码不正确')];
        } else {
            if ($_POST['sNewPass'] != $_POST['sNewPassConfirm']) {
                $arrData = ['bSuccess' => false, 'sMsg' => \Yii::t('app', '两次密码输入不一致')];
            } else {

                \Yii::$app->backendsession->sysuser->sPassword = $_POST['sNewPass'];
                \Yii::$app->backendsession->sysuser->save();

                $arrData = ['bSuccess' => true, 'sMsg' => \Yii::t('app', '修改密码成功')];
            }
        }

        return json_encode($arrData);
    }
}