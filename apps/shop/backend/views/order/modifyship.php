<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
    <h4 class="modal-title"><?= Yii::t('app', '修改物流') ?>:</h4>
</div>
<div class="modal-body">
    <div class="row">
        <div class="col-md-12">
            <div class="alert alert-info">
                为保证买家体验，请仔细填写，勿频繁更改物流信息
            </div>
        </div>
    </div>
    <form name="modifyshipform">
            <? foreach ($order as $i => $shipDetail) { ?>
            <div class="row margin-top-10">
                <div class="col-md-12">
                    <strong>包裹<?= $i + 1 ?></strong> 共
                    <?php
                    $sProductInfo = json_decode($shipDetail['sProductInfo'],true);
                    $num = 0;
                    foreach ($sProductInfo as  $v){
                        $num+= $v['lQuantity'];
                    }
                    ?>
                    <?=$num?>件商品
                    <input type="hidden" value="<?= $shipDetail['lID']?>" name="arrShip[<?= $i?>][lID]">
                </div>
            </div>
            <div class="row margin-top-20">
                <div class="col-md-6">
                    <div class="col-md-4">发货方式：</div>
                    <div class="col-md-8">
                        <input type="radio"
                               name="arrShip[<?= $i ?>][ShipID]"
                               value="wuliu"
                               onclick="changeShip('wuliu', <?= $shipDetail['lID'] ?>)"
                               checked
                        />物流
                    </div>
                </div>
            </div>
            <div class="row margin-top-20" id="company_<?= $shipDetail['lID'] ?>"
                 <? if ($shipDetail['ShipID'] == 'self') { ?>style="display: none" <? } ?>>
                <div class="col-md-6">
                    <div class="col-md-4">物流公司：</div>
                    <div class="col-md-8">
                        <select class="form-control" name="arrShip[<?= $i ?>][CompanyID]">
                            <? foreach ($arrCompany as $company) { ?>
                                <option value="<?= $company->ID ?>"
                                        <? if ($shipDetail['sExpressCompany'] == $company->sKdBirdCode) { ?>selected<? } ?>><?= $company->sName ?></option>
                            <? } ?>
                        </select>
                    </div>

                </div>
                <div class="col-md-6">
                    <div class="col-md-4">快递单号：</div>
                    <div class="col-md-8">
                        <input class="form-control" value="<?= $shipDetail['sExpressNo'] ?>" type="text"
                               name="arrShip[<?= $i ?>][sShipNo]">
                    </div>
                </div>
            </div>
        <? } ?>
    </form>
</div>
<div class="modal-footer">
    <button type="button" onclick="closeModal()" class="btn btn-outline dark"><?= Yii::t('app', '取消') ?></button>
    <button type="button" class="btn green" onclick="ok()"><?= Yii::t('app', '确定') ?></button>
</div>
<script>

    function ok() {
        $.post(
            '/shop/order/modifyship?ID=<?=$_GET['ID']?>',
            $(document.modifyshipform).serialize(),
            function (data) {
                if (data.status) {
                    success(data.message);
                    closeModal();
                } else {
                    error(data.message);
                }
            }
        )
    }

    function save(data) {
        uploadSave('<?=$_GET['sObjectName']?>', '<?=$_GET['sFieldAs']?>', '<?=$_GET['sLinkField']?>', data);
        clearToastr();
        closeModal();
    }

    function changeShip(sType, ID) {
        if (sType == 'wuliu') {
            $("#company_" + ID).show();
        } else {
            $("#company_" + ID).hide();
        }
    }

    clearToastr();
</script>
