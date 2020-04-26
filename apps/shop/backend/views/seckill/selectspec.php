<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
    <h4 class="modal-title"><?= Yii::t('app', '选择商品规格') ?>:</h4>
</div>
<div class="modal-body">
    <form name="modifyshipform">
        <div class="row" style="margin: 1px">
            <div class="table-scrollable">
                <table class="table table-bordered table-hover">
                    <thead>
                    <tr>
                        <th> 选择</th>
                        <th> 商品规格</th>
                        <th> 价格(元)</th>
                        <th> 数量</th>
                        <th> 进货价格</th>
                    </tr>
                    </thead>
                    <tbody>
                    <? if ($arrSku) { ?>
                        <? foreach ($arrSku as $sku) { ?>
                            <tr>
                                <td><input checked type="checkbox" name="spec" value="<?= $sku->sValue ?>"></td>
                                <td><?= $sku->sValue ?></td>
                                <td><?= $sku->fPrice ?></td>
                                <td><?= $sku->lStock ?></td>
                                <td><?= $sku->fCostPrice ?></td>
                            </tr>
                        <? } ?>
                    <? } else { ?>
                        <tr>
                            <td><input checked type="checkbox" name="spec" value="默认规格"></td>
                            <td>默认规格</td>
                            <td><?= $product->fPrice ?></td>
                            <td><?= $product->lStock ?></td>
                            <td><?= $product->fCostPrice ?></td>
                        </tr>
                    <? } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </form>
</div>
<div class="modal-footer">
    <button type="button" onclick="closeModal()" class="btn btn-outline dark"><?= Yii::t('app', '取消') ?></button>
    <button type="button" class="btn green" onclick="ok()"><?= Yii::t('app', '确定') ?></button>
</div>
<script>

    function ok() {
        $("input:checked[name='spec']").each(
            function () {
                selectedSpec(this);
            }
        );
        closeModal()
    }

    clearToastr();
</script>