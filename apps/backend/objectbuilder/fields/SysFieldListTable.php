<?php

namespace myerm\backend\objectbuilder\fields;

use myerm\backend\system\models\SysField;
/**
 * 数据字典类-参照类型
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
class SysFieldListTable extends \myerm\backend\system\models\SysField
{
	/**
	 * 新增属性
	 * @param unknown_type $arrConfig
	 */
	public function addField($arrConfig)
	{
		parent::setField($arrConfig);
		
		$this->sDataType = "ListTable";
		$this->sUIType = "ListTable";
		
		//缺省的长度限制，32
		if (!$this->lLength) {
			$this->lLength = 32;
		}

		$this->save();
		
		return $this->ID;
	}
}
