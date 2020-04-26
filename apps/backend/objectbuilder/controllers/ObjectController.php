<?php

namespace myerm\backend\objectbuilder\controllers;

use myerm\backend\common\libs\NewID;
use myerm\backend\common\libs\StrTool;
use myerm\backend\system\models\SysDataSource;
use myerm\backend\system\models\SysField;
use myerm\backend\system\models\SysFieldEnum;
use myerm\backend\system\models\SysFilterDetail;
use myerm\backend\system\models\SysList;
use myerm\backend\system\models\SysListAdvancedSearch;
use myerm\backend\system\models\SysListSelect;
use myerm\backend\system\models\SysModule;
use myerm\backend\system\models\SysNavItem;
use myerm\backend\system\models\SysObject;
use myerm\backend\system\models\SysObjectOperator;
use myerm\backend\system\models\SysOrgObject;
use myerm\backend\system\models\SysOrgOperator;
use myerm\backend\system\models\SysSolutionNavItem;
use myerm\backend\system\models\SysUI;
use myerm\backend\system\models\SysUIFieldClass;
use myerm\backend\system\models\SysUIFieldClassField;
use myerm\backend\system\models\SysUIFieldClassRule;
use Yii;

/**
 * 对象管理器控制器-对象管理
 * ============================================================================
 * 版权所有 2010-2017 三只象（厦门）网络科技有限公司，并保留所有权利。
 * 网站地址: http://www.myerm.cn
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和
 * 使用；不允许对程序代码以任何形式任何目的的再发布。
 * ============================================================================
 * @author 陈鹭明  <lumingchen@qq.com>
 * @since 2015-11-1 14:25
 * @version v2.0
 */
class ObjectController extends Controller
{
    /**
     * 对象的列表
     */
    public function actionList()
    {
        $arrData = [];

        //获取模块数据
        $arrData['arrModule'] = SysModule::find()->asArray()->all();

        //获取数据源数据
        $arrData['arrDatasource'] = SysDataSource::find()->asArray()->all();

        //获取对象的数据
        $sSQL = "1>0";
        if (Yii::$app->request->post('sModuleID')) {
            $sSQL .= " AND ModuleID='" . Yii::$app->request->post('sModuleID') . "'";
        }

        if (Yii::$app->request->post('sKeyword')) {
            $sSQL .= " AND sName LIKE '%" . Yii::$app->request->post('sKeyword') . "%'";
        }

        $arrData['arrObject'] = [];
        $arrObject = SysObject::find()->with('datasource')->with('parent')->with('module')->where($sSQL)->orderBy('ModuleID, sTable')->all();
        foreach ($arrObject as $i => $object) {
            $data = $object->toArray();
            $data['sModuleName'] = $object->module ? $object->module->sName : null;
            $data['sParentName'] = $object->parent ? $object->parent->sName : null;
            $data['sDataSourceName'] = $object->datasource ? $object->datasource->sName : null;

            $arrData['arrObject'][] = $data;
        }

        $this->output($arrData);
    }

    /**
     * 新建对象保存
     */
    public function actionNewsave()
    {
        $arrReturn = [];

        $sTable = ucfirst(Yii::$app->request->post('sTable'));
        $sName = Yii::$app->request->post('sName');
        $ModuleID = Yii::$app->request->post('ModuleID');
        $DataSourceID = Yii::$app->request->post('DataSourceID');
        $ParentID = Yii::$app->request->post('ParentID');
        $bWorkFlow = Yii::$app->request->post('bWorkFlow');
        $bDetailObject = Yii::$app->request->post('bDetailObject');
        $sPrimartKeyType = Yii::$app->request->post('sPrimartKeyType');
        $sLinkSubObjectField = Yii::$app->request->post('sLinkSubObjectField');
        $sObjectName = $ModuleID . "/" . $sTable;

        if (StrTool::isEmpty($sTable)) {
            $this->lStatus = 0;
            $this->sErrMsg = "表名不能为空。";
        } elseif (StrTool::isEmpty($sName)) {
            $this->lStatus = 0;
            $this->sErrMsg = "对象的名称不能为空。";
        } elseif (StrTool::isEmpty($ModuleID)) {
            $this->lStatus = 0;
            $this->sErrMsg = "模块是必选。";
        } elseif (StrTool::isEmpty($DataSourceID)) {
            $this->lStatus = 0;
            $this->sErrMsg = "数据源是必选。";
        } else {
            //检查对象是否已经存在
            $object = SysObject::find()->where("sObjectName='$sObjectName'")->one();
            if ($object) {
                $this->lStatus = 0;
                $this->sErrMsg = "表名是" . $sTable . "的对象已经存在。";
            } else {
                //检查名称是否重复
                $object = SysObject::find()->where("ModuleID='$ModuleID' AND sName='$sName'")->one();
                if ($object) {
                    $this->lStatus = 0;
                    $this->sErrMsg = "对象名称是" . $sName . "的对象已经存在。";
                }
            }
        }

        if ($this->lStatus == 1) {

            //通过数据源获取数据库连接
            $sDataSourceKey = "ds_$DataSourceID";
            $db = Yii::$app->$sDataSourceKey;

            //准备事务
            $transaction = $db->beginTransaction();

            try {
                $arrTable = $db->getSchema()->getTableNames();
                foreach ($arrTable as $sTableName) {
                    if ($sTableName == $sTable) {
                        throw new \yii\db\Exception($sTable . '表已经存在');
                    }
                }

                $sSysUserIDField = SysObject::findOne(['sObjectName' => 'System/SysUser'])->sIDField;

                if ($db->getDriverName() == 'sqlsrv') {
                    $sCreateSQL = "CREATE TABLE [dbo].[$sTable](
                				        [lID] [int] IDENTITY(1,1) NOT NULL,";

                    if ($ParentID) {
                        $sCreateSQL .= " [" . basename($ParentID) . "ID] ";

                        if ($sPrimartKeyType == 'autoincrement') {
                            $sCreateSQL .= "[int] NOT NULL,\n";
                        } else {
                            $sCreateSQL .= "[varchar](32) NOT NULL,\n";
                        }
                    }

                    $sCreateSQL .= "	[sName] [varchar](50) NOT NULL,
                				        [OwnerID] [int] NOT NULL,
                				        [NewUserID] [int] NOT NULL,
                				        [EditUserID] [int] NOT NULL,
                				        [dNewDate] [datetime] NOT NULL,
                				        [dEditDate] [datetime] NOT NULL
                				        ) ON [PRIMARY]";
                } elseif ($db->getDriverName() == 'mysql') {

                    //创建物理的数据库表
                    $sCreateSQL = "CREATE TABLE `$sTable` (
    							  `lID` int(11) NOT NULL AUTO_INCREMENT,
    							  `sName` varchar(250) DEFAULT NULL,\n";
                    if ($ParentID) {
                        $sCreateSQL .= " `" . basename($ParentID) . "ID` ";

                        if ($sPrimartKeyType == 'autoincrement') {
                            $sCreateSQL .= "int(11) DEFAULT NULL,\n";
                        } else {
                            $sCreateSQL .= "varchar(32) DEFAULT NULL,\n";
                        }
                    }

                    $sCreateSQL .= "  `OwnerID` " . ($sSysUserIDField == "lID" ? "int(11)" : "varchar(32)") . " DEFAULT NULL,
        							  `NewUserID` " . ($sSysUserIDField == "lID" ? "int(11)" : "varchar(32)") . " DEFAULT NULL,
        							  `EditUserID` " . ($sSysUserIDField == "lID" ? "int(11)" : "varchar(32)") . " DEFAULT NULL,
        							  `dNewDate` datetime DEFAULT NULL,
        							  `dEditDate` datetime DEFAULT NULL,
        							  PRIMARY KEY (`lID`),
        							  KEY `OwnerID` (`OwnerID`,`dNewDate`),
        							  KEY `dNewDate` (`dNewDate`)\n";
                    if ($ParentID) {
                        $sCreateSQL .= ",KEY `" . basename($ParentID) . "ID` (`" . basename($ParentID) . "ID`)\n";
                    }

                    $sCreateSQL .= ") ENGINE=InnoDB DEFAULT CHARSET=utf8;";
                }


                $db->createCommand($sCreateSQL)->execute();

                //保存基本信息到SysObject表
                $sysObject = new SysObject();
                $sysObject->sTable = $sTable;
                $sysObject->sName = $sName;
                $sysObject->ModuleID = $ModuleID;
                $sysObject->ParentID = $ParentID;
                $sysObject->sObjectName = $sObjectName;
                $sysObject->DataSourceID = $DataSourceID;
                $sysObject->bWorkFlow = $bWorkFlow;
                $sysObject->bUDF = 1;
                $sysObject->sIDField = $sPrimartKeyType == 'autoincrement' ? 'lID' : 'ID';
                $sysObject->sNameField = "sName";
                $sysObject->sLinkSubObjectField = $sLinkSubObjectField;
                $sysObject->save();

                //保存缺省的操作权限到SysObjectOperator，缺省的操作权限：新建、编辑、删除、查看详情、列表
                $lPos = 0;
                $arrOpera = [
                    'new' => '新建',
                    'edit' => '编辑',
                    'del' => '删除',
                    'export' => '导出',
                    'view' => '查看详情',
                    'home' => '主页'
                    /*, 'changeowner' => '更改拥有者'*/
                ];
                foreach ($arrOpera as $sOperator => $sOperatorName) {
                    $sysOpera = new SysObjectOperator();
                    $sysOpera->ID = NewID::make();
                    $sysOpera->sObjectName = $sObjectName;
                    $sysOpera->sOperator = $sOperator;
                    $sysOpera->sName = $sOperatorName;
                    $sysOpera->lPos = $lPos;
                    $sysOpera->save();
                    $lPos++;
                }

                /**
                 * 保存属性到字典表SysField
                 */
                //创建属性：lID
                $textField = SysField::createField("int");
                $textField->addField([
                    'sName' => $sName . 'ID',
                    'sObjectName' => $sObjectName,
                    'sFieldAs' => 'lID',
                    'sLinkField' => 'sName',
                    'sFieldClassType' => 'list,view',
                    'bPrimaryKey' => 1, //是主键
                    'sPrimartKeyType' => 'autoincrement', //混合型主键
                    'lLength' => 11
                ]);

                /*
				 * 抛弃32位的ID算法，2016/3/5
				 * 
				//创建属性：ID
				$textField = SysField::createField("text");				
				$textField->addField([
							'sName'=>'ID',
							'sObjectName'=>$sObjectName,
							'sFieldAs'=>'ID',
				            'sLinkField'=>'sName',
				            'sFieldClassType'=>'list,view',
							'bPrimaryKey'=>1, //是主键
							'sPrimartKeyType'=>'system', //混合型主键
							'lSysPrimartKeyValueLength'=>32, //主键长度
							'lLength'=>32
							]); */

                //创建属性: sName
                $textField = SysField::createField("text");
                $NameFieldID = $textField->addField([
                    'sName' => '名称',
                    'sObjectName' => $sObjectName,
                    'sFieldAs' => 'sName',
                    'sFieldClassType' => 'list,view,edit,new',
                    'sRefKey' => $sObjectName,
                    'sLinkField' => $sPrimartKeyType == 'system' ? 'ID' : 'lID',
                    'bNull' => 1
                ]);

                //如果是明细对象，创建参照父对象的属性
                if ($ParentID) {
                    $sCreateSQL .= " `" . basename($ParentID) . "ID` ";

                    if ($sPrimartKeyType == 'autoincrement') {
                        $sCreateSQL .= "int(11) DEFAULT NULL,\n";
                    } else {
                        $sCreateSQL .= "varchar(32) DEFAULT NULL,\n";
                    }

                    $listTableField = SysField::createField("listtable");
                    $listTableField->addField([
                        'sName' => '参照父对象',
                        'sObjectName' => $sObjectName,
                        'sFieldAs' => basename($ParentID) . "ID",
                        'sFieldClassType' => 'list,view',
                        'sLinkField' => 'lID',
                        'sShwField' => 'sName',
                        'sRefKey' => $ParentID,
                        'sInElt' => strtolower("{$sTable}_" . basename($ParentID) . "IDname:sname:center"),
                    ]);
                }

                //创建属性: OwnerID
                $listTableField = SysField::createField("listtable");
                $listTableField->addField([
                    'sName' => '拥有者',
                    'sObjectName' => $sObjectName,
                    'sFieldAs' => 'OwnerID',
                    'sFieldClassType' => 'list,view',
                    'sLinkField' => $sSysUserIDField,
                    'sShwField' => 'sName',
                    'sRefKey' => 'System/SysUser',
                    'sInElt' => strtolower("{$sTable}_OwnerIDname:sname:center"),
                    'sDefValue' => '?curuserid?',
                ]);

                //创建属性: NewUserID
                $listTableField = SysField::createField("listtable");
                $listTableField->addField([
                    'sName' => '新建人',
                    'sObjectName' => $sObjectName,
                    'sFieldAs' => 'NewUserID',
                    'sFieldClassType' => 'list,view',
                    'sLinkField' => $sSysUserIDField,
                    'sShwField' => 'sName',
                    'sRefKey' => 'System/SysUser',
                    'sInElt' => strtolower("{$sTable}_NewUserIDname:sname:center"),
                    'sDefValue' => '?curuserid?',
                ]);

                //创建属性: EditUserID
                $listTableField = SysField::createField("listtable");
                $listTableField->addField([
                    'sName' => '编辑人',
                    'sObjectName' => $sObjectName,
                    'sFieldAs' => 'EditUserID',
                    'sFieldClassType' => 'list,view',
                    'sLinkField' => $sSysUserIDField,
                    'sShwField' => 'sName',
                    'sRefKey' => 'System/SysUser',
                    'sInElt' => strtolower("{$sTable}_EditUserIDname:sname:center"),
                    'sDefValue' => '?curuserid?',
                ]);


                //创建属性: dNewDate
                $dateField = SysField::createField("date");
                $dateField->addField([
                    'sName' => '新建时间',
                    'sObjectName' => $sObjectName,
                    'sFieldAs' => 'dNewDate',
                    'sFieldClassType' => 'list,view',
                    'sDefValue' => '?curdate?',
                    'sAttribute' => serialize(['dFormat' => 'long']),
                ]);

                //创建属性: dEditDate
                $dateField = SysField::createField("date");
                $dateField->addField([
                    'sName' => '编辑时间',
                    'sObjectName' => $sObjectName,
                    'sFieldAs' => 'dEditDate',
                    'sFieldClassType' => 'list,view',
                    'sDefValue' => '?curdate?',
                    'sAttribute' => serialize(['dFormat' => 'long']),
                ]);

                /**
                 * 保存视图数据
                 */
                $sysList = new SysList();
                $sysList->ID = NewID::make();
                $sysList->sObjectName = $sObjectName;
                $sysList->sKey = "Main.$ModuleID.$sTable.List.All";
                $sysList->sName = "我管理的";
                $sysList->sOrderBy = $sTable . ".dNewDate DESC";
                $sysList->sDataPowerField = 'OwnerID';
                $sysList->bCanPage = 1;
                $sysList->bCanSort = 1;
                $sysList->bCanBat = 1;
                $sysList->bDefault = 1;
                $sysList->sDataRegion = "datapower";
                $sysList->sType = "list";
                $sysList->save();

                //除了ID字段，都显示到视图上
                $arrSysField = SysField::find()->select([
                    'ID',
                    'sName'
                ])->where("sObjectName='$sObjectName' AND sFieldAs NOT IN ('ID', 'lID')")->orderBy("ID")->all();
                foreach ($arrSysField as $lPos => $sysField) {
                    $sysListSelect = new SysListSelect();
                    $sysListSelect->ID = NewID::make();
                    $sysListSelect->sObjectName = $sObjectName;
                    $sysListSelect->SysListID = $sysList->ID;
                    $sysListSelect->SysFieldID = $sysField->ID;
                    $sysListSelect->lPos = $lPos;
                    $sysListSelect->save();

                    $sysListAdvancedSearch = new SysListAdvancedSearch();
                    $sysListAdvancedSearch->ID = NewID::make();
                    $sysListAdvancedSearch->sObjectName = $sObjectName;
                    $sysListAdvancedSearch->SysListID = $sysList->ID;
                    $sysListAdvancedSearch->SysFieldID = $sysField->ID;
                    $sysListAdvancedSearch->lPos = $lPos;
                    $sysListAdvancedSearch->save();
                }

                $sysList = new SysList();
                $sysList->ID = NewID::make();
                $sysList->sObjectName = $sObjectName;
                $sysList->sKey = "Main.$ModuleID.$sTable.Refer.All";
                $sysList->sName = "参照";
                $sysList->sOrderBy = $sTable . ".dNewDate DESC";
                $sysList->sDataPowerField = 'OwnerID';
                $sysList->bCanPage = 1;
                $sysList->bCanSort = 1;
                $sysList->bCanBat = 1;
                $sysList->bDefault = 0;
                $sysList->sDataRegion = "datapower";
                $sysList->sType = "refer";
                $sysList->save();

                //除了ID字段，都显示到视图上
                $arrSysField = SysField::find()->select([
                    'ID',
                    'sName'
                ])->where("sObjectName='$sObjectName' AND sFieldAs NOT IN ('ID', 'lID')")->orderBy("ID")->all();
                foreach ($arrSysField as $lPos => $sysField) {
                    $sysListSelect = new SysListSelect();
                    $sysListSelect->ID = NewID::make();
                    $sysListSelect->sObjectName = $sObjectName;
                    $sysListSelect->SysListID = $sysList->ID;
                    $sysListSelect->SysFieldID = $sysField->ID;
                    $sysListSelect->lPos = $lPos;
                    $sysListSelect->save();

                    $sysListAdvancedSearch = new SysListAdvancedSearch();
                    $sysListAdvancedSearch->ID = NewID::make();
                    $sysListAdvancedSearch->sObjectName = $sObjectName;
                    $sysListAdvancedSearch->SysListID = $sysList->ID;
                    $sysListAdvancedSearch->SysFieldID = $sysField->ID;
                    $sysListAdvancedSearch->lPos = $lPos;
                    $sysListAdvancedSearch->save();
                }

                if ($ParentID) {
                    $sysList = new SysList();
                    $sysList->ID = NewID::make();
                    $sysList->sObjectName = $sObjectName;
                    $sysList->sKey = "Main.$ModuleID.$sTable.Info.All";
                    $sysList->sName = $sysObject->sName;
                    $sysList->sOrderBy = $sTable . ".dNewDate DESC";
                    $sysList->sDataPowerField = 'OwnerID';
                    $sysList->bCanPage = 1;
                    $sysList->bCanSort = 1;
                    $sysList->bCanBat = 1;
                    $sysList->bDefault = 0;
                    $sysList->sDataRegion = "datapower";
                    $sysList->sType = "info";
                    $sysList->save();

                    //除了ID字段，都显示到视图上
                    $arrSysField = SysField::find()->select([
                        'ID',
                        'sName'
                    ])->where("sObjectName='$sObjectName' AND sFieldAs NOT IN ('ID', 'lID')")->orderBy("ID")->all();
                    foreach ($arrSysField as $lPos => $sysField) {
                        $sysListSelect = new SysListSelect();
                        $sysListSelect->ID = NewID::make();
                        $sysListSelect->sObjectName = $sObjectName;
                        $sysListSelect->SysListID = $sysList->ID;
                        $sysListSelect->SysFieldID = $sysField->ID;
                        $sysListSelect->lPos = $lPos;
                        $sysListSelect->save();

                        $sysListAdvancedSearch = new SysListAdvancedSearch();
                        $sysListAdvancedSearch->ID = NewID::make();
                        $sysListAdvancedSearch->sObjectName = $sObjectName;
                        $sysListAdvancedSearch->SysListID = $sysList->ID;
                        $sysListAdvancedSearch->SysFieldID = $sysField->ID;
                        $sysListAdvancedSearch->lPos = $lPos;
                        $sysListAdvancedSearch->save();
                    }
                }


                /**
                 * 保存界面配置数据
                 */
                //保存新建、编辑界面配置数据
                $sysUI = new SysUI();
                $sysUI->ID = NewID::make();
                $sysUI->sObjectName = $sObjectName;
                $sysUI->sName = "新建/编辑";
                $sysUI->sInterface = "new,edit";
                $sysUI->save();

                $sysUIFieldClass = new SysUIFieldClass();
                $sysUIFieldClass->ID = NewID::make();
                $sysUIFieldClass->sObjectName = $sObjectName;
                $sysUIFieldClass->SysUIID = $sysUI->ID;
                $sysUIFieldClass->sName = "基本信息";
                $sysUIFieldClass->bExpand = 1;
                $sysUIFieldClass->lPos = 0;
                $sysUIFieldClass->save();

                //名称字段做为基本信息的字段
                $sysUIFieldClassField = new SysUIFieldClassField();
                $sysUIFieldClassField->ID = NewID::make();
                $sysUIFieldClassField->sObjectName = $sObjectName;
                $sysUIFieldClassField->SysUIID = $sysUI->ID;
                $sysUIFieldClassField->SysUIFieldClassID = $sysUIFieldClass->ID;
                $sysUIFieldClassField->SysFieldID = $NameFieldID;
                $sysUIFieldClassField->lGroup = 1;
                $sysUIFieldClassField->lPos = 0;
                $sysUIFieldClassField->save();

                //保存详细界面配置数据
                $sysUI = new SysUI();
                $sysUI->ID = NewID::make();
                $sysUI->sObjectName = $sObjectName;
                $sysUI->sName = "详细界面";
                $sysUI->sInterface = "view";
                $sysUI->save();

                $sysUIFieldClass = new SysUIFieldClass();
                $sysUIFieldClass->ID = NewID::make();
                $sysUIFieldClass->sObjectName = $sObjectName;
                $sysUIFieldClass->SysUIID = $sysUI->ID;
                $sysUIFieldClass->sName = "基本信息";
                $sysUIFieldClass->lPos = 0;
                $sysUIFieldClass->bExpand = 1;
                $sysUIFieldClass->save();

                //名称字段做为基本信息的字段
                $sysUIFieldClassField = new SysUIFieldClassField();
                $sysUIFieldClassField->ID = NewID::make();
                $sysUIFieldClassField->SysUIID = $sysUI->ID;
                $sysUIFieldClassField->sObjectName = $sObjectName;
                $sysUIFieldClassField->SysUIFieldClassID = $sysUIFieldClass->ID;
                $sysUIFieldClassField->SysFieldID = $NameFieldID;
                $sysUIFieldClassField->lGroup = 1;
                $sysUIFieldClassField->lPos = 0;
                $sysUIFieldClassField->save();

                //保存SysNavItem
                $sysNavItem = new SysNavItem();
                $sysNavItem->ID = NewID::make();
                $sysNavItem->sName = $sName;
                $sysNavItem->sObjectName = $sObjectName;
                $sysNavItem->sAction = strtolower("$sObjectName/home");
                $sysNavItem->bActive = 1;
                $sysNavItem->save();

                //提交
                $transaction->commit();
            } catch (\yii\db\Exception $e) {
                $transaction->rollBack();
                Yii::warning($e->getMessage());
                $this->lStatus = 0;
                $this->sErrMsg = $e->getMessage();
            }
        }

        $this->output($arrReturn);
    }

    /**
     * 附加对象保存
     */
    public function actionAttachsave()
    {
        $arrReturn = [];

        $sTable = ucfirst(Yii::$app->request->post('sTable'));
        $ObjectID = ucfirst(Yii::$app->request->post('ObjectID'));
        $sName = Yii::$app->request->post('sName');
        $ModuleID = Yii::$app->request->post('ModuleID');
        $DataSourceID = Yii::$app->request->post('DataSourceID');
        $bWorkFlow = Yii::$app->request->post('bWorkFlow');
        $bDetailObject = Yii::$app->request->post('bDetailObject');
        $sPrimartKeyType = Yii::$app->request->post('sPrimartKeyType');
        $sIDField = Yii::$app->request->post('sIDField');
        $sNameField = Yii::$app->request->post('sNameField');
        $sObjectName = $ModuleID . "/" . $ObjectID;

        if (StrTool::isEmpty($sTable)) {
            $this->lStatus = 0;
            $this->sErrMsg = "对象的表名不能为空。";
        } elseif (StrTool::isEmpty($sName)) {
            $this->lStatus = 0;
            $this->sErrMsg = "对象的名称不能为空。";
        } elseif (StrTool::isEmpty($ModuleID)) {
            $this->lStatus = 0;
            $this->sErrMsg = "模块是必选。";
        } elseif (StrTool::isEmpty($ObjectID)) {
            $this->lStatus = 0;
            $this->sErrMsg = "对象ID是必填。";
        } elseif (StrTool::isEmpty($DataSourceID)) {
            $this->lStatus = 0;
            $this->sErrMsg = "数据源是必选。";
        } elseif ($sIDField == $sNameField) {
            $this->lStatus = 0;
            $this->sErrMsg = "主键字段和名称字段不能相同";
        } else {
            //检查对象是否已经存在
            $object = SysObject::find()->where("sObjectName='$sObjectName'")->one();
            if ($object) {
                $this->lStatus = 0;
                $this->sErrMsg = "对象ID是" . $sObjectName . "的对象已经存在。";
            } else {
                //检查名称是否重复
                $object = SysObject::find()->where("ModuleID='$ModuleID' AND sName='$sName'")->one();
                if ($object) {
                    $this->lStatus = 0;
                    $this->sErrMsg = "对象名称是" . $sName . "的对象已经存在。";
                }
            }
        }

        if ($this->lStatus == 1) {

            //通过数据源获取数据库连接
            $sDataSourceKey = "ds_db";
            $db = Yii::$app->$sDataSourceKey;

            //准备事务
            $transaction = $db->beginTransaction();

            try {

                //保存基本信息到SysObject表
                $sysObject = new SysObject();
                $sysObject->sTable = $sTable;
                $sysObject->sName = $sName;
                $sysObject->ModuleID = $ModuleID;
                $sysObject->sObjectName = $sObjectName;
                $sysObject->DataSourceID = $DataSourceID;
                $sysObject->bWorkFlow = $bWorkFlow;
                $sysObject->bDetailObject = $bDetailObject;
                $sysObject->bUDF = 1;
                $sysObject->sIDField = $sIDField;
                $sysObject->sNameField = $sNameField;
                $sysObject->save();

                // 保存缺省的操作权限到SysObjectOperator，缺省的操作权限：新建、编辑、删除、查看详情、列表
                $lPos = 0;
                $arrOpera = [
                    'new' => '新建',
                    'edit' => '编辑',
                    'del' => '删除',
                    'view' => '查看详情',
                    'home' => '主页',
                    'changeowner' => '更改拥有者'
                ];
                foreach ($arrOpera as $sOperator => $sOperatorName) {
                    $sysOpera = new SysObjectOperator();
                    $sysOpera->ID = NewID::make();
                    $sysOpera->sObjectName = $sObjectName;
                    $sysOpera->sOperator = $sOperator;
                    $sysOpera->sName = $sOperatorName;
                    $sysOpera->lPos = $lPos;
                    $sysOpera->save();
                    $lPos++;
                }

                /**
                 * 保存属性到字典表SysField
                 */
                if ($sPrimartKeyType == 'autoincrement') {
                    $intField = SysField::createField("int");
                    $intField->addField([
                        'sName' => $sIDField,
                        'sObjectName' => $sObjectName,
                        'sFieldAs' => $sIDField,
                        'sLinkField' => $sNameField,
                        'sFieldClassType' => 'list,view',
                        'bPrimaryKey' => 1, //是主键
                        'sPrimartKeyType' => $sPrimartKeyType, //混合型主键
                        'lLength' => 11
                    ]);
                } else {
                    $textField = SysField::createField("text");
                    $textField->addField([
                        'sName' => $sIDField,
                        'sObjectName' => $sObjectName,
                        'sFieldAs' => $sIDField,
                        'sLinkField' => $sNameField,
                        'sFieldClassType' => 'list,view',
                        'bPrimaryKey' => 1, //是主键
                        'sPrimartKeyType' => $sPrimartKeyType, //混合型主键
                        'lSysPrimartKeyValueLength' => 32, //主键长度
                        'lLength' => 32
                    ]);
                }


                //创建标题属性
                $textField = SysField::createField("text");
                $NameFieldID = $textField->addField([
                    'sName' => '名称',
                    'sObjectName' => $sObjectName,
                    'sFieldAs' => $sNameField,
                    'sFieldClassType' => 'list,view,edit,new',
                    'sRefKey' => $sObjectName,
                    'sLinkField' => $sIDField,
                    'bNull' => 1
                ]);

                /**
                 * 保存视图数据
                 */
                $sysList = new SysList();
                $sysList->ID = NewID::make();
                $sysList->sObjectName = $sObjectName;
                $sysList->sKey = "Main.$ModuleID.$ObjectID.List.All";
                $sysList->sDataPowerField = 'OwnerID';
                $sysList->sName = "我管理的";
                $sysList->bCanPage = 1;
                $sysList->bCanSort = 1;
                $sysList->bCanBat = 1;
                $sysList->bDefault = 1;
                $sysList->sDataRegion = "datapower";
                $sysList->sType = "list";
                $sysList->save();

                //除了ID字段，都显示到视图上
                $arrSysField = SysField::find()->select([
                    'ID',
                    'sName'
                ])->where("sObjectName='$sObjectName' AND sFieldAs NOT IN ('$sIDField')")->all();
                foreach ($arrSysField as $lPos => $sysField) {
                    $sysListSelect = new SysListSelect();
                    $sysListSelect->ID = NewID::make();
                    $sysListSelect->sObjectName = $sObjectName;
                    $sysListSelect->SysListID = $sysList->ID;
                    $sysListSelect->SysFieldID = $sysField->ID;
                    $sysListSelect->lPos = $lPos;
                    $sysListSelect->save();

                    $sysListAdvancedSearch = new SysListAdvancedSearch();
                    $sysListAdvancedSearch->ID = NewID::make();
                    $sysListAdvancedSearch->sObjectName = $sObjectName;
                    $sysListAdvancedSearch->SysListID = $sysList->ID;
                    $sysListAdvancedSearch->SysFieldID = $sysField->ID;
                    $sysListAdvancedSearch->lPos = $lPos;
                    $sysListAdvancedSearch->save();
                }

                $sysList = new SysList();
                $sysList->ID = NewID::make();
                $sysList->sObjectName = $sObjectName;
                $sysList->sKey = "Main.$ModuleID.$ObjectID.Refer.All";
                $sysList->sDataPowerField = 'OwnerID';
                $sysList->sName = "参照";
                $sysList->bCanPage = 1;
                $sysList->bCanSort = 1;
                $sysList->bCanBat = 1;
                $sysList->bDefault = 0;
                $sysList->sDataRegion = "datapower";
                $sysList->sType = "refer";
                $sysList->save();

                //除了ID字段，都显示到视图上
                $arrSysField = SysField::find()->select([
                    'ID',
                    'sName'
                ])->where("sObjectName='$sObjectName' AND sFieldAs NOT IN ('$sIDField')")->all();
                foreach ($arrSysField as $lPos => $sysField) {
                    $sysListSelect = new SysListSelect();
                    $sysListSelect->ID = NewID::make();
                    $sysListSelect->sObjectName = $sObjectName;
                    $sysListSelect->SysListID = $sysList->ID;
                    $sysListSelect->SysFieldID = $sysField->ID;
                    $sysListSelect->lPos = $lPos;
                    $sysListSelect->save();

                    $sysListAdvancedSearch = new SysListAdvancedSearch();
                    $sysListAdvancedSearch->ID = NewID::make();
                    $sysListAdvancedSearch->sObjectName = $sObjectName;
                    $sysListAdvancedSearch->SysListID = $sysList->ID;
                    $sysListAdvancedSearch->SysFieldID = $sysField->ID;
                    $sysListAdvancedSearch->lPos = $lPos;
                    $sysListAdvancedSearch->save();
                }

                /**
                 * 保存界面配置数据
                 */
                //保存新建、编辑界面配置数据
                $sysUI = new SysUI();
                $sysUI->ID = NewID::make();
                $sysUI->sObjectName = $sObjectName;
                $sysUI->sName = "新建/编辑";
                $sysUI->sInterface = "new,edit";
                $sysUI->save();

                $sysUIFieldClass = new SysUIFieldClass();
                $sysUIFieldClass->ID = NewID::make();
                $sysUIFieldClass->sObjectName = $sObjectName;
                $sysUIFieldClass->SysUIID = $sysUI->ID;
                $sysUIFieldClass->sName = "基本信息";
                $sysUIFieldClass->bExpand = 1;
                $sysUIFieldClass->lPos = 0;
                $sysUIFieldClass->save();

                //名称字段做为基本信息的字段
                $sysUIFieldClassField = new SysUIFieldClassField();
                $sysUIFieldClassField->ID = NewID::make();
                $sysUIFieldClassField->sObjectName = $sObjectName;
                $sysUIFieldClassField->SysUIID = $sysUI->ID;
                $sysUIFieldClassField->SysUIFieldClassID = $sysUIFieldClass->ID;
                $sysUIFieldClassField->SysFieldID = $NameFieldID;
                $sysUIFieldClassField->lGroup = 1;
                $sysUIFieldClassField->lPos = 0;
                $sysUIFieldClassField->save();

                //保存详细界面配置数据
                $sysUI = new SysUI();
                $sysUI->ID = NewID::make();
                $sysUI->sObjectName = $sObjectName;
                $sysUI->sName = "详细界面";
                $sysUI->sInterface = "view";
                $sysUI->save();

                $sysUIFieldClass = new SysUIFieldClass();
                $sysUIFieldClass->ID = NewID::make();
                $sysUIFieldClass->sObjectName = $sObjectName;
                $sysUIFieldClass->SysUIID = $sysUI->ID;
                $sysUIFieldClass->sName = "基本信息";
                $sysUIFieldClass->lPos = 0;
                $sysUIFieldClass->bExpand = 1;
                $sysUIFieldClass->save();

                //名称字段做为基本信息的字段
                $sysUIFieldClassField = new SysUIFieldClassField();
                $sysUIFieldClassField->ID = NewID::make();
                $sysUIFieldClassField->sObjectName = $sObjectName;
                $sysUIFieldClassField->SysUIID = $sysUI->ID;
                $sysUIFieldClassField->SysUIFieldClassID = $sysUIFieldClass->ID;
                $sysUIFieldClassField->SysFieldID = $NameFieldID;
                $sysUIFieldClassField->lGroup = 1;
                $sysUIFieldClassField->lPos = 0;
                $sysUIFieldClassField->save();

                //保存SysNavItem
                $sysNavItem = new SysNavItem();
                $sysNavItem->ID = NewID::make();
                $sysNavItem->sName = $sName;
                $sysNavItem->sObjectName = $sObjectName;
                $sysNavItem->sAction = strtolower("$ModuleID/$ObjectID/home");
                $sysNavItem->bActive = 1;
                $sysNavItem->save();

                //提交
                $transaction->commit();
            } catch (\yii\db\Exception $e) {
                $transaction->rollBack();

                $this->lStatus = 0;
                $this->sErrMsg = $e->getMessage();
            }
        }

        $this->output($arrReturn);
    }

    /**
     * 新建对象保存
     */
    public function actionNewextendsave()
    {
        $arrReturn = [];

        $ID = ucfirst(Yii::$app->request->post('ID'));
        $sName = Yii::$app->request->post('sName');
        $DataSourceID = Yii::$app->request->post('DataSourceID');
        $ModuleID = Yii::$app->request->post('ModuleID');
        $sObjectName = Yii::$app->request->post('sObjectName');
        $sRelation = Yii::$app->request->post('relation');

        if (StrTool::isEmpty($ID)) {
            $this->lStatus = 0;
            $this->sErrMsg = "对象的ID不能为空。";
        } elseif (StrTool::isEmpty($sName)) {
            $this->lStatus = 0;
            $this->sErrMsg = "对象的名称不能为空。";
        } elseif (StrTool::isEmpty($ModuleID)) {
            $this->lStatus = 0;
            $this->sErrMsg = "模块是必选。";
        } elseif (StrTool::isEmpty($DataSourceID)) {
            $this->lStatus = 0;
            $this->sErrMsg = "数据源是必选。";
        } else {
            //检查对象是否已经存在
            $object = SysObject::find()->where("sObjectName='$sObjectName'")->one();
            if ($object) {
                $this->lStatus = 0;
                $this->sErrMsg = "对象ID是" . $ID . "的对象已经存在。";
            } else {
                //检查名称是否重复
                $object = SysObject::find()->where("ModuleID='$ModuleID' AND sName='$sName'")->one();
                if ($object) {
                    $this->lStatus = 0;
                    $this->sErrMsg = "对象名称是" . $sName . "的对象已经存在。";
                }
            }
        }


        if ($this->lStatus == 1) {

            //通过数据源获取数据库连接
            $sDataSourceKey = "ds_$DataSourceID";
            $db = Yii::$app->$sDataSourceKey;

            //准备事务
            $transaction = $db->beginTransaction();

            $sysObject = SysObject::findOne(['sObjectName' => $sObjectName]);

            //找出被扩展对象的主键，确定它的类型（自增型还是自动自带类型）
            $sysPKField = SysField::find()->select(['sPrimartKeyType'])->where("sObjectName='$sObjectName' AND sFieldAs='" . $sysObject->sIDField . "'")->one();

            //查询被扩展对象的信息
            $extendObject = SysObject::find()->where("sObjectName='$sObjectName'")->one();
            $ObjectID = $extendObject->sTable;

            try {
                $arrTable = $db->getSchema()->getTableNames();
                foreach ($arrTable as $sTable) {
                    if ($ID == $sTable) {
                        throw new \yii\db\Exception($ID . '表已经存在');
                    }
                }

                //创建物理的数据库表	                
                $sCreateSQL = "CREATE TABLE `$ID` (
            	                `lID` int(11) NOT NULL AUTO_INCREMENT,
            	                `ID` varchar(32) DEFAULT NULL,
            	                `sName` varchar(250) DEFAULT NULL,
            	                `{$ObjectID}ID` " . ($sysPKField->sPrimartKeyType == 'autoincrement' ? "int(11)" : "varchar(32)") . " DEFAULT NULL,
            	                PRIMARY KEY (`lID`),
            	                UNIQUE KEY `ID` (`ID`),
            	                " . ($sRelation == 'one' ? "UNIQUE KEY `{$ObjectID}ID` (`{$ObjectID}ID`)" : "KEY `{$ObjectID}ID` (`{$ObjectID}ID`)") . "
            	                ) ENGINE=InnoDB DEFAULT CHARSET=utf8;";
                $db->createCommand($sCreateSQL)->execute();

                //保存基本信息到SysObject表
                $sysObject = new SysObject();
                $sysObject->sTable = $ID;
                $sysObject->sName = $sName;
                $sysObject->ExtendID = $sObjectName;
                $sysObject->ModuleID = $ModuleID;
                $sysObject->sObjectName = "$ModuleID/$ID";
                $sysObject->DataSourceID = $DataSourceID;
                $sysObject->sType = 'Extended';
                $sysObject->bUDF = 1;
                $sysObject->save();

                /**
                 * 保存属性到字典表SysField
                 */
                //创建属性：lID
                $textField = SysField::createField("int");
                $textField->addField([
                    'sName' => 'lID',
                    'sObjectName' => "$ModuleID/$ID",
                    'sFieldAs' => 'lID',
                    'bPrimaryKey' => 1, //是主键
                    'sPrimartKeyType' => 'autoincrement', //混合型主键
                    'lLength' => 11
                ]);

                //创建属性：ID
                $textField = SysField::createField("text");
                $textField->addField([
                    'sName' => 'ID',
                    'sObjectName' => "$ModuleID/$ID",
                    'sFieldAs' => 'ID',
                    'bPrimaryKey' => 1, //是主键
                    'sPrimartKeyType' => 'system', //混合型主键
                    'lSysPrimartKeyValueLength' => 32, //主键长度
                    'lLength' => 32
                ]);

                //创建属性: sName
                $textField = SysField::createField("text");
                $NameFieldID = $textField->addField([
                    'sName' => '名称',
                    'sObjectName' => "$ModuleID/$ID",
                    'sFieldAs' => 'sName',
                    'sFieldClassType' => 'list,view,edit,new',
                    'bNull' => 1
                ]);

                //创建连接属性
                $listTableField = SysField::createField("listtable");
                $listTableField->addField([
                    'sName' => $extendObject->sName,
                    'sObjectName' => "$ModuleID/$ID",
                    'sFieldAs' => "{$ObjectID}ID",
                    'sFieldClassType' => 'list,view,new,edit',
                    'sShwField' => 'sName',
                    'sRefKey' => $sObjectName,
                    'bNull' => 1
                ]);

                //提交
                $transaction->commit();
            } catch (\yii\db\Exception $e) {
                $transaction->rollBack();

                $this->lStatus = 0;
                $this->sErrMsg = $e->getMessage();
            }
        }


        $this->output($arrReturn);
    }


    /**
     * 删除对象
     */
    public function actionDel()
    {
        $arrReturn = [];

        $sObjectName = Yii::$app->request->post('sObjectName');

        //关联的子对象、扩展对象都要删除掉
        $arrObject = SysObject::find()->where("ParentID='$sObjectName' OR ExtendID='$sObjectName' OR sObjectName='$sObjectName'")->all();
        foreach ($arrObject as $object) {

            $sObjectName = $object->sObjectName;
            $ModuleID = $object->ModuleID;

            if (!$object) {
                $this->lStatus = 0;
                $this->sErrMsg = "对象" . $sObjectName . "不存在。";
            }/*  elseif (!$object->bUDF) {
    	        $this->lStatus = 0;
    	        $this->sErrMsg = "对象".$sObjectName."是系统对象，不能删除。";	        
    	    } */

            if ($this->lStatus == 0) {
                $this->output($arrReturn);
                return;
            }

            $sDataSourceKey = "ds_" . $object->DataSourceID;
            $db = Yii::$app->$sDataSourceKey;

            //准备事务
            $transaction = $db->beginTransaction();

            try {

                //删除物理表
                //$db->createCommand("DROP TABLE `$sObjectName`")->execute();

                //删除SysObject
                SysObject::deleteAll("sObjectName='$sObjectName'");

                //删除SysField
                SysField::deleteAll("sObjectName='$sObjectName'");

                //删除SysFilterDetail
                SysFilterDetail::deleteAll("sObjectName='$sObjectName'");

                //删除SysList
                SysList::deleteAll("sObjectName='$sObjectName'");

                //删除SysListSelect
                SysListSelect::deleteAll("sObjectName='$sObjectName'");

                //删除SysObjectOperator
                SysObjectOperator::deleteAll("sObjectName='$sObjectName'");

                //删除SysUI
                SysUI::deleteAll("sObjectName='$sObjectName'");

                //删除SysUIFieldClass
                SysUIFieldClass::deleteAll("sObjectName='$sObjectName'");

                //删除SysUIFieldClassField
                SysUIFieldClassField::deleteAll("sObjectName='$sObjectName'");

                //删除SysUIFieldClassRule
                SysUIFieldClassRule::deleteAll("sObjectName='$sObjectName'");

                //删除SysFieldEnum
                SysFieldEnum::deleteAll("sObjectName='$sObjectName'");

                //删除SysOrgObject
                SysOrgObject::deleteAll("sObjectName='$sObjectName'");

                //删除SysOrgOperator
                SysOrgOperator::deleteAll("sObjectName='$sObjectName'");

                //删除目录
                //File::removeDirectory(Yii::getAlias('@app')."/modules/".strtolower($ModuleID)."/".strtolower($sObjectName));

                //SysNavItem
                SysNavItem::deleteAll("sObjectName='$sObjectName'");

                //工作台方案的菜单项
                SysSolutionNavItem::deleteAll("sObjectName='$sObjectName'");

                //高级搜索项
                SysListAdvancedSearch::deleteAll("sObjectName='$sObjectName'");

                $transaction->commit();

            } catch (\yii\db\Exception $e) {
                $transaction->rollBack();

                $this->lStatus = 0;
                $this->sErrMsg = $e->getMessage();
            }
        }

        $this->output($arrReturn);
    }

    /**
     * 编辑对象
     */
    public function actionEdit()
    {
        $arrReturn = [];

        $sObjectName = Yii::$app->request->post('sObjectName');
        $ModuleID = Yii::$app->request->post('ModuleID');

        $object = SysObject::find()->where("sObjectName='$sObjectName'")->one();
        if (!$object) {
            $this->lStatus = 0;
            $this->sErrMsg = "对象" . $sObjectName . "不存在。";
        }

        if ($this->lStatus == 0) {
            $this->output($arrReturn);
            return;
        }

        $data = $object->toArray();
        $data['sModuleName'] = $object->module ? $object->module->sName : null;
        $data['sParentName'] = $object->parent ? $object->parent->sName : null;
        $data['sDataSourceName'] = $object->datasource ? $object->datasource->sName : null;

        $arrReturn['object'] = $data;

        //获取模块数据
        $arrReturn['arrModule'] = SysModule::find()->asArray()->all();

        //获取数据源数据
        $arrReturn['arrDatasource'] = SysDataSource::find()->asArray()->all();

        $this->output($arrReturn);
    }

    /**
     * 编辑对象保存
     */
    public function actionEditsave()
    {
        $arrReturn = [];

        $sObjectName = Yii::$app->request->post('sObjectName');
        $ModuleID = Yii::$app->request->post('ModuleID');

        $object = SysObject::find()->where("sObjectName='$sObjectName'")->one();
        if (!$object) {
            $this->lStatus = 0;
            $this->sErrMsg = "对象" . $sObjectName . "不存在。";
        }

        $object->sName = Yii::$app->request->post('sName');
        $object->DataSourceID = Yii::$app->request->post('DataSourceID');
        $object->save();

        $this->output($arrReturn);
    }

    /**
     * 获取数据库表
     */
    public function actionGetobjects()
    {
        $arrReturn = [];

        $DataSourceID = Yii::$app->request->post('DataSourceID');

        $sDataSourceKey = "ds_$DataSourceID";
        $db = Yii::$app->$sDataSourceKey;

        $arrTableExist = [];
        $arrSysObject = SysObject::find()->where("DataSourceID='$DataSourceID'")->all();
        foreach ($arrSysObject as $object) {
            $arrTableExist[] = $object->sTable;
        }

        $arrReturn['arrTable'] = [];
        $arrTable = $db->getSchema()->getTableNames();
        foreach ($arrTable as $sTable) {
            if (!in_array($sTable, $arrTableExist)) {
                $arrReturn['arrTable'][] = $sTable;
            }
        }

        $this->output($arrReturn);
    }

    /**
     * 获取数据库表的字段
     */
    public function actionGetfields()
    {
        $arrReturn = [];

        $DataSourceID = Yii::$app->request->post('DataSourceID');
        $sTable = Yii::$app->request->post('sTable');

        $sDataSourceKey = "ds_$DataSourceID";
        $db = Yii::$app->$sDataSourceKey;

        $arrReturn['arrField'] = [];
        $tableSchema = $db->getSchema()->getTableSchema($sTable, true);

        foreach ($tableSchema->columns as $sFieldName => $column) {
            $arrReturn['arrField'][] = $sFieldName;
        }

        $this->output($arrReturn);
    }

    /**
     * 导出对象
     */
    public function actionExport()
    {
        $arrReturn = [];

        $sObjectName = Yii::$app->request->post('sObjectName');
        $ModuleID = Yii::$app->request->post('ModuleID');

        $arrSql = [];

        //
        $arrSql[] = "DELETE FROM `SysObject` WHERE sObjectName='$sObjectName';";
        $result = Yii::$app->db->createCommand("SELECT * FROM SysObject WHERE sObjectName='$sObjectName'")->queryAll();
        $arrSql[] = $this->formatSql('SysObject', $result);

        $arrSql[] = "DELETE FROM `SysField` WHERE sObjectName='$sObjectName';";
        $result = Yii::$app->db->createCommand("SELECT * FROM `SysField` WHERE sObjectName='$sObjectName'")->queryAll();
        $arrSql[] = $this->formatSql('SysField', $result);

        $arrSql[] = "DELETE FROM `SysFieldEnum` WHERE sObjectName='$sObjectName';";
        $result = Yii::$app->db->createCommand("SELECT * FROM `SysFieldEnum` WHERE sObjectName='$sObjectName'")->queryAll();
        $arrSql[] = $this->formatSql('SysFieldEnum', $result);

        $arrSql[] = "DELETE FROM `SysFilterDetail` WHERE sObjectName='$sObjectName';";
        $result = Yii::$app->db->createCommand("SELECT * FROM `SysFilterDetail` WHERE sObjectName='$sObjectName'")->queryAll();
        $arrSql[] = $this->formatSql('SysFilterDetail', $result);


        $arrSql[] = "DELETE FROM `SysList` WHERE sObjectName='$sObjectName';";
        $result = Yii::$app->db->createCommand("SELECT * FROM `SysList` WHERE sObjectName='$sObjectName'")->queryAll();
        $arrSql[] = $this->formatSql('SysList', $result);

        $arrSql[] = "DELETE FROM `SysListAdvancedSearch` WHERE sObjectName='$sObjectName';";
        $result = Yii::$app->db->createCommand("SELECT * FROM `SysListAdvancedSearch` WHERE sObjectName='$sObjectName'")->queryAll();
        $arrSql[] = $this->formatSql('SysListAdvancedSearch', $result);

        $arrSql[] = "DELETE FROM `SysListSelect` WHERE sObjectName='$sObjectName';";
        $result = Yii::$app->db->createCommand("SELECT * FROM `SysListSelect` WHERE sObjectName='$sObjectName'")->queryAll();
        $arrSql[] = $this->formatSql('SysListSelect', $result);

        $arrSql[] = "DELETE FROM `SysNavItem` WHERE sObjectName='$sObjectName';";
        $result = Yii::$app->db->createCommand("SELECT * FROM `SysNavItem` WHERE sObjectName='$sObjectName'")->queryAll();
        $arrSql[] = $this->formatSql('SysNavItem', $result);

        $arrSql[] = "DELETE FROM `SysObjectOperator` WHERE sObjectName='$sObjectName';";
        $result = Yii::$app->db->createCommand("SELECT * FROM `SysObjectOperator` WHERE sObjectName='$sObjectName'")->queryAll();
        $arrSql[] = $this->formatSql('SysObjectOperator', $result);

        $arrSql[] = "DELETE FROM `SysUI` WHERE sObjectName='$sObjectName';";
        $result = Yii::$app->db->createCommand("SELECT * FROM `SysUI` WHERE sObjectName='$sObjectName'")->queryAll();
        $arrSql[] = $this->formatSql('SysUI', $result);

        $arrSql[] = "DELETE FROM `SysUIFieldClass` WHERE sObjectName='$sObjectName';";
        $result = Yii::$app->db->createCommand("SELECT * FROM `SysUIFieldClass` WHERE sObjectName='$sObjectName'")->queryAll();
        $arrSql[] = $this->formatSql('SysUIFieldClass', $result);

        $arrSql[] = "DELETE FROM `SysUIFieldClassField` WHERE sObjectName='$sObjectName';";
        $result = Yii::$app->db->createCommand("SELECT * FROM `SysUIFieldClassField` WHERE sObjectName='$sObjectName'")->queryAll();
        $arrSql[] = $this->formatSql('SysUIFieldClassField', $result);

        $arrSql[] = "DELETE FROM `SysUIFieldClassRule` WHERE sObjectName='$sObjectName';";
        $result = Yii::$app->db->createCommand("SELECT * FROM `SysUIFieldClassRule` WHERE sObjectName='$sObjectName'")->queryAll();
        $arrSql[] = $this->formatSql('SysUIFieldClassRule', $result);

        $arrReturn['sSql'] = implode("<br>", $arrSql);


        $this->output($arrReturn);
    }

    public function formatSql($sTable, $arrResult)
    {
        $arrField = [];
        $tableSchema = Yii::$app->db->getSchema()->getTableSchema($sTable, true);
        foreach ($tableSchema->columns as $sFieldName => $column) {
            $arrField[] = $sFieldName;
        }

        $arrSql = [];
        foreach ($arrResult as $r) {
            $sInsertSql = "INSERT INTO `$sTable` (";

            foreach ($arrField as $sField) {
                $sInsertSql .= "`$sField`,";
            }

            $sInsertSql = trim($sInsertSql, ",");
            $sInsertSql .= ")VALUES(";

            $arr = [];
            foreach ($r as $v) {
                if (is_null($v)) {
                    $arr[] = "NULL";
                } else {
                    $arr[] = "'" . addslashes($v) . "'";
                }
            }

            $sInsertSql .= implode(",", $arr);
            $sInsertSql .= ");";

            $arrSql[] = $sInsertSql;
        }

        return implode("<br>", $arrSql);
    }
}
