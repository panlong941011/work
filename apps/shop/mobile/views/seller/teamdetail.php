<?php $this->beginBlock('style') ?>
<link rel="stylesheet" href="/css/settlement.css?<?= \Yii::$app->request->sRandomKey ?>">
<?php $this->endBlock() ?>
<div class="dealer_etails">
    <div class="ad_header">
        <a href="javascript:;" class="ad_back" onclick="goBack()">
            <span class="icon">&#xe885;</span>
        </a>
        <h2>经销商详情</h2>
    </div>
    <div class="user flex">
        <div class="user_pic">
            <img src="<?= $seller->member->avatar ?>">
        </div>
        <h2><?= $seller->member->sName ?></h2>
    </div>
    <div class="user_info">
        <div class="info_item flex">
            <span>真实姓名</span>
            <p><?= $seller->sName ?></p>
        </div>
        <div class="info_item flex">
            <span>经销商类型</span>
            <p><?= $seller->type->sName ?></p>
        </div>
        <div class="info_item flex">
            <span>手机号</span>
            <p class="focus"><a href="tel:<?= $seller->sMobile ?>"><?= $seller->sMobile ?></a></p>
        </div>
        <div class="info_item flex">
            <span>注册来源</span>
            <p class="focus"><?= $seller->member->fromMember ? $seller->member->fromMember->sName : "--" ?></p>
        </div>
        <div class="info_item flex">
            <span>推荐人</span>
            <p class="focus"><?= $seller->upSeller ? $seller->upSeller->sName : "--" ?></p>
        </div>
        <div class="info_item flex">
            <span>入驻时间</span>
            <p><?= $seller->dNewDate ?></p>
        </div>
    </div>
    <div class="achieve">
        <div class="achieve_item flex">
            <span>累计个人业绩</span>
            <p>￥<?= number_format($seller->stats->fSale, 2) ?></p>
        </div>
        <div class="achieve_item flex">
            <span>本月个人业绩</span>
            <p>￥<?= number_format($seller->stats->fSaleThisMonth, 2) ?></p>
        </div>
        <div class="achieve_item flex">
            <span>上月个人业绩</span>
            <p>￥<?= number_format($seller->stats->fSaleLastMonth, 2) ?></p>
        </div>
        <div class="achieve_item flex">
            <span>上上月个人业绩</span>
            <p>￥<?= number_format($seller->stats->fSaleBeforeLastMonth, 2) ?></p>
        </div>
        <div class="achieve_item flex">
            <span>环比</span>
            <div class="mom_icon downward">
                <i class="icon">&#xe612;</i>
                <em><?=$seller->stats->sConsecutive == '--' ? "--" : $seller->stats->sConsecutive."%"?></em>
            </div>
        </div>
        <div class="achieve_item flex">
            <span>一级团队数</span>
            <p><?= $seller->stats->lFirstTeamNum ?></p>
        </div>
        <div class="achieve_item flex">
            <span>二级团队数</span>
            <p><?= $seller->stats->lSecondTeamNum ?></p>
        </div>
        <div class="achieve_item flex">
            <span>团队总数</span>
            <p><?= $seller->stats->lTeamNum ?></p>
        </div>
        <div class="achieve_item flex">
            <span>团队业绩</span>
            <p>￥<?= number_format($seller->stats->fTeamSale, 2) ?></p>
        </div>
    </div>
</div>
