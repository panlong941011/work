<?php

namespace myerm\backend\common\controllers;

use myerm\backend\common\libs\File;
use myerm\backend\common\libs\NewID;
use myerm\backend\common\libs\Pagination;
use myerm\backend\common\libs\StrTool;
use myerm\backend\common\libs\SystemTime;
use myerm\backend\common\models\ObjectModel;
use myerm\backend\system\models\SysAttach;
use myerm\backend\system\models\SysDep;
use myerm\backend\system\models\SysField;
use myerm\backend\system\models\SysList;
use myerm\backend\system\models\SysManualShare;
use myerm\backend\system\models\SysObject;
use myerm\backend\system\models\SysObjectOperator;
use myerm\backend\system\models\SysOrgObject;
use myerm\backend\system\models\SysOrgOperator;
use myerm\backend\system\models\SysRole;
use myerm\backend\system\models\SysTeam;
use myerm\backend\system\models\SysUI;
use myerm\backend\system\models\SysUser;
use myerm\shop\common\models\Product;
use Yii;
use yii\base\UserException;


/**
 * 这个控制器包含了用户的登录验证，权限控制，主页，编辑，新建，详情等，通常的对象控制器都要继承该类。
 */
class ObjectController extends Controller
{
    /**
     * @var string 覆盖定义通用的框架视图
     */
    public $layout = 'layout';

    public $defaultAction = 'home';

    public $enableCsrfValidation = false;

    public $sysObject = null;

    public function beforeAction($action)
    {
        if (parent::beforeAction($action)) {

            //设置layout的路径
            Yii::$app->setLayoutPath('@myerm/backend/common/views/');
            Yii::trace("设置layout的路径：" . \Yii::$app->getLayoutPath());

            //设置view的路径
            Yii::$app->setViewPath('@myerm/backend/' . strtolower($this->sObjectName) . '/views/');

            if (!Yii::$app->backendsession->blogin) {
                header("LOCATION:" . Yii::$app->getHomeUrl() . "/system/login/expire");
                exit;
            }

            $this->sysObject = SysObject::findOne(['sObjectName' => $this->sObjectName]);

            if ($this->sObjectName == '/') {
                header("LOCATION:" . Yii::$app->getHomeUrl() . "/system/login/expire");
                exit;
            }

            /**
             * 检查是否有对象的操作权限
             */
            if (!$this->checkHasOperaPower($action->id)) {

                $sysOperator = SysObjectOperator::findOne([
                    'sObjectName' => $this->sObjectName,
                    'sOperator' => $action->id
                ]);

                $sMsg = \Yii::t('app', '您没有【{0}】的【{1}】操作权限', $this->sysObject->sName, $sysOperator->sName);
                if (\Yii::$app->request->isAjax) {
                    exit($this->renderPartial('@myerm/backend/common/views/nooperapower', ['sMsg' => $sMsg]));
                } else {
                    throw \Yii::$app->errorHandler->exception = new UserException($sMsg);
                }
            }

            return true;
        }

        return false;
    }

    public function checkHasOperaPower($sAction)
    {
        if (!Yii::$app->backendsession->sysrole->UpID) {
            return true;
        }

        // 首先确定这个对象是否有这个动作的操作权限要求
        $sysOperator = SysObjectOperator::findOne([
            'sObjectName' => $this->sObjectName,
            'sOperator' => $sAction
        ]);

        if (!$sysOperator) {
            return true;
        }

        // 先检查人员是否有权限
        $bExists = SysOrgOperator::findOne([
            'sObjectName' => $this->sObjectName,
            'sOrgName' => 'System/SysUser',
            'ObjectID' => Yii::$app->backendsession->SysUserID,
            'sOperator' => $sAction
        ]);

        if (!$bExists) {
            if (Yii::$app->backendsession->SysTeamID) {
                $bExists = SysOrgOperator::findOne([
                    'sObjectName' => $this->sObjectName,
                    'sOrgName' => 'System/SysTeam',
                    'ObjectID' => explode(";", Yii::$app->backendsession->SysTeamID),
                    'sOperator' => $sAction
                ]);
            } else {
                $bExists = false;
            }

            if (!$bExists) {
                // 第二检查人员所在的部门是否有权限
                $bExists = SysOrgOperator::findOne([
                    'sObjectName' => $this->sObjectName,
                    'sOrgName' => 'System/SysDep',
                    'ObjectID' => Yii::$app->backendsession->SysDepID,
                    'sOperator' => $sAction
                ]);

                if (!$bExists) {
                    // 第三检查人员所属的角色是否有权限
                    $bExists = SysOrgOperator::findOne([
                        'sObjectName' => $this->sObjectName,
                        'sOrgName' => 'System/SysRole',
                        'ObjectID' => Yii::$app->backendsession->SysRoleID,
                        'sOperator' => $sAction
                    ]);
                }
            }
        }

        return $bExists;
    }

    public function checkHasOpera($sAction)
    {
        $sysOperator = SysObjectOperator::findOne([
            'sObjectName' => $this->sObjectName,
            'sOperator' => $sAction
        ]);

        if (!$sysOperator) {
            return false;
        } else {
            return true;
        }
    }

    /**
     * 对象的主页
     */
    public function actionHome()
    {
        $data = [];
        $data['sysObject'] = $this->sysObject;
        $data['sHttpParam'] = http_build_query($_GET);
        return $this->render('@myerm/backend/common/views/home', $data);
    }

    /**
     * 获取对象主页的标签，每个标签都带有链接，用于调用视图。
     */
    public function getHomeTabs()
    {
        $data = [];

        //取出所有的视图
        $arrList = SysList::find()
            ->select(["ID", "sName", "bDefault", "sKey", "sNote"])
            ->where(['sObjectName' => $this->sObjectName, 'sType' => 'list', 'bActive' => 1])
            ->orderBy("lPos")
            ->indexBy("ID")->asArray()->all();

        //过滤掉没有显示权限的视图
        $arrOrgObject = SysOrgObject::findAll(['sObjectName' => 'System/SysList', 'ObjectID' => array_keys($arrList)]);
        foreach ($arrOrgObject as $orgObject) {
            if (
                !stristr($orgObject->sRoleID, ";" . Yii::$app->backendsession->SysRoleID . ";")
                && !stristr($orgObject->sDepID, ";" . Yii::$app->backendsession->SysDepID . ";")
                && !stristr($orgObject->sUserID, ";" . Yii::$app->backendsession->SysUserID . ";")
            ) {
                unset($arrList[$orgObject->ObjectID]);
            }
        }

        $data['arrList'] = [];
        foreach ($arrList as $list) {
            $list['sLinkUrl'] = Yii::$app->homeUrl . '/' . strtolower($this->sObjectName) . '/getlisttable?sListKey=' . $list['sKey'] . '&sTabID=' . $list['ID'];
            $data['arrList'][] = $list;
        }

        return $this->renderPartial('@myerm/backend/common/views/hometabs', $data);
    }

    /**
     * 对象的复制
     */
    public function actionClone()
    {
        return $this->actionEdit();
    }

    /**
     * 对象的编辑
     */
    public function actionEdit()
    {
        //获取界面
        $ui = $this->getUI($this->sObjectName, "edit");
//        $ui = SysUI::getUI($this->sObjectName, "edit");
        if (!$ui) {//找不到界面，要报错
            throw new UserException(\Yii::t('app', '找不到编辑的界面，请联系管理员。'));
        }

        $bCheck = $this->beforeEdit($_GET['ID']);
        if ($bCheck !== true) {
            throw new UserException($bCheck);
        }

        $arrData = [];
        $arrData['sysObject'] = $this->sysObject;
        $arrData['arrSysDetailObject'] = SysObject::find()->where([
            'ParentID' => $this->sysObject->sObjectName,
            'bDetailObject' => '1'
        ])->orderBy('lPos')->all();


        $data = SysUI::getUIData($ui, $this->sObjectName, $_GET['ID']);
        foreach ($ui->fieldclass as $fieldclass) {
            foreach ($fieldclass->fields as $f) {
                if ($f->field->sDataType == 'Date' && $f->field->attr['sFieldDataType'] == 'unix' && $data[$f->field->sFieldAs]) {
                    $data[$f->field->sFieldAs] = $f->field->attr['dFormat'] == 'long' ? SystemTime::getLongDate($data[$f->field->sFieldAs],
                        $f->field->attr['lTimeOffset']) : SystemTime::getShortDate($data[$f->field->sFieldAs],
                        $f->field->attr['lTimeOffset']);
                }
            }
        }

        $arrData['ui'] = $ui;
        $arrData['arrUIData'] = $this->editObjectDataPrepare($data);
        $arrData['arrDetailUIData'] = [];

        //带有明细的对象
        if ($arrData['arrSysDetailObject']) {
            foreach ($arrData['arrSysDetailObject'] as $sysDetailObject) {
                //获取界面
                $ui = SysUI::getUI($sysDetailObject->sObjectName, "editdetail");
                if (!$ui) {//找不到界面，要报错
                    $ui = SysUI::getUI($sysDetailObject->sObjectName, "edit");
                    if (!$ui) {
                        throw new UserException("找不到【" . $sysDetailObject->sName . "】编辑的界面，请联系管理员。");
                    }
                }

                $arrData['arrDetailUIData'][$sysDetailObject->sObjectName] = [];
                $arr = ObjectModel::config($sysDetailObject)->select([$sysDetailObject->sIDField . " AS ID"])->where([$this->sysObject->sLinkSubObjectField => $_GET['ID']])->asArray()->all();
                foreach ($arr as $v) {
                    $data = SysUI::getUIData($ui, $sysDetailObject->sObjectName, $v['ID']);
                    $data['ID'] = $v['ID'];
                    foreach ($ui->fieldclass as $fieldclass) {
                        foreach ($fieldclass->fields as $f) {
                            if ($f->field->sDataType == 'Date' && $f->field->attr['sFieldDataType'] == 'unix' && $data[$f->field->sFieldAs]) {
                                $data[$f->field->sFieldAs] = $f->field->attr['dFormat'] == 'long' ? SystemTime::getLongDate($data[$f->field->sFieldAs],
                                    $f->field->attr['lTimeOffset']) : SystemTime::getShortDate($data[$f->field->sFieldAs],
                                    $f->field->attr['lTimeOffset']);
                            }
                        }
                    }

                    $arrData['arrDetailUIData'][$sysDetailObject->sObjectName][] = $data;
                }

                $arrData['arrDetailUI'][$sysDetailObject->sObjectName] = $ui;
            }
        }

        $bCheck = $this->afterEdit($_GET['ID']);
        if ($bCheck !== true) {
            throw new UserException($bCheck);
        }

        $arrData['arrErrMsg'] = [];

        return $this->render('@app/common/views/new', $arrData);
    }

    public function getUI($sObjectName, $sAction)
    {
        return SysUI::getUI($sObjectName, $sAction);
    }


    /**
     * 在编辑对象之前，调用此方法
     * @param String $ID
     * @return boolean
     */
    protected function beforeEdit($ID)
    {
        if (!$this->bObjectPermit($this->sObjectName, Yii::$app->backendsession->SysUserID, $ID, 'edit')) {
            return \Yii::t('app', '您没有编辑的数据权限');
        }

        if ($this->sObjectName == 'shop/product' && Yii::$app->backendsession->SysRoleID != 1) {
            $product = Product::findOne($ID);
            if ($product->bCheck) {
                return \Yii::t('app', '您没有编辑的数据权限,请联系管理员！');
            }
        }


        return true;
    }

    /**
     * 在编辑对象时，回调这个方法提前处理填充表单的数据。这个方法可由开发者继承，做特殊化处理。
     * @param Array $objectData
     */
    protected function editObjectDataPrepare($objectData)
    {


        return $objectData;
    }

    /**
     * 在编辑对象之后，调用此方法
     * @param String $ID
     * @return boolean
     */
    protected function afterEdit($ID)
    {
        return true;
    }

    /**
     * 对象的复制保存
     */
    public function actionClonesave()
    {
        return $this->actionNewsave();
    }

    /**
     * 对象的新建保存
     */
    public function actionNewsave()
    {
        //获取界面
        $ui = SysUI::getUI($this->sObjectName, "new");
        if (!$ui) {//找不到界面，要报错
            throw new UserException(\Yii::t('app', '找不到新建的界面，请联系管理员。'));
        }

        $arrErrMsg = [];
        $arrPostData = Yii::$app->request->post('arrObjectData');
        $arrObjectData = [];
        foreach ($ui->fieldclass as $fieldclass) {
            foreach ($fieldclass->fields as $f) {
                $sValue = $arrPostData[$f->field->sObjectName][$f->field->sFieldAs];
                $check = $this->newObjectFieldValidate($f->field, $sValue);
                if ($check['bSuccess']) {

                    if ($f->field->sDataType == "MultiList" && $sValue) {
                        $sValue = ";" . implode(";", $sValue) . ";";
                    }

                    if ($f->field->bEnableRTE || $f->field->sDataType == "Text") {
                        $sValue = html_entity_decode($sValue);
                    }

                    $arrObjectData[$f->field->sObjectName][$f->field->sFieldAs] = $sValue;

                    if ($f->field->sDataType == 'AttachFile') {
                        $arrObjectData[$f->field->sObjectName][$f->field->sLinkField] = $arrPostData[$f->field->sObjectName][$f->field->sLinkField];
                    } elseif ($f->field->sDataType == 'Date' && $f->field->attr['sFieldDataType'] == 'unix' && $sValue) {
                        $arrObjectData[$f->field->sObjectName][$f->field->sFieldAs] = strtotime($sValue) + (SystemTime::getTimeOffset() - $f->field->attr['lTimeOffset']) * 3600;
                    }

                } else {
                    $arrErrMsg[$check['sFieldAs']] = ['sFieldAs' => $check['sFieldAs'], 'sMsg' => $check['sMsg']];
                }
            }
        }

        //明细对象
        $sysDetailObject = SysObject::findOne(['ParentID' => $this->sysObject->sObjectName, 'bDetailObject' => '1']);
        if ($sysDetailObject) {
            $detailUI = SysUI::getUI($sysDetailObject->sObjectName, "newdetail");
            if (!$detailUI) {//找不到界面，要报错
                $detailUI = SysUI::getUI($sysDetailObject->sObjectName, "new");
                if (!$detailUI) {
                    throw new UserException(\Yii::t('app', '找不到新建的界面，请联系管理员。'));
                }
            }

            foreach ($detailUI->fieldclass as $fieldclass) {
                foreach ($fieldclass->fields as $f) {
                    foreach ($arrPostData[$f->field->sObjectName][$f->field->sFieldAs] as $lKey => $sValue) {
                        $check = $this->newObjectFieldValidate($f->field, $sValue);
                        if ($check['bSuccess']) {

                            if ($f->field->sDataType == "MultiList" && $sValue) {
                                $sValue = ";" . implode(";", $sValue) . ";";
                            }

                            if ($f->field->bEnableRTE || $f->field->sDataType == "Text") {
                                $sValue = html_entity_decode($sValue);
                            }

                            $arrObjectData[$f->field->sObjectName][$lKey][$f->field->sFieldAs] = $sValue;

                            if ($f->field->sDataType == 'AttachFile') {
                                $arrObjectData[$f->field->sObjectName][$lKey][$f->field->sLinkField] = $arrPostData[$f->field->sObjectName][$f->field->sLinkField][$lKey];
                            } elseif ($f->field->sDataType == 'Date' && $f->field->attr['sFieldDataType'] == 'unix' && $sValue) {
                                $arrObjectData[$f->field->sObjectName][$lKey][$f->field->sFieldAs] = strtotime($sValue) + (SystemTime::getTimeOffset() - $f->field->attr['lTimeOffset']) * 3600;
                            }

                        } else {
                            $arrErrMsg[$check['sFieldAs']] = [
                                'sFieldAs' => $check['sFieldAs'],
                                'sMsg' => $check['sMsg']
                            ];
                        }
                    }
                }
            }
        }

        //没有错误，保存数据
        if (!$arrErrMsg) {
            $arrTrans = [];
            foreach ($arrObjectData as $sObjectName => $objectData) {
                $sysObject = SysObject::findOne(['sObjectName' => $sObjectName]);

                $db = $sysObject->dbconn;

                //准备事务
                $transaction = $db->beginTransaction();
                $arrTrans[] = $transaction;

                try {

                    if (StrTool::equalsIgnoreCase($sObjectName, $this->sObjectName)) {
                        //主对象
                        $objectData = $this->beforeObjectNewSave($sysObject, $objectData);

                        $db->createCommand()->insert($sysObject->sTable, $objectData)->execute();

                        $ID = $db->getLastInsertID();
                        if ($sysObject->pkfield->sPrimartKeyType == 'autoincrement') {

                        } else {
                            $ID = $objectData[$sysObject->sIDField];
                        }

                        $this->afterObjectNewSave($sysObject, $ID);

                        $NewID = $ID;
                    } elseif (StrTool::equalsIgnoreCase($sObjectName, $sysDetailObject->sObjectName)) {
                        //明细对象
                        foreach ($objectData as $data) {
                            $data = $this->beforeObjectNewSave($sysObject, $data);
                            $data[$this->sysObject->sLinkSubObjectField] = $NewID;

                            $db->createCommand()->insert($sysObject->sTable, $data)->execute();

                            $ID = $db->getLastInsertID();
                            if ($sysObject->pkfield->sPrimartKeyType == 'autoincrement') {

                            } else {
                                $ID = $data[$sysObject->sIDField];
                            }

                            $this->afterObjectNewSave($sysObject, $ID);
                        }
                    }

                } catch (\yii\db\Exception $e) {
                    $transaction->rollBack();
                    $arrErrMsg[] = ['sMsg' => $e->getMessage()];
                }
            }
        }

        //没有错误，再统一提交
        if (!$arrErrMsg) {
            foreach ($arrTrans as $transaction) {
                $transaction->commit();
            }

            return $this->redirect(Yii::$app->homeUrl . "/" . $this->sObjectName . "/view?ID=" . $NewID);
        } else {
            //如果出错，需要把提交的值填回表单项去
            $arrData = [];
            $arrData['sysObject'] = $this->sysObject;
            $arrData['sysDetailObject'] = SysObject::findOne(['ParentID' => $this->sysObject->sObjectName]);


            //主对象
            $data = [];
            foreach ($ui->fieldclass as $fieldclass) {
                foreach ($fieldclass->fields as $f) {
                    $sValue = $arrPostData[$f->field->sObjectName][$f->field->sFieldAs];
                    if ($f->field->sDataType == 'ListTable') {
                        $data[$f->field->sFieldAs] = ObjectModel::config($f->field->refobject)
                            ->select([
                                $f->field->refobject->sIDField . " AS ID",
                                $f->field->refobject->sNameField . " AS sName"
                            ])
                            ->where([$f->field->refobject->sIDField => $sValue])
                            ->asArray()
                            ->one();
                    } elseif ($f->field->sDataType == 'List') {
                        $data[$f->field->sFieldAs] = ['ID' => $sValue];
                    } elseif ($f->field->sDataType == 'MultiList') {
                        $arrValue = explode(";", trim($sValue, ";"));
                        foreach ($arrValue as $sValue) {
                            $data[$f->field->sFieldAs][] = ['ID' => $sValue];
                        }
                    } else {
                        $data[$f->field->sFieldAs] = $sValue;

                        if ($f->field->sDataType == 'AttachFile') {
                            $data[$f->field->sLinkField] = $arrObjectData[$f->field->sObjectName][$f->field->sLinkField];
                        }
                    }
                }
            }

            $arrData['arrUIData'] = $this->saveErrorObjectDataPrepare($data);
            $arrData['ui'] = $ui;

            //明细的对象
            if ($arrData['sysDetailObject']) {
                //获取界面
                $ui = SysUI::getUI($arrData['sysDetailObject']->sObjectName, "newdetail");
                if (!$ui) {//找不到界面，要报错
                    $ui = SysUI::getUI($arrData['sysDetailObject']->sObjectName, "new");
                    if (!$ui) {
                        throw new UserException("找不到【" . $arrData['sysDetailObject']->sName . "】新建的界面，请联系管理员。");
                    }
                }
                $arrData['detailUI'] = $ui;

                $arrData['arrDetailUIData'] = [];
                foreach ($arrObjectData[$arrData['sysDetailObject']->sObjectName] as $objectData) {

                    $data = [];
                    foreach ($ui->fieldclass as $fieldclass) {
                        foreach ($fieldclass->fields as $f) {
                            $sValue = $objectData[$f->field->sFieldAs];
                            if ($f->field->sDataType == 'ListTable') {
                                $data[$f->field->sFieldAs] = ObjectModel::config($f->field->refobject)
                                    ->select([
                                        $f->field->refobject->sIDField . " AS ID",
                                        $f->field->refobject->sNameField . " AS sName"
                                    ])
                                    ->where([$f->field->refobject->sIDField => $sValue])
                                    ->asArray()
                                    ->one();
                            } elseif ($f->field->sDataType == 'List') {
                                $data[$f->field->sFieldAs] = ['ID' => $sValue];
                            } elseif ($f->field->sDataType == 'MultiList') {
                                $arrValue = explode(";", trim($sValue, ";"));
                                foreach ($arrValue as $sValue) {
                                    $data[$f->field->sFieldAs][] = ['ID' => $sValue];
                                }
                            } else {
                                $data[$f->field->sFieldAs] = $sValue;

                                if ($f->field->sDataType == 'AttachFile') {
                                    $data[$f->field->sLinkField] = $objectData[$f->field->sLinkField];
                                }
                            }
                        }
                    }

                    $arrData['arrDetailUIData'][] = $data;
                }
            }

            $arrData['arrErrMsg'] = $arrErrMsg;

            return $this->render('@myerm/backend/common/views/new', $arrData);
        }
    }

    /**
     * 对象新建/编辑保存时，字段值的检查。这个方法可由开发者继承。
     * @param String $sActon :(new|edit)
     * @param SysField $field
     * @param mixed $value
     */
    protected function newObjectFieldValidate($field, $value)
    {
        //检查必填
        if ($field->bNull && StrTool::isEmpty($value)) {
            return ['bSuccess' => false, 'sFieldAs' => $field->sFieldAs, 'sMsg' => $field->sName . "是必填字段"];
        } elseif ($field->sDataType == 'Date' && $field->attr['sFieldDataType'] == 'unix' && !StrTool::isEmpty($value) && strtotime($value) === false) {
            return ['bSuccess' => false, 'sFieldAs' => $field->sFieldAs, 'sMsg' => $field->sName . "输入的时间格式有误"];
        }

        return ['bSuccess' => true, 'sFieldAs' => $field->sFieldAs];
    }

    /**
     * 在对象保存插入之前，系统会调用这个方法，对对象的数据进行处理。这个方法可由开发者继承，做特殊化处理。
     * @param SysObject $sysObject
     * @param Array $objectData
     */
    protected function beforeObjectNewSave($sysObject, $arrObjectData)
    {
        //主键
        $arrField = SysField::find()->where(['sObjectName' => $sysObject->sObjectName, 'bPrimaryKey' => 1])->all();
        foreach ($arrField as $field) {
            if ($field->sPrimartKeyType == "autoincrement") {
                //如果主键是自增字段，可以忽略
            } elseif ($field->sPrimartKeyType == "system") {
                $arrObjectData[$field->sFieldAs] = NewID::make();
            }
        }

        //系统保留字段
        $arrField = SysField::find()->where([
            'sObjectName' => $sysObject->sObjectName,
            'sFieldAs' => ['dNewDate', 'dEditDate', 'OwnerID', 'NewUserID', 'EditUserID']
        ])->all();
        foreach ($arrField as $field) {
            if ($field->sDataType == "Date") {
                $arrObjectData[$field->sFieldAs] = $field->attr['sFieldDataType'] == 'unix' ? time() : SystemTime::getCurLongDate($field->attr['lTimeOffset']);
            } else {
                if($field->sLinkField) {
                    $arrObjectData[$field->sFieldAs] = Yii::$app->backendsession->sysuser->{$field->sLinkField};
                }
            }
        }

        return $arrObjectData;
    }

    /**
     * 当新建对象保存成功之后，回调这个方法。
     * @param SysObject $sysObject
     * @param String $ID
     */
    protected function afterObjectNewSave($sysObject, $ID)
    {
        return true;
    }

    /**
     * 在保存对象时，如果出错了会重新跳回新建的界面，表单数据需要填充用户填写的数据，回调这个方法可以处理用户提交表单的数据。这个方法可由开发者继承，做特殊化处理。
     * @param Array $objectData
     */
    protected function saveErrorObjectDataPrepare($objectData)
    {
        return $objectData;
    }

    /**
     * 对象的新建
     */
    public function actionNew()
    {
        //获取界面
        $ui = SysUI::getUI($this->sObjectName, "new");
        if (!$ui) {//找不到界面，要报错
            throw new UserException("找不到【" . $this->sysObject->sName . "】新建的界面，请联系管理员。");
        }

        $bCheck = $this->beforeNew();
        if ($bCheck !== true) {
            throw new UserException($bCheck);
        }

        $arrData = [];
        $arrData['sysObject'] = $this->sysObject;
        $arrData['arrSysDetailObject'] = SysObject::find()->where([
            'ParentID' => $this->sysObject->sObjectName,
            'bDetailObject' => '1'
        ])->orderBy('lPos')->all();

        $data = [];
        foreach ($ui->fieldclass as $fieldclass) {
            foreach ($fieldclass->fields as $f) {
                if ($f->field->sDataType == 'Text' || $f->field->sDataType == 'Int' || $f->field->sDataType == 'Float') {
                    $data[$f->field->sFieldAs] = $f->field->sDefValue;
                } elseif ($f->field->sDataType == 'Date') {
                    if ($f->field->sDefValue == '?curdate?') {
                        $data[$f->field->sFieldAs] = $f->field->attr['dFormat'] == 'long' ? SystemTime::getLongDate(time(),
                            $f->field->attr['lTimeOffset']) : SystemTime::getShortDate(time(),
                            $f->field->attr['lTimeOffset']);
                    }
                } elseif ($f->field->sDataType == 'ListTable') {
                    if ($f->field->sDefValue == '?curuserid?') {
                        $data[$f->field->sFieldAs] = [
                            'ID' => Yii::$app->session2->SysUserID,
                            'sName' => Yii::$app->session2->sysuser->sName
                        ];
                    }
                }
            }
        }
        $arrData['arrUIData'] = $this->newObjectPrepare($data);
        $arrData['arrDetailUIData'] = [];
        $arrData['arrDetailUI'] = [];
        $arrData['ui'] = $ui;

        //带有明细的对象
        if ($arrData['arrSysDetailObject']) {
            foreach ($arrData['arrSysDetailObject'] as $sysDetailObject) {
                //获取界面
                $ui = SysUI::getUI($sysDetailObject->sObjectName, "newdetail");
                if (!$ui) {//找不到界面，要报错
                    $ui = SysUI::getUI($sysDetailObject->sObjectName, "new");
                    if (!$ui) {
                        throw new UserException("找不到【" . $sysDetailObject->sName . "】新建的界面，请联系管理员。");
                    }
                }

                $data = [];
                foreach ($ui->fieldclass as $fieldclass) {
                    foreach ($fieldclass->fields as $f) {
                        if (
                            $f->field->sDataType == 'Text'
                            || $f->field->sDataType == 'Int'
                            || $f->field->sDataType == 'Float'
                        ) {
                            $data[$f->field->sFieldAs] = $f->field->sDefValue;
                        } elseif ($f->field->sDataType == 'Date') {
                            if ($f->field->sDefValue == '?curdate?') {
                                $data[$f->field->sFieldAs] = $f->field->attr['dFormat'] == 'long' ? SystemTime::getLongDate(time(),
                                    $f->field->attr['lTimeOffset']) : SystemTime::getShortDate(time(),
                                    $f->field->attr['lTimeOffset']);
                            }
                        } elseif ($f->field->sDataType == 'ListTable') {
                            if ($f->field->sDefValue == '?curuserid?') {
                                $data[$f->field->sFieldAs] = [
                                    'ID' => Yii::$app->session2->SysUserID,
                                    'sName' => Yii::$app->session2->sysuser->sName
                                ];
                            }
                        }
                    }
                }

                $arrData['arrDetailUI'][$sysDetailObject->sObjectName] = $ui;
            }
        }

        $bCheck = $this->afterNew();
        if ($bCheck !== true) {
            throw new UserException($bCheck);
        }

        $arrData['arrErrMsg'] = [];

        return $this->render('@myerm/backend/common/views/new', $arrData);
    }

    /**
     * 在新建对象之前调用此方法
     * @return boolean
     */
    protected function beforeNew()
    {
        return true;
    }

    /**
     * 在新建对象时，回调这个方法提前处理填充表单的数据。这个方法可由开发者继承，做特殊化处理。
     * @param Array $objectData
     */
    protected function newObjectPrepare($objectData)
    {
        $objectData['OwnerID'] = [
            'ID' => Yii::$app->backendsession->sysuser->ID,
            'sName' => Yii::$app->backendsession->sysuser->sName
        ];

        return $objectData;
    }

    /**
     * 在新建对象之前调用此方法
     * @return boolean
     */
    protected function afterNew()
    {
        return true;
    }

    /**
     * 对象的编辑保存
     */
    public function actionEditsave()
    {
        //获取界面
        $ui = SysUI::getUI($this->sObjectName, "edit");
        if (!$ui) {//找不到界面，要报错
            throw new UserException(\Yii::t('app', '找不到编辑的界面，请联系管理员。'));
        }

        $arrErrMsg = [];
        $arrPostData = Yii::$app->request->post('arrObjectData');
        $arrObjectData = [];
        foreach ($ui->fieldclass as $fieldclass) {
            foreach ($fieldclass->fields as $f) {
                $sValue = $arrPostData[$f->field->sObjectName][$f->field->sFieldAs];
                $check = $this->editObjectFieldValidate($_POST['ID'], $f->field, $sValue);
                if ($check['bSuccess']) {

                    if ($f->field->sDataType == "MultiList" && $sValue) {
                        $sValue = ";" . implode(";", $sValue) . ";";
                    }

                    if ($f->field->bEnableRTE || $f->field->sDataType == "Text") {
                        $sValue = html_entity_decode($sValue);
                    }

                    $arrObjectData[$f->field->sObjectName][$f->field->sFieldAs] = $sValue;

                    if ($f->field->sDataType == 'AttachFile') {
                        $arrObjectData[$f->field->sObjectName][$f->field->sLinkField] = $arrPostData[$f->field->sObjectName][$f->field->sLinkField];
                    } elseif ($f->field->sDataType == 'Date' && $f->field->attr['sFieldDataType'] == 'unix' && $sValue) {
                        $arrObjectData[$f->field->sObjectName][$f->field->sFieldAs] = strtotime($sValue) + (SystemTime::getTimeOffset() - $f->field->attr['lTimeOffset']) * 3600;
                    }

                } else {
                    $arrErrMsg[$check['sFieldAs']] = ['sFieldAs' => $check['sFieldAs'], 'sMsg' => $check['sMsg']];
                }
            }
        }

        //明细对象
        $sysDetailObject = SysObject::findOne(['ParentID' => $this->sysObject->sObjectName, 'bDetailObject' => '1']);
        if ($sysDetailObject) {
            $detailUI = SysUI::getUI($sysDetailObject->sObjectName, "editdetail");
            if (!$detailUI) {//找不到界面，要报错
                $detailUI = SysUI::getUI($sysDetailObject->sObjectName, "edit");
                if (!$detailUI) {
                    throw new UserException(\Yii::t('app', '找不到编辑的界面，请联系管理员。'));
                }
            }

            foreach ($detailUI->fieldclass as $fieldclass) {
                foreach ($fieldclass->fields as $f) {
                    foreach ($arrPostData[$f->field->sObjectName][$f->field->sFieldAs] as $lKey => $sValue) {

                        $ID = $arrPostData[$f->field->sObjectName]['ID'][$lKey];
                        if ($ID) {
                            $check = $this->editObjectFieldValidate($ID, $f->field, $sValue);
                        } else {
                            $check = $this->newObjectFieldValidate($f->field, $sValue);
                        }

                        if ($check['bSuccess']) {

                            if ($f->field->sDataType == "MultiList" && $sValue) {
                                $sValue = ";" . implode(";", $sValue) . ";";
                            }

                            if ($f->field->bEnableRTE || $f->field->sDataType == "Text") {
                                $sValue = html_entity_decode($sValue);
                            }

                            $arrObjectData[$f->field->sObjectName][$lKey][$f->field->sFieldAs] = $sValue;

                            if ($f->field->sDataType == 'AttachFile') {
                                $arrObjectData[$f->field->sObjectName][$lKey][$f->field->sLinkField] = $arrPostData[$f->field->sObjectName][$f->field->sLinkField][$lKey];
                            } elseif ($f->field->sDataType == 'Date' && $f->field->attr['sFieldDataType'] == 'unix' && $sValue) {
                                $arrObjectData[$f->field->sObjectName][$lKey][$f->field->sFieldAs] = strtotime($sValue) + (SystemTime::getTimeOffset() - $f->field->attr['lTimeOffset']) * 3600;
                            } elseif ($f->field->sDataType == 'Bool') {
                                $arrObjectData[$f->field->sObjectName][$lKey][$f->field->sFieldAs] = intval($sValue);
                            }

                            $arrObjectData[$f->field->sObjectName][$lKey]['ID'] = $ID;

                        } else {
                            $arrErrMsg[$check['sFieldAs']] = [
                                'sFieldAs' => $check['sFieldAs'],
                                'sMsg' => $check['sMsg']
                            ];
                        }
                    }
                }
            }

            if (!$arrObjectData[$sysDetailObject->sObjectName]['ID']) {
                $arrObjectData[$sysDetailObject->sObjectName][] = ['ID' => -1];
            }
        }


        //没有错误，保存数据
        if (!$arrErrMsg) {
            $arrTrans = [];
            foreach ($arrObjectData as $sObjectName => $objectData) {
                $sysObject = SysObject::findOne(['sObjectName' => $sObjectName]);

                $db = $sysObject->dbconn;

                //准备事务
                $transaction = $db->beginTransaction();
                $arrTrans[] = $transaction;

                try {
                    //主对象
                    if ($sObjectName == $this->sysObject->sObjectName) {
                        $objectData = $this->beforeObjectEditSave($sysObject, $_POST['ID'], $objectData);
                        $db->createCommand()->update($sysObject->sTable, $objectData,
                            [$sysObject->sIDField => $_POST['ID']])->execute();
                        $this->afterObjectEditSave($sysObject, $_POST['ID']);
                    } elseif ($sObjectName == $sysDetailObject->sObjectName) {
                        //明细对象

                        //删除数据
                        $arrObjectDel = $sysObject->dbconn->createCommand("SELECT " . $sysObject->sIDField . " AS ID FROM " . $sysObject->sTable . " WHERE " . $this->sysObject->sLinkSubObjectField . "='{$_POST['ID']}' AND $sysObject->sIDField NOT IN ('" . implode("','",
                                $arrPostData[$sObjectName]['ID']) . "')")->queryAll();
                        foreach ($arrObjectDel as $objectDel) {
                            $db->createCommand()->delete($sysObject->sTable,
                                [$sysObject->sIDField => $objectDel['ID']])->execute();
                        }

                        foreach ($objectData as $data) {
                            if ($data['ID'] == -1) {//不做任何动作

                            } elseif ($data['ID']) {//更新数据
                                $data = $this->beforeObjectEditSave($sysObject, $data['ID'], $data);

                                $ID = $data['ID'];
                                unset($data['ID']);

                                $db->createCommand()->update($sysObject->sTable, $data,
                                    [$sysObject->sIDField => $ID])->execute();

                                $this->afterObjectEditSave($sysObject, $data['ID']);
                            } else {//插入新数据
                                unset($data['ID']);
                                $data = $this->beforeObjectNewSave($sysObject, $data);
                                $data[$this->sysObject->sLinkSubObjectField] = $_POST['ID'];

                                $db->createCommand()->insert($sysObject->sTable, $data)->execute();

                                $ID = $db->getLastInsertID();
                                if ($sysObject->pkfield->sPrimartKeyType == 'autoincrement') {

                                } else {
                                    $ID = $data[$sysObject->sIDField];
                                }

                                $this->afterObjectNewSave($sysObject, $ID);
                            }
                        }
                    }

                } catch (\yii\db\Exception $e) {
                    $transaction->rollBack();
                    $arrErrMsg[] = ['sMsg' => $e->getMessage()];
                }
            }
        }

        //没有错误，再统一提交
        if (!$arrErrMsg) {
            foreach ($arrTrans as $transaction) {
                $transaction->commit();
            }

            $this->afterEditSave();

            return $this->redirect(Yii::$app->homeUrl . "/" . $this->sObjectName . "/view?ID=" . $_POST['ID']);
        } else {
            //如果出错，需要把提交的值填回表单项去
            $arrData = [];

            $arrData['sysObject'] = $this->sysObject;
            $arrData['sysDetailObject'] = SysObject::findOne(['ParentID' => $this->sysObject->sObjectName]);


            $data = [];
            foreach ($ui->fieldclass as $fieldclass) {
                foreach ($fieldclass->fields as $f) {
                    $sValue = $arrPostData[$f->field->sObjectName][$f->field->sFieldAs];
                    if ($f->field->sDataType == 'ListTable') {
                        $data[$f->field->sFieldAs] = ObjectModel::config($f->field->refobject)
                            ->select([
                                $f->field->refobject->sIDField . " AS ID",
                                $f->field->refobject->sNameField . " AS sName"
                            ])
                            ->where([$f->field->refobject->sIDField => $sValue])
                            ->asArray()
                            ->one();
                    } elseif ($f->field->sDataType == 'List') {
                        $data[$f->field->sFieldAs] = ['ID' => $sValue];
                    } elseif ($f->field->sDataType == 'MultiList') {
                        $arrValue = explode(";", trim($sValue, ";"));
                        foreach ($arrValue as $sValue) {
                            $data[$f->field->sFieldAs][] = ['ID' => $sValue];
                        }
                    } else {
                        $data[$f->field->sFieldAs] = $sValue;

                        if ($f->field->sDataType == 'AttachFile') {
                            $data[$f->field->sLinkField] = $arrObjectData[$f->field->sObjectName][$f->field->sLinkField];
                        }
                    }
                }
            }

            $arrData['arrUIData'] = $this->saveErrorObjectDataPrepare($data);
            $arrData['ui'] = $ui;

            //明细的对象
            if ($arrData['sysDetailObject']) {
                //获取界面
                $ui = SysUI::getUI($arrData['sysDetailObject']->sObjectName, "editdetail");
                if (!$ui) {//找不到界面，要报错
                    $ui = SysUI::getUI($arrData['sysDetailObject']->sObjectName, "edit");
                    if (!$ui) {
                        throw new UserException("找不到【" . $arrData['sysDetailObject']->sName . "】编辑的界面，请联系管理员。");
                    }
                }
                $arrData['detailUI'] = $ui;

                $arrData['arrDetailUIData'] = [];
                foreach ($arrObjectData[$arrData['sysDetailObject']->sObjectName] as $objectData) {

                    $data = [];
                    foreach ($ui->fieldclass as $fieldclass) {
                        foreach ($fieldclass->fields as $f) {
                            $sValue = $objectData[$f->field->sFieldAs];
                            if ($f->field->sDataType == 'ListTable') {
                                $data[$f->field->sFieldAs] = ObjectModel::config($f->field->refobject)
                                    ->select([
                                        $f->field->refobject->sIDField . " AS ID",
                                        $f->field->refobject->sNameField . " AS sName"
                                    ])
                                    ->where([$f->field->refobject->sIDField => $sValue])
                                    ->asArray()
                                    ->one();
                            } elseif ($f->field->sDataType == 'List') {
                                $data[$f->field->sFieldAs] = ['ID' => $sValue];
                            } elseif ($f->field->sDataType == 'MultiList') {
                                $arrValue = explode(";", trim($sValue, ";"));
                                foreach ($arrValue as $sValue) {
                                    $data[$f->field->sFieldAs][] = ['ID' => $sValue];
                                }
                            } else {
                                $data[$f->field->sFieldAs] = $sValue;

                                if ($f->field->sDataType == 'AttachFile') {
                                    $data[$f->field->sLinkField] = $objectData[$f->field->sLinkField];
                                }
                            }
                        }
                    }

                    $arrData['arrDetailUIData'][] = $data;
                }
            }


            $arrData['arrErrMsg'] = $arrErrMsg;

            return $this->render('@myerm/backend/common/views/new', $arrData);
        }
    }

    /**
     * 对象新建/编辑保存时，字段值的检查。这个方法可由开发者继承。
     * @param SysField $field
     * @param mixed $value
     */
    protected function editObjectFieldValidate($ID, $field, $value)
    {
        //检查必填
        if ($field->bNull && StrTool::isEmpty($value)) {
            return ['bSuccess' => false, 'sFieldAs' => $field->sFieldAs, 'sMsg' => $field->sName . "是必填字段"];
        } elseif ($field->sDataType == 'Date' && $field->attr['sFieldDataType'] == 'unix' && !StrTool::isEmpty($value) && strtotime($value) === false) {
            return ['bSuccess' => false, 'sFieldAs' => $field->sFieldAs, 'sMsg' => $field->sName . "输入的时间格式有误"];
        }

        return ['bSuccess' => true, 'sFieldAs' => $field->sFieldAs];
    }

    /**
     * 在对象保存编辑之前，系统会调用这个方法，对对象的数据进行处理。这个方法可由开发者继承，做特殊化处理。
     * @param SysObject $sysObject
     * @param String $ID
     * @param Array $arrObjectData
     */
    protected function beforeObjectEditSave($sysObject, $ID, $arrObjectData)
    {
        //系统保留字段
        $arrField = SysField::find()->where([
            'sObjectName' => $sysObject->sObjectName,
            'sFieldAs' => ['dEditDate', 'EditUserID']
        ])->all();
        foreach ($arrField as $field) {
            if ($field->sDataType == "Date") {
                $arrObjectData[$field->sFieldAs] = $field->attr['sFieldDataType'] == 'unix' ? time() : SystemTime::getCurLongDate($field->attr['lTimeOffset']);
            } else {
                $arrObjectData[$field->sFieldAs] = Yii::$app->backendsession->sysuser->{$field->sLinkField};
            }
        }

        return $arrObjectData;
    }


    protected function afterEditSave()
    {
        return true;
    }

    /**
     * 当编辑对象保存成功之后，回调这个方法。
     * @param SysObject $sysObject
     * @param String $ID
     */
    protected function afterObjectEditSave($sysObject, $ID)
    {
        return true;
    }

    public function actionShowref()
    {
        $arrData = [];

        $field = SysField::findOne(['sObjectName' => $_GET['sObjectName'], 'sFieldAs' => $_GET['sFieldAs']]);

        /*         if ($this->sObjectName == 'system/sysuser') {
                    $arrDep = SysDep::find()->select(['lID', 'sName'])->where(['bActive'=>1])->asArray()->all();
                    $arrUser = SysUser::find()->select([$field->sLinkField.' AS ID', 'sName', 'SysDepID'])->where(['bActive'=>1])->asArray()->all();

                    foreach ($arrDep as $i => $dep) {
                        foreach ($arrUser as $user) {
                            if ($dep['lID'] == $user['SysDepID']) {
                                $arrDep[$i]['arrUser'][] = $user;
                            }
                        }
                    }

                    $arrData['sysObject'] = SysObject::findOne(['sObjectName'=>$this->sObjectName]);
                    $arrData['arrDep'] = $arrDep;
                    return $this->renderPartial('@myerm/backend/common/views/showuserref', $arrData);
                } else { */

        $arrData['list'] = SysList::findOne(['sObjectName' => $this->sObjectName, 'sType' => 'refer']);
        if (!$arrData['list']) {
            $arrData['list'] = SysList::findOne([
                'sObjectName' => $this->sObjectName,
                'sType' => 'list',
                'bDefault' => '1'
            ]);
        }

        $arrData['sysObject'] = $arrData['list']->sysobject;
        $arrData['list']->bSingle = true;

        return $this->renderPartial('@myerm/backend/common/views/showref', $arrData);
        /* }  */
    }

    public function actionRefsave()
    {
        $field = SysField::findOne(['sObjectName' => $_POST['sObjectName'], 'sFieldAs' => $_POST['sFieldAs']]);

        if ($field) {
            $data = ObjectModel::config($this->sysObject)
                ->select([$field->sLinkField . " AS ID", $field->sShwField . " AS sName"])
                ->where([$this->sysObject->sIDField => $_POST['ID']])
                ->asArray()
                ->one();
        } else {
            $data = ObjectModel::config($this->sysObject)
                ->select([$this->sysObject->sIDField . " AS ID", $this->sysObject->sNameField . " AS sName"])
                ->where([$this->sysObject->sIDField => $_POST['ID']])
                ->asArray()
                ->one();
        }

        return json_encode($data);
    }

    public function actionGetlisttable()
    {
        $data = [];

        if (Yii::$app->request->get('sListKey')) {
            $data['list'] = SysList::find()->where("sKey='" . Yii::$app->request->get('sListKey') . "'")->one();
        } else {
            $data['list'] = SysList::find()->where("ID='" . Yii::$app->request->get('ListID') . "'")->one();
        }

        $data['sysObject'] = $data['list']->sysobject;

        return $this->renderPartial('@myerm/backend/common/views/listtable', $data);
    }

    public function actionGetlistdata()
    {
        $arrConfig = $_POST;
        $sysList = SysList::find()->where("sKey='" . Yii::$app->request->post('sListKey') . "'")->one();

        if (!StrTool::isEmpty(Yii::$app->request->post('sSearchKeyWord'))) {
            $arrConfig['arrFlt'][] = [
                'sField' => $sysList->sysobject->sNameField,
                'sOper' => 'center',
                'sValue' => Yii::$app->request->post('sSearchKeyWord')
            ];
        }

        if ($_POST['arrSearchField']) {
            foreach ($_POST['arrSearchField'] as $sField => $arr) {
                $arrConfig['arrFlt'][] = ['sField' => $sField, 'sOper' => $arr['sOper'], 'sValue' => $arr['sValue']];
            }
        }

        $arrConfig = $this->listDataConfig($sysList, $arrConfig);

        $data = $sysList->getListdata($arrConfig);

        $data['sysobject'] = $sysList->sysobject;
        $data['list'] = $sysList;
        $data['lPage'] = $_POST['lPage'];
        $data['arrData'] = $this->formatListData($data['arrData']);
        $pagination = new Pagination([
            'defaultPageSize' => $data['arrConfig']['lPageLimit'],
            'totalCount' => $data['lTotalCount'],
            'page' => $data['arrConfig']['lPage'],
        ]);
        $data['pagination'] = $pagination;

        $data['sQuickSearchTip'] = SysField::find()->select(['sName'])->where([
            'sObjectName' => $sysList->sObjectName,
            'sFieldAs' => $sysList->sysobject->sNameField
        ])->one()->sName;


        return $this->renderPartial('@myerm/backend/common/views/listdata', $data);
    }

    /**
     * 视图数据的配置
     * @param Array $arrConfig
     */
    protected function listDataConfig($sysList, $arrConfig)
    {
        parse_str(html_entity_decode($_POST['sExtra']), $output);
        if ($_POST['sExtra'] && $output['sLinkField'] && $output['ID']) {
            $arrConfig['arrFlt'][] = [
                'sField' => $output['sLinkField'],
                'sOper' => 'equal',
                'sValue' => $output['ID'],
                'sSQL' => $output['sLinkField'] . "='" . $output['ID'] . "'"
            ];
        }

        return $arrConfig;
    }

    /**
     * 处理视图的数据
     * @param unknown $arrData
     */
    protected function formatListData($arrData)
    {
        return $arrData;
    }

    public function actionChangeowner()
    {
        $data = [];

        $arrDep = SysDep::find()->select(['lID', 'sName'])->where(['bActive' => 1])->asArray()->all();
        $arrUser = SysUser::find()->select(['lID', 'sName', 'SysDepID'])->where(['bActive' => 1])->asArray()->all();

        foreach ($arrDep as $i => $dep) {
            foreach ($arrUser as $user) {
                if ($dep['lID'] == $user['SysDepID']) {
                    $arrDep[$i]['arrUser'][] = $user;
                }
            }
        }

        $data['arrDep'] = $arrDep;

        if ($_GET['ID']) {
            $data['OwnerID'] = ObjectModel::config($this->sysObject)->select(['OwnerID'])->where([$this->sysObject->sIDField => $_GET['ID']])->one()->OwnerID;
        }

        return $this->renderPartial('@myerm/backend/common/views/changeowner', $data);
    }

    public function actionChangeownersave()
    {
        $ownerIDField = SysField::find()->select(['ID', 'sLinkField'])->where([
            'sObjectName' => $this->sObjectName,
            'sFieldAs' => 'OwnerID'
        ])->one();
        if (!$ownerIDField) {
            return json_encode(['bSuccess' => false, 'sMsg' => '对不起，该对象没有拥有者这个属性']);
        }

        if ($ownerIDField->sLinkField != 'lID') {
            $NewOwnerID = SysUser::find()->select($ownerIDField->sLinkField)->where(['lID' => $_POST['OwnerID']])->one()->{$ownerIDField->sLinkField};
        } else {
            $NewOwnerID = $_POST['OwnerID'];
        }

        //通过数据源获取数据库连接
        $sDataSourceKey = "ds_" . $this->sysObject->DataSourceID;
        $db = Yii::$app->$sDataSourceKey;

        //准备事务
        $transaction = $db->beginTransaction();

        try {

            if ($_GET['ID']) {
                $db->createCommand()->update($this->sysObject->sTable, ['OwnerID' => $NewOwnerID],
                    [$this->sysObject->sIDField => $_GET['ID']])->execute();
            } else {
                $arrData = $this->listBatch();
                foreach ($arrData as $data) {
                    if (!$this->bObjectPermit($this->sObjectName, Yii::$app->backendsession->SysUserID,
                        $data[$this->sysObject->sIDField], 'edit')
                    ) {
                        $sRespone = json_encode([
                            'bSuccess' => false,
                            'sMsg' => '对不起，您没有编辑"' . $data['sName'] . '"的权限。'
                        ]);
                        break;
                    } else {
                        $db->createCommand()->update($this->sysObject->sTable, ['OwnerID' => $NewOwnerID],
                            [$this->sysObject->sIDField => $data[$this->sysObject->sIDField]])->execute();
                    }
                }
            }

            //提交
            $transaction->commit();
        } catch (\yii\db\Exception $e) {
            $transaction->rollBack();
            $sRespone = json_encode(['bSuccess' => true, 'sMsg' => $e->getMessage()]);
        }

        if (!$sRespone) {
            $sRespone = json_encode(['bSuccess' => true, 'sMsg' => \Yii::t('app', '操作成功')]);
        }

        return $sRespone;
    }

    /**
     * 视图数据批处理
     */
    public function listBatch()
    {
        $arrConfig = $_POST;

        $arrConfig['sDataRegion'] = 'all';
        $arrConfig['bCanPage'] = 0;

        if ($_POST['sSelectedID'] == "all") {
            if (!StrTool::isEmpty(Yii::$app->request->post('sSearchKeyWord'))) {
                $arrConfig['arrFlt'][] = [
                    'sField' => 'sName',
                    'sOper' => 'center',
                    'sValue' => Yii::$app->request->post('sSearchKeyWord')
                ];
            }

            if ($_POST['arrSearchField']) {
                foreach ($_POST['arrSearchField'] as $sField => $arr) {
                    $arrConfig['arrFlt'][] = [
                        'sField' => $sField,
                        'sOper' => $arr['sOper'],
                        'sValue' => $arr['sValue']
                    ];
                }
            }

            //只搜索ID和sName
            $arrConfig['arrDispCol'] = [$this->sysObject->sIDField, $this->sysObject->sNameField];

            $sysList = SysList::find()->where("sKey='" . Yii::$app->request->post('sListKey') . "'")->one();

            $arrConfig = $this->listDataConfig($sysList, $arrConfig);

            return $sysList->getListdata($arrConfig)['arrData'];
        } else {
            $arrSelectedID = explode(";", $_POST['sSelectedID']);
            $arrConfig['arrFlt'] = [];
            $arrConfig['arrFlt'][] = [
                'sField' => $this->sysObject->sIDField,
                'sOper' => 'in',
                'sValue' => "'" . implode("','", $arrSelectedID) . "'"
            ];

            if ($_POST['arrSearchField']) {
                foreach ($_POST['arrSearchField'] as $sField => $arr) {
                    $arrConfig['arrFlt'][] = [
                        'sField' => $sField,
                        'sOper' => $arr['sOper'],
                        'sValue' => $arr['sValue']
                    ];
                }
            }

            //只搜索ID和sName
            $arrConfig['arrDispCol'] = [$this->sysObject->sIDField, $this->sysObject->sNameField];

            $sysList = SysList::find()->where("sKey='" . Yii::$app->request->post('sListKey') . "'")->one();

            $arrConfig = $this->listDataConfig($sysList, $arrConfig);

            return $sysList->getListdata($arrConfig)['arrData'];
        }
    }

    public function actionDel()
    {
        //通过数据源获取数据库连接
        $sDataSourceKey = "ds_" . $this->sysObject->DataSourceID;
        $db = Yii::$app->$sDataSourceKey;

        //准备事务
        $transaction = $db->beginTransaction();

        try {
            $arrData = $this->listBatch();
            $this->beforeDel($arrData);

            foreach ($arrData as $data) {
                if (!$this->bObjectPermit($this->sObjectName, Yii::$app->backendsession->SysUserID,
                    $data[$this->sysObject->sIDField], 'del')
                ) {
                    $sRespone = json_encode([
                        'bSuccess' => false,
                        'sMsg' => '对不起，您没有删除"' . $data[$this->sysObject->sNameField] . '"的权限。'
                    ]);
                    break;
                } else {
                    $db->createCommand()->delete($this->sysObject->sTable,
                        [$this->sysObject->sIDField => $data[$this->sysObject->sIDField]])->execute();
                }
            }

            //提交
            $transaction->commit();
        } catch (\yii\base\Exception $e) {
            $transaction->rollBack();
            $sRespone = json_encode(['bSuccess' => false, 'sMsg' => $e->getMessage()]);
        }

        $sysDetailObject = SysObject::findOne(['ParentID' => $this->sysObject->sObjectName]);
        if (!$sRespone && $sysDetailObject) {
            $db = $sysDetailObject->dbconn;
            $transaction = $db->beginTransaction();
            try {
                foreach ($arrData as $data) {
                    $db->createCommand()->delete($sysDetailObject->sTable,
                        [$this->sysObject->sLinkSubObjectField => $data[$this->sysObject->sIDField]])->execute();
                }

                //提交
                $transaction->commit();
            } catch (\yii\base\Exception $e) {
                $transaction->rollBack();
                $sRespone = json_encode(['bSuccess' => false, 'sMsg' => $e->getMessage()]);
            }
        }

        if (!$sRespone) {
            $sRespone = json_encode(['bSuccess' => true, 'sMsg' => \Yii::t('app', '操作成功')]);
        }

        $this->afterDel($arrData);

        return "var ret = " . $sRespone;
    }

    /**
     * 在删除对象之前，会回调此方法。
     * 如果返回true，表示验证成功，执行删除动作。如果返回字符串，则验证失败，返回错误信息。
     * @param String $ID 删除对象的ID
     */
    protected function beforeDel($arrData)
    {
        return true;
    }

    /**
     * 在删除对象之后，会回调此方法。
     * @param String $ID 删除对象的ID
     */
    protected function afterDel($arrData)
    {
        foreach ($arrData as $data) {
            SysManualShare::deleteAll([
                'sObjectName' => $this->sObjectName,
                'ObjectID' => $data[$this->sysObject->sIDField]
            ]);
        }

        return true;
    }

    public function actionExport()
    {
        set_time_limit(0);
        ini_set("memory_limit", "8048M");

        $sysObject = SysObject::findOne(['sObjectName' => $this->sObjectName]);

        include Yii::getAlias("@app") . "/common/libs/PHPExcel.php";
        include Yii::getAlias("@app") . '/common/libs/PHPExcel/IOFactory.php';

        $objPHPExcel = new \PHPExcel();

        $arrChar = [
            'A',
            'B',
            'C',
            'D',
            'E',
            'F',
            'G',
            'H',
            'I',
            'J',
            'K',
            'L',
            'M',
            'N',
            'O',
            'P',
            'Q',
            'R',
            'S',
            'T',
            'U',
            'V',
            'W',
            'X',
            'Y',
            'Z'
        ];

        $sPrefix = "";
        $lKey = 0;
        foreach ($sysObject->field as $i => $field) {
            if ($i % 26 == 0 && $i > 25) {
                $lKey = 0;
                if ($sPrefix == "") {
                    $sPrefix = "A";
                } else {
                    $sPrefix = $arrChar[array_search($sPrefix, $arrChar) + 1];
                }
            }

            $arrHeader[] = $sPrefix . $arrChar[$lKey];
            $lKey++;
        }

        $sysList = SysList::find()->where("sKey='" . Yii::$app->request->post('sListKey') . "'")->one();

        $arrConfig = $_POST;
        foreach ($sysList->cols as $i => $col) {
            $objPHPExcel->getActiveSheet()->setCellValue($arrHeader[$i] . '1', $col->field->sName);
            $arrConfig['arrDispCol'][] = $col->field->sFieldAs;
        }

        //关闭分页，查询所有的数据
        $arrConfig['bCanPage'] = 0;

        if ($_POST['sSelectedID'] == "all") {
            if (!StrTool::isEmpty(Yii::$app->request->post('sSearchKeyWord'))) {
                $arrConfig['arrFlt'][] = [
                    'sField' => 'sName',
                    'sOper' => 'center',
                    'sValue' => Yii::$app->request->post('sSearchKeyWord')
                ];
            }

            if ($_POST['arrSearchField']) {
                foreach ($_POST['arrSearchField'] as $sField => $arr) {
                    $arrConfig['arrFlt'][] = [
                        'sField' => $sField,
                        'sOper' => $arr['sOper'],
                        'sValue' => $arr['sValue']
                    ];
                }
            }

            $arrConfig = $this->listDataConfig($sysList, $arrConfig);

            $arrData = $sysList->getListdata($arrConfig)['arrData'];
        } else {
            $arrSelectedID = explode(";", $_POST['sSelectedID']);
            $arrConfig['arrFlt'] = [];
            $arrConfig['arrFlt'][] = [
                'sField' => $sysObject->sIDField,
                'sOper' => 'in',
                'sValue' => "'" . implode("','", $arrSelectedID) . "'"
            ];

            if ($_POST['arrSearchField']) {
                foreach ($_POST['arrSearchField'] as $sField => $arr) {
                    $arrConfig['arrFlt'][] = [
                        'sField' => $sField,
                        'sOper' => $arr['sOper'],
                        'sValue' => $arr['sValue']
                    ];
                }
            }

            $arrConfig['sDataRegion'] = 'all';

            $arrConfig = $this->listDataConfig($sysList, $arrConfig);

            $arrData = $sysList->getListdata($arrConfig)['arrData'];
        }

        foreach ($arrData as $lRow => $data) {
            foreach ($sysList->cols as $i => $col) {

                $field = $col->field;

                if ($field->sDataType == 'List' || $field->sDataType == 'ListTable') {
                    $sValue = $data[$field->sFieldAs]['sName'];
                } elseif ($field->sDataType == 'MultiList') {
                    $sValue = $sComm = "";
                    foreach ($data[$field->sFieldAs] as $arrValue) {
                        $sValue .= $sComm . $arrValue['sName'];
                        $sComm = ";";
                    }
                } elseif ($field->sDataType == 'Bool') {
                    if ($data[$field->sFieldAs]) {
                        $sValue = \Yii::t('app', '是');
                    } else {
                        $sValue = \Yii::t('app', '否');
                    }
                } elseif ($field->sDataType == 'Date') {
                    if ($field->attr['dFormat'] == 'short') {
                        $sValue = SystemTime::getShortDate($data[$field->sFieldAs], $field->attr['lTimeOffset']);
                    } elseif ($data[$field->sFieldAs]) {
                        $sValue = SystemTime::getLongDate($data[$field->sFieldAs], $field->attr['lTimeOffset']);
                    } else {
                        $sValue = "";
                    }
                } else {
                    $sValue = $data[$field->sFieldAs];
                }

                $objPHPExcel->getActiveSheet()->setCellValueExplicit($arrHeader[$i] . ($lRow + 2), $sValue,
                    \PHPExcel_Cell_DataType::TYPE_STRING);
            }
        }

        $objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');

        ob_end_clean();
        header("Content-Type: application/force-download");
        header("Content-Type: application/octet-stream");
        header("Content-Type: application/download");
        header("Content-Type: application/msexcel");

        if (stristr($_SERVER['HTTP_USER_AGENT'], 'Firefox')) {
            header('Content-Disposition:inline;filename="' . $sysObject->sName . \Yii::t('app',
                    '导出') . date("Y-m-d") . '.xls"');
        } else {
            header('Content-Disposition:inline;filename="' . urlencode($sysObject->sName . \Yii::t('app',
                        '导出')) . date("Y-m-d") . '.xls"');
        }

        header("Content-Transfer-Encoding: binary");
        header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
        header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
        header("Pragma: no-cache");
        $objWriter->save('php://output');
    }

    public function getAdvancedSearch($sListKey)
    {
        $data = [];

        $sysList = SysList::find()->where("sKey='" . $sListKey . "'")->one();
        $data['arrSearchField'] = $sysList->advancedsearchfield;


        return $this->renderPartial('@myerm/backend/common/views/advancedsearch', $data);
    }

    public function getListButton()
    {
        $arrData = [];
        $arrData['bOwnerIDExists'] = SysField::find()->select(['ID'])->where([
            'sObjectName' => $this->sObjectName,
            'sFieldAs' => 'OwnerID'
        ])->one();
        return $this->renderPartial('@myerm/backend/common/views/listbutton', $arrData);
    }

    public function getListTableLineButton($data, $arrConfig = null)
    {
        return $this->renderPartial('@myerm/backend/common/views/listtablelinebutton', ['data' => $data]);
    }

    public function getRelInfoListButton()
    {
        $arrData = [];
        $arrData['bOwnerIDExists'] = SysField::find()->select(['ID'])->where([
            'sObjectName' => $this->sObjectName,
            'sFieldAs' => 'OwnerID'
        ])->one();
        return $this->renderPartial('@myerm/backend/common/views/relinfolistbutton', $arrData);
    }

    public function getViewButton()
    {
        $arrData = [];

        $arrData['bOwnerIDExists'] = SysField::find()->select(['ID'])->where([
            'sObjectName' => $this->sObjectName,
            'sFieldAs' => 'OwnerID'
        ])->one();

        return $this->renderPartial('@myerm/backend/common/views/viewbutton', $arrData);
    }

    public function getNewButton()
    {
        $arrData = [];

        return $this->renderPartial('@myerm/backend/common/views/newbutton', $arrData);
    }

    public function getRefButton()
    {
        $arrData = [];
        return $this->renderPartial('@myerm/backend/common/views/refbutton', $arrData);
    }

    /**
     * 当点击列表或者参照字段时，需要跳转到详情页面，这个方法是做为跳转的中转站
     */
    public function actionViewredirect()
    {
        $field = SysField::findOne($_GET['FieldID']);

        if ($field->sDataType == 'List' || $field->sDataType == 'MultiList') {
            $refObject = $field->enumobject;
        } else {
            $refObject = $field->refobject;
        }

        if ($field->RefFieldID) {//表示它是引用字段，需要做特殊处理。Mars@2016年10月26日 10:41:07
            return $this->redirect(Yii::$app->homeUrl . '/' . strtolower($refObject->sObjectName) . '/view?ID=' . $_GET['ID']);
        } elseif (StrTool::equalsIgnoreCase($field->sLinkField, $refObject->sIDField)) {
            return $this->redirect(Yii::$app->homeUrl . '/' . strtolower($refObject->sObjectName) . '/view?ID=' . $_GET['ID']);
        } else {
            $object = ObjectModel::config($refObject)->select($refObject->sIDField)->where([$field->sLinkField => $_GET['ID']])->one();
            return $this->redirect(Yii::$app->homeUrl . '/' . strtolower($refObject->sObjectName) . '/view?ID=' . $object->{$refObject->sIDField});
        }
    }

    public function actionView()
    {
        $data = [];

        $bCheck = $this->beforeView($_GET['ID']);
        if ($bCheck !== true) {
            throw new UserException($bCheck);
        }

        $data['sysObject'] = $this->sysObject;
        $data['data'] = ObjectModel::config($data['sysObject'])
            ->select([$data['sysObject']->sIDField . " AS ID", $data['sysObject']->sNameField . " AS sName"])
            ->where([$data['sysObject']->sIDField => $_GET['ID']])
            ->asArray()
            ->one();
        if (!$data['data']) {
            throw new UserException(\Yii::t('app', "您查看的对象不存在。"));
        }

        //获取界面
        $ui = SysUI::getUI($this->sObjectName, "view");
        if (!$ui) {//找不到界面，要报错
            throw new UserException(\Yii::t('app', '找不到查看的界面，请联系管理员。'));
        }

        $data['ui'] = $ui;
        $data['arrUIData'] = $this->viewObjectPrepare(SysUI::getUIData($ui, $this->sObjectName, $_GET['ID']));
        $data['arrSysDetailObject'] = SysObject::find()->where(['ParentID' => $this->sysObject->sObjectName])->all();

        return $this->render('@myerm/backend/common/views/view', $data);
    }

    /**
     * 在查看对象详情之前，调用此方法
     * @param String $ID
     * @return boolean
     */
    protected function beforeView($ID)
    {
        if (!$this->bObjectPermit($this->sObjectName, Yii::$app->backendsession->SysUserID, $ID, 'view')) {
            return \Yii::t('app', '您没有查看的数据权限');
        }

        return true;
    }

    /**
     * 在查看对象时，回调这个方法提前处理填充表单的数据。这个方法可由开发者继承，做特殊化处理。
     * @param Array $objectData
     */
    protected function viewObjectPrepare($objectData)
    {
        return $objectData;
    }

    /**
     * 获取对象的相关信息
     */
    public function getRelInfo($sysObject)
    {
        $infoList = SysList::find()
            ->where(['sObjectName' => $sysObject->sObjectName, 'sType' => 'info', 'bActive' => 1])
            ->one();
        if (!$infoList) {
            $infoList = SysList::find()
                ->where(['sObjectName' => $sysObject->sObjectName, 'bDefault' => '1'])
                ->one();
        }

        return $this->renderPartial('@myerm/backend/common/views/relinfo', [
            'list' => $infoList,
            'sLinkField' => $sysObject->sLinkSubObjectField ? $sysObject->sLinkSubObjectField : $this->sysObject->sLinkSubObjectField
        ]);
    }

    public function actionShowupload()
    {
        $arrData = [];
        return $this->renderPartial('@myerm/backend/common/views/showupload', $arrData);
    }

    public function actionUpload()
    {
        if (Yii::$app->request->isPost) {

            if (!StrTool::validateExtension($_FILES['file']['name'])) {
                $arrData = ['bSuccess' => false, 'sMsg' => Yii::t('app', '您上传的文件非法，请重新选择。')];
            } else {
                File::createDirectory(Yii::$app->params['sUploadDir']);
                File::createDirectory(Yii::$app->params['sUploadDir'] . "/userfile/upload");
                File::createDirectory(Yii::$app->params['sUploadDir'] . "/userfile/upload");
                File::createDirectory(Yii::$app->params['sUploadDir'] . "/userfile/upload/" . SystemTime::getCurYear());
                File::createDirectory(Yii::$app->params['sUploadDir'] . "/userfile/upload/" . SystemTime::getCurYear() . "/" . SystemTime::getMonthDay());

                $sDestination = "userfile/upload/" . SystemTime::getCurYear() . "/" . SystemTime::getMonthDay() . "/" . NewID::make() . "." . File::getExtension($_FILES['file']['name']);
                \Yii::trace(Yii::$app->params['sUploadDir'] . "/" . $sDestination);
                if (move_uploaded_file($_FILES['file']['tmp_name'],
                    Yii::$app->params['sUploadDir'] . "/" . $sDestination)) {
                    $arrData = [
                        'bSuccess' => true,
                        'sMsg' => Yii::t('app', '文件上传成功'),
                        'sName' => $_FILES['file']['name'],
                        'sPath' => $sDestination
                    ];
                } elseif (copy($_FILES['file']['tmp_name'], Yii::$app->params['sUploadDir'] . "/" . $sDestination)) {
                    $arrData = [
                        'bSuccess' => true,
                        'sMsg' => Yii::t('app', '文件上传成功'),
                        'sName' => $_FILES['file']['name'],
                        'sPath' => $sDestination
                    ];
                } else {
                    $arrData = ['bSuccess' => false, 'sMsg' => Yii::t('app', '文件上传失败')];
                }
            }

            //上传成功，写入到SysAttach
            $field = SysField::find()->select('ID')->where([
                'sObjectName' => $this->sObjectName,
                'sFieldAs' => $_GET['sField']
            ])->one();
            SysAttach::saveData($_FILES['file']['name'], $_FILES['file']['type'], $_FILES['file']['size'],
                $sDestination, $this->sObjectName, $field->ID);


            echo "<script>var data=" . json_encode($arrData) . ";if(data.bSuccess){parent.save(data);}else{parent.error(data.sMsg);}</script>";

        } else {
            $arrData = [];
            return $this->renderPartial('@myerm/backend/common/views/upload', $arrData);
        }
    }

    public function actionJs()
    {
        return null;
    }

    public function actionJslang()
    {
        return $this->renderPartial('@myerm/backend/common/views/jslang', []);
    }

    public function actionManualshare()
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
        $arrData['arrRole'] = SysRole::find()->select(['lID', 'sName'])->orderBy('PathID')->asArray()->all();
        $arrData['arrTeam'] = SysTeam::find()->select(['lID', 'sName'])->asArray()->all();

        return $this->renderPartial('@myerm/backend/common/views/manualshare', $arrData);
    }

    public function actionManualsharesave()
    {
        set_time_limit(0);
        ini_set("memory_limit", "8048M");

        //通过数据源获取数据库连接
        $db = SysManualShare::getDb();

        //准备事务
        $transaction = $db->beginTransaction();

        try {
            $arrData = $this->listBatch();
            foreach ($arrData as $data) {

                $arrShareData = $_POST['arrShareData'];

                $ObjectID = $data[$this->sysObject->sIDField];

                //获取操作者，对此条数据的最大操作权限
                $sToken = $this->getObjectPermitToken($this->sObjectName, Yii::$app->backendsession->SysUserID,
                    $ObjectID);

                $arrCount = [];
                foreach ($arrShareData as $sObjectName => $arrObjectID) {
                    $arrShareData[$sObjectName] = array_values($arrObjectID);
                    $arrCount[] = count($arrShareData[$sObjectName]);
                }

                $lMaxKey = max($arrCount);

                for ($i = 0; $i < $lMaxKey; $i++) {
                    if ($arrShareData['User'][$i]) {
                        $db->createCommand()->update(SysManualShare::tableName(),
                            ['ToSysUserID' => null],
                            [
                                'sObjectName' => $this->sObjectName,
                                'ObjectID' => $ObjectID,
                                'FromSysUserID' => Yii::$app->backendsession->SysUserID,
                                'ToSysUserID' => $arrShareData['User'][$i],
                            ])->execute();
                    }

                    if ($arrShareData['Role'][$i]) {
                        $db->createCommand()->update(SysManualShare::tableName(),
                            ['ToSysRoleID' => null],
                            [
                                'sObjectName' => $this->sObjectName,
                                'ObjectID' => $ObjectID,
                                'FromSysUserID' => Yii::$app->backendsession->SysUserID,
                                'ToSysRoleID' => $arrShareData['Role'][$i],
                            ])->execute();
                    }

                    if ($arrShareData['Dep'][$i]) {
                        $db->createCommand()->update(SysManualShare::tableName(),
                            ['ToSysDepID' => null],
                            [
                                'sObjectName' => $this->sObjectName,
                                'ObjectID' => $ObjectID,
                                'FromSysUserID' => Yii::$app->backendsession->SysUserID,
                                'ToSysDepID' => $arrShareData['Dep'][$i],
                            ])->execute();
                    }

                    if ($arrShareData['Team'][$i]) {
                        $db->createCommand()->update(SysManualShare::tableName(),
                            ['ToSysTeamID' => null],
                            [
                                'sObjectName' => $this->sObjectName,
                                'ObjectID' => $ObjectID,
                                'FromSysUserID' => Yii::$app->backendsession->SysUserID,
                                'ToSysTeamID' => $arrShareData['Team'][$i],
                            ])->execute();
                    }

                    $db->createCommand()->delete(SysManualShare::tableName(),
                        [
                            'sObjectName' => $this->sObjectName,
                            'ObjectID' => $ObjectID,
                            'FromSysUserID' => Yii::$app->backendsession->SysUserID,
                            'ToSysUserID' => null,
                            'ToSysRoleID' => null,
                            'ToSysDepID' => null,
                            'ToSysTeamID' => null
                        ])->execute();

                    $arrInserData = [
                        'ID' => NewID::make(),
                        'sObjectName' => $this->sObjectName,
                        'ObjectID' => $ObjectID,
                        'FromSysUserID' => Yii::$app->backendsession->SysUserID,
                        'ToSysUserID' => $arrShareData['User'][$i],
                        'ToSysRoleID' => $arrShareData['Role'][$i],
                        'ToSysDepID' => $arrShareData['Dep'][$i],
                        'ToSysTeamID' => $arrShareData['Team'][$i],
                        'sToken' => $sToken
                    ];

                    $db->createCommand()->insert(SysManualShare::tableName(), $arrInserData)->execute();

                    unset($arrShareData['User'][$i], $arrShareData['Role'][$i], $arrShareData['Team'][$i], $arrShareData['Dep'][$i]);
                }

            }

            //提交
            $transaction->commit();
        } catch (\yii\db\Exception $e) {
            $transaction->rollBack();
            $sRespone = json_encode(['bSuccess' => true, 'sMsg' => $e->getMessage()]);
        }

        if (!$sRespone) {
            $sRespone = json_encode(['bSuccess' => true, 'sMsg' => \Yii::t('app', '操作成功')]);
        }

        return $sRespone;
    }

    /**
     * 新建/编辑的页面底部引入其他模板文件
     */
    public function getNewFooterAppend()
    {

        //return $this->renderPartial('@myerm/backend/common/views/manualshare', $arrData);
    }

    /**
     * 详情的页面底部引入其他模板文件
     */
    public function getViewFooterAppend()
    {


    }

    protected function newDetailbjectPrepare($detailObjectData)
    {
        return $detailObjectData;
    }

}
