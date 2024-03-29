<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
    <h4 class="modal-title"><?= Yii::t('app', '退款处理') ?>:</h4>
</div>
<div class="modal-body">
    <form name="modifyshipform">
            <div class="row">
                <div class="col-md-12">
                    <div class="col-md-2">退款方式：</div>
                    <div class="col-md-8">
                        <?=$refund->sType?>
                    </div>
                </div>
                <div class="col-md-12 margin-top-20">
                    <div class="col-md-2">退款金额：</div>
                    <div class="col-md-8">
                        <?=number_format($refund->fRefundApply, 2)?>
                    </div>
                </div>
                <div class="col-md-12 margin-top-20">
                    <div class="col-md-2">退款地址：</div>
                    <div class="col-md-8">
                        <?=$refund->sAddress?>
                    </div>
                </div>
            </div>
    </form>
</div>
<div class="modal-footer">
    <button type="button" onclick="closeModal()" class="btn btn-outline dark"><?= Yii::t('app', '取消') ?></button>
    <button type="button" class="btn green" onclick="ok()"><?= Yii::t('app', '同意') ?></button>
</div>
<script>

    function ok() {
        $.post(
            '/shop/refund/agreereceive?ID=<?=$_GET['ID']?>',
            $(document.modifyshipform).serialize(),
            function (data) {
                if (data.status) {
                    success(data.message)
                    setInterval("location.reload();", 1000);
                } else {
                    error(data.message);
                }
            }
        )
    }

    clearToastr();
</script>