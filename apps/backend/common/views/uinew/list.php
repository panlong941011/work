<select class="form-control multiselect" sFieldAs="<?=$field->sFieldAs?>" sDataType="<?=$field->sDataType?>" name="arrObjectData[<?=$field->sObjectName?>][<?=$field->sFieldAs?>]" <? if ($field->bReadOnly) { ?>readonly="readonly"<? } elseif ($field->bDisabled) { ?>disabled="disabled"<? } ?>>
    <? if (!$field->bNull) { ?>
    <option value="">--------------</option>
    <? } ?>
    <? foreach ($field->options as $option) { ?>
    <option value="<?=$option['ID']?>" <? if (strlen($data['ID']) > 0 && $data['ID'] === $option['ID'] || strlen($data['ID']) == 0 && $option['bDefault']) { ?>selected<? } ?>><?=$option['sName']?></option>
    <? } ?>
</select>
