<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
    <h4 class="modal-title"><?=Yii::t('app', '应用工作台方案')?></h4>
</div>
<div class="modal-body">
	<div class="row">  
        <div class="col-md-6">
            <div class="portlet light bordered">
                <div class="portlet-body ">
                	<label class="control-label"><?=Yii::t('app', '未选项')?></label>
                    <div class="dd" id="nestable_list_1" style="height:330px; overflow:auto">
                        <ol class="dd-list">
                        	<? foreach($arrDep as $dep) { ?>
                            <li class="dd-item" data-id="">
                                <div class="dd-handle"> <?=$dep['sName']?> </div>
                                <ol class="dd-list">
                					<? if ($dep['arrUser']) { ?>
                    				<? foreach ($dep['arrUser'] as $user) { ?>
                                    <? if (!$arrSelected[$user['lID']]) { ?>
                                    <li class="dd-item" data-id="<?=$user['lID']?>">
                                        <div class="dd-handle"> <?=$user['sName']?> </div>
                                    </li>
									<? }}} ?>
                                </ol>    
                                                    
                            </li>                            
                            <? } ?>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="portlet light bordered">
                <div class="portlet-body">
                	<label class="control-label"><?=Yii::t('app', '已选项')?></label>
                    <div class="dd" id="nestable_list_2" style="height:330px; overflow:auto">
                    	<? if ($arrSelected) { ?>
                        <ol class="dd-list">
							<? foreach ($arrSelected as $user) { ?>
                            <li class="dd-item" data-id="<?=$user['lID']?>">
                                <div class="dd-handle"> <?=$user['sName']?> </div>
                            </li>
                            <? } ?>
                        </ol>
                        <? } else { ?>
                            <div class="dd-empty"></div>
						<? } ?>
                    </div>
                </div>
            </div>
        </div>
	</div>
</div>
<div class="modal-footer">
    <button type="button" onclick="closeModal()" class="btn btn-outline dark"><?=Yii::t('app', '取消')?></button>
    <button type="button" class="btn green" onclick="ok()"><?=Yii::t('app', '确定')?></button>
</div>
<link href="<?=Yii::$app->homeUrl?>/js/global/plugins/jquery-nestable/jquery.nestable.css" rel="stylesheet" type="text/css" />
<script src="<?=Yii::$app->homeUrl?>/js/global/plugins/jquery-nestable/jquery.nestable.js" type="text/javascript"></script>
<script>
clearToastr()

function ok()
{
	if ($("#nestable_list_2 .dd-item").size() == 0) {
		error('<?=Yii::t('app', '请选择需要应用的人员。')?>');	
		return false;			
	}
	
	
	var sSelectItem = "";
	var sComm = "";
	$("#nestable_list_2 .dd-item").each
	(
		function() 
		{
			if ($(this).data('id') == "") {
				return;
			}
			
			sSelectItem += sComm + $(this).data('id');
			sComm = ";";
		}
	);	
	
	
	$.post(
		sHomeUrl+'/system/syssolution/applysave?SolutionID=<?=$_GET['ID']?>',
		{sSelectItem:sSelectItem},
		function(data)
		{
			var ret = jQuery.parseJSON(data);
			
			if (ret.bSuccess) {
				location.href="<?=Yii::$app->homeUrl?>/<?=strtolower($this->context->sObjectName)?>/view?ID=<?=$_GET['ID']?>";
			} else {
				error(ret.sMsg);
			}
		}
	)
}

var updateOutput = function (e) {
	var list = e.length ? e : $(e.target),
		output = list.data('output');
	if (window.JSON) {
		output.val(window.JSON.stringify(list.nestable('serialize'))); //, null, 2));
	} else {
		output.val('JSON browser support required for this demo.');
	}
};

$('#nestable_list_1').nestable({
	group: 1
}).on('change', updateOutput);

// activate Nestable for list 2
$('#nestable_list_2').nestable({
	group: 1
}).on('change', updateOutput);

updateOutput($('#nestable_list_1').data('output', $('#nestable_list_1_output')));
updateOutput($('#nestable_list_2').data('output', $('#nestable_list_2_output')));
$('.dd').nestable('collapseAll');
</script>