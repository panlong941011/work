<link rel="stylesheet" href="/css/pay.css?<?=\Yii::$app->request->sRandomKey?>">
<div class="paywrap">
    <header>支付成功</header>
    <div class="pay_line"></div>
    <section>
        <div class="pay_pic"></div>
        <p class="pay_tip">支付成功</p>
        <div class="pay_btn flex">
            <a href="<?=Yii::$app->request->mallHomeUrl?>" class="pay_back">回到首页</a>
            <a href="<?=\yii\helpers\Url::toRoute([Yii::$app->request->shopUrl."/member/orderlist"], true)?>" class="look_order">查看订单</a>
        </div>
    </section>
</div>