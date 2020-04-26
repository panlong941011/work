<? if ($this->context->checkHasOperaPower('new')) { ?>
    <a href="javascript:;" class="btn green btn-sm"
       onclick="this.listtable.new('<?= Yii::t('app', '充值提现') ?> <?= $this->context->sysObject->sName ?>')"> <?= Yii::t('app', '充值申请') ?> </a>
<? } ?>
<? if ($this->context->checkHasOpera('export') && $this->context->checkHasOperaPower('export')) { ?>
    <a href="javascript:;" class="btn green btn-sm" onclick="this.listtable.export()"> <?= Yii::t('app', '导出') ?> </a>
<? } ?>

<a href="javascript:;" class="btn green btn-sm" onclick="this.listtable.refresh()"> <?= Yii::t('app', '刷新') ?> </a>
