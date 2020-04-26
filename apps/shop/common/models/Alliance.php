<?php

namespace myerm\shop\common\models;


class Alliance extends ShopModel
{
	/**
	 * 配置数据源
	 * @return null|object|\yii\db\Connection
	 * @throws \yii\base\InvalidConfigException
	 * @author hechengcheng
	 * @time 2019/5/9 17:05
	 */
	public static function getDb()
	{
		return \Yii::$app->get('ds_cloud');
	}

}