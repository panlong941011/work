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
        <h2>收入结算说明</h2>
    </div>
    <div class="detail_content">
        <h2>一、收入结算说明</h2>
        <div class="detail_main">
            <h3>待结算：</h3>
            <div>买家付款后，相应提成将计入“待结算收入"。</div>
        </div>
        <div class="detail_main">
            <h3>可提现：</h3>
            <div>买家的订单状态变为“交易成功”后，该笔订单对应的提成会统计到您的“可提现收入”。</div>
            <div>您当前可以提现的金额。已结算的订单提成及其他收入都将进入到可提现金额。您可以随时申请提现。</div>
        </div>
        <div class="detail_main">
            <h3>提现中金额：</h3>
            <div>正在提现中的收入，将显示在“提现中”中。</div>
        </div>
        <div class="detail_main">
            <h3>累计收入：</h3>
            <div>历史累计收入之和。</div>
        </div>
    </div>
</div>
