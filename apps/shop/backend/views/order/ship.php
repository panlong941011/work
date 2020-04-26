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

    .common_ui_select {
        width: 150px;
        float: left;
        height: 35px;
        border: 1px solid #D8D8D8;
        text-align: center;
        margin-right: 50px;
    }

    .common_ui_input {
        width: 150px;
        float: left;
        height: 35px;
        border: 1px solid #D8D8D8;
        padding-left: 5px;
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

    .express_type input {
        margin: 0 5px 0 10px;
    }

    #orderDeliver td {

    }

    #orderDeliver {
        table-layout: fixed;
        text-align: center;
    }
</style>
<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
    <h4 class="modal-title">发货</h4>
</div>

<form name="shipform" class="form-horizontal" onsubmit="return false;">
    <input type="hidden" name="_csrf" value="<?= \Yii::$app->request->getCsrfToken() ?>">
    <div class="modal-body">
        <? if (!$order->supplier->sRefundAddress) { ?>
            <div class="alert alert-danger">
                您还未设置默认退货地址，无法发货。请前往设置退货地址！<a href="javascript:;"
                                              onclick="parent.addTab($(this).text(), '/shop/supplier/edit?ID=<?= $order->SupplierID ?>')">设置退货地址</a>
            </div>
        <? } ?>
        <div id="shipdetail">

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
        <div class="common_ui_module">
            <label class="common_ui_label">发货方式：</label>
            <div class="express_type">
                <div>
                    <input type="radio"
                           name="ShipID"
                           value="wuliu"
                           onclick="changeShip('wuliu')"
                           checked
                    />物流
                    
                </div>
            </div>
        </div>
        <div class="common_ui_module" id="expressInfo">
            <label class="common_ui_label">物流公司：</label>
            <select class="common_ui_select" name="CompanyID">
                <? foreach ($arrCompany as $company) { ?>
                    <option value="<?= $company->ID ?>"><?= $company->sPinYin ?>-<?= $company->sName ?></option>
                <? } ?>
            </select>
            <label class="common_ui_label">快递单号：</label>
            <input type="text" class="common_ui_input" name="sShipNo"/>
        </div>
        <div class="submitButton">
            <? if (!$lWaitDeliver) { ?>
                <button style="background:#e1e5ec">提 交</button>
            <? } else { ?>
                <button onclick="deliver()">提 交</button>
            <? } ?>

            <input type="reset" class="hide">
        </div>
    </div>
</form>

<script>
    //发货请求
    function deliver() {

        var checkLength = $("input.check_one[type='checkbox']:checked").length;
        if (checkLength == 0) {
            error("请选择发货商品");
            return false;
        }

        if ($("input:checked[name='ShipID']").val() == 'wuliu') {
            if ($("input[name='sShipNo']").val() == "") {
                error("请输入快递单号");
                return false;
            }
        }

        info("正在提交修改，请稍等。。。");
        $.post('/shop/order/ship?ID=<?= $order->lID ?>', $(document.shipform).serialize(), function (data) {
            if (data.status) {
                success(data.msg);
                if (data.lWaitDeliver == '0') {
                    closeModal();
                    reload();
                } else {
                    getShipDetail();
                    changeShip('wuliu');
                    $("input.check_one[type='checkbox']:checked").prop('disabled', true);
                    $("input[type='reset']").click();
                }
            } else {
                error(data.msg);
            }
        }, 'json');
    }
    //选择物流类型
    function changeShip(type) {
        if (type != 'wuliu') {
            $('#expressInfo').hide();
        } else {
            $('#expressInfo').show();
        }
    }
    //全选
    function checkAll() {
        var cheked = $('.check_all').is(':checked');
        if (cheked) {
            $("input.check_one[type='checkbox']:not(:disabled)").prop('checked', true);
        } else {
            $("input.check_one[type='checkbox']:not(:disabled)").prop('checked', false);
        }
        var checkLength = $("input.check_one[type='checkbox']:checked").length;
        $('.checkLength').text(checkLength);
    }

    //待发货数
    var waitDeliverLength = Number(<?= $lWaitDeliver ?>);

    //单选
    function checkOne() {
        var checkLength = $("input.check_one[type='checkbox']:checked").length;
        $('.checkLength').text(checkLength);
        if (checkLength == waitDeliverLength) {
            $('.check_all').prop('checked', true);
        } else {
            $('.check_all').prop('checked', false);
        }
    }

    function getShipDetail() {
        $.get(
            '/shop/order/getshipdetail?ID=<?= $order->lID ?>',
            function (data) {
                $("#shipdetail").html(data);
            }
        )
    }

    function reload() {
        var ListTable = $('#myermmodal').prop('listtable');

        if (ListTable) {
            ListTable.loadData();
        } else {
            location.reload();
        }
    }

    getShipDetail();
</script>