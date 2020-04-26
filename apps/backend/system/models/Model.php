<?php

namespace myerm\backend\system\models;

use myerm\backend\common\libs\StrTool;

/**
 * 系统对象公共模型类
 * ============================================================================
 * 版权所有 2010-2017 三只象（厦门）网络科技有限公司，并保留所有权利。
 * 网站地址: http://www.myerm.cn
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和
 * 使用；不允许对程序代码以任何形式任何目的的再发布。
 * ============================================================================
 * @author 陈鹭明  <lumingchen@qq.com>
 * @since 2015-11-3 16:14
 * @version v2.0
 */
class Model extends \yii\db\ActiveRecord
{
    public static $arrDataSource = [];

    /**
     * 获取数据源，数据源都是由系统统一配置管理(SysObject表)
     */
    public static function getDb()
    {
        $sObjectName = 'System/' . StrTool::basename(get_called_class());

        if (self::$arrDataSource[$sObjectName]) {
            return \Yii::$app->{self::$arrDataSource[$sObjectName]};
        }

        $DataSourceID = SysObject::find()->select(['DataSourceID'])->where(['sObjectName' => $sObjectName])->one()->DataSourceID;
        if (!$DataSourceID) {
            self::$arrDataSource[$sObjectName] = "db";
            return \Yii::$app->db;
        } else {
            self::$arrDataSource[$sObjectName] = "ds_" . $DataSourceID;
            return \Yii::$app->{self::$arrDataSource[$sObjectName]};
        }
    }

    /**
     * 获取数据库表名，默认是对象名称，大小写相关。
     * @return string
     */
    public static function tableName()
    {
        return '{{' . StrTool::basename(get_called_class()) . '}}';
    }

    /**
     * 获取SysObject
     */
    public function getSysobject()
    {
        return $this->hasOne(SysObject::className(), ['sObjectName' => 'sObjectName']);
    }

    /**
     * 获取SysField
     */
    public function getField()
    {
        return $this->hasOne(SysField::className(), ['ID' => 'SysFieldID']);
    }

    /**
     * 获取组织数据
     */
    public function getOrgobject()
    {
        return $this->hasOne(SysOrgObject::className(), ['ObjectID' => 'ID']);
    }

    /**
     * 获取所有应用的角色
     */
    public function getRoles()
    {
        return $this->orgobject ? $this->orgobject->roles : [];
    }

    /**
     * 获取所有应用的部门
     */
    public function getDeps()
    {
        return $this->orgobject ? $this->orgobject->deps : [];
    }

    /**
     * 获取所有应用的人员
     */
    public function getUsers()
    {
        return $this->orgobject ? $this->orgobject->users : [];
    }

    /**
     * 获取所有应用的团队
     */
    public function getTeams()
    {
        return $this->orgobject ? $this->orgobject->teams : [];
    }
}
