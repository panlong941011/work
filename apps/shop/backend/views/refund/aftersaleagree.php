<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
    <h4 class="modal-title"><?= Yii::t('app', '退款处理') ?>:</h4>
</div>
<div class="modal-body">
    <!--    <div class="row">-->
    <!--        <div class="col-md-12">-->
    <!--            <div class="alert alert-danger">-->
    <!--                仅退款情况下，您一旦同意，退款将自动原路退回至买家付款账户；<br>-->
    <!--                退货退款情况下，您同意后，需要买家先退货。等您确认收货后，退款将自动原路退回到买家付款账户-->
    <!--            </div>-->
    <!--        </div>-->
    <!--    </div>-->
    <form name="modifyshipform">
        <div class="row">
            <div class="col-md-12">
                <div class="col-md-2">退款方式：</div>
                <div class="col-md-8">
                    <?= $refund->sType ?>
                </div>
            </div>
            <div class="col-md-12 margin-top-20">
                <div class="col-md-2">渠道商建议商品退还金额：</div>
                <div class="col-md-8">
                    ¥<?= number_format($adviseBuyerProduct, 2) ?>
                </div>
            </div>
            <div class="col-md-12 margin-top-20">
                <div class="col-md-2">渠道商商品退款金额：</div>
                <div class="col-md-8">
                    ¥<input type="text" id="BuyerRefundProduct">
                </div>
            </div>
            <div class="col-md-12 margin-top-20">
                <div class="col-md-2">渠道商退款金额：</div>
                <div class="col-md-8">
                    ¥<input type="text" id="BuyerRefund" readonly>
                </div>
            </div>
            <div class="col-md-12 margin-top-20">
                <div class="col-md-2">供应商建议商品扣除金额：</div>
                <div class="col-md-8">
                    ¥<?= number_format($adviseSupplierProduct, 2) ?>
                </div>
            </div>
            <div class="col-md-12 margin-top-20">
                <div class="col-md-2">供应商商品退款金额：</div>
                <div class="col-md-8">
                    ¥<input type="text" id="SupplierRefundProduct">
                </div>
            </div>
            <div class="col-md-12 margin-top-20">
                <div class="col-md-2">供应商退款金额：</div>
                <div class="col-md-8">
                    ¥<input type="text" id="SupplierRefund" readonly>
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
        var BuyerRefund = parseFloat($('#BuyerRefund').val());
        var BuyerRefundProduct = parseFloat($('#BuyerRefundProduct').val());
        var SupplierRefund = parseFloat($('#SupplierRefund').val());
        var SupplierRefundProduct = parseFloat($('#SupplierRefundProduct').val());
        if (!$('#BuyerRefundProduct').val()) {
            error('请输入渠道商退款商品金额');
            return;
        }
        if (!$('#SupplierRefundProduct').val()) {
            error('请输入供应商退款商品金额');
            return;
        }
        if (BuyerRefundProduct <= 0) {
            error('渠道商退款商品金额必须大于0');
            return;
        }
        if (SupplierRefundProduct <= 0) {
            error('供应商退款商品金额必须大于0');
            return;
        }
        $.post(
            '/shop/refund/aftersaleagree?ID=<?=$_GET['ID']?>&BuyerRefund=' + BuyerRefund + '&SupplierRefund=' + SupplierRefund + '&BuyerRefundProduct=' + BuyerRefundProduct + '&SupplierRefundProduct=' + SupplierRefundProduct,
            $(document.modifyshipform).serialize(),
            function (data) {
                if (data.status) {
                    success(data.message)
                    setInterval("location.reload();", 1000);
                } else {
                    error(data.message);
                    console.log(data.msg);
                    $("#btn-ok").prop("disabled", "");
                }
            }
        )
    }

    $('#BuyerRefundProduct').bind('input propertychange', function () {

        var fRefundProduct = parseFloat($('#BuyerRefundProduct').val());

        if (fRefundProduct) {
            var fBuyerTotalPrice = parseFloat(<?=$fBuyerPaidTotal?>);
            var fTotalShip = parseFloat(<?=$fTotalShip?>);
            var refundprice = fRefundProduct + fTotalShip * (fRefundProduct / fBuyerTotalPrice);
            if (fRefundProduct >= fBuyerTotalPrice) {
                refundprice = fRefundProduct + fTotalShip;
            }
            $('#BuyerRefund').val(refundprice.toFixed(2));
        }
    })

    $('#SupplierRefundProduct').bind('input propertychange', function () {

        var fRefundProduct = parseFloat($('#SupplierRefundProduct').val());

        if (fRefundProduct) {
            var fSupplierTotalPrice = parseFloat(<?=$fSupplierIncomeTotal?>);
            var fTotalShip = parseFloat(<?=$fTotalShip?>);
            var refundprice = fRefundProduct + fTotalShip * (fRefundProduct / fSupplierTotalPrice);
            if (fRefundProduct >= fSupplierTotalPrice) {
                refundprice = fRefundProduct + fTotalShip;
            }
            $('#SupplierRefund').val(refundprice.toFixed(2));
        }
    })

    clearToastr();
</script>