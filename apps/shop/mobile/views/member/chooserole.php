<?php $this->beginBlock('style') ?>
<link rel="stylesheet" href="/css/login.css?<?= \Yii::$app->request->sRandomKey ?>">
<style>
    img {
        width: 50%;
        margin-left: 25%;
        margin-top: 5%;
        margin-bottom: 5%;
    }

    #supplier, #provider {
        background-color: #3394FF;
        width: 40%;
        margin-left: 30%;
    }

    .cert {
        text-align: center;
        font-size: 0.6rem;
        padding-bottom: 20px;
    }
</style>
<?php $this->endBlock() ?>
<div class="register">
    <div class="ad_header">
        <a href="javascript:;" class="ad_back" onclick="goBack()">
            <span class="icon back">&#xe885;</span>
        </a>
        <h2>我的认证</h2>
    </div>
    <div class="form_wrap">
        <img src="/images/home/supplier.png">
        <div class="cert">未认证></div>
        <div class="submit_btn">
            <button id="supplier">我是供应商</button>
        </div>
        <img src="/images/home/provider.png">
        <div class="cert">未认证></div>
        <div class="submit_btn">
            <button id="provider">我是渠道商</button>
        </div>
    </div>

</div>


<?php $this->beginBlock('foot') ?>
<script>
    $('#supplier').on('click', function () {
        location.href = '/supplier/supplierreg';
    });
    $('#provider').on('click', function () {
        location.href = '/supplier/providerreg';
    });
</script>
<?php $this->endBlock() ?>
