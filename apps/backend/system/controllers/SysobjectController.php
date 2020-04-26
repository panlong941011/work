<?php

namespace myerm\backend\system\controllers;

use myerm\backend\common\controllers\ObjectController;
use myerm\backend\common\libs\NewID;
use myerm\backend\system\models\SysAutoShare;
use myerm\backend\system\models\SysDep;
use myerm\backend\system\models\SysObject;
use myerm\backend\system\models\SysObjectOperator;
use myerm\backend\system\models\SysOrgOperator;
use myerm\backend\system\models\SysRole;
use myerm\backend\system\models\SysTeam;
use myerm\backend\system\models\SysUser;

/**
 * 对象管理控制器
 * ============================================================================
 * 版权所有 2010-2017 三只象（厦门）网络科技有限公司，并保留所有权利。
 * 网站地址: http://www.myerm.cn
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和
 * 使用；不允许对程序代码以任何形式任何目的的再发布。
 * ============================================================================
 *
 * @author 陈鹭明 <lumingchen@qq.com>
 * @since 2016-1-14 17:10
 * @version v2.0
 */
class SysobjectController extends ObjectController
{
    public function getListButton()
    {
        $arrData = [];
        return $this->renderPartial('listbutton', $arrData);
    }

    public function actionJs()
    {
        $arrData = [];
        return $this->renderPartial('js', $arrData);
    }

    public function actionEditoperator()
    {
        $arrData = [];

        $arrData['sysObject'] = SysObject::findOne($_GET['sObjectName']);
        $arrData['arrOperator'] = SysObjectOperator::find()->where(['sObjectName' => $_GET['sObjectName']])->orderBy('lPos')->all();

        $arrOrgOperator = SysOrgOperator::findAll(['sObjectName' => $_GET['sObjectName']]);
        foreach ($arrOrgOperator as $opera) {
            $arrData['arrOrgOperator'][$opera->sOrgName][$opera->sOperator][$opera->ObjectID] = 1;
        }

        $arrData['arrUser'] = SysUser::find()->select([
            'lID AS ID',
            'sName',
            'SysDepID'
        ])->where("bActive='1'")->asArray()->all();
        $arrData['arrDep'] = SysDep::find()->select(['lID AS ID', 'sName'])->where("bActive='1'")->asArray()->all();
        $arrData['arrRole'] = SysRole::find()->select([
            'lID AS ID',
            'sName',
            'PathID'
        ])->asArray()->orderBy('PathID')->all();
        $arrData['arrTeam'] = SysTeam::find()->select(['lID AS ID', 'sName'])->where("bActive='1'")->asArray()->all();

        return $this->render('editoperator', $arrData);
    }

    public function actionEditoperatorsave()
    {
        SysOrgOperator::deleteAll(['sObjectName' => $_GET['sObjectName']]);
        foreach ($_POST['operatorSelected'] as $sOrgName => $arrOperator) {
            foreach ($arrOperator as $sOperator => $arrObjectID) {
                foreach ($arrObjectID as $lPos => $ObjectID) {
                    $org = new SysOrgOperator();
                    $org->sObjectName = $_GET['sObjectName'];
                    $org->sOrgName = $sOrgName;
                    $org->ObjectID = $ObjectID;
                    $org->sOperator = $sOperator;
                    $org->lPos = $lPos;
                    $org->save();
                }
            }
        }

        return $this->redirect(\Yii::$app->homeUrl . '/system/sysobject/editoperator?sObjectName=' . $_GET['sObjectName']);
    }

    public function actionAutoshare()
    {
        $arrData = [];

        $arrData['sysObject'] = SysObject::findOne($_GET['sObjectName']);
        $arrData['arrAutoShare'] = SysAutoShare::findAll(['sObjectName' => $_GET['sObjectName']]);


        return $this->render('autoshare', $arrData);
    }

    public function actionNewautoshare()
    {
        $arrData = [];

        $arrData['sysObject'] = SysObject::findOne($_GET['sObjectName']);


        return $this->render('newautoshare', $arrData);
    }

    public function actionNewautosharesave()
    {
        $autoShare = new SysAutoShare();
        $autoShare->ID = NewID::make();
        $autoShare->sObjectName = $_POST['sObjectName'];

        if ($_POST['source'] == 'dep') {
            $autoShare->FromSysDepID = $_POST['sourcedepid'];
        } elseif ($_POST['source'] == 'role') {
            $autoShare->FromSysRoleID = $_POST['sourceroleid'];
        } elseif ($_POST['source'] == 'team') {
            $autoShare->FromSysTeamID = $_POST['sourceteamid'];
        }

        if ($_POST['target'] == 'dep') {
            $autoShare->ToSysDepID = $_POST['targetdepid'];
        } elseif ($_POST['target'] == 'role') {
            $autoShare->ToSysRoleID = $_POST['targetroleid'];
        } elseif ($_POST['target'] == 'team') {
            $autoShare->ToSysTeamID = $_POST['targetteamid'];
        }

        $autoShare->bFromInclude = $_POST['sourceinclude'];
        $autoShare->bToInclude = $_POST['targetinclude'];
        $autoShare->sToken = $_POST['stoken'];
        $autoShare->save();

        return $this->redirect('./autoshare?sObjectName=' . $_POST['sObjectName']);
    }

    public function actionEditautoshare()
    {
        $arrData = [];

        $arrData['sysObject'] = SysObject::findOne($_GET['sObjectName']);
        $arrData['autoshare'] = SysAutoShare::findOne($_GET['ID']);

        return $this->render('newautoshare', $arrData);
    }

    public function actionEditautosharesave()
    {
        $autoShare = SysAutoShare::findOne($_POST['ID']);

        $autoShare->FromSysDepID = null;
        $autoShare->FromSysRoleID = null;
        $autoShare->FromSysTeamID = null;

        if ($_POST['source'] == 'dep') {
            $autoShare->FromSysDepID = $_POST['sourcedepid'];
        } elseif ($_POST['source'] == 'role') {
            $autoShare->FromSysRoleID = $_POST['sourceroleid'];
        } elseif ($_POST['source'] == 'team') {
            $autoShare->FromSysTeamID = $_POST['sourceteamid'];
        }

        $autoShare->ToSysDepID = null;
        $autoShare->ToSysRoleID = null;
        $autoShare->ToSysTeamID = null;

        if ($_POST['target'] == 'dep') {
            $autoShare->ToSysDepID = $_POST['targetdepid'];
        } elseif ($_POST['target'] == 'role') {
            $autoShare->ToSysRoleID = $_POST['targetroleid'];
        } elseif ($_POST['target'] == 'team') {
            $autoShare->ToSysTeamID = $_POST['targetteamid'];
        }

        $autoShare->bFromInclude = $_POST['sourceinclude'];
        $autoShare->bToInclude = $_POST['targetinclude'];
        $autoShare->sToken = $_POST['stoken'];
        $autoShare->save();

        return $this->redirect('./autoshare?sObjectName=' . $_POST['sObjectName']);
    }

    public function actionDel()
    {
        foreach ($_POST['selected'] as $ID) {
            SysAutoShare::deleteAll(['ID' => $ID]);
        }

        return $this->redirect('./autoshare?sObjectName=' . $_GET['sObjectName']);
    }
}