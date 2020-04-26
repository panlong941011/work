<?php

namespace myerm\backend\common\libs;

/**
 * 公共函数库-字符串助手类
 * ============================================================================
 * 版权所有 2010-2017 三只象（厦门）网络科技有限公司，并保留所有权利。
 * 网站地址: http://www.myerm.cn
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和
 * 使用；不允许对程序代码以任何形式任何目的的再发布。
 * ============================================================================
 * @author 陈鹭明  <lumingchen@qq.com>
 * @since 2015-11-2 14:31
 * @version v2.0
 */
class StrTool extends \yii\helpers\StringHelper
{
	/**
	 * $str1 == $str2，大小写无关
	 * @version 1.1 修正了类型不相等的问题，要用===来解决(2010/10/28)
	 * @return boolean
	 */
	public static function equalsIgnoreCase($str1, $str2)
	{
		return strtolower($str1) === strtolower($str2);
	}

	/**
	 * 判断是否$string是否为空，null和空字符都会返回true;
	 * @param string $string
	 * @param boolean $trim 是否要对$string进行trim处理之后，再进行判断。
	 */
	public static function isEmpty($string, $trim=false)
	{
		if (is_null($string)) {
			return true;
		}

		if ("$string" == "") {
			return true;
		}

		return false;
	}

	/**
	 * 解析Email地址
	 * @param String $address
	 * @return Array
	 */
	public static function parseEmailAddress($address)
	{
		$matches = @mailparse_rfc822_parse_addresses($address);

		$emails = array();
		if (is_array($matches)) {
			foreach ($matches as $m) {
				if ($m['display'] != $m['address']) {
					$emails[] = array('email'=>trim($m['address']), 'displayname'=>trim($m['display'], "\"'"));
				} else {
					$emails[] = array('email'=>trim($m['address']), 'displayname'=>preg_replace("/@.*$/", "", $m['display']));
				}
			}
		} else {
			echo $address;
			exit;
		}

		return $emails;
	}

	/**
	 * 验证是否合法的email地址
	 * @param string $address
	 * @return boolean
	 */
	public static function ValidateAddress($address)
	{
		if (function_exists('filter_var')) { //Introduced in PHP 5.2
			if (filter_var($address, FILTER_VALIDATE_EMAIL) === FALSE) {
				return false;
			} else {
				return true;
			}
		} else {
			return preg_match('/^(?:[\w\!\#\$\%\&\'\*\+\-\/\=\?\^\`\{\|\}\~]+\.)*[\w\!\#\$\%\&\'\*\+\-\/\=\?\^\`\{\|\}\~]+@(?:(?:(?:[a-zA-Z0-9_](?:[a-zA-Z0-9_\-](?!\.)){0,61}[a-zA-Z0-9_-]?\.)+[a-zA-Z0-9_](?:[a-zA-Z0-9_\-](?!$)){0,61}[a-zA-Z0-9_]?)|(?:\[(?:(?:[01]?\d{1,2}|2[0-4]\d|25[0-5])\.){3}(?:[01]?\d{1,2}|2[0-4]\d|25[0-5])\]))$/', $address);
		}
	}

	/**
	 * 找出$needle第一次出现在$hayStack的位置
	 */
	public static function indexOf($hayStack, $needle, $offset=0)
	{
	    $iPos = stripos($hayStack, $needle, $offset);
	
	    if($iPos === false)
	    {
	        return -1;
	    }
	    else
	    {
	        return $iPos;
	    }
	}
	
	/**
	 * 找出$needle最后一次出现在$hayStack的位置
	 */
	public static function lastIndexOf($hayStack, $needle, $offset=0)
	{
	    $iPos = strripos($hayStack, $needle, $offset);
	
	    if($iPos === false)
	    {
	        return -1;
	    }
	    else
	    {
	        return $iPos;
	    }
	}	
	
	/**
	 * 验证文件的后缀名
	 * 返回true则为合法文件，
	 * 返回false则为不合法文件
	 */
	public static function validateExtension($sFileName)
	{
	    $bFlag = true;
	
	    //非法文件
	    if(preg_match("/\.(php|phtml|php3|php4|jsp|exe|dll|asp|cer|asa|shtml|shtm|aspx|asax|cgi|fcgi|pl|bat)$/i", $sFileName))
	    {
	        $bFlag = false;
	    }
	
	    return $bFlag;
	}
}
