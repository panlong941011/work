<?php

$mysql = new \Swoole\Coroutine\MySQL();

$dsn = str_ireplace("mysql:", "", Yii::$app->db->dsn);
$arr = explode(";", $dsn);

$port = 3306;
if (preg_match("/port=([0-9]{1,})/", $dsn, $m)) {
    $port = $m[1];
}


$bConn = $mysql->connect([
    'host' => explode("=", $arr[0])[1],
    'user' => Yii::$app->db->username,
    'password' => Yii::$app->db->password,
    'database' => explode("=", $arr[count($arr) - 1])[1],
    'port' => $port,
    'charset' => Yii::$app->db->charset,
]);
if (!$bConn) {
    throw new \Exception($mysql->connect_error);
}

$mysql->setDefer();