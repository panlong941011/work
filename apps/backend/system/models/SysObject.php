<?php

namespace myerm\backend\system\models;

/**
 * 系统对象-对象模型
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
class SysObject extends \myerm\backend\system\models\Model
{

    public static function getDb()
    {
        return \Yii::$app->db;
    }

    public function getField()
    {
        return $this->hasMany(SysField::className(), ['sObjectName' => 'sObjectName']);
    }

    public function getDatasource()
    {
        return $this->hasOne(SysDataSource::className(), ["ID" => "DataSourceID"]);
    }

    public function getParent()
    {
        return $this->hasOne(SysObject::className(), ["sObjectName" => "ParentID"]);
    }

    public function getModule()
    {
        return $this->hasOne(SysModule::className(), ["ID" => "ModuleID"]);
    }

    public function getPkfield()
    {
        return SysField::findOne(['sObjectName' => $this->sObjectName, 'sFieldAs' => $this->sIDField]);
    }

    public function getDbconn()
    {
        return \Yii::$app->{"ds_" . $this->DataSourceID};
    }
}
