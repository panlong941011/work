<div class="car_selling" v-if="!isEdit">
    <h2>
        <span class="icon">&#xe65a;</span>热销推荐
    </h2>
    <div class="selling_list">
        <ul>
            <? foreach ($arrHotSale as $product) { ?>
            <li class="sellings">
                <a href="<?= \yii\helpers\Url::toRoute([
                    \Yii::$app->request->shopUrl . "/product/detail",
                    'id' => $product->lID
                ], true) ?>">
                    <div class="sell_pic <? if ($product->secKill->sStatus == '已抢光') { ?>loot_all<? } elseif ($product->secKill->sStatus == '已结束') {?>over<? } ?>">

                        <img src="<?=Yii::$app->request->imgUrl?>/<?=$product->sMasterPic?>" alt="">

                        <? if ($product->secKill) { ?>
                            <div class="sell_speckill"></div>
                            <? if ($product->secKill->sStatus == '未开始') { ?>
                                <div class="buy_time" data-time="<?= $product->secKill->dStartDate ?>"></div>
                            <? } elseif ($product->secKill->sStatus == '未抢光') { ?>
                                <div class="buy_stock">仅剩<?= $product->secKill->lStock ?>件</div>
                            <? } ?>
                        <? } ?>

                        <? if (!$product->secKill) { ?>
                            <div class="label" style="background-image: url('<?=$product->icon?>');"></div>
                        <? } ?>

                    </div>
                    <div class="sell_info">
                        <h3 class="multiEllipsis"><?=$product->sName?></h3>
                        <span class="sell_price">¥<?=number_format($product->fShowSalePrice, 2)?></span>
                    </div>
                </a>
            </li>
            <? } ?>
        </ul>
    </div>
</div>

<script src="/js/zepto.min.js"></script>
<script>
    $(function() {
        var time = null;
        var nowTime = (new Date(dNow).getTime())/1000;
        countDown(nowTime);
    });
    function countDown(nowTime) {

        for(var i = 0; i< $('.buy_time').length ;i ++){
            var leftTime = $('.buy_time').eq(i).data('time').replace(/\-/g, "/"),
                endTime = new Date(leftTime),
                disTime = endTime.getTime() - nowTime*1000,
                day = Math.floor( disTime/(1000*60*60*24) ),
                hour = Math.floor( disTime/(1000*60*60)%24 ),
                minute = Math.floor( disTime/(1000*60)%60 ),
                second = Math.floor( disTime/1000%60 );

            if( disTime < 0 ) {
                 $('.buy_time').remove();
                  continue;
            }
            day = checkTime(day);
            hour = checkTime(hour);
            minute = checkTime(minute);
            second = checkTime(second);
            $('.buy_time').eq(i).html('距开抢:'+day+ '天'+ hour +'时'+ minute +'分'+ second +'秒');
        }
        time = setTimeout(function() {
                nowTime = nowTime + 1;
                countDown(nowTime);
        },1000);

        var b_length = $('.buy_time').length;
        if( b_length == 0 ) {
            clearTimeout(time);
        }
    }
    function checkTime(i) {
        if( i < 10 ) {
            i = '0' + i;
        }
        return i;
    }
</script>