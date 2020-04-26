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
    <h2>经销商管理</h2>
    <h3>经销商推广二维码设置</h3>
</div>

<div class="row margin-top-10">

    <div class="col-md-12">
        <div class="note note-info">
            <h4 class="block"><?= Yii::t('app', '提示') ?></h4>
            <p>您可在此页面设置经销商店铺二维码样式</p>
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
                <form name="objectform" action="/shop/sellerconfig/qrcode"
                      class="horizontal-form" method="post" enctype="multipart/form-data">
                    <input type="hidden" name="_csrf" value="<?= Yii::$app->request->getCsrfToken() ?>">
                    <div class="form-body margin-top-10">
                        <div class="row">
                            <div class="col-md-6">

                                <div class="row">
                                    <div class="form-group " sDataType="Text"
                                         sFieldAs="sMallLogo">
                                        <label class="control-label col-md-3">二维码背景图片<span class="required"
                                                                                        aria-required="true">*</span>:</label>
                                        <div class="col-md-9">
                                            <div class="imgWrap">
                                                <? if ($config['sQrcode']) { ?>
                                                    <div class="frameShow" style="display: none">+</div>
                                                    <div class="headeImg" style="display: block">
                                                        <img src="<?= \Yii::$app->params['sUploadUrl'] ?>/<?= $config['sQrcode'] ?>"
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
                                                建议尺寸：610X920；<br>
                                                图片不得大于<span class="font-red">3</span>M；<br>
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

        $(document.objectform).append("<input type='hidden' name='sQrcode' value='" + $("div[sFieldAs='sMallLogo'] .headeImg img").attr('src') + "'>");

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