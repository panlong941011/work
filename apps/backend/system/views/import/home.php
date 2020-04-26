<div class="breadcrumb" style="display:none">
    <h2><?=Yii::t('app', '批量导入')?></h2>
    <h3><?=Yii::t('app', '主页')?></h3>
</div>
<div class="row margin-top-10">
    <div class="col-md-12">
        <div class="portlet light bordered">
            <div class="form-actions margin-top-10">
                <div class="row">
                    <div class="col-md-12">
                    	<button type="button" class="btn default" onclick="parent.closeCurrTab()"><?=Yii::t('app', '取消')?></button>
                        <button type="button" class="btn green" onclick="checkValidate()"><i class="fa fa-check"></i> <?=Yii::t('app', '保存')?></button>   
                    </div>
                    <div class="col-md-6"> </div>
                </div>
            </div>                       
            <div class="portlet-body form">
                <form name="objectform" action="<?=Yii::$app->homeUrl?>/<?=strtolower($sysObject->sObjectName)?>/<?=str_ireplace("save", "", $this->context->action->id)?>save" class="horizontal-form" method="post" enctype="multipart/form-data">
                <input type="hidden" name="_csrf" value="<?=Yii::$app->request->getCsrfToken()?>">
                <input type="hidden" name="ID" value="<?=$_GET['ID'] ? $_GET['ID'] : $_POST['ID']?>">
                <div class="form-body">
                    <h3 class="form-section"><?=Yii::t('app', '批量导入')?></h3>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="control-label col-md-3"><?=Yii::t('app', '上传Excel文件')?><span class="required" aria-required="true">*</span>:</label>
                                <div class="col-md-9">
                                    <input type="file" class="form-control" name="file">
                                    <span class="help-block">  </span>
                                </div>
                            </div>
                        </div>
                        <!--/span-->
                    </div>
                    <!--/row-->
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="control-label col-md-3"><?=Yii::t('app', '导入的对象')?><span class="required" aria-required="true">*</span>:</label>
                                <div class="col-md-9">
                                	
                                    <select name="sObjectName" class="form-control" onchange="getField()">
										<? foreach ($arrModule as $module) { ?>
                                        <? if ($arrObject[$module->ID]) { ?>
										<optgroup label="<?=$module->sName?>">                                        
                                        <? ksort($arrObject[$module->ID]);foreach ($arrObject[$module->ID] as $sFirstPY => $arr) { ?>
                                        	<optgroup label="&nbsp;&nbsp;&nbsp;<?=$sFirstPY?>">
                                            <? ksort($arr);foreach ($arr as $object) { ?>
                                            <option value="<?=$object->sObjectName?>" <? if($object->sObjectName == $_POST['sObjectName']){?>selected<? } ?>>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?=$object->sName?></option>
                                            <? } ?>
                                            </optgroup>
                                        <? } ?>
                                        </optgroup>
									    <? }} ?>
                                    </select>
                                    
                                    <span class="help-block">  </span>
                                </div>
                            </div>
                        </div>
                        <!--/span-->
                    </div>
                    <!--/row-->
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="control-label col-md-3"><?=Yii::t('app', '主键字段')?><span class="required" aria-required="true">*</span>:</label>
                                <div class="col-md-9">
                                	<div id="keyfield">
                                    <select class="form-control" name="sKeyField[]">
                                    </select>
                                    </div>
                                    <span class="help-block"> <?=Yii::t('app', '当通过主键字段的值匹配到数据，系统会自动更新数据，否则插入新数据。')?> </span>
                                    <div class="margin-top-10">
                                    <button type="button" class="btn green" onclick="addKeyField()"><?=Yii::t('app', '新增主键字段')?></button>
                                    <button type="button" class="btn default" onclick="delKeyField()"><?=Yii::t('app', '删除主键字段')?></button>
                                    </div>
                                    
                                </div>
                            </div>
                        </div>
                        <!--/span-->
                    </div>
                    
					<? if ($arrMsg) { ?>
                    <div class="alert alert-danger margin-top-10">
                        <button class="close" data-close="alert"></button> 
                        <? foreach ($arrMsg as $msg) { ?>
                        <p><?=$msg?></p>
                        <? } ?>
                    </div>
                    <? } ?>                    
                </div>

				</form>
			</div>
        </div>
    </div> 
</div>
<script src="<?=Yii::$app->homeUrl?>/system/import/js" type="text/javascript"></script>