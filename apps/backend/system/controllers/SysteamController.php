<?php

namespace myerm\backend\system\controllers;

use myerm\backend\common\controllers\ObjectController;
use myerm\backend\system\models\SysDep;
use myerm\backend\system\models\SysObjectOperator;
use myerm\backend\system\models\SysOrgOperator;
use myerm\backend\system\models\SysTeam;
use myerm\backend\system\models\SysTeamUser;
use myerm\backend\system\models\SysUser;

/**
 * 团队控制器
 * ============================================================================
 * 版权所有 2010-2017 三只象（厦门）网络科技有限公司，并保留所有权利。
 * 网站地址: http://www.myerm.cn
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和
 * 使用；不允许对程序代码以任何形式任何目的的再发布。
 * ============================================================================
 *
 * @author 陈鹭明 <lumingchen@qq.com>
 * @since 2016-1-18 11:49
 * @version v2.0
 */
class SysteamController extends ObjectController
{
    public function actionJs()
    {
        $arrData = [];
        return $this->renderPartial('js', $arrData);
    }

    public function getNewFooterAppend()
    {
        $arrData = [];

        $arrDep = SysDep::find()->select(['lID', 'sName'])->where(['bActive' => 1])->asArray()->all();
        $arrUser = SysUser::find()->select(['lID', 'sName', 'SysDepID'])->where(['bActive' => 1])->asArray()->all();

        foreach ($arrDep as $i => $dep) {
            foreach ($arrUser as $user) {
                if ($dep['lID'] == $user['SysDepID']) {
                    $arrDep[$i]['arrUser'][] = $user;
                }
            }
        }

        $arrData['arrDep'] = $arrDep;

        if ($_GET['ID']) {
            $arrData['arrTeamUser'] = SysTeamUser::find()->with('sysuser')->where(['SysTeamID' => $_GET['ID']])->all();
        }

        return $this->renderPartial('newfooter', $arrData);
    }

    public function afterObjectNewSave($sysObject, $SysTeamID)
    {
        foreach ($_POST['arrTeamUser'] as $SysUserID) {
            SysTeam::getDb()->createCommand()->insert('SysTeamUser',
                ['SysTeamID' => $SysTeamID, 'SysUserID' => $SysUserID])->execute();
        }

        return true;
    }

    public function afterObjectEditSave($sysObject, $SysTeamID)
    {
        SysTeamUser::deleteAll(['SysTeamID' => $SysTeamID]);
        foreach ($_POST['arrTeamUser'] as $SysUserID) {
            SysTeam::getDb()->createCommand()->insert('SysTeamUser',
                ['SysTeamID' => $SysTeamID, 'SysUserID' => $SysUserID])->execute();
        }

        return true;
    }

    public function getViewFooterAppend()
    {
        $arrData = [];

        $arrData['arrTeamUser'] = SysTeamUser::find()->with('sysuser')->where(['SysTeamID' => $_GET['ID']])->all();

        return $this->renderPartial('viewfooter', $arrData);
    }

    public function afterDel($arrObjectData)
    {
        foreach ($arrObjectData as $data) {
            SysTeamUser::deleteAll(['SysTeamID' => $data['lID']]);
        }

        return true;
    }

    public function getListButton()
    {
        $arrData = [];
        return $this->renderPartial('listbutton', $arrData);
    }

    public function actionEditoperator()
    {
        $arrData = [];

        $arrData['sysTeam'] = SysDep::findOne($_GET['ID']);
        $arrOperator = SysObjectOperator::find()->with('sysobject')->orderBy('sObjectName,lPos')->all();
        foreach ($arrOperator as $opera) {
            $arrData['arrOperator'][$opera->sObjectName][] = $opera;
            $arrData['arrModule'][$opera->sysobject->module->sName][$opera->sysobject->sObjectName] = $opera->sysobject->sName;
        }

        $arrOrgOperator = SysOrgOperator::findAll(['sOrgName' => 'System/SysTeam', 'ObjectID' => $_GET['ID']]);
        foreach ($arrOrgOperator as $opera) {
            $arrData['arrOrgOperator'][$opera->sObjectName][$opera->sOperator] = 1;
        }

        return $this->render('editoperator', $arrData);
    }

    public function actionEditoperatorsave()
    {
        SysOrgOperator::deleteAll(['sOrgName' => 'System/SysTeam', 'ObjectID' => $_GET['ID']]);
        foreach ($_POST['operatorSelected'] as $sObjectName => $arrOperator) {
            foreach ($arrOperator as $lPos => $sOperator) {
                $org = new SysOrgOperator();
                $org->sObjectName = $sObjectName;
                $org->sOrgName = 'System/SysTeam';
                $org->ObjectID = $_GET['ID'];
                $org->sOperator = $sOperator;
                $org->lPos = $lPos;
                $org->save();
            }
        }

        return $this->redirect(\Yii::$app->homeUrl . '/system/systeam/editoperator?ID=' . $_GET['ID']);
    }
}