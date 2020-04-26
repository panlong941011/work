<?php $this->beginBlock('style') ?>
<link rel="stylesheet" href="/css/car.css?<?=\Yii::$app->request->sRandomKey?>">
<link rel="stylesheet" href="/css/supplier.css?<?=\Yii::$app->request->sRandomKey?>">
<?php $this->endBlock() ?>
    <div class="car_header">
        <a href="javascript:;" onclick="goBack()" class="car_back">
            <span class="icon">&#xe885;</span>
        </a>
        <h2>供应商详情</h2>
        <span class="ad_more icon">&#xe602;</span>
    </div>
    <div class="banner-wrap"
         style="background:url(<? if ($supplier->sPicPath) { ?><?= \Yii::$app->request->imgUrl ?>/<?= $supplier->sPicPath ?><? } else { ?>/images/car/s_header.png<? } ?>) no-repeat center center">
        <div class="b-mask">
            <h2 class="s-title">
                <span class="s-icon">
                    <i class="s-icon-inner"
                       style="background:url(/images/car/shop.png) no-repeat center center;background-size: 60%;"></i>
                </span>
                <span class="s-h"><?= $supplier->sName ?></span>
            </h2>
            </span>
        </div>
    </div>

<div class="car_selling s-list">
    <h3 class="layer-title">
        <span class="layer-name">供应商名称</span>
        <span class="layer-more" onclick="location.href='<?= \yii\helpers\Url::toRoute([
            \Yii::$app->request->shopUrl . "/supplier/list",
            'id' => $supplier->lID
        ], true) ?>'">查看全部</span>
    </h3>
    <div class="selling_list">
        <ul>
            <? foreach ($supplier->arrDetailProduct as $product) { ?>
            <li class="sellings">
                <a href="<?= \yii\helpers\Url::toRoute([
                    \Yii::$app->request->shopUrl . "/product/detail",
                    'id' => $product->lID
                ], true) ?>">
                    <div class="sell_pic
                        <? if ($product->secKill->sStatus == '进行中' || $product->secKill->sStatus == '已结束'|| $product->secKill->sStatus == '已抢光') { ?>
                            <? if ($product->secKill->lStock == 0) { ?>
                            s_loot_all
                            <? } else { ?>
                            s_over
                            <? } ?>
                        <? } ?>
                        <? if ($product->bSaleOut) { ?>sellout<? } ?>">
                        <img src="<?= \Yii::$app->request->imgUrl ?>/<?= $product->sMasterPic ?>" alt="">

                        <? if ($product->secKill) { ?>
                            <div class="seckill"></div>
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
                        <h3 class="multiEllipsis"><?= $product->sName ?></h3>
                        <span class="sell_price">¥<?= $product->fShowSalePrice ?></span>
                    </div>
                </a>
            </li>
            <? } ?>
        </ul>
    </div>
</div>
<div class="s-detail">
    <h3 class="layer-title">
        <span class="layer-name">店铺简介</span>
    </h3>
    <div class="layer-con"><?= $supplier->sContent ?></div>
</div>

<div class="nav_more_list">
    <div class="triangle-up"></div>
    <ul>
        <li class="flex" onclick="location.href='<?= \Yii::$app->request->mallHomeUrl ?>'">
            <span class="icon">&#xe608;</span>
            <em>首页</em>
        </li>
        <li class="flex" onclick="location.href='<?= \yii\helpers\Url::toRoute(["/cart"], true) ?>'">
            <span class="icon">&#xe60a;</span>
            <em>购物车</em>
        </li>
        <li class="flex" onclick="location.href='<?= \yii\helpers\Url::toRoute(["/member"], true) ?>'">
            <span class="icon">&#xe64a;</span>
            <em>我的</em>
        </li>
    </ul>
</div>

<?php $this->beginBlock('foot') ?>
    <script>
      
        $(function () {
            $('.ad_more').on('click', function () {
                event.stopPropagation();
                $(".nav_more_list").toggle();
            })

            $(window).on('scroll', function () {
                $(".nav_more_list").hide();
            })

            $('body').on('click', function () {
                $(".nav_more_list").hide();
            }) 
             var time = null;
             var nowTime = (new Date(dNow).getTime())/1000;
            //倒计时
            countDown(nowTime);

        })

        function countDown(nowTime) {
            for (var i = 0; i < $('.buy_time').length; i++) {
                var leftTime = $('.buy_time').eq(i).data('time').replace(/\-/g, "/"),
                    endTime = new Date(leftTime),
                    disTime = endTime.getTime() - nowTime*1000,
                    day = Math.floor(disTime / (1000 * 60 * 60 * 24)),
                    hour = Math.floor(disTime / (1000 * 60 * 60) % 24),
                    minute = Math.floor(disTime / (1000 * 60) % 60),
                    second = Math.floor(disTime / 1000 % 60);

                if (disTime < 0) {
                    $('.buy_time').remove();
                    continue;
                }
                day = checkTime(day);
                hour = checkTime(hour);
                minute = checkTime(minute);
                second = checkTime(second);
                $('.buy_time').eq(i).html('距开抢:' + day + '天' + hour + '时' + minute + '分' + second + '秒');
            }

            time = setTimeout(function () {
                nowTime = nowTime + 1;
                countDown(nowTime);
            }, 1000);

            var b_length = $('.buy_time').length;
            if( b_length == 0 ) {
                clearTimeout(time);
            }
        }
        //数字格式优化
        function checkTime(i) {
            if (i < 10) {
                i = '0' + i;
            }
            return i;
        }
    </script>
<?php $this->endBlock() ?>
