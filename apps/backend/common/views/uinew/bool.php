
<div class="radio-list input-group">
    <label class="radio-inline"><input type="radio" sDataType="<?=$field->sDataType?>" name="arrObjectData[<?=$field->sObjectName?>][<?=$field->sFieldAs?>]" value="1" <? if ($data == '1' || $data == '' && $field->sDefValue == '1') { ?>checked<? } ?>> <?=Yii::t('app', '是')?> </label>
    <label class="radio-inline"><input type="radio" sDataType="<?=$field->sDataType?>" name="arrObjectData[<?=$field->sObjectName?>][<?=$field->sFieldAs?>]" value="0" <? if ($data == '0' || $data == '' && $field->sDefValue == '0') { ?>checked<? } ?>> <?=Yii::t('app', '否')?> </label>
</div>