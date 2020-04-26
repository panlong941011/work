<link rel="stylesheet" href="/css/address.css?1">
<link rel="stylesheet" href="/css/ydui.css">
<style>
    .m-cityselect {
        top: 0;
        height: 92%;
    }
    footer {
        position: fixed;
        left: 0;
        bottom: 0;
        width: 100%;
        z-index: 100;
    }
    .buy_now {
        width: 100%;
        height: 2rem;
        line-height: 2rem;
        text-align: center;
        color: #fff;
        background: #f42323;
        font-size: 0.8rem;
    }
</style>
<img src="/images/home/groupbanner.jpg">
<footer>
    <div class="fix_btn flex">
        <a id="buy_now" style="width: 100%;" class="buy_now " href="<?= \yii\helpers\Url::toRoute([\Yii::$app->request->shopUrl . "/home/groupapply"], true) ?>">
            团长申请
        </a>
    </div>
</footer>