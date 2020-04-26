<?php

namespace myerm\backend\system\models;

use myerm\backend\common\libs\NewID;
use myerm\backend\common\models\ObjectModel;

/**
 * 系统对象模型-数据字典基类
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
class SysField extends \myerm\backend\system\models\Model
{

    public static $arrType = [
        'bool' => [
            'ID' => 'Bool',
            'sName' => '布尔值',
            'sDBType' => 'boolean',
            'sPrefix' => 'b',
            'class' => '\myerm\backend\objectbuilder\fields\SysFieldBool',
        ],
        'text' => [
            'ID' => 'Text',
            'sName' => '文本',
            'sDBType' => 'string(500)',
            'sPrefix' => 's',
            'class' => '\myerm\backend\objectbuilder\fields\SysFieldText',
        ],
        'textarea' => [
            'ID' => 'TextArea',
            'sName' => '长文本',
            'sDBType' => 'text',
            'sPrefix' => 's',
            'class' => '\myerm\backend\objectbuilder\fields\SysFieldTextArea',
        ],
        'listtable' => [
            'ID' => 'ListTable',
            'sName' => '参照',
            'sDBType' => 'varchar(32)',
            'sPrefix' => '',
            'class' => '\myerm\backend\objectbuilder\fields\SysFieldListTable',
        ],
        'date' => [
            'ID' => 'Date',
            'sName' => '日期',
            'sDBType' => 'datetime',
            'sPrefix' => 'd',
            'class' => '\myerm\backend\objectbuilder\fields\SysFieldDate',
        ],
        'int' => [
            'ID' => 'Int',
            'sName' => '整型',
            'sDBType' => 'integer',
            'sPrefix' => 'l',
            'class' => '\myerm\backend\objectbuilder\fields\SysFieldInt',
        ],
        'float' => [
            'ID' => 'Float',
            'sName' => '浮点',
            'sDBType' => 'money',
            'sPrefix' => 'f',
            'class' => '\myerm\backend\objectbuilder\fields\SysFieldFloat',
        ],
        'attachfile' => [
            'ID' => 'AttachFile',
            'sName' => '附件',
            'sDBType' => 'string(500)',
            'sPrefix' => 's',
            'class' => '\myerm\backend\objectbuilder\fields\SysFieldAttachFile',
        ],
        'list' => [
            'ID' => 'List',
            'sName' => '列表',
            'sDBType' => 'string(32)',
            'sPrefix' => '',
            'class' => '\myerm\backend\objectbuilder\fields\SysFieldList',
        ],
        'multilist' => [
            'ID' => 'MultiList',
            'sName' => '多选列表',
            'sDBType' => 'string(500)',
            'sPrefix' => '',
            'class' => '\myerm\backend\objectbuilder\fields\SysFieldMultiList',
        ],
        'reference' => [
            'ID' => 'Reference',
            'sName' => '引用',
            'class' => '\myerm\backend\objectbuilder\fields\SysFieldReference',
        ],
        'common' => [
            'ID' => 'Common',
            'sName' => '公共属性',
            'class' => '\myerm\backend\objectbuilder\fields\SysFieldCommon',
        ],
        'virtual' => [
            'ID' => 'Virtual',
            'sName' => '虚拟属性',
            'class' => '\myerm\backend\objectbuilder\fields\SysFieldVirtual',
        ],
    ];


    public static function tableName()
    {
        return '{{SysField}}';
    }

    public static function createField($sType)
    {
        return new self::$arrType[strtolower($sType)]['class']();
    }

    /**
     * 设置属性
     * @param array $arrConfig
     */
    public function setField($arrConfig)
    {
        foreach ($arrConfig as $sField => $sValue) {
            $this->$sField = $sValue;
        }

        if (!$this->ID) {
            $this->ID = NewID::make();
        }
    }

    public function getRefobject()
    {
        return $this->hasOne(SysObject::className(), ["sObjectName" => "sRefKey"]);
    }

    public function getEnumobject()
    {
        return $this->hasOne(SysObject::className(), ["sObjectName" => "sEnumTable"]);
    }

    public function getReffield()
    {
        return $this->hasOne(SysField::className(), ["ID" => "RefFieldID"]);
    }

    public function getType()
    {
        return self::$arrType[strtolower($this->sDataType)];
    }

    public function getAttr()
    {
        return unserialize($this->sAttribute);
    }

    /**
     * 如果是列表和多选列表类型字段，获取他们的选项。
     */
    public function getOptions()
    {
        if ($this->sEnumOption) {
            return $this->enumoptions;
        } elseif ($this->sEnumTable != "System/SysFieldEnum") {
            return ObjectModel::config($this->enumobject)->select([
                $this->enumobject->sIDField . " AS ID",
                $this->enumobject->sNameField . " AS sName"
            ])->asArray()->all();
        } else {
            return SysFieldEnum::find()->select([
                'ID',
                'sName',
                'UpID',
                'bDefault'
            ])->where(['SysFieldID' => $this->sRefKey])->orderBy("UpID,lPos")->asArray()->all();
        }
    }

    public function getEnumoptions()
    {
        $arrOption = explode("\n", $this->sEnumOption);
        $arrOptionValue = [];
        foreach ($arrOption as $sOpt) {
            $sKey = trim(substr($sOpt, 0, strpos($sOpt, "=")));
            $sValue = trim(substr($sOpt, strpos($sOpt, "=") + 1));
            $arrOptionValue[$sKey] = ['ID' => $sKey, 'sName' => $sValue];
        }

        return $arrOptionValue;
    }
}
