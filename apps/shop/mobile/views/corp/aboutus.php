<?php $this->beginBlock('style') ?>
<link rel="stylesheet" href="/css/policyAndAbnormal.css?<?=\Yii::$app->request->sRandomKey?>">
<?php $this->endBlock() ?>
<style>

     @media screen and (max-width: 640px) {
        .policy_list p,
        .policy_list span{
            font-size: 30px!important;
        }
     } 
    @media screen  and (min-width: 641px) and (max-width: 750px) {
        .policy_list p,
        .policy_list span{
            font-size: 32px!important;
        }
     } 
     @media screen  and (min-width: 1000px) {
        .policy_list p,
        .policy_list span{
            font-size: 48px!important;
        }
     } 
</style>
<div class="refund_policy">
    <div class="ad_header">
        <a href="javascript:;" onclick="goBack()" class="ad_back">
            <span class="icon">&#xe885;</span>
        </a>
        <h2>关于我们</h2>
    </div>
    <div class="policy_list">
        <?=\myerm\shop\common\models\MallConfig::getValueByKey('sAbout')?>
    </div>
</div>
