<a href="javascript:;" class="btn green btn-sm" onclick="this.listtable.refresh()"> <?= Yii::t('app', '刷新') ?> </a>

<a href="javascript:;" class="btn green btn-sm"  onclick="confirmation(this, '<?= Yii::t('app', '确定要确认订单？') ?>', function(obj){confirmOrder(obj.listtable)})"> <?= Yii::t('app',
		'确认订单') ?> </a>
<a href="javascript:;" class="btn green btn-sm"  onclick="confirmation(this, '<?= Yii::t('app', '确定要取消订单？') ?>', function(obj){cancleOrder(obj.listtable)})"> <?= Yii::t('app',
        '取消订单') ?> </a>
<script>
    function confirmOrder(listtable) {
        var lLength = listtable.getSelectedLength();
        if (lLength == 0) {
            error("至少选择一条记录。");
            return;
        }

        var postData = listtable.getPostData();

        $.post
        (
            listtable.sUrl + '/confirmorder',
            postData,
            function (data) {
                clearToastr();
                if (data.bSuccess) {
                    success(data.sMsg);
                    listtable.emptySelected();
                    listtable.loadData();
                } else {
                    error(data.sMsg);
                }
            }
        )
    }
    function cancleOrder(listtable) {
        var lLength = listtable.getSelectedLength();
        if (lLength == 0) {
            error("至少选择一条记录。");
            return;
        }

        var postData = listtable.getPostData();

        $.post
        (
            listtable.sUrl + '/cancleorder',
            postData,
            function (data) {
                clearToastr();
                if (data.bSuccess) {
                    success(data.sMsg);
                    listtable.emptySelected();
                    listtable.loadData();
                } else {
                    error(data.sMsg);
                }
            }
        )
    }
</script>