<?php $this->beginBlock('style') ?>
<link rel="stylesheet" href="/css/whereaboutsAndconsult.css?<?=\Yii::$app->request->sRandomKey?>">
<?php $this->endBlock() ?>
<div class="naegotiation_record">
    <div class="ad_header">
        <a href="javascript:goBack();" class="ad_back">
            <span class="icon">&#xe885;</span>
        </a>
        <h2>协商记录</h2>
    </div>
    <? foreach ($arrRefundLog as $log) { ?>
        <section>
            <div class="person flex">
                <div class="portrait">
                    <? if ($log->sWhoDo == '卖家') { ?>
                        <img src="/images/order/seller.png">
                    <? } elseif ($log->sWhoDo == '买家') { ?>
                        <img src="/images/order/buyer.png">
                    <? } elseif ($log->sWhoDo == '系统') { ?>
                        <img src="/images/order/system.jpg">
                    <? } ?>
                </div>
                <div class="person_main">
                    <h3><?= $log->sWhoDo ?></h3>
                    <p><?= $log->dNewDate ?></p>
                </div>
            </div>
            <div class="record"><?= $log->sStatus ?></div>
            <? $arrMessage = json_decode($log->sMessage, true); ?>
            <? foreach ($arrMessage as $sKey => $sMess) { ?>
                <? if ($sKey == '退款凭证' || $sKey == '快递凭证') { ?>
                    <div class="record"><?= $sKey ?>：
                        <? if ($sMess == '--' || !$sMess) { ?>
                            --
                        <? } else { ?>
                            <div class="pic_list">
                                <?
                                foreach ($sMess as $img) { ?>
                                    <img src="<?=Yii::$app->request->imgUrl?>/<?=$img?>">
                                <? } ?>
                            </div>
                        <? } ?>
                    </div>
                <? } else { ?>
                    <div class="record"><?= $sKey ?>：<?= $sMess ?></div>
                <? } ?>


            <? } ?>
        </section>
    <? } ?>
</div>
<?php $this->beginBlock('foot') ?>
 <script src="/js/zoomerang.js"></script>
 <script>
    $(function() {

        //图片放大
         Zoomerang.config({
            maxHeight: 800,
            maxWidth: 800,
            bgColor: '#000',
            bgOpacity: .8
        })
        .listen('.pic_list img')
    })
 </script>
 <?php $this->endBlock() ?>
