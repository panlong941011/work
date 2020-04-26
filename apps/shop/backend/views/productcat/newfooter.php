<script>

    <? if ($this->context->action->id == 'edit') { ?>
    var bOptionDel = false;
    $("select[sFieldAs='GradeID'] option").each(
        function () {
            if (!$(this).prop('selected')) {
                $(this).remove();
            }
        }
    );
    <? } ?>

    function beforeObjectSubmit() {
        var bValidate = true;

        $(".has-error .help-block").html("");

        //商品分类名称字数限制移除 by hcc on 2018/7/6
        // if ($("input[sFieldAs='sName']").val() != "") {
        //     if ($("input[sFieldAs='sName']").val().length > 10) {
        //         bValidate = false;
        //         $("input[sFieldAs='sName']").closest(".form-group").addClass("has-error");
        //         $("input[sFieldAs='sName']").closest(".form-group").find(".help-block").html("不得多于10字");
        //     }
        // }

        if ($("select[sFieldAs='GradeID']").val() == '2' || $("select[sFieldAs='GradeID']").val() == '3') {
            if ($("input[sFieldAs='UpID']").val() == "") {
                $("input[sFieldAs='UpID']").closest(".form-group").addClass("has-error");
                $("input[sFieldAs='UpID']").closest(".form-group").find(".help-block").html("请选择上级分类");
                bValidate = false;
            }
        }

        if (!bValidate) {
            error("请修正表单中红色框的内容。");
        }

        return bValidate;
    }
</script>