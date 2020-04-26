<?php

namespace myerm\backend\system\models;

use myerm\backend\common\libs\File;
use myerm\backend\common\libs\SystemTime;

/**
 * 系统对象模型-附件
 * ============================================================================
 * 版权所有 2010-2017 三只象（厦门）网络科技有限公司，并保留所有权利。
 * 网站地址: http://www.myerm.cn
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和
 * 使用；不允许对程序代码以任何形式任何目的的再发布。
 * ============================================================================
 * @author 陈鹭明  <lumingchen@qq.com>
 * @since 2016-9-20 10:18
 * @version v2.0
 */
class SysAttach extends \myerm\backend\system\models\Model
{
    /**
     * 保存上传的附件信息
     * @param $sName 文件名
     * @param $sMIMEType MIMEType
     * @param $lSize 文件大小
     * @param $sSavePath 保存的路径
     * @author: Mars
     * @time: 2016-09-20 10:23:52
     */
    public static function saveData($sName, $sMIMEType, $lSize, $sSavePath, $sObjectName, $SysFieldID)
    {
        $sysAttach = new SysAttach();
        $sysAttach->sName = $sName;
        $sysAttach->OwnerID = \Yii::$app->backendsession->SysUserID;
        $sysAttach->NewUserID = \Yii::$app->backendsession->SysUserID;
        $sysAttach->EditUserID = \Yii::$app->backendsession->SysUserID;
        $sysAttach->dNewDate = SystemTime::getCurLongDate();
        $sysAttach->dEditDate = SystemTime::getCurLongDate();
        $sysAttach->sMIMEType = $sMIMEType;
        $sysAttach->lSize = $lSize;
        $sysAttach->sPath = $sSavePath;
        $sysAttach->sExt = strtoupper(File::getExtension($sName));
        $sysAttach->sPath = $sSavePath;
        $sysAttach->sObjectName = $sObjectName;
        $sysAttach->SysFieldID = $SysFieldID;

        if (in_array($sysAttach->sExt, ['JPEG', 'JPG', 'BMP', 'GIF', 'PNG', 'TIFF'])) {
            $sysAttach->bImage = 1;
        } else {
            $sysAttach->bImage = 0;
        }

        $sysAttach->save();

        return true;
    }
}
