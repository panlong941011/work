<style>
    th{
        width: 13%;
        text-align: center;
    }
    td{
        text-align: center;
    }
</style>
    <div class="modal-header">
        <h4 class="modal-title printTitle">发货并打印快递单号</h4>
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
    </div>
    <div class="modal-body" style="overflow: auto; height: 420px;">
        <table class="deliveryTable" border="1" cellspacing="0" cellpadding="0" style="display: block;">
            <thead>
            <tr>
                <th>订单号</th>
                <th>快递单号</th>
                <th>状态</th>
            </tr>
            </thead>
            <tbody class="deliveryTbody">
            <?php foreach ($data as $v):?>
            <tr>
                <?php if ($v['sName']):?>
                    <td><?=$v['sName']?></td>
                <?php else:?>
                    <td></td>
                <?php endif;?>

                <?php if ($v['sExpressNo']):?>
                    <td><?=$v['sExpressNo']?></td>
                <?php else:?>
                    <td></td>
                <?php endif;?>

                <td><?=$v['sReason']?></td>
            </tr>
            <?php endforeach;?>
            </tbody>
        </table>
    </div>
    <div class="modal-footer">
        <button type="button" onclick="closeModal()" class="btn btn-outline dark">关闭</button>
    </div>
