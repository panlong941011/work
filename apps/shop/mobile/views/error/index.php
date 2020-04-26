<?php $this->beginBlock('style') ?>
<link rel="stylesheet" href="/css/policyAndAbnormal.css?<?=\Yii::$app->request->sRandomKey?>">
<?php $this->endBlock() ?>
<div class="network_anomaly">
    <div class="ad_header wrong">
        <a href="javascript:;" onclick="goBack()" class="ad_back">
            <span class="icon">&#xe885;</span>
        </a>
        <h2>页面出错</h2>
    </div>
    <div class="pic_wrap">
        <img src="/images/order/404.png">
    </div>
    <p class="wrong_tip">咦？页面出错了...</p>
</div>
