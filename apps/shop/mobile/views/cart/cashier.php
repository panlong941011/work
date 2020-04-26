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
        <div class="order">订单编号：<?= $order->sName ?></div>
        <div class="order">下单时间：<?= $order->dNewDate ?></div>
        <div class="order_amount flex">
            <span>需支付</span>
            <em class="amount">¥<?= number_format($fTotalFee, 2) ?></em>
        </div>
    </div>
    <div class="pay">
        <a href="javascript:;" class="wxPay flex" onclick="wxPay()">
            <div class="wx_pic">
                <img src="/images/supplier/wxpay.png">
            </div>
            <div class="pay_info">
                <h3>微信支付</h3>
                <p>微信安全支付</p>
            </div>
        </a>
    </div>
</div>


<div class="weui-loading_toast" style="display: none;">
    <div class="weui-mask_transparent"></div>
    <div class="weui-toast">
        <i class="weui-loading weui-icon_toast"></i>
    </div>
</div>
<?php $this->beginBlock('foot') ?>
<script>

    $(function () {
        //显示弹框
        $('.alert_close').on('click', function () {
            $('.message_wrap').hide();
        })
    })


    function wxPay() {
        $(".weui-loading_toast").show();
        $.post(
            '/cart/cashier?no=<?=$_GET['no']?>',
            {
                _csrf: '<?=\Yii::$app->request->getCsrfToken()?>',
                type: 'wx'
            },
            function (data) {
                $(".weui-loading_toast").hide();
                if (data.status) {
                    pay(data.config);
                } else {
                    shoperm.showTip(data.message);
                }
            }
        )
    }

    function goldPay() {
        $(".weui-loading_toast").show();
        $.post(
            '/cart/cashier?no=<?=$_GET['no']?>',
            {
                _csrf: '<?=\Yii::$app->request->getCsrfToken()?>',
                type: 'gold'
            },
            function (data) {
                $(".weui-loading_toast").hide();
                if (data.status) {
                    location.href = "/pay/success";
                } else {
                    shoperm.showTip(data.message);
                }
            }
        )
    }

    function pay(config) {
        wx.chooseWXPay({
            timestamp: config.timestamp,
            nonceStr: config.nonceStr,
            package: config.package,
            signType: config.signType,
            paySign: config.paySign, // 支付签名
            success: function (res) {
                if (res.errMsg == "chooseWXPay:ok") {
                    location.href = "shop<?=\Yii::$app->frontsession->urlSeller?>/pay/success";
                } else {
                    alert(res.errMsg);
                }
            },
            cancel: function (res) {

            }
        });
    }
</script>
<?php $this->endBlock() ?>
