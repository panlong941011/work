<?php

namespace myerm\backend\common\models;

use Yii;
use myerm\backend\common\libs\NewID;
use myerm\backend\common\libs\SystemTime;
use myerm\backend\system\models\SysTeamUser;

/**
 * 会话类
 * ============================================================================
 * 版权所有 2010-2017 三只象（厦门）网络科技有限公司，并保留所有权利。
 * 网站地址: http://www.myerm.cn
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和
 * 使用；不允许对程序代码以任何形式任何目的的再发布。
 * ============================================================================
 * @author 陈鹭明  <lumingchen@qq.com>
 * @since 2015-12-3 17:03
 * @version v2.0
 */
class Session extends \myerm\backend\common\models\Model
{
    private $session = null;
    
    public static function getDb()
    {
        return Yii::$app->ds_db;
    }

    public static function tableName()
    {
        return "SysSession";
    }

    public function start()
    {
        $sSessionID = Yii::$app->request->cookies->getValue("MYERM2SESSIONID");
        if (!$sSessionID) {
            $sSessionID = NewID::make();
            Yii::$app->response->cookies->add(new \yii\web\Cookie([
                'name' => 'MYERM2SESSIONID',
                'value' => $sSessionID
            ]));
        }
        
        $session = parent::findOne($sSessionID);
       
        if (!$session->ID) {
            $session = new Session();
            $session->ID = $sSessionID;
            $session->dStart = SystemTime::getCurLongDate();
        }
        
        $session->sIP = Yii::$app->request->userIP;
        $session->dLastActivity = SystemTime::getCurLongDate();
        $session->save();
        
        $this->session = $session;

        //遍历挂载session数据 akun 2016-9-12 17:25:44
        foreach ($session->toArray() as $key => $value) {
            $this->$key = $value;
        }
    }
    
    /**
     * 是否已登陆
     */
    public function getBlogin()
    {
        return !!$this->session->SysUserID;
    }
    
    /**
     * 登陆成功
     */
    public function login($sysUser)
    {
        $this->session->SysUserID = $sysUser->lID;
        $this->session->SysRoleID = $sysUser->SysRoleID;
        $this->session->SysDepID = $sysUser->SysDepID;
        
        $sTeamID = $sComm = "";
        $arrTeam = SysTeamUser::findAll(['SysUserID'=>$sysUser->lID]);
        foreach ($arrTeam as $team) {
            $sTeamID .= $sComm.$team->SysTeamID;
            $sComm .= ";";
        }       
        $this->session->SysTeamID = $sTeamID;
        
        $this->session->save();
        
        return true;
    }
    
    /**
     * 退出
     */
    public function logout()
    {
        $this->session->SysUserID = null;
        $this->session->SysRoleID = null;
        $this->session->SysDepID = null;
        $this->session->SysTeamID = null;
        $this->session->save();        
    }
    
    public function expire()
    {
        self::deleteAll("dLastActivity<'".SystemTime::getLongDate(time() - 3600 * 3)."'");       
    }

}