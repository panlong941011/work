<?
$arrID = [];
foreach ($data as $d) {
	$arrID[$d['ID']] = 1;
}
?>
<select class="form-control" sFieldAs="<?=$field->sFieldAs?>" size="2" sDataType="<?=$field->sDataType?>" name="arrObjectData[<?=$field->sObjectName?>][<?=$field->sFieldAs?>][][]" multiple="multiple" <? if ($field->bReadOnly) { ?>readonly="readonly"<? } elseif ($field->bDisabled) { ?>disabled="disabled"<? } ?>>
    <? foreach ($field->options as $option) { ?>
    <option value="<?=$option['ID']?>" <? if ($data && $arrID[$option['ID']] || !$data && $option['bDefault']) { ?>selected<? } ?>><?=$option['sName']?></option>
    <? } ?>
</select>
