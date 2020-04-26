<div class="radio-list input-group">
    <input type="text" style="display: none" sDataType="<?=$field->sDataType?>" name="arrObjectData[<?=$field->sObjectName?>][<?=$field->sFieldAs?>][]" value="<? if ($data == '1' || $data == '' && $field->sDefValue == '1') { ?>1<? } else { ?>0<? } ?>">
    <input type="checkbox" name="arrObjectData[ignore][][]" onclick="if($(this).prop('checked')){$(this).prev().val('1')}else{$(this).prev().val('0')}" <? if ($data == '1' || $data == '' && $field->sDefValue == '1') { ?>checked<? } ?>>	
</div>