<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
    <h4 class="modal-title"><?=Yii::t('app', '部门层级')?></h4>
</div>
<div class="modal-body">
	<div class="row">
    	<div class="col-md-12" id="chart"></div>
    </div>
</div>
<div class="col-md-12" id="org" style="display:none">
    <?=$sCharDiv?>
</div>
<div class="modal-footer">
    <button type="button" class="btn green" onclick="closeModal()"><?=Yii::t('app', '关闭')?></button>
</div>
<script src="<?=Yii::$app->homeUrl?>/js/global/plugins/bootstrap-selectsplitter/bootstrap-selectsplitter.min.js" type="text/javascript"></script>
<link rel="stylesheet" href="<?=Yii::$app->homeUrl?>/js/global/plugins/jquery.orgchart/jquery.orgchart.css"/>
<link rel="stylesheet" href="<?=Yii::$app->homeUrl?>/js/global/plugins/jquery.orgchart/site.css?=dddd11"/>
<script type="text/javascript" src="<?=Yii::$app->homeUrl?>/js/global/plugins/jquery.orgchart/jquery.orgchart.js"></script>
<script>
$("#org>ul").orgChart(
	{container:$('#chart')}
);
window.setTimeout("$('.orgChart').css('width', $('.orgChart table').width()+20)", 1000);

</script>