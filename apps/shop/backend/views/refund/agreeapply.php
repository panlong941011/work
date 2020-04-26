<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
    <h4 class="modal-title"><?= Yii::t('app', '退款处理') ?>:</h4>
</div>
<div class="modal-body">
    <div class="row">
        <div class="col-md-12">
            <div class="alert alert-danger">
                仅退款情况下，您一旦同意，退款将自动原路退回至买家付款账户；<br>
                退货退款情况下，您同意后，需要买家先退货。等您确认收货后，退款将自动原路退回到买家付款账户
            </div>
        </div>
    </div>
    <form name="modifyshipform">
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
            </div>
    </form>
</div>
<div class="modal-footer">
    <button type="button" onclick="closeModal()" class="btn btn-outline dark"><?= Yii::t('app', '取消') ?></button>
    <button type="button" class="btn green" onclick="ok()" id="btn-ok"><?= Yii::t('app', '同意') ?></button>
</div>
<script>

    function ok() {

        $("#btn-ok").prop("disabled", "disabled");
        var refundprice=$('#refundprice').val();
        $.post(
            '/shop/refund/agreeapply?ID=<?=$_GET['ID']?>',
            $(document.modifyshipform).serialize(),
            function (data) {
                if (data.status) {
                    success(data.message)
                    setInterval("location.reload();", 1000);
                } else {
                    error(data.message);
                    $("#btn-ok").prop("disabled", "");
                }
            }
        )
    }

    clearToastr();
</script>