<div class="input-group">
    <input type="text" class="form-control" sFieldAs="<?=$field->sFieldAs?>" sDataType="<?=$field->sDataType?>" onchange="$('input[name=\'arrObjectData[<?=$field->sObjectName?>][<?=$field->sLinkField?>]\']').val('')" placeholder="" value="<?=$data?>" name="arrObjectData[<?=$field->sObjectName?>][<?=$field->sFieldAs?>]" <? if ($field->bReadOnly) { ?>readonly="readonly"<? } elseif ($field->bDisabled) { ?>disabled="disabled"<? } ?>>
    <input type="hidden" sFieldAs="<?=$field->sLinkField?>" name="arrObjectData[<?=$field->sObjectName?>][<?=$field->sLinkField?>]" value="<?=$sLinkFieldValue?>" />
    <span class="input-group-btn">
        <button type="button" class="btn btn-sm green" sDataType="<?=$field->sDataType?>" onclick="showUpload('<?=$field->sObjectName?>', '<?=$field->sFieldAs?>', '<?=$field->sLinkField?>')"><?=Yii::t('app', '上传')?></button>
    </span>
</div> 