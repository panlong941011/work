<?php

namespace myerm\shop\common\models;

use myerm\common\components\CommonEvent;
use yii\base\Event;

/**
 * 渠道平台会员
 * Class MemberWholesaler
 * @package myerm\shop\common\models
 * @author hechengcheng
 * @time 2019/5/9 18:49
 */
class MemberWholesaler extends ShopModel
{
	/**
	 * 配置数据源
	 * @return null|object|\yii\db\Connection
	 * @throws \yii\base\InvalidConfigException
	 * @author hechengcheng
	 * @time 2019/5/9 18:49
	 */
	public static function getDb()
	{
		return \Yii::$app->get('db_wholesaler');
	}
	
	/**
	 * 配置表名
	 * @return string
	 * @author hechengcheng
	 * @time 2019/5/9 18:52
	 */
	public static function tableName()
	{
		return '{{Member}}';
	}
}