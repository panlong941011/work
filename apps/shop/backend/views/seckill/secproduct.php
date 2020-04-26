<?php

$arrSecKillProduct = \myerm\shop\common\models\SecKillProduct::findAll(['SecKillID' => $_GET['ID']]);

?>
<style>
    .well {
        margin-bottom: 0px;
        padding-top: 10px;
        width: 800px;
    }

    .seckillproduct {
        background-color: white;
        padding: 20px;
    }
</style>
<div class="well">

    <? foreach ($arrSecKillProduct as $product) { ?>
        <div class="seckillproduct margin-top-10">
            <div class="row">
                <div class="col-md-12"><?= $product->product->sName ?></div>
            </div>

            <div class="row" style="margin: 1px">
                <div class="table-scrollable">
                    <table class="table table-bordered table-hover">
                        <thead>
                        <tr>
                            <th> 参与活动的规格</th>
                            <th> 秒杀价格</th>
                            <th> 活动库存</th>
                        </tr>
                        </thead>
                        <tbody>
                        <? foreach ($product->arrSku as $sku) { ?>
                        <tr>
                            <td><?=$sku->sName?></td>
                            <td><?=$sku->fPrice?></td>
                            <td><?=$sku->lStock?></td>
                        </tr>
                        <? } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    <? } ?>


</div>
