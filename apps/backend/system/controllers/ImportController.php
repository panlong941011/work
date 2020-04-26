<?php

namespace myerm\backend\system\controllers;

use myerm\backend\common\controllers\ObjectController;
use myerm\backend\common\libs\PinYin;
use myerm\backend\common\libs\StrTool;
use myerm\backend\common\models\ObjectModel;
use myerm\backend\system\models\SysField;
use myerm\backend\system\models\SysModule;
use myerm\backend\system\models\SysObject;

/**
 * 对象数据批量导入控制器
 * ============================================================================
 * 版权所有 2010-2017 三只象（厦门）网络科技有限公司，并保留所有权利。
 * 网站地址: http://www.myerm.cn
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和
 * 使用；不允许对程序代码以任何形式任何目的的再发布。
 * ============================================================================
 *
 * @author 陈鹭明 <lumingchen@qq.com>
 * @since 2016-1-21 22:12
 * @version v2.0
 */
class ImportController extends ObjectController
{

    public function actionJs()
    {
        $arrData = [];
        return $this->renderPartial('js', $arrData);
    }

    /**
     * 登陆页
     */
    public function actionHome()
    {
        $arrData = [];

        $pinyin = new PinYin();
        $arrData['arrObject'] = [];
        $arrObject = SysObject::find()->all();
        foreach ($arrObject as $object) {
            $sPinYin = $pinyin->getFullSpell(mb_convert_encoding($object->sName, "gb2312", "utf-8"), "");
            $arrData['arrObject'][$object->ModuleID][strtoupper($sPinYin[0])][$sPinYin] = $object;
        }

        $arrData['arrModule'] = SysModule::find()->all();

        return $this->render('home', $arrData);
    }

    public function actionGetfield()
    {
        $arrField = SysField::findAll(['sObjectName' => $_GET['sObjectName']]);
        foreach ($arrField as $field) {
            echo "<option value='" . $field->sFieldAs . "'>" . $field->sName . "</option>";
        }
    }

    /**
     * 导入保存
     */
    public function actionSave()
    {
        set_time_limit(0);
        ini_set("memory_limit", "8048M");

        include \Yii::getAlias("@app") . "/common/libs/PHPExcel.php";
        include \Yii::getAlias("@app") . '/common/libs/PHPExcel/IOFactory.php';

        //读取Excel表格的内容
        $inputFileType = \PHPExcel_IOFactory::identify($_FILES['file']['tmp_name']);
        $objReader = \PHPExcel_IOFactory::createReader($inputFileType);
        $objPHPExcel = $objReader->load($_FILES['file']['tmp_name']);


        $sysObject = SysObject::findOne($_GET['sObjectName']);

        //通过数据源获取数据库连接
        $sDataSourceKey = "ds_" . $sysObject->DataSourceID;
        $db = \Yii::$app->$sDataSourceKey;

        //准备事务
        //$transaction = $db->beginTransaction();

        try {
            $bFlag = true;
            $arrMsg = [];
            $arrObjectCache = [];
            foreach ($objPHPExcel->getAllSheets() as $sheet) {
                $sheetData = $sheet->toArray(null, true, true, true);

                $arrField = array_shift($sheetData);
                $arrFieldIDKey = array();
                foreach ($arrField as $k => $v) {
                    if (!trim($v)) {
                        unset($arrField[$k]);
                    } else {
                        $field = SysField::findOne(['sObjectName' => $sysObject->sObjectName, 'sName' => $v]);

                        if (!$field) {
                            $arrMsg[] = "字段[" . $v . "]不存在。";
                            $bFlag = false;
                        } else {
                            $arrField[$k] = $field->toArray();
                            $arrFieldIDKey[$arrField[$k]['sFieldAs']] = $arrField[$k];
                            $arrFieldIDKey[$arrField[$k]['sFieldAs']]['key'] = $k;
                        }
                    }
                }

                $isExist = false;
                foreach ($arrField as $k => $arr) {
                    foreach ($_POST['sKeyField'] as $sKeyField) {
                        if (StrTool::equalsIgnoreCase($sKeyField, $arr['sFieldAs'])) {
                            $isExist = true;
                        }
                    }
                }

                if (!$isExist) {
                    $arrMsg[] = "Excel文件的列中并不包含您选中的主键字段。";
                    $bFlag = false;
                }

                $arrMsg['update'] = 0;
                $arrMsg['insert'] = 0;


                if ($bFlag) {
                    foreach ($sheetData as $row => $data) {

                        $arrDataImport = [];

                        $arrDataEmptyCheck = array_unique($data);
                        if (count($arrDataEmptyCheck) == 1 && $arrDataEmptyCheck[0] == "") {
                            continue;
                        }

                        foreach ($arrField as $k => $arr) {
                            $sValue = $data[$k];

                            if (!StrTool::isEmpty($sValue) && stristr($arr['sDataType'],
                                    'list')
                            ) { // 参照字段                           
                                if ($arr['sDataType'] == 'ListTable') {
                                    if (isset($arrObjectCache[$arr['sRefKey']])) {
                                        $refObject = $arrObjectCache[$arr['sRefKey']];
                                    } else {
                                        $refObject = SysObject::findOne($arr['sRefKey']);
                                        $arrObjectCache[$arr['sRefKey']] = $refObject;
                                    }

                                    $ObjectID = ObjectModel::config($refObject)
                                        ->select([$arr['sLinkField']])
                                        ->where([$arr['sShwField'] => $sValue])
                                        ->asArray()
                                        ->one()[$arr['sLinkField']];
                                } elseif ($arr['sDataType'] == 'List' || $arr['sDataType'] == 'MultiList') {
                                    if (isset($arrObjectCache[$arr['sEnumTable']])) {
                                        $refObject = $arrObjectCache[$arr['sEnumTable']];
                                    } else {
                                        $refObject = SysObject::findOne($arr['sEnumTable']);
                                        $arrObjectCache[$arr['sEnumTable']] = $refObject;
                                    }

                                    $arrCond = [];
                                    if ($refObject->sTable == 'SysFieldEnum') {
                                        $arrCond['SysFieldID'] = $arr['sRefKey'];
                                    }

                                    if ($arr['sDataType'] == 'List') {
                                        $arrCond[$arr['sShwField']] = $sValue;

                                        $ObjectID = ObjectModel::config($refObject)
                                            ->select([$arr['sLinkField']])
                                            ->where($arrCond)
                                            ->asArray()
                                            ->one()[$arr['sLinkField']];
                                    } else {
                                        $arrCond[$arr['sShwField']] = explode(";", trim($sValue, ";"));

                                        $arrObject = ObjectModel::config($refObject)
                                            ->select([$arr['sLinkField']])
                                            ->where($arrCond)
                                            ->asArray()
                                            ->all();
                                        $ObjectID = $sComm = "";
                                        foreach ($arrObject as $object) {
                                            $ObjectID .= $sComm . $object[$arr['sLinkField']];
                                            $sComm = ";";
                                        }

                                        $ObjectID = ";" . $ObjectID . ";";
                                    }
                                }

                                if (!$ObjectID || $ObjectID == ";;") {
                                    $arrMsg["[" . $arr['sName'] . "]的值[" . $sValue . "]不存在。"] = "[" . $arr['sName'] . "]的值[" . $sValue . "]不存在。";
                                    $bFlag = false;
                                } else {
                                    $sValue = $ObjectID;
                                }

                            } elseif ($arr['sDataType'] == 'Bool') {
                                if ($sValue == '是') {
                                    $sValue = 1;
                                } else {
                                    $sValue = 0;
                                }
                            }

                            $arrDataImport[$arr['sFieldAs']] = $sValue;
                        }

                        $arrKeyFlt = [];
                        foreach ($_POST['sKeyField'] as $sKeyField) {
                            $arrKeyFlt[$sKeyField] = $arrDataImport[$sKeyField];
                        }

                        $object = ObjectModel::config($sysObject)
                            ->select($sysObject->sIDField)
                            ->where($arrKeyFlt)
                            ->asArray()
                            ->one();
                        if ($object) {
                            ObjectModel::config($sysObject)
                                ->createCommand()
                                ->update($sysObject->sTable, $this->beforeObjectEditSave($sysObject, $arrDataImport),
                                    [$sysObject->sIDField => $object[$sysObject->sIDField]])
                                ->execute();
                            $arrMsg['update']++;
                        } else {
                            ObjectModel::config($sysObject)
                                ->createCommand()
                                ->insert($sysObject->sTable, $this->beforeObjectNewSave($sysObject, $arrDataImport))
                                ->execute();
                            $arrMsg['insert']++;
                        }
                    }
                }
            }
        } catch (\yii\db\Exception $e) {
            //$transaction->rollBack();
            $arrMsg[] = $e->getMessage();
        }

        if ($bFlag) {
            //$transaction->commit();
            $arrMsg['insert'] = "共插入了" . $arrMsg['insert'] . "条记录";
            $arrMsg['update'] = "共更新了" . $arrMsg['update'] . "条记录";
        } else {
            //$transaction->rollBack();
            unset($arrMsg['insert'], $arrMsg['update']);
        }

        $arrData = [];

        $pinyin = new PinYin();
        $arrData['arrObject'] = [];
        $arrObject = SysObject::find()->all();
        foreach ($arrObject as $object) {
            $sPinYin = $pinyin->getFullSpell(mb_convert_encoding($object->sName, "gb2312", "utf-8"), "");
            $arrData['arrObject'][$object->ModuleID][strtoupper($sPinYin[0])][$sPinYin] = $object;
        }

        $arrData['arrModule'] = SysModule::find()->all();
        $arrData['arrMsg'] = $arrMsg;

        return $this->render('home', $arrData);
    }

}