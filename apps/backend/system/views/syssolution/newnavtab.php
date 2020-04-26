<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
    <h4 class="modal-title"><?=Yii::t('app', '新建菜单')?></h4>
</div>
<div class="modal-body">
	<div class="row">
    	<div class="col-md-12">
            <div class="form-group">
                <label class="control-label"><?=Yii::t('app', '菜单名称')?>:</label>
                <input type="text" id="sName" class="form-control" placeholder="<?=Yii::t('app', '请输入菜单名称')?>">
            </div>
        </div>
    </div>  
	<div class="row">
    	<div class="col-md-12">
                <label class="control-label"><?=Yii::t('app', '菜单项')?>:</label>
        </div>    
        <div class="col-md-6">
            <div class="portlet light bordered">
                <div class="portlet-body ">
                	<label class="control-label"><?=Yii::t('app', '未选项')?></label>
                    <div class="dd" id="nestable_list_1" style="height:330px; overflow:auto">
                        <ol class="dd-list">
                        	<? foreach ($arrModule as $module) { ?>
                            <? if ($arrNavItem[$module->ID]) { ?>
                            <li class="dd-item" data-id="">
                                <div class="dd-handle"> <?=$module->sName?> </div>
                                <ol class="dd-list">
                                	<? 
									ksort($arrNavItem[$module->ID]);									
									foreach ($arrNavItem[$module->ID] as $sFirstPY => $arrItem) { ?>                                    
                                        <li class="dd-item" data-id="">
                                            <div class="dd-handle"> <?=$sFirstPY?> </div>
                                            <ol class="dd-list">
												<? 
												ksort($arrItem);	
												foreach ($arrItem as $item) { 
												?>
                                                <li class="dd-item" data-id="<?=$item->ID?>">
                                                    <div class="dd-handle"> <?=$item->sName?> </div>
                                                </li>
                                                <? } ?>
											</ol>
                                        </li>
                                    <? } ?>
                                </ol>                                
                            </li> 
                            <? }} ?>
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
                        <div class="dd-empty"></div>
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
	if ($(".modal-body #sName").val() == "") {
		error('<?=Yii::t('app', '请输入菜单名称')?>');	
		return false;		
	}
	
	if ($("#nestable_list_2 .dd-item").size() == 0) {
		error('<?=Yii::t('app', '请选择菜单项')?>');	
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
		sHomeUrl+'/system/syssolution/newnavtabsave?HeadingID=<?=$_GET['HeadingID']?>&SolutionID=<?=$SolutionID?>',
		{sName:$(".modal-body #sName").val(), sSelectItem:sSelectItem},
		function(data)
		{
			var ret = jQuery.parseJSON(data);
			
			if (ret.bSuccess) {
				location.href="<?=Yii::$app->homeUrl?>/<?=strtolower($this->context->sObjectName)?>/view?ID=<?=$SolutionID?>";
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