<a href="javascript:;" class="btn green btn-sm" onclick="this.listtable.refresh()"> <?= Yii::t('app', '刷新') ?> </a>

<a href="javascript:;" class="btn green btn-sm" onclick="confirmation(this, '是否启用 ', function(obj){enable(obj.listtable)})"> 启用 </a>

<a href="javascript:;" class="btn green btn-sm" onclick="confirmation(this, '是否禁用 ', function(obj){disable(obj.listtable)})"> 禁用 </a>

<script>
    function enable(listtable)
    {
        var lLength = listtable.getSelectedLength();
        if (lLength == 0) {
            error("至少选择一条记录。");
            return;
        }

        var postData = listtable.getPostData();
        $(listtable.container).mask('');
        $.post
        (
            '<?=Yii::$app->homeUrl?>/<?=strtolower($this->context->sysObject->sObjectName)?>/enable',
            postData,
            function(data)
            {
                eval("var ret = "+data);
                if (ret.bSuccess) {
                    success(ret.sMsg);
                    listtable.emptySelected();
                    listtable.loadData();
                } else {
                    error(ret.sMsg);
                    $(listtable.container).unmask('');
                }
            }
        )
    }

    function disable(listtable)
    {
        var lLength = listtable.getSelectedLength();
        if (lLength == 0) {
            error("至少选择一条记录。");
            return;
        }

        var postData = listtable.getPostData();
        $(listtable.container).mask('');
        $.post
        (
            '<?=Yii::$app->homeUrl?>/<?=strtolower($this->context->sysObject->sObjectName)?>/disable',
            postData,
            function(data)
            {
                eval("var ret = "+data);
                if (ret.bSuccess) {
                    success(ret.sMsg);
                    listtable.emptySelected();
                    listtable.loadData();
                } else {
                    error(ret.sMsg);
                    $(listtable.container).unmask('');
                }
            }
        )
    }
</script>