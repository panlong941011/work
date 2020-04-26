<style>
    .form-group {
        margin-bottom: 0px
    }
</style>
<div class="breadcrumb" style="display:none">
    <h2>订单退货记录</h2>
    <h3><?= $data['sName'] ?></h3>
</div>
<div class="row">
    <? if ($desc) { ?>
        <div class="note note-info margin-top-10">
            <h4 class="block"><?= Yii::t('app', '提示') ?></h4>
            <p><?= $desc ?></p>
        </div>
    <? } ?>
</div>
<div class="row margin-top-20">
    <div class="col-md-4">
        <!-- BEGIN PORTLET-->
        <div class="portlet light bordered">
            <div class="portlet-title">
                <div class="caption">
                    <span class="caption-subject bold uppercase"> 退货信息</span>
                </div>

                <div id="context" data-toggle="context" data-target="#context-menu2">
                    <form class="form-horizontal">
                        <div class="form-group">
                            <label class="col-md-3 control-label" for="title"></label>
                            <div class="col-md-9">
                                <p class="form-control-static"></p>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label" for="title">
                                <img src="<?= Yii::$app->params['sUploadUrl'] ?>/<?= $data->refund->orderDetail->sPic ?>"
                                     width="58"/>
                            </label>
                            <div class="col-md-9">
                                <p class="form-control-static">
                                    <a href="javascript:;"
                                       onclick="parent.addTab($(this).text(), '/shop/product/view?ID=<?= $data->refund->orderDetail->ProductID ?>')"><?= $data->refund->orderDetail->sName ?></a><br><?= $data->refund->orderDetail->sSKU ?>
                                </p>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <div class="portlet-body">
                <div id="context" data-toggle="context" data-target="#context-menu2">
                    <form class="form-horizontal">
                        <div class="form-group">
                            <label class="col-md-3 control-label" for="title">订单编号:</label>
                            <div class="col-md-9">
                                <p class="form-control-static"><?= $data->sName ?></p>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label" for="title">申请时间:</label>
                            <div class="col-md-9">
                                <p class="form-control-static"><?= $data->dNewDate ?></p>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label" for="title">退货原因:</label>
                            <div class="col-md-9">
                                <p class="form-control-static"><?= $data->refund->sReason ?></p>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label" for="title">退货说明:</label>
                            <div class="col-md-9">
                                <p class="form-control-static"><?= $data->refund->sExplain ?></p>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label" for="title">商品数量:</label>
                            <div class="col-md-9">
                                <p class="form-control-static"><?= $data->refund->lItemTotal ?></p>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label" for="title">退款数量:</label>
                            <div class="col-md-9">
                                <p class="form-control-static"><?= $data->refund->lRefundItem ?></p>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label" for="title">快递公司:</label>
                            <div class="col-md-9">
                                <p class="form-control-static"><?= $ShipCompany ?></p>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label" for="title">快递单号:</label>
                            <div class="col-md-9">
                                <p class="form-control-static"><?= $data->sShipNo ?></p>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label" for="title">退货凭证:</label>
                            <div class="col-md-9">
                                <p class="form-control-static">
                                    <?php
                                    $arrImg = json_decode($data->sShipVoucher, true);
                                    if (!$arrImg) {
                                        echo "--";
                                    } else {
                                        foreach ($arrImg as $img) {
                                            echo "<a href='" . $img . "' target='_blank'><img src='" . $img . "' width='58' /></a> ";
                                        }
                                    }
                                    ?>
                                </p>
                            </div>
                        </div>

                    </form>
                </div>
            </div>

        </div>

    </div>
    <div class="col-md-8">
        <!-- BEGIN PORTLET-->
        <div class="portlet light bordered">

            <div class="portlet-body">
                <? if ($data->StatusID == 'delivered') { ?>
                    <p>
                        买家已发货，请尽快处理。
                    </p>
                    <p>
                        <button type="button"
                                class="btn green" <? if ($unpaid || !$bHasPower) {
                            echo 'disabled';
                        } ?> onclick="agreeReceive()">确认收货
                        </button>
                        <button type="button"
                                class="btn red" <? if ($unpaid || !$bHasPower) {
                            echo 'disabled';
                        } ?> onclick="denyReceive()">拒绝收货
                        </button>
                    </p>
                <? } elseif ($data->StatusID == 'refuse') { ?>
                    <p>卖家已经拒绝本次收货，买家修改退款申请后，需要重新处理</p>

                <? } elseif ($data->StatusID == 'received') { ?>
                    <p>卖家已确认收货</p>
                <? } elseif ($data->StatusID == 'cancel') { ?>
                    <p>该退款申请已关闭</p>
                <? } ?>
            </div>
        </div>
        <!-- END PORTLET-->
    </div>
</div>

<? if ($arrSysDetailObject) { ?>
    <? foreach ($arrSysDetailObject as $sysDetailObject) { ?>
        <?= $this->context->getRelInfo($sysDetailObject) ?>
    <? }
} ?>
<script src="<?= Yii::$app->homeUrl ?>/js/pages/scripts/view.js" type="text/javascript"></script>
<script src="/shop/order/js" type="text/javascript"></script>
<script>
    var lTimeLeft = <?=intval($lTimeLeft)?>;

    function countDown() {
        if (lTimeLeft >= 0) {
            var d = Math.floor(lTimeLeft / 86400);
            var h = Math.floor((lTimeLeft - d * 86400) / 3600);
            var m = Math.floor((lTimeLeft - d * 86400 - 3600 * h) / 60);
            var i = lTimeLeft - d * 86400 - 3600 * h - m * 60;

            $(".font-yellow-gold").html(d + "天" + h + "小时" + m + "分" + i + "秒");
        } else {
            $(".font-yellow-gold").html("0小时0分0秒");
        }
    }
    countDown();
    setInterval("lTimeLeft--;countDown()", 1000);

    function agreeReceive() {
        $.get('/shop/refundreturn/agreereceive', {ID: '<?=$_GET['ID']?>'}, function (data) {
            var modal = openModal(data, 700, 300);
        });
    }

    function denyReceive() {
        $.get('/shop/refundreturn/denyreceive', {ID: '<?=$_GET['ID']?>'}, function (data) {
            var modal = openModal(data, 700, 370);
        });
    }

</script>
