<?php

namespace myerm\backend\system\controllers;

use myerm\backend\common\controllers\ObjectController;
use myerm\backend\system\models\SysObjectOperator;
use myerm\backend\system\models\SysOrgOperator;
use myerm\backend\system\models\SysRole;
use myerm\backend\system\models\SysUser;

/**
 * 角色控制器
 * ============================================================================
 * 版权所有 2010-2017 三只象（厦门）网络科技有限公司，并保留所有权利。
 * 网站地址: http://www.myerm.cn
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和
 * 使用；不允许对程序代码以任何形式任何目的的再发布。
 * ============================================================================
 *
 * @author 陈鹭明 <lumingchen@qq.com>
 * @since 2016-1-13 15:30
 * @version v2.0
 */
class SysroleController extends ObjectController
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


    public function beforeDel($arrData)
    {
        foreach ($arrData as $data) {
            if (SysUser::findOne(['SysRoleID' => $data[$this->sysObject->sIDField]])) {
                throw new \yii\base\UserException($data[$this->sysObject->sNameField] . '下还有人员，不能删除。');
            }
        }
    }

    public function actionViewchart()
    {
        $arrData = [];

        $arrLevel = [];
        $arrRole = SysRole::find()->asArray()->all();
        foreach ($arrRole as $role) {
            $arrLevel[$role['UpID']][] = $role;
        }

        $arrData['sCharDiv'] = $this->recurseRole($arrLevel, '');

        return $this->renderPartial('viewchart', $arrData);
    }

    private function recurseRole(&$arrRole, $ParentID)
    {
        $charthtml = "<ul>";
        foreach ($arrRole[$ParentID] as $role) {
            $charthtml .= "<li id='{$role['lID']}'>";
            $charthtml .= "<span>" . $role['sName'] . "</span>";

            if ($arrRole[$role['lID']]) {
                $charthtml .= $this->recurseRole($arrRole, $role['lID']);
            }

            $charthtml .= "</li>";
        }
        $charthtml .= "</ul>";

        return $charthtml;
    }

    public function actionEditoperator()
    {
        $arrData = [];

        $arrData['sysRole'] = SysRole::findOne($_GET['ID']);
        $arrOperator = SysObjectOperator::find()->with('sysobject')->orderBy('sObjectName,lPos')->all();
        foreach ($arrOperator as $opera) {
            $arrData['arrOperator'][$opera->sObjectName][] = $opera;
            $arrData['arrModule'][$opera->sysobject->module->sName][$opera->sysobject->sObjectName] = $opera->sysobject->sName;
        }

        $arrOrgOperator = SysOrgOperator::findAll(['sOrgName' => 'System/SysRole', 'ObjectID' => $_GET['ID']]);
        foreach ($arrOrgOperator as $opera) {
            $arrData['arrOrgOperator'][$opera->sObjectName][$opera->sOperator] = 1;
        }

        return $this->render('editoperator', $arrData);
    }

    public function actionEditoperatorsave()
    {
        SysOrgOperator::deleteAll(['sOrgName' => 'System/SysRole', 'ObjectID' => $_GET['ID']]);
        foreach ($_POST['operatorSelected'] as $sObjectName => $arrOperator) {
            foreach ($arrOperator as $lPos => $sOperator) {
                $org = new SysOrgOperator();
                $org->sObjectName = $sObjectName;
                $org->sOrgName = 'System/SysRole';
                $org->ObjectID = $_GET['ID'];
                $org->sOperator = $sOperator;
                $org->lPos = $lPos;
                $org->save();
            }
        }

        return $this->redirect(\Yii::$app->homeUrl . '/system/sysrole/editoperator?ID=' . $_GET['ID']);
    }

    /**
     * {@inheritDoc}
     * @see \myerm\backend\common\controllers\ObjectController::afterObjectEditSave()
     */
    protected function afterObjectEditSave($sysObject, $ID)
    {
        $this->afterObjectNewSave($sysObject, $ID);
        return true;
    }

    /**
     *
     * {@inheritDoc}
     * @see \myerm\backend\common\controllers\ObjectController::afterObjectNewSave()
     */
    protected function afterObjectNewSave($sysObject, $ID)
    {
        $arrRole = SysRole::find()->where("UpID IS NULL OR UpID=''")->all();
        foreach ($arrRole as $role) {
            $role->PathID = "/" . $role->{$this->sysObject->sIDField} . "/";
            $role->save();

            $this->updatePathID($role->{$this->sysObject->sIDField}, $role->PathID);
        }

        SysRole::calcUserLevel();

        return true;
    }

    private function updatePathID($UpID, $PathID)
    {
        $arrRole = SysRole::find()->where("UpID='$UpID'")->all();
        foreach ($arrRole as $role) {
            $role->PathID = $PathID . $role->{$this->sysObject->sIDField} . "/";
            $role->save();

            $this->updatePathID($role->{$this->sysObject->sIDField}, $role->PathID);
        }

        return true;
    }
}