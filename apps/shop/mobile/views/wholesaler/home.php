<?
use \myerm\shop\common\models\MallConfig;
?>
<?php $this->beginBlock('style') ?>
<link rel="stylesheet" href="/css/supplierMain.css?<?= \Yii::$app->request->sRandomKey ?>">
<?php $this->endBlock() ?>
<div class="distribution">
    <div class="dis_shop flex">
        <div class="shop_img">
            <!--TODO 图片需要替换 -->
            <img src="<?= \Yii::$app->request->imgUrl . "/" . MallConfig::getValueByKey("sMallLogo") ?>">
        </div>
        <div class="shop_title">
            <h2>渠道商</h2>
        </div>
    </div>
    <div class="withdrawals">
        <div class="amount_tip flex" onclick="location.href='/wholesaler/flow'">
            <i class="icon">&#xe61b;</i>
            <p>可提现金额</p>
        </div>
        <div class="amount" onclick="location.href='/wholesaler/flow'">
            <span>¥</span>
            <em><?= number_format($wholesaler->fWithdraw, 2) ?></em>
        </div>
        <div class="to_withdrawals">提现</div>
    </div>
    <div class="frozen_amount flex" onclick="location.href='/wholesaler/frozen'">
        <i class="icon">&#xe60f;</i>
        <p>冻结金额：<?= number_format($wholesaler->fFrozen, 2) ?></p>
    </div>
    <div class="income">
        <div class="my_income flex">
            <span>我的收入</span>
            <i class="icon" onclick="location.href='/wholesaler/incomedesc'">&#xe670;</i>
        </div>
        <ul class="income_item flex">
            <li>
                <a href="/wholesaler/unsettlement">
                    <em><?= number_format($wholesaler->fUnsettlement, 2) ?></em>
                    <span>待结算</span>
                </a>
            </li>
            <li>
                <a href="/wholesaler/settlement">
                    <em><?= number_format($wholesaler->fSettlement, 2) ?></em>
                    <span>已结算</span>
                </a>
            </li>
            <li>
                <a href="/wholesaler/withdraw?type=已提现">
                    <em><?= number_format($wholesaler->fWithdrawed, 2) ?></em>
                    <span>已提现</span>
                </a>
            </li>
            <li>
                <a href="/wholesaler/flow">
                    <em><?= number_format($wholesaler->fSumIncome, 2) ?></em>
                    <span>累计收入</span>
                </a>
            </li>
        </ul>
    </div>
    <div class="person_item">
        <ul class="flex">
            <li>
                <a href="/wholesaler/orderlist">
                    <div class="order"></div>
                    <p>渠道订单</p>
                </a>
            </li>
            <li>
                <a href="/wholesaler/mycustomer">
                    <div class="customer"></div>
                    <p>经销商管理</p>
                </a>
            </li>
            <li>
                <a href="/product/list?bWholesale=1">
                    <div class="order"></div>
                    <p>挑选渠道商品</p>
                </a>
            </li>
            <li>
                <a href="/wholesaler/product">
                    <div class="order"></div>
                    <p>渠道商品</p>
                </a>
            </li>
        </ul>
    </div>
    <? if (MallConfig::getValueByKey('sWithdrawMethod') == 'bankcard') { ?>
        <div class="my_card">
            <a class="flex" href="/wholesaler/bindbank">
                <p>我的银行卡</p>
                <? if (!$wholesaler->sBankAccount) { ?>
                    <span>未绑定</span>
                <? } else { ?>
                    <span>已绑定</span>
                <? } ?>
            </a>
        </div>
    <? } ?>
</div>
<!-- 二维码图片 -->
<div class="shopBg_canvas"></div>
<!-- 遮罩 -->
<div class="shopMask"></div>
<footer>
    <div class="bottom_fixed flex">
        <a href="<?= Yii::$app->request->mallHomeUrl ?>">
            <span class="icon">&#xe608;</span>
            <p>首页</p>
        </a>
        <a href="<?= \yii\helpers\Url::toRoute([\Yii::$app->request->shopUrl . "/product/category"], true) ?>">
            <span class="icon">&#xe6b0;</span>
            <p>分类</p>
        </a>
        <a href="/wholesaler" class="on">
            <span class="icon center">&#xe655;</span>
            <p>渠道中心</p>
        </a>
        <a href="/cart">
            <span class="icon">&#xe60a;</span>
            <p>购物车</p>
        </a>
        <a href="/member">
            <span class="icon">&#xe64a;</span>
            <p>我的</p>
        </a>
    </div>
</footer>

<!-- 弹框提示 -->
<div class="message_wrap">
    <div class="message_alert">
        <h2 class="alert_title">请先绑定银行卡</h2>
        <div class="btn_wrap flex">
            <div class="alert_close">取消</div>
            <a href="/wholesaler/bindbank" class="alert_btn">去绑定</a>
        </div>

    </div>
</div>
<?php $this->beginBlock('foot') ?>
<script src="/js/qrcode.min.js"></script>
<script src="/js/html2canvas.js"></script>
<script src="/js/canvas2image.js"></script>
<script>
    $(function () {
        $('.shopMask').on('click', function (event) {
            $('.shopBg_canvas').find('img').remove();
            $('.shopBg_wrap').hide();
            $(this).hide();
        })
        //阻止冒泡
        $('.shopbg').on('click', function (event) {
            event.stopPropagation();
        })

        $('.to_withdrawals').on('click', function () {
            <? if (!$wholesaler->sBankAccount && MallConfig::getValueByKey('sWithdrawMethod') == 'bankcard') { ?>
            $('.message_wrap').show();
            <? } else { ?>
            location.href = "/wholesaler/withdrawreq";
            <? } ?>
        })


        $('.message_alert').on('click', function (event) {
            event.stopPropagation();
        })
        $('.message_wrap,.alert_close').on('click', function () {
            $('.message_wrap').hide();
        })

    })
</script>
<?php $this->endBlock() ?>
