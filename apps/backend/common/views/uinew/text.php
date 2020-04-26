<input type="text" class="form-control" sDataType="<?= $field->sDataType ?>" sFieldAs="<?= $field->sFieldAs ?>"
       placeholder="" value="<?= $data ?>" name="arrObjectData[<?= $field->sObjectName ?>][<?= $field->sFieldAs ?>]"
       <? if ($field->bReadOnly) { ?>readonly="readonly" <? } elseif ($field->bDisabled) { ?>disabled="disabled"<? } ?>>