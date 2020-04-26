<?php

//调用ERM缺省的配置
$config = require(__DIR__ . '/../../common/config/web.php');

$config['id'] = 'Shop-Console';

//$config['bootstrap'] = ['log'];
//$config['modules'] = [];
$config['controllerMap'] = [];

unset($config['components']['request']);
unset($config['components']['errorHandler']);

//修改某些值
$config['basePath'] = dirname(__DIR__);
$config['controllerNamespace'] = "console\controllers";

return $config;
