<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
    <h4 class="modal-title"><?=Yii::t('app', '新建分类')?></h4>
</div>
<div class="modal-body">
	<div class="row">
    	<div class="col-md-12">
            <div class="form-group">
                <label class="control-label"><?=Yii::t('app', '分类名称')?></label>
                <input type="text" id="sName" class="form-control" placeholder="<?=Yii::t('app', '请输入分类名称')?>">
            </div>
        </div>
    </div>
</div>
<div class="modal-footer">
    <button type="button" onclick="closeModal()" class="btn btn-outline dark"><?=Yii::t('app', '取消')?></button>
    <button type="button" class="btn green" onclick="ok()"><?=Yii::t('app', '确定')?></button>
</div>
<script>
clearToastr()
function ok()
{
	if ($(".modal-body #sName").val() == "") {
		error('<?=Yii::t('app', '请输入分类名称')?>');	
		return false;		
	}
	
	$.post(
		sHomeUrl+'/system/syssolution/newnavheadingsave?SolutionID=<?=$_GET['SolutionID']?>',
		{sName:$(".modal-body #sName").val()},
		function(data)
		{
			var ret = jQuery.parseJSON(data);
			
			if (ret.bSuccess) {
				location.href="<?=Yii::$app->homeUrl?>/<?=strtolower($this->context->sObjectName)?>/view?ID=<?=$_GET['SolutionID']?>";
			} else {
				error(ret.sMsg);
			}
		}
	)
}
</script>