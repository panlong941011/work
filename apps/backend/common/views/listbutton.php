<? if ($this->context->checkHasOpera('new') && $this->context->checkHasOperaPower('new')) { ?>
    <a href="javascript:;" class="btn green btn-sm"
       onclick="this.listtable.new('<?= Yii::t('app', '新建') ?> <?= $this->context->sysObject->sName ?>')"> <?= Yii::t('app', '新建') ?> </a>
<? } ?>

<? if ($this->context->checkHasOpera('del') && $this->context->checkHasOperaPower('del')) { ?>
    <a href="javascript:;" class="btn green btn-sm"
       onclick="confirmation(this, '<?= Yii::t('app', '确定要删除？') ?>', function(obj){obj.listtable.del()})"> <?= Yii::t('app', '删除') ?> </a>
<? } ?>

<? if ($bOwnerIDExists&&1==0) { ?>
    <a href="javascript:;" class="btn green btn-sm hide"
       onclick="this.listtable.manualshare()"> <?= Yii::t('app', '共享') ?> </a>
    <? if ($this->context->checkHasOpera('changeOwner') && $this->context->checkHasOperaPower('changeOwner')) { ?>
        <a class="btn green btn-sm" onclick="this.listtable.changeOwner()"
           data-toggle="modal"> <?= Yii::t('app', '更改拥有者') ?> </a>
    <? } ?>
<? } ?>

<? if ($this->context->checkHasOpera('export') && $this->context->checkHasOperaPower('export')) { ?>
    <a href="javascript:;" class="btn green btn-sm" onclick="this.listtable.export()"> <?= Yii::t('app', '导出') ?> </a>
<? } ?>

<a href="javascript:;" class="btn green btn-sm" onclick="this.listtable.refresh()"> <?= Yii::t('app', '刷新') ?> </a>