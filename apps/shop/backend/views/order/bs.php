<body>
<? foreach ($data as $k => $v) { ?>
    <div style="width:375px;height:671px;">
        <table style="font-size:14px; border:1px solid black; width:375px;height:650px;overflow: hidden; border-collapse:collapse;">
            <!--头部-->
            <tr>
                <td colspan="3" style="height: 40px;border-bottom: 1px solid black;">
                    <div style="float: left;overflow: hidden;height: 40px">
                        <img height="28" alt="logo" src="data:image/png;base64,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" style="margin-top: 2px">

                    </div>
                    <div style="width: 110px;float: right;overflow: hidden;height:40px">
                        <div style="font-weight:bold;letter-spacing:3px;font-family: '黑体'; font-size: 14px;margin-left: 20px">
                            标准快件
                        </div>
                        <div style=" font-family: '黑体';font-size: 8px;">
                            精彩生活·快递欢乐
                        </div>
                    </div>
                </td>
            </tr>
            <tr>
                <td colspan="3" style="height:56px;border-bottom: 1px solid black;">
                    <div style="overflow: hidden;width: 100%;font-family: '黑体';font-size: 26px;font-weight:bold;">
						<?= $v['MarkDestination'] ?>&nbsp;<?= $v['SortingCode'] ?>
                    </div>
                </td>
            </tr>

            <tr>
                <td colspan="3" style="font-family:'黑体';font-size: 22px;height:38px;border-bottom: 1px solid black;font-weight:bold;">
					<?= $v['PackageCode'] ?>
                </td>
            </tr>

            <!--收件-->
            <tr>
                <td style="width: 18px;height: 75px;border-bottom: 1px solid black;text-align:center;border-right:1px solid black;">收<br/>件
                </td>
                <td style="border-bottom: 1px solid black;font-size: 10px;border-right:1px solid black;">

                    <div style="font-size: 14px;overflow: hidden;height:75px ">
						<?= $v['receiverName'] ?> &nbsp;&nbsp;&nbsp;<?= $v['receiverMobile'] ?> <br/>
						<?= $v['receiverProvinceName'] ?><?= $v['receiverCityName'] ?><?= $v['receiverExpAreaName'] ?><?= $v['receiverAddress'] ?>

                    </div>
                </td>
                <td rowspan="2" style="width:90px;border-bottom: 1px solid black;vertical-align:top; padding:0;">
                    <div style="border-bottom:1px solid #000; text-align:center;font-size: 12px;">服&nbsp;务</div>
                </td>
            </tr>
            <!--寄件-->
            <tr>
                <td style="height: 45px;border-bottom: 1px solid black;text-align:center;border-right:1px solid black;">寄<br/>件
                </td>
                <td style="border-bottom: 1px solid black;border-right:1px solid black;">
                    <div style="font-size: 14px;overflow: hidden;height:45px ">
						<?= $v['senderName'] ?> &nbsp;&nbsp;&nbsp;<?= $v['senderMobile'] ?> <br/>
						<?= $v['senderProvinceName'] ?><?= $v['senderCityName'] ?><?= $v['senderExpAreaName'] ?><?= $v['senderAddress'] ?>
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
                        签收人：<br/>时间：
                    </div>
                    <div style=" font-family: '黑体';font-size: 12px;float: left">
                    </div>
                </td>
            </tr>

            <!--条形码-->
            <tr>
                <td colspan="3" style="height: 35px;border-bottom: 1px solid black;">
                    <div style="height: 35px;float: left;width: 180px">
                        <img height="28" alt="logo" src="data:image/png;base64,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">
                    </div>
                    <div style="height: 35px;float: left;">
                    </div>
                </td>
            </tr>


            <!--收件-->
            <tr>
                <td style="width: 18px;height: 45px;border-bottom: 1px solid black;text-align:center;border-right:1px solid black;">收<br/>件
                </td>
                <td style="border-bottom: 1px solid black;font-size: 10px;border-right:1px solid black;">

                    <div style="font-size: 12px;overflow: hidden;height:45px;margin-top: 3px ">
						<?= $v['receiverName'] ?> &nbsp;&nbsp;&nbsp;<?= $v['receiverMobile'] ?> <br/>
						<?= $v['receiverProvinceName'] ?><?= $v['receiverCityName'] ?><?= $v['receiverExpAreaName'] ?><?= $v['receiverAddress'] ?>
                    </div>
                </td>
                <td rowspan="2" style="width:80px;border-bottom: 1px solid black;vertical-align:top;">
                </td>
            </tr>
            <!--寄件-->
            <tr>
                <td style="height: 45px;border-bottom: 1px solid black;text-align:center;border-right:1px solid black;">寄<br/>件
                </td>
                <td style="border-bottom: 1px solid black;border-right:1px solid black;">
                    <div style="font-size: 12px;overflow: hidden;height:45px;margin-top: 3px  ">
						<?= $v['senderName'] ?> &nbsp;&nbsp;&nbsp;<?= $v['senderMobile'] ?> <br/>
						<?= $v['senderProvinceName'] ?><?= $v['senderCityName'] ?><?= $v['senderExpAreaName'] ?><?= $v['senderAddress'] ?>
                    </div>
                </td>

            </tr>
            <!--商品信息-->
            <tr height="80">
                <td colspan="3" style="vertical-align:bottom;overflow: hidden;font-size: 12px;line-height: 12px">
                    <div style="height: 80px;overflow: hidden;">
						<?php foreach ($v['sProductInfo'] as $k => $value): ?>
							<?= $value['sName'] ?>*<?= $value['lQuantity'] ?>
                            <?if($k%2 != 0){?>
                                <br/>
                            <?}?>
						<?php endforeach; ?>
                    </div>
                </td>
            </tr>
        </table>
    </div>
<? } ?>
</body>


