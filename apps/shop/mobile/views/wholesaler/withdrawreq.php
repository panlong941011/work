<?
use \myerm\shop\common\models\MallConfig;
?>

<?php $this->beginBlock('style') ?>
<link rel="stylesheet" href="/css/settlement.css?<?= \Yii::$app->request->sRandomKey ?>">
<?php $this->endBlock() ?>
<div class="withdraw_cash">
    <div class="ad_header">
        <a href="javascript:;" class="ad_back" onclick="goBack()">
            <span class="icon">&#xe885;</span>
        </a>
        <h2>提现</h2>
    </div>


    <? if (MallConfig::getValueByKey('sWithdrawMethod') == 'bankcard') { ?>
        <div class="withdraw_main">
            <div class="withdraw_bank">
                <span>到账银行卡</span>
                <em><?= $wholesaler->sBank ?>（<?= substr($wholesaler->sBankAccount, -4) ?>）</em>
            </div>
            <div class="withdraw_amount">
                <span>提现金额</span>
                <input type="" name="" placeholder="余额¥<?= number_format($wholesaler->fWithdraw, 2) ?>" class="amount"
                       id="sAmount">
            </div>
        </div>
        <div class="withdraw_tip">
            <p>金额低于 <em><?= MallConfig::getValueByKey('lWithdrawMin') ?></em> 元时不可提现</p>
            <p>预计3日内到账，实际以银行到账时间为准</p>
        </div>
    <? } else { ?>
        <div class="withdraw_main">
            <div class="withdraw_main_tip">提现的钱将进入微信钱包</div>
            <div class="withdraw_bank withdraw_code_wrap flex">
                <span>验证码</span>
                <input type="text" name="" class="withdraw_code_input">
                <div class="withdraw_code">发送验证码</div>
            </div>
            <div class="withdraw_amount">
                <span>提现金额</span>
                <input type="" name="" placeholder="余额¥<?= number_format($wholesaler->fWithdraw, 2) ?>" class="amount"
                       id="sAmount">
            </div>
        </div>
        <div class="withdraw_tip">
            <p>金额低于 <em><?= MallConfig::getValueByKey('lWithdrawMin') ?></em> 元时不可提现</p>
            <p>预计3日内到账，实际以微信到账时间为准</p>
        </div>
    <? } ?>
    <button class="withdraw_btn">提现</button>
</div>
<div class="weui-loading_toast" style="display: none;">
    <div class="weui-mask_transparent"></div>
    <div class="weui-toast">
        <i class="weui-loading weui-icon_toast"></i>
    </div>
</div>
<?php $this->beginBlock('foot') ?>
<? $lWithdrawMin = MallConfig::getValueByKey('lWithdrawMin') ? MallConfig::getValueByKey('lWithdrawMin') :'0.00'?>
<script>
    $(function () {
        var lWithdrawMin = <?= $lWithdrawMin ?>;
        var pattren = /^\d+(\.\d+)?$/;
        $('.withdraw_btn').on('click', function () {
            var price = $.trim($('#sAmount').val());

            if (price == '') {
                shoperm.showTip("请输入提现金额");
                return;
            }

            if (!pattren.test(price)) {
                shoperm.showTip("请输入正确的金额");
                return;
            }

            if (price < lWithdrawMin) {
                shoperm.showTip("金额低于<?= $lWithdrawMin ?>元时不可提现");
                return false;
            }

            if (price > <?=$wholesaler->fWithdraw?>) {
                shoperm.showTip("可提现金额不足");
                return false;
            }

            if ($(".withdraw_code_input").size() && $(".withdraw_code_input").val() == "") {
                shoperm.showTip("请输入验证码");
                return false;
            }

            $(".weui-loading_toast").show();

            $.post(
                '/wholesaler/withdrawreq',
                {price: price, code: $(".withdraw_code_input").val()},
                function (data) {
                    $(".weui-loading_toast").hide();
                    if (data.status) {
                        shoperm.showTip("提现成功");
                        setTimeout("location.href='/wholesaler';", 1000);
                    } else {
                        shoperm.showTip(data.message);
                    }
                }
            );
        })
    })


    $(".withdraw_code").click(
        function () {

            var phone = $.trim($('#phone').val());

            $(".weui-loading_toast").show();

            $.post(
                '/wholesaler/sendwithdrawcode',
                {_csrf: '<?= \Yii::$app->request->getCsrfToken() ?>'},
                function (data) {

                    $(".weui-loading_toast").hide();

                    if (data.status) {
                        countdown();
                        codecountdown = setInterval("countdown()", 1000);
                        shoperm.showTip(data.message);
                    } else {
                        shoperm.showTip(data.message);
                    }
                }
            )
        }
    )

    var lCountDown = 60;
    function countdown() {
        $('.withdraw_code').html(lCountDown + 's');
        lCountDown--;

        if (lCountDown == 0) {
            clearInterval(codecountdown);
            $('.withdraw_code').html('发送验证码');
            lCountDown = 60;
        }

    }
</script>
<?php $this->endBlock() ?>
