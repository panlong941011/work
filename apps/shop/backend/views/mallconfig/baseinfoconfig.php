<style>
    .imgWrap {
        width: 90px;
        height: 90px;
        background: #E9EDEF;
        margin-top: 15px;
        position: relative;
    }

    .frameShow {
        position: absolute;
        left: 0;
        top: 0;
        color: #fff;
        width: 100%;
        height: 100%;
        text-align: center;
        line-height: 90px;
        font-size: 40px;
    }

    .choseImg {
        width: 90px;
        height: 26px;
        background: #32c5d2;
        text-align: center;
        line-height: 26px;
        font-size: 14px;
        color: #fff;
        position: relative;
        margin-top: 10px;
        margin-bottom: 5px;
    }

    .choseImg .upFile {
        position: absolute;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        opacity: 0;
        filter: alpha(opacity=0);
    }

    .headeImg {
        width: 100%;
        height: 100%;
        display: none;
        position: relative;
    }

    .headeImg img {
        width: 100%;
        height: 100%;
    }

    .delImg {
        width: 15px;
        height: 15px;
        background: #666;
        color: #fff;
        -webkit-border-radius: 100% !important;
        -moz-border-radius: 100% !important;
        border-radius: 100% !important;
        position: absolute;
        right: -5px;
        top: -5px;
        font-size: 14px;
        text-align: center;
        line-height: 15px;
        z-index: 10;
        cursor: pointer;
    }

    .upImgTip {
        font-size: 14px;
        color: #666;
        line-height: 1.4;
        margin: 0px !important;
    }
</style>

<div class="breadcrumb" style="display:none">
    <h2>商城设置</h2>
    <h3>商城基本信息设置</h3>
</div>

<div class="row margin-top-10">

    <div class="col-md-12">
        <div class="note note-info">
            <h4 class="block"><?= Yii::t('app', '提示') ?></h4>
            <p>您可在此页面设置商城名称、客服电话、关于我们等信息。</p>
        </div>
    </div>


    <div class="col-md-12">
        <div class="portlet light bordered">
            <div class="form-actions margin-top-10">
                <div class="row">
                    <div class="col-md-12">
                        <button type="button" class="btn default" onclick="parent.closeCurrTab()">取消</button>
                        <button type="submit" class="btn green" onclick="objectSubmit()"><i class="fa fa-check"></i> 保存
                        </button>
                    </div>
                    <div class="col-md-6"></div>
                </div>
            </div>
            <div class="portlet-body form margin-top-10">
                <form name="objectform" action="/shop/mallconfig/baseinfoconfig"
                      class="horizontal-form" method="post" enctype="multipart/form-data">
                    <input type="hidden" name="_csrf" value="<?= Yii::$app->request->getCsrfToken() ?>">
                    <div class="form-body margin-top-10">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="row">
                                    <div class="form-group " sDataType="Text"
                                         sFieldAs="sMallName">
                                        <label class="control-label col-md-3">商城名称<span class="required"
                                                                                        aria-required="true">*</span>:</label>
                                        <div class="col-md-9">
                                            <input type="text" class="form-control" sDataType="Text" sFieldAs="sName"
                                                   placeholder="不得多于16字" value="<?= $sMallName ?>"
                                                   name="MallConfig[sMallName]"
                                            >
                                            <p class="help-block font-blue-dark">商城首页分享到微信等社交软件的标题将调用此处设置的商城名称；</p>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="form-group " sDataType="Text"
                                         sFieldAs="sMallDesc">
                                        <label class="control-label col-md-3">商城描述<span class="required"
                                                                                        aria-required="true">*</span>:</label>
                                        <div class="col-md-9">
                                            <input type="text" class="form-control" sDataType="Text" sFieldAs="sName"
                                                   placeholder="不得多于50字" value="<?= $sMallDesc ?>"
                                                   name="MallConfig[sMallDesc]"
                                            >
                                            <p class="help-block font-blue-dark">商城首页分享到微信等社交软件的描述将调用此处设置的商城描述；</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group " sDataType="Text"
                                         sFieldAs="sServiceNum">
                                        <label class="control-label col-md-3">客服电话<span class="required"
                                                                                        aria-required="true">*</span>:</label>
                                        <div class="col-md-9">
                                            <input type="text" class="form-control" sDataType="Text" sFieldAs="sName"
                                                   placeholder="" value="<?= $sServiceNum ?>"
                                                   name="MallConfig[sServiceNum]"
                                            >
                                            <p class="help-block font-blue-dark">商品详情页的客服电话将调用此处电话，请务必填写正确；</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group " sDataType="Text"
                                         sFieldAs="sMallLogo">
                                        <label class="control-label col-md-3">商城图标<span class="required"
                                                                                        aria-required="true">*</span>:</label>
                                        <div class="col-md-9">
                                            <div class="imgWrap">
                                                <? if ($sMallLogo) { ?>
                                                    <div class="frameShow" style="display: none">+</div>
                                                    <div class="headeImg" style="display: block">
                                                        <img src="<?= \Yii::$app->params['sUploadUrl'] ?>/<?= $sMallLogo ?>"
                                                             alt="">
                                                        <span class="delImg">×</span>
                                                    </div>
                                                <? } else { ?>
                                                    <div class="frameShow">+</div>
                                                    <div class="headeImg">
                                                        <img src="" alt="">
                                                        <span class="delImg">×</span>
                                                    </div>
                                                <? } ?>

                                            </div>
                                            <div class="choseImg">
                                                选择
                                                <input type="file" class="upFile"
                                                       accept="image/gif,image/jpeg,image/png">
                                            </div>
                                            <script>
                                                $(function () {
                                                    //上传图片
                                                    $('.upFile').on('change', function (event) {
                                                        var files = event.target.files || event.srcElement.files,
                                                            file = files[0],
                                                            reader = new FileReader();
                                                        console.log(file);

                                                        if (files.length === 0) {
                                                            return;
                                                        }
                                                        if (file.type.indexOf('image') == -1) { // 判断是否是图片
                                                            error('请上传正确的图片格式');
                                                            return;
                                                        }

                                                        var maxSize = 3000000; //设置上传图片的质量 这里是3M
                                                        if (file.size > maxSize) {
                                                            error("上传图片大小不得大于3M");
                                                            return;
                                                        }

                                                        reader.readAsDataURL(file);
                                                        reader.onload = function () {
                                                            var result = this.result;  //后端要取的图片的值 base64的 要在这个函数里面赋值
                                                            $('.headeImg img').attr('src', result);
                                                            $('.frameShow').hide();
                                                            $('.headeImg').show();
                                                        }
                                                    });
                                                    //删除图片
                                                    $('.delImg').on('click', function () {
                                                        $('.headeImg img').attr('src', '');
                                                        $('.frameShow').show();
                                                        $('.headeImg').hide();
                                                        $('.upFile').val('');
                                                    })
                                                })
                                            </script>

                                            <p class="help-block font-blue-dark">
                                                建议尺寸：320*320；<br>
                                                图片不得大于<span class="font-red">3</span>M；<br>
                                                商城首页分享到微信等社交软件中，图标将调用此处所设置商城图标；如下图
                                                <img src="/js/pages/img/u152.png" width="400"/>

                                            </p>
                                        </div>
                                    </div>
                                </div>


                            </div>
                            <div class="col-md-4">
                                <div class="row">
                                </div>
                                <div class="row">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="row">
                                </div>
                                <div class="row">
                                </div>
                            </div>
                        </div>


                        <div class="row">

                            <div class="col-md-6">
                                <div class="form-group " sDataType="Text"
                                     sFieldAs="sName">
                                    <label class="control-label col-md-3">关于我们:</label>
                                    <div class="col-md-9">


                                        <script id="sAbout" name="MallConfig[sAbout]" type="text/plain"
                                                style="width:800px;height:300px;"><?= $sAbout ?></script>
                                        <script
                                        type = "text/javascript" >
                                            $(document).ready
                                            (
                                                function () {
                                                    var ue = UE.getEditor('sAbout');
                                                }
                                            );
                                        </script>


                                        <p class="help-block font-blue-dark">此处填写内容，将在商城个人中心关于我们显示</p>
                                    </div>
                                </div>

                            </div>
                            <div class="row">
                            </div>


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
                        </div>

                </form>
            </div>
        </div>
    </div>
</div>

<script src="<?= Yii::$app->homeUrl ?>/js/global/plugins/ueditor/ueditor.config.js" type="text/javascript"></script>
<script src="<?= Yii::$app->homeUrl ?>/js/global/plugins/ueditor/ueditor.all.min.js" type="text/javascript"></script>

<script>
    function objectSubmit() {

        clearToastr();

        var bValidate = true;

        $(".form-group").removeClass("has-error");
        $(".has-error").remove();

        if ($("div[sFieldAs='sMallName'] input").val() == "") {
            $("div[sFieldAs='sMallName']").addClass("has-error");
            $("div[sFieldAs='sMallName']").find(".help-block").after("<p class='help-block has-error'>请输入商城名称</p>");
            bValidate = false;
        } else if ($("div[sFieldAs='sMallName'] input").val().length > 16) {
            $("div[sFieldAs='sMallName']").addClass("has-error");
            $("div[sFieldAs='sMallName']").find(".help-block").after("<p class='help-block has-error'>不得多于16字</p>");
            bValidate = false;
        }

        if ($("div[sFieldAs='sMallDesc'] input").val() == "") {
            $("div[sFieldAs='sMallDesc']").addClass("has-error");
            $("div[sFieldAs='sMallDesc']").find(".help-block").after("<p class='help-block has-error'>请输入商城描述</p>");
            bValidate = false;
        } else if ($("div[sFieldAs='sMallDesc'] input").val().length > 50) {
            $("div[sFieldAs='sMallDesc']").addClass("has-error");
            $("div[sFieldAs='sMallDesc']").find(".help-block").after("<p class='help-block has-error'>不得多于50字</p>");
            bValidate = false;
        }

        if ($("div[sFieldAs='sServiceNum'] input").val() == "") {
            $("div[sFieldAs='sServiceNum']").addClass("has-error");
            $("div[sFieldAs='sServiceNum']").find(".help-block").after("<p class='help-block has-error'>请输入客服电话</p>");
            bValidate = false;
        } else if (!$("div[sFieldAs='sServiceNum'] input").val().match(/^[0-9]{1,}$/)) {
            $("div[sFieldAs='sServiceNum']").addClass("has-error");
            $("div[sFieldAs='sServiceNum']").find(".help-block").after("<p class='help-block has-error'>请输入数字</p>");
            bValidate = false;
        }

        if ($("div[sFieldAs='sMallLogo'] .headeImg img").attr('src') == "") {
            $("div[sFieldAs='sMallLogo']").addClass("has-error");
            $("div[sFieldAs='sMallLogo']").find(".help-block").after("<p class='help-block has-error'>请上传商城图标</p>");
            bValidate = false;
        }

        if (!bValidate) {
            error("请修正表单中红色框的内容。");
            return false;
        }

        $(document.objectform).append("<input type='hidden' name='MallConfig[sMallLogo]' value='" + $("div[sFieldAs='sMallLogo'] .headeImg img").attr('src') + "'>");

        document.objectform.submit();
    }

    <? if ($_GET['save'] == 'yes') { ?>
    $(document).ready(
        function () {
            success('保存成功');
        }
    )

    <? } ?>
</script>