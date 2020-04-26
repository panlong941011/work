<?php

namespace myerm\backend\system\models;

use myerm\backend\common\libs\StrTool;
use myerm\backend\common\libs\SystemTime;
use myerm\backend\common\models\ObjectModel;
use Yii;

/**
 * 系统对象模型-视图模型
 * ============================================================================
 * 版权所有 2010-2017 三只象（厦门）网络科技有限公司，并保留所有权利。
 * 网站地址: http://www.myerm.cn
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和
 * 使用；不允许对程序代码以任何形式任何目的的再发布。
 * ============================================================================
 * @author 陈鹭明  <lumingchen@qq.com>
 * @since 2015-11-5 10:15
 * @version v2.0
 */
class SysList extends \myerm\backend\system\models\Model
{
    public static function getAutoShareToken($SysUserID, $sObjectName)
    {
        $sysUser = SysUser::findOne($SysUserID);

        $arrToken = [];
        $arrAutoShare = SysAutoShare::find()
            ->with('fromdep', 'fromrole', 'fromteam', 'todep', 'torole', 'toteam')
            ->where(['sObjectName' => $sObjectName])
            ->all();
        foreach ($arrAutoShare as $autoshare) {
            //当前登陆人是否满足规则
            $bExist = false;

            if ($autoshare->ToSysTeamID) {//共享人在团队里
                if (SysTeamUser::findOne(['SysTeamID' => $autoshare->ToSysTeamID, 'SysUserID' => $SysUserID])) {
                    $bExist = true;
                }
            } elseif ($autoshare->ToSysDepID) {//共享人在部门里
                if ($autoshare->bToInclude && strstr($sysUser->sysdep->PathID, $autoshare->todep->PathID)) {//包含下级
                    $bExist = true;
                } elseif (!$autoshare->bToInclude && $autoshare->ToSysDepID == $sysUser->SysDepID) {//不包含下级
                    $bExist = true;
                }
            } elseif ($autoshare->ToSysRoleID) {//共享人在角色里
                if ($autoshare->bToInclude && strstr($sysUser->sysrole->PathID, $autoshare->torole->PathID)) {//包含下级
                    $bExist = true;
                } elseif (!$autoshare->bToInclude && $autoshare->ToSysRoleID == $sysUser->SysRoleID) {//不包含下级
                    $bExist = true;
                }
            } elseif ($autoshare->ToSysUserID == $SysUserID) {//共享人 是人员
                $bExist = true;
            } else {
                continue;
            }

            if ($bExist) {
                $arrToken[] = $autoshare->sToken;
            }
        }

        foreach ($arrToken as $sToken) {
            if ($sToken == 'view,edit,del,ref') {
                return 'view,edit,del,ref';
            }
        }

        foreach ($arrToken as $sToken) {
            if ($sToken == 'view,edit,ref') {
                return 'view,edit,ref';
            }
        }

        foreach ($arrToken as $sToken) {
            if ($sToken == 'view') {
                return 'view';
            }
        }

        foreach ($arrToken as $sToken) {
            if ($sToken == 'ref') {
                return 'ref';
            }
        }

        return '';
    }

    /**
     * 获取显示列
     */
    public function getCols()
    {
        return $this->hasMany(SysListSelect::className(), ["SysListID" => "ID"])->with(['field'])->orderBy('lPos');
    }

    /**
     * 获取筛选列
     */
    public function getFilters()
    {
        return $this->hasMany(SysFilterDetail::className(), ["SysListID" => "ID"])->with(['field'])->orderBy('lPos');
    }

    /**
     * 获取高级搜索列
     */
    public function getAdvancedsearchfield()
    {
        return $this->hasMany(SysListAdvancedSearch::className(),
            ["SysListID" => "ID"])->with(['field'])->orderBy('lPos');
    }

    /**
     * 获取视图的数据
     * @param array $arrConfig 配置
     */
    public function getListdata($arrConfig)
    {
        /**
         * 数据范围
         * 共享给我的：sharetome,我共享的：sharetoother,我的：my,待处理：pending,已审核：checked,带有数据权限的：datapower
         */
        $sDataRegion = $arrConfig['sDataRegion'];
        if (!$sDataRegion) {
            $sDataRegion = $this->sDataRegion;
        }

        /**
         * 排序字段
         */
        $sOrderBy = $arrConfig['sOrderBy'];
        if (!$sOrderBy) {
            $sOrderBy = $this->sOrderBy;
        }

        /**
         * 是否可分页
         */
        $bCanPage = isset($arrConfig['bCanPage']) ? $arrConfig['bCanPage'] : false;
        if ($bCanPage === false) {
            $bCanPage = $this->bCanPage;
        }

        /**
         * 是否可排序
         */
        $bCanSort = isset($arrConfig['bCanSort']) ? $arrConfig['bCanSort'] : false;
        if ($bCanSort === false) {
            $bCanSort = $this->bCanSort;
        }

        /**
         * 是否可批处理
         */
        $bCanBat = isset($arrConfig['bCanBat']) ? $arrConfig['bCanBat'] : false;
        if ($bCanBat === false) {
            $bCanBat = $this->bCanBat;
        }

        /**
         * 是否单选，如果否，就是多选。无论单选、多选，前提都是$bCanBat为true
         */
        $bSingle = isset($arrConfig['bSingle']) ? $arrConfig['bSingle'] : false;
        if ($bSingle === false) {
            $bSingle = $this->bSingle;
        }

        /**
         * 如果$bCanPage为true，$lPageLimit代表每页多少条记录。如果为0或者$bCanPage为false，表示取出全部的数据。
         */
        $lPageLimit = isset($arrConfig['lPageLimit']) ? intval($arrConfig['lPageLimit']) : -1;
        if ($lPageLimit == -1) {
            $lPageLimit = $this->lPageLimit ? $this->lPageLimit : 20;
        }

        /**
         * 当前页码
         * @var int
         */
        $lPage = isset($arrConfig['lPage']) ? intval($arrConfig['lPage']) : 1;

        //如果不能分页，关闭分页的功能
        if (!$bCanPage) {
            $lPageLimit = -1;
            $lPage = -1;
        }

        /**
         * 处理数据范围
         */
        //带数据权限的
        if ($sDataRegion == "datapower") {
            $dataPowerField = SysField::find()->select([
                'ID',
                'sLinkField'
            ])->where(['sObjectName' => $this->sObjectName, 'sFieldAs' => $this->sDataPowerField])->one();

            if ($dataPowerField) {
                $arrDownID = [Yii::$app->backendsession->sysuser->{$dataPowerField->sLinkField}];
                $arrDownline = Yii::$app->ds_db->createCommand("SELECT SysUserLevel.SysUserID AS lID, SysUser.ID
                                                                    FROM SysUserLevel 
                                                                    INNER JOIN SysUser ON SysUser.lID=SysUserLevel.SysUserID 
                                                                    WHERE SysUserLevel.UpSysUserID='" . Yii::$app->backendsession->SysUserID . "'")->queryAll();
                foreach ($arrDownline as $d) {
                    $arrDownID[] = $d[$dataPowerField->sLinkField];
                }

                //通过自动共享给我的
                if ($dataPowerField) {
                    $arrShareUserID = self::getAutoShareUsers(Yii::$app->backendsession->SysUserID, $this->sObjectName,
                        'view');
                    if ($arrShareUserID) {
                        if ($dataPowerField->sLinkField != 'lID') {
                            $arrShareUser = SysUser::find()->select($dataPowerField->sLinkField)->where(['lID' => $arrShareUserID])->all();
                            $arrShareUserID = [];
                            foreach ($arrShareUser as $sysUser) {
                                $arrShareUserID[] = $sysUser->{$dataPowerField->sLinkField};
                            }
                        }
                    }

                    $arrDownID = array_merge($arrDownID, $arrShareUserID);
                }

                $sFltSQL = $this->sDataPowerField . " IN (" . implode(",", $arrDownID) . ")";
            }
        } elseif ($sDataRegion == "my") { //我的
            $newUserIDField = SysField::find()->select([
                'ID',
                'sLinkField'
            ])->where(['sObjectName' => $this->sObjectName, 'sFieldAs' => 'NewUserID'])->one();
            if ($newUserIDField) {
                if ($newUserIDField->sLinkField == 'lID') {
                    $sFltSQL = "NewUserID=" . Yii::$app->backendsession->SysUserID;
                } else {
                    $sFltSQL = "NewUserID=" . Yii::$app->backendsession->sysuser->{$newUserIDField->sLinkField};
                }
            }
        } elseif ($sDataRegion == "sharetome") { //共享给我的

            $arrShareObjectID = [];

            //通过人员共享给我
            $arrShareObject = SysManualShare::find()
                ->select(['ObjectID'])
                ->where(['sObjectName' => $this->sObjectName, 'ToSysUserID' => Yii::$app->backendsession->SysUserID])
                ->all();
            foreach ($arrShareObject as $shareObject) {
                $arrShareObjectID[] = $shareObject->ObjectID;
            }

            //通过角色共享给我
            $arrShareObject = SysManualShare::find()
                ->select(['ObjectID'])
                ->where(['sObjectName' => $this->sObjectName, 'ToSysRoleID' => Yii::$app->backendsession->SysRoleID])
                ->all();
            foreach ($arrShareObject as $shareObject) {
                $arrShareObjectID[] = $shareObject->ObjectID;
            }

            //通过部门共享给我
            $arrShareObject = SysManualShare::find()
                ->select(['ObjectID'])
                ->where(['sObjectName' => $this->sObjectName, 'ToSysDepID' => Yii::$app->backendsession->SysDepID])
                ->all();
            foreach ($arrShareObject as $shareObject) {
                $arrShareObjectID[] = $shareObject->ObjectID;
            }

            //通过团队共享给我
            $arrTeamID = array_keys(SysTeamUser::find()->select(['SysTeamID'])->where(['SysUserID' => Yii::$app->backendsession->SysDepID])->indexBy('SysTeamID')->all());
            if ($arrTeamID) {
                $arrShareObject = SysManualShare::find()
                    ->select(['ObjectID'])
                    ->where(['sObjectName' => $this->sObjectName, 'ToSysTeamID' => $arrTeamID])
                    ->all();
                foreach ($arrShareObject as $shareObject) {
                    $arrShareObjectID[] = $shareObject->ObjectID;
                }
            }

            if ($arrShareObjectID) {
                $sFltSQL = $this->sysobject->sIDField . " IN ('" . implode("','", $arrShareObjectID) . "')";
            }

            //通过自动共享给我的
            $dataPowerField = SysField::find()->select([
                'ID',
                'sLinkField'
            ])->where(['sObjectName' => $this->sObjectName, 'sFieldAs' => $this->sDataPowerField])->one();
            if ($dataPowerField) {
                $arrShareUserID = self::getAutoShareUsers(Yii::$app->backendsession->SysUserID, $this->sObjectName,
                    'view');
                if ($arrShareUserID) {
                    if ($dataPowerField->sLinkField != 'lID') {
                        $arrShareUser = SysUser::find()->select($dataPowerField->sLinkField)->where(['lID' => $arrShareUserID])->all();
                        $arrShareUserID = [];
                        foreach ($arrShareUser as $sysUser) {
                            $arrShareUserID[] = $sysUser->{$dataPowerField->sLinkField};
                        }
                    }

                    if ($sFltSQL == "") {
                        $sFltSQL = $this->sDataPowerField . " IN (" . implode(",", $arrShareUserID) . ")";
                    } else {
                        $sFltSQL .= " OR " . $this->sDataPowerField . " IN (" . implode(",", $arrShareUserID) . ")";
                    }
                }
            }

            if ($sFltSQL == "") {
                $sFltSQL = "0>1";
            }

        } elseif ($sDataRegion == "sharetoother") { //我共享的
            //通过手动共享给我的
            $arrShareObjectID = [0];
            $arrShareObject = SysManualShare::find()
                ->select(['ObjectID'])
                ->where(['sObjectName' => $this->sObjectName, 'FromSysUserID' => Yii::$app->backendsession->SysUserID])
                ->all();
            foreach ($arrShareObject as $shareObject) {
                $arrShareObjectID[] = $shareObject->ObjectID;
            }

            $sFltSQL = $this->sysobject->sIDField . " IN ('" . implode("','", $arrShareObjectID) . "')";
        }

        /**
         * 自定义筛选列
         */
        $arrFlt = $arrConfig['arrFlt'];
        if ($this->filters) {
            foreach ($this->filters as $filter) {
                $arrFlt[] = [
                    'sField' => $filter->field->sFieldAs,
                    'sOper' => $filter->sOpera,
                    'sValue' => $filter->sParament,
                    'sTip' => $filter->sCDesc
                ];
            }
        }

        //格式化筛选列
        $arrFlt = $this->getListFilters($this, $arrFlt);
        foreach ($arrFlt as $flt) {
            $sFltSQL .= " AND " . $flt['sSQL'];
        }

        $sFltSQL = preg_replace("/^and/i", "", trim($sFltSQL));

        /**
         * 显示列，参照、列表、多选列表
         */
        $arrDispCol = $arrConfig['arrDispCol'];
        if (!$arrDispCol && $this->cols) {
            foreach ($this->cols as $col) {
                $arrDispCol[] = $col->field->sFieldAs;
            }
        }

        //格式化显示列
        $arr = $this->getListCols($this, $arrDispCol);
        $arrDispCol = $arr[0];
        $arrDbSelect = $arr[1];

        //获取数据
        if ($lPageLimit > 0 && $lPage > 0) {//有分页
            $arrData = ObjectModel::config($this->sysobject)
                ->select($arrDbSelect)
                ->where($sFltSQL)
                ->asArray()
                ->orderBy($sOrderBy)
                ->offset(($lPage - 1) * $lPageLimit)
                ->limit($lPageLimit)
                ->all();
        } else {//没有分页
            $arrData = ObjectModel::config($this->sysobject)
                ->select($arrDbSelect)
                ->where($sFltSQL)
                ->asArray()
                ->orderBy($sOrderBy)
                ->all();
        }

        //获取总数
        $lTotalCount = ObjectModel::config($this->sysobject)->where($sFltSQL)->count();

        //处理引用，参照等字段
        $arrListField = [];
        $arrRefField = [];
        foreach ($arrDispCol as $field) {
            if ($field->RefFieldID) {//引用的字段特殊处理
                $arrRefField[$field->reffield->sFieldAs][$field->sFieldAs] = $field;
            } elseif ($field->sDataType == 'ListTable' || $field->sDataType == 'List' || $field->sDataType == 'MultiList') {
                $arrListField[$field->sFieldAs] = $field;
            }
        }

        $arrListObjectID = [];
        $arrRefObjectID = [];
        foreach ($arrData as $data) {
            foreach ($arrListField as $sFieldAs => $refField) {
                if (!StrTool::isEmpty($data[$sFieldAs])) {
                    if ($refField->sDataType == 'MultiList') {
                        foreach (explode(";", trim($data[$sFieldAs], ";")) as $ObjectID) {
                            $arrListObjectID[$sFieldAs][$ObjectID] = $ObjectID;
                        }
                    } else {
                        $arrListObjectID[$sFieldAs][$data[$sFieldAs]] = $data[$sFieldAs];
                    }
                }
            }

            foreach ($arrRefField as $sFieldAs => $arrField) {
                $arrRefObjectID[$sFieldAs][$data[$sFieldAs]] = $data[$sFieldAs];
            }
        }

        $arrRefObjectListObjectID = [];
        foreach ($arrRefField as $sFieldAs => $arrField) {
            $refObject = current($arrField)->reffield->refobject;
            $arrSelect = [$refObject->sIDField];
            foreach ($arrField as $field) {
                $arrSelect[] = $field->sLinkField . " AS " . $field->sFieldAs;
            }

            $arrRefData[$sFieldAs] = ObjectModel::config($refObject)
                ->select($arrSelect)
                ->where([$refObject->sIDField => $arrRefObjectID[$sFieldAs]])
                ->indexBy($refObject->sIDField)
                ->asArray()
                ->all();
            //引用过来的字段带有参照，列表，多选列表型，需要再进一步进行提取ID，然后取值再赋值回去
            foreach ($arrRefData[$sFieldAs] as $data) {
                foreach ($arrField as $field) {
                    $sValue = $data[$field->sFieldAs];
                    if ($sValue) {
                        if ($field->sDataType == 'ListTable' || $field->sDataType == 'List') {
                            $arrRefObjectListObjectID[$field->sFieldAs][$sValue] = $sValue;
                        } elseif ($field->sDataType == 'MultiList') {
                            foreach (explode(";", trim($sValue, ";")) as $sListID) {
                                $arrRefObjectListObjectID[$field->sFieldAs][$sListID] = $sListID;
                            }
                        }
                    }
                }
            }
        }

        foreach ($arrRefField as $sFieldAs => $arrField) {
            foreach ($arrField as $field) {
                $arrValue = $arrRefObjectListObjectID[$field->sFieldAs];
                if ($arrValue) {
                    if ($field->sDataType == 'ListTable') {
                        $arrRefObjectListObjectID[$field->sFieldAs] = ObjectModel::config($field->refobject)
                            ->select([$field->refobject->sIDField . ' AS ID', $field->sShwField . ' AS sName'])
                            ->where($field->refobject->sIDField . " IN ('" . implode("','", $arrValue) . "')")
                            ->indexBy("ID")
                            ->asArray()
                            ->all();
                    } else {
                        if (trim($field->sEnumOption)) {
                            $arrRefObjectListObjectID[$field->sFieldAs] = $field->enumoptions;
                        } else {
                            $arrRefObjectListObjectID[$field->sFieldAs] = ObjectModel::config($field->enumobject)
                                ->select([$field->enumobject->sIDField . ' AS ID', $field->sShwField . ' AS sName'])
                                ->where($field->sLinkField . " IN ('" . implode("','", $arrValue) . "')")
                                ->indexBy("ID")
                                ->asArray()
                                ->all();
                        }
                    }
                }
            }
        }

        foreach ($arrRefData as $sFieldAs => $refData) {
            foreach ($refData as $RefFieldValueID => $arrValue) {
                foreach ($arrRefField[$sFieldAs] as $field) {
                    $sValue = $arrRefData[$sFieldAs][$RefFieldValueID][$field->sFieldAs];
                    if ($sValue) {
                        if ($field->sDataType == "MultiList") {
                            $arrRefData[$sFieldAs][$RefFieldValueID][$field->sFieldAs] = [];
                            foreach (explode(";", trim($sValue, ";")) as $ID) {
                                $arrRefData[$sFieldAs][$RefFieldValueID][$field->sFieldAs][] = $arrRefObjectListObjectID[$field->sFieldAs][$ID];
                            }
                        } elseif ($field->sDataType == "List" || $field->sDataType == "ListTable") {
                            $arrRefData[$sFieldAs][$RefFieldValueID][$field->sFieldAs] = $arrRefObjectListObjectID[$field->sFieldAs][$sValue];
                        }
                    }
                }

                unset($arrRefData[$sFieldAs][$RefFieldValueID]['lID']);
                unset($arrRefData[$sFieldAs][$RefFieldValueID]['ID']);
            }
        }

        foreach ($arrListField as $sFieldAs => $refField) {
            if ($refField->sDataType == 'ListTable' && $arrListObjectID[$sFieldAs]) {
                $arrListObjectID[$sFieldAs] = ObjectModel::config($refField->refobject)
                    ->select([$refField->sLinkField . ' AS ID', $refField->sShwField . ' AS sName'])
                    ->where($refField->sLinkField . " IN ('" . implode("','", $arrListObjectID[$sFieldAs]) . "')")
                    ->indexBy("ID")
                    ->asArray()
                    ->all();
            } elseif (($refField->sDataType == 'List' || $refField->sDataType == 'MultiList') && $arrListObjectID[$sFieldAs]) {
                if (trim($refField->sEnumOption)) {
                    $arrListObjectID[$sFieldAs] = $refField->enumoptions;
                } else {
                    $arrListObjectID[$sFieldAs] = ObjectModel::config($refField->enumobject)
                        ->select([$refField->sLinkField . ' AS ID', $refField->sShwField . ' AS sName'])
                        ->where($refField->sLinkField . " IN ('" . implode("','", $arrListObjectID[$sFieldAs]) . "')")
                        ->indexBy("ID")
                        ->asArray()
                        ->all();
                }
            }
        }

        $arrObjectID = [];

        //把参照、列表、多选列表的值赋值到数据列表去
        foreach ($arrData as $key => $data) {
            foreach ($arrListObjectID as $sFieldAs => $arrObject) {
                if ($arrListField[$sFieldAs]->sDataType == 'MultiList') {
                    $arr = [];
                    foreach (explode(";", trim($data[$sFieldAs], ";")) as $ObjectID) {
                        if ($arrObject[$ObjectID]) {
                            $arr[] = $arrObject[$ObjectID];
                        }
                    }
                    $arrData[$key][$sFieldAs] = $arr;
                } else {
                    $arrData[$key][$sFieldAs] = $arrObject[$data[$sFieldAs]];
                }
            }

            foreach ($arrRefField as $sFieldAs => $arrField) {
                if ($arrRefData[$sFieldAs][$data[$sFieldAs]]) {
                    $arrData[$key] = array_merge($arrData[$key], $arrRefData[$sFieldAs][$data[$sFieldAs]]);
                }
            }

            $arrObjectID[] = $data[$this->sysobject->sIDField];
        }

        //组织当前的配置信息返回
        $arrConfig['sDataRegion'] = $sDataRegion;
        $arrConfig['sOrderBy'] = $sOrderBy;
        $arrConfig['bCanPage'] = $bCanPage;
        $arrConfig['bCanSort'] = $bCanSort;
        $arrConfig['bCanBat'] = $bCanBat;
        $arrConfig['bSingle'] = $bSingle;
        $arrConfig['lPageLimit'] = $lPageLimit;
        $arrConfig['lPage'] = $lPage;

        return [
            'arrData' => $arrData,
            'arrObjectID' => $arrObjectID,
            'arrConfig' => $arrConfig,
            'arrDispCol' => $arrDispCol,
            'lTotalCount' => $lTotalCount,
            'lTotalPage' => ceil($lTotalCount / $lPageLimit)
        ];
    }

    /**
     * 获取自动共享的SQL语句
     */
    public static function getAutoShareUsers($SysUserID, $sObjectName, $sToken)
    {
        $sysUser = SysUser::findOne($SysUserID);

        $arrToUserID = [];
        $arrAutoShare = SysAutoShare::find()
            ->with('fromdep', 'fromrole', 'fromteam', 'todep', 'torole', 'toteam')
            ->where(['sObjectName' => $sObjectName])
            ->all();
        foreach ($arrAutoShare as $autoshare) {

            if (!stristr($autoshare->sToken, $sToken)) {
                continue;
            }

            //当前登陆人是否满足规则
            $bExist = false;

            if ($autoshare->ToSysTeamID) {//共享人在团队里
                if (SysTeamUser::findOne(['SysTeamID' => $autoshare->ToSysTeamID, 'SysUserID' => $SysUserID])) {
                    $bExist = true;
                }
            } elseif ($autoshare->ToSysDepID) {//共享人在部门里
                if ($autoshare->bToInclude && strstr($sysUser->sysdep->PathID, $autoshare->todep->PathID)) {//包含下级
                    $bExist = true;
                } elseif (!$autoshare->bToInclude && $autoshare->ToSysDepID == $sysUser->SysDepID) {//不包含下级
                    $bExist = true;
                }
            } elseif ($autoshare->ToSysRoleID) {//共享人在角色里
                if ($autoshare->bToInclude && strstr($sysUser->sysrole->PathID, $autoshare->torole->PathID)) {//包含下级
                    $bExist = true;
                } elseif (!$autoshare->bToInclude && $autoshare->ToSysRoleID == $sysUser->SysRoleID) {//不包含下级
                    $bExist = true;
                }
            } elseif ($autoshare->ToSysUserID == $SysUserID) {//共享人 是人员
                $bExist = true;
            } else {
                continue;
            }

            if ($bExist) {
                if ($autoshare->FromSysRoleID) {
                    if ($autoshare->bFromInclude) {
                        $arrDownRole = SysRole::find()->select(['lID'])->where("PathID LIKE '" . $autoshare->fromrole->PathID . "%'")->indexBy('lID')->all();
                        $arrDownUser = SysUser::find()->select(['lID'])->where(['SysRoleID' => array_keys($arrDownRole)])->indexBy('lID')->all();
                        $arrToUserID = array_merge($arrToUserID, array_keys($arrDownUser));
                    } else {
                        $arrDownUser = SysUser::find()->select(['lID'])->where(['SysRoleID' => $autoshare->FromSysRoleID])->indexBy('lID')->all();
                        $arrToUserID = array_merge($arrToUserID, array_keys($arrDownUser));
                    }
                } elseif ($autoshare->FromSysDepID) {
                    if ($autoshare->bFromInclude) {
                        $arrDownDep = SysDep::find()->select(['lID'])->where("PathID LIKE '" . $autoshare->fromdep->PathID . "%'")->indexBy('lID')->all();
                        $arrDownUser = SysUser::find()->select(['lID'])->where(['SysDepID' => array_keys($arrDownDep)])->indexBy('lID')->all();
                        $arrToUserID = array_merge($arrToUserID, array_keys($arrDownUser));
                    } else {
                        $arrDownUser = SysUser::find()->select(['lID'])->where(['SysDepID' => $autoshare->FromSysDepID])->indexBy('lID')->all();
                        $arrToUserID = array_merge($arrToUserID, array_keys($arrDownUser));
                    }
                } elseif ($autoshare->FromSysTeamID) {
                    $arrTeamUser = SysTeamUser::find()->select(['SysUserID'])->where(['SysTeamID' => $autoshare->FromSysTeamID])->indexBy('SysUserID')->all();
                    $arrToUserID = array_merge($arrToUserID, array_keys($arrTeamUser));
                } elseif ($autoshare->FromSysUserID) {
                    $arrToUserID[] = $autoshare->FromSysUserID;
                }
            }
        }

        return array_unique($arrToUserID);
    }

    /**
     * 格式化筛选列
     * @param SysList $SysList
     * @param array $arrFlt
     */
    private function getListFilters($sysList, $arrFlt)
    {
        if (!$arrFlt) {
            return [];
        }

        $arr = [];
        foreach ($arrFlt as $flt) {
            $arr[] = $flt['sField'];
        }

        $arrField = [];
        $arrFltField = SysField::find()->where("sObjectName='" . $sysList->sObjectName . "' AND sFieldAs IN ('" . implode("','",
                $arr) . "') AND sDataType<>'Virtual'")->all();
        foreach ($arrFltField as $field) {
            $arrField[strtolower($field->sFieldAs)] = $field;
        }

        //把不存在的字段去除
        foreach ($arrFlt as $lKey => $flt) {
            if (!$arrField[strtolower($flt['sField'])]) {
                unset($arrFlt[$lKey]);
            } else {

            }
        }

        foreach ($arrFlt as $lKey => $flt) {

            if ($flt['sSQL']) {
                continue;
            }

            if (!is_array($flt['sValue'])) {
                $flt['sValue'] = htmlspecialchars_decode($flt['sValue']);
            }

            //获取字段的配置
            $field = $arrField[strtolower($flt['sField'])];

            if ($field->RefFieldID) {
                $flt['sField'] = $field->sLinkField;
            }

            $sSQL = "";

            switch ($field->sDataType) {
                case "ListTable":
                case "List":
                case "AttachFile":
                case "Text":
                case "TextArea":
                    if (StrTool::equalsIgnoreCase($flt['sOper'], "equal")) {
                        if ($field->sDataType == 'List' || $field->sDataType == 'MultiList') {
                            $sSQL = "IN ('" . implode("','", explode(";", $flt['sValue'])) . "')";
                        } else {
                            $sSQL = "='" . $flt['sValue'] . "'";
                        }
                    } elseif (StrTool::equalsIgnoreCase($flt['sOper'], "notequal")) {
                        if ($field->sDataType == 'List' || $field->sDataType == 'MultiList') {
                            $sSQL = " NOT IN ('" . implode("','", explode(";", $flt['sValue'])) . "')";
                        } else {
                            $sSQL = "<>'" . $flt['sValue'] . "'";
                        }
                    } elseif (StrTool::equalsIgnoreCase($flt['sOper'], "benull")) {
                        $sSQL = "IS NULL";
                    } elseif (StrTool::equalsIgnoreCase($flt['sOper'], "notnull")) {
                        $sSQL = "IS NOT NULL";
                    } elseif (StrTool::equalsIgnoreCase($flt['sOper'], "center")) {
                        $sSQL = "LIKE '%" . $flt['sValue'] . "%'";
                    } elseif (StrTool::equalsIgnoreCase($flt['sOper'], "left")) {
                        $sSQL = "LIKE '" . $flt['sValue'] . "%'";
                    } elseif (StrTool::equalsIgnoreCase($flt['sOper'], "right")) {
                        $sSQL = "LIKE '%" . $flt['sValue'] . "'";
                    } elseif (StrTool::equalsIgnoreCase($flt['sOper'], "in")) {
                        $sSQL = "IN (" . (is_array($flt['sValue']) ? "'" . implode("','",
                                    $flt['sValue']) . "'" : $flt['sValue']) . ")";
                    } elseif (StrTool::equalsIgnoreCase($flt['sOper'], "noin")) {
                        $sSQL = "NOT IN (" . (is_array($flt['sValue']) ? "'" . implode("','",
                                    $flt['sValue']) . "'" : $flt['sValue']) . ")";
                    }

                    break;
                case "MultiList":
                    if (StrTool::equalsIgnoreCase($flt['sOper'], "equal")) {
                        if ($flt->sDataType == 'List' || $flt->sDataType == 'MultiList') {
                            $sSQL = $flt['sField'] . " IN ('" . implode("','", explode(";", $flt['sValue'])) . "')";
                        } else {
                            $sSQL = $flt['sField'] . "='" . $flt['sValue'] . "'";
                        }
                    } elseif (StrTool::equalsIgnoreCase($flt['sOper'], "notequal")) {
                        if ($flt->sDataType == 'List' || $flt->sDataType == 'MultiList') {
                            $sSQL = $flt['sField'] . " NOT IN ('" . implode("','", explode(";", $flt['sValue'])) . "')";
                        } else {
                            $sSQL = $flt['sField'] . "<>'" . $flt['sValue'] . "'";
                        }
                    } elseif (StrTool::equalsIgnoreCase($flt['sOper'], "benull")) {
                        $sSQL = $flt['sField'] . " IS NULL";
                    } elseif (StrTool::equalsIgnoreCase($flt['sOper'], "notnull")) {
                        $sSQL = $flt['sField'] . " IS NOT NULL";
                    } elseif (StrTool::equalsIgnoreCase($flt['sOper'], "center")) {

                        if (is_array($flt['sValue'])) {
                            $sSQL = "(";
                            $sComm = "";
                            foreach ($flt['sValue'] as $sValue) {
                                $sSQL .= $sComm . $flt['sField'] . " LIKE '%" . $sValue . "%'";
                                $sComm = " OR ";
                            }
                            $sSQL .= ")";
                        } else {
                            $sSQL = $flt['sField'] . " LIKE '%" . $flt['sValue'] . "%'";
                        }

                    } elseif (StrTool::equalsIgnoreCase($flt['sOper'], "left")) {

                        if (is_array($flt['sValue'])) {
                            $sSQL = "(";
                            $sComm = "";
                            foreach ($flt['sValue'] as $sValue) {
                                $sSQL .= $sComm . $flt['sField'] . " LIKE '" . $sValue . "'%";
                                $sComm .= " OR ";
                            }
                            $sSQL .= ")";
                        } else {
                            $sSQL = $flt['sField'] . " LIKE '" . $flt['sValue'] . "%'";
                        }

                    } elseif (StrTool::equalsIgnoreCase($flt['sOper'], "right")) {
                        $sSQL = "LIKE '%" . $flt['sValue'] . "'";

                        if (is_array($flt['sValue'])) {
                            $sSQL = "(";
                            $sComm = "";
                            foreach ($flt['sValue'] as $sValue) {
                                $sSQL .= $sComm . $flt['sField'] . " LIKE '%" . $sValue . "'";
                                $sComm .= " OR ";
                            }
                            $sSQL .= ")";
                        } else {
                            $sSQL = $flt['sField'] . " LIKE '%" . $flt['sValue'] . "'";
                        }

                    } elseif (StrTool::equalsIgnoreCase($flt['sOper'], "in")) {
                        $sSQL = $flt['sField'] . " IN (" . (is_array($flt['sValue']) ? "'" . implode("','",
                                    $flt['sValue']) . "'" : $flt['sValue']) . ")";
                    } elseif (StrTool::equalsIgnoreCase($flt['sOper'], "noin")) {
                        $sSQL = $flt['sField'] . " NOT IN (" . (is_array($flt['sValue']) ? "'" . implode("','",
                                    $flt['sValue']) . "'" : $flt['sValue']) . ")";
                    }

                    break;
                case "Bool":
                    $sSQL = "='" . $flt['sValue'] . "'";
                    break;
                case "Int":
                case "Float":
                    if (StrTool::equalsIgnoreCase($flt['sOper'], "equal")) {
                        $sSQL = "='" . $flt['sValue'] . "'";
                    } elseif (StrTool::equalsIgnoreCase($flt['sOper'], "notequal")) {
                        $sSQL = "<>'" . $flt['sValue'] . "'";
                    } elseif (StrTool::equalsIgnoreCase($flt['sOper'], "benull")) {
                        $sSQL = "IS NULL";
                    } elseif (StrTool::equalsIgnoreCase($flt['sOper'], "notnull")) {
                        $sSQL = "IS NOT NULL";
                    } elseif (StrTool::equalsIgnoreCase($flt['sOper'], "larger")) {
                        $sSQL = ">" . $flt['sValue'];
                    } elseif (StrTool::equalsIgnoreCase($flt['sOper'], "smaller")) {
                        $sSQL = "<" . $flt['sValue'];
                    } elseif (StrTool::equalsIgnoreCase($flt['sOper'], "lgeq")) {
                        $sSQL = ">=" . $flt['sValue'];
                    } elseif (StrTool::equalsIgnoreCase($flt['sOper'], "smeq")) {
                        $sSQL = "<=" . $flt['sValue'];
                    } elseif (StrTool::equalsIgnoreCase($flt['sOper'], "in")) {
                        $sSQL = "IN (" . (is_array($flt['sValue']) ? "'" . implode("','",
                                    $flt['sValue']) . "'" : $flt['sValue']) . ")";
                    } elseif (StrTool::equalsIgnoreCase($flt['sOper'], "noin")) {
                        $sSQL = "NOT IN (" . (is_array($flt['sValue']) ? "'" . implode("','",
                                    $flt['sValue']) . "'" : $flt['sValue']) . ")";
                    }

                    break;
                case "Date":
                    $fdate = new GetListFltDate($field->attr['sFieldDataType'], $field->attr['lTimeOffset']);

                    if (!strstr($flt['sValue'], "/")) {
                        $arrPart[] = explode("=", $flt['sValue'])[1];
                    } else {
                        $arrPart = [];
                        $arr = explode("/", $flt['sValue']);
                        foreach ($arr as $sPart) {
                            $arrPart[] = explode("=", $sPart)[1];
                        }
                    }

                    $sValue = strtolower($arrPart[0]);

                    switch (count($arrPart)) {
                        case 1:
                            if ($sValue == "thisyear") {
                                $sSQL = $fdate->getThisYear($flt['sField']);
                            } elseif ($sValue == "thismonth") {
                                $sSQL = $fdate->getThisMonth($flt['sField']);
                            } elseif ($sValue == "thisweek") {
                                $sSQL = $fdate->getThisWeek($flt['sField']);
                            } elseif ($sValue == "thisquarter") {
                                $sSQL = $fdate->getThisQuarter($flt['sField']);
                            } elseif ($sValue == "today") {
                                $sSQL = $fdate->getToday($flt['sField']);
                            } elseif ($sValue == "thistime") {
                                $sReturn = "";
                                if ($flt['sOper'] == "equal") {
                                    $sSQL = $flt['sField'] . "='" . ($field->attr['sFieldDataType'] == 'unix' ? time() : SystemTime::getCurLongDate($field->attr['lTimeOffset'])) . "'";
                                } elseif ($flt['sOper'] == "smaller") {
                                    $sSQL = $flt['sField'] . "<'" . ($field->attr['sFieldDataType'] == 'unix' ? time() : SystemTime::getCurLongDate($field->attr['lTimeOffset'])) . "'";
                                } elseif ($flt['sOper'] == "larger") {
                                    $sSQL = $flt['sField'] . ">'" . ($field->attr['sFieldDataType'] == 'unix' ? time() : SystemTime::getCurLongDate($field->attr['lTimeOffset'])) . "'";
                                } elseif ($flt['sOper'] == "smeq") {
                                    $sSQL = $flt['sField'] . "<='" . ($field->attr['sFieldDataType'] == 'unix' ? time() : SystemTime::getCurLongDate($field->attr['lTimeOffset'])) . "'";
                                } elseif ($flt['sOper'] == "lgeq") {
                                    $sSQL = $flt['sField'] . ">='" . ($field->attr['sFieldDataType'] == 'unix' ? time() : SystemTime::getCurLongDate($field->attr['lTimeOffset'])) . "'";
                                }
                            }

                            break;
                        case 2:
                            $arrDate = $fdate->get_FltPar($arrPart[1], "", "");

                            if ($sValue == "equal") {
                                $sSQL = $flt['sField'] . ">='" . $arrDate[0] . "' AND " . $flt['sField'] . "<='" . $arrDate[1] . "'";
                            } elseif ($sValue == "smaller") {
                                $sSQL = $flt['sField'] . "<'" . $arrDate[0] . "'";
                            } elseif ($sValue == "larger") {
                                $sSQL = $flt['sField'] . ">'" . $arrDate[0] . "'";
                            } elseif ($sValue == "smeq") { // 小于等于,应该小于等于后一个日期,如本月,则应该是<=7.1不是<=6.1,修改时间2004-06-07
                                $sSQL = $flt['sField'] . "<='" . $arrDate[1] . "'";
                            } elseif ($sValue == "lgeq") {
                                $sSQL = $flt['sField'] . ">='" . $arrDate[0] . "'";
                            } elseif ($sValue == "notin" || $sValue == "noteq") {
                                $sSQL = $flt['sField'] . "<>'" . $arrDate[1] . "'";
                            }

                            break;
                        case 3:
                            $arrDate = $fdate->get_FltPar($arrPart[1], "", "");
                            $arrDate1 = $fdate->get_FltPar($arrPart[2], "", "");
                            $sSQL = $flt['sField'] . ">='" . $arrDate[0] . "' AND " . $flt['sField'] . "<='" . $arrDate1[1] . "'";

                            break;
                    }
                    break;
            }

            if ($field->RefFieldID) {
                switch ($field->sDataType) {
                    case "ListTable":
                        $arrID = ObjectModel::config($field->refobject)
                            ->select([$field->refobject->sIDField . ' AS ID'])
                            ->where($field->refobject->sNameField . " " . $sSQL)
                            ->indexBy("ID")
                            ->asArray()
                            ->all();
                        $arrID = array_keys($arrID);

                        if ($arrID) {
                            $arrID = ObjectModel::config($field->reffield->refobject)
                                ->select([$field->reffield->refobject->sIDField . ' AS ID'])
                                ->where([$field->sLinkField => $arrID])
                                ->indexBy("ID")
                                ->asArray()
                                ->all();
                            $arrID = array_keys($arrID);
                            $arrID[] = -1;//加一个默认的值

                            $arrFlt[$lKey]['sSQL'] = $field->reffield->sFieldAs . " IN ('" . implode("','",
                                    $arrID) . "')";
                        } else {
                            $arrFlt[$lKey]['sSQL'] = $field->reffield->sFieldAs . "='-1'";
                        }

                        break;
                    case "Date":
                    case "MultiList":
                        $arrFlt[$lKey]['sSQL'] = $sSQL;

                        $arrID = ObjectModel::config($field->reffield->refobject)
                            ->select([$field->reffield->refobject->sIDField . ' AS ID'])
                            ->where($sSQL)
                            ->indexBy("ID")
                            ->asArray()
                            ->all();
                        $arrID = array_keys($arrID);
                        $arrID[] = -1;//加一个默认的值

                        $arrFlt[$lKey]['sSQL'] = $field->reffield->sFieldAs . " IN ('" . implode("','", $arrID) . "')";

                        break;
                    default:
                        $arrID = ObjectModel::config($field->reffield->refobject)
                            ->select([$field->reffield->refobject->sIDField . ' AS ID'])
                            ->where($field->sLinkField . " " . $sSQL)
                            ->indexBy("ID")
                            ->asArray()
                            ->all();
                        $arrID = array_keys($arrID);
                        $arrID[] = -1;//加一个默认的值

                        $arrFlt[$lKey]['sSQL'] = $field->reffield->sFieldAs . " IN ('" . implode("','", $arrID) . "')";
                        break;
                }

            } else {
                switch ($field->sDataType) {
                    case "ListTable":
                        $arrID = ObjectModel::config($field->refobject)
                            ->select([$field->sLinkField . ' AS ID'])
                            ->where($flt['sOper'] == 'id' ? $field->sLinkField : $field->sShwField . " " . $sSQL)
                            ->indexBy("ID")
                            ->asArray()
                            ->all();
                        $arrID = array_keys($arrID);
                        $arrID[] = -1;//加一个默认的值
                        $arrFlt[$lKey]['sSQL'] = $flt['sField'] . " IN ('" . implode("','", $arrID) . "')";
                        break;
                    case "Date":
                    case "MultiList":
                        $arrFlt[$lKey]['sSQL'] = $sSQL;
                        break;
                    default:
                        $arrFlt[$lKey]['sSQL'] = $flt['sField'] . " " . $sSQL;
                        break;
                }
            }
        }

        return $arrFlt;
    }

    /**
     * 格式化显示列
     * @param SysList $SysList
     * @param array $arrDispCol
     */
    private function getListCols($sysList, $arrDispCol)
    {
        //获取视图所属对象的配置信息
        $sysObject = $sysList->sysobject;

        $arr = [];
        $arrDispField = SysField::find()->with('reffield')->where("sObjectName='" . $sysObject->sObjectName . "' AND sFieldAs IN ('" . implode("','",
                $arrDispCol) . "')")->all();
        foreach ($arrDispField as $field) {
            $arr[strtolower($field->sFieldAs)] = $field;
        }

        //排列顺序
        $arrDispField = [];
        $arrDbSelect = [$sysObject->sIDField];
        foreach ($arrDispCol as $sFieldAs) {

            $field = $arr[strtolower($sFieldAs)];
            if (!$field) {
                continue;
            }

            if ($field->sDataType != 'Virtual') {

                if ($field->RefFieldID) {//引用字段，初始值为空
                    //$arrDbSelect[] = "'' AS ".$field->sFieldAs;
                    //引用的字段，需要把被引用的字段带进来，否则没有值可以去获取
                    if (!in_array($field->reffield->sFieldAs, $arrDbSelect)) {
                        $arrDbSelect[] = $field->reffield->sFieldAs;
                    }
                } else {
                    $arrDbSelect[] = $sysObject->sTable . "." . $field->sFieldAs;
                }


                if ($field->sDataType == 'AttachFile') {
                    $arrDbSelect[] = $sysObject->sTable . "." . $field->sLinkField;
                }


            }

            $arrDispField[] = $field;
        }

        return [$arrDispField, $arrDbSelect];
    }
}
