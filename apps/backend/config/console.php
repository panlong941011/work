<?php

//调用ERM缺省的配置
$config = require(__DIR__ . '/web.php');

$config['id'] = 'MyERM-Console';

$config['bootstrap'] = ['log'];
$config['modules'] = [];

//加载参数
$config['params'] = $params;

//记载数据库配置
$config['components'] = [
    'log' => [
        'targets' => [
            [
                'class' => 'yii\log\FileTarget',
                'levels' => ['error', 'warning'],
            ],
        ],
    ],
    'db' => $db
];

//修改某些值
$config['basePath'] = dirname(__DIR__).'/console';
$config['runtimePath'] = dirname(__DIR__).'/runtime';
$config['controllerNamespace'] = "console\controllers";

return $config;
