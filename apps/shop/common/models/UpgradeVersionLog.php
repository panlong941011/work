<?php
/**
 * Created by PhpStorm.
 * User: ouyangyz <ouyangyanzhong@163.com>
 * Date: 2019/2/21
 * Time: 11:37
 */

namespace myerm\shop\common\models;

/**
 * 来三斤同步到云端版本更新记录
 * Class UpgradeVersionLog
 * @package myerm\shop\common\models
 * @author ouyangyz <ouyangyanzhong@163.com>
 * @time 2019/2/21 11:38
 */
class UpgradeVersionLog extends ShopModel
{
	const STATUC_FAIL = 1; //更新失败
	const STATUS_SUCCESS = 2; //更新成功
	
	const PUSH_PENDING = 1; //推送处理中
	const PUSH_SUCCESS = 2; //推送成功
}