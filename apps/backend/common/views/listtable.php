<? if ($list['sNote']) { ?>
    <div class="note note-info margin-top-10">
        <h4 class="block"><?= Yii::t('app', '提示') ?></h4>
        <p><?= $list['sNote'] ?></p>
    </div>
<? } ?>
<div class="portlet light bordered" id="listtable-<?=$_GET['sTabID'] ? $_GET['sTabID'] : $list->ID?>">
	<? if ($list->bShowTitle) { ?>
    <div class="portlet-title">
        <div class="caption font-dark">
            <i class="icon-settings font-dark"></i>
            <span class="caption-subject bold uppercase"><?=$sysObject->sName?></span>
        </div>
    </div>
    <? } ?>
    <div class="portlet-body">
        <div class="table-toolbar">
            <div class="row">
                <div class="col-md-9" id="btngroup">
                    <?=$this->context->getListButton($list->sKey)?>
                </div>
                <div class="col-md-3">
                    <div class="btn-group pull-right">
                        <button class="btn red hide" type="button" id="advancedBtn"> <?=Yii::t('app', '高级搜索')?>
                            <i class="fa fa-angle-down"></i>
                        </button>											
                    </div>
                </div>
            </div>
        </div>
        <div class="search-toolbar" style="display:no1ne">
            <?=$this->context->getAdvancedSearch($list->sKey)?>
        </div>                            
        <div class="dataTables_wrapper no-footer" id="listtable-container">
    		

            
        </div>
    </div>
</div>
<script src="<?=Yii::$app->homeUrl?>/js/pages/scripts/listtable.js" type="text/javascript"></script>
<script src="<?=Yii::$app->homeUrl?>/js/global/plugins/jquery-scrollTo/jquery.scrollTo.js" type="text/javascript"></script>
<script>
var config = {
	sUrl : '<?=Yii::$app->homeUrl?>/<?=strtolower($sysObject->sObjectName)?>',/*必填*/
	container : $("#listtable-<?=$_GET['sTabID'] ? $_GET['sTabID'] : $list->ID?>"),/*必填*/
	sDataUrl : '<?=Yii::$app->homeUrl?>/<?=strtolower($sysObject->sObjectName)?>/getlistdata',/*必填*/
	ListID : '<?=$list->ID?>',/*必填*/
	sListKey : '<?=$list->sKey?>',/*必填*/
	bSingle : <?=$list->bSingle ? "1" : "0"?>,
	sExtra : '<?=$_GET['sExtra']?>'/*额外字段，用于给视图传特殊的值用于检索*/
}

var listtable = new Array();
listtable['<?=$_GET['sTabID'] ? $_GET['sTabID'] : $list->ID?>'] = new ListTable(config);	
listtable['<?=$_GET['sTabID'] ? $_GET['sTabID'] : $list->ID?>'].loadData();
</script>
