<div class="breadcrumb" style="display:none">
    <h2><a href="<?=Yii::$app->homeUrl?>/<?=strtolower($sysObject->sObjectName)?>/home"><?=$sysObject->sName?></a></h2>
    <h3><?=Yii::t('app', '详情页')?></h3>
</div>
<div class="margin-top-10">
    <div>
        <div class="portlet light bordered">
        	<div class="portlet-title">
                <div class="caption">
                    <span class="caption-subject font-green-haze bold uppercase"><?=$data['sName']?></span>
                </div>
                <div class="tools">
                </div>
            </div>       
            <div class="form-actions">
                <div class="row">
                    <div class="col-md-12">
                    	<?=$this->context->getViewButton()?> 
                    </div>
                    <div class="col-md-6"> </div>
                </div>
            </div>             
            <div class="portlet-body form">
			<form action="#" class="form-horizontal">
			<div class="form-body">
			    <? foreach ($ui->fieldclass as $fieldclass) { ?>
                <div name="<?=$fieldclass->sName?>">
                <h3 class="form-section"><?=$fieldclass->sName?></h3>
                <div class="row">
                	<div class="col-md-4">
					<? foreach ($fieldclass->fields as $f) { ?>
                        <? if ($f->lGroup == 1 && $f->field->sDataType != 'TextArea') { ?>
                        <div class="form-group">
                            <label class="control-label col-md-4 bold"><?=$f->field->sName?>:</label>
                            <div class="col-md-8">
                                <p class="form-control-static"><?=$this->render(stristr($f->field->sUIType, '/') ? strtolower($f->field->sUIType) : 'uiview/'.strtolower($f->field->sUIType), ['field'=>$f->field, 'data'=>$arrUIData[$f->field->sFieldAs], 'sLinkFieldValue'=>$arrUIData[$f->field->sLinkField]])?></p>
                            </div>
                        </div>
                        <? } ?>
                    <? } ?>
                    </div>
                	<div class="col-md-4">
					<? foreach ($fieldclass->fields as $f) { ?>
                        <? if ($f->lGroup == 2 && $f->field->sDataType != 'TextArea') { ?>
                        <div class="form-group">
                            <label class="control-label col-md-4 bold"><?=$f->field->sName?>:</label>
                            <div class="col-md-8">
                                <p class="form-control-static"><?=$this->render(stristr($f->field->sUIType, '/') ? strtolower($f->field->sUIType) : 'uiview/'.strtolower($f->field->sUIType), ['field'=>$f->field, 'data'=>$arrUIData[$f->field->sFieldAs], 'sLinkFieldValue'=>$arrUIData[$f->field->sLinkField]])?></p>
                            </div>
                        </div>
                        <? } ?>
                    <? } ?>                    
                    </div>
                    <div class="col-md-4">
					<? foreach ($fieldclass->fields as $f) { ?>
                        <? if ($f->lGroup == 3 && $f->field->sDataType != 'TextArea') { ?>
                        <div class="form-group">
                            <label class="control-label col-md-4 bold"><?=$f->field->sName?>:</label>
                            <div class="col-md-8">
                                <p class="form-control-static"><?=$this->render(stristr($f->field->sUIType, '/') ? strtolower($f->field->sUIType) : 'uiview/'.strtolower($f->field->sUIType), ['field'=>$f->field, 'data'=>$arrUIData[$f->field->sFieldAs], 'sLinkFieldValue'=>$arrUIData[$f->field->sLinkField]])?></p>
                            </div>
                        </div>
                        <? } ?>
                    <? } ?>                    
                    </div>
            	</div>              
                

			    <? foreach ($fieldclass->fields as $f) { ?>
				<div class="row">
                <? if ($f->field->sDataType == 'TextArea') { ?>
                    <div class="col-md-12">
                    	<div class="form-group">
                            <div class="row">
                                <label class="control-label col-md-1 bold"><?=$f->field->sName?>:</label>
                                <div class="col-md-11"><p class="form-control-static"><?=$this->render(stristr($f->field->sUIType, '/') ? strtolower($f->field->sUIType) : 'uiview/'.strtolower($f->field->sUIType), ['field'=>$f->field, 'data'=>$arrUIData[$f->field->sFieldAs], 'sLinkFieldValue'=>$arrUIData[$f->field->sLinkField]])?></p></div>
                            </div>
                        </div>
                    </div>   
                <? } ?>
                </div>    
                <? } ?>
                </div>
                <? } ?>               

				<?=$this->context->getViewFooterAppend()?>
              
			</div>
			</form>
          </div>
        </div>
    </div> 
</div>
<? if ($arrSysDetailObject) { ?>
<? foreach ($arrSysDetailObject as $sysDetailObject) { ?>
<?=$this->context->getRelInfo($sysDetailObject)?>
<? }} ?>
<script src="<?=Yii::$app->homeUrl?>/js/pages/scripts/view.js" type="text/javascript"></script>