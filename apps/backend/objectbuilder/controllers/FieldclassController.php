<?php

namespace myerm\backend\objectbuilder\controllers;

use myerm\backend\common\libs\NewID;
use myerm\backend\system\models\SysField;
use myerm\backend\system\models\SysOrgObject;
use myerm\backend\system\models\SysUI;
use myerm\backend\system\models\SysUIFieldClass;
use myerm\backend\system\models\SysUIFieldClassField;
use Yii;

/**
 * 对象管理器控制器-信息块
 * ============================================================================
 * 版权所有 2010-2017 三只象（厦门）网络科技有限公司，并保留所有权利。
 * 网站地址: http://www.myerm.cn
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和
 * 使用；不允许对程序代码以任何形式任何目的的再发布。
 * ============================================================================
 * @author 陈鹭明  <lumingchen@qq.com>
 * @since 2015-11-17 15:24
 * @version v2.0
 */
class FieldclassController extends Controller
{
    /**
     * 信息块的列表
     * @author Mars <lumingchen@qq.com>
     * @since 2015-11-15 15:02
     */
    public function actionList()
    {
        $arrReturn = [];

        $sObjectName = Yii::$app->request->post('sObjectName');
        $SysUIID = Yii::$app->request->post('SysUIID');

        $arrReturn['arrFieldClass'] = [];
        $arrFieldClass = SysUIFieldClass::find()->with('fields')->where("SysUIID='$SysUIID'")->orderBy("lPos")->all();
        foreach ($arrFieldClass as $fc) {
            $data = $fc->toArray();

            $data['sFieldName'] = $sComm = "";
            foreach ($fc->fields as $f) {
                $data['sFieldName'] .= $sComm . $f->field->sName;
                $sComm = ", ";
            }

            $arrReturn['arrFieldClass'][] = $data;
        }


        $sysUI = SysUI::findOne($SysUIID);
        $arrReturn['sysUI'] = $sysUI->toArray();

        $this->output($arrReturn);
    }

    /**
     * 新建信息块
     * @author Mars <lumingchen@qq.com>
     * @since 2015-11-17 16:45
     */
    public function actionNew()
    {
        $arrReturn = [];

        $sObjectName = Yii::$app->request->post('sObjectName');
        $SysUIID = Yii::$app->request->post('SysUIID');

        $sysUI = SysUI::findOne($SysUIID);

        $sSQLAdd = " AND (1<0";
        if (stristr($sysUI->sInterface, "new")) {
            $sSQLAdd .= " OR sFieldClassType LIKE '%new%'";
        }

        if (stristr($sysUI->sInterface, "edit")) {
            $sSQLAdd .= " OR sFieldClassType LIKE '%edit%'";
        }

        if (stristr($sysUI->sInterface, "view")) {
            $sSQLAdd .= " OR sFieldClassType LIKE '%view%'";
        }

        $sSQLAdd .= ")";

        $sSQLAdd .= " AND ID NOT IN (SELECT SysFieldID FROM SysUIFieldClassField WHERE SysUIID='$SysUIID')";

        $arrReturn['arrUnSelect'] = SysField::find()->select([
            'ID',
            'sName'
        ])->where("sObjectName='$sObjectName' $sSQLAdd")->asArray()->all();
        $arrReturn['arrSelect'] = [];

        $this->output($arrReturn);
    }

    /**
     * 新建信息块保存
     * @author Mars <lumingchen@qq.com>
     * @since 2015-11-18 10:06
     */
    public function actionNewsave()
    {
        $sObjectName = Yii::$app->request->post('sObjectName');
        $SysUIID = Yii::$app->request->post('SysUIID');
        $sName = Yii::$app->request->post('sName');
        $bExpand = Yii::$app->request->post('bExpand');
        $arrFieldClass1 = explode(";", Yii::$app->request->post('sFieldClass1'));
        $arrFieldClass2 = explode(";", Yii::$app->request->post('sFieldClass2'));
        $arrFieldClass3 = explode(";", Yii::$app->request->post('sFieldClass3'));

        $sysUIFieldClass = new SysUIFieldClass();
        $sysUIFieldClass->ID = NewID::make();
        $sysUIFieldClass->sObjectName = $sObjectName;
        $sysUIFieldClass->SysUIID = $SysUIID;
        $sysUIFieldClass->sName = $sName;
        $sysUIFieldClass->bExpand = $bExpand;
        $sysUIFieldClass->lPos = SysUIFieldClass::find()->where("SysUIID='$SysUIID'")->max('lPos') + 1;
        $sysUIFieldClass->save();

        foreach ($arrFieldClass1 as $lPos => $SysFieldID) {
            if ($SysFieldID) {
                $sysUIFieldClassField = new SysUIFieldClassField();
                $sysUIFieldClassField->ID = NewID::make();
                $sysUIFieldClassField->sObjectName = $sObjectName;
                $sysUIFieldClassField->SysUIID = $SysUIID;
                $sysUIFieldClassField->SysUIFieldClassID = $sysUIFieldClass->ID;
                $sysUIFieldClassField->SysFieldID = $SysFieldID;
                $sysUIFieldClassField->lGroup = 1;
                $sysUIFieldClassField->lPos = $lPos;
                $sysUIFieldClassField->save();
            }
        }

        foreach ($arrFieldClass2 as $lPos => $SysFieldID) {
            if ($SysFieldID) {
                $sysUIFieldClassField = new SysUIFieldClassField();
                $sysUIFieldClassField->ID = NewID::make();
                $sysUIFieldClassField->sObjectName = $sObjectName;
                $sysUIFieldClassField->SysUIID = $SysUIID;
                $sysUIFieldClassField->SysUIFieldClassID = $sysUIFieldClass->ID;
                $sysUIFieldClassField->SysFieldID = $SysFieldID;
                $sysUIFieldClassField->lGroup = 2;
                $sysUIFieldClassField->lPos = $lPos;
                $sysUIFieldClassField->save();
            }
        }

        foreach ($arrFieldClass3 as $lPos => $SysFieldID) {
            if ($SysFieldID) {
                $sysUIFieldClassField = new SysUIFieldClassField();
                $sysUIFieldClassField->ID = NewID::make();
                $sysUIFieldClassField->sObjectName = $sObjectName;
                $sysUIFieldClassField->SysUIID = $SysUIID;
                $sysUIFieldClassField->SysUIFieldClassID = $sysUIFieldClass->ID;
                $sysUIFieldClassField->SysFieldID = $SysFieldID;
                $sysUIFieldClassField->lGroup = 3;
                $sysUIFieldClassField->lPos = $lPos;
                $sysUIFieldClassField->save();
            }
        }

        $arrReturn = [];
        $this->output($arrReturn);
    }

    /**
     * 编辑信息块
     * @author Mars <lumingchen@qq.com>
     * @since 2015-11-18 14:55
     */
    public function actionEdit()
    {
        $arrReturn = [];

        $sObjectName = Yii::$app->request->post('sObjectName');
        $SysUIID = Yii::$app->request->post('SysUIID');
        $ID = Yii::$app->request->post('ID');

        $sysUIFieldClass = SysUIFieldClass::findOne($ID);
        $sysUI = SysUI::findOne($sysUIFieldClass->SysUIID);


        $sSQLAdd = " AND (1<0";
        if (stristr($sysUI->sInterface, "new")) {
            $sSQLAdd .= " OR sFieldClassType LIKE '%new%'";
        }

        if (stristr($sysUI->sInterface, "edit")) {
            $sSQLAdd .= " OR sFieldClassType LIKE '%edit%'";
        }

        if (stristr($sysUI->sInterface, "view")) {
            $sSQLAdd .= " OR sFieldClassType LIKE '%view%'";
        }

        $sSQLAdd .= ")";

        $sSQLAdd .= " AND ID NOT IN (SELECT SysFieldID FROM SysUIFieldClassField WHERE SysUIID='$SysUIID')";

        $arrReturn['arrUnSelect'] = SysField::find()->select([
            'ID',
            'sName'
        ])->where("sObjectName='$sObjectName' $sSQLAdd")->asArray()->all();
        $arrReturn['arrSelect'] = SysField::find()->select([
            'ID',
            'sName'
        ])->where("ID IN (SELECT SysFieldID FROM SysUIFieldClassField WHERE SysUIFieldClassID='$ID')")->asArray()->all();

        $arrReturn['arrSelect'] = [
            'grp_1' => [],
            'grp_2' => [],
            'grp_3' => [],
        ];
        $arrFieldSelect = SysUIFieldClassField::find()->with('field')->where("SysUIFieldClassID='$ID'")->orderBy("lGroup, lPos")->all();
        foreach ($arrFieldSelect as $field) {
            $arrReturn['arrSelect']['grp_' . $field->lGroup][] = [
                'ID' => $field->SysFieldID,
                'sName' => $field->field->sName
            ];
        }

        $arrReturn['fieldclass'] = $sysUIFieldClass->toArray();

        $this->output($arrReturn);
    }


    /**
     * 编辑信息块保存
     * @author Mars <lumingchen@qq.com>
     * @since 2015-11-18 15:59
     */
    public function actionEditsave()
    {
        $sObjectName = Yii::$app->request->post('sObjectName');
        $ID = Yii::$app->request->post('ID');
        $sName = Yii::$app->request->post('sName');
        $bExpand = Yii::$app->request->post('bExpand');
        $arrFieldClass1 = explode(";", Yii::$app->request->post('sFieldClass1'));
        $arrFieldClass2 = explode(";", Yii::$app->request->post('sFieldClass2'));
        $arrFieldClass3 = explode(";", Yii::$app->request->post('sFieldClass3'));

        $sysUIFieldClass = SysUIFieldClass::findOne($ID);
        $sysUIFieldClass->sName = $sName;
        $sysUIFieldClass->bExpand = $bExpand;
        $sysUIFieldClass->save();

        SysUIFieldClassField::deleteAll("SysUIFieldClassID='" . $ID . "'");

        foreach ($arrFieldClass1 as $lPos => $SysFieldID) {
            if ($SysFieldID) {
                $sysUIFieldClassField = new SysUIFieldClassField();
                $sysUIFieldClassField->ID = NewID::make();
                $sysUIFieldClassField->sObjectName = $sObjectName;
                $sysUIFieldClassField->SysUIID = $sysUIFieldClass->SysUIID;
                $sysUIFieldClassField->SysUIFieldClassID = $sysUIFieldClass->ID;
                $sysUIFieldClassField->SysFieldID = $SysFieldID;
                $sysUIFieldClassField->lGroup = 1;
                $sysUIFieldClassField->lPos = $lPos;
                $sysUIFieldClassField->save();
            }
        }

        foreach ($arrFieldClass2 as $lPos => $SysFieldID) {
            if ($SysFieldID) {
                $sysUIFieldClassField = new SysUIFieldClassField();
                $sysUIFieldClassField->ID = NewID::make();
                $sysUIFieldClassField->sObjectName = $sObjectName;
                $sysUIFieldClassField->SysUIID = $sysUIFieldClass->SysUIID;
                $sysUIFieldClassField->SysUIFieldClassID = $sysUIFieldClass->ID;
                $sysUIFieldClassField->SysFieldID = $SysFieldID;
                $sysUIFieldClassField->lGroup = 2;
                $sysUIFieldClassField->lPos = $lPos;
                $sysUIFieldClassField->save();
            }
        }

        foreach ($arrFieldClass3 as $lPos => $SysFieldID) {
            if ($SysFieldID) {
                $sysUIFieldClassField = new SysUIFieldClassField();
                $sysUIFieldClassField->ID = NewID::make();
                $sysUIFieldClassField->sObjectName = $sObjectName;
                $sysUIFieldClassField->SysUIID = $sysUIFieldClass->SysUIID;
                $sysUIFieldClassField->SysUIFieldClassID = $sysUIFieldClass->ID;
                $sysUIFieldClassField->SysFieldID = $SysFieldID;
                $sysUIFieldClassField->lGroup = 3;
                $sysUIFieldClassField->lPos = $lPos;
                $sysUIFieldClassField->save();
            }
        }

        $arrReturn = [];
        $this->output($arrReturn);
    }


    /**
     * 删除界面配置保存
     */
    public function actionDel()
    {
        $arrReturn = [];

        $sObjectName = Yii::$app->request->post('sObjectName');

        $arrID = explode(";", Yii::$app->request->post('IDs'));
        foreach ($arrID as $ID) {
            SysUIFieldClassField::deleteAll("SysUIFieldClassID='$ID'");

            SysUIFieldClass::deleteAll("ID='$ID'");

            SysOrgObject::deleteAll("sObjectName='$sObjectName' AND ObjectID='$ID'");
        }

        $this->output($arrReturn);
    }
}