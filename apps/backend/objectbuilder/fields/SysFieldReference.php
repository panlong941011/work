<?php

namespace myerm\backend\objectbuilder\fields;

use myerm\backend\system\models\SysField;
use myerm\backend\common\libs\NewID;

/**
 * 数据字典类-引用类型
 * ============================================================================
 * 版权所有 2010-2017 三只象（厦门）网络科技有限公司，并保留所有权利。
 * 网站地址: http://www.myerm.cn
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和
 * 使用；不允许对程序代码以任何形式任何目的的再发布。
 * ============================================================================
 * @author 陈鹭明  <lumingchen@qq.com>
 * @since 2015-11-7 15:51
 * @version v2.0
 */
class SysFieldReference extends \myerm\backend\system\models\SysField
{
	/**
	 * 新增属性
	 * @param unknown_type $arrConfig
	 */
	public function addField($arrConfig)
	{
	    $field = SysField::findOne($arrConfig['referenceField']);
	    
	    $commonField = new SysField();	    
	    foreach ($field->attributes as $sFieldAs => $sValue) {
	        $commonField->$sFieldAs = $sValue;
	    }

	    $commonField->ID = $arrConfig['ID'] ? $arrConfig['ID'] : NewID::make();
	    $commonField->sName = $arrConfig['sName'];
	    $commonField->RefFieldID = $arrConfig['RefFieldID'];
	    $commonField->sFieldAs = $arrConfig['sFieldAs'];
	    $commonField->sObjectName = $arrConfig['sObjectName'];
	    $commonField->sFieldClassType = "view,list";
	    $commonField->bUDF = 1;
	    $commonField->sLinkField = $arrConfig['sLinkField'];
		$commonField->save();
		
		return $commonField->ID;
	}
}
