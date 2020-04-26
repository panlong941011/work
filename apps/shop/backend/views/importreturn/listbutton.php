<a href="javascript:;" class="btn green btn-sm" onclick="this.listtable.del()"> <?= Yii::t('app', '删除') ?> </a>
<a href="javascript:;" class="btn green btn-sm" onclick="this.listtable.refresh()"> <?= Yii::t('app', '刷新') ?> </a>
<script>
    function choose(listtable) {
        var postData = '';
        if (listtable.arrSelected == 'all') {
            postData = {
                sSelectedID: 'all'
            }
        }
        else {
            var lLength = listtable.getSelectedLength();
            if (lLength < 1) {
                error("请至少选择一条订单数据提交");
                return;
            }
            postData = listtable.getPostData();
        }
        $.post
        (
            listtable.sUrl + '/savepreorder', postData,
            function (data) {
                console.log(data);
                eval(data);
                if (ret.bSuccess) {
                    success(ret.sMsg);
                    listtable.loadData();
                } else {
                    error(ret.sMsg);
                    $(listtable.container).unmask('');
                }
            }
        )
    }

</script>