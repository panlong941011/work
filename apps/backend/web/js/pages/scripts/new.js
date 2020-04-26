$(document).ready
(
    function () {
        $(".multiselect").multiselect({
            maxHeight: 200,
            nSelectedText: '',
            nonSelectedText: '请选择。。。',
            selectAllText: '全选',
            enableFiltering: true,
            includeSelectAllOption: true,
        });


        $("#newDetailRowBtn").click
        (
            function () {
                var cloneRow = $("#detailCloneRow").clone(true);
                cloneRow.removeClass('hide').removeAttr('id').appendTo("#detailTable tbody");
            }
        );

        $(".detailCloneRowBtn").click
        (
            function () {
                var cloneRow = $(this).closest('tr').clone(true);
                cloneRow.insertAfter($(this).closest('tr'));

                return false;
            }
        );

        $(".detailDelRowBtn").click
        (
            function () {
                $(this).closest('tr').remove();
            }
        );
    }
);

function showRef(sObjectName, sRefObjectName, sFieldAs) {
    $("body").prop("refobject", null);

    info('正在弹出窗口。。。。。');
    $.post
    (
        sHomeUrl + '/' + sRefObjectName.toLowerCase() + '/showref?sFieldAs=' + sFieldAs + '&sObjectName=' + encodeURI(sObjectName),
        {
            ID: $("input[name='arrObjectData[" + sObjectName + "][" + sFieldAs + "]']").val(),
            sName: $("input[name='arrObjectData[" + sObjectName + "][" + sFieldAs + "Name]']").val()
        },
        function (data) {
            if (sRefObjectName == 'System/SysUser') {
                var modal = openModal(data, 600, 250);
            } else {
                var modal = openModal(data, $(window).width() * 0.8, $(window).height() * 0.8);
            }
        }
    );
}

function showDetailRef(input, sObjectName, sRefObjectName, sFieldAs) {
    $("body").prop("refobject", input);

    info('正在弹出窗口。。。。。');
    $.post
    (
        sHomeUrl + '/' + sRefObjectName.toLowerCase() + '/showref?sFieldAs=' + sFieldAs + '&sObjectName=' + encodeURI(sObjectName),
        {
            ID: $("input[name='arrObjectData[" + sObjectName + "][" + sFieldAs + "]']").val(),
            sName: $("input[name='arrObjectData[" + sObjectName + "][" + sFieldAs + "Name]']").val()
        },
        function (data) {
            if (sRefObjectName == 'System/SysUser') {
                var modal = openModal(data, 600, 250);
            } else {
                var modal = openModal(data, '1050', $(window).height() * 0.8);
            }
        }
    );
}

function showUpload(sObjectName, sFieldAs, sLinkField) {
    $("body").prop("refobject", null);

    info('正在弹出窗口。。。。。');
    $.post
    (
        sHomeUrl + '/' + sObjectName.toLowerCase() + '/showupload?sFieldAs=' + sFieldAs + '&sLinkField=' + sLinkField + '&sObjectName=' + encodeURI(sObjectName),
        {
            sName: $("input[name='arrObjectData[" + sObjectName + "][" + sFieldAs + "]']").val(),
            sPath: $("input[name='arrObjectData[" + sObjectName + "][" + sLinkField + "]']").val()
        },
        function (data) {
            var modal = openModal(data, 400, 70);
        }
    );
}

function showDetailUpload(input, sObjectName, sFieldAs, sLinkField) {
    $("body").prop("refobject", input);

    info('正在弹出窗口。。。。。');
    $.post
    (
        sHomeUrl + '/' + sObjectName.toLowerCase() + '/showupload?sFieldAs=' + sFieldAs + '&sLinkField=' + sLinkField + '&sObjectName=' + encodeURI(sObjectName),
        {
            sName: $("input[name='arrObjectData[" + sObjectName + "][" + sFieldAs + "]']").val(),
            sPath: $("input[name='arrObjectData[" + sObjectName + "][" + sLinkField + "]']").val()
        },
        function (data) {
            var modal = openModal(data, 400, 70);
        }
    );
}


function uploadSave(sObjectName, sFieldAs, sLinkField, data) {
    var refobject = $("body").prop("refobject");
    if (refobject) {
        $("input[name='arrObjectData[" + sObjectName + "][" + sFieldAs + "][]']", $(refobject).parent()).val(data.sName);
        $("input[name='arrObjectData[" + sObjectName + "][" + sLinkField + "][]']", $(refobject).parent()).val(data.sPath);
    } else {
        $("input[name='arrObjectData[" + sObjectName + "][" + sFieldAs + "]']").val(data.sName);
        $("input[name='arrObjectData[" + sObjectName + "][" + sLinkField + "]']").val(data.sPath);
    }

    $("body").prop("refobject", null);
}

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
}

function objectSubmit() {
    clearToastr();

    var bSuccess = true;
    $(".form-group,.input-group").removeClass("has-error");
    $(".has-error").remove();
    $(".form-group", document.objectform).each
    (
        function () {
            var sValue = $(this).find("[name='arrObjectData[" + $(this).attr('sObjectName') + "][" + $(this).attr('sFieldAs') + "]']").val();

            if ($(this).attr('sDataType') == 'TextArea') {
                return;
            }

            //必填项
            if ($(this).find(".required").size() > 0) {
                if ($.trim(sValue) == "") {
                    bSuccess = false;
                    $(this).addClass("has-error");
                    $(this).find(".help-block").after("<p class='help-block has-error'>不能为空</p>");
                }
            }

            //整型
            if ($(this).attr("sDataType") == "Int") {
                if ($.trim(sValue) != "" && !sValue.match(/^[0-9]{1,}$/)) {
                    bSuccess = false;
                    $(this).addClass("has-error");
                    $(this).find(".help-block").after("<p class='help-block has-error'>请填写数字</p>");
                }
            }

            //浮点型
            if ($(this).attr("sDataType") == "Float") {
                if ($.trim(sValue) != "" && !sValue.match(/^[0-9\.]{1,}$/)) {
                    bSuccess = false;
                    $(this).addClass("has-error");
                    $(this).find(".help-block").after("<p class='help-block has-error'>请填写数字</p>");
                }
            }

        }
    );

    $("td.required").removeClass("has-error");
    $("tr[id!='detailCloneRow'] td.required", document.objectform).each
    (
        function () {
            if ($(this).find("[name='arrObjectData[" + $(this).attr('sObjectName') + "][" + $(this).attr('sFieldAs') + "][][]']").size() > 0) {
                var sValue = $(this).find("[name='arrObjectData[" + $(this).attr('sObjectName') + "][" + $(this).attr('sFieldAs') + "][][]']").val();
            } else {
                var sValue = $(this).find("[name='arrObjectData[" + $(this).attr('sObjectName') + "][" + $(this).attr('sFieldAs') + "][]']").val();
            }


            if ($(this).attr('sDataType') == 'TextArea') {
                return;
            }

            if ($.trim(sValue) == "") {
                bSuccess = false;
                $(this).addClass("has-error");
            }
        }
    );


    if (!bSuccess) {
        error("请修正表单中红色框的内容。");
        return false;
    }

    if (typeof beforeObjectSubmit == 'function') {
        var ret = beforeObjectSubmit();
        if (!ret) {
            return false;
        }
    }

    $("#detailCloneRow").remove();
    $("[ignore='true'], option[value='multiselect-all']").attr('name', 'xxx');


    $("tbody tr", $("#detailTable")).each
    (
        function (i) {
            var lRowIndex = i;
            $("select,input,textarea", this).each
            (
                function () {
                    if ($(this).attr('name').indexOf('[][]') > -1) {
                        $(this).attr('name', $(this).attr('name').replace('[][]', '[' + lRowIndex + '][]'));
                    } else if ($(this).attr('name').indexOf('[]') > -1) {
                        $(this).attr('name', $(this).attr('name').replace('[]', '[' + lRowIndex + ']'));
                    }
                }
            );
        }
    );


    document.objectform.submit();
}