<?php $this->beginBlock('style') ?>
<link rel="stylesheet" href="/css/policyAndAbnormal.css?<?=\Yii::$app->request->sRandomKey?>">
<?php $this->endBlock() ?>
<style>
    .policy_list {
        font-size: 32px;
    }
</style>
<div class="refund_policy">
    <div class="ad_header">
        <a href="javascript:;" onclick="goBack()" class="ad_back">
            <span class="icon">&#xe885;</span>
        </a>
        <h2>注册协议</h2>
    </div>
    <div class="policy_list">
        <?=\myerm\shop\common\models\MallConfig::getValueByKey('sMemberContract')?>
    </div>
</div>
