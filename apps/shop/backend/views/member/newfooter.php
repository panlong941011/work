<script>
    function beforeObjectSubmit() {
        var bValidate = true;

        $(".help-block").html("");

        if ($("input[sFieldAs='sMobile']").val() != "") {
            if (!$("input[sFieldAs='sMobile']").val().match(/^[0-9]{11}$/)) {
                bValidate = false;
                $("input[sFieldAs='sMobile']").closest(".form-group").addClass("has-error");
                $("input[sFieldAs='sMobile']").closest(".form-group").find(".help-block").html("请填写正确的手机号。");
            }
        }

        if ($("textarea[sFieldAs='sNote']").val() != "") {
            if ($("textarea[sFieldAs='sNote']").val().length > 35) {
                bValidate = false;
                $("textarea[sFieldAs='sNote']").closest(".form-group").addClass("has-error");
            }
        }

        if (!bValidate) {
            error("请修正表单中红色框的内容。");
        }

        return bValidate;
    }
</script>