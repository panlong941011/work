<?php

namespace myerm\backend\objectbuilder\controllers;

use Yii;
use myerm\backend\system\models\SysField;
use myerm\backend\system\models\SysObject;
use myerm\backend\system\models\SysModule;
use myerm\backend\system\models\SysFieldEnum;
use myerm\backend\common\libs\NewID;

/**
 * 对象管理器控制器-分类信息管理
 * ============================================================================
 * 版权所有 2010-2017 三只象（厦门）网络科技有限公司，并保留所有权利。
 * 网站地址: http://www.myerm.cn
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和
 * 使用；不允许对程序代码以任何形式任何目的的再发布。
 * ============================================================================
 * @author 陈鹭明  <lumingchen@qq.com>
 * @since 2015-11-14 16:11
 * @version v2.0
 */
class EnumController extends Controller
{
	/**
	 * 分类信息的列表
	 * @author Mars <lumingchen@qq.com>
	 * @since 2015-11-14 16:11
	 */
	public function actionList()
	{
	    $arrReturn = [];

	    $sObjectName = Yii::$app->request->post('sObjectName');
	    $sKeyword = Yii::$app->request->post('sKeyword');
	    $FieldID = Yii::$app->request->post('FieldID');

	    $arrField = SysField::find()->where("sObjectName='$sObjectName' AND (sDataType='List' OR sDataType='MultiList') AND sEnumTable='System/SysFieldEnum'")->orderBy("sFieldAs")->asArray()->all();

	    if (!$FieldID) {
	        $FieldID = $arrField[0]['ID'];
	    } else {
	        $field = SysField::findOne($FieldID);
	        $FieldID = $field->sRefKey;
	    }

	    $arrReturn['arrField'] = $arrField;

	    if ($sKeyword) {
	        $arrEnumList = SysFieldEnum::find()->where("SysFieldID='$FieldID' AND sName LIKE '%".$sKeyword."%'")->orderBy("lPos")->all();
	    } else {
            $arrEnumList = SysFieldEnum::find()->where("SysFieldID='$FieldID'")->orderBy("lPos")->all();
	    }

	    $arrReturn['arrEnumList'] = [];
	    foreach ($arrEnumList as $enum) {
	        $data = $enum->toArray();
	        $data['sParentName'] = $enum->parent->sName;
	        $arrReturn['arrEnumList'][] = $data;
	    }

	    $arrReturn['FieldID'] = $FieldID;

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

	    $sObjectName = Yii::$app->request->post('sObjectName');
	    $FieldID = Yii::$app->request->post('FieldID');

	    $arrEnumList = SysFieldEnum::find()->where("SysFieldID='$FieldID'")->orderBy("UpID,lPos")->asArray()->all();

	    $arrReturn['arrEnumList'] = [];
	    foreach ($arrEnumList as $enum) {
	        $arrReturn['arrEnumList'][$enum['UpID'] ? $enum['UpID'] : '0'][] = $enum;
	    }

	    $this->output($arrReturn);
	}

	/**
	 * 编辑分类信息
	 * @author Mars <lumingchen@qq.com>
	 * @since 2015-11-12 20:23
	 */
	public function actionEdit()
	{
	    $arrReturn = [];

	    $sObjectName = Yii::$app->request->post('sObjectName');
	    $FieldID = Yii::$app->request->post('FieldID');
	    $ID = Yii::$app->request->post('ID');

	    $arrReturn['enum'] = SysFieldEnum::findOne($ID)->toArray();

	    //上级的数据
	    $arrReturn['arrEnumList'] = [];
	    $arrEnumList = SysFieldEnum::find()->where("SysFieldID='$FieldID' AND ID<>'$ID'")->orderBy("UpID,lPos")->asArray()->all();
	    foreach ($arrEnumList as $enum) {
	        $arrReturn['arrEnumList'][$enum['UpID'] ? $enum['UpID'] : '0'][] = $enum;
	    }

	    $this->output($arrReturn);
	}

	/**
	 * 编辑分类信息保存
	 * @author Mars <lumingchen@qq.com>
	 * @since 2015-11-12 20:23
	 */
	public function actionEditsave()
	{
	    $arrReturn = [];

	    $data = [];
	    $data['ID'] = $_POST['ID'];
	    $data['sName'] = $_POST['sName'];
	    $data['sObjectName'] = $_POST['sObjectName'];
	    $data['SysFieldID'] = $_POST['SysFieldID'];
	    $data['bActive'] = $_POST['bActive'];
	    $data['bDefault'] = $_POST['bDefault'];
	    $data['UpID'] = $_POST['UpID'];
	    $data['bActive'] = $_POST['bActive'];
	    $data['lPos'] = $_POST['lPos'];

	    if (SysFieldEnum::find()->select(['ID'])->where("SysFieldID='{$data['SysFieldID']}' AND ID<>'{$data['ID']}' AND sName='{$data['sName']}'")->one()) {
	        $this->lStatus = 0;
	        $this->sErrMsg = "名称重复了";
	    } else {

	        if ($data['bDefault']) {
	            SysFieldEnum::updateAll(['bActive'=>0], "SysFieldID='{$data['SysFieldID']}'");
	        }

	        $sysFieldEnum = SysFieldEnum::findOne($data['ID']);
	        foreach ($data as $sField => $sValue) {
	            $sysFieldEnum->$sField = $sValue;
	        }

	        $sysFieldEnum->save();
	    }

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

	    $data = [];
	    $data['ID'] = $_POST['ID'] ? $_POST['ID'] : NewID::make();
	    $data['sName'] = $_POST['sName'];
	    $data['sObjectName'] = $_POST['sObjectName'];
	    $data['SysFieldID'] = $_POST['SysFieldID'];
	    $data['bActive'] = $_POST['bActive'];
	    $data['bDefault'] = $_POST['bDefault'];
	    $data['UpID'] = $_POST['UpID'];
	    $data['bActive'] = $_POST['bActive'];

	    $enum = SysFieldEnum::findOne($data['ID']);

	    if ($enum) {
	        $this->lStatus = 0;
	        $this->sErrMsg = "ID重复了";
	    } elseif (SysFieldEnum::find()->select(['ID'])->where("SysFieldID='{$data['SysFieldID']}' AND sName='{$data['sName']}'")->one()) {
	        $this->lStatus = 0;
	        $this->sErrMsg = "名称重复了";
	    } else {

	        if ($data['bDefault']) {
	            SysFieldEnum::updateAll(['bActive'=>0], "SysFieldID='{$data['SysFieldID']}'");
	        }

	        $sysFieldEnum = new SysFieldEnum();
	        foreach ($data as $sField => $sValue) {
	            $sysFieldEnum->$sField = $sValue;
	        }

	        $sysFieldEnum->lPos = SysFieldEnum::find()->where("SysFieldID='{$data['SysFieldID']}'")->max('lPos') + 1;
	        $sysFieldEnum->save();
	    }

	    $this->output($arrReturn);
	}


	/**
	 * 删除分类信息保存
	 * @author Mars <lumingchen@qq.com>
	 * @since 2015-11-14 18:07
	 */
	public function actionDel()
	{
	    $arrReturn = [];

        $arrID = explode(";", $_POST['IDs']);
        $sObjectName = $_POST['sObjectName'];

	    foreach ($arrID as $ID) {
	        $this->del($ID);
	    }

	    $this->output($arrReturn);
	}

	/**
	 * 递归删除
	 */
	private function del($ID)
	{
	    $sysFieldEnum = SysFieldEnum::find()->select(['ID'])->where("UpID='$ID'")->all();

	    foreach ($sysFieldEnum as $enum) {
	        $this->del($enum->ID);
	    }

	    SysFieldEnum::deleteAll("UpID='$ID' OR ID='$ID'");
	}

	/**
	 * 查询可以被参照的对象
	 */
	public function actionGetlisttableobjects()
	{
	    $arrReturn = [];
	    $arrReturn['arrRefObj'] = SysObject::find()->select(['sName', 'sObjectName', 'ModuleID', 'sTable'])->orderBy("ModuleID, sTable")->asArray()->all();
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
	    $arrRefField = SysField::find()->select(['sName', 'sObjectName', 'sFieldAs', 'ID'])->with('object')->where("sObjectName='$sObjectName' AND sDataType='ListTable'")->all();
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

	    $sObjectName = SysField::find()->select(['sObjectName'])->where(['ID'=>$referenceObj])->one()->sObjectName;

	    $arrReturn['arrRefField'] = SysField::find()->select(['sName', 'ID', 'sFieldAs'])->where("sObjectName='$sObjectName' AND (sDataType<>'ListTable' AND sDataType<>'AttachFile' AND sFieldAs<>'ID' AND RefFieldID IS NULL)")->asArray()->all();

	    $this->output($arrReturn);
	}

	/**
	 * 查询可以被做为上级列表的字段
	 */
	public function actionGetlistfields()
	{
	    $sObjectName = Yii::$app->request->post('sObjectName');

	    $arrReturn = [];
	    $arrReturn['arrListField'] = SysField::find()->select(['sName', 'ID', 'sFieldAs'])->where("sObjectName='$sObjectName' AND sDataType='List'")->orderBy("sName")->asArray()->all();
	    $arrReturn['arrRefObj'] = SysObject::find()->select(['sName', 'sObjectName', 'ModuleID', 'sTable'])->orderBy("ModuleID, sTable")->asArray()->all();
	    $arrReturn['arrModule'] = SysModule::find()->asArray()->all();

	    $this->output($arrReturn);
	}

	/**
	 * 查询可以公共对象的字段
	 */
	public function actionGetcommonfields()
	{
	    $arrReturn = [];

	    $arrReturn['arrRefField'] = SysField::find()->select(['sName', 'ID', 'sFieldAs'])->where("sObjectName='System/Common'")->asArray()->all();

	    $this->output($arrReturn);
	}

}
