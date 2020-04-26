<?php
namespace myerm\backend\common\controllers;

use myerm\common\controllers\MyERMController;
use Yii;
use myerm\backend\system\models\SysField;
use myerm\backend\system\models\SysObject;
use myerm\backend\common\models\ObjectModel;
use myerm\backend\system\models\SysUser;
use myerm\backend\system\models\SysList;

/**
 * 所有控制器的基类
 */
class Controller extends MyERMController
{
    /**
     *
     * @var string 该对象所属的模块名称
     */
    public $sModule;

    /**
     *
     * @var string 该对象的ID
     */
    public $sSysObjectID;

    /**
     *
     * @var string 对象的完整路径=$sModule+'/'+$sSysObjectID
     */
    public $sObjectName;

    /**
     * 临时缓存，用于运行时频繁用到的数据查询
     * @var Array
     */
    public $arrCache;
    
    public function beforeAction($action)
    {
        if (parent::beforeAction($action)) {

            $arrPart = explode("/", Yii::$app->request->pathInfo);

            // 设置模块名称
            $this->sModule = strtolower($arrPart[0]);
            Yii::trace("设置模块名称：" . $this->sModule);
            
            // 设置对象的名称
            $this->sSysObjectID = strtolower($arrPart[1]);
            Yii::trace("设置对象的名称：" . $this->sSysObjectID);
            
            // 设置对象的完整路径
            $this->sObjectName = $this->sModule . '/' . $this->sSysObjectID;
                       
            //删除过期的会话
            Yii::$app->backendsession->expire();
            
            // 运行Session
            Yii::$app->backendsession->start();

            //设置语言
            \Yii::$app->language = 'cn-ZH';
            return true;
        }
        
        return false;
    }
    
    /**
     * 获取数据权限
     * @param $sObjectName 对象名
     * @param $OperaUserID 操作人员ID
     * @param $ObjectID 对象的某条记录的ID
     */
    public function getObjectPermitToken($sObjectName, $OperaUserID, $ObjectID)
    {
        //判断该对象是否有OwnerID，这个字段用于判断上下级数据权限，如果没有这个字段返回true
        $bExists = $this->arrCache['findownerid-'.$this->sObjectName];
        if ($bExists == -1) {
            return "view,edit,del,ref";
        } elseif ($bExists != 1) {
            $bExists = SysField::find()->select(['ID'])->where(['sObjectName'=>$sObjectName, 'sFieldAs'=>'OwnerID'])->one();            
            if (!$bExists) {                
                $this->arrCache['findownerid-'.$this->sObjectName] = -1;
                return "view,edit,del,ref";
            } else {
                $this->arrCache['findownerid-'.$this->sObjectName] = 1;
            }
        }
        
        $sysObject = $this->arrCache['sysObject-'.$this->sObjectName];
        if (!$sysObject) {
            $sysObject = SysObject::findOne(['sObjectName'=>$this->sObjectName]);
            $this->arrCache['sysObject-'.$this->sObjectName] = $sysObject;
        }
        
        /**
         * 如果存在OwnerID，就要判断数据权限
         */
        //先取出数据的OwnerID        
        $OwnerID = ObjectModel::config($sysObject)->select(['OwnerID'])->where([$sysObject->sIDField=>$ObjectID])->one()->OwnerID;
        
        //其次判断是否相等
        if ($OwnerID === $OperaUserID) {
            return "view,edit,del,ref";
        }
        
        //再次判断数据权限
        $dataUser = $this->arrCache['sysuser-'.$OwnerID];
        if (!$dataUser) {
            $dataUser = SysUser::find()->select(['SysRoleID'])->where(["lID"=>$OwnerID])->one();
            if (!$dataUser) {
                return "view,edit,del,ref";
            }            
            
            $dataUser->sysrole->PathID;
            $this->arrCache['sysuser-'.$OwnerID] = $dataUser;
        }
        
        $operaUser = $this->arrCache['sysuser-'.$OperaUserID];
        if (!$operaUser) {
            $operaUser = SysUser::find()->select(['SysRoleID'])->where(["lID"=>$OperaUserID])->one();
            if (!$operaUser) {
                return false;
            }
            
            $operaUser->sysrole->PathID;
            $this->arrCache['sysuser-'.$OperaUserID] = $operaUser;
        }        

        if (stristr($dataUser->sysrole->PathID, $operaUser->sysrole->PathID) && strlen($dataUser->sysrole->PathID) > strlen($operaUser->sysrole->PathID)) {
            return "view,edit,del,ref";
        }
        

        $sToken = $this->arrCache['autoshare-token-'.$OperaUserID];
        if (!$sToken) {          
            $sToken = SysList::getAutoShareToken($OperaUserID, $sObjectName);
            if (!$sToken) {
                $sToken = -1;
            }
            
            $this->arrCache['autoshare-token-'.$OperaUserID] = $sToken;
        }

        return $sToken;
    }
    
    /**
     * 是否有相应的操作权限
     * @param $sObjectName 对象名
     * @param $OperaUserID 操作人员ID
     * @param $ObjectID 对象的某条记录的ID
     * @param $sToken 操作token:view,del,ref,edit
     */
    public function bObjectPermit($sObjectName, $OperaUserID, $ObjectID, $sToken)
    {
        //判断该对象是否有OwnerID，这个字段用于判断上下级数据权限，如果没有这个字段返回true
        $ownerIDField = $this->arrCache['findownerid-'.$this->sObjectName];
        if ($ownerIDField == -1) {
            return true;
        } elseif (!$ownerIDField) {
            $ownerIDField = SysField::find()->select(['ID', 'sLinkField'])->where(['sObjectName'=>$sObjectName, 'sFieldAs'=>'OwnerID'])->one();
            if (!$ownerIDField) {
                $this->arrCache['findownerid-'.$this->sObjectName] = -1;
                return true;
            } else {
                $this->arrCache['findownerid-'.$this->sObjectName] = $ownerIDField;
            }
        }
    
        $sysObject = $this->arrCache['sysObject-'.$this->sObjectName];
        if (!$sysObject) {
            $sysObject = SysObject::findOne(['sObjectName'=>$this->sObjectName]);
            $this->arrCache['sysObject-'.$this->sObjectName] = $sysObject;
        }
    
        /**
         * 如果存在OwnerID，就要判断数据权限
         */
        //先取出数据的OwnerID
        $OwnerID = ObjectModel::config($sysObject)->select(['OwnerID'])->where([$sysObject->sIDField=>$ObjectID])->one()->OwnerID;
        if ($ownerIDField->sLinkField == 'ID') {
            $OwnerID = SysUser::find()->select(['lID'])->where(["ID"=>$OwnerID])->one()->lID;
        }

        //其次判断是否相等
        if ($OwnerID == $OperaUserID) {
            return true;
        }
    
        //再次判断数据权限
        $dataUser = $this->arrCache['sysuser-'.$OwnerID];
        if (!$dataUser) {
            $dataUser = SysUser::find()->select(['SysRoleID'])->where(["lID"=>$OwnerID])->one();
            if (!$dataUser) {
                return true;
            }
    
            $dataUser->sysrole->PathID;
            $this->arrCache['sysuser-'.$OwnerID] = $dataUser;
        }
    
        $operaUser = $this->arrCache['sysuser-'.$OperaUserID];
        if (!$operaUser) {
            $operaUser = SysUser::find()->select(['SysRoleID'])->where(["lID"=>$OperaUserID])->one();
            if (!$operaUser) {
                return false;
            }
    
            $operaUser->sysrole->PathID;
            $this->arrCache['sysuser-'.$OperaUserID] = $operaUser;
        }
    
        //判断拥有者是否操作人的下级
        if (stristr($dataUser->sysrole->PathID, $operaUser->sysrole->PathID) && strlen($dataUser->sysrole->PathID) > strlen($operaUser->sysrole->PathID)) {
            return true;
        }
    
        //判断是否有共享给操作人
        $sMaxToken = $this->arrCache['autoshare-token-'.$OperaUserID];
        if (!$sMaxToken) {
            $sMaxToken = SysList::getAutoShareToken($OperaUserID, $sObjectName);
            if (!$sMaxToken) {
                $sMaxToken = -1;
            }
    
            $this->arrCache['autoshare-token-'.$OperaUserID] = $sMaxToken;
        }

        if (stristr($sMaxToken, $sToken)) {
            return true;
        }

        return false;
    }    
    
}
