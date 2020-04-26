<?php

$config = [];

/**
 * 配置数据库，用于不同的环境
 */
if (isset($_SERVER['HTTP_HOST']) && strpos($_SERVER['HTTP_HOST'],'m')!==false) {
    //前台数据库
    $config['db'] = [
        'class' => 'yii\db\Connection',
        'dsn' => 'mysql:host=127.0.0.1;dbname=ylwholesaler',
        'username' => 'root',
        'password' => '123456',
        'enableSchemaCache' => true,
        'charset' => 'utf8mb4',
    ];

    $config['redis'] = [
        'class' => 'yii\redis\Connection',
        'hostname' => '127.0.0.1',
        'port' => 6379,
        'database' => 0
    ];

    $config['db_wholesaler'] = [
        'class' => 'yii\db\Connection',
        'dsn' => 'mysql:host=127.0.0.1;dbname=ylwholesaler',
        'username' => 'root',
        'password' => '123456',
        'enableSchemaCache' => true,
        'charset' => 'utf8mb4',
    ];
    $config['cloud'] = [
        'class' => 'yii\db\Connection',
        'dsn' => 'mysql:host=127.0.0.1;dbname=ylcloud',
        'username' => 'root',
        'password' => '123456',
        'enableSchemaCache' => true,
        'charset' => 'utf8',
    ];
    //快递鸟在线获取单号接口
    $KDBirdUrl = 'http://testapi.kdniao.com:8081/api/Eorderservice';//测试环境
} else {
    //后台数据库
    $config['db'] = [
        'class' => 'yii\db\Connection',
        'dsn' => 'mysql:host=127.0.0.1;dbname=ylcloud',
        'username' => 'root',
        'password' => 'root',
        'enableSchemaCache' => true,
        'charset' => 'utf8',
    ];
    $config['cloud'] = [
        'class' => 'yii\db\Connection',
        'dsn' => 'mysql:host=127.0.0.1;dbname=ylcloud',
        'username' => 'root',
        'password' => '123456',
        'enableSchemaCache' => true,
        'charset' => 'utf8',
    ];
    $config['redis'] = [
        'class' => 'yii\redis\Connection',
        'hostname' => 'r-uf61001f40b4a174.redis.rds.aliyuncs.com',
        'port' => 6379,
        'password' => 'ELbQyOpgKmb5tQrt',
        'database' => 0
    ];

    $config['db_wholesaler'] = [
        'class' => 'yii\db\Connection',
        'dsn' => 'mysql:host=127.0.0.1;dbname=ylwholesaler',
        'username' => 'root',
        'password' => '123456',
        'enableSchemaCache' => true,
        'charset' => 'utf8mb4',
    ];
    $config['cloud'] = [
        'class' => 'yii\db\Connection',
        'dsn' => 'mysql:host=127.0.0.1;dbname=ylcloud',
        'username' => 'root',
        'password' => '123456',
        'enableSchemaCache' => true,
        'charset' => 'utf8',
    ];
    //快递鸟在线获取单号接口
    $KDBirdUrl = 'http://api.kdniao.com/api/Eorderservice';//正式环境
}

/**
 *  在控制台模式下
 */
if (defined('YII_CONSOLE') && YII_CONSOLE) {
    $config['db'] = [
        'class' => 'yii\db\Connection',
        'dsn' => 'mysql:host=rm-uf6099wq81218v1fk.mysql.rds.aliyuncs.com;dbname=ylwholesaler',
        'username' => 'root',
        'password' => 'root',
        'enableSchemaCache' => true,
        'charset' => 'utf8'
    ];
    $config['cloud'] = [
        'class' => 'yii\db\Connection',
        'dsn' => 'mysql:host=127.0.0.1;dbname=ylcloud',
        'username' => 'root',
        'password' => '123456',
        'enableSchemaCache' => true,
        'charset' => 'utf8',
    ];
}

if (!defined('YII_CONSOLE') || !YII_CONSOLE) {

    //参数配置
    $config['params'] = [
        'adminEmail' => 'admin@myerm.cn',
        'WECHAT' => require(__DIR__ . '/wechat.php'),
        'debugExpireTime' => 86400 * 7,//Debug的过期时间，默认五天，过期时候，系统会自动删除记录。
    ];

    $config['params']['sUploadDir'] = dirname(__DIR__) . '/../apps/backend/web';
    $config['params']['sUploadUrl'] = 'http://' . $_SERVER['HTTP_HOST'];
    $config['params']['sSystemTitle'] = '有链供应链系统管理后台';
    $config['params']['sExpressApiUrl'] = "http://express.shop.myerm.cn";//快递接口
}

//这里在部署的时候需要修改
if (!YII_CONSOLE && stristr($_SERVER['HTTP_HOST'], "product.")) {
    $config['params']['sShopERMHomeUrl'] = 'http://' . str_ireplace("product.", "m.", $_SERVER['HTTP_HOST']);
}

$config['params']['KDBirdUrl'] = $KDBirdUrl;

//白名单，用于对象管理器，Debug
$config['params']['whitelist'] = ['127.0.0.1', '::1','47.103.107.98'];

//快递的用户名密码
$config['sms'] = [
    'class' => 'myerm\sms\weilaiwuxian\Sms',
    'sAccount' => '301066',
    'sPass' => 'YYS7A6MZ51',
];

//配置快递接口
$config['expresstrace'] = [
    'class' => 'myerm\kuaidi100\models\ExpressTrace',
    'sKey' => 'tNQmDZkj7809',//授权密匙(Key)
    'sCustomerNo' => '9E1FC70D937810D7ECF8C76D196C7DE1'//公司编号(customer)
];
//云端接口
$config['dnyapi'] = [
    'class' => 'myerm\shop\common\components\DnyApi',
];
//是否启用debug
defined('MYERM_DEBUG') or define('MYERM_DEBUG', true);

return $config;
