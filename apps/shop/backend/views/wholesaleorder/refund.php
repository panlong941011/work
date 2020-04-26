<style>
    .form-group {
        margin-bottom: 0px
    }
</style>
<div class="breadcrumb" style="display:none">
    <h2>申请订单退款</h2>
    <h3><?= $data['sName'] ?></h3>
</div>
<div class="row margin-top-20">
    <div class="col-md-4">
        <!-- BEGIN PORTLET-->
        <div class="portlet light bordered">
            <div class="portlet-title">
                <div class="caption">
                    <span class="caption-subject bold uppercase"> 订单信息</span>
                </div>
            </div>
            <div class="portlet-body">
                <div id="context" data-toggle="context" data-target="#context-menu2">
                    <form class="form-horizontal">
                        <div class="form-group">
                            <label class="col-md-3 control-label" for="title">订单号:</label>
                            <div class="col-md-9">
                                <p class="form-control-static"><?= $data->sName ?></p>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label" for="title">订单状态:</label>
                            <div class="col-md-9">
                                <p class="form-control-static"><?= $data->StatusID ?></p>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label" for="title">商品总金额:</label>
                            <div class="col-md-9">
                                <p class="form-control-static"><? if ($data->fBuyerProductPaid > 0.00) { ?>¥<?= $data->fBuyerProductPaid ?><? } else { ?>--<? } ?></p>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label" for="title">运费:</label>
                            <div class="col-md-9">
                                <p class="form-control-static"><? if ($data->fShip > 0.00) { ?>¥<?= $data->fShip ?><? } else { ?>--<? } ?></p>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-3 control-label" for="title">订单总价:</label>
                            <div class="col-md-9">
                                <p class="form-control-static"><?= $data->fBuyerPaid ?></p>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-3 control-label" for="title">下单时间:</label>
                            <div class="col-md-9">
                                <p class="form-control-static"><?= $data->dNewDate ?></p>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <div class="portlet-title">
                <div class="caption">
                    <span class="caption-subject bold uppercase"> 订单收货地址</span>
                </div>
            </div>
            <div class="portlet-body">
                <div id="context" data-toggle="context" data-target="#context-menu2">
                    <form class="form-horizontal">

                        <div class="form-group">
                            <label class="col-md-4 control-label" for="title">收货地址:</label>
                            <div class="col-md-8">
                                <p class="form-control-static">
                                    <?= $data->orderAddress->province->sName ?>
                                    <?= $data->orderAddress->city->sName ?>
                                    <?= $data->orderAddress->area->sName ?>
                                    <?= $data->orderAddress->sAddress ?>
                                </p>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-4 control-label" for="title">收货人:</label>
                            <div class="col-md-8">
                                <p class="form-control-static"><?= $data->orderAddress->sName ?></p>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-4 control-label" for="title">收货人手机号:</label>
                            <div class="col-md-8">
                                <p class="form-control-static"><?= $data->orderAddress->sMobile ?></p>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <div class="portlet-title">
                <div class="caption">
                    <span class="caption-subject bold uppercase"> 买家留言</span>
                </div>
            </div>
            <div class="portlet-body">
                <p>
                    <?= $data->sMessage ?>
                </p>
            </div>
        </div>
    </div>
    <div class="col-md-8">
        <!-- BEGIN PORTLET-->
        <div class="portlet light bordered">
            <div class="portlet-title">
                <div class="caption">
                    <span class="caption-subject bold uppercase"> 申请退款</span>
                </div>
            </div>
            <div class="portlet-body">
                <form class="form-horizontal" id="RefundForm">
                    <input type="hidden" name="_csrf" value="<?= Yii::$app->request->getCsrfToken() ?>">
                    <input type="hidden" value="<?= $data->lID ?>" name="orderID">
                    <!--判断是否发货-->
                    <?  if ($data->StatusID == '已发货') { ?>
                        <p>退款说明：</p>
                        <p>最多上传5张图片</p>
                        <div class="form-group" style="margin-top:10px">
                            <label class="col-md-2 control-label" for="title">退款数量:</label>
                            <div class="col-md-4">
                                <input type="text" class="form-control" value="" name="lRefundNum">
                            </div>
                        </div>
                        <div class="form-group" style="margin-top:10px">
                            <label class="col-md-2 control-label" for="title">商品总数:</label>
                            <div class="col-md-4">
                                <input type="text" class="form-control" value="" name="lTotalNum">
                            </div>
                        </div>
                        <div class="form-group" style="margin-top:10px">
                            <label class="col-md-2 control-label" for="title">退款金额:</label>
                            <div class="col-md-4">
                                <input type="text" class="form-control" value="" name="fMoney">
                            </div>
                        </div>
                        <div class="form-group" style="margin-top:10px">
                            <label class="col-md-2 control-label" for="title">退款说明:</label>
                            <div class="col-md-4">
                                <input type="text" class="form-control" value="" name="sReason">
                            </div>
                        </div>
                        <div class="form-group" style="margin-top:10px">
                            <label class="col-md-2 control-label" for="title">上传图片:</label>
                            <div class="col-md-4">
                                <?= $this->render('refundimg', $data); ?>
                            </div>
                        </div>
                    <? } elseif($data->StatusID == 'paid') { ?>
                        <p>订单未发货，默认全额退款</p>
                        <div class="form-group">
                            <label class="col-md-2 control-label" for="title">退款金额:</label>
                            <div class="col-md-4">
                                <p class="form-control-static"><?= $data->fBuyerPaid ?></p>
                            </div>
                        </div>
                    <? } ?>
                        <div class="form-group">
                            <div class="col-md-8">
                                <button type="button" class="btn green" onclick="applyRefund()"> 申请退款
                                </button>
                            </div>
                        </div>

                </form>
            </div>
        </div>
        <!-- END PORTLET-->
    </div>
</div>
<!--关联订单明细-->
<? if ($arrSysDetailObject) { ?>
    <? foreach ($arrSysDetailObject as $sysDetailObject) { ?>
        <?= $this->context->getRelInfo($sysDetailObject) ?>
    <? }
} ?>
<script src="<?= Yii::$app->homeUrl ?>/js/pages/scripts/view.js" type="text/javascript"></script>
<script>
    function applyRefund() {
        $("#pic_list .re_pic img").each(function () {
           if(this.src){
               $('#RefundForm').append('<input type="hidden" class="hid_RefundPic" value="'+this.src+'" name="sRefundPic[]">');
           }
        });

        $("#RefundForm .hid_RefundPic").each(function () {
            this.remove();
        });

        $.post('<?= Yii::$app->homeUrl ?>/shop/wholesaleorder/refundapply',
            $('#RefundForm').serialize(),
            function (data) {
                if(data.status){
                    success(data.msg);
                }else {
                    error(data.msg);
                }
            }
        )
    }
</script>
