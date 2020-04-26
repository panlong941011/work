<? if ($this->context->checkHasOpera('export') && $this->context->checkHasOperaPower('export')) { ?>
    <a href="javascript:;" class="btn green btn-sm" onclick="this.listtable.export()"> <?= Yii::t('app', '导出') ?> </a>
<? } ?>

<? if ($_GET['sTabID'] == 'delivered') { ?>
    <a href="javascript:;" class="btn green btn-sm"  onclick="confirmation(this, '<?= Yii::t('app', '确定要确认收货？') ?>', function(obj){confirmReceive(obj.listtable)})"> <?= Yii::t('app',
            '确认收货') ?> </a>
<? } ?>

<a href="javascript:;" class="btn green btn-sm" onclick="this.listtable.refresh()"> <?= Yii::t('app', '刷新') ?> </a>

<a href="javascript:;" class="btn green btn-sm"   onclick="QuickSeparate(this.listtable)"  style="display: none">
    <?= Yii::t('app', '一键拆分订单物流') ?>
</a>

<a href="javascript:;" class="btn green btn-sm"   onclick="SeparateExpress(this.listtable)" style="display: none">
    <?= Yii::t('app', '拆分订单物流') ?>
</a>

<a href="javascript:;" class="btn green btn-sm"   onclick="couriernumber(this.listtable)"  style="display: none">
    <?= Yii::t('app', '获取快递单号') ?>
</a>

<a href="javascript:;" class="btn green btn-sm"    onclick="printsheet(this.listtable)"  style="display: none">
    <?= Yii::t('app', '打印电子面单') ?>
</a>

<script>
    function confirmReceive(listtable) {
        var lLength = listtable.getSelectedLength();
        if (lLength == 0) {
            error("至少选择一条记录。");
            return;
        }

        var postData = listtable.getPostData();

        $.post
        (
            listtable.sUrl + '/confirmreceive',
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

    function couriernumber(listtable) {
        var lLength = listtable.getSelectedLength();
        if (lLength == 0) {
            error("至少选择一条记录。");
            return;
        }

        var postData = listtable.getPostData();

        $.post
        (
            listtable.sUrl + '/alerttemplate',
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

    function printsheet(listtable) {
        var lLength = listtable.getSelectedLength();
        if (lLength == 0) {
            error("至少选择一条记录。");
            return;
        }

        var postData = listtable.getPostData();

        $.post
        (
            listtable.sUrl + '/alertexpressnumber',
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

    //拆分物流 by hcc on 2018/7/10
    function SeparateExpress(listtable){
        var lLength = listtable.getSelectedLength();
        if (lLength == 0) {
            error("请选择一条记录。");
            return;
        }
        if(lLength > 1 || lLength == -1){
            error("只能选择一条记录。");
            return;
        }

        var postData = listtable.getPostData();

        $.post
        (
            listtable.sUrl + '/separateexpress',
            postData,
            function (data) {
                clearToastr();
                if (data.bSuccess) {
                    openModal(data.data, 800, 300)
                } else {
                    error(data.sMsg);
                }
            }
        )
    }

    //一键拆分物流 by hcc on 2018/7/20
    function QuickSeparate(listtable){
        var lLength = listtable.getSelectedLength();
        if (lLength == 0) {
            error("请选择一条记录。");
            return;
        }

        var postData = listtable.getPostData();

        $.post
        (
            listtable.sUrl + '/quickseparate',
            postData,
            function (data) {
                clearToastr();
                if (data.bSuccess) {
                    openModal(data.data, 800, 300)
                } else {
                    error(data.sMsg);
                }
            }
        )
    }
</script>