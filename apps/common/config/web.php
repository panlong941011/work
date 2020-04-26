<?php

/**
 * 通用的web配置，所有的应用都将继承这个配置，然后各自的应用再重写配置的某些值。
 * ============================================================================
 * 版权所有 2010-2017 三只象（厦门）网络科技有限公司，并保留所有权利。
 * 网站地址: http://www.myerm.cn
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和
 * 使用；不允许对程序代码以任何形式任何目的的再发布。
 * ============================================================================
 *
 * @author 陈鹭明 <lumingchen@qq.com>
 * @since 2017年9月3日 09:11:11
 * @version v2.0
 */

$main = require(__DIR__ . '/../../../config/common/main.php');

$config = [
    'id' => 'MyERM',
    'bootstrap' => ['log', 'devicedetect'],
    'basePath' => dirname(__DIR__),//应用里必须要修改此值
    'vendorPath' => dirname(__DIR__) . "/../../vendor",
    'runtimePath' => '@myerm/common/runtime',
    'timeZone' => 'Asia/Shanghai',
    'components' => [
        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => 'myerm',//应用里必须要修改此值
            'class' => 'myerm\common\components\Request',
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
            'cachePath' => '@myerm/common/runtime/cache',
        ],
        'wechat' => [
            'class' => 'maxwen\easywechat\Wechat',
            // 'userOptions' => []  # user identity class params
            // 'sessionParam' => '' # wechat user info will be stored in session under this key
            // 'returnUrlParam' => '' # returnUrl param stored in session
        ],
        'myerm' => [
            'class' => 'myerm\common\models\MyERMModel',
        ],
        'errorHandler' => [
            //'errorAction' => 'error',
            // 'class' => 'myerm\common\components\ErrorHandler',
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'myerm\common\components\log\DbTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            // send all mails to a file by default. You have to set
            // 'useFileTransport' to false and configure a transport
            // for the mailer to send real emails.
            'useFileTransport' => true,
        ],
        'formatter' => [
            'dateFormat' => 'yyyy-MM-dd',
            'datetimeFormat' => 'yyyy-MM-dd HH:mm:ss',
            'decimalSeparator' => ',',
            'thousandSeparator' => ' ',
            'currencyCode' => 'CNY',
        ],
        'db' => $main['db'],
	    'db_wholesaler' => $main['db_wholesaler'],
	    'ds_cloud' => $main['cloud'],
        'redis' => $main['redis'],
        'sms' => $main['sms'],
	    'dnyapi' => $main['dnyapi'],
        'bankcardverify' => $main['bankcardverify'],
        'expresstrace' => $main['expresstrace'],
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [
            ],
        ],
        'devicedetect' => [
            'class' => 'myerm\common\components\DeviceDetect',
        ],
    ],
    'params' => $main['params'],
];

if (YII_ENV_DEV) {
    // configuration adjustments for 'dev' environment

    if (MYERM_DEBUG && php_sapi_name() != 'cli') {
        $config['bootstrap'][] = 'debug';
        $config['modules']['debug'] = [
            'class' => 'yii\debug\Module',
            // uncomment the following to add your IP if you are not connecting from localhost.
            'allowedIPs' => ['127.0.0.1', '::1'],
        ];
    }

    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
        // uncomment the following to add your IP if you are not connecting from localhost.
        'allowedIPs' => ['127.0.0.1', '::1'],
    ];
}

return $config;
