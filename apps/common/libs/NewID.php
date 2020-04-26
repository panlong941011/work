<?php

namespace myerm\common\libs;

/**
 * 公共函数库-生成ID
 * ============================================================================
 * 版权所有 2010-2017 三只象（厦门）网络科技有限公司，并保留所有权利。
 * 网站地址: http://www.myerm.cn
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和
 * 使用；不允许对程序代码以任何形式任何目的的再发布。
 * ============================================================================
 * @author 陈鹭明  <lumingchen@qq.com>
 * @since 2015-11-4 14:49
 * @version v2.0
 */
class NewID
{
	/**
	 * 生成一个长度为32的字符串
	 * @return String
	 */
	public static function make()
	{
		$mt = explode(" ", microtime());
		$sOid = $mt[1]."".$mt[0]."".mt_rand()."".mt_rand();
		$sOid = str_replace('.', '0', $sOid);
	
		if (strlen($sOid) < 32) {
			for ($i=strlen($sOid)+1; $i<=32; $i++) {
				$sOid .= "0";
			}
		} elseif (strlen($sOid) > 32) {
			$sOid = substr($sOid, 0, 32);
		}
	
		return $sOid;
	}	
}
