<? if ($_GET['sListKey'] == 'Main.Shop.HomeSecKill.List.All') { ?>
<a href="javascript:;" class="btn green btn-sm" onclick="addProduct(this.listtable)"> <?= Yii::t('app', '添加推荐') ?> </a>
<a href="javascript:;" class="btn green btn-sm"
   onclick="confirmation(this, '<?= Yii::t('app', '确定要删除？') ?>', function(obj){obj.listtable.del()})"> <?= Yii::t('app', '删除') ?> </a>
<? } ?>
<a href="javascript:;" class="btn green btn-sm" onclick="this.listtable.refresh()"> <?= Yii::t('app', '刷新') ?> </a>

<script>

    function addProduct(object) {
        var listtable = $(object).closest(".tab-pane").find("#btngroup a").prop("listtable");
        $('#myermmodal').prop('listtable', listtable);
        $.get('/shop/homeseckill/addproduct', function (data) {
            var modal = openModal(data, 800, 500);
        });
    }

    function savePos(lID, lPos) {
        $.get('/shop/homeseckill/savepos?lID='+lID+'&lPos='+lPos, function (data) {
            success("排序保存成功");
        });
    }

</script>