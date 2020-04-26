<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
    <h4 class="modal-title"><?=$product->sName?></h4>
</div>
<div class="modal-body">
    <div class="row">
        <div class="col-md-12 text-center" id="chart"><img width="300" height="300" src="https://www.kuaizhan.com/common/encode-png?large=true&data=<?=\myerm\shop\common\models\MallConfig::getValueByKey('sMallRootUrl')?>/shop0/product/detail?id=<?=$product->lID?>" alt=""></div>
    </div>
    <div class="row margin-top-10">
        <div class="col-md-12 text-center">
            <button type="button" class="btn green" id="copy" data-clipboard-text="<?=\myerm\shop\common\models\MallConfig::getValueByKey('sMallRootUrl')?>/shop0/product/detail?id=<?=$product->lID?>">复制链接</button>
        </div>
    </div>
</div>