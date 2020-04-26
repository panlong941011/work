
<a href="javascript:;" style="display: none" class="btn green btn-sm" onclick="this.listtable.export()"> <?= Yii::t('app', '导出') ?> </a>
<a href="javascript:;" class="btn green btn-sm" onclick="choose(this.listtable)"> <?= Yii::t('app', '臻选商品') ?> </a>
<a href="javascript:;" class="btn green btn-sm" onclick="this.listtable.refresh()"> <?= Yii::t('app', '刷新') ?> </a>
<script>
    function choose(listtable){
        var lLength = listtable.getSelectedLength();
        if (lLength != 1) {
            error("请选择一条商品");
            return;
        }
        var postData = listtable.getPostData();
        $.post
        (
            listtable.sUrl + '/productbuyer', postData,
            function (data) {
                eval(data);
                if (ret.bSuccess) {
                    success(ret.sMsg);
                } else {
                    error(ret.sMsg);
                    $(listtable.container).unmask('');
                }
            }
        )
    }

</script>