<style>
    .form-group {
        margin-bottom: 0px
    }
</style>
<div class="breadcrumb" style="display:none">
    <h2>订单管理</h2>
    <h3><?= $data['sName'] ?></h3>
</div>
<div class="row margin-top-20">
    <div class="col-md-12">
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
                                <p class="form-control-static"><?= $data->sStatus ?></p>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label" for="title">应付商品渠道款:</label>
                            <div class="col-md-9">
                                <p class="form-control-static">
                                    <? if ($data->fBuyerProductPaid > 0.00) { ?>¥<?= $data->fBuyerProductPaid ?><? } else { ?>--<? } ?>
                                </p>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label" for="title">供应商商品收入:</label>
                            <div class="col-md-9">
                                <p class="form-control-static">
                                    <? if ($data->fSupplierProductIncome > 0.00) { ?>¥<?= $data->fSupplierProductIncome ?><? } else { ?>--<? } ?>
                                </p>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label" for="title">运费:</label>
                            <div class="col-md-9">
                                <p class="form-control-static"><? if ($data->fShip > 0.00) { ?>¥<?= $data->fShip ?><? } else { ?>--<? } ?></p>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label" for="title">渠道商应付金额:</label>
                            <div class="col-md-9">
                                <p class="form-control-static">
                                    <? if ($data->fBuyerPaid > 0.00) { ?>¥<?= $data->fBuyerPaid ?><? } else { ?>--<? } ?>
                                </p>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label" for="title">供应商收入:</label>
                            <div class="col-md-9">
                                <p class="form-control-static">
                                    <? if ($data->fSupplierIncome > 0.00) { ?>¥<?= $data->fSupplierIncome ?><? } else { ?>--<? } ?>
                                </p>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label" for="title">渠道商:</label>
                            <div class="col-md-9">
                                <p class="form-control-static">
                                    <a href="javascript:;"
                                                                  onclick="parent.addTab($(this).text(), '/shop/buyer/view?ID=<?= $data->BuyerID ?>//&FieldID=15091995320045902000682317782367')"><?= $BuyerName?></a>
                                </p>
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
                    <span class="caption-subject bold uppercase"> 客户留言</span>
                </div>
            </div>
            <div class="portlet-body">
                <p>
                    <?= $data->sMessage ?>
                </p>
            </div>

        </div>

    </div>

</div>
<? if ($arrSysDetailObject) { ?>
    <? foreach ($arrSysDetailObject as $sysDetailObject) { ?>
        <?=$this->context->getRelInfo($sysDetailObject)?>
    <? }} ?>
<script src="<?=Yii::$app->homeUrl?>/js/pages/scripts/view.js" type="text/javascript"></script>
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
</script>
