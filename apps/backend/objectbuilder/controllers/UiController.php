<?php

namespace myerm\backend\objectbuilder\controllers;

use myerm\backend\common\libs\NewID;
use myerm\backend\system\models\SysDep;
use myerm\backend\system\models\SysField;
use myerm\backend\system\models\SysOrgObject;
use myerm\backend\system\models\SysRole;
use myerm\backend\system\models\SysTeam;
use myerm\backend\system\models\SysUI;
use myerm\backend\system\models\SysUIFieldClass;
use myerm\backend\system\models\SysUIFieldClassField;
use myerm\backend\system\models\SysUIFieldClassRule;
use myerm\backend\system\models\SysUser;
use Yii;

/**
 * 对象管理器控制器-界面配置
 * ============================================================================
 * 版权所有 2010-2017 三只象（厦门）网络科技有限公司，并保留所有权利。
 * 网站地址: http://www.myerm.cn
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和
 * 使用；不允许对程序代码以任何形式任何目的的再发布。
 * ============================================================================
 * @author 陈鹭明  <lumingchen@qq.com>
 * @since 2015-11-15 15:01
 * @version v2.0
 */
class UiController extends Controller
{
    /**
     * 界面配置的列表
     * @author Mars <lumingchen@qq.com>
     * @since 2015-11-15 15:02
     */
    public function actionList()
    {
        $arrReturn = [];

        $sObjectName = Yii::$app->request->post('sObjectName');

        $arrSysUI = SysUI::find()->where("sObjectName='$sObjectName'")->all();
        foreach ($arrSysUI as $ui) {
            $data = $ui->toArray();

            $data['sFieldClassName'] = $sComm = "";
            foreach ($ui->fieldclass as $fc) {
                $data['sFieldClassName'] .= $sComm . $fc->sName;
                $sComm = ", ";
            }

            $data['sOrgObject'] = $sComm = "";
            foreach ($ui->roles as $role) {
                $data['sOrgObject'] .= $sComm . $role->sName;
                $sComm = ", ";
            }

            foreach ($ui->deps as $dep) {
                $data['sOrgObject'] .= $sComm . $dep->sName;
                $sComm = ", ";
            }

            foreach ($ui->users as $user) {
                $data['sOrgObject'] .= $sComm . $user->sName;
                $sComm = ", ";
            }

            foreach ($ui->teams as $team) {
                $data['sOrgObject'] .= $sComm . $team->sName;
                $sComm = ", ";
            }

            $arrReturn['arrSysUI'][] = $data;
        }

        $this->output($arrReturn);
    }

    /**
     * 新建界面
     */
    public function actionNew()
    {
        $arrReturn = [];

        $arrReturn['arrUser'] = SysUser::find()->select(['lID AS ID', 'sName'])->where("bActive='1'")->asArray()->all();
        $arrReturn['arrDep'] = SysDep::find()->select(['lID AS ID', 'sName'])->asArray()->all();
        $arrReturn['arrRole'] = SysRole::find()->select(['lID AS ID', 'sName'])->asArray()->all();
        $arrReturn['arrTeam'] = SysTeam::find()->select(['lID AS ID', 'sName'])->asArray()->all();
        $arrReturn['arrUI'] = SysUI::find()->where("sObjectName='".$_POST['sObjectName']."'")->asArray()->all();

        $this->output($arrReturn);
    }

    /**
     * 新建界面保存
     */
    public function actionNewsave()
    {
        $sObjectName = Yii::$app->request->post('sObjectName');
        $sName = Yii::$app->request->post('sName');
        $sInterface = Yii::$app->request->post('sInterface');
        $CloneUIID = Yii::$app->request->post('CloneUIID');
        $arrOrgObject = Yii::$app->request->post('OrgObject');

        $sysUI = new SysUI();
        $sysUI->ID = NewID::make();
        $sysUI->sObjectName = $sObjectName;
        $sysUI->sName = $sName;
        $sysUI->sInterface = $sInterface;
        $sysUI->save();

        if ($arrOrgObject) {
            $sysOrgObject = new SysOrgObject();
            $sysOrgObject->ID = NewID::make();
            $sysOrgObject->sObjectName = 'System/SysUI';
            $sysOrgObject->ObjectID = $sysUI->ID;

            if ($arrOrgObject['System/SysUser']) {
                $sysOrgObject->sUserID = ";" . implode(";", $arrOrgObject['System/SysUser']) . ";";
            }

            if ($arrOrgObject['System/SysRole']) {
                $sysOrgObject->sRoleID = ";" . implode(";", $arrOrgObject['System/SysRole']) . ";";
            }

            if ($arrOrgObject['System/SysDep']) {
                $sysOrgObject->sDepID = ";" . implode(";", $arrOrgObject['System/SysDep']) . ";";
            }

            if ($arrOrgObject['System/SysTeam']) {
                $sysOrgObject->sTeamID = ";" . implode(";", $arrOrgObject['System/SysTeam']) . ";";
            }

            $sysOrgObject->save();
        }

        if (!$CloneUIID) {
            $nameField = SysField::find()->select(['ID'])->where("sObjectName='$sObjectName' AND sFieldAs='sName'")->one();
            if ($nameField) {
                $sysUIFieldClass = new SysUIFieldClass();
                $sysUIFieldClass->ID = NewID::make();
                $sysUIFieldClass->sObjectName = $sObjectName;
                $sysUIFieldClass->SysUIID = $sysUI->ID;
                $sysUIFieldClass->sName = "基本信息";
                $sysUIFieldClass->bExpand = 1;
                $sysUIFieldClass->lPos = 0;
                $sysUIFieldClass->save();

                //名称字段做为基本信息的字段
                $sysUIFieldClassField = new SysUIFieldClassField();
                $sysUIFieldClassField->ID = NewID::make();
                $sysUIFieldClassField->SysUIID = $sysUI->ID;
                $sysUIFieldClassField->sObjectName = $sObjectName;
                $sysUIFieldClassField->SysUIFieldClassID = $sysUIFieldClass->ID;
                $sysUIFieldClassField->SysFieldID = $nameField->ID;
                $sysUIFieldClassField->lGroup = 1;
                $sysUIFieldClassField->lPos = 0;
                $sysUIFieldClassField->save();
            }
        } else {
            $arrUIFieldClass = SysUIFieldClass::find()->where(["SysUIID"=>$CloneUIID])->all();
            foreach ($arrUIFieldClass as $fieldClass) {
                $fieldClassClone = new SysUIFieldClass();
                $fieldClassClone->setAttributes($fieldClass->getAttributes(), false);
                $fieldClassClone->ID = NewID::make();
                $fieldClassClone->SysUIID = $sysUI->ID;
                $fieldClassClone->save();

                $arrUIFieldClassField = SysUIFieldClassField::find()->where(["SysUIFieldClassID"=>$fieldClass->ID])->all();
                foreach ($arrUIFieldClassField as $fieldClassField) {
                    $fieldClassFieldClone = new SysUIFieldClassField();
                    $fieldClassFieldClone->setAttributes($fieldClassField->getAttributes(), false);
                    $fieldClassFieldClone->ID = NewID::make();
                    $fieldClassFieldClone->SysUIID = $sysUI->ID;
                    $fieldClassFieldClone->SysUIFieldClassID = $fieldClassClone->ID;
                    $fieldClassFieldClone->save();
                }
            }
        }


        $arrReturn = [];
        $this->output($arrReturn);
    }

    /**
     * 编辑界面
     */
    public function actionEdit()
    {
        $sObjectName = Yii::$app->request->post('sObjectName');
        $ID = Yii::$app->request->post('ID');

        $arrReturn = [];

        $sysUI = SysUI::findOne($ID);

        $arrReturn['sysUI'] = $sysUI->toArray();

        $arrReturn['sysUI']['roles'] = [];
        foreach ($sysUI->roles as $role) {
            $arrReturn['sysUI']['roles'][] = $role->toArray();
        }

        $arrReturn['sysUI']['teams'] = [];
        foreach ($sysUI->teams as $team) {
            $arrReturn['sysUI']['teams'][] = $team->toArray();
        }

        $arrReturn['sysUI']['deps'] = [];
        foreach ($sysUI->deps as $dep) {
            $arrReturn['sysUI']['deps'][] = $dep->toArray();
        }

        $arrReturn['sysUI']['users'] = [];
        foreach ($sysUI->users as $user) {
            $arrReturn['sysUI']['users'][] = $user->toArray();
        }

        $arrReturn['arrUser'] = SysUser::find()->select(['lID AS ID', 'sName'])->where("bActive='1'")->asArray()->all();
        $arrReturn['arrDep'] = SysDep::find()->select([
            'lID AS ID',
            'sName'
        ])->where(['bActive' => 1])->asArray()->all();
        $arrReturn['arrRole'] = SysRole::find()->select(['lID AS ID', 'sName'])->asArray()->all();
        $arrReturn['arrTeam'] = SysTeam::find()->select([
            'lID AS ID',
            'sName'
        ])->where(['bActive' => 1])->asArray()->all();

        $arrReturn['arrUI'] = SysUI::find()->where("sObjectName='".$sObjectName."'")->asArray()->all();

        $this->output($arrReturn);
    }

    /**
     * 编辑界面保存
     */
    public function actionEditsave()
    {
        $arrReturn = [];

        $ID = Yii::$app->request->post('ID');
        $sName = Yii::$app->request->post('sName');
        $sInterface = Yii::$app->request->post('sInterface');
        $CloneUIID = Yii::$app->request->post('CloneUIID');
        $arrOrgObject = Yii::$app->request->post('OrgObject');

        $sysUI = SysUI::findOne($ID);
        $sysUI->sName = $sName;
        $sysUI->sInterface = $sInterface;
        $sysUI->save();

        SysOrgObject::deleteAll("sObjectName='System/SysUI' AND ObjectID='$ID'");

        if ($arrOrgObject) {
            $sysOrgObject = new SysOrgObject();
            $sysOrgObject->ID = NewID::make();
            $sysOrgObject->sObjectName = 'System/SysUI';
            $sysOrgObject->ObjectID = $sysUI->ID;

            if ($arrOrgObject['System/SysUser']) {
                $sysOrgObject->sUserID = ";" . implode(";", $arrOrgObject['System/SysUser']) . ";";
            }

            if ($arrOrgObject['System/SysRole']) {
                $sysOrgObject->sRoleID = ";" . implode(";", $arrOrgObject['System/SysRole']) . ";";
            }

            if ($arrOrgObject['System/SysDep']) {
                $sysOrgObject->sDepID = ";" . implode(";", $arrOrgObject['System/SysDep']) . ";";
            }

            if ($arrOrgObject['System/SysTeam']) {
                $sysOrgObject->sTeamID = ";" . implode(";", $arrOrgObject['System/SysTeam']) . ";";
            }

            $sysOrgObject->save();
        }

        if ($CloneUIID) {
            SysUIFieldClass::deleteAll(["SysUIID"=>$ID]);
            SysUIFieldClassField::deleteAll(["SysUIID"=>$ID]);

            $arrUIFieldClass = SysUIFieldClass::find()->where(["SysUIID"=>$CloneUIID])->all();
            foreach ($arrUIFieldClass as $fieldClass) {
                $fieldClassClone = new SysUIFieldClass();
                $fieldClassClone->setAttributes($fieldClass->getAttributes(), false);
                $fieldClassClone->ID = NewID::make();
                $fieldClassClone->SysUIID = $sysUI->ID;
                $fieldClassClone->save();

                $arrUIFieldClassField = SysUIFieldClassField::find()->where(["SysUIFieldClassID"=>$fieldClass->ID])->all();
                foreach ($arrUIFieldClassField as $fieldClassField) {
                    $fieldClassFieldClone = new SysUIFieldClassField();
                    $fieldClassFieldClone->setAttributes($fieldClassField->getAttributes(), false);
                    $fieldClassFieldClone->ID = NewID::make();
                    $fieldClassFieldClone->SysUIID = $sysUI->ID;
                    $fieldClassFieldClone->SysUIFieldClassID = $fieldClassClone->ID;
                    $fieldClassFieldClone->save();
                }
            }
        }


        $this->output($arrReturn);
    }

    /**
     * 删除界面保存
     */
    public function actionDel()
    {
        $arrReturn = [];

        $sObjectName = Yii::$app->request->post('sObjectName');
        $arrID = explode(";", Yii::$app->request->post('IDs'));
        foreach ($arrID as $ID) {
            SysUI::deleteAll("ID='$ID'");

            SysUIFieldClassRule::deleteAll("SysUIID='$ID'");

            SysUIFieldClassField::deleteAll("SysUIID='$ID'");

            SysUIFieldClass::deleteAll("SysUIID='$ID'");

            SysOrgObject::deleteAll("sObjectName='System/SysUI' AND ObjectID='$ID'");
        }

        $this->output($arrReturn);
    }
}