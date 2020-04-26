<a href="javascript:;" class="btn green btn-sm" onclick="this.listtable.refresh()"> <?= Yii::t('app', '刷新') ?> </a>

<a href="javascript:;" class="btn green btn-sm"
   onclick="printsheet(this.listtable)">
    <?= Yii::t('app', '补打面单') ?>
</a>

<script>
    function printsheet(listtable) {
        var lLength = listtable.getSelectedLength();
        if (lLength == 0) {
            error("至少选择一条记录。");
            return;
        }
        var postData = listtable.getPostData();
        $.post
        (
            listtable.sUrl + '/alertexpress',
            postData,
            function (data) {
                clearToastr();
                if (data.bSuccess) {
                    openModal(data.data, 800, 400)
                } else {
                    error(data.sMsg);
                }
            }
        )
    }
</script>