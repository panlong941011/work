<?php

/**
 * 移动端的web配置
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

//调用ShopERM缺省的配置
$config = require(__DIR__ . '/../../common/config/web.php');

$main = require(__DIR__ . '/../../../../config/shop/main.php');

//加载组件配置
$config['components'] = $config['components'] ? array_merge($config['components'],
    $main['components']) : $main['components'];
$config['controllerMap'] = $config['controllerMap'] ? array_merge($config['controllerMap'],
    $main['controllerMap']) : $main['controllerMap'];


//修改某些值
$config['basePath'] = dirname(__DIR__);
$config['controllerNamespace'] = "myerm\shop\\mobile\controllers";
$config['components']['request']['cookieValidationKey'] = 'shopermmobile';


//路由规则
$config['components']['urlManager']['rules'] = [
    '<sShopUrl:shop\d+>/<controller:\w+>/<action:\w+>' => '<controller>/<action>',
    '<sShopUrl:shop\d+>/<controller:\w+>' => '<controller>/index',
];

return $config;
