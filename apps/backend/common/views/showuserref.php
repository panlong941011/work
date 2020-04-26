<link href="<?=Yii::$app->homeUrl?>/js/global/plugins/bootstrap-daterangepicker/daterangepicker.min.css" rel="stylesheet" type="text/css" />
<script src="<?=Yii::$app->homeUrl?>/js/global/plugins/moment.js" type="text/javascript"></script>
<script src="<?=Yii::$app->homeUrl?>/js/global/plugins/bootstrap-daterangepicker/daterangepicker.min.js" type="text/javascript"></script>
<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
    <h4 class="modal-title"><?=$sysObject->sName?></h4>
</div>
<div class="modal-body">
	<div class="row">
    	<div class="col-md-12">
            <select id="UserID" class="form-control">
				<? foreach($arrDep as $dep) { ?>
                <optgroup label="<?=$dep['sName']?>">
                	<? if ($dep['arrUser']) { ?>
                    <? foreach ($dep['arrUser'] as $user) { ?>
                    <option value="<?=$user['ID']?>" <? if($_POST['ID'] === $user['ID']) { ?>selected="selected"<? } ?>><?=$user['sName']?></option>
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
$('#UserID').selectsplitter({
	selectSize: 10
});

function ok()
{
	if($('select[data-selectsplitter-secondselect-selector] option:selected').size() == 0)
	{
		error('<?=Yii::t('app', '请选择一个人员')?>');
		return false;
	}
	
	$.post
	(
		'<?=Yii::$app->homeUrl?>/<?=strtolower($sysObject->sObjectName)?>/refsave',
		{ID:$('#UserID').val(), sObjectName:'<?=$_GET['sObjectName']?>', sFieldAs:'<?=$_GET['sFieldAs']?>'},
		function (data) 
		{
			eval("var data="+data);
			refSave('<?=$_GET['sObjectName']?>', '<?=$_GET['sFieldAs']?>', data);
			closeModal();
		}
	);
}

clearToastr();
</script>