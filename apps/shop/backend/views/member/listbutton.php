<? if ($this->context->checkHasOperaPower('new')) { ?>
    <a href="javascript:;" class="btn green btn-sm" onclick="this.listtable.new('<?= Yii::t('app',
        '新建') ?> <?= $this->context->sysObject->sName ?>')"> <?= Yii::t('app', '新建') ?> </a>
<? } ?>
<? if (0 && $this->context->checkHasOperaPower('edit')) { ?>
    <a href="javascript:;" class="btn green btn-sm" onclick="this.listtable.edit('<?= Yii::t('app',
        '编辑') ?> <?= $this->context->sysObject->sName ?>')"> <?= Yii::t('app', '编辑') ?> </a>
<? } ?>

<? if ($this->context->checkHasOperaPower('export')) { ?>
    <a href="javascript:;" class="btn green btn-sm" onclick="this.listtable.export()"> <?= Yii::t('app', '导出') ?> </a>
<? } ?>

<? if ($this->context->checkHasOperaPower('levelup')) { ?>
    <a href="javascript:;" class="btn green btn-sm" onclick="levelup(this.listtable)"> <?= Yii::t('app',
            '升级成经销商') ?> </a>
<? } ?>

<a href="javascript:;" class="btn green btn-sm" onclick="this.listtable.refresh()"> <?= Yii::t('app', '刷新') ?> </a>

<script>
    function levelup(listtable) {
        var ID = listtable.getFirstSelected();
        var SelectedLength = listtable.getSelectedLength();
        if (SelectedLength == 0) {
            error('必须选择一条记录。');
            return;
        } else if (SelectedLength > 1) {
            error('只能选择一条记录。');
            return;
        }
        $.get(sHomeUrl + '/' + sObjectName + '/levelup?ID=' + ID, function (data) {
            if (!data) {
                error('该用户无法升级为经销商');
            } else {
                var modal = openModal(data, 400, 160);
            }
        });
    }
</script>