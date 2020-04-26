<div class="breadcrumb" style="display:none">
    <h2><?= Yii::t('app', '批量发货') ?></h2>
    <h3><?= Yii::t('app', '主页') ?></h3>
</div>
<div class="row margin-top-10">
    <div class="col-md-12">
        <div class="portlet light bordered">
            <div class="form-actions margin-top-10">
                <div class="note note-warning">
                    <p>
                        批量发货针对整笔订单发货，无法部分商品发货，如某订单已部分商品发货，再批量发货，会提示商品重复发货。
                    </p>
                </div>
            </div>
            <div class="portlet-body form">
                <form name="objectform"
                      action="/shop/order/batchship" class="horizontal-form" method="post"
                      enctype="multipart/form-data">
                    <input type="hidden" name="_csrf" value="<?= Yii::$app->request->getCsrfToken() ?>">
                    <div class="form-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label col-md-3"><?= Yii::t('app', 'Excel订单文件') ?><span
                                                class="required" aria-required="true">*</span>:</label>
                                    <div class="col-md-9">
                                        <input type="file" class="form-control" name="file">
                                        <span class="help-block">
                                            使用说明：<br>
                                            1、请先从【订单】列表导出需要发货的订单<br>
                                            2、填写完整订单文件两列（快递公司、物流单号）
                                        </span>

                                        <div class="margin-top-10">
                                            <button type="button" class="btn default" onclick="parent.closeCurrTab()"><?=Yii::t('app', '取消')?></button>
                                            <button type="button" class="btn green" onclick="ok()">
                                                <i class="fa fa-check"></i>
                                                <?=Yii::t('app', '确定上传')?>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!--/span-->
                        </div>
                        <!--/row-->
                        <? if ($arrMsg) { ?>
                            <div class="alert alert-danger margin-top-10">
                                <button class="close" data-close="alert"></button>
                                <? foreach ($arrMsg as $msg) { ?>
                                    <p><?= $msg ?></p>
                                <? } ?>
                            </div>
                        <? } ?>
                    </div>

                </form>
            </div>
        </div>
    </div>
</div>
<script>
function ok() {
    document.objectform.submit();
}

</script>