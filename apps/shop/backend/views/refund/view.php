<style>
    .form-group {
        margin-bottom: 0px
    }
</style>

<div class="row margin-top-20">
    <div class="col-md-12">
        <!-- BEGIN PORTLET-->
        <div class="portlet light bordered">
            <div class="portlet-title">
                <div class="caption">
                    <span class="caption-subject bold uppercase"> 退款信息</span>
                </div>
                <br>
                <div id="context" data-toggle="context" data-target="#context-menu2">
                    <? if ($check == 'finance') { ?>
                        <p>
                            <button type="button" class="btn green" onclick="agreeApply()">同意退款</button>
                            <button type="button" class="btn red" onclick="denyApply()">拒绝退款</button>
                        </p>
                    <? } elseif ($check == 'aftersale') { ?>
                        <button type="button" class="btn green" onclick="aftersaleagree()">同意退款</button>
                        <button type="button" class="btn red" onclick="aftersaledeny()">拒绝退款</button>
                    <? } ?>
                </div>
            </div>
            <div class="portlet-body">
                <div id="context" data-toggle="context" data-target="#context-menu2">
                    <form class="form-horizontal">
                        <div class="form-group">
                            <label class="col-md-3 control-label" for="title">退款编号:</label>
                            <div class="col-md-9">
                                <p class="form-control-static"><?= $data->sName ?></p>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label" for="title">申请时间:</label>
                            <div class="col-md-9">
                                <p class="form-control-static"><?= $data->dEditDate ?></p>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label" for="title">退款类型:</label>
                            <div class="col-md-9">
                                <p class="form-control-static"><?= $data->sType ?></p>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label" for="title">退款原因:</label>
                            <div class="col-md-9">
                                <p class="form-control-static"><?= $data->sReason ?></p>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label" for="title">商品数量:</label>
                            <div class="col-md-9">
                                <p class="form-control-static"><?= $data->lItemTotal ?></p>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label" for="title">退款数量:</label>
                            <div class="col-md-9">
                                <p class="form-control-static"><?= $data->lRefundItem ?></p>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label" for="title">退还金额:</label>
                            <div class="col-md-9">
                                <p class="form-control-static">¥<?= number_format($data->fRefundApply, 2) ?></p>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-3 control-label" for="title">商品总价:</label>
                            <div class="col-md-9">
                                <p class="form-control-static">¥<?= number_format($data->fProductPrice, 2) ?></p>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label" for="title">运费金额:</label>
                            <div class="col-md-9">
                                <p class="form-control-static">¥<?= number_format($ship, 2) ?></p>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label" for="title">退款说明:</label>
                            <div class="col-md-9">
                                <p class="form-control-static"><?= $data->sExplain ?></p>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label" for="title">退款凭证:</label>
                            <div class="col-md-9">
                                <p class="form-control-static">
                                    <?php
                                    $arrImg = json_decode($data->sRefundVoucher, true);
                                    if (!$arrImg) {
                                        echo "--";
                                    } else {
                                        foreach ($arrImg as $img) {
                                            echo "<a href='/" . $img . "' target='_blank'><img src='/" . $img . "' width='58' /></a> ";
                                        }
                                    }
                                    ?>
                                </p>
                            </div>
                        </div>

                    </form>
                </div>
            </div>

            <div class="portlet-title">
                <div class="caption">
                    <span class="caption-subject bold uppercase"> 关联订单</span>
                </div>
            </div>
            <div class="portlet-body">
                <div id="context" data-toggle="context" data-target="#context-menu2">
                    <form class="form-horizontal">
                        <div class="form-group">
                            <label class="col-md-3 control-label" for="title">订单号:</label>
                            <div class="col-md-9">
                                <p class="form-control-static">
                                    <a href="javascript:;"
                                       onclick="parent.addTab($(this).text(), '/shop/order/view?ID=<?= $data->order->lID ?>')"><?= $data->order->sName ?></a>
                                </p>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label" for="title">买家:</label>
                            <div class="col-md-9">
                                <p class="form-control-static"><?= $BuyerName ?></p>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label" for="title">发货状态:</label>
                            <div class="col-md-9">
                                <p class="form-control-static"><?= $data->orderDetail->dShipDate ? "已发货" : "未发货" ?></p>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-3 control-label" for="title">快递单号:</label>
                            <div class="col-md-9">
                                <? if ($data->orderDetail->sShipNo) { ?>
                                    <p class="form-control-static"><a href="javascript:;"
                                                                      onclick="parent.addTab('查询物流信息', '/shop/express/query?CompanyID=<?= $data->orderDetail->ShipCompanyID ?>&sNo=<?= $data->orderDetail->sShipNo ?>')"><?= $data->orderDetail->sShipNo ?></a>
                                    </p>
                                <? } else {
                                    echo '--';
                                } ?>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-3 control-label" for="title">运费:</label>
                            <div class="col-md-9">
                                <p class="form-control-static"><?= $data->order->fShip == 0 ? "免运费" : '¥' . number_format($data->order->fShip,
                                            2) ?></p>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label" for="title">实付款:</label>
                            <div class="col-md-9">
                                <p class="form-control-static">
                                    ¥<?= number_format($data->order->fBuyerProductPaid, 2) ?></p>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <div class="portlet light bordered">
            <div class="portlet-title">
                <div class="caption">
                    <span class="caption-subject bold uppercase"> 退款记录</span>
                </div>
            </div>

            <div class="portlet-body">
                <div class="dataTables_wrapper no-footer" id="listtable-container">
                    <div class="table-scrollable">
                        <table class="table table-striped table-bordered table-hover table-checkable order-column dataTable no-footer table-header-fixed">
                            <thead>
                            <tr role="row">
                                <th class="sorting_disabled" sfieldas="sName">时间&nbsp;&nbsp;&nbsp;</th>
                                <th class="sorting_disabled" sfieldas="sSKU">操作人&nbsp;&nbsp;&nbsp;</th>
                                <th class="sorting" >退款状态&nbsp;&nbsp;&nbsp;</th>
                                <th class="sorting_disabled" sfieldas="ShipCompanyID">说明&nbsp;&nbsp;&nbsp;</th>
                            </tr>
                            </thead>
                            <tbody>
                            <? foreach ($arrLog as $i => $log) { ?>
                            <tr class="gradeX even" role="row">
                                <td nowrap="nowrap"><?= $log->dNewDate ?></td>
                                <td nowrap="nowrap"><?= $log->sWhoDo ?></td>
                                <td nowrap="nowrap"><?= $log->status ?></td>
                                <td nowrap="nowrap">
                                    <? $arrMessage = json_decode($log->sMessage, true); ?>
                                    <? foreach ($arrMessage as $sKey => $sMessage) { ?>
                                        <div class="row margin-top-10">
                                            <div class="col-md-1 control-label"><?= $sKey ?></div>
                                            <div class="col-md-11">
                                                <? if ($sKey == '退款凭证' || $sKey == '快递凭证') { ?>
                                                <? } else { ?>
                                                    <?= $sMessage ?>
                                                <? } ?>
                                            </div>
                                        </div>
                                    <? } ?>
                                </td>
                            </tr>
                            <? } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

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

    function agreeApply() {
        //供应商同意退款
        $.get('/shop/refund/agreeapply', {
            ID: '<?=$_GET['ID']?>'
        }, function (data) {
            var modal = openModal(data, 700, 300);
        });
    }

    function denyApply() {
        //供应商拒绝退款
        $.get('/shop/refund/denyapply', {
            ID: '<?=$_GET['ID']?>',
            adviseBuyer: '<?=$adviseBuyer?>',
            adviseSupplier: '<?=$adviseSupplier?>'
        }, function (data) {
            var modal = openModal(data, 700, 370);
        });
    }

    function aftersaleagree() {
        $.get('/shop/refund/aftersaleagree', {
            ID: '<?=$_GET['ID']?>',
            adviseBuyerProduct: '<?=$adviseBuyerProduct?>',
            adviseSupplierProduct: '<?=$adviseSupplierProduct?>'
        }, function (data) {
            var modal = openModal(data, 700, 300);
        });
    }

    function aftersaledeny() {
        $.get('/shop/refund/aftersaledeny', {
            ID: '<?=$_GET['ID']?>',
            adviseBuyer: '<?=$adviseBuyer?>',
            adviseSupplier: '<?=$adviseSupplier?>'
        }, function (data) {
            var modal = openModal(data, 700, 370);
        });
    }

    function agreeReceive() {
        $.get('/shop/refund/agreereceive', {ID: '<?=$_GET['ID']?>'}, function (data) {
            var modal = openModal(data, 700, 300);
        });
    }

    function denyReceive() {
        $.get('/shop/refund/denyreceive', {ID: '<?=$_GET['ID']?>'}, function (data) {
            var modal = openModal(data, 700, 370);
        });
    }

</script>
