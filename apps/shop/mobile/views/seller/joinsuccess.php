<?php $this->beginBlock('style') ?>
<link rel="stylesheet" href="/css/supplierMain.css?<?= \Yii::$app->request->sRandomKey ?>">
<?php $this->endBlock() ?>
<div class="paywrap">
    <header>支付成功</header>
    <div class="pay_line"></div>
    <section>
        <div class="pay_pic"></div>
        <p class="pay_tip">支付成功</p>
        <div class="pay_btn flex">
            <a href="/seller" class="pay_back">确认</a>
        </div>
    </section>
</div>
