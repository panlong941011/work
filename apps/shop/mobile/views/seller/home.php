<?php $this->beginBlock('style') ?>
<link rel="stylesheet" href="/css/supplierMain.css?<?= \Yii::$app->request->sRandomKey ?>">
<?php $this->endBlock() ?>
<div class="distribution">
    <div class="dis_shop flex">
        <div class="shop_img">
            <img src="<?= $member->sAvatarPath ?>">
        </div>
        <div class="shop_title">
            <h2>来瓜分团长</h2>
            <p><?= $seller->shop->sShopName ?></p>
        </div>

    </div>
    <div class="withdrawals">
        <div class="amount_tip flex" onclick="location.href='/seller/flow'">
            <i class="icon">&#xe61b;</i>
            <p>可提现金额</p>
        </div>
        <div class="amount" onclick="location.href='/seller/flow'">
            <span>¥</span>
            <em><?= number_format($seller->computeWithdraw, 2) ?></em>
        </div>
        <div class="to_withdrawals">提现</div>
    </div>
    <div class="income">
        <div class="my_income flex">
            <span>我的收入</span>
            <i class="icon" onclick="location.href='/seller/incomedesc'">&#xe670;</i>
        </div>
        <ul class="income_item flex">
            <li>
                <a>
                    <em><?= number_format($seller->fUnsettlement, 2) ?></em>
                    <span>待结算</span>
                </a>
            </li>
            <li>
                <a>
                    <em><?= number_format($seller->fWithdrawed, 2) ?></em>
                    <span>提现中</span>
                </a>
            </li>
            <li>
                <a>
                    <em><?= number_format($seller->fWithdraw, 2) ?></em>
                    <span>已提现</span>
                </a>
            </li>
            <li>
                <a>
                    <em><?= number_format($seller->fUnsettlement + $seller->fSettlement+$seller->fWithdraw, 2) ?></em>
                    <span>累计收入</span>
                </a>
            </li>
        </ul>
    </div>

</div>

<footer>
    <div class="bottom_fixed flex">
        <a href="<?= Yii::$app->request->mallHomeUrl ?>">
            <span class="icon">&#xe608;</span>
            <p>首页</p>
        </a>
        <a href="<?= \yii\helpers\Url::toRoute([\Yii::$app->request->shopUrl . "/product/category"], true) ?>">
            <span class="icon">&#xe6b0;</span>
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
            <a href="/seller/bindbank" class="alert_btn">去绑定</a>
        </div>

    </div>
</div>
<?php $this->beginBlock('foot') ?>
<script src="/js/qrcode.min.js"></script>
<script src="/js/html2canvas.js"></script>
<script src="/js/canvas2image.js"></script>
<script>
    $(function () {
        $('.to_withdrawals').on('click', function () {
            location.href = "/seller/withdrawreq";
        })
    })
</script>
<?php $this->endBlock() ?>
