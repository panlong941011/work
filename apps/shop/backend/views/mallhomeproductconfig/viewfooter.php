<div class="margin-top-10">
    <div class="portlet light bordered">
        <div class="portlet-title">
            <div class="caption">
                <span class="caption-subject bold uppercase">商品明细</span>
            </div>
        </div>
        <div class="table-scrollable">
            <table class="table table-striped table-bordered table-hover table-checkable order-column dataTable no-footer table-header-fixed"
                   id="listtable">
                <thead>
                <tr role="row">
                    <th>商品名称</th>
                    <th style="text-align: center;">序号</th>
                    <th style="text-align: center;">商品图片</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($arrDetail as $detail) { ?>
                    <tr class="gradeX odd" role="row">
                        <td><a href="javsscript:;"
                               onclick="parent.addTab($(this).text(), '/shop/product/view?ID=<?= $detail['product']->lID ?>')"><?= $detail['product']->sName ?></a>
                        </td>
                        <td style="text-align: center"><?= $detail['lPos'] ?></td>
                        <td style="text-align: center;">
                            <img src="<?= \Yii::$app->params['sUploadUrl'] ?>/<?= $detail['sPicPath'] ?>" width="150">
                        </td>
                    </tr>
                <?php } ?>

                </tbody>
            </table>
        </div>
    </div>
</div>