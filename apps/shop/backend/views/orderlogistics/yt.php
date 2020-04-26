
<body>
<? foreach($data as $k=>$v){?>
    <? if(count($data)-$k !=1 ){?>
        <div style="width:375px;height:670px;overflow: hidden">
            <table style="font-size:14px; border:1px solid black; width:100%;height:650px;overflow: hidden; border-collapse:collapse;">
                <!--代收款-->
                <tr >
                    <td colspan="3" style="height: 63px;width: 100% ;border-bottom: 1px solid black;overflow: hidden">

                        <div class="b">代收货款</div>
                        <div>
                            金额：<span class="f14">&nbsp;元</span>
                        </div>

                    </td>
                </tr>

                <!--地名-->
                <tr>
                    <td colspan="3" style="height:72px ;text-align: center;width: 100%;border-bottom: 1px solid black;overflow: hidden;font-size: 32px; font-weight: bold;letter-spacing:5px; line-height:0.95;font-family: Microsoft YaHei">
                        <?=$v['MarkDestination']?>
                    </td>

                </tr>
                <!--条形码-->
                <tr>
                    <td colspan="3" style=" height: 70px;width: 100%;overflow: hidden;border-bottom: 1px solid black">
                        <!--                    <div style=" height: 50px;border:1px solid red"></div>-->
                    </td>

                </tr>

                <!--信息-->
                <!--寄件人-->
                <tr style="height:65px;">
                    <td style="width:60px;height:65px;vertical-align:top;border-bottom: 1px solid black;padding:0 5px; overflow: hidden;">
                        寄件人:
                    </td>
                    <td  style="border-bottom: 1px solid black;padding:0 5px; overflow: hidden;height:65px;" >
                        <?=$v['senderName']?>&nbsp;&nbsp;<?=$v['senderProvinceName']?>&nbsp;<?=$v['senderCityName']?>&nbsp;<?=$v['senderExpAreaName']?>&nbsp;<?=$v['senderAddress']?><br/>
                        <?=$v['senderMobile']?>
                    </td>
                    <td rowspan="3" style="border-left: 1px solid black;border-bottom: 5px solid black;width:25px;text-align: center">签收联</td>
                </tr>
                <!--收件人-->
                <tr style="height:65px;">
                    <td style="width:60px;vertical-align:top;height:65px;border-bottom: 1px solid black;padding:0 5px; overflow: hidden;">
                        收件人:
                    </td>
                    <td  style="border-bottom: 1px solid black;padding:0 5px; ">
                        <?=$v['receiverName']?>&nbsp;&nbsp;<?=$v['receiverProvinceName']?>&nbsp;<?=$v['receiverCityName']?>&nbsp;<?=$v['receiverExpAreaName']?>&nbsp;<?=$v['receiverAddress']?><br/>
                        <?=$v['receiverMobile']?>
                    </td>

                </tr>
                <!--其他-->
                <tr >
                    <td colspan="2" style="vertical-align:top;border-bottom: 5px solid black;height: 68px">
                        <div style="width: 168px;float: left;overflow: hidden;border-right: 1px solid black;height: 68px">
                            收件人/代收人：
                        </div>
                        <div style="width: 160px;float: left;overflow: hidden;">
                            签收时间：<br/><br/>
                            &nbsp;<span style="float: right">&nbsp; 年&nbsp; 月&nbsp; 日</span>
                        </div>
                    </td>
                </tr>

                <!--下联-->
                <tr >
                    <td colspan="3" style="height:40px;border-bottom: 1px solid black ">
                        <div style="width: 168px;float: left;overflow: hidden;border-right: 1px solid black;height: 40px;vertical-align:middle;">

                        </div>
                        <div style="width: 168px;float: left;overflow: hidden;">

                        </div>
                    </td>
                </tr>

                <!--寄件人-->
                <tr style="height:42px;">
                    <td style="width:60px;height:42px;vertical-align:top;border-bottom: 1px solid black;padding:0 5px; overflow: hidden;">
                        寄件人:
                    </td>
                    <td  style="border-bottom: 1px solid black;padding:0 5px; overflow: hidden;height:42px;" >
                        <?=$v['senderName']?>&nbsp;&nbsp;<?=$v['senderProvinceName']?>&nbsp;<?=$v['senderCityName']?>&nbsp;<?=$v['senderExpAreaName']?>&nbsp;<?=$v['senderAddress']?><br/>
                        <?=$v['senderMobile']?>
                    </td>
                    <td rowspan="2" style="border-left: 1px solid black;border-bottom: 1px solid black;width:25px;text-align: center">收件联</td>
                </tr>
                <!--收件人-->
                <tr style="height:42px;">
                    <td style="width:60px;vertical-align:top;height:42px;border-bottom: 1px solid black;padding:0 5px; overflow: hidden;">
                        收件人:
                    </td>
                    <td  style="border-bottom: 1px solid black;padding:0 5px; ">
                        <?=$v['receiverName']?>&nbsp;&nbsp;<?=$v['receiverProvinceName']?>&nbsp;<?=$v['receiverCityName']?>&nbsp;<?=$v['receiverExpAreaName']?>&nbsp;<?=$v['receiverAddress']?><br/>
                        <?=$v['receiverMobile']?>
                    </td>

                </tr>
                <!--订单详情-->
                <tr>
                    <td colspan="3" style="height:70px;border-bottom: 1px solid black">
                        <div style="width: 270px;float: left;overflow: hidden;border-right: 1px solid black;height: 70px;vertical-align:middle;">
                            订单详情：<p style="font-size: 12px;margin-top:-3px">
                                <?php foreach ($v['sProductInfo'] as $value):?>
                                    <?=$value['sName']?>*<?=$value['lQuantity']?><br/>
                                <?php endforeach;?>
                            </p>
                        </div>
                        <div style="float: left;overflow: hidden;">

                        </div>
                    </td>
                </tr>
                <tr>
                    <td colspan="3" style="height: 14px">
                        <span style="float: right;line-height: 11px;font-size: 10px">已验视</span>
                    </td>
                </tr>
            </table>
        </div>
    <?}else{?>
        <div style="width:375px;height:655px;overflow: hidden">
            <table style="font-size:14px; border:1px solid black; width:100%;height:650px;overflow: hidden; border-collapse:collapse;">
                <!--代收款-->
                <tr >
                    <td colspan="3" style="height: 64px;width: 100% ;border-bottom: 1px solid black;overflow: hidden">

                        <div class="b">代收货款</div>
                        <div>
                            金额：<span class="f14">&nbsp;元</span>
                        </div>

                    </td>
                </tr>

                <!--地名-->
                <tr>
                    <td colspan="3" style="height:72px ;text-align: center;width: 100%;border-bottom: 1px solid black;overflow: hidden;font-size: 32px; font-weight: bold;letter-spacing:5px; line-height:0.95;font-family: Microsoft YaHei">
                        <?=$v['MarkDestination']?>
                    </td>

                </tr>
                <!--条形码-->
                <tr>
                    <td colspan="3" style=" height: 70px;width: 100%;overflow: hidden;border-bottom: 1px solid black">
                        <!--                    <div style=" height: 50px;border:1px solid red"></div>-->
                    </td>

                </tr>

                <!--信息-->
                <!--寄件人-->
                <tr style="height:65px;">
                    <td style="width:60px;height:65px;vertical-align:top;border-bottom: 1px solid black;padding:0 5px; overflow: hidden;">
                        寄件人:
                    </td>
                    <td  style="border-bottom: 1px solid black;padding:0 5px; overflow: hidden;height:65px;" >
                        <?=$v['senderName']?>&nbsp;&nbsp;<?=$v['senderProvinceName']?>&nbsp;<?=$v['senderCityName']?>&nbsp;<?=$v['senderExpAreaName']?>&nbsp;<?=$v['senderAddress']?><br/>
                        <?=$v['senderMobile']?>
                    </td>
                    <td rowspan="3" style="border-left: 1px solid black;border-bottom: 5px solid black;width:25px;text-align: center">签收联</td>
                </tr>
                <!--收件人-->
                <tr style="height:65px;">
                    <td style="width:60px;vertical-align:top;height:65px;border-bottom: 1px solid black;padding:0 5px; overflow: hidden;">
                        收件人:
                    </td>
                    <td  style="border-bottom: 1px solid black;padding:0 5px; ">
                        <?=$v['receiverName']?>&nbsp;&nbsp;<?=$v['receiverProvinceName']?>&nbsp;<?=$v['receiverCityName']?>&nbsp;<?=$v['receiverExpAreaName']?>&nbsp;<?=$v['receiverAddress']?><br/>
                        <?=$v['receiverMobile']?>
                    </td>

                </tr>
                <!--其他-->
                <tr >
                    <td colspan="2" style="vertical-align:top;border-bottom: 5px solid black;height: 68px">
                        <div style="width: 168px;float: left;overflow: hidden;border-right: 1px solid black;height: 68px">
                            收件人/代收人：
                        </div>
                        <div style="width: 160px;float: left;overflow: hidden;">
                            签收时间：<br/><br/>
                            &nbsp;<span style="float: right">&nbsp; 年&nbsp; 月&nbsp; 日</span>
                        </div>
                    </td>
                </tr>

                <!--下联-->
                <tr >
                    <td colspan="3" style="height:40px;border-bottom: 1px solid black ">
                        <div style="width: 168px;float: left;overflow: hidden;border-right: 1px solid black;height: 40px;vertical-align:middle;">

                        </div>
                        <div style="width: 168px;float: left;overflow: hidden;">

                        </div>
                    </td>
                </tr>

                <!--寄件人-->
                <tr style="height:42px;">
                    <td style="width:60px;height:42px;vertical-align:top;border-bottom: 1px solid black;padding:0 5px; overflow: hidden;">
                        寄件人:
                    </td>
                    <td  style="border-bottom: 1px solid black;padding:0 5px; overflow: hidden;height:42px;" >
                        <?=$v['senderName']?>&nbsp;&nbsp;<?=$v['senderProvinceName']?>&nbsp;<?=$v['senderCityName']?>&nbsp;<?=$v['senderExpAreaName']?>&nbsp;<?=$v['senderAddress']?><br/>
                        <?=$v['senderMobile']?>
                    </td>
                    <td rowspan="2" style="border-left: 1px solid black;border-bottom: 1px solid black;width:25px;text-align: center">收件联</td>
                </tr>
                <!--收件人-->
                <tr style="height:42px;">
                    <td style="width:60px;vertical-align:top;height:42px;border-bottom: 1px solid black;padding:0 5px; overflow: hidden;">
                        收件人:
                    </td>
                    <td  style="border-bottom: 1px solid black;padding:0 5px; ">
                        <?=$v['receiverName']?>&nbsp;&nbsp;<?=$v['receiverProvinceName']?>&nbsp;<?=$v['receiverCityName']?>&nbsp;<?=$v['receiverExpAreaName']?>&nbsp;<?=$v['receiverAddress']?><br/>
                        <?=$v['receiverMobile']?>
                    </td>

                </tr>
                <!--订单详情-->
                <tr>
                    <td colspan="3" style="height: 70px;border-bottom: 1px solid black">
                        <div style="width: 270px;float: left;overflow: hidden;border-right: 1px solid black;height: 70px;vertical-align:middle;">
                            订单详情：<p style="font-size: 12px;margin-top:-3px">
                                <?php foreach ($v['sProductInfo'] as $value):?>
                                    <?=$value['sName']?>*<?=$value['lQuantity']?><br/>
                                <?php endforeach;?>


                            </p>
                        </div>
                        <div style="float: left;overflow: hidden;">

                        </div>
                    </td>
                </tr>
                <tr>
                    <td colspan="3" style="height: 14px">
                        <span style="float: right;line-height: 11px;font-size: 10px">已验视</span>
                    </td>
                </tr>
            </table>
        </div>
    <?}?>
<?}?>
</body>

