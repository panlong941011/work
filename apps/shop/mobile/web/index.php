<?php

error_reporting(E_ALL & ~E_NOTICE & ~E_STRICT & ~E_WARNING);

//启用压缩
ini_set("zlib.output_compression", "On");

//强制转换UTF-8
@header("Content-Type:text/html; charset=utf-8");

// comment out the following two lines when deployed to production
defined('YII_DEBUG') or define('YII_DEBUG', true);
defined('YII_CONSOLE') or define('YII_CONSOLE', false);
defined('YII_ENV') or define('YII_ENV', 'dev');

require(__DIR__ . '/../../../../vendor/autoload.php');
require(__DIR__ . '/../../../../vendor/yiisoft/yii2/Yii.php');

$config = require(__DIR__ . '/../config/web.php');

Yii::setAlias('@app', __DIR__ . '/../');
Yii::setAlias('@myerm', __DIR__ . '/../../../');
Yii::setAlias('@myerm/shop', __DIR__ . '/../../../shop');

$app = new yii\web\Application($config);

//Yii::$app->on('beforeRequest', function ($event) {
//    \Yii::$app->db->beginTransaction();
//});
//
//Yii::$app->on('afterRequest', function ($event) {
//    \Yii::$app->db->getTransaction()->commit();
//});
//
//register_shutdown_function(function () {
//    if ($trans = \Yii::$app->db->getTransaction()) {
//        $trans->rollBack();
//    }
//});

$app->run();