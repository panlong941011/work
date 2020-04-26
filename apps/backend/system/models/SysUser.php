<?php

namespace myerm\backend\system\models;


class SysUser extends \myerm\backend\common\models\Model
{

    /**
     * 登陆确认
     *
     * @param string $sLoginName
     * @param string $sPass
     */
    public static function checkLogin($sLoginName, $sPassword)
    {
        $sysUser = SysUser::find()->where(['sLoginName' => $sLoginName])->one();
        if ($sysUser && $sysUser->sPassword == $sPassword) {
            return $sysUser;
        } else {
            return false;
        }
    }

    public function getDownline()
    {
        return parent::getDb()->createCommand("SELECT SysUserID FROM SysUserLevel WHERE UpSysUserID='" . $this->lID . "'")->queryAll();
    }
}
