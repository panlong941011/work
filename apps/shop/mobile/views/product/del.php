<link rel="stylesheet" href="/css/car.css?<?=\Yii::$app->request->sRandomKey?>">
<div class="car_header">
    <a href="javascript:;" onclick="goBack()" class="car_back">
        <span class="icon">&#xe625;</span>
    </a>
    <h2>商品已删除</h2>

</div>
<div class="no_commodity">
    <div class="no_c_pic">
        <img src="/images/car/delete.png" alt="">
    </div>
    <p>啊哦，该宝贝已经被删除了</p>
    <a href="<?= \yii\helpers\Url::toRoute([\Yii::$app->request->shopUrl . "/home"], true) ?>" class="to_index">逛逛去</a>
</div>
<?= \myerm\shop\mobile\widgets\HotSaleWidget::widget() ?>
