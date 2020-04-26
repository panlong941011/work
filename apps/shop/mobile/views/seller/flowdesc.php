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
        <h2>收支明细说明</h2>
    </div>
    <div class="detail_content">
        <h2>收支明细说明</h2>
        <div class="detail_main">
            <h3>订单入账：</h3>
            <div>提成收入。包括销售提成、<?= $sSecondType ?>提成、<?= $sThirdType ?>提成。订单一旦交易成功，收益即进入可提现。</div>
        </div>
        <div class="detail_main">
            <h3>提现：</h3>
            <div>提现申请，可提现账户将扣除该笔申请费用；</div>
        </div>
    </div>
</div>
