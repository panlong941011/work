<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
    <h4 class="modal-title"><?=Yii::t('app', '上传附件')?>:</h4>
</div>
<div class="modal-body">
	<div class="row">
    	<div class="col-md-12">
            <iframe id="uploadwin" name="uploadwin" src="<?=Yii::$app->homeUrl?>/<?=strtolower($this->context->sObjectName)?>/upload" frameborder="0" style="height:30px; width:100%" scrolling="no"></iframe>
        </div>
    </div>
</div>
<div class="modal-footer">
    <button type="button" onclick="closeModal()" class="btn btn-outline dark"><?=Yii::t('app', '取消')?></button>
    <button type="button" class="btn green" onclick="ok()"><?=Yii::t('app', '确定')?></button>
</div>
<script>

function ok()
{
	if(window.frames["uploadwin"].$('form input').val() == "") {
		error("<?=Yii::t('app', '请选择附件')?>");
		return false;
	}
	
	var sExt = window.frames["uploadwin"].$('form input').val().substr(window.frames["uploadwin"].$('form input').val().lastIndexOf(".") + 1).toLowerCase();  
	if (sExt.match(/(php|phtml|php3|php4|jsp|exe|dll|asp|cer|asa|shtml|shtm|aspx|asax|cgi|fcgi|pl|bat)/ig)) {
		error("<?=Yii::t('app', '您选择的文件非法，请重新选择。')?>");
		return false;
	}
	
	info("<?=Yii::t('app', '正在上传附件。。。')?>");
	
	window.frames["uploadwin"].$('form').attr('action', '<?=Yii::$app->homeUrl?>/<?=strtolower($this->context->sObjectName)?>/upload?sField=<?=$_GET['sFieldAs']?>').submit();
	
	return true;
}

function save(data)
{
	uploadSave('<?=$_GET['sObjectName']?>', '<?=$_GET['sFieldAs']?>', '<?=$_GET['sLinkField']?>', data);
	clearToastr();
	closeModal();
}

clearToastr();
</script>