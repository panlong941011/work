<?php
/**
 * Created by PhpStorm.
 * User: caiguiqin
 * Date: 2019/1/28
 * Time: 16:16
 */
error_reporting(E_ALL & ~E_NOTICE & ~E_STRICT & ~E_WARNING);
$config = include __DIR__ . "/../../../../config/common/main.php";
$lID = $request->post['lID'];

$sName = $request->post['sName'];//商品名称
$keywrod = $request->post['keywrod']; //关键字
if(!$keywrod){
	$keywrod = null;
}
$fPrice = $request->post['fPrice']; //售价
$lQuantity = $request->post['lQuantity']; //库存
$ShipTemplateID = $request->post['ShipTemplateID'];  //商城运费模版ID
$SupplierTemplateID = $request->post['SupplierTemplateID'];  //供应商运费模版

$sProductLogoPath = $request->post['sProductLogoPath']; //首页图片
$fMarketPrice = $request->post['fMarketPrice'];  //市场价
$fWholesalePrice1 = $request->post['fWholesalePrice1']; //进价
if(!$fWholesalePrice1){
	$fWholesalePrice1 = 0;
}
$fWeight = $request->post['fWeight']; //重量
$fWeight = $fWeight*1000;
$sDescription = $request->post['sDescription']; //详情
$CostControl = $request->post['CostControl']; //成本控制
$fFreeShipping = $request->post['fFreeShipping']; //免邮成本
$FreightRegulation = $request->post['FreightRegulation']; //运费调节
$arrImg = $request->post['arrImg']; //详情图数组
$arrImg = json_encode($arrImg);
$SysUserID = $request->post['SysUserID']; //供应商ID
$dNewDate = $request->post['dNewDate'];
$dEditDate = $request->post['dEditDate'];

//先删除
$delsql = "DELETE FROM Product WHERE lID = ".$lID;

$sql = "INSERT INTO `Product` (lID,sName,dNewDate,dEditDate,sRecomm,SupplierID,fPrice,
lStock,bSale,ShipTemplateID,sContent,lSale,sPic,bDel,sMasterPic,
fShowPrice,fBuyerPrice,lWeight,fSupplierPrice,lSaleBase,
MemberShipTemplateID,fFreeShipCost,fShipAdjust,fCostControl
)
VALUES ({$lID},'{$sName}','{$dNewDate}','{$dEditDate}','{$keywrod}','{$SysUserID}','{$fPrice}',
'{$lQuantity}','0','{$SupplierTemplateID}','{$sDescription}','0','{$arrImg}','0','{$sProductLogoPath}',
'{$fMarketPrice}','{$fWholesalePrice1}',{$fWeight},'{$fWholesalePrice1}','0',
'{$ShipTemplateID}',{$FreightRegulation},{$FreightRegulation},{$CostControl}
)";

$connection = new PDO($config['db']['dsn'], $config['db']['username'], $config['db']['password']);
$connection->exec($delsql);
$resultSql = $connection->exec($sql);

return $resultSql;
