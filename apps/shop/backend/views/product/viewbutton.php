<? if (\Yii::$app->backendsession->SysRoleID == 1) { ?>
    <a href="<?= Yii::$app->homeUrl ?>/<?= strtolower($this->context->sObjectName) ?>/edit?ID=<?= $_GET['ID'] ?>"
       class="btn green btn-sm"><?= Yii::t('app', '编辑') ?></a>
    <a onclick="ship()" class="btn green btn-sm"><?= Yii::t('app', '审核') ?></a>
<? } ?>
<a href="javascript:parent.closeCurrTab()" class="btn green btn-sm"><?= Yii::t('app', '关闭') ?></a>

<a href="<?= Yii::$app->homeUrl ?>/<?= strtolower($this->context->sObjectName) ?>/view?ID=<?= $_GET['ID'] ?>"
   class="btn green btn-sm"><?= Yii::t('app', '刷新') ?></a>
<script>
    function ship() {
        $.get('<?=Yii::$app->homeUrl?>/<?=strtolower($this->context->sObjectName)?>/check', {ID: '<?=$_GET['ID']?>'}, function (data) {
            console.log(data);
            if (data.bSuccess) {
                openModal(data.data, 800, 400)
            } else {
                error(data.sMsg);
            }


        });
    }

</script>