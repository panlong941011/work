<div class="breadcrumb" style="display:none">
    <h2><?=$sysObject->sName?></h2>
    <h3><?=Yii::t('app', '主页')?></h3>
</div>
<div class="row">

        <? if ($sysObject->sDesc) { ?>
        <div class="note note-info margin-top-10">
            <h4 class="block"><?=Yii::t('app', '提示')?></h4>
            <p><?=$sysObject->sDesc?></p>
        </div>
        <? } ?>
    	<?=$this->context->getHomeTabs()?>

</div>



<script src="<?=Yii::$app->homeUrl?>/<?=strtolower($sysObject->sObjectName)?>/js" type="text/javascript"></script>
<script src="<?=Yii::$app->homeUrl?>/js/global/plugins/bootstrap-tabdrop/js/bootstrap-tabdrop.js" type="text/javascript"></script>
<script>
if ($(".nav-tabs").size() == 0) {
	$.get
	(
		'<?=Yii::$app->homeUrl?>/<?=strtolower($sysObject->sObjectName)?>/getlisttable?sListKey='+$("#listtable").attr('listkey')+"&sTabID="+$("#listtable").attr('listid')+"&<?=$sHttpParam?>",
		function(data)
		{
			$("#listtable").html(data);
		}
	);	
} else {
	$(".nav-tabs a[data-toggle='tab']").click
	(
		function ()
		{
			var tabId = $(this).attr("href");
			var sLinkUrl = $(this).data("slinkurl")+"&<?=$sHttpParam?>";

			if ($(tabId).size() == 0) {
				$(".tab-content").append("<div class='tab-pane active' id='"+tabId.replace('#', '')+"'></div>");
			} else {
				$(tabId).addClass("active");
			}

            $.get
            (
                sLinkUrl,
                function(data)
                {
                    $(tabId).html(data);
                }
            );
		}
	)
	
	$(document).ready
	(
		function()
		{
			if ($(".nav-tabs li.active").size() == 0) {
				$(".nav-tabs li:first").addClass('active');
			}
			
			$(".nav-tabs li.active a[data-toggle='tab']:first").click();
		}
	);
	
}


</script>