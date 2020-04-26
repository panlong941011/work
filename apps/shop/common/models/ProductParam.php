<?php

namespace myerm\shop\common\models;


/**
 * 商品参数
 * ============================================================================
 * 版权所有 2010-2017 三只象（厦门）网络科技有限公司，并保留所有权利。
 * 网站地址: http://www.myerm.cn
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和
 * 使用；不允许对程序代码以任何形式任何目的的再发布。
 * ============================================================================
 * @author 陈鹭明  <lumingchen@qq.com>
 * @time 2017年12月1日 14:55:33
 * @version v1.0
 */
class ProductParam extends ShopModel
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
	
	public function findAllParam()
	{
		return static::find()->orderBy("lID")->all();
	}
}