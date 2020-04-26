<?php $this->beginBlock('style') ?>
<link rel="stylesheet" href="/css/settlement.css?<?= \Yii::$app->request->sRandomKey ?>">
<style>
    body {
        background: #fff;
    }
</style>

<?php $this->endBlock() ?>
<div class="explain">
    <div class="ad_header">
        <a href="javascript:;" class="ad_back" onclick="goBack()">
            <span class="icon">&#xe885;</span>
        </a>
        <h2>客户说明</h2>
    </div>
    <div class="detail_content">
        <h2>一、我的客户展示的是什么？</h2>
        <div class="detail_main">
            <div>我的客户页面展示了所有你邀请注册的人。</div>
        </div>
        <h2>二、客户邀请说明</h2>
        <div class="detail_main">
            <h3>如何邀请客户？</h3>
            <div>只要通过您店铺的任何链接注册的用户就是你的客户啦！</div>
        </div>
        <div class="detail_main">
            <h3>客户有什么作用？</h3>
            <div>邀请到越多的客户注册，您就可以获得更多的销售机会！
                同时，你也有机会将普通客户发展为你的下级经销商。</div>
        </div>
    </div>
</div>
