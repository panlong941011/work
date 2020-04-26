<?php
/**
 * Created by PhpStorm.
 * User: ouyangyz <ouyangyanzhong@163.com>
 * Date: 2019/2/21
 * Time: 11:26
 */

namespace console\controllers;

use myerm\common\components\HttpHelper;
use myerm\shop\common\models\Buyer;
use myerm\shop\common\models\UpdatePromotionLog;
use yii\console\Controller;

/**
 * 促销活动控制器
 * Class PromotionController
 * @package console\controllers
 * @author hechengcheng
 * @time 2019/3/15 16:15
 */
class SalespromotionController extends Controller
{
	/**
	 * 云端推送促销活动自动更新
	 * @return int
	 * @throws \yii\base\InvalidConfigException
	 * @author hechengcheng
	 * @time 2019/3/15 16:23
	 */
	public function actionPushpromotionupdate()
	{
		$arrLog = UpdatePromotionLog::find()
			->select('lID,SalesPromotionID,sPush,dPushDate')
			->where(['StatusID' => UpdatePromotionLog::STATUS_SUCCESS, 'bPush' => UpdatePromotionLog::PUSH_PENDING])
			->all();
		
		if (!$arrLog) {
			return self::EXIT_CODE_NORMAL;
		}
		
		foreach ($arrLog as $log) {
			$log->dPushDate = \Yii::$app->formatter->asDatetime(time());
			
			$arrBuyerID = explode(',', $log->sPush);
			foreach ($arrBuyerID as $key => $buyerID) {
				$buyer = Buyer::find()->select('lID,sDomainName,bActive')->where(['lID' => $buyerID])->one();
				if (!$buyer || ($buyer && !$buyer->sDomainName) || !$buyer->bActive) {
					unset($arrBuyerID[$key]);
					continue;
				}
				echo $buyer->sDomainName . '/api/Updatepromotion';
				$sJsonResult = HttpHelper::sendPost($buyer->sDomainName . '/api/updatepromotion', ['lCloudID' => $log->SalesPromotionID]);
				$result = json_decode($sJsonResult, true);
				
				if ($result['bSuccess']) {
					unset($arrBuyerID[$key]);
				}
				break;
			}
			if (!$arrBuyerID) {
				$log->bPush = UpdatePromotionLog::PUSH_SUCCESS;
				$log->sPush = '';
			} else {
				$log->sPush = implode(',', $arrBuyerID);
			}
			$log->save();
		}
	}
}