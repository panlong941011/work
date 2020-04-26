<?php
/**
 * 查询云端商品数据接口
 * @author 张正帝  <919059960@qq.com>
 * @time 2018年06月01日
 */
error_reporting(E_ALL & ~E_NOTICE & ~E_STRICT & ~E_WARNING);
$result = [];

$idList = $request->get['idlist'];
if (empty($idList)) {
    throw new \Exception("参数缺失，请检查参数后重试。");
}
$idList = addslashes(str_replace(';', ',', $idList));

$sql = "SELECT p.lID,p.sName,p.sRecomm,p.SupplierID,p.fPrice,p.fBuyerPrice,p.sContent,p.sMasterPic,p.sPic,p.lWeight,p.lStock,p.bSale,p.bDel,p.sParameterArray,p.ShipTemplateID,p.MemberShipTemplateID,p.fFreeShipCost,p.fShipAdjust,p.fCostControl,
        b.lID as blID,b.sName as bsName, 
        s.lID as slID,s.sContent as ssContent,s.sName as ssName,s.sPicPath as ssPicPath,s.sRefundAddress as ssRefundAddress, 
        t.CityID 
        FROM Product as p 
        LEFT JOIN ProductBrand as b 
        ON p.ProductBrandID=b.lID 
        LEFT JOIN Supplier as s 
        ON p.SupplierID=s.lID 
        LEFT JOIN ShipTemplate as t 
        ON p.ShipTemplateID=t.lID ProductParamTemplateID
        WHERE p.bSale=1 
        AND p.bDel=0 ";
$sqlProductSpecification = "SELECT * FROM ProductSpecification ";
$sqlProductSKU = "SELECT lID,ProductID,sValue,fPrice,fBuyerPrice,lStock FROM ProductSKU ";

if ($idList != "all") {
    $sql = $sql . "AND p.lID in(" . $idList . ")";
    $sqlProductSpecification = $sqlProductSpecification . "WHERE ProductID in(" . $idList . ")";
    $sqlProductSKU = $sqlProductSKU . "WHERE ProductID in(" . $idList . ")";
}

$config = include __DIR__ . "/../../../../config/common/main.php";
try {
    $connection = new PDO($config['db']['dsn'], $config['db']['username'], $config['db']['password']);

    $resultSql = $connection->prepare($sql);
    $resultSql->execute();

    $dataProduct = $resultSql->fetchAll(PDO::FETCH_ASSOC);
    if (!$dataProduct) {
        throw new \Exception("云端商品数据不存在。");
    } else {
        $data['product'] = $dataProduct;
    }

    $resultSqlProductSpecification = $connection->prepare($sqlProductSpecification);
    $resultSqlProductSpecification->execute();
    $dataProductSpecification = $resultSqlProductSpecification->fetchAll(PDO::FETCH_ASSOC);
    $data['productspecification'] = $dataProductSpecification;

    $resultSqlProductSKU = $connection->prepare($sqlProductSKU);
    $resultSqlProductSKU->execute();
    $dataProductSKU = $resultSqlProductSKU->fetchAll(PDO::FETCH_ASSOC);
    $data['productsku'] = $dataProductSKU;

    //买家运费模板，2018年6月20日 15:11:00
    $data['membershiptemplate'] = [];
    foreach ($dataProduct as $i => $product) {
        if ($product['MemberShipTemplateID']) {
            $resultSql = $connection->prepare("SELECT * FROM ShipTemplate WHERE lID='{$product['MemberShipTemplateID']}'");
            $resultSql->execute();
            $data['membershiptemplate'][$product['MemberShipTemplateID']]['ShipTemplate'] = $resultSql->fetch(PDO::FETCH_ASSOC);

            $resultSql = $connection->prepare("SELECT * FROM ShipTemplateDetail WHERE ShipTemplateID='{$product['MemberShipTemplateID']}'");
            $resultSql->execute();
            $data['membershiptemplate'][$product['MemberShipTemplateID']]['ShipTemplateDetail'] = $resultSql->fetchAll(PDO::FETCH_ASSOC);

            $resultSql = $connection->prepare("SELECT * FROM ShipTemplateFree WHERE ShipTemplateID='{$product['MemberShipTemplateID']}'");
            $resultSql->execute();
            $data['membershiptemplate'][$product['MemberShipTemplateID']]['ShipTemplateFree'] = $resultSql->fetchAll(PDO::FETCH_ASSOC);

            $resultSql = $connection->prepare("SELECT * FROM ShipTemplateNoDelivery WHERE ShipTemplateID='{$product['MemberShipTemplateID']}'");
            $resultSql->execute();
            $data['membershiptemplate'][$product['MemberShipTemplateID']]['ShipTemplateNoDelivery'] = $resultSql->fetchAll(PDO::FETCH_ASSOC);
        }
    }

    //供应商运费模板，2018年6月20日 15:11:06
    $data['suppliershiptemplate'] = [];
    foreach ($dataProduct as $i => $product) {
        if ($product['ShipTemplateID']) {
            $resultSql = $connection->prepare("SELECT * FROM ShipTemplate WHERE lID='{$product['ShipTemplateID']}'");
            $resultSql->execute();
            $data['suppliershiptemplate'][$product['ShipTemplateID']]['ShipTemplate'] = $resultSql->fetch(PDO::FETCH_ASSOC);

            $resultSql = $connection->prepare("SELECT * FROM ShipTemplateDetail WHERE ShipTemplateID='{$product['ShipTemplateID']}'");
            $resultSql->execute();
            $data['suppliershiptemplate'][$product['ShipTemplateID']]['ShipTemplateDetail'] = $resultSql->fetchAll(PDO::FETCH_ASSOC);

            $resultSql = $connection->prepare("SELECT * FROM ShipTemplateFree WHERE ShipTemplateID='{$product['ShipTemplateID']}'");
            $resultSql->execute();
            $data['suppliershiptemplate'][$product['ShipTemplateID']]['ShipTemplateFree'] = $resultSql->fetchAll(PDO::FETCH_ASSOC);

            $resultSql = $connection->prepare("SELECT * FROM ShipTemplateNoDelivery WHERE ShipTemplateID='{$product['ShipTemplateID']}'");
            $resultSql->execute();
            $data['suppliershiptemplate'][$product['ShipTemplateID']]['ShipTemplateNoDelivery'] = $resultSql->fetchAll(PDO::FETCH_ASSOC);
        }
    }

    return $data;
} catch (\Exception $e) {
    throw new \Exception($e->getMessage());
}