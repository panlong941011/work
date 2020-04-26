<style>
    .common_ui_module {
        width: 100%;
        float: left;
        margin-top: 4px 0;
    }

    .common_ui_label {
        float: left;
        margin: 0 10px 0 0;
        text-align: center;
        line-height: 35px;
    }

    .common_ui_module span:first-child {
        color: red;
    }

    .common_ui_module span {
        float: left;
        margin-right: 5px;
    }

    .submitButton {
        text-align: center;
    }

    .submitButton button {
        width: 160px;
        height: 35px;
        border: 0;
        background: #32C5D2;
        color: #fff;
        cursor: pointer;
        margin-top: 10px;
    }

    .express_type {
        margin: 8px 0 0 0;
    }

    .express_type div {
        float: left;
    }

    .orderDeliver {
        table-layout: fixed;
        text-align: center;
    }

    .orderDeliver td {
        vertical-align: middle !important;
    }

    .express_type input {
        margin: 0 5px 0 10px;
    }
</style>

<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
    <h4 class="modal-title">发货</h4>
</div>

<form name="shipForm" class="form-horizontal" onsubmit="return false;">
    <!--已拆的订单物流数据-->
    <div class="modal-body">
        <div class="row">
            <div class="col-md-12">
                <div class="alert alert-info">
                    一笔订单默认发一个物流，若需发多个物流，请先进行拆分订单物流操作
                </div>
            </div>
        </div>
        <div class="common_ui_module">
            <table class="table table-bordered orderDeliver"
                   id="SeparateExpress">
                <tr role="row">
                    <td>编号</td>
                    <td width="150px">商品</td>
                    <td>规格</td>
                    <td>数量</td>
                    <td>发货方式</td>
                    <td>快递公司</td>
                    <td width="150px">快递单号</td>
                </tr>
                <? if ($sProductInfo) { ?>
                    <? foreach ($sProductInfo as $sProductKey => $sProductValue) { ?>
                        <? foreach ($sProductValue as $k => $v) { ?>
                            <tr class='No<?= $sProductKey + 1 ?>' name='OrderExpress'>
                                <? if ($k == 0) { ?>
                                    <td class='FirstTd<?= $sProductKey + 1 ?>'
                                        name='SeparateNo'
                                        rowspan="<?= count($sProductValue) ?>"
                                        style="vertical-align: middle">
                                        <?= $sProductKey + 1 ?>
                                    </td>
                                    <input type="hidden" value="<?= $lID[$sProductKey] ?>"
                                           name="arrShip[<?= $sProductKey ?>][lID]">
                                    <input type="hidden" value="<?= $sOrderDetailID[$sProductKey] ?>"
                                           name="arrShip[<?= $sProductKey ?>][sOrderDetailID]">
                                <? } ?>
                                <td><?= $v['sName'] ?></td>
                                <td><?= $v['sSKU'] ?></td>
                                <td><?= $v['lQuantity'] ?></td>
                                <? if ($k == 0) { ?>
                                    <td class='FirstTd<?= $sProductKey + 1 ?>'
                                        name='SeparateOperating'
                                        rowspan="<?= count($sProductValue) ?>"
                                        style="vertical-align: middle">
                                        <input type="radio"
                                               name="arrShip[<?= $sProductKey ?>][ShipID]"
                                               value="wuliu"
                                               mark="wl"
                                               no="<?= $sProductKey ?>"
                                               onclick="changeShip('wuliu',<?= $sProductKey ?>)"
                                               checked
                                        />物流
                                    </td>
                                <? } ?>
                                <? if ($k == 0) { ?>
                                    <td class='FirstTd<?= $sProductKey + 1 ?>'
                                        name='SeparateOperating'
                                        rowspan="<?= count($sProductValue) ?>"
                                        style="vertical-align: middle">
                                        <select class="form-control"
                                                name="arrShip[<?= $sProductKey ?>][CompanyID]"
                                                id="company_<?= $sProductKey ?>">
                                            <? foreach ($arrCompany as $company) { ?>
                                                <option value="<?= $company->sKdBirdCode ?>"><?= $company->sPinYin ?>
                                                    -<?= $company->sName ?></option>
                                            <? } ?>
                                        </select>
                                    </td>
                                <? } ?>
                                <? if ($k == 0) { ?>
                                    <td class='FirstTd<?= $sProductKey + 1 ?>'
                                        name='SeparateOperating'
                                        rowspan="<?= count($sProductValue) ?>"
                                        style="vertical-align: middle">
                                        <input type="text"
                                               class="form-control"
                                               value=""
                                               id="shipNo_<?= $sProductKey ?>"
                                               name="arrShip[<?= $sProductKey ?>][sShipNo]">
                                    </td>
                                <? } ?>
                            </tr>
                        <? } ?>
                    <? } ?>
                <? } ?>
            </table>
        </div>
    </div>

    <div class="modal-footer">
        <div class="common_ui_module">
            <div class="express_type" style="text-align: left">
                收货人：<?= $order->orderAddress->sName ?><br>
            </div>
        </div>
        <div class="common_ui_module">
            <div class="express_type" style="text-align: left">
                收货人手机号：<?= $order->orderAddress->sMobile ?><br>
            </div>
        </div>
        <div class="common_ui_module">
            <div class="express_type" style="text-align: left">
                收货地址：<?= $order->orderAddress->province->sName ?> <?= $order->orderAddress->city->sName ?> <?= $order->orderAddress->area->sName ?> <?= $order->orderAddress->sAddress ?>
            </div>
        </div>
        <div class="submitButton">
            <button onclick="deliver()">提 交</button>
            <input type="reset" class="hide">
        </div>
    </div>
</form>
<script>
    //发货请求
    function deliver() {
        //验证
        var bShipNo = true;
        $("input[mark='wl']:checked").each(function () {
            if ($("#shipNo_" + $(this).attr("no")).val() == "") {
                error("请输入快递单号");
                bShipNo = false;
                return false;
            }
        });
        if (!bShipNo) {
            return false;
        }

        info("正在提交修改，请稍等。。。");
        $.post('/shop/order/splitship', $(document.shipForm).serialize(), function (data) {
            if (data.status) {
                success(data.msg);
                closeModal();
                reload();
            } else {
                error(data.msg);
            }
        }, 'json');
    }

    function changeShip(type, mark) {
        if (type == 'wuliu') {
            $("#company_" + mark).show();
            $("#shipNo_" + mark).show();
        } else {
            $("#company_" + mark).hide();
            $("#shipNo_" + mark).hide();
        }
    }
</script>