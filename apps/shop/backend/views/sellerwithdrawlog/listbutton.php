<? if ($this->context->checkHasOpera('export') && $this->context->checkHasOperaPower('export')) { ?>
    <a href="javascript:;" class="btn green btn-sm" onclick="this.listtable.export()"> <?= Yii::t('app', '导出') ?> </a>
<? } ?>

<? if ($_GET['sListKey'] == 'Main.Shop.SellerWithdrawLog.List.Fail') { ?>
    <a href="javascript:;" class="btn green btn-sm"  onclick="confirmation(this, '<?= Yii::t('app', '确定要确认提现？') ?>', function(obj){confirmSave(obj.listtable)})"> <?= Yii::t('app',
            '确认') ?> </a>


    <div class="note note-info margin-top-10 hide">
        <h4 class="block">提示</h4>
        <p>可能由于您的微信相关账户金额不够，导致受理异常。发生异常情况，请在线下进行转账。并点击确认。</p>
    </div>

    <script>
        function confirmSave(listtable) {
            var lLength = listtable.getSelectedLength();
            if (lLength == 0) {
                error("至少选择一条记录。");
                return;
            }

            var postData = listtable.getPostData();

            $.post
            (
                listtable.sUrl + '/confirmsave',
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

        //$(".note-info").appendTo($(".tab-content"));
    </script>

<? } ?>

<a href="javascript:;" class="btn green btn-sm" onclick="this.listtable.refresh()"> <?= Yii::t('app', '刷新') ?> </a>