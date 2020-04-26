<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
    <h4 class="modal-title"><?= Yii::t('app', '金币充值') ?>:</h4>
</div>
<div class="modal-body">
    <div class="row">
        <div class="col-md-12">
            <div class="alert alert-danger">
                您在此增加或减少多少金币，该用户相应的就会增加或减少多少金币。
            </div>
        </div>
    </div>
    <form name="rechargeform">
        <div class="row">
            <div class="col-md-12">
                <div class="col-md-2">金币余额：</div>
                <div class="col-md-8">
                    <?=number_format($member->fGold, 2)?>
                </div>
            </div>
            <div class="col-md-12 margin-top-20">
                <div class="col-md-2">变化：</div>
                <div class="col-md-8">
                    <label><input type="radio" class="icheck" name="action" value="plus" checked>增加</label>
                    <label><input type="radio" class="icheck" name="action" value="minus">减少</label>
                </div>
            </div>
            <div class="col-md-12 margin-top-20">
                <div class="col-md-2">充值数目：</div>
                <div class="col-md-8">
                    <input class="form-control" name="fMoney" placeholder="请填写0~9999999的数值">
                </div>
            </div>
            <div class="col-md-12 margin-top-20">
                <div class="col-md-2">备注：</div>
                <div class="col-md-8">
                    <textarea rows="4" name="sNote" class="form-control"></textarea>
                </div>
            </div>

        </div>
    </form>
</div>
<div class="modal-footer">
    <button type="button" onclick="closeModal()" class="btn btn-outline dark"><?= Yii::t('app', '取消') ?></button>
    <button type="button" class="btn green" onclick="ok()"><?= Yii::t('app', '确认') ?></button>
</div>
<script>

    function ok() {

        var pattren = /^\d+(\.\d+)?$/;

        if (!pattren.test(document.rechargeform.fMoney.value)) {
            error("请输入正确的充值数目");
            return;
        }

        $.post(
            '/shop/member/recharge?ID=<?=$_GET['ID']?>',
            $(document.rechargeform).serialize(),
            function (data) {
                if (data.status) {
                    success(data.message)
                    closeModal();
                } else {
                    error(data.message);
                }
            }
        )
    }

    clearToastr();
</script>