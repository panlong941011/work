<?php

namespace myerm\backend\system\controllers;

use myerm\backend\common\controllers\ObjectController;
use myerm\backend\common\libs\NewID;
use myerm\backend\common\libs\PinYin;
use myerm\backend\system\models\SysDep;
use myerm\backend\system\models\SysModule;
use myerm\backend\system\models\SysNavItem;
use myerm\backend\system\models\SysSolution;
use myerm\backend\system\models\SysSolutionNavHeading;
use myerm\backend\system\models\SysSolutionNavItem;
use myerm\backend\system\models\SysSolutionNavTab;
use myerm\backend\system\models\SysUser;
use yii\base\UserException;

/**
 * 工作台方案控制器
 * ============================================================================
 * 版权所有 2010-2017 三只象（厦门）网络科技有限公司，并保留所有权利。
 * 网站地址: http://www.myerm.cn
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和
 * 使用；不允许对程序代码以任何形式任何目的的再发布。
 * ============================================================================
 *
 * @author 陈鹭明 <lumingchen@qq.com>
 * @since 2016-1-12 16:25
 * @version v2.0
 */
class SyssolutionController extends ObjectController
{
    public function getListButton()
    {
        $arrData = [];
        return $this->renderPartial('listbutton', $arrData);
    }

    public function actionView()
    {
        $arrData = [];

        $arrData['solution'] = SysSolution::findOne($_GET['ID']);

        if (!$arrData['solution']) {
            throw new UserException("找不到工作台方案");
        }

        return $this->render('view', $arrData);
    }

    /**
     * {@inheritDoc}
     * @see \myerm\backend\common\controllers\ObjectController::afterDel()
     */
    public function afterDel($arrData)
    {
        foreach ($arrData as $data) {
            $ID = $data['ID'];
            SysSolutionNavTab::deleteAll(['SolutionID' => $ID]);
            SysSolutionNavHeading::deleteAll(['SolutionID' => $ID]);
            SysSolutionNavItem::deleteAll(['SolutionID' => $ID]);
        }

        if (!SysSolution::findOne(['bDefault' => 1])) {
            $solution = SysSolution::find()->one();
            if ($solution) {
                SysSolution::updateAll(['bDefault' => 1], ['ID' => $solution->ID]);
            }
        }

        return true;
    }

    public function actionJs()
    {
        $arrData = [];
        return $this->renderPartial('js', $arrData);
    }

    /**
     * 新建分类
     */
    public function actionNewnavheading()
    {
        $arrData = [];
        return $this->renderPartial('newnavheading', $arrData);
    }

    /**
     * 新建分类保存
     */
    public function actionNewnavheadingsave()
    {
        $SolutionID = $_GET['SolutionID'];
        $sName = $_POST['sName'];

        if (SysSolutionNavHeading::findOne(['SolutionID' => $SolutionID, 'sName' => $sName])) {
            return json_encode(['bSuccess' => false, 'sMsg' => $sName . "已存在"]);
        }

        $heading = new SysSolutionNavHeading();
        $heading->ID = NewID::make();
        $heading->sName = $sName;
        $heading->SolutionID = $SolutionID;
        $heading->lPos = SysSolutionNavHeading::find()->where(['SolutionID' => $SolutionID])->max('lPos') + 1;
        $heading->save();

        return json_encode(['bSuccess' => true, 'sMsg' => \Yii::t('app', '操作成功')]);
    }

    /**
     * 分类排序
     * @return string
     */
    public function actionSortnavheading()
    {
        $arrData = [];

        $arrData['arrHeading'] = SysSolutionNavHeading::find()->where(['SolutionID' => $_GET['SolutionID']])->orderBy('lPos')->all();

        return $this->renderPartial('sortnavheading', $arrData);
    }

    public function actionSortnavheadingsave()
    {
        foreach (explode(";", $_POST['sSelectItem']) as $lPos => $ItemID) {
            SysSolutionNavHeading::updateAll(['lPos' => $lPos], ['ID' => $ItemID]);
        }

        return json_encode(['bSuccess' => true, 'sMsg' => \Yii::t('app', '操作成功')]);
    }

    public function actionSortnavtab()
    {
        $arrData = [];

        $arrData['arrNavtab'] = SysSolutionNavTab::find()->where(['NavHeadingID' => $_GET['HeadingID']])->orderBy('lPos')->all();

        return $this->renderPartial('sortnavtab', $arrData);
    }

    public function actionSortnavtabsave()
    {
        foreach (explode(";", $_POST['sSelectItem']) as $lPos => $ItemID) {
            SysSolutionNavTab::updateAll(['lPos' => $lPos], ['ID' => $ItemID]);
        }

        return json_encode(['bSuccess' => true, 'sMsg' => \Yii::t('app', '操作成功')]);
    }

    public function actionDelnavheading()
    {
        SysSolutionNavTab::deleteAll(['NavHeadingID' => $_GET['ID']]);
        SysSolutionNavItem::deleteAll(['NavHeadingID' => $_GET['ID']]);
        SysSolutionNavHeading::deleteAll(['ID' => $_GET['ID']]);

        return $this->redirect(\Yii::$app->homeUrl . '/system/syssolution/view?ID=' . $_GET['SolutionID']);
    }

    public function actionNewnavtab()
    {
        $arrData = [];

        $pinyin = new PinYin();

        $heading = SysSolutionNavHeading::findOne($_GET['HeadingID']);
        $solution = SysSolution::findOne($heading->SolutionID);

        $arrData['arrNavItem'] = [];
        $arrNavItem = SysNavItem::find()->where(['bActive' => 1])->all();
        foreach ($arrNavItem as $navItem) {
            $sPinYin = $pinyin->getFullSpell(mb_convert_encoding($navItem->sName, "gb2312", "utf-8"), "");
            $arrData['arrNavItem'][explode("/",
                $navItem->sObjectName)[0]][strtoupper($sPinYin[0])][$sPinYin] = $navItem;
        }

        $arrData['arrModule'] = SysModule::find()->orderBy('sName')->all();
        $arrData['SolutionID'] = $heading->SolutionID;

        return $this->renderPartial('newnavtab', $arrData);
    }

    public function actionNewnavtabsave()
    {
        $tab = new SysSolutionNavTab();
        $tab->ID = NewID::make();
        $tab->sName = $_POST['sName'];
        $tab->SolutionID = $_GET['SolutionID'];
        $tab->NavHeadingID = $_GET['HeadingID'];
        $tab->lPos = SysSolutionNavTab::find()->where(['NavHeadingID' => $_GET['HeadingID']])->max('lPos') + 1;
        $tab->save();

        foreach (explode(";", $_POST['sSelectItem']) as $ItemID) {
            $tabItem = new SysSolutionNavItem();
            $tabItem->ID = NewID::make();
            $tabItem->sObjectName = SysNavItem::find()->select(['sObjectName'])->where(['ID' => $ItemID])->one()->sObjectName;
            $tabItem->SolutionID = $_GET['SolutionID'];
            $tabItem->NavHeadingID = $_GET['HeadingID'];
            $tabItem->NavTabID = $tab->ID;
            $tabItem->NavItemID = $ItemID;
            $tabItem->lPos = SysSolutionNavItem::find()->where(['NavTabID' => $tab->ID])->max('lPos') + 1;
            $tabItem->save();
        }

        return json_encode(['bSuccess' => true, 'sMsg' => \Yii::t('app', '操作成功')]);
    }

    public function actionEditnavtab()
    {
        $arrData = [];

        $pinyin = new PinYin();

        $tab = SysSolutionNavTab::findOne($_GET['ID']);

        $arrData['tab'] = $tab;

        $arrData['arrNavItem'] = [];
        $arrNavItem = SysNavItem::find()->where(['bActive' => 1])->all();
        foreach ($arrNavItem as $navItem) {
            $sPinYin = $pinyin->getFullSpell(mb_convert_encoding($navItem->sName, "gb2312", "utf-8"), "");
            $arrData['arrNavItem'][explode("/",
                $navItem->sObjectName)[0]][strtoupper($sPinYin[0])][$sPinYin] = $navItem;
        }


        $arrData['arrSelected'] = SysSolutionNavItem::find()->with('navitem')->where(['NavTabID' => $tab->ID])->indexBy('NavItemID')->all();
        $arrData['arrModule'] = SysModule::find()->orderBy('sName')->all();

        return $this->renderPartial('editnavtab', $arrData);
    }

    public function actionEditnavtabsave()
    {
        $tab = SysSolutionNavTab::findOne($_GET['ID']);
        $tab->sName = $_POST['sName'];
        $tab->save();

        SysSolutionNavItem::deleteAll(['NavTabID' => $tab->ID]);
        foreach (explode(";", $_POST['sSelectItem']) as $ItemID) {
            $tabItem = new SysSolutionNavItem();
            $tabItem->ID = NewID::make();
            $tabItem->sObjectName = SysNavItem::find()->select(['sObjectName'])->where(['ID' => $ItemID])->one()->sObjectName;
            $tabItem->SolutionID = $tab->SolutionID;
            $tabItem->NavHeadingID = $tab->NavHeadingID;
            $tabItem->NavTabID = $tab->ID;
            $tabItem->NavItemID = $ItemID;
            $tabItem->lPos = SysSolutionNavItem::find()->where(['NavTabID' => $tab->ID])->max('lPos') + 1;
            $tabItem->save();
        }

        return json_encode(['bSuccess' => true, 'sMsg' => \Yii::t('app', '操作成功')]);
    }

    public function actionDeltab()
    {
        foreach ($_POST['tabSelected'] as $TabID) {
            SysSolutionNavItem::deleteAll(['NavTabID' => $TabID]);
            SysSolutionNavTab::deleteAll(['ID' => $TabID]);
        }

        return $this->redirect(\Yii::$app->homeUrl . '/system/syssolution/view?ID=' . $_GET['ID']);
    }

    public function actionApply()
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
        $arrData['arrSelected'] = SysUser::find()->select(['lID', 'sName'])->where([
            'SysSolutionID' => $_GET['ID'],
            'bActive' => 1
        ])->asArray()->indexBy('lID')->all();

        return $this->renderPartial('apply', $arrData);
    }

    public function actionApplysave()
    {
        foreach (explode(";", $_POST['sSelectItem']) as $ItemID) {
            SysUser::updateAll(['SysSolutionID' => $_GET['SolutionID']], ['lID' => $ItemID]);
        }

        return json_encode(['bSuccess' => true, 'sMsg' => \Yii::t('app', '操作成功')]);
    }

    protected function newObjectFieldValidate($field, $value)
    {
        $check = parent::newObjectFieldValidate($field, $value);
        if ($check['bSuccess']) {

            if ($field->sFieldAs == 'sName') {
                $solution = SysSolution::findOne(['sName' => $value]);
                if ($solution) {
                    return ['bSuccess' => false, 'sFieldAs' => $field->sFieldAs, 'sMsg' => "您新建的【" . $value . "】已经存在"];
                }
            }

            return $check;
        } else {
            return $check;
        }
    }

    protected function beforeObjectNewSave($sysObject, $data)
    {
        $data = parent::beforeObjectNewSave($sysObject, $data);

        if ($data['bDefault'] == 1) {
            SysSolution::updateAll(['bDefault' => 0]);
        }

        return $data;
    }

    /**
     * {@inheritDoc}
     * @see \myerm\backend\common\controllers\ObjectController::beforeObjectEditSave()
     */
    protected function beforeObjectEditSave($sysObject, $ID, $data)
    {
        $data = parent::beforeObjectEditSave($sysObject, $ID, $data);

        if ($data['bDefault'] == 1) {
            SysSolution::updateAll(['bDefault' => 0], "ID<>'$ID' AND bDefault='1'");
        }

        return $data;
    }
}