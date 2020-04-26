<body>
<? foreach($data as $k=>$v){?>
    <? if(count($data)-$k !=1 ){?>
        <div style="width:375px;height:670px;">
            <table style="font-size:14px; border:1px solid black; width:375px;height:650px;overflow: hidden; border-collapse:collapse;">
                <!--头部-->
                <tr>
                    <td colspan="3" style="height: 40px;border-bottom: 1px solid black;">
                        <div style="float: left;overflow: hidden;height: 40px">

                        </div>
                        <div style="width: 110px;float: right;overflow: hidden;height:40px">
                            <div style="font-weight:bold;letter-spacing:3px;font-family: '黑体'; font-size: 14px;margin-left: 20px">
                                标准快件
                            </div>
                            <div style=" font-family: '黑体';font-size: 8px;">
                            </div>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td colspan="3" style="height:56px;border-bottom: 1px solid black;">
                        <div style="overflow: hidden;width: 100%;font-family: '黑体';font-size: 26px;font-weight:bold;text-align: center">
                            <?=$v['MarkDestination']?>
                        </div>
                    </td>
                </tr>

                <tr>
                    <td colspan="3" style="font-family:'黑体';font-size: 22px;height:38px;border-bottom: 1px solid black;font-weight:bold;">
                        <?=$v['PackageName']?>
                    </td>
                </tr>

                <!--收件-->
                <tr>
                    <td style="width: 18px;height: 75px;border-bottom: 1px solid black;text-align:center;border-right:1px solid black;">收<br />件</td>
                    <td style="border-bottom: 1px solid black;font-size: 10px;border-right:1px solid black;">

                        <div style="font-size: 14px;overflow: hidden;height:75px ">
                            <?=$v['receiverName']?> &nbsp;&nbsp;&nbsp;<?=$v['receiverMobile']?> <br/>
                            <?=$v['receiverProvinceName']?><?=$v['receiverCityName']?><?=$v['receiverExpAreaName']?><?=$v['receiverAddress']?>

                        </div>
                    </td>
                    <td rowspan="2" style="width:90px;border-bottom: 1px solid black;vertical-align:top; padding:0;">
                        <div style="border-bottom:1px solid #000; text-align:center;font-size: 12px;">服&nbsp;务</div>
                    </td>
                </tr>
                <!--寄件-->
                <tr>
                    <td style="height: 45px;border-bottom: 1px solid black;text-align:center;border-right:1px solid black;">寄<br />件</td>
                    <td style="border-bottom: 1px solid black;border-right:1px solid black;">
                        <div style="font-size: 14px;overflow: hidden;height:45px ">
                            <?=$v['senderName']?> &nbsp;&nbsp;&nbsp;<?=$v['senderMobile']?> <br/>
                            <?=$v['senderProvinceName']?><?=$v['senderCityName']?><?=$v['senderExpAreaName']?><?=$v['senderAddress']?>
                        </div>
                    </td>

                </tr>

                <!--条形码-->
                <tr>
                    <td colspan="3" style="height: 80px;border-bottom: 1px solid black">

                    </td>
                </tr>
                <tr>
                    <td colspan="3" style="height: 70px;border-bottom: 5px solid black;">
                        <div style="width:150px;font-family: '黑体'; overflow:hidden;font-size:12px;;line-height:15px;border-right: 1px solid black;margin:0;float: left">
                            您对此单的签收，代表您已验收，确认商品信息无误，包装完好，没有划痕、破损等表面质量问题
                        </div>
                        <div style=" width:150px;font-family: '黑体';line-height:33px;font-size: 12px;border-right: 1px solid black;padding:0;margin:0;float: left">
                            签收人：<br />时间：
                        </div>
                        <div style=" font-family: '黑体';font-size: 12px;float: left">
                        </div>
                    </td>
                </tr>

                <!--条形码-->
                <tr>
                    <td colspan="3" style="height: 35px;border-bottom: 1px solid black;">
                        <div style="height: 35px;float: left;width: 180px">
                        </div>
                        <div style="height: 35px;float: left;">
                        </div>
                    </td>
                </tr>


                <!--收件-->
                <tr>
                    <td style="width: 18px;height: 45px;border-bottom: 1px solid black;text-align:center;border-right:1px solid black;">收<br />件</td>
                    <td style="border-bottom: 1px solid black;font-size: 10px;border-right:1px solid black;">

                        <div style="font-size: 12px;overflow: hidden;height:45px;margin-top: 3px ">

                            <?=$v['receiverName']?> &nbsp;&nbsp;&nbsp;<?=$v['receiverMobile']?> <br/>
                            <?=$v['receiverProvinceName']?><?=$v['receiverCityName']?><?=$v['receiverExpAreaName']?><?=$v['receiverAddress']?>

                        </div>
                    </td>
                    <td rowspan="2"  style="width:80px;border-bottom: 1px solid black;vertical-align:top;">
                    </td>
                </tr>
                <!--寄件-->
                <tr>
                    <td style="height: 45px;border-bottom: 1px solid black;text-align:center;border-right:1px solid black;">寄<br />件</td>
                    <td style="border-bottom: 1px solid black;border-right:1px solid black;">
                        <div style="font-size: 12px;overflow: hidden;height:45px;margin-top: 3px  ">
                            <?=$v['senderName']?> &nbsp;&nbsp;&nbsp;<?=$v['senderMobile']?> <br/>
                            <?=$v['senderProvinceName']?><?=$v['senderCityName']?><?=$v['senderExpAreaName']?><?=$v['senderAddress']?>
                        </div>
                    </td>

                </tr>
                <!--商品信息-->
                <tr height="80">
                    <td colspan="3" style="vertical-align:bottom;overflow: hidden;font-size: 12px;line-height: 12px">
                        <div style="height: 80px;overflow: hidden;">
                            <?php foreach ($v['sProductInfo'] as $value):?>
                                <?=$value['sName']?>*<?=$value['lQuantity']?><br/>
                            <?php endforeach;?>

                        </div>
                        <!--<span style='font-size:12px;'>已验视</span>-->
                    </td>
                </tr>

            </table>
        </div>
    <?}else{?>
        <div style="width:375px;height:650px;">
            <table style="font-size:14px; border:1px solid black; width:375px;height:645px;overflow: hidden; border-collapse:collapse;">
                <!--头部-->
                <tr>
                    <td colspan="3" style="height: 40px;border-bottom: 1px solid black;">
                        <div style="float: left;overflow: hidden;height: 40px">

                        </div>
                        <div style="width: 110px;float: right;overflow: hidden;height:40px">
                            <div style="font-weight:bold;letter-spacing:3px;font-family: '黑体'; font-size: 14px;margin-left: 20px">
                                标准快件
                            </div>
                            <div style=" font-family: '黑体';font-size: 8px;">

                            </div>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td colspan="3" style="height:55px;border-bottom: 1px solid black;">
                        <div style="overflow: hidden;width: 100%;font-family: '黑体';font-size: 26px;font-weight:bold;text-align: center">
                            <?=$v['MarkDestination']?>
                        </div>
                    </td>
                </tr>

                <tr>
                    <td colspan="3" style="font-family:'黑体';font-size: 22px;height:38px;border-bottom: 1px solid black;font-weight:bold;">
                        <?=$v['PackageName']?>
                    </td>
                </tr>

                <!--收件-->
                <tr>
                    <td style="width: 18px;height: 75px;border-bottom: 1px solid black;text-align:center;border-right:1px solid black;">收<br />件</td>
                    <td style="border-bottom: 1px solid black;font-size: 10px;border-right:1px solid black;">

                        <div style="font-size: 14px;overflow: hidden;height:75px ">
                            <?=$v['receiverName']?> &nbsp;&nbsp;&nbsp;<?=$v['receiverMobile']?> <br/>
                            <?=$v['receiverProvinceName']?><?=$v['receiverCityName']?><?=$v['receiverExpAreaName']?><?=$v['receiverAddress']?>

                        </div>
                    </td>
                    <td rowspan="2" style="width:90px;border-bottom: 1px solid black;vertical-align:top; padding:0;">
                        <div style="border-bottom:1px solid #000; text-align:center;font-size: 12px;">服&nbsp;务</div>
                    </td>
                </tr>
                <!--寄件-->
                <tr>
                    <td style="height: 45px;border-bottom: 1px solid black;text-align:center;border-right:1px solid black;">寄<br />件</td>
                    <td style="border-bottom: 1px solid black;border-right:1px solid black;">
                        <div style="font-size: 14px;overflow: hidden;height:45px ">
                            <?=$v['senderName']?> &nbsp;&nbsp;&nbsp;<?=$v['senderMobile']?> <br/>
                            <?=$v['senderProvinceName']?><?=$v['senderCityName']?><?=$v['senderExpAreaName']?><?=$v['senderAddress']?>

                        </div>
                    </td>

                </tr>

                <!--条形码-->
                <tr>
                    <td colspan="3" style="height: 80px;border-bottom: 1px solid black">

                    </td>
                </tr>
                <tr>
                    <td colspan="3" style="height: 70px;border-bottom: 5px solid black;">
                        <div style="width:150px;font-family: '黑体'; overflow:hidden;font-size:12px;;line-height:15px;border-right: 1px solid black;margin:0;float: left">
                            您对此单的签收，代表您已验收，确认商品信息无误，包装完好，没有划痕、破损等表面质量问题
                        </div>
                        <div style=" width:150px;font-family: '黑体';line-height:33px;font-size: 12px;border-right: 1px solid black;float: left">
                            签收人：<br />时间：
                        </div>
                        <div style=" font-family: '黑体';font-size: 12px;float: left">
                        </div>
                    </td>
                </tr>

                <!--条形码-->
                <tr>
                    <td colspan="3" style="height: 35px;border-bottom: 1px solid black;">
                        <div style="height: 35px;float: left;width: 180px">
                        </div>
                        <div style="height: 35px;float: left;">

                        </div>
                    </td>
                </tr>


                <!--收件-->
                <tr>
                    <td style="width: 18px;height: 45px;border-bottom: 1px solid black;text-align:center;border-right:1px solid black;">收<br />件</td>
                    <td style="border-bottom: 1px solid black;font-size: 10px;border-right:1px solid black;">

                        <div style="font-size: 12px;overflow: hidden;height:45px;margin-top: 3px ">
                            <?=$v['receiverName']?> &nbsp;&nbsp;&nbsp;<?=$v['receiverMobile']?> <br/>
                            <?=$v['receiverProvinceName']?><?=$v['receiverCityName']?><?=$v['receiverExpAreaName']?><?=$v['receiverAddress']?>

                        </div>
                    </td>
                    <td rowspan="2"  style="width:80px;border-bottom: 1px solid black;vertical-align:top;">
                    </td>
                </tr>
                <!--寄件-->
                <tr>
                    <td style="height: 45px;border-bottom: 1px solid black;text-align:center;border-right:1px solid black;">寄<br />件</td>
                    <td style="border-bottom: 1px solid black;border-right:1px solid black;">
                        <div style="font-size: 12px;overflow: hidden;height:45px;margin-top: 3px  ">
                            <?=$v['senderName']?> &nbsp;&nbsp;&nbsp;<?=$v['senderMobile']?> <br/>
                            <?=$v['senderProvinceName']?><?=$v['senderCityName']?><?=$v['senderExpAreaName']?><?=$v['senderAddress']?>
                        </div>
                    </td>

                </tr>
                <!--商品信息-->
                <tr height="80">
                    <td colspan="3" style="vertical-align:bottom;overflow: hidden;font-size: 12px;line-height: 12px">
                        <div style="height: 80px;overflow: hidden;">
                            <?php foreach ($v['sProductInfo'] as $value):?>
                                <?=$value['sName']?>*<?=$value['lQuantity']?><br/>
                            <?php endforeach;?>


                        </div>
                        <!--<span style='font-size:12px;'>已验视</span>-->
                    </td>
                </tr>

            </table>
        </div>

    <?}?>
<?}?>
</body>
