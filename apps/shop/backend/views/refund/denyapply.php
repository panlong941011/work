<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
    <h4 class="modal-title"><?= Yii::t('app', '退款处理') ?>:</h4>
</div>
<div class="modal-body">
    <div class="row">
        <div class="col-md-12">
            <div class="alert alert-danger">
                建议您跟买家协商，再确认是否拒绝退款
            </div>
        </div>
    </div>
    <form name="applyform">
            <div class="row">
                <div class="col-md-12">
                    <div class="col-md-2">退款方式：</div>
                    <div class="col-md-8">
                        <?=$refund->sType?>
                    </div>
                </div>
                <div class="col-md-12 margin-top-20">
                    <div class="col-md-2">渠道商建议金额：</div>
                    <div class="col-md-8">
                        ¥<?=number_format($adviseBuyer, 2)?>
                    </div>
                </div>
                <div class="col-md-12 margin-top-20">
                    <div class="col-md-2">渠道商退款金额：</div>
                    <div class="col-md-8">
                        ¥<?=number_format($refund->fBuyerRefund, 2)?>
                    </div>
                </div>
                <div class="col-md-12 margin-top-20">
                    <div class="col-md-2">供应商建议金额：</div>
                    <div class="col-md-8">
                        ¥<?=number_format($adviseSupplier, 2)?>
                    </div>
                </div>
                <div class="col-md-12 margin-top-20">
                    <div class="col-md-2">供应商退款金额：</div>
                    <div class="col-md-8">
                        ¥<?=number_format($refund->fSupplierRefund, 2)?>
                    </div>
                </div>

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
                        <textarea rows="4" name="sDenyApplyExplain" class="form-control" placeholder="您可以告诉买家您拒绝的详细原因和下一步如何操作，以便买家处理最多150字"></textarea>
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

        if (document.applyform.sDenyApplyReason.value == "") {
            error("请选择拒绝原因");
            return false;
        }

        $.post(
            '/shop/refund/denyapply?ID=<?=$_GET['ID']?>',
            $(document.applyform).serialize(),
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