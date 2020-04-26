<?php $this->beginBlock('style') ?>
<link rel="stylesheet" href="/css/logistics.css?<?=\Yii::$app->request->sRandomKey?>">
<?php $this->endBlock() ?>
<div class="logistics">
    <div class="ad_header">
        <a href="javascript:;" onclick="goBack()" class="ad_back">
            <span class="icon">&#xe885;</span>
        </a>
        <h2>物流信息</h2>
        <span class="ad_more icon">&#xe602;</span>
    </div>
    <? if (!$status) { ?>
        <div class="logistics_empty" style="">
            <div class="logistics_pic">
                <img src="../images/order/logistics_empty.png">
            </div>
            <p><?= $message ?></p>
        </div>
    <? } elseif (!$arrTrace) { ?>
        <!-- 无物流的情况 -->
        <div class="logistics_empty" style="">
            <div class="logistics_pic">
                <img src="../images/order/logistics_empty.png">
            </div>
            <p>抱歉，暂时没有查到物流具体信息</p>
        </div>
    <? } else { ?>

        <? if (count($arrTrace) > 1) { ?>
            <nav class="flex">
                <? foreach ($arrTrace as $i => $trace) { ?>
                    <div class="logistics_item <? if (!$_GET['index'] && $i == 0 || $_GET['index'] == $trace['sShipNo']) { ?>on<? } ?>"
                         index="<?= $i ?>">快递<?= $i + 1 ?></div>
                <? } ?>
            </nav>
        <? } ?>
        <? foreach ($arrTrace as $i => $trace) { ?>
            <div class="logistics_content" index="<?= $i ?>"
                 <? if (!$_GET['index'] && $i > 0 || $_GET['index'] && $_GET['index'] != $trace['sShipNo']) { ?>style="display:none;"<? } ?>>
                <? if ($trace['ShipID'] == 'self') { ?>
                    <!-- 自配的情况 -->
                    <div class="matching">
                        <div class="logistics_pic">
                            <img src="/images/order/logistics_empty.png">
                        </div>
                        <p>本单由卖家自配，</p>
                        <p>详细送货情况，可咨询客服</p>
                        <a href="tel:<?= \myerm\shop\common\models\MallConfig::getValueByKey('sServiceNum') ?>"
                           class="service_consult">咨询客服</a>
                    </div>
                <? } else { ?>
                    <? $arrTraceInfo = $trace['arrTraceInfo'] ?>


                    <div class="logistics_info">
                        <h3>
                            物流状态：
                            <em><?= $trace['sStatus'] ?></em>
                        </h3>
                        <p>承运公司：<span><?= $trace['sCompany'] ?></span></p>
                        <p>运单编号：<span><?= $trace['sShipNo'] ?></span></p>
                    </div>


                    <? if (!$arrTraceInfo) { ?>
                        <div class="logistics_empty" style="">
                            <div class="logistics_pic">
                                <img src="../images/order/logistics_empty.png">
                            </div>
                            <p>抱歉，暂时没有查到物流具体信息</p>
                        </div>
                    <? } else { ?>
                        <div class="logistics_list flex">
                            <div class="navigation flex">
                                <? foreach ($arrTraceInfo as $i => $info) { ?>
                                    <i class="dot <? if ($i == 0) { ?>success<? } ?>"></i>
                                    <? if ($i + 1 < count($arrTraceInfo)) { ?>
                                        <span class="line"></span>
                                    <? } ?>
                                <? } ?>
                            </div>
                            <div class="process_list">
                                <? foreach ($arrTraceInfo as $i => $info) { ?>
                                    <div class="process_item <? if ($i == 0) { ?>active<? } ?>">
                                        <h3 class="process_content"><?= $info['context'] ?></h3>
                                        <p class="process_time"><?= $info['time'] ?></p>
                                    </div>
                                <? } ?>
                            </div>
                        </div>
                    <? } ?>
                <? } ?>
            </div>
        <? } ?>
    <? } ?>
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
        <li class="flex" onclick="location.href='<?= \yii\helpers\Url::toRoute(["/member"],
            true) ?>'">
            <span class="icon">&#xe64a;</span>
            <em>我的</em>
        </li>
    </ul>
</div>
<?php $this->beginBlock('foot') ?>
<script>
    $(function () {
        /*$('.logis_type span').on('click',function() {
         var index = $(this).index();
         $(this).addClass('on').siblings().removeClass('on');
         var length = $('.logis_wrap').length;
         for(var i = 0;i < length;i++) {
         $('.logis_wrap').eq(i).hide();
         $('.logis_wrap').eq(index).show();
         showLine();
         }
         })*/

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

        $('.logistics_item').on('click', function () {
            $('.logistics_item').removeClass('on');
            $(this).addClass('on');

            $(".logistics_content").hide();
            $(".logistics_content[index='" + $(this).attr('index') + "']").show();
        })
        showLine();
        //根据物理内容计算侧栏的长度
        function showLine() {
            var len = $('.process_item').length;
            for (var i = 0; i < len; i++) {
                var height = $('.process_item').eq(i).height();
                console.log(height);
                $('.line').eq(i).height(height - 20);

                if (i === (len - 1)) {
                    $('.line').eq(i).height(height / 2);
                }
            }
        }
    })
</script>
<?php $this->endBlock() ?>
