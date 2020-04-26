<td align="center" nowrap="nowrap">
    <? if (in_array($data['StatusID']['ID'], ['unpaid', 'success', 'closed'])) { ?>
        发货
        修改物流
    <? } else { ?>

        <? if ($data['bAllShip'] === false && $data['sDetailStatus'] != "退款中") { ?>
            <a href="javascript:;"
               onclick="ship(<?= $data['lID'] ?>, this)">发货</a>
        <? } else { ?>
            发货
        <? } ?>


        <? if ($data['bShip']) { ?>
            <a href="javascript:;" onclick="modifyShip(<?= $data['lID'] ?>, this)">修改物流</a>
        <? } else { ?>
            修改物流
        <? } ?>
    <? } ?>
</td>