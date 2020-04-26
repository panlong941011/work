<?php

/**
 * web配置
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

//调用ERM缺省的配置
$config = require(__DIR__ . '/../../../common/config/web.php');

$main = require(__DIR__ . '/../../../../config/shop/main.php');

//修改ID
$config['id'] = 'ShopERM';

//加载组件配置
$config['components'] = $main['components'] ? array_merge($config['components'], $main['components']) : $config['components'];

if (MYERM_DEBUG && php_sapi_name() != "cli") {
    $config['modules']['debug'] = [
        'class' => 'myerm\shop\common\models\debug\Module',
        'allowedIPs' => $config['params']['whitelist'],
    ];
} else {
    unset($config['modules']['debug']);
}

return $config;
