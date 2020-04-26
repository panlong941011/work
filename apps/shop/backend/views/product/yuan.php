<? if ($this->context->action->id == 'view') { ?>
<?=number_format($data, $field->lDeciLength)?>
<? } else { ?>
<div class="inputBox">
    <input type="text" sFieldAs="<?= $field->sFieldAs ?>" placeholder=""
           sDataType="<?= $field->sDataType ?>" value="<?= $data ?>"
           name="arrObjectData[<?= $field->sObjectName ?>][<?= $field->sFieldAs ?>]"
           <? if ($field->bReadOnly) { ?>readonly="readonly" <? } elseif ($field->bDisabled) { ?>disabled="disabled"<? } ?>>
    <span class="bg-grey-steel">元</span>
</div>
<? } ?>