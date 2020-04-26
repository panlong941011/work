<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
    <h4 class="modal-title"><?= Yii::t('app', '退货申请') ?>:</h4>
</div>
<div class="modal-body">
    <div class="row">
        <div class="col-md-12">
            <div class="alert alert-danger">
                建议您跟买家协商，再确认是否拒绝收货
            </div>
        </div>
    </div>
    <form name="applyform">
            <div class="row">
                <div class="col-md-12 margin-top-20">
                    <div class="col-md-2">拒绝原因<span class="required">*</span>：</div>
                    <div class="col-md-4">
                        <select class="form-control" name="sDenyApplyReason">
                            <option value="">请选择拒绝原因</option>
                            <option value="未上传照片">未上传照片</option>
                            <option value="其他">其他</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-12 margin-top-20">
                    <div class="col-md-2">拒绝说明：</div>
                    <div class="col-md-8">
                        <textarea rows="4" name="sDenyReceiveExplain" class="form-control" placeholder="您可以告诉买家您拒绝的详细原因，以便买家处理最多150字"></textarea>
                    </div>
                </div>
            </div>
    </form>
</div>
<div class="modal-footer">
    <button type="button" onclick="closeModal()" class="btn btn-outline dark"><?= Yii::t('app', '取消') ?></button>
    <button type="button" class="btn red" onclick="ok()"><?= Yii::t('app', '拒绝') ?></button>
</div>
<script>

    function ok() {

        $.post(
            '/shop/refundreturn/denyreceive?ID=<?=$_GET['ID']?>',
            $(document.applyform).serialize(),
            function (data) {
                if (data.status) {
                    success(data.message);
                    setInterval("location.reload();", 1000);
                } else {
                    error(data.message);
                }
            }
        )
    }

    clearToastr();
</script>