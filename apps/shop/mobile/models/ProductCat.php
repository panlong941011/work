<?php

namespace myerm\shop\mobile\models;

use myerm\common\components\Func;
use yii\helpers\Url;

/**
 * 商品分类
 * ============================================================================
 * 版权所有 2010-2017 三只象（厦门）网络科技有限公司，并保留所有权利。
 * 网站地址: http://www.myerm.cn
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和
 * 使用；不允许对程序代码以任何形式任何目的的再发布。
 * ============================================================================
 * @author 欧阳燕忠  <oyyz@3elephant.com>
 * @time 2017-10-7 11:10:05
 * @version v1.0
 */
class ProductCat extends \myerm\shop\common\models\ProductCat
{
	/**
	 * 获取商品分类数据（树形结构）
	 * @return int
	 * @author oyyz <oyyz@3elephant.com>
	 * @time 2017-10-7 11:01:46
	 */
	public static function category()
	{
		$arrCat = ProductCat::find()
			->select('lID,sName,UpID,bNavShow,sPicPath')
			->where(['bNavShow' => '1', 'GradeID' => '1'])
			->orderBy('lPos ASC')
			->asArray()
			->all();
		if (!$arrCat) {
			return [];
		}
		foreach ($arrCat as $key => $val) {
			$arrCat[$key]['sUrl'] = Url::toRoute(['product/list', 'catid' => $val['lID']]);
		}
		
		return $arrCat;
	}
	
	/**
	 * 组装商品分类的树形结构
	 * @param array $arrDown
	 * @return array|bool
	 * @author oyyz <oyyz@3elephant.com>
	 * @time 2017-10-7 11:57:02
	 */
	public static function downSellerTree($arrDown)
	{
		$tree = array();
		if (!$arrDown) {
			return false;
		}
		foreach ($arrDown as $item) {
			if ($arrDown[$item['UpID']]) {
				$arrDown[$item['UpID']]['downseller'][] = &$arrDown[$item['lID']];
			} else {
				$tree[] = &$arrDown[$item['lID']];
			}
		}
		return $tree;
	}
	
	/**
	 * 获取产品分类最后更新的时间，用于分类页的缓存用
	 * @author 陈鹭明
	 * @time 2017年10月13日 15:01:30
	 */
	public function getLastModifyDate()
	{
		return static::find()->max('dEditDate');
	}
	
}