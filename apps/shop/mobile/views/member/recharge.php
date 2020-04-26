<?php $this->beginBlock('style') ?>
<link rel="stylesheet" href="/css/supplierMain.css?<?= \Yii::$app->request->sRandomKey ?>">
<?php $this->endBlock() ?>
<div class="recharge">
    <div class="ad_header">
        <a href="javascript:goBack();" class="ad_back">
            <span class="icon">&#xe885;</span>
        </a>
        <h2>金币充值</h2>
    </div>
    <div class="recharge_main">
        <div class="recharge_main_title flex">
            <i class="icon">&#xe627;</i>
            <span>我的金币</span>
        </div>
        <em class="gold"><?= number_format(\Yii::$app->frontsession->member->fGold, 2) ?></em>
    </div>
    <div class="recharge_amount flex">
        <span>充值金额</span>
        <input type="number" id="number" name="number" class="recharge_input" placeholder="请填写数值">
    </div>
    <p class="attention">金币可用于本商城购物，1金币可以当做1元使用</p>
    <ul class="attention_list">
        <? foreach ($arrConfig as $config) { ?>
            <li>满 <em><?= $config->fFull ?></em> 元，额外送 <em><?= $config->fGive ?></em> 金币</li>
        <? } ?>
    </ul>
    <a href="javascript:;" class="recharge_btn">确认充值</a>
</div>
<div class="weui-loading_toast" style="display: none;">
    <div class="weui-mask_transparent"></div>
    <div class="weui-toast">
        <i class="weui-loading weui-icon_toast"></i>
    </div>
</div>
<?php $this->beginBlock('foot') ?>
<script>
    $(".recharge_btn").click
    (
        function () {
            if (!/^\d+(\.\d+)?$/.test($("#number").val())) {
                shoperm.showTip("请输入正确的充值金额");
                return;
            }

            var number = parseFloat($("#number").val());
            if (number < 1) {
                shoperm.showTip("充值金额不能小于1元");
                return false;
            } else if (number > 9999) {
                shoperm.showTip("充值金额不能大于9999元");
                return false;
            }


            $(".weui-loading_toast").show();

            $.post(
                '/member/recharge',
                {
                    number: number.toFixed(2),
                    _csrf: '<?=\Yii::$app->request->getCsrfToken()?>'
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
    )

    function pay(config) {
        wx.chooseWXPay({
            timestamp: config.timestamp,
            nonceStr: config.nonceStr,
            package: config.package,
            signType: config.signType,
            paySign: config.paySign, // 支付签名
            success: function (res) {
                if (res.errMsg == "chooseWXPay:ok") {
                    location.href = "/member";
                } else {
                    alert(res.errMsg);
                }
            },
            cancel: function (res) {

            }
        });
    }
</script>
<? if (\Yii::$app->params['isWeChat'] && Yii::$app->request->userIP != '127.0.0.1') { ?>
    <script src="https://res.wx.qq.com/open/js/jweixin-1.0.0.js" type="text/javascript" charset="utf-8"></script>
    <script type="text/javascript" charset="utf-8">
        wx.config(<?php echo \Yii::$app->wechat->js->config(['chooseWXPay']) ?>);
    </script>
<? } ?>
<?php $this->endBlock() ?>
