<?php

namespace myerm\backend\system\models;

use Yii;
use myerm\backend\common\models\ObjectModel;
use myerm\backend\system\models\SysObject;
use myerm\backend\common\libs\StrTool;


/**
 * 系统对象模型-界面配置模型
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
class SysUI extends \myerm\backend\system\models\Model
{
    /**
     * 获取信息块
     */
    public function getFieldclass()
    {
        return $this->hasMany(SysUIFieldClass::className(), ['SysUIID'=>'ID'])->with('fields')->orderBy('lPos');
    }
    
    /**
     * 获取界面
     */
    public static function getUI($sObjectName, $sInterface)
    {

        $arrUI = self::find()->with('fieldclass')->select(['ID'])->where("sObjectName='$sObjectName' AND (sInterface LIKE '%{$sInterface},%' OR sInterface LIKE '%,{$sInterface},%' OR sInterface LIKE '%,{$sInterface}%' OR sInterface='$sInterface')")->all();

        foreach ($arrUI as $ui) {
            $orgObject = $ui->orgobject;
            
            $bTeamMatch = false;
            if ($orgObject->sTeamID) {
                foreach (explode(";", Yii::$app->backendsession->SysTeamID) as $SysUserTeamID) {
                    if (stristr($orgObject->sTeamID, ";".$SysUserTeamID.";")) {
                        $bTeamMatch = true;
                    }
                }
            }

            //优先等级：人员 > 团队 > 角色 > 部门
            if (stristr($orgObject->sUserID, ";".Yii::$app->backendsession->SysUserID.";")) {
                return $ui;
            } elseif ($bTeamMatch) {
                return $ui;   
            } elseif (stristr($orgObject->sRoleID, ";".Yii::$app->backendsession->SysRoleID.";")) {
                return $ui;
            } elseif (stristr($orgObject->sDepID, ";".Yii::$app->backendsession->SysDepID.";")) {
                return $ui;
            }
        }

        foreach ($arrUI as $ui) {
            $orgObject = $ui->orgobject;

            //如果没有配置权限
            if (!$orgObject) {
                return $ui;
            }
        }
    }
    
    /**
     * 获取UI的数据
     * @param SysUI $ui
     * @param String $sObjectName
     * @param String $ID
     */
    public static function getUIData($ui, $sObjectName, $ID)
    {
        $sysObject = SysObject::findOne(['sObjectName'=>$sObjectName]);
        
        $arrRefField = [];
        $arrDispCol = [$sysObject->sIDField, $sysObject->sNameField];
        foreach ($ui->fieldclass as $fieldclass) {
            foreach ($fieldclass->fields as $f) {               
                if (!$f->field->RefFieldID) {
                    $arrDispCol[] = $f->field->sFieldAs;
                } else {
                    $arrDispCol[] = $f->field->reffield->sFieldAs;              
                }
                
                if ($f->field->sDataType == 'AttachFile') {
                    $arrDispCol[] = $f->field->sLinkField;
                }

                if ($f->field->sDataType == 'ListTable' || $f->field->sDataType == 'List' || $f->field->sDataType == 'MultiList' || $f->field->RefFieldID) {
                    $arrRefField[$f->field->sFieldAs] = $f->field;
                }                                
            }
        }

        $arrDispCol = array_unique($arrDispCol);
        
        $data = ObjectModel::config($sysObject)->select($arrDispCol)->where([$sysObject->sIDField=>$ID])->asArray()->one();

        $arrRefData = [];
        foreach ($arrRefField as $sFieldAs => $field) {
            if ($field->RefFieldID) {//引用
                $refField = SysField::findOne($field->RefFieldID);
                $sValue = $data[$refField->sFieldAs];

                if ($field->sDataType == 'ListTable') {                   
                    $refFieldValue = ObjectModel::config($refField->refobject)
                                                     ->select([$field->sLinkField])
                                                     ->where([$refField->refobject->sIDField=>$sValue])
                                                     ->asArray()
                                                     ->one()[$field->sLinkField];

                    $arrRefData[$sFieldAs] = ObjectModel::config($field->refobject)
                                                        ->select([$field->refobject->sIDField." AS ID", $field->refobject->sNameField." AS sName"])
                                                        ->where([$field->refobject->sIDField=>$refFieldValue])
                                                        ->asArray()
                                                        ->one();
                } elseif ($field->sDataType == 'List') {      
                    $refFieldValue = ObjectModel::config($refField->refobject)
                                                    ->select([$field->sLinkField])
                                                    ->where([$refField->refobject->sIDField=>$sValue])
                                                    ->asArray()
                                                    ->one()[$field->sLinkField];
                    if ($field->sEnumOption) {
                        $arrRefData[$sFieldAs] = $field->enumoptions[$refFieldValue];
                    } else {
                        $arrRefData[$sFieldAs] = ObjectModel::config($field->enumobject)
                                                    ->select([$field->enumobject->sIDField, $field->enumobject->sNameField])
                                                    ->where([$field->enumobject->sIDField=>$refFieldValue])
                                                    ->asArray()
                                                    ->one();
                    }
                    
                } elseif ($field->sDataType == 'MultiList') {echo 11;
                    $refFieldValue = ObjectModel::config($refField->refobject)
                                                ->select([$field->sLinkField])
                                                ->where([$refField->refobject->sIDField=>$sValue])
                                                ->asArray()
                                                ->one()[$field->sLinkField];
                    if ($field->sEnumOption) {
                        $arrRefData[$sFieldAs] = [];
                        foreach (explode(";", trim($refFieldValue, ";")) as $ListID) {
                            $arrRefData[$sFieldAs][] = $field->enumoptions[$ListID];
                        }
                    } else {                    
                        $arrRefData[$sFieldAs] = ObjectModel::config($field->enumobject)
                                                    ->select([$field->enumobject->sIDField, $field->enumobject->sNameField])
                                                    ->where([$field->enumobject->sIDField=>explode(";", trim($refFieldValue, ";"))])
                                                    ->asArray()
                                                    ->all();
                    }
                } elseif ($field->sDataType == 'AttachFile') {
                
                
                } else {
                    $refFieldValue = ObjectModel::config($refField->refobject)
                                                        ->select([$field->sLinkField])
                                                        ->where([$refField->refobject->sIDField=>$sValue])
                                                        ->one()
                                                        ->{$field->sLinkField};
                    $arrRefData[$sFieldAs] = $refFieldValue;
                }
            
            } elseif ($field->sDataType == 'ListTable') {
                $arrRefData[$sFieldAs] = ObjectModel::config($field->refobject)
                                            ->select([$field->sLinkField." AS ID", $field->sShwField." AS sName"])
                                            ->where([$field->sLinkField=>$data[$sFieldAs]])
                                            ->asArray()
                                            ->one();
            } elseif ($field->sDataType == 'List') {
                if ($field->sEnumOption) {
                    $arrRefData[$sFieldAs] = $field->enumoptions[$data[$sFieldAs]];
                } else {
                    Yii::trace($field->sFieldAs);
                    
                    if (!StrTool::isEmpty($data[$sFieldAs])) {
                        $arrRefData[$sFieldAs] = ObjectModel::config($field->enumobject)
                                                    ->select([$field->sLinkField." AS ID", $field->sShwField." AS sName"])
                                                    ->where([$field->sLinkField=>$data[$sFieldAs]])
                                                    ->asArray()
                                                    ->one();  
                    } else {
                        $arrRefData[$sFieldAs] = ['ID'=>null, 'sName'=>null];
                    }
                }
            } elseif ($field->sDataType == 'MultiList') {
                if ($field->sEnumOption) {
                    $arrRefData[$sFieldAs] = [];
                    foreach (explode(";", trim($data[$sFieldAs], ";")) as $ListID) {
                        $arrRefData[$sFieldAs][] = $field->enumoptions[$ListID];
                    }
                } else {
                    $arr = ObjectModel::config($field->enumobject)
                        ->select([$field->sLinkField." AS ID", $field->sShwField." AS sName"])
                        ->where([$field->sLinkField=>explode(";", trim($data[$sFieldAs], ";"))])
                        ->asArray()
                        ->indexBy('ID')
                        ->all();

                    if (trim($data[$sFieldAs], ";")) {
                        foreach (explode(";", trim($data[$sFieldAs], ";")) as $ListID) {
                            $arrRefData[$sFieldAs][] = $arr[$ListID];
                        }
                    }
                }
            }
        }

        return array_merge($data, $arrRefData);
    }
}
