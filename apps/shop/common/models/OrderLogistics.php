<?php

namespace myerm\shop\common\models;

/**
 * 订单地址
 * ============================================================================
 * 版权所有 2010-2017 三只象（厦门）网络科技有限公司，并保留所有权利。
 * 网站地址: http://www.myerm.cn
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和
 * 使用；不允许对程序代码以任何形式任何目的的再发布。
 * ============================================================================
 * @author cgq
 * @time 2018年7月18日
 * @version v1.0
 */
class OrderLogistics extends ShopModel
{
	public static function getOrderInfo($orderID)
	{
		return OrderLogistics::find()->where(['OrderID' => $orderID])->asArray()->all();
	}
	
	public static function selectOrderLogisticsInfo($orderID)
	{
		return OrderLogistics::find()->select('sExpressOrderInfo,sExpressNo,sReturnedTemplate,sReason,sName')->where(['OrderID' => $orderID])->asArray()->all();
	}
	
	
	public static function modifyLogistics($sName, $sExpressNo, $sExpressCompany, $sExpressOrderInfo, $printTemplate)
	{
		$OrderLogisticsModel = OrderLogistics::find()->where(['sName' => $sName])->one();
		$OrderLogisticsModel->sExpressNo = $sExpressNo;
		$OrderLogisticsModel->sExpressCompany = $sExpressCompany;
		$OrderLogisticsModel->sExpressOrderInfo = $sExpressOrderInfo;
		$OrderLogisticsModel->sReturnedTemplate = $printTemplate;
		$OrderLogisticsModel->ShipID = "wuliu";
		$OrderLogisticsModel->sReason = "成功";
		$OrderLogisticsModel->dNewDate = \Yii::$app->formatter->asDatetime(time());
		$OrderLogisticsModel->dShipDate = \Yii::$app->formatter->asDatetime(time());
		$OrderLogisticsModel->ExpressOrderStatusID = 1;
		$result = $OrderLogisticsModel->save();
		return $result;
	}
	
	public static function failureLogistics($sName, $Reason)
	{
		$OrderLogisticsModel = OrderLogistics::find()->where(['sName' => $sName])->one();
		$OrderLogisticsModel->sReason = $Reason;
		$OrderLogisticsModel->save();
	}
	
	public static function getOrderLogisticsInfo($orderID)
	{
		return OrderLogistics::find()->where(['OrderID' => $orderID])->asArray()->one();
	}
	
	public static function changeStatus($lID)
	{
		$OrderLogisticsModel = OrderLogistics::findOne($lID);
		$OrderLogisticsModel->ExpressOrderStatusID = 2;
		$OrderLogisticsModel->save();
	}
	
	/**
	 * 保存订单物流信息
	 * @param $arrParam
	 * @return bool
	 * @throws \yii\base\InvalidConfigException
	 * @author hcc
	 * @time 2018年7月11日17:45:15
	 */
	public static function saveValue($arrParam)
	{
		//订单
		$Order = Order::findByID($arrParam['OrderID']);
		
		//整理商品信息
		$arrProductInfo = [];
		foreach ($arrParam['sProductName'] as $productKey => $productValue) {
			foreach ($productValue as $key => $productName) {
				$arrProductInfo[$productKey][] = [
					'ProductID' => $arrParam['ProductID'][$productKey][$key],
					'sName' => $productName,
					'sSKU' => $arrParam['sSKU'][$productKey][$key],
					'sKeyword' => $arrParam['sKeyword'][$productKey][$key],
					'lQuantity' => $arrParam['lQuantity'][$productKey][$key]
				];
			}
		}
		
		//删除原有数据
		static::deleteAll(['OrderID' => $arrParam['OrderID']]);
		
		//保存数据
		foreach ($arrProductInfo as $key => $productValue) {
			$sOrderDetailID = ";";
			foreach ($productValue as $product) {
				if ($product['sSKU']) {
					$OrderDetail = OrderDetail::find()
						->where([
							'and',
							['ProductID' => $product['ProductID']],
							['OrderID' => $arrParam['OrderID']],
							['sSKU' => $product['sSKU']]
						])
						->one();
				} else {
					$OrderDetail = OrderDetail::find()
						->where([
							'and',
							['ProductID' => $product['ProductID']],
							['OrderID' => $arrParam['OrderID']]
						])
						->one();
				}
				
				$sOrderDetailID .= $OrderDetail->lID . ';';
			}
			
			$OrderLogistics = new static();
			$OrderLogistics->sName = $Order->sName . '-' . $key;
			$OrderLogistics->sProductInfo = json_encode($productValue);
			$OrderLogistics->OrderID = $arrParam['OrderID'];
			$OrderLogistics->sOrderDetailID = $sOrderDetailID;
			$OrderLogistics->SupplierID = $Order->SupplierID;
			$OrderLogistics->dNewDate = \Yii::$app->formatter->asDatetime(time());;
			$OrderLogistics->save();
		}
		
		return true;
	}
	
	
	//修改操作
	public static function modifyValue($arr)
	{
		$OrderLogisticsModel = OrderLogistics::find()->where([$arr['type'] => $arr['orderID']])->one();
		$OrderLogisticsModel->sExpressNo = $arr['sExpressNo'];
		$OrderLogisticsModel->sExpressCompany = $arr['sExpressCompany'];
		if ($arr['ExpressOrderStatusID']) {
			$OrderLogisticsModel->ExpressOrderStatusID = $arr['ExpressOrderStatusID'];
		}
		if ($arr['ShipID']) {
			$OrderLogisticsModel->ShipID = $arr['ShipID'];
		}
		if ($arr['dShipDate']) {
			$OrderLogisticsModel->dShipDate = $arr['dShipDate'];
		}
		$result = $OrderLogisticsModel->save();
		return $result;
	}
	
	public static function addValue($array)
	{
		$orderLogisticsModel = new OrderLogistics();
		if ($array['sName']) {
			$orderLogisticsModel->sName = $array['sName'];
		}
		if ($array['sProductInfo']) {
			$orderLogisticsModel->sProductInfo = $array['sProductInfo'];
		}
		if ($array['OrderID']) {
			$orderLogisticsModel->OrderID = $array['OrderID'];
		}
		if ($array['sOrderDetailID']) {
			$orderLogisticsModel->sOrderDetailID = $array['sOrderDetailID'];
		}
		if ($array['sExpressNo']) {
			$orderLogisticsModel->sExpressNo = $array['sExpressNo'];
		}
		if ($array['sExpressCompany']) {
			$orderLogisticsModel->sExpressCompany = $array['sExpressCompany'];
		}
		if ($array['ShipID']) {
			$orderLogisticsModel->ShipID = $array['ShipID'];
		}
		if ($array['ExpressOrderStatusID']) {
			$orderLogisticsModel->ExpressOrderStatusID = $array['ExpressOrderStatusID'];
		}
		if ($array['dShipDate']) {
			$orderLogisticsModel->dShipDate = $array['dShipDate'];
		}
		if ($array['SupplierID']) {
			$orderLogisticsModel->SupplierID = $array['SupplierID'];
		}
		$orderLogisticsModel->dNewDate = \Yii::$app->formatter->asDatetime(time());
		$orderLogisticsModel->save();
		
		return $orderLogisticsModel->lID;
	}
	
	public function getOrder()
	{
		return $this->hasOne(Order::className(), ['lID' => 'OrderID']);
	}
	
	/**
	 * 一键拆单保存
	 * @param $arrParam
	 * @throws \yii\base\InvalidConfigException
	 * @author hechengcheng
	 * @time 2018年7月20日17:15:15
	 */
	public static function saveQuickSeparateValue($arrParam)
	{
		foreach ($arrParam['OrderID'] as $OrderID) {
			//删除原有数据
			static::deleteAll(['OrderID' => $OrderID]);
			//订单
			$order = Order::findByID($OrderID);
			//子订单编号
			$num = 1;
			
			foreach ($order->arrDetail as $detail) {
				//匹配拆分条件
				foreach ($arrParam['productInfo'] as $productInfo) {
					if ($detail->ProductID == $productInfo['ProductID'] && $detail->sSKU == $productInfo['sSKU']) {
						//订单详情商品数量
						$detailNum = $detail->lQuantity;
						//拆分数量
						$separateNum = $productInfo['lQuantity'];
						
						for ($detailNum; $detailNum > 0; $detailNum -= $separateNum) {
							$lQuantity = $detailNum >= $separateNum ? $separateNum : $detailNum;
							$product = [];
							$product[] = [
								'ProductID' => $detail->ProductID,
								'sName' => $detail->sName,
								'sSKU' => $detail->sSKU,
								'sKeyword' => $detail->product->sKeyword,
								'lQuantity' => $lQuantity
							];
							$OrderLogistics = new static();
							$OrderLogistics->sName = $order->sName . '-' . $num;
							$OrderLogistics->sProductInfo = json_encode($product);
							$OrderLogistics->OrderID = $OrderID;
							$OrderLogistics->sOrderDetailID = ";" . $detail->lID . ";";
							$OrderLogistics->SupplierID = $order->SupplierID;
							$OrderLogistics->dNewDate = \Yii::$app->formatter->asDatetime(time());
							$OrderLogistics->save();
							$num++;
						}
					}
				}
			}
		}
		return true;
	}
	
	/**
	 * 保存子平台回传的订单物流ID
	 * @param $ID
	 * @param $childID
	 * @author hechengcheng
	 * @time 2018年7月25日16:06:05
	 */
	public static function saveChildID($ID, $childID)
	{
		$orderLogistics = static::findByID($ID);
		$orderLogistics->lChildID = $childID;
		$orderLogistics->save();
	}
	
}