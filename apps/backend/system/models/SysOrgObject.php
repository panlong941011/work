<?php

namespace myerm\backend\system\models;

use myerm\backend\system\models\SysRole;
use myerm\backend\system\models\SysUser;
use myerm\backend\system\models\SysTeam;
use myerm\backend\system\models\SysDep;
/**
 * 系统对象模型-组织数据模型
 * ============================================================================
 * 版权所有 2010-2017 三只象（厦门）网络科技有限公司，并保留所有权利。
 * 网站地址: http://www.myerm.cn
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和
 * 使用；不允许对程序代码以任何形式任何目的的再发布。
 * ============================================================================
 * @author 陈鹭明  <lumingchen@qq.com>
 * @since 2015-11-15 15:23
 * @version v2.0
 */
class SysOrgObject extends \myerm\backend\system\models\Model
{
    /**
     * 获取所有应用的角色
     */
    public function getRoles()
    {
        if ($this->sRoleID) {
            $arrRoleID = explode(";", $this->sRoleID);
            return SysRole::find()->select(['lID AS ID', 'sName'])->where("lID IN ('".implode("','", $arrRoleID)."')")->all();
        }
        
        return [];
    }
    
    /**
     * 获取所有应用的人员
     */
    public function getUsers()
    {
        if ($this->sUserID) {
            $arrUserID = explode(";", $this->sUserID);
            return SysUser::find()->select(['lID AS ID', 'sName'])->where("lID IN ('".implode("','", $arrUserID)."')")->all();
        }
    
        return [];
    }    
    
    /**
     * 获取所有应用的团队
     */
    public function getTeams()
    {
        if ($this->sTeamID) {
            $arrTeamID = explode(";", $this->sTeamID);
            return SysTeam::find()->select(['lID AS ID', 'sName'])->where("lID IN ('".implode("','", $arrTeamID)."')")->all();
        }
    
        return [];
    }
    
    
    /**
     * 获取所有应用的部门
     */
    public function getDeps()
    {
        if ($this->sDepID) {
            $arrDepID = explode(";", $this->sDepID);
            return SysDep::find()->select(['lID AS ID', 'sName'])->where("lID IN ('".implode("','", $arrDepID)."')")->all();
        }
    
        return [];
    }    
    
    
}
