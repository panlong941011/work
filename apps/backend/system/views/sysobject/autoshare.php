<div class="breadcrumb" style="display:none">
    <h2><?=$sysObject->sName?></h2>
    <h3><?=Yii::t('app', '查看自动共享')?></h3>
</div>

<form name="objectform" action="<?=Yii::$app->homeUrl?>/<?=strtolower($this->context->sysObject->sObjectName)?>/del?sObjectName=<?=$sysObject->sObjectName?>" class="horizontal-form" method="post" enctype="multipart/form-data">
<input type="hidden" name="_csrf" value="<?=Yii::$app->request->getCsrfToken()?>">
<div class="row margin-top-10">
    <div class="col-md-12">
        <!-- BEGIN EXAMPLE TABLE PORTLET-->
        <div class="portlet light bordered">
            <div class="portlet-title">
                <div class="caption font-dark">
                    <i class="icon-settings font-dark"></i>
                    <span class="caption-subject bold uppercase"> <?=$sysObject->sName?></span>
                </div>
            </div>
            <div class="portlet-body">
                <div class="table-toolbar">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="btn-group">   
                                <button type="button" class="btn green" onclick="location.href=sHomeUrl+'/system/sysobject/newautoshare?sObjectName=<?=$sysObject->sObjectName?>'"><?=Yii::t('app', '新建')?></button>   
                                <button type="button" class="btn green" onclick="edit('<?=$sysObject->sObjectName?>')"><?=Yii::t('app', '编辑')?></button>   
                                <button type="button" class="btn green" onclick="del()"><?=Yii::t('app', '删除')?></button>  
                                <button type="button" class="btn green" onclick="location.reload(true)"><?=Yii::t('app', '刷新')?></button> 
                            </div>
                        </div>
                    </div>
                </div>
                <div class="table-scrollable">
                <table class="table table-striped table-bordered table-hover table-checkable order-column dataTable no-footer">
                    <thead>
                        <tr role="row">
                        	<th style="width: 71px;">
                                <div class="checker"><span><input type="checkbox" class="group-checkable"></span></div> 
							</th>
                            <th><?=Yii::t('app', '源团队')?></th>
                            <th><?=Yii::t('app', '源角色')?></th>
                            <th><?=Yii::t('app', '源部门')?></th>
                            <th><?=Yii::t('app', '源包含下级')?></th>
                            <th><?=Yii::t('app', '共享给部门')?></th>
                            <th><?=Yii::t('app', '共享给角色')?></th>
                            <th><?=Yii::t('app', '共享给团队')?></th>
                            <th><?=Yii::t('app', '共享给包含下级')?></th>
                            <th><?=Yii::t('app', '共享模式')?></th>
						</tr>		
                    </thead>
                    <tbody>
                    	<? foreach ($arrAutoShare as $autoshare) { ?>
                        <tr>
                            <td> <div class="checker"><span><input type="checkbox" name="selected[]" class="checkboxes" value="<?=$autoshare->ID?>"></span></div> </td>
                            <td> <?=$autoshare->fromteam->sName?> </td>
                            <td> <?=$autoshare->fromrole->sName?> </td>
                            <td> <?=$autoshare->fromdep->sName?> </td>
                            <td> <? if ($autoshare->bFromInclude) { ?><i class="fa fa-check font-green-jungle"></i><? } else { ?>&nbsp;<? } ?> </td>
                            <td> <?=$autoshare->todep->sName?> </td>
                            <td> <?=$autoshare->torole->sName?> </td>
                            <td> <?=$autoshare->toteam->sName?> </td>
                            <td> <? if ($autoshare->bToInclude) { ?><i class="fa fa-check font-green-jungle"></i><? } else { ?>&nbsp;<? } ?> </td>
                            <td> 
                            	<? 
								if ($autoshare->sToken == 'ref') { 
									echo "参照";
								} elseif ($autoshare->sToken == 'view') { 
									echo "公共只读";
								} elseif ($autoshare->sToken == 'view,edit,ref') { 
									echo "公共读写";
								} elseif ($autoshare->sToken == 'view,edit,del,ref') { 
									echo "管理者";
								}
								?>
                            </td>
                        </tr>
                        <? } ?>
                        
                    </tbody>
                </table></div></div>
            </div>
        </div>
        <!-- END EXAMPLE TABLE PORTLET-->
    </div>
</div>


</form>
<script>
function del()
{
	if ($("input:checked[name='selected[]']").size() == 0) {
    	error("请选择至少一条记录。");
        return;
    }

	document.objectform.submit();
}

function edit()
{
	if ($("input:checked[name='selected[]']").size() == 0) {
    	error("请选择一条记录。");
        return;
    } else if ($("input:checked[name='selected[]']").size() > 1) {
    	error("只能选择一条记录。");
        return;		
	}
	
	location.href=sHomeUrl+'/system/sysobject/editautoshare?ID='+$("input:checked[name='selected[]']").val()+'&sObjectName=<?=$sysObject->sObjectName?>'
}

$(".group-checkable").click
(
	function()
    {
    	if ($(this).attr('checked')) {
        	$(".checker span").find("input").attr('checked', false);
			$(".checker span").removeClass("checked");
            $(this).attr('checked', false);
            $(this).parent().removeClass("checked");
        } else {
			$(".checker span").find("input").attr('checked', true);
			$(".checker span").addClass("checked");
            $(this).attr('checked', true);
            $(this).parent().addClass("checked");
        }
    }
);

$("tbody .checker").click
(
	function()
	{
		if ($(this).find("input").attr('checked')) {
			$(this).find("input").attr('checked', false);
			$(this).find("span").removeClass("checked");
		} else {
			$(this).find("input").attr('checked', true);
			$(this).find("span").addClass("checked");
		}

	}	
);

</script>