<?php

namespace myerm\shop\backend\controllers;

use myerm\backend\common\controllers\ObjectController;
use myerm\backend\common\libs\File;
use myerm\backend\common\libs\NewID;
use myerm\shop\common\models\Buyer;
use myerm\shop\common\models\DealFlow;
use myerm\shop\common\models\MemberWholesaler;
use myerm\shop\common\models\Wholesaler;
use yii\helpers\ArrayHelper;

/**
 * 渠道人员管理
 * Class WholesalerController
 * @package myerm\shop\backend\controllers
 * @author hechengcheng
 * @time 2019/5/9 18:14
 */
class WholesalerController extends ObjectController
{
	/**
	 * 配置列表按钮
	 * @return string
	 * @author hechengcheng
	 * @time 2019/5/10 9:53
	 */
	public function getListButton()
	{
		$data = [];
		return $this->renderPartial('listbutton', $data);
	}
	
	/**
	 * 启用渠道人员
	 * @return string
	 * @author hechengcheng
	 * @time 2019/5/10 10:30
	 */
	public function actionEnable()
	{
		$arrData = $this->listBatch();
		
		$arrID = ArrayHelper::getColumn($arrData,'lID');
		
		foreach ($arrID as $ID) {
			\Yii::$app->wholesaler->enable($ID);
		}
		
		return json_encode(['bSuccess' => true, 'sMsg' => '操作成功']);
	}
	
	/**
	 * 禁用渠道人员
	 * @return string
	 * @author hechengcheng
	 * @time 2019/5/10 10:30
	 */
	public function actionDisable()
	{
		$arrData = $this->listBatch();
		
		$arrID = ArrayHelper::getColumn($arrData,'lID');
		
		foreach ($arrID as $ID) {
			\Yii::$app->wholesaler->disable($ID);
		}
		
		return json_encode(['bSuccess' => true, 'sMsg' => '操作成功']);
	}
}