<?php

namespace myerm\backend\common\models;

use myerm\backend\system\models\SysDep;
use myerm\backend\system\models\SysObject;
use myerm\backend\system\models\SysRole;
use myerm\backend\system\models\SysUser;
use myerm\common\models\MyERMModel;
use Yii;
use yii\helpers\StringHelper;

/**
 * 公共模型类，提供给所有业务对象(通过对象管理器管理的对象)使用的基类。
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
class Model extends MyERMModel
{
    public static $arrDataSource = [];

    /**
     * 获取数据源，数据源都是由系统统一配置管理(SysObject表)
     */
    public static function getDb()
    {
        $arrPart = explode("\\", get_called_class());
        $sObjectName = $arrPart[2] . '/' . $arrPart[3];

        if (self::$arrDataSource[$sObjectName]) {
            return Yii::$app->{self::$arrDataSource[$sObjectName]};
        }

        self::$arrDataSource[$sObjectName] = "ds_" . SysObject::find()->select(['DataSourceID'])->where(['sObjectName' => $arrPart[2] . '/' . $arrPart[4]])->one()->DataSourceID;

        return Yii::$app->{self::$arrDataSource[$sObjectName]};
    }

    public function getSysuser()
    {
        return $this->hasOne(SysUser::className(), ['lID' => 'SysUserID']);
    }

    public function getSysrole()
    {
        return $this->hasOne(SysRole::className(), ['lID' => 'SysRoleID']);
    }

    public function getSysdep()
    {
        return $this->hasOne(SysDep::className(), ['lID' => 'SysDepID']);
    }

}
