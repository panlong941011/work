<?php
/**
 * Created by PhpStorm.
 * User: oyyz <ouyangyanzhong@163.com>
 * Date: 2018-06-04
 * Time: 下午 3:30
 */

namespace myerm\common\components;

/**
 * http请求
 * Class HttpHelper
 * @package myerm\common\components
 * @author ouyangyz <ouyangyanzhong@163.com>
 * @time 2019/2/21 15:50
 */
class HttpHelper
{
	public static function sendPost($url, $post)
	{
		$ch = curl_init($url);
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
		$data = curl_exec($ch);
		return $data;
	}
	
	public static function sendJsonPost($url, $json)
	{
		$data_string = json_encode($json);
		$ch = curl_init($url);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);//$data JSON类型字符串
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json', 'Content-Length: ' . strlen($data_string)));
		$result = curl_exec($ch);
		return $result;
	}
}