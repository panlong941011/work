<?php

$controllerMap = [];

//会员
$controllerMap['member'] = [
    'class' => 'myerm\shop\mobile\controllers\MemberController',
    'events' => [
        ['wechat_callback', ['myerm\shop\common\models\\Member', 'saveWXMemberInfo']],
        ['wechat_callback', ['myerm\shop\mobile\models\\WXUser', 'saveWXUserInfo']],
        ['wechat_callback', ['myerm\shop\common\models\\FrontSession', 'wxLogin']],
        ['logout', ['myerm\shop\common\models\\Member', 'unbindWX']],
        ['logout', ['myerm\shop\mobile\models\\WXUser', 'unbindWX']],
        ['logout', ['myerm\shop\common\models\\FrontSession', 'logout']],
    ]
];

//商品
$controllerMap['product'] = [
    'class' => 'myerm\shop\mobile\controllers\ProductController',
];

//商品
$controllerMap['supplier'] = [
    'class' => 'myerm\shop\mobile\controllers\SupplierController',
];

//首页
$controllerMap['home'] = [
    'class' => 'myerm\shop\mobile\controllers\HomeController',
];

//购物车
$controllerMap['cart'] = [
    'class' => 'myerm\shop\mobile\controllers\CartController',
];

$controllerMap['address'] = [
    'class' => 'myerm\shop\mobile\controllers\AddressController',
];

return $controllerMap;