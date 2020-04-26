<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/4/25
 * Time: 15:19
 */

if ($request) {

    $mysql->query("SELECT ProductSKU.* FROM ProductSKU INNER JOIN Product ON Product.lID=ProductSKU.ProductID 
                                                WHERE ProductSKU.ProductID='" . addslashes($request->get['productid']) . "'");

    $mysql_res = $mysql->recv();

    if ($mysql_res === false) {
        throw new \Exception("查询失败，错误提示：" . $mysql->error);
    } else {
        $data = [];
        foreach ($mysql_res as $res) {
            $data[$res['sValue']] = $res['lStock'];
        }

        return $data;
    }
} else {
    $config = include __DIR__ . "/../../../../config/common/main.php";

    $output = [];
    try {
        $connection = new PDO($config['db']['dsn'], $config['db']['username'], $config['db']['password']);

        $stmt = $connection->prepare("SELECT ProductSKU.* FROM ProductSKU INNER JOIN Product ON Product.lID=ProductSKU.ProductID 
                                                WHERE ProductSKU.ProductID='" . addslashes($_GET['productid']) . "'");

        // call the stored procedure
        $stmt->execute();

        $output['status'] = 0;
        $output['data'] = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $output['data'][$row['sValue']] = $row['lStock'];
        }

    } catch (PDOException $e) {
        $output['status'] = -1;
        $output['errmsg'] = $e->getMessage();
    }

    echo json_encode($output);
}
