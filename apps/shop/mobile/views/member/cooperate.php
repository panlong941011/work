<?php $this->beginBlock('style') ?>
<link rel="stylesheet" href="/css/refundApply.css">
<style>
    .div1{
        color: #3394FF;
        text-align: center;
        font-size: 0.9rem;
        font-weight: 500;
        margin-top: 2rem;
    }

    .div5{
        text-align: center;
        color: #362F30;
        font-size: 0.55rem;
        height: 1rem;
    }
    .div2{
        margin-top: 1rem;
    }
    .submit{
        display: block;
        width: 14.72rem;
        height: 1.92rem;
        -webkit-border-radius: .2133333333rem;
        border-radius: .2133333333rem;
        background: #3394FF;
        text-align: center;
        line-height: 1.92rem;
        color: #fff;
        margin: 0 auto;
        margin-bottom: .64rem;
        font-size: 0.6rem;
        margin-top: 2.5rem;
    }
</style>
<?php $this->endBlock() ?>
<div class="refund_apply">
    <div class="ad_header">
        <a href="javascript:;" class="ad_back" onclick="goBack()">
            <span class="icon back">&#xe885;</span>
        </a>
        <h2>达成联盟</h2>
    </div>
    <img src="/images/home/logotxt.png" style="width: 40%;margin-top: 0.5rem;display: block;">
    <img src="/images/home/cooperate.png" style="width:60%;margin-left: 20%;margin-top: 50px;">
    <div class="div1">恭喜合作成功</div>
    <div class="div5 div2">请及时关注达咖选货信息</div>
    <div class="div5">我们将为您提供联盟服务</div>
    <button class="submit" id="home" data-finished="finished" onclick="home()">去商城首页</button>
</div>
<?php $this->beginBlock('foot') ?>
<script>
     function home () {
        location.href='/shop0/home'
    }
</script>
<?php $this->endBlock() ?>