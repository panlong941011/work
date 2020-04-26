<input type="text" class="form-control" sFieldAs="<?= $field->sFieldAs ?>" placeholder="" style="width: 100px"
       sDataType="<?= $field->sDataType ?>" value="<?= $data ?>"
       name="arrObjectData[<?= $field->sObjectName ?>][<?= $field->sFieldAs ?>]"
       <? if ($field->bReadOnly) { ?>readonly="readonly" <? } elseif ($field->bDisabled) { ?>disabled="disabled"<? } ?>>