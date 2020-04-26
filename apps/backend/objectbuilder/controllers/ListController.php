<?php

namespace myerm\backend\objectbuilder\controllers;

use myerm\backend\common\libs\NewID;
use myerm\backend\common\libs\PinYin;
use myerm\backend\system\models\SysDep;
use myerm\backend\system\models\SysField;
use myerm\backend\system\models\SysFieldEnum;
use myerm\backend\system\models\SysFilterDetail;
use myerm\backend\system\models\SysList;
use myerm\backend\system\models\SysListAdvancedSearch;
use myerm\backend\system\models\SysListSelect;
use myerm\backend\system\models\SysOrgObject;
use myerm\backend\system\models\SysRole;
use myerm\backend\system\models\SysUser;
use Yii;

/**
 * 对象管理器控制器-视图
 * ============================================================================
 * 版权所有 2010-2017 三只象（厦门）网络科技有限公司，并保留所有权利。
 * 网站地址: http://www.myerm.cn
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和
 * 使用；不允许对程序代码以任何形式任何目的的再发布。
 * ============================================================================
 * @author 陈鹭明  <lumingchen@qq.com>
 * @since 2015-11-19 14:22
 * @version v2.0
 */
class ListController extends Controller
{
    /**
     * 视图的列表
     * @author Mars <lumingchen@qq.com>
     * @since 2015-11-19 15:08
     */
    public function actionList()
    {
        $arrReturn = [];

        $sObjectName = Yii::$app->request->post('sObjectName');


        $arrReturn['arrList'] = SysList::find()->where("sObjectName='$sObjectName'")->asArray()->all();;


        $this->output($arrReturn);
    }

    /**
     * 新建视图
     * @author Mars <lumingchen@qq.com>
     * @since 2015-11-17 16:45
     */
    public function actionNew()
    {
        $arrReturn = [];

        $sObjectName = Yii::$app->request->post('sObjectName');

        $arrReturn['arrUser'] = SysUser::find()->select(['lID AS ID', 'sName'])->where("bActive='1'")->asArray()->all();
        $arrReturn['arrDep'] = SysDep::find()->select(['lID AS ID', 'sName'])->where("bActive='1'")->asArray()->all();
        $arrReturn['arrRole'] = SysRole::find()->select(['lID AS ID', 'sName'])->asArray()->all();
        $arrReturn['arrDataPowerField'] = SysField::find()->select([
            'ID',
            'sName',
            'sFieldAs'
        ])->where([
            'sObjectName' => $sObjectName,
            'sDataType' => 'ListTable',
            'sRefKey' => 'System/SysUser'
        ])->asArray()->all();
        $arrSearchFld = SysField::find()->select([
            'ID',
            'sName',
            'sFieldAs',
            'sDataType'
        ])->where("sObjectName='$sObjectName' AND sFieldAs NOT IN ('ID', 'lID')")->asArray()->all();
        $arrReturn['arrList'] = SysList::find()->where("sObjectName='$sObjectName'")->asArray()->all();;

        $pinyin = new PinYin();
        $arrReturn['arrSearchFld'] = [];
        foreach ($arrSearchFld as $fld) {
            $fld['sName'] = strtolower(substr($pinyin->getFullSpell(mb_convert_encoding($fld['sName'], "gb2312",
                    "utf-8"), ""), 0, 1)) . $fld['sName'];
            $arrReturn['arrSearchFld'][$fld['sName']] = $fld;
        }

        ksort($arrReturn['arrSearchFld']);


        $this->output($arrReturn);
    }

    /**
     * 新建视图保存
     * @author Mars <lumingchen@qq.com>
     * @since 2015-11-18 10:06
     */
    public function actionNewsave()
    {
        $ID = NewID::make();
        $sObjectName = Yii::$app->request->post('sObjectName');
        $sName = Yii::$app->request->post('sName');
        $sDataPowerField = Yii::$app->request->post('sDataPowerField');
        $sType = Yii::$app->request->post('sType');
        $CloneListID = Yii::$app->request->post('CloneListID');
        $sKey = Yii::$app->request->post('sKey') ? Yii::$app->request->post('sKey') : "Main." . str_replace("/", ".",
                $sObjectName) . "." . ucfirst($sType) . "." . $ID;
        $sDataRegion = Yii::$app->request->post('sDataRegion');
        $bDefault = Yii::$app->request->post('bDefault');
        $bCanPage = Yii::$app->request->post('bCanPage');
        $bCanSort = Yii::$app->request->post('bCanSort');
        $bSingle = Yii::$app->request->post('bSingle');
        $bCanBat = Yii::$app->request->post('bCanBat');
        $bActive = Yii::$app->request->post('bActive');
        $lPageLimit = Yii::$app->request->post('lPageLimit');
        $arrOrgObject = Yii::$app->request->post('OrgObject');
        $arrFilter = Yii::$app->request->post('Filter');
        $arrAdvField = Yii::$app->request->post('advanced');

        if (SysList::find()->where("sKey='$sKey'")->one()) {
            $this->lStatus = 0;
            $this->sErrMsg = $sKey . "已经存在。";
        } else {

            if ($bDefault) {
                SysList::updateAll(['bDefault' => 0], ['sObjectName' => $sObjectName]);
            }

            $sysList = new SysList();
            $sysList->ID = $ID;
            $sysList->sObjectName = $sObjectName;
            $sysList->sName = $sName;
            $sysList->sKey = $sKey;
            $sysList->sType = $sType;
            $sysList->sDataRegion = $sDataRegion;
            $sysList->sDataPowerField = $sDataPowerField;
            $sysList->bDefault = $bDefault;
            $sysList->bCanPage = $bCanPage;
            $sysList->bCanSort = $bCanSort;
            $sysList->bCanBat = $bCanBat;
            $sysList->bActive = $bActive;
            $sysList->bSingle = $bSingle;
            $sysList->lPageLimit = $lPageLimit;
            $sysList->save();

            if (!$CloneListID) {
                //默认把sName字段做为显示列
                $nameField = SysField::find()->select(['ID'])->where("sObjectName='$sObjectName' AND sFieldAs='sName'")->one();
                if ($nameField) {
                    $sysListSelect = new SysListSelect();
                    $sysListSelect->ID = NewID::make();
                    $sysListSelect->sObjectName = $sObjectName;
                    $sysListSelect->SysListID = $sysList->ID;
                    $sysListSelect->SysFieldID = $nameField->ID;
                    $sysListSelect->lPos = 0;
                    $sysListSelect->save();
                }
            } else {
                $arrListSelect = SysListSelect::findAll(['SysListID' => $CloneListID]);
                foreach ($arrListSelect as $listSelect) {
                    $sysListSelect = new SysListSelect();
                    $sysListSelect->ID = NewID::make();
                    $sysListSelect->sObjectName = $sObjectName;
                    $sysListSelect->SysListID = $sysList->ID;
                    $sysListSelect->SysFieldID = $listSelect->SysFieldID;
                    $sysListSelect->lPos = $listSelect->lPos;
                    $sysListSelect->save();
                }
            }

            if ($arrFilter) {
                $lPos = 0;
                foreach ($arrFilter as $FieldID => $filter) {
                    $sysFilterDetail = new SysFilterDetail();
                    $sysFilterDetail->ID = NewID::make();
                    $sysFilterDetail->sObjectName = $sObjectName;
                    $sysFilterDetail->SysListID = $sysList->ID;
                    $sysFilterDetail->SysFieldID = $FieldID;
                    $sysFilterDetail->sOpera = $filter['sOpera'];
                    $sysFilterDetail->sParament = $filter['sParament'];
                    $sysFilterDetail->sCDesc = $filter['sCDesc'];
                    $sysFilterDetail->lPos = $lPos;
                    $sysFilterDetail->save();

                    $lPos++;
                }
            }

            if ($arrOrgObject) {
                $sysOrgObject = new SysOrgObject();
                $sysOrgObject->ID = NewID::make();
                $sysOrgObject->sObjectName = "System/SysList";
                $sysOrgObject->ObjectID = $ID;

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

            if ($arrAdvField) {
                foreach ($arrAdvField as $i => $FieldID) {
                    $sysListAdvancedSearch = new SysListAdvancedSearch();
                    $sysListAdvancedSearch->ID = NewID::make();
                    $sysListAdvancedSearch->sObjectName = $sObjectName;
                    $sysListAdvancedSearch->SysListID = $sysList->ID;
                    $sysListAdvancedSearch->SysFieldID = $FieldID;
                    $sysListAdvancedSearch->lPos = $i;
                    $sysListAdvancedSearch->save();
                }
            }

        }

        $arrReturn = [];
        $this->output($arrReturn);
    }

    /**
     * 编辑视图
     * @author Mars <lumingchen@qq.com>
     * @since 2015-11-18 14:55
     */
    public function actionEdit()
    {
        $arrReturn = [];

        $sObjectName = Yii::$app->request->post('sObjectName');
        $ID = Yii::$app->request->post('ID');

        $sysList = SysList::findOne($ID);

        $arrReturn['arrDataPowerField'] = SysField::find()->select([
            'ID',
            'sName',
            'sFieldAs'
        ])->where([
            'sObjectName' => $sObjectName,
            'sDataType' => 'ListTable',
            'sRefKey' => 'System/SysUser'
        ])->asArray()->all();
        $arrReturn['arrSysFilter'] = SysFilterDetail::find()->where("SysListID='$ID'")->asArray()->all();
        $arrReturn['sysList'] = $sysList->toArray();
        $arrReturn['arrUser'] = SysUser::find()->select(['lID AS ID', 'sName'])->where("bActive='1'")->asArray()->all();
        $arrReturn['arrDep'] = SysDep::find()->select(['lID AS ID', 'sName'])->asArray()->all();
        $arrReturn['arrRole'] = SysRole::find()->select(['lID AS ID', 'sName'])->asArray()->all();
        $arrReturn['arrList'] = SysList::find()->where("sObjectName='$sObjectName' AND ID<>'$ID'")->asArray()->all();;
        $arrSearchFld = SysField::find()->select([
            'ID',
            'sName',
            'sFieldAs',
            'sDataType'
        ])->where("sObjectName='$sObjectName'")->orderBy("sName")->asArray()->all();


        $pinyin = new PinYin();
        $arrReturn['arrSearchFld'] = [];
        foreach ($arrSearchFld as $fld) {
            $fld['sName'] = strtolower(substr($pinyin->getFullSpell(mb_convert_encoding($fld['sName'], "gb2312",
                    "utf-8"), ""), 0, 1)) . $fld['sName'];
            $arrReturn['arrSearchFld'][$fld['sName']] = $fld;
        }

        ksort($arrReturn['arrSearchFld']);

        $arrReturn['arrAdvSearchFld'] = [];
        $arr = SysListAdvancedSearch::find()->where(['SysListID' => $ID])->with("sysField")->all();
        foreach ($arr as $a) {
            $arrReturn['arrAdvSearchFld'][$a->SysFieldID] = [$a->SysFieldID, $a->sysField->sName];
        }

        $arrReturn['arrAdvSearchField'] = [];
        $arr = SysListAdvancedSearch::find()->where(['SysListID' => $ID])->with("sysField")->all();
        foreach ($arr as $a) {
            $arrReturn['arrAdvSearchField'][] = [$a->SysFieldID, $a->sysField->sName];
        }


        $arrReturn['OrgObject']['SysRole'] = [];
        foreach ($sysList->roles as $role) {
            $arrReturn['OrgObject']['SysRole'][] = $role->ID;
        }

        $arrReturn['OrgObject']['SysTeam'] = [];
        foreach ($sysList->teams as $team) {
            $arrReturn['OrgObject']['SysTeam'][] = $team->ID;
        }

        $arrReturn['OrgObject']['SysDep'] = [];
        foreach ($sysList->deps as $dep) {
            $arrReturn['OrgObject']['SysDep'][] = $dep->ID;
        }

        $arrReturn['OrgObject']['SysUser'] = [];
        foreach ($sysList->users as $user) {
            $arrReturn['OrgObject']['SysUser'][] = $user->ID;
        }


        $this->output($arrReturn);
    }


    /**
     * 编辑视图保存
     * @author Mars <lumingchen@qq.com>
     * @since 2015-11-20 17:59
     */
    public function actionEditsave()
    {
        $ID = Yii::$app->request->post('ID');

        $sObjectName = Yii::$app->request->post('sObjectName');
        $sName = Yii::$app->request->post('sName');
        $sDataPowerField = Yii::$app->request->post('sDataPowerField');
        $sType = Yii::$app->request->post('sType');
        $sDataRegion = Yii::$app->request->post('sDataRegion');
        $CloneListID = Yii::$app->request->post('CloneListID');
        $bDefault = Yii::$app->request->post('bDefault');
        $bCanPage = Yii::$app->request->post('bCanPage');
        $bCanSort = Yii::$app->request->post('bCanSort');
        $bSingle = Yii::$app->request->post('bSingle');
        $bCanBat = Yii::$app->request->post('bCanBat');
        $bActive = Yii::$app->request->post('bActive');
        $lPageLimit = Yii::$app->request->post('lPageLimit');
        $arrOrgObject = Yii::$app->request->post('OrgObject');
        $arrFilter = Yii::$app->request->post('Filter');
        $arrAdvField = Yii::$app->request->post('advanced');
        $sysList = SysList::findOne($ID);

        if (!$sysList) {
            $this->lStatus = 0;
            $this->sErrMsg = "您编辑的视图不存在。";
        } else {

            if ($bDefault && !$sysList->bDefault) {
                SysList::updateAll(['bDefault' => 0], ['sObjectName' => $sObjectName]);
            }

            $sysList->sName = $sName;
            $sysList->sType = $sType;
            $sysList->sDataRegion = $sDataRegion;
            $sysList->sDataPowerField = $sDataPowerField;
            $sysList->bDefault = $bDefault;
            $sysList->bCanPage = $bCanPage;
            $sysList->bCanSort = $bCanSort;
            $sysList->bCanBat = $bCanBat;
            $sysList->bActive = $bActive;
            $sysList->bSingle = $bSingle;
            $sysList->lPageLimit = $lPageLimit;
            $sysList->save();

            SysFilterDetail::deleteAll("SysListID='" . $sysList->ID . "'");
            if ($arrFilter) {
                $lPos = 0;
                foreach ($arrFilter as $FieldID => $filter) {
                    $sysFilterDetail = new SysFilterDetail();
                    $sysFilterDetail->ID = NewID::make();
                    $sysFilterDetail->sObjectName = $sObjectName;
                    $sysFilterDetail->SysListID = $sysList->ID;
                    $sysFilterDetail->SysFieldID = $FieldID;
                    $sysFilterDetail->sOpera = $filter['sOpera'];
                    $sysFilterDetail->sParament = $filter['sParament'];
                    $sysFilterDetail->sCDesc = $filter['sCDesc'];
                    $sysFilterDetail->lPos = $lPos;
                    $sysFilterDetail->save();

                    $lPos++;
                }
            }

            SysOrgObject::deleteAll("sObjectName='System/SysList' AND ObjectID='" . $sysList->ID . "'");
            if ($arrOrgObject) {
                $sysOrgObject = new SysOrgObject();
                $sysOrgObject->ID = NewID::make();
                $sysOrgObject->sObjectName = "System/SysList";
                $sysOrgObject->ObjectID = $sysList->ID;

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

            SysListAdvancedSearch::deleteAll(['SysListID' => $sysList->ID]);
            if ($arrAdvField) {
                foreach ($arrAdvField as $i => $FieldID) {
                    $sysListAdvancedSearch = new SysListAdvancedSearch();
                    $sysListAdvancedSearch->ID = NewID::make();
                    $sysListAdvancedSearch->sObjectName = $sObjectName;
                    $sysListAdvancedSearch->SysListID = $sysList->ID;
                    $sysListAdvancedSearch->SysFieldID = $FieldID;
                    $sysListAdvancedSearch->lPos = $i;
                    $sysListAdvancedSearch->save();
                }
            }

            if ($CloneListID) {
                SysListSelect::deleteAll(['SysListID' => $sysList->ID]);
                $arrListSelect = SysListSelect::findAll(['SysListID' => $CloneListID]);
                foreach ($arrListSelect as $listSelect) {
                    $sysListSelect = new SysListSelect();
                    $sysListSelect->ID = NewID::make();
                    $sysListSelect->sObjectName = $sObjectName;
                    $sysListSelect->SysListID = $sysList->ID;
                    $sysListSelect->SysFieldID = $listSelect->SysFieldID;
                    $sysListSelect->lPos = $listSelect->lPos;
                    $sysListSelect->save();
                }
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
            SysList::deleteAll("ID='$ID'");
            SysListSelect::deleteAll("SysListID='$ID'");
            SysFilterDetail::deleteAll("SysListID='$ID'");
            SysOrgObject::deleteAll("sObjectName='$sObjectName' AND ObjectID='$ID'");
            SysListAdvancedSearch::deleteAll("SysListID='$ID'");
        }

        $this->output($arrReturn);
    }

    /**
     * 查询条件-列表型获取枚举值
     */
    public function actionOperlist()
    {
        $arrReturn = [];

        $FieldID = Yii::$app->request->post('FieldID');
        $sParam = Yii::$app->request->post('sParam');

        $sysField = SysField::findOne($FieldID);

        if ($sysField->sEnumTable == "System/SysFieldEnum") {
            if ($sysField->sEnumOption) {
                $arrReturn['arrListEnum'] = [];
                $arrLine = explode("\n", trim($sysField->sEnumOption));
                foreach ($arrLine as $sLine) {
                    $arrReturn['arrListEnum'][] = [
                        'ID' => substr($sLine, 0, strpos($sLine, '=')),
                        'sName' => substr($sLine, strpos($sLine, '=') + 1)
                    ];
                }
            } else {
                $arrReturn['arrListEnum'] = SysFieldEnum::find()->where("SysFieldID='" . $sysField->sRefKey . "'")->orderBy("lPos")->asArray()->all();
            }
        } else {
            $sDataSourceKey = "ds_" . $sysField->enumobject->DataSourceID;
            $arrReturn['arrListEnum'] = Yii::$app->$sDataSourceKey->createCommand("SELECT ID, sName FROM " . $sysField->object->sTable)->queryAll();
        }

        $this->output($arrReturn);
    }

    /**
     * 编辑显示列
     */
    public function actionEditcol()
    {
        $arrReturn = [];

        $ID = Yii::$app->request->post('ID');

        $sysList = SysList::findOne($ID);

        $arrReturn['arrColSelect'] = [];
        if ($sysList->cols) {
            foreach ($sysList->cols as $col) {
                $arrReturn['arrColSelect'][] = ['ID' => $col->SysFieldID, 'sName' => $col->field->sName];
            }
        }

        $arrReturn['arrColUnSelect'] = SysField::find()->select([
            'ID',
            'sName'
        ])->where("sObjectName='" . $sysList->sObjectName . "' AND ID NOT IN (SELECT SysFieldID FROM SysListSelect WHERE SysListID='$ID')")->asArray()->all();


        $arrReturn['arrOrderColSelect'] = [];
        if ($sysList->sOrderBy) {
            $arrOrderBy = explode(",", $sysList->sOrderBy);
            foreach ($arrOrderBy as $sOrderByField) {
                if (preg_match("/desc$/i", trim($sOrderByField))) {
                    $sOrderBy = "DESC";
                } else {
                    $sOrderBy = "ASC";
                }

                $sOrderByField = preg_replace("/(desc|asc|)$/i", "", trim($sOrderByField));

                preg_match("/\.(.*)/i", $sOrderByField, $m);

                $field = SysField::find()->select([
                    'ID',
                    'sName'
                ])->where("sObjectName='" . $sysList->sObjectName . "' AND sFieldAs='{$m[1]}'")->one();

                $arrReturn['arrOrderColSelect'][] = [
                    'ID' => $field->ID,
                    'sName' => $field->sName,
                    'sDirection' => $sOrderBy
                ];
            }
        }

        $arrReturn['arrOrderColUnSelect'] = SysField::find()->select([
            'ID',
            'sName'
        ])->where("sObjectName='" . $sysList->sObjectName . "'")->asArray()->all();

        $this->output($arrReturn);
    }

    /**
     * 编辑显示列保存
     */
    public function actionEditcolsave()
    {
        $arrReturn = [];

        $ID = Yii::$app->request->post('ID');
        $sSelItem = Yii::$app->request->post('sSelItem');
        $sOrderByField = Yii::$app->request->post('sOrderByField');
        $sOrderByDirection = Yii::$app->request->post('sOrderByDirection');

        $sysList = SysList::findOne($ID);

        SysListSelect::deleteAll("SysListID='$ID'");
        foreach (explode(";", $sSelItem) as $lPos => $FieldID) {
            $sysListSelect = new SysListSelect();
            $sysListSelect->ID = NewID::make();
            $sysListSelect->sObjectName = $sysList->sObjectName;
            $sysListSelect->SysListID = $ID;
            $sysListSelect->SysFieldID = $FieldID;
            $sysListSelect->lPos = $lPos;
            $sysListSelect->save();
        }

        $sysList->sOrderBy = null;
        if ($sOrderByField) {
            $sComm = "";
            $arrDirection = explode(";", $sOrderByDirection);
            foreach (explode(";", $sOrderByField) as $lPos => $FieldID) {
                $sysList->sOrderBy .= $sComm . $sysList->sysobject->sTable . "." . SysField::findOne($FieldID)->sFieldAs . " " . $arrDirection[$lPos];
                $sComm = ",";
            }
        }

        $sysList->save();

        $this->output($arrReturn);
    }

}