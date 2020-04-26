<input type="text" class="form-control" sFieldAs="<?= $field->sFieldAs ?>" placeholder="" style="width: 70px;display: inline;"
       sDataType="<?= $field->sDataType ?>" value="<?= $data ?>"
       name="arrObjectData[<?= $field->sObjectName ?>][<?= $field->sFieldAs ?>]"
       <? if ($field->bReadOnly) { ?>readonly="readonly" <? } elseif ($field->bDisabled) { ?>disabled="disabled"<? } ?>> <?=$field->sUnit?>