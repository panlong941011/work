<style>
    .inputBox{
        width: 102px;
        border: 1px solid #c2cad8;
        position: relative;
    }

    .inputBox input {
        width: 70px;
        height: 30px;
        border: 0px solid #c2cad8;
    }

    .inputBox span {
        position: absolute;
        width: 30px;
        height: 30px;
        text-align: center;
        line-height: 30px;
        color: #000;
        font-weight: 700;
        right: 0;
        top: 0;
        border-left: 1px solid #ccc;
    }
</style>
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

