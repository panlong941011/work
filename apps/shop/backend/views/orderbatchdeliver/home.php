<style>
    .page-bar {
        display: none;
    }

    .returnMsg {

        background: #E7ECF1;
        margin: 0 0 10px 15px;
        color: #364150;
        border: 0;
    }
    .div_msg{
        background-color: white;
        color: #34495E;
        overflow: scroll;
        position: absolute;
        z-index: 10000;
        height: 400px;
        top: 80px;
    }
    .div_btn{
       clear: both;
        margin-left: 100px;
    }
    .div_front{
        background-color: #364150;
        opacity: 0.5;
        z-index: 9999;
        position: absolute;
        top: 0px;
        left: 0px;
        display: none
    }
</style>
<script>
    function showFontCover() {
        window.document.getElementById('div_front').style.display='block';
        window.document.getElementById('div_front').style.height=window.document.body.scrollHeight+'px';
        window.document.getElementById('div_front').style.width=window.document.body.scrollWidth+'px';
    }
    function closeMsg() {
        window.document.getElementById('div_front').style.display='none';
        window.document.getElementById('div_msg').style.display='none';
    }
</script>
<div id="div_front" class="div_front"></div>
<div class="row margin-top-10">
    <div class="col-md-12">
        <div class="portlet light bordered">
            <div class="portlet-body form">
                <form name="objectform" class="horizontal-form" method="post" enctype="multipart/form-data">
                    <input type="hidden" name="_csrf" value="UkJlb1c5cHAUcC85JAseRRQYHVsiTRsbZRtXKiUKQxswEQwYE1AoNA==">
                    <div class="form-body">
                        <h3 class="form-section">订单批量发货</h3>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label col-md-3">Excel订单文件:</label>
                                    <div class="col-md-9">
                                        <input type="file" class="form-control" name="file">
                                        <span class="help-block">  </span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <p style="margin:10px 0 0 15px">
                            <span class="help-block">使用说明：</span>
                            <span class="help-block">① 请先从【订单】列表导出需要发货的订单</span>
                            <span class="help-block">② 填写完整订单文件的前两列（快递公司、物流单号）</span>
                        </p>

                        <div class="form-actions margin-top-10" style="margin: 0 0 0 15px;">
                            <div class="row">
                                <div class="col-md-12">
                                    <button type="button" class="btn default" onclick="parent.closeCurrTab()">取消
                                    </button>
                                    <button type="button" class="btn green" onclick="submitDeliver()"><i
                                            class="fa fa-check"></i> 提交
                                    </button>
                                </div>
                                <div class="col-md-6"></div>
                            </div>
                        </div>

                        <? if ($arrMessage) { ?>
                            <div id="div_msg" class="div_msg"  >
                                <? foreach ($arrMessage as $message) { ?>
                                    <p><?= $message ?></p>
                                <? } ?>
                                <div class="div_btn">
                                    <button type="button" class="btn green" onclick="closeMsg()"><i
                                            class="fa fa-check"></i> 确认
                                    </button>
                                </div>
                            </div>
                            <script>
                                showFontCover();
                                window.document.getElementById('div_msg').style.width=window.document.body.scrollWidth-100+'px';
                            </script>
                        <? } ?>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>


    function submitDeliver() {
        if ($.trim(document.objectform.file.value) == "") {
            error("请选择要上传的Excel文件。");
            return false;
        }

        var ext = document.objectform.file.value.substr(document.objectform.file.value.lastIndexOf('.') + 1).toLowerCase();

        if (ext != 'xls' && ext != 'xlsx') {
            error("请选择扩展名为xls或者xlsx的文件。");
            return false;
        }

        document.objectform.action = sHomeUrl + '/' + sObjectName + '/run';
        document.objectform.submit();
        showMsg("正在提交，请稍等。。。");
        showFontCover();
    }
    function showMsg(sTitle, sDesc)
    {
        toastr.options = {
            "closeButton": false,
            "debug": false,
            "positionClass": "toast-top-center",
            "showDuration": "1000",
            "hideDuration": "500000",
            "timeOut": "500000",
            "extendedTimeOut": "500000",
            "showEasing": "swing",
            "hideEasing": "linear",
            "showMethod": "fadeIn",
            "hideMethod": "fadeOut"
        }
        Command: toastr['info'](sDesc, sTitle)
    }

</script>