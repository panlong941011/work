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
    <h2>会员管理</h2>
    <h3>充值设置</h3>
</div>

<div class="row margin-top-10">

    <div class="col-md-12">
        <div class="note note-info">
            <h4 class="block"><?= Yii::t('app', '提示') ?></h4>
            <p>您可在此页面设置充值多少钱可赠送多少金币，该设置仅适用于用户微信支付充值</p>
        </div>
    </div>


    <div class="col-md-12">
        <div class="portlet light bordered">

            <div class="portlet-body form margin-top-10">
                <form name="objectform"
                      class="horizontal-form" method="post" enctype="multipart/form-data">
                    <input type="hidden" name="_csrf" value="<?= Yii::$app->request->getCsrfToken() ?>">
                    <div class="form-body margin-top-10">
                        <? foreach ($arrConfig as $config) { ?>
                            <div class="row margin-top-10">
                                <div class="col-md-6">
                                    <div class="input-group">
                                                        <span class="input-group-btn">
                                                            <button class="btn" type="button">满</button>
                                                        </span>
                                        <input type="text" class="form-control" name="full[]" value="<?=$config->fFull?>" >
                                        <span class="input-group-btn">
                                                            <button class="btn" type="button">赠送</button>
                                                        </span>
                                        <input type="text" class="form-control" name="give[]"  value="<?=$config->fGive?>">
                                        <span class="input-group-btn">
                                                            <button class="btn" type="button">元</button>
                                                        </span>
                                        <span class="input-group-btn">
                                                            <button class="btn red" type="button" onclick="$(this).closest('.row').remove()">X</button>
                                                        </span>
                                    </div>
                                </div>
                            </div>
                        <? } ?>
                    </div>
                    <div class="form-body margin-top-10">
                        <div class="row hide margin-top-10" id="clone">
                            <div class="col-md-6">
                                <div class="input-group">
                                                        <span class="input-group-btn">
                                                            <button class="btn" type="button">满</button>
                                                        </span>
                                    <input type="text" class="form-control" name="full[]" >
                                    <span class="input-group-btn">
                                                            <button class="btn" type="button">赠送</button>
                                                        </span>
                                    <input type="text" class="form-control" name="give[]" >
                                    <span class="input-group-btn">
                                                            <button class="btn" type="button">元</button>
                                                        </span>
                                    <span class="input-group-btn">
                                                            <button class="btn red" type="button" onclick="$(this).closest('.row').remove()">X</button>
                                                        </span>
                                </div>
                            </div>
                        </div>
                        <div class="row margin-top-10">
                            <div class="col-md-6">
                                <button class="btn" type="button" onclick="addNew()">+增加优惠项</button>
                            </div>
                        </div>
                        <div class="row margin-top-10">
                            <div class="col-md-6">
                                1.最多设置5个优惠项目；<br>
                                2. 当未设置任何优惠项时，买多少，即获得多少金币<br>
                                3. 当有设置优惠项时，获得金币 = 支付金额 + 赠送金币<br>
                                4. 满多少不得填写等于或小于0<br>
                            </div>
                        </div>
                </form>
            </div>

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
        </div>
    </div>
</div>

<script src="<?= Yii::$app->homeUrl ?>/js/global/plugins/ueditor/ueditor.config.js" type="text/javascript"></script>
<script src="<?= Yii::$app->homeUrl ?>/js/global/plugins/ueditor/ueditor.all.min.js" type="text/javascript"></script>

<script>
    function objectSubmit() {
        clearToastr();

        var bValidate = true;


        $(".form-body:first input").each(
            function () {
                var pattren = /^\d+(\.\d+)?$/;

                if (!pattren.test($(this).val())) {
                    error("请输入正确的金额");
                    bValidate = false;
                    return false;
                }
            }
        );


        if (bValidate) {
            document.objectform.submit();
        }
    }
    
    function addNew() {

        if ($(".form-body:first .input-group").size() == 5) {
            return false;
        }

        var node = $("#clone").clone(true);
        $(".form-body:first").append(node.removeClass('hide').removeAttr('id'));
    }

    <? if ($_GET['save'] == 'yes') { ?>
    $(document).ready(
        function () {
            success('保存成功');
        }
    )

    <? } ?>
</script>