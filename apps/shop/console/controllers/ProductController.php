<?php
/**
 * Created by PhpStorm.
 * User: ouyangyz <ouyangyanzhong@163.com>
 * Date: 2019/2/21
 * Time: 11:26
 */

namespace console\controllers;

use myerm\common\components\Func;
use myerm\common\components\HttpHelper;
use myerm\shop\common\models\Buyer;
use yii\console\Controller;
use myerm\shop\common\models\Product;
use myerm\shop\common\models\UpgradeVersionLog;
use yii\helpers\FileHelper;
use myerm\common\components\simple_html_dom;

/**
 * 商品控制器
 * Class ProductController
 * @package console\controllers
 * @author ouyangyz <ouyangyanzhong@163.com>
 * @time 2019/2/21 11:27
 */
class ProductController extends Controller
{
	/**
	 * 云端推送商品自动更新
	 * @author ouyangyz <ouyangyanzhong@163.com>
	 * @time 2019/2/21 11:28
	 */
	public function actionPushproductupdate()
	{
		$arrLog = UpgradeVersionLog::find()
			//->select('lID,ProductID,sPush,bPush,dPush,LSJID')
			->where(['StatusID' => UpgradeVersionLog::STATUS_SUCCESS, 'bPush' => UpgradeVersionLog::PUSH_PENDING, 'bDownloadImg' => 1])
			->orderBy('lID asc')
			->all();
		
		if (!$arrLog) {
			return self::EXIT_CODE_NORMAL;
		}
		foreach ($arrLog as $log) {
			$log->dPush = \Yii::$app->formatter->asDatetime(time());
			echo 'logID:' . $log->lID . ' start at ' . $log->dPush . "\n";
			$log->save();
			$product = Product::find()->select('lID,sName,LSJID')->where(['lID' => $log->ProductID, 'bSale' => 1])->one();
			if (!$product) {
				$log->bPush = UpgradeVersionLog::PUSH_SUCCESS;
				$log->sPush = '';
				$log->save();
				echo $product->lID . "not exist\n";
				continue;
			}
			$arrBuyerID = explode(',', $log->sPush);
			foreach ($arrBuyerID as $key => $buyerID) {
				if (in_array($buyerID, [13, 14])) {
					unset($arrBuyerID[$key]);
					continue;
				}
				echo $buyerID . "start\n";
				
				$buyer = Buyer::find()->select('lID,sDomainName')->where(['lID' => $buyerID])->one();
				if (!$buyer || ($buyer && !$buyer->sDomainName)) {
					unset($arrBuyerID[$key]);
					echo $buyerID . "not exist\n";
					continue;
				}
				
				$sJsonResult = HttpHelper::sendPost($buyer->sDomainName . '/api/cloud_product_push_update', ['lCloudID' => $product->lID]);
				
				$result = json_decode($sJsonResult, true);
				
				if ($result['bSuccess']) {
					unset($arrBuyerID[$key]);
				}
				continue;
			}
			if (!$arrBuyerID) {
				$log->bPush = UpgradeVersionLog::PUSH_SUCCESS;
				$log->sPush = '';
			} else {
				$log->sPush = implode(',', $arrBuyerID);
			}
			$log->save();
			
			echo "push product update success\n\n";
		}
		echo "push product update done\n";
	}
	
	/**
	 * 下载来三斤图片
	 * @author caiguiqin
	 * @time 2019年1月29日 15:21:35
	 */
	public function actionDownloadfromlsj()
	{
		//设置不超时，大量数据需要处理
		set_time_limit(0);
		
		//设置足够的内存
		ini_set("memory_limit", "256M");
		
		$arrLog = UpgradeVersionLog::find()
			->where(['bDownloadImg' => 0, 'StatusID' => 2])
			->orderBy('lID asc')
			->limit(1)
			->All();
		$savePath = "/home/www/yl/apps/backend/web/userfile/upload/";//保存路径
		
		foreach ($arrLog as $log) {
			//查找商品表的 sPic,sMasterPic,sContent
			$product = Product::find()->select('sName,lID,sPic,sMasterPic,sContent,dNewDate')->where(['LSJID' => $log->LSJID, 'LSJStandardID' => $log->StandardID])->one();
			
			// 下载主图
			if ($product['sMasterPic']) {
				if (strpos($product['sMasterPic'], 'standard')) {
					$sImgPath = str_replace('userfile/upload/standard', 'userfile/upload', $product['sMasterPic']);
					$downloadPath = "http://m.laisanjin.cn/" . $sImgPath;
					$this->download($downloadPath, $savePath . 'standard/', $product['sMasterPic'], 1, $product['dNewDate']);
				} else {
					//比如2019
					$downloadPath = "http://image.laisanjin.cn/" . $product['sMasterPic'];
					$this->download($downloadPath, $savePath, $product['sMasterPic'], 1, $product['dNewDate']);
				}
			}
			
			//下载商品详情图
			if ($product['sPic']) {
				$arrPic = json_decode($product['sPic'], true);
				foreach ($arrPic as $pic) {
					if (strpos($pic, 'standard')) {
						$sImgPath = str_replace('userfile/upload/standard', 'userfile/upload', $pic);
						$downloadPath = "http://m.laisanjin.cn/" . $sImgPath;
						
						$this->download($downloadPath, $savePath . 'standard/', $pic, 1, $product['dNewDate']);
					} else {
						//比如2019
						$downloadPath = "http://image.laisanjin.cn/" . $pic;
						$this->download($downloadPath, $savePath, $pic, 1, $product['dNewDate']);
					}
				}
			}
			
			//下载商品内容
			$simpleHtmlDomModel = new simple_html_dom();
			$simpleHtmlDomModel->load($product['sContent']);
			$ssContent = $product['sContent'];
			foreach ($simpleHtmlDomModel->find('img') as $imgKey => $imgValue) {
				$downloadUrl = $imgValue->src;
				$saveUrl = $this->download($downloadUrl, $savePath, '', 2, $product['dNewDate']);
				$ssContent = str_replace($downloadUrl, 'http://product.aiyolian.cn' . $saveUrl, $ssContent);
			}
			$product->sContent = $ssContent;
			$product->save();
			$log->bDownloadImg = 1;
			$log->save();
			echo $log->ProductID . "done\n";
		}
		echo "end push product\n";
		echo "start push productupdate\n";
		//$this->actionPushproductupdate();
	}
	
	/**
	 * @param $url 下载图片路径
	 * @param $savePath  保存路径
	 * @param string $img 路径数据库全路径
	 * @param int $cycleTimes
	 * @return bool|string
	 * @author caiguiqin
	 * @time 2019年1月29日 15:55:12
	 */
	public function download($url, $savePath, $img, $type, $dNewDate)
	{
		$fileName = pathinfo($url, PATHINFO_BASENAME); //文件名字
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_TIMEOUT, 60);
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 0);
		$file = curl_exec($ch);
		curl_close($ch);
		
		if ($type == 1) {
			$arrImg = explode('/', $img);
			$dirName = $arrImg[2];
			$dirName1 = $arrImg[3];
		}
		if ($type == 2) {
			$data = date('Y-m-d', strtotime($dNewDate));
			$arrTime = explode('-', $data);
			$dirName = $arrTime[0];
			$dirName1 = $arrTime[1] . "-" . $arrTime[2];
		}
		
		if (strpos($img, 'standard')) {
			$newSavePath = $savePath . $fileName;
			FileHelper::createDirectory($savePath);
			unlink($newSavePath);
			$resource = fopen($newSavePath, 'a');
		} else {
			$newSavePath = $savePath . $dirName . '/' . $dirName1 . '/' . $fileName;
			unlink($newSavePath);
			FileHelper::createDirectory($savePath . $dirName . '/' . $dirName1);
			$resource = fopen($newSavePath, 'a');
		}
		fwrite($resource, $file);
		fclose($resource);
		
		$url = "/userfile/upload/" . $dirName . '/' . $dirName1 . '/' . $fileName;
		
		return $url;
	}
	
//	public function actionPushproduct()
//	{
//		HttpHelper::sendPost('http://m.dny.group.com/api/cloud_product_push_update', ['lCloudID' => 526]);
//		echo "done";
//		exit;
//		$arrProduct = Product::find()->select('lID')->where(['bSale' => 1])->all();
//		foreach ($arrProduct as $product) {
//			//HttpHelper::sendPost('http://m.hxgongxiao.com/api/cloud_product_push_update', ['lCloudID' => $product->lID]);
//			HttpHelper::sendPost('http://m.dny.group.com/api/cloud_product_push_update', ['lCloudID' => $product->lID]);
//		}
//		echo "done";
//	}
}