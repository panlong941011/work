<div class="breadcrumb" style="display:none">
    <h2>经销商管理</h2>
    <h3>经销商提现设置</h3>
</div>

<style>
    .inputBox {
        width: 102px;
        border: 1px solid #c2cad8;
        position: relative;
    }

    .inputBox input {
        width: 70px;
        height: 30px;
        border: 0px solid #c2cad8;
    }

    .inputBox span {
        position: absolute;
        width: 30px;
        height: 30px;
        text-align: center;
        line-height: 30px;
        color: #000;
        font-weight: 700;
        right: 0;
        top: 0;
        border-left: 1px solid #ccc;
    }
</style>
<div class="row margin-top-10">

    <div class="col-md-12">
        <div class="note note-info">
            <h4 class="block"><?= Yii::t('app', '提示') ?></h4>
            <p>您可在此页面设置经销商提现最低金额</p>
        </div>
    </div>


    <div class="col-md-12">
        <div class="portlet light bordered">
            <div class="form-actions margin-top-10">
                <div class="row">
                    <div class="col-md-12">
                        <button type="button" class="btn default" onclick="parent.closeCurrTab()">取消</button>
                        <button type="submit" class="btn green" onclick="beforeObjectSubmit()"><i
                                    class="fa fa-check"></i> 保存
                        </button>
                    </div>
                    <div class="col-md-6"></div>
                </div>
            </div>
            <div class="portlet-body form margin-top-10">
                <form name="objectform" action="/shop/mallconfig/withdraw"
                      class="horizontal-form" method="post" enctype="multipart/form-data">
                    <input type="hidden" name="_csrf" value="<?= Yii::$app->request->getCsrfToken() ?>">
                    <div class="form-body margin-top-10">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="row">
                                    <div class="form-group " sDataType="Text"
                                         sFieldAs="sDefSearchWord">
                                        <label class="control-label col-md-3">最低提现金额<span class="required"
                                                                                          aria-required="true">*</span>:</label>
                                        <div class="col-md-9">

                                            <div class="inputBox">
                                                <input type="text"
                                                       value="<?= \myerm\shop\common\models\MallConfig::getValueByKey('lWithdrawMin') ?>"
                                                       name="lWithdrawMin">
                                                <span class="bg-grey-steel">元</span>
                                            </div>
                                            <p class="help-block font-blue-dark">必须是0~9999999之间的数字<br>
                                                设置后，经销商每笔提现金额必须大于该数值。<br>
                                                未设置时，默认为0元。</p>
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
<script src="<?= Yii::$app->homeUrl ?>/js/pages/scripts/new.js" type="text/javascript"></script>

<script>
    function beforeObjectSubmit() {

        if ($.trim($("input[name='lWithdrawMin']").val()) == "") {
            error("请输入最低提现金额");
            return false;
        }

        if (!$("input[name='lWithdrawMin']").val().match(/^[0-9\.]{1,}$/)) {
            error("请输入数字");
            return false;
        }

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