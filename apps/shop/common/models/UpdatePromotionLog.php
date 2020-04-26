<?php
/**
 * Created by PhpStorm.
 * User: ouyangyz <ouyangyanzhong@163.com>
 * Date: 2019/2/21
 * Time: 11:37
 */

namespace myerm\shop\common\models;

/**
 * 来三斤同步到云端促销活动记录
 * Class UpdatePromotionLog
 * @package myerm\shop\common\models
 * @author hechengcheng
 * @time 2019/3/15 16:27
 */
class UpdatePromotionLog extends ShopModel
{
	const STATUS_FAIL = 1; //更新失败
	const STATUS_SUCCESS = 2; //更新成功
	
	const PUSH_PENDING = 1; //推送处理中
	const PUSH_SUCCESS = 2; //推送成功
}