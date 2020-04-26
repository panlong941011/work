<?php
use yii\helpers\Url;
use myerm\common\components\Func;
?>
<?php $this->beginBlock('style') ?>
    <link rel="stylesheet" href="/css/home.css?<?= \Yii::$app->request->sRandomKey ?>">
    <style>
        body{
            background-color: #fff;
        }
    </style>
<?php $this->endBlock() ?>
<img src="/images/home/invite.jpg">

    <style>
        .commodity_price p {
            color: #ABABAB;
        }

        .commodity_price > p:first-child {
            color: #333;
        }

        [data-dpr="1"] .commodity_price p {
            font-size: 14px;
        }

        [data-dpr="2"] .commodity_price p {
            font-size: 26px;
        }

        [data-dpr="3"] .commodity_price p {
            font-size: 37px;
        }
    </style>