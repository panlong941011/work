<?php

namespace myerm\backend\system\controllers;

use myerm\backend\common\controllers\ObjectController;
use myerm\backend\system\models\SysObjectOperator;
use myerm\backend\system\models\SysOrgOperator;
use myerm\backend\system\models\SysRole;
use myerm\backend\system\models\SysUser;

/**
 * 人员控制器
 * ============================================================================
 * 版权所有 2010-2017 三只象（厦门）网络科技有限公司，并保留所有权利。
 * 网站地址: http://www.myerm.cn
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和
 * 使用；不允许对程序代码以任何形式任何目的的再发布。
 * ============================================================================
 *
 * @author 陈鹭明 <lumingchen@qq.com>
 * @since 2016-1-13 22:11
 * @version v2.0
 */
class SysuserController extends ObjectController
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

    /**
     * 禁用
     */
    public function actionDisable()
    {
        $arrData = $this->listBatch();

        foreach ($arrData as $data) {
            SysUser::updateAll(['bActive' => 0], ['lID' => $data['lID']]);
        }

        return json_encode(['bSuccess' => true, 'sMsg' => '操作成功']);
    }

    /**
     * 启用
     */
    public function actionEnable()
    {
        $arrData = $this->listBatch();

        foreach ($arrData as $data) {
            SysUser::updateAll(['bActive' => 1], ['lID' => $data['lID']]);
        }

        return json_encode(['bSuccess' => true, 'sMsg' => '操作成功']);
    }

    /**
     * 重置密码
     */
    public function actionReset()
    {
        $arrData = $this->listBatch();

        foreach ($arrData as $data) {
            SysUser::updateAll(['sPassword' => '123456'], ['lID' => $data['lID']]);
        }

        return json_encode(['bSuccess' => true, 'sMsg' => '操作成功']);
    }

    public function actionEditoperator()
    {
        $arrData = [];

        $arrData['sysUser'] = SysUser::findOne($_GET['ID']);
        $arrOperator = SysObjectOperator::find()->with('sysobject')->orderBy('sObjectName,lPos')->all();
        foreach ($arrOperator as $opera) {
            $arrData['arrOperator'][$opera->sObjectName][] = $opera;
            $arrData['arrModule'][$opera->sysobject->module->sName][$opera->sysobject->sObjectName] = $opera->sysobject->sName;
        }

        $arrOrgOperator = SysOrgOperator::findAll(['sOrgName' => 'System/SysUser', 'ObjectID' => $_GET['ID']]);
        foreach ($arrOrgOperator as $opera) {
            $arrData['arrOrgOperator'][$opera->sObjectName][$opera->sOperator] = 1;
        }

        return $this->render('editoperator', $arrData);
    }

    public function actionEditoperatorsave()
    {
        SysOrgOperator::deleteAll(['sOrgName' => 'System/SysUser', 'ObjectID' => $_GET['ID']]);
        foreach ($_POST['operatorSelected'] as $sObjectName => $arrOperator) {
            foreach ($arrOperator as $lPos => $sOperator) {
                $org = new SysOrgOperator();
                $org->sObjectName = $sObjectName;
                $org->sOrgName = 'System/SysUser';
                $org->ObjectID = $_GET['ID'];
                $org->sOperator = $sOperator;
                $org->lPos = $lPos;
                $org->save();
            }
        }

        return $this->redirect(\Yii::$app->homeUrl . '/system/sysuser/editoperator?ID=' . $_GET['ID']);
    }

    protected function afterObjectEditSave($sysObject, $ID)
    {
        $this->afterObjectNewSave($sysObject, $ID);
        return true;
    }

    protected function afterObjectNewSave($sysObject, $ID)
    {
        SysRole::calcUserLevel();

        return true;
    }
}