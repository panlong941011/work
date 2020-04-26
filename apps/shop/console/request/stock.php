<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/4/25
 * Time: 15:19
 */

if ($request) {

    if ($request->get['sku']) {
        $mysql->query("SELECT Product.lID, Product.bDel, ProductSKU.lStock, Product.bSale FROM ProductSKU INNER JOIN Product ON Product.lID=ProductSKU.ProductID 
                          WHERE ProductSKU.ProductID='" . addslashes($request->get['productid']) . "' AND ProductSKU.sValue='" . addslashes(urldecode($request->get['sku'])) . "'");
    } else {
        $mysql->query("SELECT lID, bDel, lStock, bSale FROM Product WHERE lID='" . addslashes($request->get['productid']) . "'");
    }

    $mysql_res = $mysql->recv();

    if ($mysql_res === false) {
        throw new \Exception("查询失败，错误提示：" . $mysql->error);
    } else {
        if (!$mysql_res[0]) {
            throw new \Exception("此商品不存在");
        } else {
            return [
                'id' => $mysql_res[0]['lID'],
                'stock' => $mysql_res[0]['lStock'],
                'del' => $mysql_res[0]['bDel'],
                'sale' => $mysql_res[0]['bSale']
            ];
        }
    }
} else {
    $config = include __DIR__ . "/../../../../config/common/main.php";

    $output = [];
    try {
        $connection = new PDO($config['db']['dsn'], $config['db']['username'], $config['db']['password']);

        if ($_GET['sku']) {
            $stmt = $connection->prepare("SELECT Product.lID, Product.bDel, ProductSKU.lStock, Product.bSale FROM ProductSKU INNER JOIN Product ON Product.lID=ProductSKU.ProductID 
                          WHERE ProductSKU.ProductID='" . addslashes($_GET['productid']) . "' AND ProductSKU.sValue='" . addslashes(urldecode($_GET['sku'])) . "'");
        } else {
            $stmt = $connection->prepare("SELECT lID, bDel, lStock, bSale FROM Product WHERE lID='" . addslashes($_GET['productid']) . "'");
        }

        // call the stored procedure
        $stmt->execute();

        // fetch all rows into an array.
        $row = $stmt->fetch();

        if (!$row) {
            throw new \PDOException("此商品不存在");
        } else {
            $output['status'] = 0;
            $output['data'] = [
                'id' => $row['lID'],
                'stock' => $row['lStock'],
                'del' => $row['bDel'],
                'sale' => $row['bSale']
            ];
        }
    } catch (PDOException $e) {
        $output['status'] = -1;
        $output['errmsg'] = $e->getMessage();
    }

    echo json_encode($output);
}
