<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
    <h4 class="modal-title"><?= Yii::t('app', '添加推荐') ?>:</h4>
</div>
<div class="modal-body">
    <form name="modifyshipform">
        <input type="hidden" name="_csrf" value="<?= Yii::$app->request->getCsrfToken() ?>">
        <div class="table-scrollable">
            <table class="table table-hover">
                <thead>
                <tr>
                    <th> 选中</th>
                    <th> 秒杀商品</th>
                    <th> 秒杀价格</th>
                    <th> 开始时间</th>
                    <th> 结束时间</th>
                    <th> 状态</th>
                </tr>
                </thead>
                <tbody>
                <? foreach ($arrProduct as $key => $product) { ?>
                    <? if (!in_array($product->lID, $arrID)) { ?>
                        <tr>
                            <td><input type="checkbox" name="checked[]" value="<?= $product['lID'] ?>"></td>
                            <td> <?= $product->product->sName ?></td>
                            <td> <?= number_format($product['fPrice'], 2) ?></td>
                            <td> <?= $product['dStartDate'] ?></td>
                            <td> <?= $product['dEndDate'] ?></td>
                            <td> <?= $product->parent->sStatus ?></td>
                        </tr>
                    <? } ?>
                <? } ?>
                </tbody>
            </table>
        </div>
    </form>
</div>
<div class="modal-footer">
    <button type="button" onclick="closeModal()" class="btn btn-outline dark"><?= Yii::t('app', '取消') ?></button>
    <button type="button" class="btn green" onclick="ok()"><?= Yii::t('app', '确定') ?></button>
</div>
<script>

    function ok() {
        $.post(
            '/shop/homeseckill/addproduct',
            $(document.modifyshipform).serialize(),
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
</script>