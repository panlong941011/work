<?php

namespace myerm\backend\system\models;

use myerm\backend\system\models\SysDep;
use myerm\backend\system\models\SysRole;
use myerm\backend\system\models\SysTeam;
/**
 * 系统对象模型-自动共享
 * ============================================================================
 * 版权所有 2010-2017 三只象（厦门）网络科技有限公司，并保留所有权利。
 * 网站地址: http://www.myerm.cn
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和
 * 使用；不允许对程序代码以任何形式任何目的的再发布。
 * ============================================================================
 * @author 陈鹭明  <lumingchen@qq.com>
 * @since 2016-1-17 17:54
 * @version v2.0
 */
class SysAutoShare extends \myerm\backend\system\models\Model
{
    public function getFromdep()
    {
        return $this->hasOne(SysDep::className(), ['lID'=>'FromSysDepID']);
    }
    
    public function getFromrole()
    {
        return $this->hasOne(SysRole::className(), ['lID'=>'FromSysRoleID']);
    }    
    
    public function getFromteam()
    {
        return $this->hasOne(SysTeam::className(), ['lID'=>'FromSysTeamID']);
    }    
    
    public function getTodep()
    {
        return $this->hasOne(SysDep::className(), ['lID'=>'ToSysDepID']);
    }
    
    public function getTorole()
    {
        return $this->hasOne(SysRole::className(), ['lID'=>'ToSysRoleID']);
    }
    
    public function getToteam()
    {
        return $this->hasOne(SysTeam::className(), ['lID'=>'ToSysTeamID']);
    }    
    
    
}
