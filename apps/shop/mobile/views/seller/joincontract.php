<?php $this->beginBlock('style') ?>
<link rel="stylesheet" href="/css/supplierMain.css?<?= \Yii::$app->request->sRandomKey ?>">
<?php $this->endBlock() ?>
<div class="be_supplier">
    <div class="ad_header">
        <a href="javascript:;" class="ad_back" onclick="goBack()">
            <span class="icon">&#xe885;</span>
        </a>
        <h2>经销商协议</h2>
        <span class="ad_more icon">&#xe602;</span>
    </div>
    <div class="sup_content">
        <?= \myerm\shop\common\models\SellerConfig::findValueByKey('sSellerJoinContract') ?>
    </div>
</div>
<div class="nav_more_list">
    <div class="triangle-up"></div>
    <ul>
        <li class="flex" onclick="location.href='<?= \Yii::$app->request->mallHomeUrl ?>'">
            <span class="icon">&#xe608;</span>
            <em>首页</em>
        </li>
        <li class="flex"
            onclick="location.href='<?= \yii\helpers\Url::toRoute(["/cart"], true) ?>'">
            <span class="icon">&#xe60a;</span>
            <em>购物车</em>
        </li>
        <li class="flex"
            onclick="location.href='<?= \yii\helpers\Url::toRoute(["/member"],
                true) ?>'">
            <span class="icon">&#xe64a;</span>
            <em>我的</em>
        </li>
    </ul>
</div>
<?php $this->beginBlock('foot') ?>

<script>
    $(function() {

        $('.ad_more').on('click', function () {
            event.stopPropagation();
            $(".nav_more_list").toggle();
        })
        $(window).on('click', function () {
            $(".nav_more_list").hide();
        })

        $('.apply').on('click',function() {

        })
    })
</script>
<?php $this->endBlock() ?>

