//设置面包屑

//设置模块
if ($(".breadcrumb h1").size()) {
    $(".page-breadcrumb").append("<li><span>" + $(".breadcrumb h1").html() + "</span><i class=\"fa fa-circle\"></i></li>");
}

//设置对象
if ($(".breadcrumb h2").size()) {
    $(".page-breadcrumb").append("<li><span>" + $(".breadcrumb h2").html() + "</span><i class=\"fa fa-circle\"></i></li>");
}

//设置动作
if ($(".breadcrumb h3").size()) {
    $(".page-breadcrumb").append("<li><span>" + $(".breadcrumb h3").html() + "</span></li>");
}

function clearToastr() {
    toastr.clear();
}

function info(sTitle, sDesc) {
    toastr.options = {
        "closeButton": false,
        "debug": false,
        "positionClass": "toast-top-center",
        "showDuration": "1000",
        "hideDuration": "1000",
        "timeOut": "5000",
        "extendedTimeOut": "1000",
        "showEasing": "swing",
        "hideEasing": "linear",
        "showMethod": "fadeIn",
        "hideMethod": "fadeOut"
    }
    Command: toastr['info'](sDesc, sTitle)
}

function waring(sTitle, sDesc) {
    toastr.options = {
        "closeButton": false,
        "debug": false,
        "positionClass": "toast-top-center",
        "showDuration": "1000",
        "hideDuration": "1000",
        "timeOut": "5000",
        "extendedTimeOut": "1000",
        "showEasing": "swing",
        "hideEasing": "linear",
        "showMethod": "fadeIn",
        "hideMethod": "fadeOut"
    }
    Command: toastr['warning'](sDesc, sTitle)
}

function error(sTitle, sDesc) {
    toastr.options = {
        "closeButton": false,
        "debug": false,
        "positionClass": "toast-top-center",
        "showDuration": "1000",
        "hideDuration": "1000",
        "timeOut": "5000",
        "extendedTimeOut": "1000",
        "showEasing": "swing",
        "hideEasing": "linear",
        "showMethod": "fadeIn",
        "hideMethod": "fadeOut"
    }
    Command: toastr['error'](sDesc, sTitle)
}

function success(sTitle, sDesc) {
    toastr.options = {
        "closeButton": false,
        "debug": false,
        "positionClass": "toast-top-center",
        "showDuration": "1000",
        "hideDuration": "1000",
        "timeOut": "5000",
        "extendedTimeOut": "1000",
        "showEasing": "swing",
        "hideEasing": "linear",
        "showMethod": "fadeIn",
        "hideMethod": "fadeOut"
    }
    Command: toastr['success'](sDesc, sTitle)
}

function confirmation(obj, title, callback, placement) {
    var  listcount = obj.listtable.getSelectedLength();
    if (listcount == 0) {
        error("至少选择一条记录。");
        return;
    }
    swal({
            title: title,
            text: "",
            type: "info",
            showConfirmButton: true,
            showCancelButton: true,
            confirmButtonClass: "btn-success",
            cancelButtonClass: "btn-default",
            confirmButtonText: "确定",
            cancelButtonText: "取消",
        },
        function (isConfirm) {
            if (isConfirm) {
                callback(obj);
                swal("Cancelled", "", "success");
            } else {
                swal("Cancelled", "", "success");
            }
        });
}

function openModal(sContent, lWidth, lHeight) {
    $("#myermmodal").html(sContent);
    $("#myermmodal").modal({
        width: lWidth || null,
        height: lHeight || null,
    });

    return $("#myermmodal");
}

function closeModal() {
    $('#myermmodal').modal('hide');
}