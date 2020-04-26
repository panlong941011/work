<div class="form-group" style="margin-left: 5px;">
    待发货<?= $lWaitDeliver ?>，已选<span class="checkLength">0</span>
</div>
<table class="table table-striped table-bordered table-hover table-checkable order-column dataTable no-footer"
       id="orderDeliver">
    <tr role="row">
        <td><input type="checkbox" class="check_all"
                   onclick="checkAll()" <?= $lWaitDeliver <= 0 ? 'disabled' : '' ?>/></td>
        <td width="150px">商品</td>
        <td>数量</td>
        <td>售后</td>
        <td width="75px">发货方式</td>
        <td width="70px">物流公司</td>
        <td width="100px">快递单号</td>
        <td>发货状态</td>
    </tr>
    <? foreach ($order->arrDetail as $detail) { ?>
        <tr>
            <td>
                <input type="checkbox"
                       name="DetailID[]"
                       value="<?= $detail->lID ?>"
                       class="check_one"
                       onclick="checkOne()"
                    <?= !$detail->bCanShip ? 'disabled' : '' ?>/>
            </td>
            <td><a href="javascript:;"
                   onclick="parent.addTab($(this).text(), '/shop/product/view?ID=<?= $detail->ProductID ?>')"><?= $detail->sName ?></a>
            </td>
            <td><?= $detail->lQuantity ?></td>
            <td><?= $detail->sStatus ?></td>
            <td><?= $detail->sShip ?></td>
            <td><?= $detail->shipCompany->sName ?></td>
            <td><a href='javascript:;'
                   onclick="parent.addTab('查询物流信息', '/shop/express/query?CompanyID=<?= $detail->ShipCompanyID ?>&sNo=<?= $detail->sShipNo ?>')"> <?= $detail->sShipNo ?></a>
            </td>
            <td><?= empty($detail->bShiped) ? '' : '<font color="green">已发货</font>' ?></td>
        </tr>
    <? } ?>
</table>