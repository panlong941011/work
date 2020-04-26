<?php $this->beginBlock('style') ?>
<link rel="stylesheet" href="/css/refundApply.css">
<style>
    .div1{
        color: #362F30;
        text-align: center;
        font-size: 0.7rem;
        font-weight: 500;
        margin-top: 1rem;
    }
    .div2{
        text-align: center;
        color: #A3A3A3;
        font-size: 0.7rem;
        font-weight: 500;
    }
    .div3{
        margin-top: 1rem;
    }
    .div5{
        text-align: center;
        color: #362F30;
        font-size: 0.55rem;
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
        <h2>提交成功</h2>
    </div>
    <img src="/images/home/success.png" style="width: 20%;margin-left: 40%;margin-top: 1.5rem;">
    <div class="div1">您的资料提交成功</div>
    <div class="div2 div3">目前进入审核阶段</div>
    <div class="div2">我们将于1-3个工作日內与您联系</div>
    <img src="/images/home/kf.jpg" style="width:40%;margin-left: 30%;margin-top: 50px;">
    <div class="div5">扫一扫，了解更多</div>
    <div class="div5">招商微信号：lianxiaobao000</div>
    <button class="submit" id="home" data-finished="finished" onclick="home()">去商城首页</button>
</div>
<?php $this->beginBlock('foot') ?>
<script>
     function home () {
        location.href='/shop0/home'
    }
</script>
<?php $this->endBlock() ?>