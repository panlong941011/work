<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
    <h4 class="modal-title"><?= Yii::t('app', '订单备注') ?>:</h4>
</div>
<div class="modal-body">
    <form name="modifyshipform">
        <div class="row">
            <div class="col-md-12 margin-top-20">
                <div class="col-md-3">订单备注：</div>
                <div class="col-md-8">
                    <textarea class="form-control" name="message"><?=$order->sNote?></textarea>
                </div>
            </div>
        </div>
    </form>
</div>
<div class="modal-footer">
    <button type="button" onclick="closeModal()" class="btn btn-outline dark"><?= Yii::t('app', '取消') ?></button>
    <button type="button" class="btn green" onclick="ok()"><?= Yii::t('app', '保存') ?></button>
</div>
<script>

    function ok() {
        $.post(
            '/shop/order/modifymessage?ID=<?=$_GET['ID']?>',
            $(document.modifyshipform).serialize(),
            function (data) {
                if (data.status) {
                    success(data.message)
                    setInterval("location.reload();", 500);
                } else {
                    error(data.message);
                }
            }
        )
    }

    clearToastr();
</script>