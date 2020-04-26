<?php

$arrFoot = [
    [
        'url' => \yii\helpers\Url::toRoute([\Yii::$app->request->shopUrl . "/home"], true),
        'icon' => '&#xe617;',
        'sName' => '首页',
        'active' => strpos($_SERVER['REQUEST_URI'], 'home') !== false ? 'on' : '',
        'show' =>true
    ],
    [
        'url' => \yii\helpers\Url::toRoute([\Yii::$app->request->shopUrl . "/cart"], true),
        'icon' => '&#xe638;',
        'sName' => '购物车',
        'active' => strpos($_SERVER['REQUEST_URI'], 'alliance') !== false ||strpos($_SERVER['REQUEST_URI'], 'cart') !== false? 'on' : '',
        'show'=>true
    ],
    [
        'url' => \yii\helpers\Url::toRoute([\Yii::$app->request->shopUrl . "/member"], true),
        'icon' => '&#xe64a;',
        'sName' => '我的',
        'active' => strpos($_SERVER['REQUEST_URI'], 'member') !== false ? 'on' : '',
        'show'=>true

    ],
];
?>
<footer>
    <div class="bottom_fixed flex">
        <? foreach ($arrFoot as $foot) { if($foot['show']){?>
            <a href="<?= $foot['url'] ?>" class="<?= $foot['active'] ?>">
                <span class="icon"><?= $foot['icon'] ?></span>
                <p><?= $foot['sName'] ?></p>
            </a>
        <? }} ?>
    </div>
</footer>