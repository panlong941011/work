<?php $this->beginBlock('style') ?>
<link rel="stylesheet" href="/css/whereaboutsAndconsult.css?<?=\Yii::$app->request->sRandomKey?>">
<?php $this->endBlock() ?>
<div class="whereabouts">
    <div class="ad_header">
        <a href="javascript:goBack();" class="ad_back">
            <span class="icon">&#xe885;</span>
        </a>
        <h2>退款去向</h2>
        <span class="ad_more icon">&#xe602;</span>
    </div>
    <div class="whereabouts_main">
        <div class="where_top">
            <h2>退款原路返还</h2>
            <p>根据支付公司处理情况情况，最迟3个工作日到账</p>
        </div>
        <div class="refund_price">退款金额： ¥<?=number_format($refundmoney->fRefund, 2)?></div>
        <div class="refund_process">
            <div class="process_item flex">
                <div class="navigation flex">
                    <i class="dot"></i>
                    <span class="line"></span>

                    <i class="dot"></i>
                    <span class="line"></span>

                    <i class="dot <? if ($refundmoney->dSuccessDate) { ?>success<? } ?>"></i>
                </div>
                <div class="process_list">
                    <div class="process_item">
                        <h3 class="process_content">卖家退款</h3>
                        <p class="process_time"><?=$refundmoney->dNewDate?></p>
                    </div>
                    <div class="process_item">
                        <h3 class="process_content">受理时间</h3>
                        <p class="process_time"><?=$refundmoney->dProcessingDate?></p>
                    </div>
                    <div class="process_item">
                        <h3 class="process_content">到账时间</h3>
                        <p class="process_time"><?=$refundmoney->dSuccessDate?></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="nav_more_list">
    <div class="triangle-up"></div>
    <ul>
        <li class="flex">
            <span class="icon">&#xe608;</span>
            <em>首页</em>
        </li>
        <li class="flex">
            <span class="icon">&#xe60a;</span>
            <em>购物车</em>
        </li>
        <li class="flex">
            <span class="icon">&#xe64a;</span>
            <em>我的</em>
        </li>
    </ul>
</div>
