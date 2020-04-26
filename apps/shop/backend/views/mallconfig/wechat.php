<script src="<?= Yii::$app->homeUrl ?>/js/pages/scripts/new.js" type="text/javascript"></script>

<div class="breadcrumb" style="display:none">
    <h3>公众号配置</h3>
</div>


<div class="row margin-top-10">
    <div class="col-md-12">
        <div class="portlet light bordered">

            <div class="portlet-body form">
                <form name="objectform" action="/shop/mallconfig/wechat"
                      class="horizontal-form" method="post" enctype="multipart/form-data">
                    <input type="hidden" name="_csrf" value="<?= Yii::$app->request->getCsrfToken() ?>">
                    <div class="form-body">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="row">
                                    <div class="form-group " sObjectName="Shop/Supplier" sDataType="Text"
                                         sFieldAs="sName">
                                        <label class="control-label col-md-3">AppID<span class="required"
                                                                                         aria-required="true">*</span>:</label>
                                        <div class="col-md-9">
                                            <input type="text" class="form-control" sDataType="Text"
                                                   placeholder="" value="<?=$sAppID?>"
                                                   name="sAppID">
                                            <p class="help-block"></p>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="form-group " sObjectName="Shop/Supplier" sDataType="Text"
                                         sFieldAs="sName">
                                        <label class="control-label col-md-3">AppSecret<span class="required"
                                                                                             aria-required="true">*</span>:</label>
                                        <div class="col-md-9">
                                            <input type="text" class="form-control" sDataType="Text"
                                                   placeholder="" value="<?=$sAppSecret?>"
                                                   name="sAppSecret">
                                            <p class="help-block"></p>
                                        </div>
                                    </div>
                                </div>


                                <div class="row">
                                    <div class="form-group " sObjectName="Shop/Supplier" sDataType="Text"
                                         sFieldAs="sName">
                                        <label class="control-label col-md-3">Token<span class="required"
                                                                                         aria-required="true">*</span>:</label>
                                        <div class="col-md-9">
                                            <input type="text" class="form-control" sDataType="Text"
                                                   placeholder="" value="<?=$sToken?>"
                                                   name="sToken">
                                            <p class="help-block"></p>
                                        </div>
                                    </div>
                                </div>


                                <div class="row">
                                    <div class="form-group " sObjectName="Shop/Supplier" sDataType="Text"
                                         sFieldAs="sName">
                                        <label class="control-label col-md-3">MerchantID<span class="required"
                                                                                             aria-required="true">*</span>:</label>
                                        <div class="col-md-9">
                                            <input type="text" class="form-control" sDataType="Text"
                                                   placeholder="" value="<?=$sMerchantID?>"
                                                   name="sMerchantID">
                                            <p class="help-block"></p>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="form-group " sObjectName="Shop/Supplier" sDataType="Text"
                                         sFieldAs="sName">
                                        <label class="control-label col-md-3">Key<span class="required"
                                                                                              aria-required="true">*</span>:</label>
                                        <div class="col-md-9">
                                            <input type="text" class="form-control" sDataType="Text"
                                                   placeholder="" value="<?=$sWXPayKey?>"
                                                   name="sWXPayKey">
                                            <p class="help-block"></p>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>


                    </div>

                </form>
            </div>
            <div class="form-actions">
                <div class="row">
                    <div class="col-md-1"></div>
                    <div class="col-md-11">
                        <button type="submit" class="btn green" onclick="objectSubmit()"><i class="fa fa-check"></i> 保存
                        </button>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>


<script>
function objectSubmit()
{
    var bHasError = false;

    $(".help-block").html("");

    if ($.trim(document.objectform.sAppID.value) == "") {
        $(document.objectform.sAppID).closest(".form-group").addClass("has-error");
        $(document.objectform.sAppID).closest(".form-group").find(".help-block").html("内容不能为空。");
        bHasError = true;
    } else {
        $(document.objectform.sAppID).closest(".form-group").removeClass("has-error");
    }

    if ($.trim(document.objectform.sAppSecret.value) == "") {
        $(document.objectform.sAppSecret).closest(".form-group").addClass("has-error");
        $(document.objectform.sAppSecret).closest(".form-group").find(".help-block").html("内容不能为空。");
        bHasError = true;
    } else {
        $(document.objectform.sAppSecret).closest(".form-group").removeClass("has-error");
    }

    if ($.trim(document.objectform.sToken.value) == "") {
        $(document.objectform.sToken).closest(".form-group").addClass("has-error");
        $(document.objectform.sToken).closest(".form-group").find(".help-block").html("内容不能为空。");
        bHasError = true;
    } else {
        $(document.objectform.sToken).closest(".form-group").removeClass("has-error");
    }

    if (bHasError) {
        error("请修正表单中红色框的内容。");
        return false;
    }

    document.objectform.submit();

}
</script>