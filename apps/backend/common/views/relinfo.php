<div class="row">
	<div class="col-md-12 detailObjectListTable">    
        <div class="portlet light bordered" id="listtable-<?=$list->ID?>">
            <div class="portlet-title">
                <div class="caption font-dark">
                    <i class="icon-settings font-dark"></i>
                    <span class="caption-subject bold uppercase"><?=$list->sysobject->sName?></span>
                </div>
            </div>
            <div class="portlet-body">
                <div class="table-toolbar">
                    <div class="row">
                        <div class="col-md-9" id="btngroup">
                            <?=$this->context->getRelInfoListButton($list->sKey)?>
                        </div>
                    </div>
                </div>
                <div class="dataTables_wrapper no-footer" id="listtable-container">
                    
        
                    
                </div>
            </div>
        </div>		
    </div>
</div>
<script src="<?=Yii::$app->homeUrl?>/js/pages/scripts/listtable.js" type="text/javascript"></script>
<script src="<?=Yii::$app->homeUrl?>/js/global/plugins/jquery-scrollTo/jquery.scrollTo.js" type="text/javascript"></script>
<script>

$(document).ready
(
	function ()
	{
		var config = {
			sUrl : '<?=Yii::$app->homeUrl?>/<?=strtolower($list->sObjectName)?>',/*必填*/
			container : $("#listtable-<?=$list->ID?>"),/*必填*/
			sDataUrl : '<?=Yii::$app->homeUrl?>/<?=strtolower($list->sObjectName)?>/getlistdata',/*必填*/
			ListID : '<?=$list->ID?>',/*必填*/
			sListKey : '<?=$list->sKey?>',/*必填*/
			bSingle : <?=$list->bSingle ? "1" : "0"?>,
			sDataRegion : 'all',
			sExtra : 'sLinkField=<?=$sLinkField?>&ID=<?=$_GET['ID']?>'/*额外字段，用于给视图传特殊的值用于检索*/
		}
		
		var listtable = new Array();
		listtable['<?=$_GET['sTabID'] ? $_GET['sTabID'] : $list->ID?>'] = new ListTable(config);	
		listtable['<?=$_GET['sTabID'] ? $_GET['sTabID'] : $list->ID?>'].loadData();	
	}
);


</script>