<?php
/**
 * 同步云端促销活动接口
 */
error_reporting(E_ALL & ~E_NOTICE & ~E_STRICT & ~E_WARNING);
$result = [];

$slID = $request->get['slID'];

if (empty($slID)) {
	throw new \Exception("参数缺失，请检查参数后重试。");
}
$slID = addslashes(str_replace(';', ',', $slID));

$sql = "SELECT lID,sName,dStart,dEnd,lPurchase,bActive FROM SalesPromotion
        WHERE lID in (" . $slID . ")";

$config = include __DIR__ . "/../../../../config/common/main.php";

$connection = new PDO($config['db']['dsn'], $config['db']['username'], $config['db']['password']);

$resultSql = $connection->prepare($sql);
$resultSql->execute();

$SalesPromotion = $resultSql->fetchAll(PDO::FETCH_ASSOC);

if (!$SalesPromotion) {
	throw new \Exception("云端促销活动不存在");
}

foreach ($SalesPromotion as $key => $sale) {
	$resultSql = $connection->prepare("SELECT ProductID,CostControl,fFreeShippingCost,lQuantity,fPrice,
										FreightRegulation,fBuyerPrice,ShipTemplateID,SupplierShipTemplateID
										FROM SalesPromotionDetail WHERE SalesPromotionID='{$sale['lID']}'");
	$resultSql->execute();
	$SalesPromotion[$key]['detail'] = $resultSql->fetchAll(PDO::FETCH_ASSOC);
}

return $SalesPromotion;


