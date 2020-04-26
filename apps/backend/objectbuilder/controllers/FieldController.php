<?php

namespace myerm\backend\objectbuilder\controllers;

use myerm\backend\common\libs\StrTool;
use myerm\backend\common\libs\SystemTime;
use myerm\backend\system\models\SysField;
use myerm\backend\system\models\SysModule;
use myerm\backend\system\models\SysObject;
use Yii;

/**
 * 对象管理器控制器-属性管理
 * ============================================================================
 * 版权所有 2010-2017 三只象（厦门）网络科技有限公司，并保留所有权利。
 * 网站地址: http://www.myerm.cn
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和
 * 使用；不允许对程序代码以任何形式任何目的的再发布。
 * ============================================================================
 * @author 陈鹭明  <lumingchen@qq.com>
 * @since 2015-11-6 9:41
 * @version v2.0
 */
class FieldController extends Controller
{
    /**
     * 属性的列表
     * @author Mars <lumingchen@qq.com>
     * @since 2015-11-6 9:41
     */
    public function actionList()
    {
        $arrReturn = [];

        $sObjectName = Yii::$app->request->post('sObjectName');

        if (Yii::$app->request->post('keyword')) {
            $arrField = SysField::find()->where("sObjectName='$sObjectName' AND (sFieldAs LIKE '%" . Yii::$app->request->post('keyword') . "%' OR sName LIKE '%" . Yii::$app->request->post('keyword') . "%')")->orderBy("sFieldAs")->all();
        } else {
            $arrField = SysField::find()->where("sObjectName='$sObjectName'")->orderBy("sFieldAs")->all();
        }

        foreach ($arrField as $field) {
            $data = $field->toArray();

            //如果是参照字段，去读取参照了哪个对象
            if (StrTool::equalsIgnoreCase($field->sDataType, "ListTable")) {
                $refObject = SysObject::find()->select(['sName'])->where("sObjectName='" . $field->sRefKey . "'")->one();
                $data['sRefObjectName'] = $refObject->sName;
            } elseif (StrTool::equalsIgnoreCase($field->sDataType,
                    "List") || StrTool::equalsIgnoreCase($field->sDataType, "multilist")
            ) {
                //如果是列表型或者多选列表型，通过sEnumTable查找，看它是参照了哪个对象
                $refObject = SysObject::find()->select(['sName'])->where("sObjectName='" . $field->sEnumTable . "'")->one();
                $data['sRefObjectName'] = $refObject->sName;
            }

            //类别的中文名
            $data['sType'] = SysField::$arrType[strtolower($field->sDataType)]['sName'];

            $arrReturn['arrField'][] = $data;
        }

        $this->output($arrReturn);
    }

    /**
     * 新建属性
     * @author Mars <lumingchen@qq.com>
     * @since 2015-11-6 9:41
     */
    public function actionNew()
    {
        $arrReturn = [];
        $arrReturn['arrFieldType'] = SysField::$arrType;

        $this->output($arrReturn);
    }


    /**
     * 附加属性
     * @author Mars <lumingchen@qq.com>
     * @since 2015-11-25 17:05
     */
    public function actionAttach()
    {
        $arrReturn = [];
        $arrReturn['arrFieldType'] = SysField::$arrType;

        $sObjectName = Yii::$app->request->post('sObjectName');

        $sysObject = SysObject::find()->where("sObjectName='$sObjectName'")->one();

        $arr = ['-11111'];
        $arrAllField = SysField::find()->select(['sFieldAs'])->where("sObjectName='$sObjectName'")->all();
        foreach ($arrAllField as $field) {
            $arr[] = $field->sFieldAs;
        }

        $arrReturn['arrField'] = [];
        $tableSchema = $sysObject->dbconn->getSchema()->getTableSchema($sysObject->sTable, true);
        foreach ($tableSchema->columns as $sFieldName => $column) {
            if (!in_array($sFieldName, $arr)) {
                $arrReturn['arrField'][] = $sFieldName;
            }
        }

        $this->output($arrReturn);
    }

    /**
     * 编辑属性
     * @author Mars <lumingchen@qq.com>
     * @since 2015-11-12 20:23
     */
    public function actionEdit()
    {
        $arrReturn = [];
        $arrReturn['field'] = SysField::findOne(Yii::$app->request->post('id'))->toArray();

        $this->output($arrReturn);
    }


    /**
     * 编辑属性保存
     * @author Mars <lumingchen@qq.com>
     * @since 2015-11-12 20:23
     */
    public function actionEditsave()
    {
        $arrReturn = [];
        $field = SysField::findOne(Yii::$app->request->post('ID'));
        foreach ($field->attributes as $sField => $sValue) {
            if ($sField == 'sAttribute') {
                if (isset($_POST['sAttribute'])) {
                    $arr = json_decode(htmlspecialchars_decode($_POST['sAttribute']), true);
                    $arr2 = unserialize($field->sAttribute);
                    if ($arr2 === FALSE) {
                        $arr2 = json_decode($field->sAttribute, true);
                    }

                    if ($arr['sTimeZone']) {
                        $arr['lTimeOffset'] = SystemTime::getTimeOffset($arr['sTimeZone']);
                    }

                    $field->sAttribute = serialize(array_merge($arr2, $arr));
                }
            } elseif (isset($_POST[$sField])) {
                $field->$sField = htmlspecialchars_decode($_POST[$sField]);
            }
        }
        $field->save();

        $this->output($arrReturn);
    }


    /**
     * 新建属性保存
     * @author Mars <lumingchen@qq.com>
     * @since 2015-11-7 11:15
     */
    public function actionNewsave()
    {
        $arrReturn = [];

        $arrPostData = [];
        foreach ($_POST as $sKey => $sValue) {
            $arrPostData[$sKey] = $sValue;
        }

        if ($arrPostData['sDataType'] == "Common") {
            $data['FieldID'] = $arrPostData['CommonFieldID'];
        } elseif ($arrPostData['sDataType'] == "Reference") {
            $data['referenceObj'] = $arrPostData['referenceObj'];
            $data['referenceField'] = $arrPostData['referenceField'];

            //引用，字段名需要特殊拼接起来
            $refObj = SysField::findOne($data['referenceObj']);
            $refField = SysField::findOne($data['referenceField']);
            $arrPostData['sFieldAs'] = $refField->sFieldAs . $refObj->sFieldAs;
            $data['RefFieldID'] = $data['referenceObj'];
            $data['sLinkField'] = $refField->sFieldAs;
        }

        $data['ID'] = $arrPostData['ID'];
        $data['sName'] = $arrPostData['sName'];
        $data['sFieldAs'] = $arrPostData['sFieldAs'];
        $data['sObjectName'] = $arrPostData['sObjectName'];
        $data['sTip'] = $arrPostData['sTip'];
        $data['bNull'] = $arrPostData['bNull'];
        $data['sDataType'] = $arrPostData['sDataType'];
        $data['sAttribute'] = htmlspecialchars_decode($arrPostData['sAttribute']);
        $data['sDefValue'] = $arrPostData['sDefValue'];
        $data['lLength'] = $arrPostData['lLength'];
        $data['lDeciLength'] = $arrPostData['lDeciLength'];
        $data['ParentID'] = $arrPostData['ParentID'];
        $data['sRefKey'] = $arrPostData['sRefKey'];
        $data['sFieldClassType'] = $arrPostData['sFieldClassType'];
        $data['sEnumTable'] = $arrPostData['sEnumTable'];
        $data['bEnableRTE'] = $arrPostData['bEnableRTE'];
        $data['sEnumOption'] = $arrPostData['sEnumOption'];
        $data['bUDF'] = 1;

        $sysObject = SysObject::findOne(['sObjectName' => $data['sObjectName']]);

        if (!$data['sFieldAs'] && $data['sDataType'] != "Reference") {
            $this->lStatus = 0;
            $this->sErrMsg = "请输入字段名";
        } elseif (!$data['sName']) {
            $this->lStatus = 0;
            $this->sErrMsg = "请输入属性名称";
        } else {
            if (SysField::find()->where("sObjectName='" . $data['sObjectName'] . "' AND sFieldAs='" . $data['sFieldAs'] . "'")->one()) {
                $this->lStatus = 0;
                $this->sErrMsg = "字段已经存在。";
            } elseif (SysField::find()->where("sObjectName='" . $data['sObjectName'] . "' AND sName='" . $data['sName'] . "'")->one()) {
                $this->lStatus = 0;
                $this->sErrMsg = "属性名已经被使用。";
            } else {

                if ($data['sDataType'] == "ListTable") {
                    //查找被参照对象的主键字段名
                    $sysObject = SysObject::findOne(['sObjectName' => $data['sRefKey']]);
                    $primaryKey = SysField::find()->select([
                        'sFieldAs',
                        'sDataType',
                        'sLinkField'
                    ])->where("sObjectName='" . $data['sRefKey'] . "' AND sFieldAs='" . $sysObject->sIDField . "'")->one();
                    $data['sLinkField'] = $sysObject->sIDField;
                    $data['sShwField'] = $sysObject->sNameField;
                } elseif ($data['sDataType'] == "List" || $data['sDataType'] == "MultiList") {
                    if (trim($data['sEnumOption'])) {

                    } elseif ($data['sEnumTable'] != "System/SysFieldEnum") {
                        $sysObject = SysObject::findOne(['sObjectName' => $data['sEnumTable']]);
                        $primaryKey = SysField::find()->select([
                            'sFieldAs',
                            'sDataType',
                            'sLinkField'
                        ])->where("sObjectName='" . $data['sEnumTable'] . "' AND sFieldAs='" . $sysObject->sIDField . "'")->one();
                        $data['sLinkField'] = $sysObject->sIDField;
                        $data['sShwField'] = $sysObject->sNameField;
                    } else {
                        $data['sLinkField'] = "ID";
                        $data['sShwField'] = "sName";
                    }
                } elseif ($data['sDataType'] == "AttachFile") {
                    $data['sLinkField'] = $data['sFieldAs'] . "Path";
                } elseif ($data['sDataType'] == "Date") {
                    $arrAttr = json_decode($data['sAttribute'], true);
                    $arrAttr['lTimeOffset'] = SystemTime::getTimeOffset($arrAttr['sTimeZone']);
                    $data['sAttribute'] = serialize($arrAttr);
                }

                $field = SysField::createField($data['sDataType']);
                $fieldID = $field->addField($data);

                if ($data['sDataType'] != "Reference" && !$arrPostData['bAttach']) {
                    if ($data['sDataType'] == "Common") {
                        $data['sDataType'] = SysField::findOne($fieldID)->sDataType;
                    }

                    if ($data['sObjectName'] != "System/Common") {
                        //新增数据库字段
                        $type = SysField::$arrType[strtolower($data['sDataType'])];

                        $object = SysObject::find()->select([
                            'DataSourceID',
                            'sTable'
                        ])->where("sObjectName='" . $data['sObjectName'] . "'")->one();
                        $sDataSourceKey = "ds_" . $object->DataSourceID;
                        $db = Yii::$app->$sDataSourceKey;

                        if ($data['sDataType'] == "ListTable") {
                            if ($primaryKey->sDataType == 'Int') {
                                $db->createCommand()->addColumn($object->sTable, $data['sFieldAs'],
                                    'integer')->execute();
                            } else {
                                $db->createCommand()->addColumn($object->sTable, $data['sFieldAs'],
                                    'string(32)')->execute();
                            }
                        } elseif ($data['sDataType'] == "List") {
                            if (trim($data['sEnumOption'])) {
                                $db->createCommand()->addColumn($object->sTable, $data['sFieldAs'],
                                    'string(50)')->execute();
                            } elseif ($data['sEnumTable'] != "System/SysFieldEnum") {
                                if ($primaryKey->sDataType == 'Int') {
                                    $db->createCommand()->addColumn($object->sTable, $data['sFieldAs'],
                                        'integer')->execute();
                                } else {
                                    $db->createCommand()->addColumn($object->sTable, $data['sFieldAs'],
                                        'string(32)')->execute();
                                }
                            } else {
                                $db->createCommand()->addColumn($object->sTable, $data['sFieldAs'],
                                    'string(32)')->execute();
                            }
                        } elseif ($data['sDataType'] == "Bool") {
                            $db->createCommand()->addColumn($object->sTable, $data['sFieldAs'],
                                $type['sDBType'])->execute();
                        } elseif ($data['sDataType'] == "Date") {
                            $arrAttr = unserialize($data['sAttribute']);
                            if ($arrAttr === FALSE) {
                                $arrAttr = json_decode($data['sAttribute'], true);
                            }

                            if ($arrAttr['sFieldDataType'] == 'date') {
                                $db->createCommand()->addColumn($object->sTable, $data['sFieldAs'],
                                    $type['sDBType'])->execute();
                            } else {
                                $db->createCommand()->addColumn($object->sTable, $data['sFieldAs'],
                                    'integer')->execute();
                            }
                        } elseif ($data['sDataType'] == "Virtual") {
                        } else {
                            $db->createCommand()->addColumn($object->sTable, $data['sFieldAs'],
                                $type['sDBType'])->execute();
                            if ($data['sDataType'] == "AttachFile") {
                                $db->createCommand()->addColumn($object->sTable, $data['sFieldAs'] . "Path",
                                    $type['sDBType'])->execute();
                            }
                        }
                    }
                }
            }
        }

        $this->output($arrReturn);
    }


    /**
     * 删除属性保存
     * @author Mars <lumingchen@qq.com>
     * @since 2015-11-7 16:15
     */
    public function actionDel()
    {
        $arrReturn = [];

        $ID = $_POST['ID'];
        $sObjectName = $_POST['sObjectName'];

        $sysField = SysField::findOne($ID);
        if (!$sysField) {
            $this->lStatus = 0;
            $this->sErrMsg = "该属性不存在。";
        }/*  elseif (!$sysField->bUDF) {
	        $this->lStatus = 0;
	        $this->sErrMsg = "只有自定义属性才能删除。";	        
	    } */ else {
            $sysField->deleteField();
        }

        $this->output($arrReturn);
    }


    /**
     * 查询可以被参照的对象
     */
    public function actionGetlisttableobjects()
    {
        $arrReturn = [];
        $arrReturn['arrRefObj'] = SysObject::find()->select([
            'sName',
            'sObjectName',
            'ModuleID',
            'sTable'
        ])->where("sType='Master'")->orderBy("ModuleID, sTable")->asArray()->all();
        $arrReturn['arrModule'] = SysModule::find()->asArray()->all();

        $this->output($arrReturn);
    }

    /**
     * 查询可以被引用的对象
     */
    public function actionGetrefobjects()
    {
        $arrReturn = [];

        $sObjectName = Yii::$app->request->post('sObjectName');
        $arrReturn['arrRefField'] = [];
        $arrRefField = SysField::find()->select([
            'sName',
            'sObjectName',
            'sFieldAs',
            'ID'
        ])->with('sysobject')->where("sObjectName='$sObjectName' AND sDataType='ListTable'")->all();
        foreach ($arrRefField as $field) {
            $data = [];
            $data['sName'] = $field->sName;
            $data['sFieldAs'] = $field->sFieldAs;
            $data['ID'] = $field->ID;
            $data['sObjectName'] = $field->sObjectName;
            $arrReturn['arrRefField'][] = $data;
        }

        $this->output($arrReturn);
    }


    /**
     * 查询可以被引用的对象的字段
     */
    public function actionGetreffields()
    {
        $arrReturn = [];

        $referenceObj = Yii::$app->request->post('referenceObj');

        $sObjectName = SysField::find()->select(['sRefKey'])->where(['ID' => $referenceObj])->one()->sRefKey;

        $arrReturn['arrRefField'] = SysField::find()->select([
            'sName',
            'ID',
            'sFieldAs'
        ])->where("sObjectName='$sObjectName' AND RefFieldID IS NULL")->asArray()->all();

        $this->output($arrReturn);
    }

    /**
     * 查询可以被做为上级列表的字段
     */
    public function actionGetlistfields()
    {
        $sObjectName = Yii::$app->request->post('sObjectName');

        $arrReturn = [];
        $arrReturn['arrListField'] = SysField::find()->select([
            'sName',
            'ID',
            'sFieldAs'
        ])->where("sObjectName='$sObjectName' AND sDataType='List'")->orderBy("sName")->asArray()->all();
        $arrReturn['arrRefObj'] = SysObject::find()->select([
            'sName',
            'sObjectName',
            'ModuleID',
            'sTable'
        ])->orderBy("ModuleID, sTable")->asArray()->all();
        $arrReturn['arrModule'] = SysModule::find()->asArray()->all();

        $this->output($arrReturn);
    }

    /**
     * 查询可以公共对象的字段
     */
    public function actionGetcommonfields()
    {
        $arrReturn = [];

        $arrReturn['arrRefField'] = SysField::find()->select([
            'sName',
            'ID',
            'sFieldAs'
        ])->where("sObjectName='System/Common'")->asArray()->all();

        $this->output($arrReturn);
    }

    public function actionGetdefaulttimezone()
    {
        $this->output(['timezone' => date_default_timezone_get()]);
    }

}
