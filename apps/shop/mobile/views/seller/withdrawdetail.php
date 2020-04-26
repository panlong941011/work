<?php $this->beginBlock('style') ?>
<link rel="stylesheet" href="/css/settlement.css?<?= \Yii::$app->request->sRandomKey ?>">
<?php $this->endBlock() ?>
<div class="present_record">
    <div class="ad_header">
        <a href="javascript:;" class="ad_back" onclick="goBack()">
            <span class="icon">&#xe885;</span>
        </a>
        <h2>提现详情</h2>
    </div>
    <div class="f_d_info">
        <div class="change_money flex">
            <span>提现金额</span>
            <em>¥<?=number_format(abs($log->fMoney), 2)?></em>
        </div>
        <div class="change_time flex">
            <span>持卡人</span>
            <em><?=$log->sBankAccount?></em>
        </div>
        <div class="change_type flex">
            <span>银行卡号</span>
            <em><?=$log->sBankNo?></em>
        </div>
        <div class="change_reason flex">
            <span>银行</span>
            <em><?=$log->sBank?></em>
        </div>
    </div>
    <div class="progress_wrap">
        <h2>提现处理进度</h2>
        <div class="process flex">
            <div class="navigation flex">
                <i class="dot"></i>
                <span class="line"></span>

                <i class="dot"></i>
                <span class="line"></span>

                <i class="dot success"></i>
            </div>
            <div class="process_list">
                <div class="process_item">
                    <h3 class="process_content">申请提现</h3>
                    <p class="process_time"><?=$log->dNewDate?></p>
                </div>
                <div class="process_item">
                    <h3 class="process_content">微信受理</h3>
                    <p class="process_time"><?=$log->dAcceptDate?></p>
                </div>
                <div class="process_item">
                    <h3 class="process_content">确认到账</h3>
                    <p class="process_time"><?=$log->dCompleteDate?></p>
                </div>
            </div>
        </div>
    </div>
</div>
