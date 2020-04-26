<?php $this->beginBlock('style') ?>
<link rel="stylesheet" href="/css/settlement.css?<?= \Yii::$app->request->sRandomKey ?>">
<?php $this->endBlock() ?>
<div class="flow_details">
    <div class="ad_header">
        <a href="javascript:;" class="ad_back" onclick="goBack()">
            <span class="icon">&#xe885;</span>
        </a>
        <h2>流水详情</h2>
    </div>
    <div class="f_d_info">
        <div class="change_money flex">
            <span>变动金额</span>
            <em>¥<?=number_format($flow->fChange, 2)?></em>
        </div>
        <div class="change_time flex">
            <span>变动时间</span>
            <em><?=$flow->dNewDate?></em>
        </div>
        <div class="change_type flex">
            <span>收支类型</span>
            <em>收入</em>
        </div>
        <div class="change_reason flex">
            <span>变动原因</span>
            <em><?=$flow->sName?></em>
        </div>
    </div>
    <div class="person">
        <div class="buyer flex">
            <div class="logo_pic">
                <img src="<?=$flow->wholesaleOrder->order->member->avatar?>">
            </div>
            <div class="person_info">
                <h3>买家</h3>
                <p><?=$flow->wholesaleOrder->order->member->sName?></p>
            </div>
        </div>
        <div class="suppiler flex">
            <div class="logo_pic">
                <img src="<?=$flow->wholesaleOrder->seller  ? $flow->wholesaleOrder->seller->avatar : "/images/order/person.png"?>">
            </div>
            <div class="person_info">
                <h3>经销商</h3>
                <p><?=$flow->wholesaleOrder->seller->sName?></p>
            </div>
        </div>
    </div>
    <div class="buy_commity">
        <h2>购买商品</h2>
        <ul onclick="location.href='/member/order?id=<?=$flow->wholesaleOrder->OrderID?>'">
            <? foreach ($flow->wholesaleOrder->arrOrderDetail as $detail) { ?>
            <li class="b_c_item">
                <p><?=$detail->sName?></p>
                <span><?=$flow->wholesaleOrder->order->dNewDate?></span>
            </li>
            <? } ?>
        </ul>
    </div>
</div>
