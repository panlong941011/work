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
                <form name="objectform" action="/shop/mallconfig/orderstatus"
                      class="horizontal-form" method="post" enctype="multipart/form-data">
                    <input type="hidden" name="_csrf" value="<?= Yii::$app->request->getCsrfToken() ?>">
                    <div class="form-body margin-top-10">
                        <div class="row">
                            <div class="col-md-7">
                                <div class="row">
                                    <div class="form-group " sDataType="Text"
                                         sFieldAs="sDefSearchWord">
                                        <label class="control-label col-md-2">订单取消设置<span class="required"
                                                                                          aria-required="true">*</span>:</label>
                                        <div class="col-md-9">
                                            未付款订单超过
                                            <input type="text" class="form-control" sDataType="Text"
                                                   sFieldAs="lOrderAutoCloseTime"
                                                   value="<?= $lOrderAutoCloseTime ?>"
                                                   name="lOrderAutoCloseTime" style="width: 50px;display:inline"
                                                   onkeyup="if (!this.value.match(/^[0-9]{1,}$/)) { this.value='' }"
                                            >
                                            小时后自动关闭
                                        </div>
                                    </div>
                                </div>
                                <div class="row margin-top-20">
                                    <div class="form-group " sDataType="Text"
                                         sFieldAs="sDefSearchWord">
                                        <label class="control-label col-md-2">订单完成设置<span class="required"
                                                                                          aria-required="true">*</span>:</label>
                                        <div class="col-md-9">


                                            <? if ($sOrderCompleteDependOn == 'ship') { ?>
                                                订单发货
                                            <? } else { ?>
                                                物流签收
                                            <? } ?>
                                            <input type="text" class="form-control" sDataType="Text"
                                                   sFieldAs="lAutoConfirmReceive"
                                                   value="<?= $lAutoConfirmReceive ?>"
                                                   name="lAutoConfirmReceive" style="width: 50px;display:inline"
                                                   onkeyup="if (!this.value.match(/^[0-9]{1,}$/)) { this.value='' }"
                                            >
                                            天后，若用户没有确认收货，则系统自动确认收货，订单变为交易成功
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
<script src="<?= Yii::$app->homeUrl ?>/js/pages/scripts/new.js" type="text/javascript"></script>

<script>
    function beforeObjectSubmit() {

        if ($("input[name='lOrderAutoCloseTime']").val() == "") {

            error("订单取消设置不能为空");

            return false;
        }

        if ($("input[name='lAutoConfirmReceive']").val() == "") {

            error("订单完成设置不能为空");

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