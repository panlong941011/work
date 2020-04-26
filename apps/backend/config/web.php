<?php

//调用ERM缺省的配置
$config = require(__DIR__ . '/../../common/config/web.php');


//修改某些值
$config['basePath'] = dirname(__DIR__);
$config['controllerNamespace'] = "myerm\backend";

$config['components']['request'] = [
    'class' => 'myerm\common\components\Request',
    'cookieValidationKey' => 'backenderm',
];

$config['components']['backendsession'] = [
    'class' => 'myerm\backend\common\models\Session',
];

$config['components']['errorHandler'] = [
    'errorAction' => 'system/error/home',
];


$config['modules']['debug'] = [
    'class' => 'myerm\backend\common\models\debug\Module',
    'allowedIPs' => $config['params']['whitelist'],
];

$config['modules']['shop'] = [
    'class' => 'myerm\shop\backend\Module',
];

$config['modules']['system'] = [
    'class' => 'myerm\backend\system\Module',
];

$config['modules']['common'] = [
    'class' => 'myerm\backend\common\Module',
];

$config['modules']['objectbuilder'] = [
    'class' => 'myerm\backend\objectbuilder\Module',
    'allowedIPs' => $config['params']['whitelist'],
];


return $config;