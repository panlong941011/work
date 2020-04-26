<div class="breadcrumb" style="display:none">
    <h2><?=$sysUser->sName?></h2>
    <h3><?=Yii::t('app', '编辑操作权限')?></h3>
</div>

<div class="row margin-top-10">
	<div class="col-md-12">
        <button type="button" class="btn default" onclick="parent.closeCurrTab()"><?=Yii::t('app', '取消')?></button>	
        <button type="submit" class="btn green" onclick="document.objectform.submit()"><i class="fa fa-check"></i> <?=Yii::t('app', '保存')?></button>       
    </div>
</div>

<div class="row margin-top-10">
	<div class="col-md-12">
        <button type="button" class="btn green" onclick="checkAll()"> <?=Yii::t('app', '全选')?></button> 
        <button type="button" class="btn green" onclick="uncheckAll()"> <?=Yii::t('app', '全部取消')?></button>  
        
        <button type="button" class="btn green" onclick="expandAll()"> <?=Yii::t('app', '全部展开')?></button> 
        <button type="button" class="btn green" onclick="collapseAll()"> <?=Yii::t('app', '全部收起')?></button> 
    </div>
</div>

<form name="objectform" action="<?=Yii::$app->homeUrl?>/<?=strtolower($this->context->sObjectName)?>/editoperatorsave?ID=<?=$_GET['ID']?>" class="horizontal-form" method="post" enctype="multipart/form-data">
<input type="hidden" name="_csrf" value="<?=Yii::$app->request->getCsrfToken()?>">
<div class="margin-top-10"></div>
<? foreach ($arrModule as $sModuleName => $arrModuleObject) { ?> 
<div class="row">
	<div class="col-md-12">
		<div class="portlet box green">
            <div class="portlet-title">
                <div class="caption"><?=$sModuleName?> </div>
                <div class="tools">
					<a href="javascript:;" class="expand" data-original-title="" title=""> </a>
				</div>
            </div>
            <div id="operatable" class="portlet-body" style="display: none;">
                <a href="javascript:;" class="btn green btn-sm" onclick="checkTableAll($(this).parent())"> <?=Yii::t('app', '全选')?> </a>
                <a href="javascript:;" class="btn green btn-sm" onclick="uncheckTableAll($(this).parent())"> <?=Yii::t('app', '全部取消')?> </a>
                <div class="margin-top-10"></div>
                <? foreach ($arrModuleObject as $sObjectName => $sObjectChineseName) { ?>
                <table class="table table-bordered">
                        <tr>
                            <th width="100"> <?=$sObjectChineseName?> </th>
                            <td>
                                <label class="bold"><div class="checker"><span><input type="checkbox" class="group-checkable checkrowall"></span></div><?=Yii::t('app', '全选')?></label>
                                <? foreach ($arrOperator[$sObjectName] as $opera) { ?>
                                <label><div class="checker"><span <? if ($arrOrgOperator[$sObjectName][$opera->sOperator]) { ?>class='checked'<? } ?>><input type="checkbox" class="group-checkable checkbox" name="operatorSelected[<?=$sObjectName?>][]" value="<?=$opera->sOperator?>" <? if ($arrOrgOperator[$sObjectName][$opera->sOperator]) { ?>checked<? } ?>></span></div><?=$opera->sName?></label>
                                <? } ?>
                            </td>
                        </tr>
                </table>                            
                <? } ?>
            </div>
        </div>
    </div>
</div>
<? } ?>
</form>
<script src="<?=Yii::$app->homeUrl?>/<?=strtolower($this->context->sObjectName)?>/js" type="text/javascript"></script>
<script>
$("tr").each
(
	function () 
	{
		isRowAllChecked(this);	
	}
);
</script>