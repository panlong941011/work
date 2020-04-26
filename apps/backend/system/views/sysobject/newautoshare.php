<div class="breadcrumb" style="display:none">
    <h2><?=$sysObject->sName?></h2>
    <? if ($this->context->action->id == 'editautoshare') { ?>
    <h3><?=Yii::t('app', '编辑自动共享')?></h3>
    <? } else { ?>
    <h3><?=Yii::t('app', '新建自动共享')?></h3>
    <? } ?>
</div>

<div class="row" class="margin-top-10">
    <div class="col-md-12">
        <div class="portlet light bordered">
        	<div class="portlet-title">
                <div class="caption">
                    <span class="caption-subject font-green-haze bold"><?=$sysObject->sName?></span>
                </div>
                <div class="tools">
                </div>
            </div>       
            <div class="form-actions">
                <div class="row">
                    <div class="col-md-12">
                        <button type="button" class="btn default" onclick="parent.closeCurrTab()"><?=Yii::t('app', '取消')?></button>
                        <button type="submit" class="btn green" onclick="objectSubmit()"><i class="fa fa-check"></i> <?=Yii::t('app', '保存')?></button>   
                    </div>
                    <div class="col-md-6"> </div>
                </div>
            </div>                       
            <div class="portlet-body form">
                <form name="objectform" action="<?=Yii::$app->homeUrl?>/system/sysobject/<?=str_ireplace("save", "", $this->context->action->id)?>save" class="horizontal-form" method="post" enctype="multipart/form-data">
                    <input type="hidden" name="_csrf" value="<?=Yii::$app->request->getCsrfToken()?>">
                    <input type="hidden" name="sObjectName" value="<?=$sysObject->sObjectName?>">
                    <input type="hidden" name="ID" value="<?=$_GET['ID'] ? $_GET['ID'] : $_POST['ID']?>">
                    <div class="form-body">
						<h3 class="form-section"><?=Yii::t('app', '共享源')?></h3>
                        <div class="row">
                            <div class="col-md-12">
                            	<div class="form-group">
                                    <label class="control-label col-md-2"><?=Yii::t('app', '选择共享源的组织')?><span class="required" aria-required="true">*</span>:</label>
                                    <div class="col-md-2">
                                        <select class="form-control" name="source" onchange="changeSource($(this).closest('.row'), this.value)">
                                            <option value="dep"<? if ($autoshare->FromSysDepID) { ?> selected="selected"<? } ?>>部门</option>
                                            <option value="role"<? if ($autoshare->FromSysRoleID) { ?> selected="selected"<? } ?>>角色</option>
                                            <option value="team"<? if ($autoshare->FromSysTeamID) { ?> selected="selected"<? } ?>>团队</option>
                                        </select>       
                                    </div>
                                </div>                                      	
							</div>
                            <div sobjectname="dep" class="col-md-12 margin-top-10" style="display:none">
                                <div class="form-group">
                                    <label class="control-label col-md-2"><?=Yii::t('app', '源部门')?><span class="required" aria-required="true">*</span>:</label>
                                    <div class="col-md-10">
                                        <div class="input-group col-md-4">
                                            <input type="text" class="form-control" ignore="true" onchange="$(document.objectform.sourcedepid).val('')" placeholder="" value="<?=$autoshare->fromdep->sName?>" name="sourcedepidname">
                                            <input type="hidden" name="sourcedepid" value="<?=$autoshare->fromdep->lID?>"/>
                                            <span class="input-group-btn">
                                                <button type="button" class="btn green" onclick="showRef('<?=$sysObject->sObjectName?>', 'System/SysDep', 'sourcedepid')"><?=Yii::t('app', '选择')?></button>
                                            </span>
                                        </div>                                          
                                    </div>
                                </div> 
                            </div> 
                            <div sobjectname="role" class="col-md-12 margin-top-10" style="display:none">
                                <div class="form-group">
                                    <label class="control-label col-md-2"><?=Yii::t('app', '源角色')?><span class="required" aria-required="true">*</span>:</label>
                                    <div class="col-md-10">
                                        <div class="input-group col-md-4">
                                            <input type="text" class="form-control" ignore="true" onchange="$(document.objectform.sourceroleid).val('')" placeholder="" value="<?=$autoshare->fromrole->sName?>" name="sourceroleidname">
                                            <input type="hidden" name="sourceroleid" value="<?=$autoshare->fromrole->lID?>"/>
                                            <span class="input-group-btn">
                                                <button type="button" class="btn green" onclick="showRef('<?=$sysObject->sObjectName?>', 'System/SysRole', 'sourceroleid')"><?=Yii::t('app', '选择')?></button>
                                            </span>
                                        </div>                                          
                                    </div>
                                </div> 
                            </div>                             
                            <div sobjectname="team" class="col-md-12 margin-top-10" style="display:none">
                                <div class="form-group">
                                    <label class="control-label col-md-2"><?=Yii::t('app', '源团队')?><span class="required" aria-required="true">*</span>:</label>
                                    <div class="col-md-10">
                                        <div class="input-group col-md-4">
                                            <input type="text" class="form-control" ignore="true" onchange="$(document.objectform.sourceteamid).val('')" placeholder="" value="<?=$autoshare->fromteam->sName?>" name="sourceteamidname">
                                            <input type="hidden" name="sourceteamid" value="<?=$autoshare->fromteam->lID?>"/>
                                            <span class="input-group-btn">
                                                <button type="button" class="btn green" onclick="showRef('<?=$sysObject->sObjectName?>', 'System/SysTeam', 'sourceteamid')"><?=Yii::t('app', '选择')?></button>
                                            </span>
                                        </div>                                          
                                    </div>
                                </div> 
                            </div>  
                            <div id="sourceinclude" class="col-md-12 margin-top-10" style="display:none">
                                <div class="form-group">
                                    <label class="control-label col-md-2"><?=Yii::t('app', '共享源包含下级')?><span class="required" aria-required="true">*</span>:</label>
                                    <div class="col-md-10">
                                        <div class="radio-list input-group">
                                            <label class="radio-inline"><input type="radio" name="sourceinclude" value="1" <? if ($autoshare->bFromInclude == '1') { ?>checked<? } ?>> <?=Yii::t('app', '是')?> </label>
                                            <label class="radio-inline"><input type="radio" name="sourceinclude" value="0" <? if ($autoshare->bFromInclude == '0' || $autoshare->bFromInclude == '') { ?>checked<? } ?>> <?=Yii::t('app', '否')?> </label>
                                        </div>  
                                    </div>                      
                                </div> 
                            </div>                             
                            
                            
                        </div>   
						<h3 class="form-section"><?=Yii::t('app', '共享给')?></h3>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="control-label col-md-2"><?=Yii::t('app', '选择共享给的组织')?><span class="required" aria-required="true">*</span>:</label>
                                    <div class="col-md-2">
                                        <select class="form-control" name="target" onchange="changeTarget($(this).closest('.row'), this.value)">
                                            <option value="dep"<? if ($autoshare->ToSysDepID) { ?> selected="selected"<? } ?>>部门</option>
                                            <option value="role"<? if ($autoshare->ToSysRoleID) { ?> selected="selected"<? } ?>>角色</option>
                                            <option value="team"<? if ($autoshare->ToSysTeamID) { ?> selected="selected"<? } ?>>团队</option>
                                        </select>       
                                    </div>
                                </div> 
                            </div> 
                            <div sobjectname="dep" class="col-md-12 margin-top-10" style="display:none">
                                <div class="form-group">
                                    <label class="control-label col-md-2"><?=Yii::t('app', '共享给部门')?><span class="required" aria-required="true">*</span>:</label>
                                    <div class="col-md-10">
                                        <div class="input-group col-md-4">
                                            <input type="text" class="form-control" ignore="true" onchange="$(document.objectform.targetdepid).val('')" placeholder="" value="<?=$autoshare->todep->sName?>" name="targetdepidname">
                                            <input type="hidden" name="targetdepid" value="<?=$autoshare->todep->lID?>"/>
                                            <span class="input-group-btn">
                                                <button type="button" class="btn green" onclick="showRef('<?=$sysObject->sObjectName?>', 'System/SysDep', 'targetdepid')"><?=Yii::t('app', '选择')?></button>
                                            </span>
                                        </div>                                          
                                    </div>
                                </div> 
                            </div> 
                            <div sobjectname="role" class="col-md-12 margin-top-10" style="display:none">
                                <div class="form-group">
                                    <label class="control-label col-md-2"><?=Yii::t('app', '共享给角色')?><span class="required" aria-required="true">*</span>:</label>
                                    <div class="col-md-10">
                                        <div class="input-group col-md-4">
                                            <input type="text" class="form-control" ignore="true" onchange="$(document.objectform.targetroleid).val('')" placeholder="" value="<?=$autoshare->torole->sName?>" name="targetroleidname">
                                            <input type="hidden" name="targetroleid" value="<?=$autoshare->torole->lID?>"/>
                                            <span class="input-group-btn">
                                                <button type="button" class="btn green" onclick="showRef('<?=$sysObject->sObjectName?>', 'System/SysRole', 'targetroleid')"><?=Yii::t('app', '选择')?></button>
                                            </span>
                                        </div>                                          
                                    </div>
                                </div> 
                            </div>                             
                            <div sobjectname="team" class="col-md-12 margin-top-10" style="display:none">
                                <div class="form-group">
                                    <label class="control-label col-md-2"><?=Yii::t('app', '共享给团队')?><span class="required" aria-required="true">*</span>:</label>
                                    <div class="col-md-10">
                                        <div class="input-group col-md-4">
                                            <input type="text" class="form-control" ignore="true" onchange="$(document.objectform.targetteamid).val('')" placeholder="" value="<?=$autoshare->toteam->sName?>" name="targetteamidname">
                                            <input type="hidden" name="targetteamid" value="<?=$autoshare->toteam->lID?>"/>
                                            <span class="input-group-btn">
                                                <button type="button" class="btn green" onclick="showRef('<?=$sysObject->sObjectName?>', 'System/SysTeam', 'targetteamid')"><?=Yii::t('app', '选择')?></button>
                                            </span>
                                        </div>                                          
                                    </div>
                                </div> 
                            </div>
                            <div class="col-md-12 margin-top-10">
                                <div class="form-group">
                                    <label class="control-label col-md-2"><?=Yii::t('app', '共享模式')?><span class="required" aria-required="true">*</span>:</label>
                                    <div class="col-md-2">
                                        <select class="form-control" name="stoken">
                                            <option value="ref"<? if ($autoshare->sToken == 'ref') { ?> selected="selected"<? } ?>>参照</option>
                                            <option value="view"<? if ($autoshare->sToken == 'view') { ?> selected="selected"<? } ?>>公共只读</option>
                                            <option value="view,edit,ref"<? if ($autoshare->sToken == 'view,edit,ref') { ?> selected="selected"<? } ?>>公共读写</option>
                                            <option value="view,edit,del,ref"<? if ($autoshare->sToken == 'view,edit,del,ref') { ?> selected="selected"<? } ?>>管理者</option>
                                        </select>
                                    </div>
                                </div> 
                            </div>                             
                            
                            <div id="targetinclude" class="col-md-12 margin-top-10" style="display:none">
                                <div class="form-group">
                                    <label class="control-label col-md-2"><?=Yii::t('app', '共享源包含下级')?><span class="required" aria-required="true">*</span>:</label>
                                    <div class="col-md-10">
                                        <div class="radio-list input-group">
                                            <label class="radio-inline"><input type="radio" name="targetinclude" value="1" <? if ($autoshare->bToInclude == '1') { ?>checked<? } ?>> <?=Yii::t('app', '是')?> </label>
                                            <label class="radio-inline"><input type="radio" name="targetinclude" value="0" <? if ($autoshare->bToInclude == '0' || $autoshare->bToInclude == '') { ?>checked<? } ?>> <?=Yii::t('app', '否')?> </label>
                                        </div>  
                                    </div>                      
                                </div> 
                            </div>
                        </div>   
                    </div>
				</form>
			</div>
        </div>
    </div> 
</div>
<script src="<?=Yii::$app->homeUrl?>/<?=strtolower($this->context->sObjectName)?>/js" type="text/javascript"></script>
<script>
changeSource($("select[name='source']").closest('.row'), $("select[name='source']").val());
changeTarget($("select[name='target']").closest('.row'), $("select[name='target']").val());

</script>