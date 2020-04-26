<div class="input-group">
    <input type="text" class="form-control" sDataType="<?=$field->sDataType?>" ignore="true" onchange="$('input[name=\'arrObjectData[<?=$field->sObjectName?>][<?=$field->sFieldAs?>]\']').val('');;$(this).val('')" placeholder="" value="<?=$data['sName']?>" name="arrObjectData[<?=$field->sObjectName?>][<?=$field->sFieldAs?>Name]" <? if ($field->bReadOnly) { ?><? } elseif ($field->bDisabled) { ?>disabled="disabled"<? } ?>>
    <input type="hidden" name="arrObjectData[<?=$field->sObjectName?>][<?=$field->sFieldAs?>]" value="<?=$data['ID']?>" sFieldAs="<?=$field->sFieldAs?>"/>
    <span class="input-group-btn">
        <button type="button" class="btn green" sDataType="<?=$field->sDataType?>" onclick="showRef('<?=$field->sObjectName?>', '<?=$field->sRefKey?>', '<?=$field->sFieldAs?>')"><?=Yii::t('app', '选择')?></button>
    </span>
</div> 