<? if ($this->context->action->id == 'view') {

    if ($data === null) {
        echo "&nbsp;";
    } else {
        if ($field->sEnumTable && $field->sEnumTable != "System/SysFieldEnum") {
            echo '<a href="javascript:;" onclick="parent.addTab($(this).text(), \'' . Yii::$app->homeUrl . '/' . strtolower($field->sEnumTable) . '/viewredirect?ID=' . $data['ID'] . '&FieldID=' . $field->ID . '\')">' . $data['sName'] . '</a>';
        } else {
            echo $data['sName'];
        }
    }
} else { ?>
    <select class="form-control" sFieldAs="<?= $field->sFieldAs ?>" sDataType="<?= $field->sDataType ?>"
            name="arrObjectData[<?= $field->sObjectName ?>][<?= $field->sFieldAs ?>]"
            <? if ($field->bReadOnly) { ?>readonly="readonly"
            <? } elseif ($field->bDisabled) { ?>disabled="disabled"<? } ?>>
        <? if (!$field->bNull) { ?>
            <option value="">--------------</option>
        <? } ?>
        <? foreach ($field->options as $option) { ?>
            <option value="<?= $option['ID'] ?>"
                    <? if (strlen($data['ID']) > 0 && $data['ID'] === $option['ID'] || strlen($data['ID']) == 0 && $option['bDefault']) { ?>selected<? } ?>><?= $option['sName'] ?></option>
        <? } ?>
    </select>
<? } ?>
<script>
    $(document).ready(
        function () {
            $("[sFieldAs='UpID']").closest(".row").hide();


            $("select[sFieldAs='GradeID']").change(
                function () {
                    if ($(this).val() == '1') {
                        $("[sfieldas='UpID']").closest(".row").hide();
                        $("div[sfieldas='UpID'] input").val('');
                    } else {
                        $("[sfieldas='UpID']").closest(".row").show();
                    }
                }
            );

            if ($("select[sFieldAs='GradeID']").val() == '1') {
                $("[sfieldas='UpID']").closest(".row").hide();
                $("div[sfieldas='UpID'] input").val('');
            } else {
                $("[sfieldas='UpID']").closest(".row").show();
            }
        }
    )

    function getExtra() {
        return $("select[sFieldAs='GradeID']").val();
    }
</script>