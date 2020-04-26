<?php $this->beginBlock('style') ?>
<link rel="stylesheet" href="/css/settlement.css?<?= \Yii::$app->request->sRandomKey ?>">
<style>
    body {
        background: #fff;
    }
    .explain_link {
        color: #4395ff;
        text-decoration: underline;
    }
</style>

<?php $this->endBlock() ?>
<div class="explain">
    <div class="ad_header">
        <a href="javascript:;" class="ad_back" onclick="goBack()">
            <span class="icon">&#xe885;</span>
        </a>
        <h2>发展团队说明</h2>
    </div>
    <div class="detail_content">
        <h2>发展团队说明</h2>
        <div class="detail_main">
            <h3>什么是团队？</h3>
            <div>您的团队包括您自己、您的下级经销商、下下级经销商</div>
        </div>
        <div class="detail_main">
            <h3>发展团队有什么好处？</h3>
            <div>团队人数越多，就越多人帮你销售，您可获得的收入就越多。</div>
        </div>
        <div class="detail_main">
            <h3>如何发展团队？</h3>
            <div>想赚钱的朋友，您都可以跟他阐明您的销售模式，在经销中心点击邀请好友，将页面分享给他。</div><br>
            <div>只要他在你分享的页面申请成为经销商，你就是他的推荐人，他就是你下级啦。</div><br>
            <a class="explain_link" href="/<?=Yii::$app->request->shopUrl?>/seller/joindesc">查看邀请好友页面</a>
        </div>
    </div>
</div>
