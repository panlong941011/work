<div class="input-group">
    <input type="text" class="form-control" sFieldAs="<?=$field->sFieldAs?>" sDataType="<?=$field->sDataType?>" placeholder="" value="<?=$data?>" name="arrObjectData[<?=$field->sObjectName?>][<?=$field->sFieldAs?>]" onFocus="WdatePicker({ dateFmt:<? if ($field->attr['dFormat'] == 'long') { ?>'yyyy-MM-dd HH:mm:ss'<? } else { ?>'yyyy-MM-dd'<? } ?> })" <? if ($field->bReadOnly) { ?>readonly="readonly"<? } elseif ($field->bDisabled) { ?>disabled="disabled"<? } ?>>
    <span class="input-group-addon">
        <i class="fa fa-calendar"></i>
    </span>
</div>                                                        