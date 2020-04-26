<?php $this->beginBlock('style') ?>
<link rel="stylesheet" href="/css/supplierMain.css?<?= \Yii::$app->request->sRandomKey ?>">
<style>
    .order{
        font-size: 30px;
        height: 50px;
        line-height: 50px;
    }
</style>
<?php $this->endBlock() ?>
<div class="cashier">
    <div class="ad_header">
        <a href="javascript:;" class="ad_back" onclick="goBack()">
            <span class="icon">&#xe885;</span>
        </a>
        <h2>收银台</h2>
    </div>
    <div class="cashier_top">
        <div class="order">订单编号：<?= $sTradeNo ?></div>
        <div class="order">下单时间：<?= $dNewDate ?></div>
        <div class="order_amount flex">
            <span>需支付</span>
            <em class="amount">¥<?= number_format($fTotalFee, 2) ?></em>
        </div>
    </div>
    <div class="pay">
        <a href="javascript:;" class="wxPay flex" onclick="pay()">
            <div class="wx_pic">
                <img src="/images/supplier/wxpay.png">
            </div>
            <div class="pay_info">
                <h3>微信支付</h3>
                <p>微信安全支付</p>
            </div>
        </a>
    </div>
    <div id="res"></div>
</div>


<?php $this->beginBlock('foot') ?>
<script>
    var ID='<?=$sTradeNo?>';
    function pay() {
        if (typeof WeixinJSBridge !== "undefined") {
            $.ajax({
                url: "/cart/pay",
                type: "GET",
                data: {ID:ID},
                success: function (data) {
                    var data = JSON.parse(data);
                    var wxdata = data.JsPayConfig;
                    WeixinJSBridge.invoke('getBrandWCPayRequest', {
                        "appId": wxdata.appId, //公众号名称，由商户传入
                        "timeStamp": wxdata.timestamp+'', //时间戳，自1970年以来的秒数
                        "nonceStr": wxdata.noncestr, //随机串
                        "package": wxdata.package,
                        "signType": wxdata.signtype, //微信签名方式：
                        "paySign": wxdata.paysign //微信签名
                    }, function (data) {
                        location.href='/member/orderlist?type=paid';
                    });
                }
            })
        }
        else {
            alert('请在微信中支付')
        }
    }
</script>
<?php $this->endBlock() ?>
