<? foreach ($arrDetail as $detail) { ?>
    <span class="bg-grey-silver">
    <?= \myerm\shop\common\models\ShipTemplate::getShipMethodName($detail->sShipMethod) ?></span>
    <p>默认运费:<?= $detail['lStart'] ?><?= $sValuation?>内<?= number_format($detail['fPostage'], 2) ?>元，每增加<?= $detail['lPlus'] ?><?= $sValuation?>，增加<?= number_format($detail['fPostageplus'], 2) ?>元</p>
    <a href="javascript:;" onclick="parent.addTab('<?=$detail->parent->sName?>', '/shop/shiptemplate/view?ID=<?=$detail->ShipTemplateID?>')">查看详情</a>
<? } ?>