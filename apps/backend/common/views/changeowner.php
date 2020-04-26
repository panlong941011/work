<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
    <h4 class="modal-title"><?=Yii::t('app', '更改拥有者')?></h4>
</div>
<div class="modal-body">
	<div class="row">
    	<div class="col-md-12">
            <select id="OwnerID" class="form-control">
				<? foreach($arrDep as $dep) { ?>
                <optgroup label="<?=$dep['sName']?>">
                	<? if ($dep['arrUser']) { ?>
                    <? foreach ($dep['arrUser'] as $user) { ?>
                    <option value="<?=$user['lID']?>" <? if($OwnerID === $user['lID']) { ?>selected="selected"<? } ?>><?=$user['sName']?></option>
					<? }} ?>
                </optgroup>
                <? } ?>
            </select>
        </div>
    </div>
</div>
<div class="modal-footer">
    <button type="button" onclick="closeModal()" class="btn btn-outline dark"><?=Yii::t('app', '取消')?></button>
    <button type="button" class="btn green" onclick="ok()"><?=Yii::t('app', '确定')?></button>
</div>
<script src="<?=Yii::$app->homeUrl?>/js/global/plugins/bootstrap-selectsplitter/bootstrap-selectsplitter.min.js" type="text/javascript"></script>
<script>
$('#OwnerID').selectsplitter({
	selectSize: 10
});

function ok()
{
	if($('select[data-selectsplitter-secondselect-selector] option:selected').size() == 0)
	{
		error('<?=Yii::t('app', '请选择一个人员做为新的拥有者。')?>');
		return false;
	}
	
	<? if (!$_GET['ID']) { ?>
	var ListTable = $('#myermmodal').prop('listtable');
	ListTable.changeOwnerSave($('#OwnerID').val());
	<? } else { ?>
	changeOwnerSave('<?=$_GET['ID']?>', $('#OwnerID').val());
	<? } ?>
}
</script>