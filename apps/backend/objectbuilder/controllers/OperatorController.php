<?php

namespace myerm\backend\objectbuilder\controllers;

use Yii;
use myerm\backend\system\models\SysUIFieldClass;
use myerm\backend\system\models\SysField;
use myerm\backend\system\models\SysUI;
use myerm\backend\common\libs\NewID;
use myerm\backend\system\models\SysUIFieldClassField;
use myerm\backend\system\models\SysObjectOperator;
use myerm\backend\system\models\SysUser;
use myerm\backend\system\models\SysDep;
use myerm\backend\system\models\SysRole;
use myerm\backend\system\models\SysOrgObject;
use myerm\backend\system\models\SysOrgOperator;

/**
 * 对象管理器控制器-操作权限
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
class OperatorController extends Controller
{
    /**
     * 操作权限的列表
     * @author Mars <lumingchen@qq.com>
     * @since 2015-11-19 15:08
     */
    public function actionList()
    {
        $arrReturn = [];
        
        $sObjectName = Yii::$app->request->post('sObjectName');
 
        
        $arrReturn['arrOperator'] = SysObjectOperator::find()->where("sObjectName='$sObjectName'")->asArray()->all();;

        
        $this->output($arrReturn);
    }
    
    /**
     * 新建操作权限
     * @author Mars <lumingchen@qq.com>
     * @since 2015-11-17 16:45
     */
    public function actionNew()
    {
        $arrReturn = [];
        
	    $arrReturn['arrUser'] = SysUser::find()->select(['lID AS ID', 'sName'])->where("bActive='1'")->asArray()->all();
	    $arrReturn['arrDep'] = SysDep::find()->select(['lID AS ID', 'sName'])->asArray("bActive='1'")->all();
	    $arrReturn['arrRole'] = SysRole::find()->select(['lID AS ID', 'sName'])->asArray()->all();
       
        $this->output($arrReturn);
    }
    
    /**
     * 新建操作权限保存
     * @author Mars <lumingchen@qq.com>
     * @since 2015-11-18 10:06
     */
    public function actionNewsave()
    {       
    	$sObjectName = Yii::$app->request->post('sObjectName');
	    $sName = Yii::$app->request->post('sName');
	    $sOperator = Yii::$app->request->post('sOperator');
	    $arrOrgObject = Yii::$app->request->post('OrgObject');
	    
	    if (SysObjectOperator::find()->where("sObjectName='$sObjectName' AND sOperator='$sOperator'")->one()) {
	        $this->lStatus = 0;
	        $this->sErrMsg = $sOperator."已经存在。";	        
	    } else {	        	    
    	    $sysObjectOperator = new SysObjectOperator();
    	    $sysObjectOperator->ID = NewID::make();
    	    $sysObjectOperator->sObjectName = $sObjectName;
    	    $sysObjectOperator->sName = $sName;
    	    $sysObjectOperator->sOperator = $sOperator;
    	    $sysObjectOperator->save();
    	    
    	    if ($arrOrgObject) {
    	        if ($arrOrgObject['System/SysUser']) {
    	            foreach ($arrOrgObject['System/SysUser'] as $ObjectID) {
    	                $sysOrgOperator = new SysOrgOperator();
    	                $sysOrgOperator->sObjectName = $sObjectName;
    	                $sysOrgOperator->sOrgName = 'System/SysUser';
    	                $sysOrgOperator->ObjectID = $ObjectID;
    	                $sysOrgOperator->sOperator = $sOperator;
    	                $sysOrgOperator->save();
    	            }
    	        }
    	        
    	        if ($arrOrgObject['System/SysRole']) {
    	        	foreach ($arrOrgObject['System/SysRole'] as $ObjectID) {
    	                $sysOrgOperator = new SysOrgOperator();
    	                $sysOrgOperator->sObjectName = $sObjectName;
    	                $sysOrgOperator->ObjectID = $ObjectID;
    	                $sysOrgOperator->sOrgName = 'System/SysRole';
    	                $sysOrgOperator->sOperator = $sOperator;
    	                $sysOrgOperator->save();
    	            }
    	        }
    	         
    	        if ($arrOrgObject['System/SysDep']) {
                    foreach ($arrOrgObject['System/SysDep'] as $ObjectID) {
    	                $sysOrgOperator = new SysOrgOperator();
    	                $sysOrgOperator->sObjectName = $sObjectName;
    	                $sysOrgOperator->ObjectID = $ObjectID;
    	                $sysOrgOperator->sOrgName = 'System/SysDep';
    	                $sysOrgOperator->sOperator = $sOperator;
    	                $sysOrgOperator->save();
    	            }
    	        }      
    	    }	    
	    }
	    
        $arrReturn = [];
        $this->output($arrReturn);        
    }
    
    /**
     * 编辑操作权限
     * @author Mars <lumingchen@qq.com>
     * @since 2015-11-18 14:55
     */
    public function actionEdit()
    {
        $arrReturn = [];
        
        $sObjectName = Yii::$app->request->post('sObjectName');
        $ID = Yii::$app->request->post('ID');

        $sysOperator = SysObjectOperator::findOne($ID);
         
        $arrReturn['sysOperator'] = $sysOperator->toArray();        
        $arrReturn['sysOperator']['roles'] = $sysOperator->roles;
        $arrReturn['sysOperator']['deps'] = $sysOperator->deps;
        $arrReturn['sysOperator']['users'] = $sysOperator->users;
        
        $arrReturn['arrUser'] = SysUser::find()->select(['lID AS ID', 'sName'])->where("bActive='1'")->asArray()->all();
        $arrReturn['arrDep'] = SysDep::find()->select(['lID AS ID', 'sName'])->asArray("bActive='1'")->all();
        $arrReturn['arrRole'] = SysRole::find()->select(['lID AS ID', 'sName'])->asArray()->all();
        
        $this->output($arrReturn);        
    }

    
    /**
     * 编辑操作权限保存
     * @author Mars <lumingchen@qq.com>
     * @since 2015-11-18 15:59
     */
    public function actionEditsave()
    {
        $ID = Yii::$app->request->post('ID');
        $sName = Yii::$app->request->post('sName');
        $sObjectName = Yii::$app->request->post('sObjectName');
        $sOperator = Yii::$app->request->post('sOperator');
        $arrOrgObject = Yii::$app->request->post('OrgObject');
                
        $sysObjectOperator = SysObjectOperator::findOne($ID);
        $sysObjectOperator->sName = $sName;

        if ($sOperator) {
            $sysObjectOperator->sOperator = $sOperator;
        }

        $sysObjectOperator->save();
                
        SysOrgOperator::deleteAll("sObjectName='$sObjectName' AND sOperator='".$sysObjectOperator->sOperator."'");
        
        if ($arrOrgObject) {
            if ($arrOrgObject['System/SysUser']) {
                foreach ($arrOrgObject['System/SysUser'] as $ObjectID) {
                    $sysOrgOperator = new SysOrgOperator();
                    $sysOrgOperator->sObjectName = $sObjectName;
                    $sysOrgOperator->sOrgName = 'System/SysUser';
                    $sysOrgOperator->ObjectID = $ObjectID;
                    $sysOrgOperator->sOperator = $sysObjectOperator->sOperator;
                    $sysOrgOperator->save();
                }
            }
             
            if ($arrOrgObject['System/SysRole']) {
                foreach ($arrOrgObject['System/SysRole'] as $ObjectID) {
                    $sysOrgOperator = new SysOrgOperator();
                    $sysOrgOperator->sObjectName = $sObjectName;
                    $sysOrgOperator->ObjectID = $ObjectID;
                    $sysOrgOperator->sOrgName = 'System/SysRole';
                    $sysOrgOperator->sOperator = $sysObjectOperator->sOperator;
                    $sysOrgOperator->save();
                }
            }
        
            if ($arrOrgObject['System/SysDep']) {
                foreach ($arrOrgObject['System/SysDep'] as $ObjectID) {
                    $sysOrgOperator = new SysOrgOperator();
                    $sysOrgOperator->sObjectName = $sObjectName;
                    $sysOrgOperator->ObjectID = $ObjectID;
                    $sysOrgOperator->sOrgName = 'System/SysDep';
                    $sysOrgOperator->sOperator = $sysObjectOperator->sOperator;
                    $sysOrgOperator->save();
                }
            }
        }

        Yii::$app->cache->flush();

        $arrReturn = [];
        $this->output($arrReturn);
    }    
    
    
    /**
     * 删除界面配置保存
     */
    public function actionDel()
    {
        $arrReturn = [];
         
		$sObjectName =  Yii::$app->request->post('sObjectName');
		 
        $arrID = explode(";", Yii::$app->request->post('IDs'));
        foreach ($arrID as $ID) {           
            $sysObjectOperator = SysObjectOperator::findOne($ID);
            SysOrgOperator::deleteAll("sObjectName='".$sysObjectOperator->sObjectName."' AND sOperator='".$sysObjectOperator->sOperator."'");
            $sysObjectOperator->delete();
        }
         
        $this->output($arrReturn);
    }    
}