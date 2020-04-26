<? if ($this->context->checkHasOpera('del') && $this->context->checkHasOperaPower('new')) { ?>
    <a href="javascript:;" class="btn green btn-sm"
       onclick="this.listtable.new('<?= Yii::t('app', '新建') ?> <?= $this->context->sysObject->sName ?>')"> <?= Yii::t('app', '新建') ?> </a>
<? } ?>

<? if ($this->context->checkHasOperaPower('new')) { ?>
    <a href="javascript:;" class="btn green btn-sm"
       onclick="this.listtable.clone('<?= Yii::t('app', '复制') ?> <?= $this->context->sysObject->sName ?>')"> <?= Yii::t('app', '复制') ?> </a>
<? } ?>

<? if ($this->context->checkHasOpera('del') && $this->context->checkHasOperaPower('del')) { ?>
    <a href="javascript:;" class="btn green btn-sm"
       onclick="confirmation(this, '<?= Yii::t('app', '确定要删除？') ?>', function(obj){obj.listtable.del()})"> <?= Yii::t('app', '删除') ?> </a>
<? } ?>

<? if ($this->context->checkHasOperaPower('on')) { ?>
    <a href="javascript:;" class="btn green btn-sm"
       onclick="on(this.listtable)"> <?= Yii::t('app', '上架') ?> </a>
<? } ?>

<? if ($this->context->checkHasOperaPower('off')) { ?>
    <a href="javascript:;" class="btn green btn-sm"
       onclick="off(this.listtable)"> <?= Yii::t('app', '下架') ?> </a>
<? } ?>


<? if ($bOwnerIDExists) { ?>
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

<a href="javascript:;" class="btn green btn-sm" style="display: none"
        onclick="confirmation(this, '<?= Yii::t('app', '确定要同步？') ?>', function(obj){grounding(obj.listtable)})">
	<?= Yii::t('app', '同步') ?>
</a>

<a href="javascript:;" class="btn green btn-sm" onclick="this.listtable.refresh()"> <?= Yii::t('app', '刷新') ?> </a>




<script>
    function on(listtable) {
        var lLength = listtable.getSelectedLength();
        if (lLength == 0) {
            error("至少选择一条记录。");
            return;
        }

        var postData = listtable.getPostData();

        $(listtable.container).mask('');

        $.post
        (
            listtable.sUrl+'/on',
            postData,
            function(data)
            {
                eval(data);
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
    
    function off(listtable) {
        var lLength = listtable.getSelectedLength();
        if (lLength == 0) {
            error("至少选择一条记录。");
            return;
        }

        var postData = listtable.getPostData();

        $(listtable.container).mask('');

        $.post
        (
            listtable.sUrl+'/off',
            postData,
            function(data)
            {
                eval(data);
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
<script>
    function grounding(listtable) {
        var lLength = listtable.getSelectedLength();
        if (lLength == 0) {
            error("至少选择一条记录。");
            return;
        }

        var postData = listtable.getPostData();

        $(listtable.container).mask('');

        $.post
        (
            listtable.sUrl + '/downloadfromlsj',
            postData,
            function (data) {
                eval(data);
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
<script>
    function choose(listtable){
        var lLength = listtable.getSelectedLength();
        if (lLength == 0) {
            error("至少选择一条记录。");
            return;
        }
        
        var postData = listtable.getPostData();
        console.log(postData);
        $(listtable.container).mask('');
        return;
        $.post
        (
            listtable.sUrl + '/downloadfromlsj',
            postData,
            function (data) {
                eval(data);
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
    
    function refund(listtable)
    {
        //新开窗口页
        parent.addTab($(this).text(), 'http://admin.dny.net/shop/product/view?ID=799')
        //弹窗
        openModal(data.data, 800, 400)
    }
</script>