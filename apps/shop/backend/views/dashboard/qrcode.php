<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
    <h4 class="modal-title">微信扫码直接访问商城</h4>
</div>
<div class="modal-body">
    <div class="row">
        <div class="col-md-12 text-center" id="chart"><img width="300" height="300" src="https://pan.baidu.com/share/qrcode?w=300&h=300&url=<?= \myerm\shop\common\models\MallConfig::getValueByKey('sMallRootUrl') ?>/shop0/home" alt=""></div>
    </div>
    <div class="row">
        <div class="col-md-12 text-center">
            <button type="button" class="btn green" id="copy" data-clipboard-text="<?= \myerm\shop\common\models\MallConfig::getValueByKey('sMallRootUrl') ?>/shop0/home">复制链接</button>
        </div>
    </div>
</div>