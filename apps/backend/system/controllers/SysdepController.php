<?php

namespace myerm\backend\system\controllers;

use myerm\backend\common\controllers\ObjectController;
use myerm\backend\system\models\SysDep;
use myerm\backend\system\models\SysObjectOperator;
use myerm\backend\system\models\SysOrgOperator;
use myerm\backend\system\models\SysUser;

/**
 * 部门控制器
 * ============================================================================
 * 版权所有 2010-2017 三只象（厦门）网络科技有限公司，并保留所有权利。
 * 网站地址: http://www.myerm.cn
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和
 * 使用；不允许对程序代码以任何形式任何目的的再发布。
 * ============================================================================
 *
 * @author 陈鹭明 <lumingchen@qq.com>
 * @since 2016-1-13 10:30
 * @version v2.0
 */
class SysdepController extends ObjectController
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
            if (SysUser::findOne(['SysDepID' => $data[$this->sysObject->sIDField]])) {
                throw new \yii\base\UserException($data[$this->sysObject->sNameField] . '下还有人员，不能删除。');
            }
        }
    }

    public function actionViewchart()
    {
        $arrData = [];

        $arrLevel = [];
        $arrDep = SysDep::find()->asArray()->all();
        foreach ($arrDep as $dep) {
            $arrLevel[$dep['UpID']][] = $dep;
        }

        $arrData['sCharDiv'] = $this->recurseDep($arrLevel, '');

        return $this->renderPartial('viewchart', $arrData);
    }

    private function recurseDep(&$arrDep, $ParentID)
    {
        $charthtml = "<ul>";
        foreach ($arrDep[$ParentID] as $dep) {
            $charthtml .= "<li id='{$dep['lID']}'>";
            $charthtml .= "<span>" . $dep['sName'] . "</span>";

            if ($arrDep[$dep['lID']]) {
                $charthtml .= $this->recurseDep($arrDep, $dep['lID']);
            }

            $charthtml .= "</li>";
        }
        $charthtml .= "</ul>";

        return $charthtml;
    }

    public function actionEditoperator()
    {
        $arrData = [];

        $arrData['sysDep'] = SysDep::findOne($_GET['ID']);
        $arrOperator = SysObjectOperator::find()->with('sysobject')->orderBy('sObjectName,lPos')->all();
        foreach ($arrOperator as $opera) {
            $arrData['arrOperator'][$opera->sObjectName][] = $opera;
            $arrData['arrModule'][$opera->sysobject->module->sName][$opera->sysobject->sObjectName] = $opera->sysobject->sName;
        }

        $arrOrgOperator = SysOrgOperator::findAll(['sOrgName' => 'System/SysDep', 'ObjectID' => $_GET['ID']]);
        foreach ($arrOrgOperator as $opera) {
            $arrData['arrOrgOperator'][$opera->sObjectName][$opera->sOperator] = 1;
        }

        return $this->render('editoperator', $arrData);
    }

    public function actionEditoperatorsave()
    {
        SysOrgOperator::deleteAll(['sOrgName' => 'System/SysDep', 'ObjectID' => $_GET['ID']]);
        foreach ($_POST['operatorSelected'] as $sObjectName => $arrOperator) {
            foreach ($arrOperator as $lPos => $sOperator) {
                $org = new SysOrgOperator();
                $org->sObjectName = $sObjectName;
                $org->sOrgName = 'System/SysDep';
                $org->ObjectID = $_GET['ID'];
                $org->sOperator = $sOperator;
                $org->lPos = $lPos;
                $org->save();
            }
        }

        return $this->redirect(\Yii::$app->homeUrl . '/system/sysdep/editoperator?ID=' . $_GET['ID']);
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
        $arrDep = SysDep::find()->where("UpID IS NULL OR UpID=''")->all();
        foreach ($arrDep as $dep) {
            $dep->PathID = "/" . $dep->{$this->sysObject->sIDField} . "/";
            $dep->save();

            $this->updatePathID($dep->{$this->sysObject->sIDField}, $dep->PathID);
        }

        return true;
    }

    private function updatePathID($UpID, $PathID)
    {
        $arrDep = SysDep::find()->where("UpID='$UpID'")->all();
        foreach ($arrDep as $dep) {
            $dep->PathID = $PathID . $dep->{$this->sysObject->sIDField} . "/";
            $dep->save();

            $this->updatePathID($dep->{$this->sysObject->sIDField}, $dep->PathID);
        }

        return true;
    }
}