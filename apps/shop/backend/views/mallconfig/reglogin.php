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
            <p>你可在此设置登录/注册方式、供应商是否可登录管理后台</p>
            <p class="required">此处操作修改请慎重，勿频繁修改</p>
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
                                         sFieldAs="sMallName">
                                        <label class="control-label col-md-3">是否需要手机号注册<span class="required"
                                                                                             aria-required="true">*</span>:</label>
                                        <div class="col-md-9">
                                            <div class="radio-list input-group">
                                                <label class="radio-inline"><input type="radio" name="bRequireMobileReg"
                                                                                   value="1"
                                                                                   <? if ($bRequireMobileReg == '1') { ?>checked<? } ?>> <?= Yii::t('app',
                                                        '是') ?> </label>
                                                <label class="radio-inline"><input type="radio" name="bRequireMobileReg"
                                                                                   value="0"
                                                                                   <? if ($bRequireMobileReg == '0' || $bRequireMobileReg == '') { ?>checked<? } ?>> <?= Yii::t('app',
                                                        '否') ?> </label>
                                            </div>
                                            <p class="help-block font-blue-dark">一旦选择是，所有用户都需要用手机号方可注册。
                                                若为否，则微信号自动注册；</p>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="form-group " sDataType="Text"
                                         sFieldAs="sMallDesc">
                                        <label class="control-label col-md-3">是否允许供应商登录管理后台<span class="required"
                                                                                                 aria-required="true">*</span>:</label>
                                        <div class="col-md-9">
                                            <div class="radio-list input-group">
                                                <label class="radio-inline"><input type="radio"
                                                                                   name="bSupplierLoginBackend"
                                                                                   value="1"
                                                                                   <? if ($bSupplierLoginBackend == '1') { ?>checked<? } ?>> <?= Yii::t('app',
                                                        '是') ?> </label>
                                                <label class="radio-inline"><input type="radio"
                                                                                   name="bSupplierLoginBackend"
                                                                                   value="0"
                                                                                   <? if ($bSupplierLoginBackend == '0' || $bSupplierLoginBackend == '') { ?>checked<? } ?>> <?= Yii::t('app',
                                                        '否') ?> </label>
                                            </div>
                                            <p class="help-block font-blue-dark">一旦选择是，供应商登录管理后台，并获得以下操作功能：<br>
                                                1.查看供应商为自己的订单；<br>
                                                2.发货</p>
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
                                    <label class="control-label col-md-3">用户协议:</label>
                                    <div class="col-md-9">


                                        <script id="sMemberContract" name="sMemberContract" type="text/plain"
                                                style="width:800px;height:300px;"><?= $sMemberContract ?></script>
                                        <script
                                        type = "text/javascript" >
                                            $(document).ready
                                            (
                                                function () {
                                                    var ue = UE.getEditor('sMemberContract');
                                                }
                                            );
                                        </script>


                                        <p class="help-block font-blue-dark">当选择手机号注册时，可在注册页面点击用户协议，并查看用户协议</p>
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