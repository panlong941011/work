<h3 class="form-section"><?=Yii::t('app', '团队成员')?></h3>
<div class="row">
    <div class="form-group  col-md-4">
        <label class="control-label col-md-3"><?=Yii::t('app', '成员')?>:</label>
        <div class="col-md-9">
    		<input type="text" class="form-control" placeholder="" value="<?=$data?>" id="object_tagsinput">
    	</div>
    </div> 
</div>  
<div class="row">
    <div class="form-group  col-md-4">
        <label class="control-label col-md-3"></label>
        <div class="col-md-9">
    		<select class="form-control input-large" id="depusers">
				<? foreach($arrDep as $dep) { ?>
                <optgroup label="<?=$dep['sName']?>">
                	<? if ($dep['arrUser']) { ?>
                    <? foreach ($dep['arrUser'] as $user) { ?>
                    <option value="<?=$user['lID']?>" <? if($OwnerID === $user['lID']) { ?>selected="selected"<? } ?>><?=$user['sName']?></option>
					<? }} ?>
                </optgroup>
                <? } ?>
			</select>
            <div class="margin-top-10">
				<a href="javascript:;" class="btn red" id="object_tagsinput_add"><?=Yii::t('app', '添加成员')?></a>
			</div>                                                        
    	</div>
    </div> 
</div>
<link href="<?=Yii::$app->homeUrl?>/js/global/plugins/bootstrap-tagsinput/bootstrap-tagsinput.css" rel="stylesheet" type="text/css" />
<script src="<?=Yii::$app->homeUrl?>/js/global/plugins/bootstrap-tagsinput/bootstrap-tagsinput.min.js" type="text/javascript"></script>
<script>
var elt = $('#object_tagsinput');

elt.tagsinput({
  itemValue: 'value',
  itemText: 'text',
});

<? foreach ($arrTeamUser as $user) { ?>
elt.tagsinput('add', { "value": <?=$user->sysuser->lID?> , "text": "<?=$user->sysuser->sName?>" });
<? } ?>

$('#object_tagsinput_add').on('click', function(){
	elt.tagsinput('add', { 
		"value": $('#depusers').val(), 
		"text": $('#depusers option:selected').text(), 
	});
});

function beforeObjectSubmit()
{
	var arrItem = $('#object_tagsinput').tagsinput('items');
	
	if (arrItem.length == 0) {
		error("<?=Yii::t('app', '请选择团队的成员。')?>");
	}
	
	for (var i=0; i<arrItem.length; i++) {
		$(document.objectform).append("<input type='hidden' name='arrTeamUser[]' value='"+arrItem[i].value+"' />");
	}
	
	
	return true;
}

</script>