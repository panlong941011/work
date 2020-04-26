<div class="breadcrumb" style="display:none">
    <h2><?=$sysObject->sName?></h2>
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

<form name="objectform" action="<?=Yii::$app->homeUrl?>/<?=strtolower($this->context->sysObject->sObjectName)?>/editoperatorsave?sObjectName=<?=$sysObject->sObjectName?>" class="horizontal-form" method="post" enctype="multipart/form-data">
<input type="hidden" name="_csrf" value="<?=Yii::$app->request->getCsrfToken()?>">
<div class="row margin-top-10">
	<div class="col-md-12">
		<div class="portlet box green">
            <div class="portlet-title">
                <div class="caption">人员 </div>
            </div>
            <div class="portlet-body">
            	<? foreach ($arrDep as $dep) { ?>                
                <div class="portlet box green">
                    <div class="portlet-title">
                        <div class="caption"><?=$dep['sName']?> </div>
                        <div class="tools">
							<a href="javascript:;" class="expand" data-original-title="" title=""> </a>
						</div>
                    </div>
                    <div id="operatable" class="portlet-body" style="display: none;">
                    
                        <a href="javascript:;" class="btn green btn-sm" onclick="checkTableAll($(this).parent())"> 全选 </a> 
                        <a href="javascript:;" class="btn green btn-sm" onclick="uncheckTableAll($(this).parent())"> 全部取消 </a>
                        <div class="table-scrollable">
                            <table class="table table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th> 名称 </th>
                                        <th> 全选 </th>
                                        <? foreach ($arrOperator as $opera) { ?>
                                        <td> <?=$opera->sName?> </td>
                                        <? } ?>
                                    </tr>
                                </thead>
                                <tbody>
                                    <? foreach ($arrUser as $user) { 
										if ($user['SysDepID'] == $dep['ID']) {
									?>
                                    <tr>
                                        <td> <?=$user['sName']?> </td>
                                        <td><div class="checker"><span><input type="checkbox" class="group-checkable checkrowall" value="<?=$tab->ID?>"></span></div></td>
                                        <? foreach ($arrOperator as $opera) { ?>
                                        <td><div class="checker"><span <? if ($arrOrgOperator['System/SysUser'][$opera->sOperator][$user['ID']]) { ?>class='checked'<? } ?>><input name="operatorSelected[System/SysUser][<?=$opera->sOperator?>][]" type="checkbox" class="group-checkable checkbox" value="<?=$user['ID']?>" <? if ($arrOrgOperator['System/SysUser'][$opera->sOperator][$user['ID']]) { ?>checked<? } ?>></span></div></td>
                                        <? } ?>
                                    </tr>
                                    <? }} ?>
                                </tbody>
                            </table>                            
                        </div>
					</div>
				</div>
                <? } ?>
            </div>
        </div>
    </div>
</div>
<div class="row margin-top-10">
	<div class="col-md-12">
		<div class="portlet box green">
            <div class="portlet-title">
                <div class="caption">角色 </div>
                <div class="tools">
                    <a href="javascript:;" class="expand" data-original-title="" title=""> </a>
                </div>                
            </div>
            <div id="operatable" class="portlet-body" style="display: none;">
				<a href="javascript:;" class="btn green btn-sm" onclick="checkTableAll($(this).parent())"> 全选 </a> 
				<a href="javascript:;" class="btn green btn-sm" onclick="uncheckTableAll($(this).parent())"> 全部取消 </a>
                <div class="table-scrollable">
                    <table class="table table-bordered table-hover">
                        <thead>
                            <tr>
                                <th> 名称 </th>
                                <th> 全选 </th>
                                <? foreach ($arrOperator as $opera) { ?>
                                <td> <?=$opera->sName?> </td>
                                <? } ?>
                            </tr>
                        </thead>
                        <tbody>
                            <? foreach ($arrRole as $role) { ?>
                            <tr>
                                <td> 
								
                                <? 
								for($i=0;$i<count(explode('/', $role['PathID']))-2; $i++) {
									echo "&nbsp;&nbsp;&nbsp;"; 
								}
								?>
                                
								<?=$role['sName']?> 
                                
                                </td>
                                <td><div class="checker"><span><input type="checkbox" class="group-checkable checkrowall" value="<?=$tab->ID?>"></span></div></td>
                                <? foreach ($arrOperator as $opera) { ?>
								<td><div class="checker"><span <? if ($arrOrgOperator['System/SysRole'][$opera->sOperator][$role['ID']]) { ?>class='checked'<? } ?>><input name="operatorSelected[System/SysRole][<?=$opera->sOperator?>][]" type="checkbox" class="group-checkable checkbox" value="<?=$role['ID']?>" <? if ($arrOrgOperator['System/SysRole'][$opera->sOperator][$role['ID']]) { ?>checked<? } ?>></span></div></td>
                                <? } ?>
                                
                            </tr>
                            <? } ?>
                        </tbody>
                    </table>
                </div>
            </div>
		</div>
	</div>
</div>
<div class="row margin-top-10">
	<div class="col-md-12">
        <div class="portlet box green">
            <div class="portlet-title">
                <div class="caption">部门 </div>
                <div class="tools">
                    <a href="javascript:;" class="expand" data-original-title="" title=""> </a>
                </div>                
               
            </div>
            <div id="operatable" class="portlet-body" style="display: none;">
				<a href="javascript:;" class="btn green btn-sm" onclick="checkTableAll($(this).parent())"> 全选 </a> 
				<a href="javascript:;" class="btn green btn-sm" onclick="uncheckTableAll($(this).parent())"> 全部取消 </a>            
                <div class="table-scrollable">
                    <table class="table table-bordered table-hover">
                        <thead>
                            <tr>
                                <th> 名称 </th>
                                <th> 全选 </th>
                                <? foreach ($arrOperator as $opera) { ?>
                                <td> <?=$opera->sName?> </td>
                                <? } ?>
                            </tr>
                        </thead>
                        <tbody>
                            <? foreach ($arrDep as $dep) { ?>
                            <tr>
                                <td> <?=$dep['sName']?> </td>
                                <td><div class="checker"><span><input type="checkbox" class="group-checkable checkrowall" value="<?=$tab->ID?>"></span></div></td>
                                <? foreach ($arrOperator as $opera) { ?>
								<td><div class="checker"><span <? if ($arrOrgOperator['System/SysDep'][$opera->sOperator][$dep['ID']]) { ?>class='checked'<? } ?>><input name="operatorSelected[System/SysDep][<?=$opera->sOperator?>][]" type="checkbox" class="group-checkable checkbox" value="<?=$dep['ID']?>" <? if ($arrOrgOperator['System/SysDep'][$opera->sOperator][$dep['ID']]) { ?>checked<? } ?>></span></div></td>
                                <? } ?>
                                
                            </tr>
                            <? } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
	</div>
</div>
<div class="row margin-top-10">
	<div class="col-md-12">
        <div class="portlet box green">
            <div class="portlet-title">
                <div class="caption">团队 </div>
                <div class="tools">
                    <a href="javascript:;" class="expand" data-original-title="" title=""> </a>
                </div>                
               
            </div>
            <div id="operatable" class="portlet-body" style="display: none;">
				<a href="javascript:;" class="btn green btn-sm" onclick="checkTableAll($(this).parent())"> 全选 </a> 
				<a href="javascript:;" class="btn green btn-sm" onclick="uncheckTableAll($(this).parent())"> 全部取消 </a>            
                <div class="table-scrollable">
                    <table class="table table-bordered table-hover">
                        <thead>
                            <tr>
                                <th> 名称 </th>
                                <th> 全选 </th>
                                <? foreach ($arrOperator as $opera) { ?>
                                <td> <?=$opera->sName?> </td>
                                <? } ?>
                            </tr>
                        </thead>
                        <tbody>
                            <? foreach ($arrTeam as $team) { ?>
                            <tr>
                                <td> <?=$team['sName']?> </td>
                                <td><div class="checker"><span><input type="checkbox" class="group-checkable checkrowall"></span></div></td>
                                <? foreach ($arrOperator as $opera) { ?>
								<td><div class="checker"><span <? if ($arrOrgOperator['System/SysTeam'][$opera->sOperator][$team['ID']]) { ?>class='checked'<? } ?>><input name="operatorSelected[System/SysTeam][<?=$opera->sOperator?>][]" type="checkbox" class="group-checkable checkbox" value="<?=$team['ID']?>" <? if ($arrOrgOperator['System/SysTeam'][$opera->sOperator][$team['ID']]) { ?>checked<? } ?>></span></div></td>
                                <? } ?>
                                
                            </tr>
                            <? } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
	</div>
</div>

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