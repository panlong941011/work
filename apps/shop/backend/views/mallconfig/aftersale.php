<div class="breadcrumb" style="display:none">
    <h2>商城设置</h2>
    <h3>订单状态设置</h3>
</div>

<div class="row margin-top-10">

    <div class="col-md-12">
        <div class="note note-info">
            <h4 class="block"><?= Yii::t('app', '提示') ?></h4>
            <p>此处设置一旦设置好，请尽量不要更改，以免造成跟买家的纠纷</p>
        </div>
    </div>


    <div class="col-md-12">
        <div class="portlet light bordered">

            <div class="portlet-body form margin-top-10">
                <form name="objectform" action="/shop/mallconfig/aftersale"
                      class="horizontal-form" method="post" enctype="multipart/form-data">
                    <input type="hidden" name="_csrf" value="<?= Yii::$app->request->getCsrfToken() ?>">
                    <div class="form-body margin-top-10">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="row">
                                    <div class="form-group " sDataType="Text"
                                         sFieldAs="sDefSearchWord">
                                        <div class="col-md-12">
                                            买家申请退款，若卖家<span class="required" aria-required="true">*</span>
                                            <input type="text" class="form-control" sDataType="Text"
                                                   sFieldAs="lOrderAutoCloseTime"
                                                   value="<?= \myerm\shop\common\models\MallConfig::getValueByKey("lRefundApplyTimeOut")?>"
                                                   name="lRefundApplyTimeOut" style="width: 50px;display:inline"  onkeyup="if (!this.value.match(/^[0-9]{1,}$/)) { this.value='' }"
                                            >
                                            天未处理，系统自动同意退款
                                        </div>
                                    </div>
                                </div>
                                <div class="row margin-top-20">
                                    <div class="form-group " sDataType="Text"
                                         sFieldAs="sDefSearchWord">
                                        <div class="col-md-9">
                                            卖家已同意，若买家<span class="required"
                                                           aria-required="true">*</span>
                                            <input type="text" class="form-control" sDataType="Text"
                                                   sFieldAs="lAutoConfirmReceive"
                                                   value="<?= \myerm\shop\common\models\MallConfig::getValueByKey("lRefundAgreeTimeOut")?>"
                                                   name="lRefundAgreeTimeOut" style="width: 50px;display:inline"  onkeyup="if (!this.value.match(/^[0-9]{1,}$/)) { this.value='' }"
                                            >
                                            天未处理，该退款申请将自动关闭
                                        </div>
                                    </div>
                                </div>
                                <div class="row margin-top-20">
                                    <div class="form-group ">
                                        <div class="col-md-9">
                                            卖家拒绝退款，若买家<span class="required"
                                                           aria-required="true">*</span>
                                            <input type="text" class="form-control" sDataType="Text"
                                                   sFieldAs="lAutoConfirmReceive"
                                                   value="<?= \myerm\shop\common\models\MallConfig::getValueByKey("lRefundDenyApplyTimeOut")?>"
                                                   name="lRefundDenyApplyTimeOut" style="width: 50px;display:inline"  onkeyup="if (!this.value.match(/^[0-9]{1,}$/)) { this.value='' }"
                                            >
                                            天未处理，该退款申请将自动关闭
                                        </div>
                                    </div>
                                </div>

                                <div class="row margin-top-20">
                                    <div class="form-group ">
                                        <div class="col-md-9">
                                            买家提交退货单，若卖家<span class="required"
                                                            aria-required="true">*</span>
                                            <input type="text" class="form-control" sDataType="Text"
                                                   sFieldAs="lAutoConfirmReceive"
                                                   value="<?= \myerm\shop\common\models\MallConfig::getValueByKey("lRefundShipTimeOut")?>"
                                                   name="lRefundShipTimeOut" style="width: 50px;display:inline"  onkeyup="if (!this.value.match(/^[0-9]{1,}$/)) { this.value='' }"
                                            >
                                            天未处理，该退款申请将自动同意并退款
                                        </div>
                                    </div>
                                </div>

                                <div class="row margin-top-20">
                                    <div class="form-group ">
                                        <div class="col-md-9">
                                            退货被卖家拒绝，买家<span class="required"
                                                             aria-required="true">*</span>
                                            <input type="text" class="form-control" sDataType="Text"
                                                   sFieldAs="lAutoConfirmReceive"
                                                   value="<?= \myerm\shop\common\models\MallConfig::getValueByKey("lRefundDenyReceiveTimeOut")?>"
                                                   name="lRefundDenyReceiveTimeOut" style="width: 50px;display:inline"  onkeyup="if (!this.value.match(/^[0-9]{1,}$/)) { this.value='' }"
                                            >
                                            天未处理，该退款申请将自动关闭
                                        </div>
                                    </div>
                                </div>

                                <div class="row margin-top-20">
                                    <div class="form-group ">
                                        <div class="col-md-12">
                                            请在以下编辑框内填写退款说明<span class="required"
                                                                aria-required="true">*</span>：（可填写文字和上传图片,每张图片不得大于2M，且此处图片宽度建议640像素）
                                            <script id="sAfterSaleNote" name="sAfterSaleNote" type="text/plain"
                                                    style="width:100%;height:300px;"><?= \myerm\shop\common\models\MallConfig::getValueByKey("sAfterSaleNote")?></script>
                                            <script
                                            type = "text/javascript" >
                                                $(document).ready
                                                (
                                                    function () {
                                                        var ue = UE.getEditor('sAfterSaleNote');
                                                    }
                                                );
                                            </script>


                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                </form>
            </div>
            <div class="form-actions margin-top-10">
                <div class="row">
                    <div class="col-md-12">
                        <button type="submit" class="btn green" onclick="beforeObjectSubmit()"><i
                                class="fa fa-check"></i> 保存
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
    function beforeObjectSubmit() {

        if ($("input[name='lRefundApplyTimeOut']").val() == "") {
            error("请输入必填项的值。");
            return false;
        }

        if ($("input[name='lRefundAgreeTimeOut']").val() == "") {
            error("请输入必填项的值。");
            return false;
        }

        if ($("input[name='lRefundDenyApplyTimeOut']").val() == "") {
            error("请输入必填项的值。");
            return false;
        }

        if ($("input[name='lAutoConfirmReceive']").val() == "") {
            error("请输入必填项的值。");
            return false;
        }

        if ($("input[name='lRefundShipTimeOut']").val() == "") {
            error("请输入必填项的值。");
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