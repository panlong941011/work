<script>
    function beforeObjectSubmit() {
        var bValidate = true;

        $(".has-error .help-block").html("");


        if ($("input[sFieldAs='sName']").val() != "") {
            if ($("input[sFieldAs='sName']").val().length > 6) {
                bValidate = false;
                $("input[sFieldAs='sName']").closest(".form-group").addClass("has-error");
                $("input[sFieldAs='sName']").closest(".form-group").find(".help-block").html("不得多于6字");
            }
        }

        if (!bValidate) {
            error("请修正表单中红色框的内容。");
        }

        return bValidate;
    }
</script>