<style>
    .express {
        width: 640px;
        padding: 20px 10px 5px;
        position: relative;
        background: #eee;
    }

    .express span {
        display: block;
        width: 50px;
        padding: 4px 10px;
        -webkit-border-radius: 5px !important;
        -moz-border-radius: 5px !important;
        border-radius: 5px !important;
        position: relative;
        color: #000;
    }

    .express span:after {
        content: '';
        position: absolute;
        left: 16px;
        bottom: -15px;
        display: block;
        width: 0;
        height: 0;

        border-color: transparent;
        border-style: solid;
        border-top-color: #BFBFBF;
        border-bottom-width: 0;
        border-width: 10px;
    }

    .express p {
        padding-top: 10px;
        color: #666;
        padding-bottom: 8px;
        border-bottom: 1px dashed #ccc;
    }

    .express a {
        position: absolute;
        top: 20px;
        right: 10px;
    }
</style>

<? if ($this->context->action->id == 'view') {
    echo '<a href="javascript:;" onclick="parent.addTab($(this).text(), \'' . Yii::$app->homeUrl . '/' . strtolower($field->sRefKey) . '/viewredirect?ID=' . $data['ID'] . '&FieldID=' . $field->ID . '\')">' . $data['sName'] . '</a>';
} else { ?>
    <div class="input-group" style="width: 400px;">
        <input type="text" class="form-control" sDataType="<?= $field->sDataType ?>" ignore="true"
               onchange="$('input[name=\'arrObjectData[<?= $field->sObjectName ?>][<?= $field->sFieldAs ?>]\']').val('')"
               placeholder="" value="<?= $data['sName'] ?>"
               name="arrObjectData[<?= $field->sObjectName ?>][<?= $field->sFieldAs ?>Name]"
               <? if ($field->bReadOnly) { ?>readonly="readonly"
               <? } elseif ($field->bDisabled) { ?>disabled="disabled"<? } ?>>
        <input type="hidden" name="arrObjectData[<?= $field->sObjectName ?>][<?= $field->sFieldAs ?>]"
               value="<?= $data['ID'] ?>" sFieldAs="<?= $field->sFieldAs ?>"/>
        <span class="input-group-btn">
        <button type="button" class="btn green" sDataType="<?= $field->sDataType ?>"
                onclick="showRef('<?= $field->sObjectName ?>', '<?= $field->sRefKey ?>', '<?= $field->sFieldAs ?>')"><?= Yii::t('app', '选择') ?></button>
        <button type="button" class="btn red"
                onclick="parent.addTab('新建运费模板', '/shop/shiptemplate/new')">新建运费模板</button>
    </span>
    </div>
<? } ?>

<div class="express margin-top-10 hide" id="shiptemplatedetail">

</div>


<!-- 这结构和样式是一套 -->
<style>
    .inputBox {
        width: 102px;
        border: 1px solid #ccc;
        position: relative;
    }

    .inputBox input {
        width: 70px;
        height: 30px;
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
<script>
    function getShipTemplateDetail(id) {

        if (id == '') {
            return false;
        }

        $.get
        (
            '/shop/shiptemplate/detail?id=' + id,
            function (data) {
                $("#shiptemplatedetail").removeClass("hide").html(data);
            }
        )
    }

    <? if ($this->context->action->id == 'view') {?>
    getShipTemplateDetail('<?=$data['ID']?>');
    <? } else { ?>
    getShipTemplateDetail($("input[sFieldAs='ShipTemplateID']").val());
    <? } ?>
    function refSave(sObjectName, sFieldAs, data) {
        var refobject = $("body").prop("refobject");
        if (refobject) {
            $("input[name='arrObjectData[" + sObjectName + "][" + sFieldAs + "][]']", $(refobject).parent()).val(data.ID);
            $("input[name='arrObjectData[" + sObjectName + "][" + sFieldAs + "Name][]']", $(refobject).parent()).val(data.sName);
        } else {
            $("input[name='arrObjectData[" + sObjectName + "][" + sFieldAs + "]']").val(data.ID);
            $("input[name='arrObjectData[" + sObjectName + "][" + sFieldAs + "Name]']").val(data.sName);
        }

        $("body").prop("refobject", null);


        if (sFieldAs == 'ShipTemplateID') {
            getShipTemplateDetail(data.ID);
        } else if (sFieldAs == 'MemberShipTemplateID') {
            getShipTemplateDetail2(data.ID);
        } else if (sFieldAs == 'ProductParamTemplateID') {
            getParamsTemplate(data.ID);
        }
    }
</script>
