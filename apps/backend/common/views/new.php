<script src="<?=Yii::$app->homeUrl?>/js/pages/scripts/new.js" type="text/javascript"></script>

<div class="breadcrumb" style="display:none">
    <h2><a href="<?=Yii::$app->homeUrl?>/<?=strtolower($sysObject->sObjectName)?>/home"><?=$sysObject->sName?></a></h2>
    <? if (str_ireplace("save", "", $this->context->action->id) == 'new') { ?>
    <h3><?=Yii::t('app', '新建')?></h3>
    <? } elseif (str_ireplace("save", "", $this->context->action->id) == 'clone') { ?>
    <h3><?=Yii::t('app', '复制')?></h3>
    <? } else { ?>
    <h3><?=Yii::t('app', '编辑')?></h3>
    <? } ?>
</div>

<div class="row margin-top-10">
    <div class="col-md-12">
        <div class="portlet light bordered">
        	<div class="portlet-title">
                <div class="caption">
					<? if (str_ireplace("save", "", $this->context->action->id) == 'new') { ?>
                    <span class="caption-subject font-green-haze bold uppercase"><?=Yii::t('app', '新建')?> <?=$sysObject->sName?></span>
                    <? } else { ?>
                    <span class="caption-subject font-green-haze bold uppercase"><?=$arrUIData[$sysObject->sNameField]?></span>
                    <? } ?>
                    <span class="caption-helper">&nbsp;</span>
                </div>
                <div class="tools">
                </div>
            </div>
            <div class="form-actions">
                <div class="row">
                    <div class="col-md-12">
                    	<?=$this->context->getNewButton()?>
                    </div>
                    <div class="col-md-6"> </div>
                </div>
            </div>
            <div class="portlet-body form">
                <form name="objectform" action="<?=Yii::$app->homeUrl?>/<?=strtolower($sysObject->sObjectName)?>/<?=str_ireplace("save", "", $this->context->action->id)?>save" class="horizontal-form" method="post" enctype="multipart/form-data">
                    <input type="hidden" name="_csrf" value="<?=Yii::$app->request->getCsrfToken()?>">
                    <input type="hidden" name="ID" value="<?=$_GET['ID'] ? $_GET['ID'] : $_POST['ID']?>">
                    <div class="form-body">
                    	<? if ($arrErrMsg) { ?>
                        <div class="alert alert-danger margin-top-10">
                            <button class="close" data-close="alert"></button>
                            <? foreach ($arrErrMsg as $arrMsg) { ?>
                            <p><?=$arrMsg['sMsg']?></p>
                            <? } ?>
                        </div>
                        <? } ?>
                        <? foreach ($ui->fieldclass as $fieldclass) { ?>
                            <div name="<?=$fieldclass->sName?>">
                        <h3 class="form-section"><?=$fieldclass->sName?></h3>

                        <div class="row">
                            <div class="col-md-4">
                            <? foreach ($fieldclass->fields as $f) { ?>
                            	<div class="row">
									<? if ($f->lGroup == 1 && $f->field->sDataType != 'TextArea') { ?>
                                    <div class="form-group <? if ($arrErrMsg[$f->field->sFieldAs]) { ?>has-error<? } ?>" sObjectName="<?=$f->field->sObjectName?>" sDataType="<?=$f->field->sDataType?>" sFieldAs="<?=$f->field->sFieldAs?>">
                                        <label class="control-label col-md-3"><?=$f->field->sName?><? if ($f->field->bNull) { ?><span class="required" aria-required="true">*</span><? } ?>:</label>
                                        <div class="col-md-9">
                                            <?=$this->render(stristr($f->field->sUIType, '/') ? strtolower($f->field->sUIType) : 'uinew/'.strtolower($f->field->sUIType), ['field'=>$f->field, 'data'=>$arrUIData[$f->field->sFieldAs], 'sLinkFieldValue'=>$arrUIData[$f->field->sLinkField]])?>
                                            <p class="help-block"><?=$f->field->sTip?></p>
                                        </div>
                                    </div>
                                    <? } ?>
                                </div>
                            <? } ?>
                            </div>
                            <div class="col-md-4">
                            <? foreach ($fieldclass->fields as $f) { ?>
                            	<div class="row">
                                <? if ($f->lGroup == 2 && $f->field->sDataType != 'TextArea') { ?>
                                <div class="form-group <? if ($arrErrMsg[$f->field->sFieldAs]) { ?>has-error<? } ?>" sObjectName="<?=$f->field->sObjectName?>" sDataType="<?=$f->field->sDataType?>" sFieldAs="<?=$f->field->sFieldAs?>">
                                    <label class="control-label col-md-3"><?=$f->field->sName?><? if ($f->field->bNull) { ?><span class="required" aria-required="true">*</span><? } ?>:</label>
                                    <div class="col-md-9">
										<?=$this->render(stristr($f->field->sUIType, '/') ? strtolower($f->field->sUIType) : 'uinew/'.strtolower($f->field->sUIType), ['field'=>$f->field, 'data'=>$arrUIData[$f->field->sFieldAs], 'sLinkFieldValue'=>$arrUIData[$f->field->sLinkField]])?>
                                        <p class="help-block"><?=$f->field->sTip?></p>
                                    </div>
                                </div>
                                <? } ?>
                                </div>
                            <? } ?>
                            </div>
                            <div class="col-md-4">
                            <? foreach ($fieldclass->fields as $f) { ?>
                            	<div class="row">
                                <? if ($f->lGroup == 3 && $f->field->sDataType != 'TextArea') { ?>
                                <div class="form-group <? if ($arrErrMsg[$f->field->sFieldAs]) { ?>has-error<? } ?>" sObjectName="<?=$f->field->sObjectName?>" sDataType="<?=$f->field->sDataType?>" sFieldAs="<?=$f->field->sFieldAs?>">
                                    <label class="control-label col-md-3"><?=$f->field->sName?><? if ($f->field->bNull) { ?><span class="required" aria-required="true">*</span><? } ?>:</label>
                                    <div class="col-md-9">
									<?=$this->render(stristr($f->field->sUIType, '/') ? strtolower($f->field->sUIType) : 'uinew/'.strtolower($f->field->sUIType), ['field'=>$f->field, 'data'=>$arrUIData[$f->field->sFieldAs], 'sLinkFieldValue'=>$arrUIData[$f->field->sLinkField]])?>
                                    <p class="help-block"><?=$f->field->sTip?></p>
                                    </div>
                                </div>
                                <? } ?>
                                </div>
                            <? } ?>
                            </div>
                        </div>


                        <? foreach ($fieldclass->fields as $f) { ?>
						<div class="row">
						<? if ($f->field->sDataType == 'TextArea') { ?>
                        	<div class="col-md-12">
                        		<div class="form-group <? if ($arrErrMsg[$f->field->sFieldAs]) { ?>has-error<? } ?>" sObjectName="<?=$f->field->sObjectName?>" sDataType="<?=$f->field->sDataType?>" sFieldAs="<?=$f->field->sFieldAs?>">
                                    <label class="control-label"><?=$f->field->sName?><? if ($f->field->bNull) { ?><span class="required" aria-required="true">*</span><? } ?>:</label>
									<?=$this->render(stristr($f->field->sUIType, '/') ? strtolower($f->field->sUIType) : 'uinew/'.strtolower($f->field->sUIType), ['field'=>$f->field, 'data'=>$arrUIData[$f->field->sFieldAs], 'sLinkFieldValue'=>$arrUIData[$f->field->sLinkField]])?>
                                    <p class="help-block"><?=$f->field->sTip?></p>
                                </div>
                        	</div>
						<? } ?>
                        </div>
                        <? } ?>
                            </div>
                        <? } ?>

                        <?=$this->context->getNewFooterAppend()?>

                    </div>

					<? if ($arrSysDetailObject) { ?>
                        <? foreach ($arrSysDetailObject as $sysDetailObject) {  ?>
                            <? $detailUI = $arrDetailUI[$sysDetailObject->sObjectName]; ?>
                            <div class="row margin-top-10">
                                <div class="col-md-12">
                                    <div class="portlet light bordered">
                                        <div class="portlet-title">
                                            <div class="caption">
                                                <span class="caption-subject font-green-haze bold uppercase" id="detailsubject"><?=$sysDetailObject->sName?></span>
                                                <span class="caption-helper">&nbsp;</span>
                                            </div>
                                        </div>
                                        <div class="portlet-body" style=" padding-top:0px">
                                            <div class="dataTables_wrapper no-footer">
                                                <div class="table-scrollable">
                                                <table class="table table-striped table-bordered table-hover table-checkable order-column dataTable no-footer table-header-fixed" id="detailTable" sObjectName="<?=$sysDetailObject->sObjectName?>">
                                                    <thead class="flip-content">
                                                        <tr>
                                                            <th width="50"> <i id="newDetailRowBtn" sObjectName="<?=$sysDetailObject->sObjectName?>" class="fa fa-plus-square"  title="<?=Yii::t('app', '新增一行')?>"> </i> </th>
                                                            <? foreach ($detailUI->fieldclass as $fieldclass) { ?>
                                                            <? foreach ($fieldclass->fields as $f) { ?>
                                                            <th> <?=$f->field->sName?><? if ($f->field->bNull) { ?><span class="required">*</span><? } ?> <? if ($f->field->sTip) { ?><label class="control-label">(<?=$f->field->sTip?>)</label><? } ?></th>
                                                            <? } ?>
                                                            <? } ?>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <? foreach ($arrDetailUIData[$sysDetailObject->sObjectName] as $arrUIData) { ?>
                                                        <tr>
                                                            <td>
                                                                <input name="arrObjectData[<?=$f->field->sObjectName?>][ID][]" type="hidden" value="<?=$arrUIData['ID']?>" />
                                                                <i class="fa fa-copy detailCloneRowBtn" title="<?=Yii::t('app', '复制这行数据')?>"></i>
                                                                <i class="fa fa-minus-square detailDelRowBtn" title="<?=Yii::t('app', '删除这行')?>"></i>
                                                            </td>
                                                            <? foreach ($detailUI->fieldclass as $fieldclass) { ?>
                                                            <? foreach ($fieldclass->fields as $f) { ?>
                                                            <td <? if ($f->field->bNull) { ?>class="required"<? } ?> sObjectName="<?=$f->field->sObjectName?>" sDataType="<?=$f->field->sDataType?>" sFieldAs="<?=$f->field->sFieldAs?>"><?=$this->render('uinew/'.strtolower($f->field->sUIType)."-detail", ['field'=>$f->field, 'data'=>$arrUIData[$f->field->sFieldAs], 'sLinkFieldValue'=>$arrUIData[$f->field->sLinkField]])?></td>
                                                            <? } ?>
                                                            <? } ?>
                                                        </tr>
                                                        <? } ?>
                                                        <tr id="detailCloneRow" sObjectName="<?=$sysDetailObject->sObjectName?>" class="hide">
                                                            <td>
                                                                <i class="fa fa-copy detailCloneRowBtn" title="<?=Yii::t('app', '复制这行数据')?>"></i>
                                                                <i class="fa fa-minus-square detailDelRowBtn" title="<?=Yii::t('app', '删除这行')?>"></i>
                                                            </td>
                                                            <? foreach ($detailUI->fieldclass as $fieldclass) { ?>
                                                            <? foreach ($fieldclass->fields as $f) { ?>
                                                            <td <? if ($f->field->bNull) { ?>class="required"<? } ?> sObjectName="<?=$f->field->sObjectName?>" sDataType="<?=$f->field->sDataType?>" sFieldAs="<?=$f->field->sFieldAs?>"><?=$this->render('uinew/'.strtolower($f->field->sUIType)."-detail", ['field'=>$f->field, 'data'=>'', 'sLinkFieldValue'=>''])?></td>
                                                            <? } ?>
                                                            <? } ?>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                                </div>
                                                <div></div><?=$sysDetailObject->sDesc?></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <? } ?>
                    <? } ?>
				</form>
			</div>
        </div>
    </div>
</div>

<script src="<?=Yii::$app->homeUrl?>/js/global/plugins/ueditor/ueditor.config.js" type="text/javascript"></script>
<script src="<?=Yii::$app->homeUrl?>/js/global/plugins/ueditor/ueditor.all.min.js" type="text/javascript"></script>
<script type="text/javascript" src="<?=Yii::$app->homeUrl?>/js/global/plugins/My97DatePicker/WdatePicker.js"></script>