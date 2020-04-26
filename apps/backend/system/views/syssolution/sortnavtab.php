<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
    <h4 class="modal-title"><?=Yii::t('app', '分类排序')?></h4>
</div>
<div class="modal-body">
	<div class="row">  
        <div class="col-md-12">
            <div class="portlet light bordered">
                <div class="portlet-body">
                    <div class="dd" id="nestable_list_2" style="height:330px; overflow:auto">
                        <ol class="dd-list">
							<? foreach ($arrNavtab as $navTab) { ?>
                            <li class="dd-item" data-id="<?=$navTab->ID?>">
                                <div class="dd-handle"> <?=$navTab->sName?> </div>
                            </li>
                            <? } ?>
                        </ol>
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
		sHomeUrl+'/system/syssolution/sortnavtabsave',
		{sSelectItem:sSelectItem},
		function(data)
		{
			var ret = jQuery.parseJSON(data);
			
			if (ret.bSuccess) {
				location.reload(true);
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


// activate Nestable for list 2
$('#nestable_list_2').nestable({
	group: 1
}).on('change', updateOutput);

updateOutput($('#nestable_list_2').data('output', $('#nestable_list_2_output')));
$('.dd').nestable('collapseAll');
</script>