<?php

namespace myerm\backend\common\models;

use Yii;

/**
 * 业务对象(通过对象管理器管理的对象)的模型基类
 * ============================================================================
 * 版权所有 2010-2017 三只象（厦门）网络科技有限公司，并保留所有权利。
 * 网站地址: http://www.myerm.cn
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和
 * 使用；不允许对程序代码以任何形式任何目的的再发布。
 * ============================================================================
 * @author 陈鹭明  <lumingchen@qq.com>
 * @since 2015-12-2 10:14
 * @version v2.0
 */
class ObjectModel extends Model
{
    public static $sysObject = null;

    public static function config($sysObject)
    {
        self::$sysObject = $sysObject;

        return self::find();
    }

    /**
     * 获取数据库表名，默认是对象名称，大小写相关。
     * @return string
     */
    public static function tableName()
    {
        return '{{' . self::$sysObject->sTable . '}}';
    }

    /**
     * 获取数据源，数据源都是由系统统一配置管理(SysObject表)
     */
    public static function getDb()
    {
        $sDataSourceKey = "ds_" . self::$sysObject->DataSourceID;
        return Yii::$app->$sDataSourceKey;
    }
}
