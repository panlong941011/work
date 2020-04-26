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
            <p>您可在此页面设置经销商入驻分享标题、入驻说明、入驻协议等。</p>
        </div>
    </div>


    <div class="col-md-12">
        <div class="portlet light bordered">
            <div class="form-actions margin-top-10">
                <div class="row">
                    <div class="col-md-12">
                        <button type="button" class="btn default" onclick="parent.closeCurrTab()">取消</button>
                        <button type="submit" class="btn green" onclick="document.objectform.submit()"><i class="fa fa-check"></i> 保存
                        </button>
                    </div>
                    <div class="col-md-6"></div>
                </div>
            </div>
            <div class="portlet-body form margin-top-10">
                <form name="objectform"
                      class="horizontal-form" method="post" enctype="multipart/form-data">
                    <input type="hidden" name="_csrf" value="<?= Yii::$app->request->getCsrfToken() ?>">
                    <div class="form-body margin-top-10">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="row">
                                    <div class="form-group " sDataType="Text"
                                         sFieldAs="sDefSearchWord">
                                        <label class="control-label col-md-3">分享标题<span class="required"
                                                                                         aria-required="true">*</span>:</label>
                                        <div class="col-md-9">
                                            <input type="text" class="form-control" sDataType="Text" sFieldAs="sDefSearchWord"
                                                   placeholder="" value="<?= $sSellerJoinShareTitle ?>"
                                                   name="sSellerJoinShareTitle"
                                            >
                                            <p class="help-block font-blue-dark">入驻页面分享到微信等社交软件的标题将调用此处设置的文字；</p>
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
                                    <label class="control-label col-md-3">入驻说明<span class="required"
                                                                                    aria-required="true">*</span>:</label>
                                    <div class="col-md-9">


                                        <script id="sSellerJoinDesc" name="sSellerJoinDesc" type="text/plain"
                                                style="width:800px;height:300px;"><?= $sSellerJoinDesc ?></script>
                                        <script
                                        type = "text/javascript" >
                                            $(document).ready
                                            (
                                                function () {
                                                    var ue = UE.getEditor('sSellerJoinDesc');
                                                }
                                            );
                                        </script>


                                        <p class="help-block font-blue-dark">此处填写内容，将在经销商入驻页面显示</p>
                                    </div>
                                </div>

                            </div>
                            <div class="row">
                            </div>
                        </div>
                        <div class="row">

                            <div class="col-md-6">
                                <div class="form-group " sDataType="Text"
                                     sFieldAs="sName">
                                    <label class="control-label col-md-3">入驻协议<span class="required"
                                                                                    aria-required="true">*</span>:</label>
                                    <div class="col-md-9">

                                        <script id="sSellerJoinContract" name="sSellerJoinContract" type="text/plain"
                                                style="width:800px;height:300px;"><?= $sSellerJoinContract ?></script>
                                        <script
                                        type = "text/javascript" >
                                            $(document).ready
                                            (
                                                function () {
                                                    var ue = UE.getEditor('sSellerJoinContract');
                                                }
                                            );
                                        </script>


                                        <p class="help-block font-blue-dark">此处填写内容，将在经销商入驻页面的入驻协议中显示
                                        </p>
                                    </div>
                                </div>

                            </div>
                            <div class="row">
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


    <? if ($_GET['save'] == 'yes') { ?>
    $(document).ready(
        function () {
            success('保存成功');
        }
    )

    <? } ?>
</script>