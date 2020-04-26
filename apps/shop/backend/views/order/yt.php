
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
                            <img height="35" class="logo" src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAIoAAAAoCAMAAAAWjZKzAAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAAABhQTFRF4dTfYjFxsZm56nkuiGKT86VnSQVb////WZAgDwAABCdJREFUeNrEWIty5CAMs7wu/P8f1y+IIWzn2ttumdk2L0DIsnBC/SeNqL++fXtMYoE34dmX6Q+gMFppwn5RJA/eCGUFYg3kPPErePnOENIOjTspkveyQmjHJp1F6J1QniExLDOhqLbItHJ25R9tCGgeLE/TofMNiVTZyCWmLXQQjWomnGvNB0WLscnIZLD/AorMvmU0PJaGTSCd1tMBBaM1nU/HstRvdqYotAuzgtITTTqbVwnVP7rMAYXL6ubqZUEiW0hWKI2msNN1dHj0mMzxNYk1Yy4JPq+Ta0A5A1RmkDMp7QaF9wtJvXGgP7K5LASqaQ+orxusia+AOHIOYOcPwXOMMIDOtT6+IMVX5H2UZ1RagIQiZPCIrgFRDQHOmclSMTXrRAlFJs/n8Dz27EmeTYUotCgVbDLQ1bKu0+LBrnB2WcBYaOLagTKmJs3VKqtU+YlmHwuKJd5DW5GOTXJV+t+gKLSQAsx+bAblTWyeONXHxOIlPDN6cDEmeXx8fAwYeniRAnanx8wcXrPPIOhGaTRQnhsFYjli3bhkOtuz/oS4R1IvYqnJSQNJvQ2qLoMSW8kE0lXbohkTis8V6ao3WZzYTKjLnSYU2VXZ+2CFSoLH5jeSzs+WPjp2C0rYdcnbXPpUXuDMN/IgcqTb3SUj7gNKtRAOVKFWX9jKFwyCrVooKQttSM7lcjeSgkVVsXhXDqfqtwhFNpT4TDEPfswpsbLpFIWTsonDc9m0IQ4wkQliXgm/Nhk5j9Nt1wiFAdABypQ1EopshUuMzTazDs6pczHrTUK5JwwbofllpTcqL7rtY/wcClqFshRSoNtmaLtAZj+cDQ+Muo6Yo5APp96rCcdSAkS7hxfVbkqKZ8zAgQ2KeZY3nU83Qy51n1U1lUZ1Uy6nBUrHlq5FtevOl8TBH+Rbmh9eDpYKxHF6LUxRs0ejAkW2/b7E51Y1ucnyyiZeV8XJOmKVyskBNcLDpmpcLTixWjICfG/O1wEjgCczcWlcKcZ/zSZHKNJKoeEJgLZ5HE/6OHaAjjyaly5nuyoeyQtzO7xiMOJTVeudYGvBsOwLOKFIxRIzDhDO4qnLWd/kgd4L6egtiqwA0T2DdigXZ3K9oC4bQS0SLF958Ch5QG4kkdocnivJjmRdgJOv0EG1eXu+YBQ/maXRtZUNUG1stBibQt7jIUlOMgkntz2rNgeyt2R7Yz4U/jLryRnRpNG6jfkw6t/xFNrgUP4dCrevGuZOlglkm0+uXIYCJOJj82aWRSWhaSeH7VCOqn36krq9fNAIlU2KoZCxliwFOkpKSak3plai0dEiT95yqvenYdhfmgaT92InqN6T9zg/j7zkRfXdn3pIfhfJf3/UAPe/gHIIkrzwo9x3P4BVW4G89OvgTz4L+hu6CL/4M+XPhvuVj6WfAgwAx/B1isMqYE8AAAAASUVORK5CYII=" alt="" />
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
                            <img height="35" class="logo" src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAIoAAAAoCAMAAAAWjZKzAAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAAABhQTFRF4dTfYjFxsZm56nkuiGKT86VnSQVb////WZAgDwAABCdJREFUeNrEWIty5CAMs7wu/P8f1y+IIWzn2ttumdk2L0DIsnBC/SeNqL++fXtMYoE34dmX6Q+gMFppwn5RJA/eCGUFYg3kPPErePnOENIOjTspkveyQmjHJp1F6J1QniExLDOhqLbItHJ25R9tCGgeLE/TofMNiVTZyCWmLXQQjWomnGvNB0WLscnIZLD/AorMvmU0PJaGTSCd1tMBBaM1nU/HstRvdqYotAuzgtITTTqbVwnVP7rMAYXL6ubqZUEiW0hWKI2msNN1dHj0mMzxNYk1Yy4JPq+Ta0A5A1RmkDMp7QaF9wtJvXGgP7K5LASqaQ+orxusia+AOHIOYOcPwXOMMIDOtT6+IMVX5H2UZ1RagIQiZPCIrgFRDQHOmclSMTXrRAlFJs/n8Dz27EmeTYUotCgVbDLQ1bKu0+LBrnB2WcBYaOLagTKmJs3VKqtU+YlmHwuKJd5DW5GOTXJV+t+gKLSQAsx+bAblTWyeONXHxOIlPDN6cDEmeXx8fAwYeniRAnanx8wcXrPPIOhGaTRQnhsFYjli3bhkOtuz/oS4R1IvYqnJSQNJvQ2qLoMSW8kE0lXbohkTis8V6ao3WZzYTKjLnSYU2VXZ+2CFSoLH5jeSzs+WPjp2C0rYdcnbXPpUXuDMN/IgcqTb3SUj7gNKtRAOVKFWX9jKFwyCrVooKQttSM7lcjeSgkVVsXhXDqfqtwhFNpT4TDEPfswpsbLpFIWTsonDc9m0IQ4wkQliXgm/Nhk5j9Nt1wiFAdABypQ1EopshUuMzTazDs6pczHrTUK5JwwbofllpTcqL7rtY/wcClqFshRSoNtmaLtAZj+cDQ+Muo6Yo5APp96rCcdSAkS7hxfVbkqKZ8zAgQ2KeZY3nU83Qy51n1U1lUZ1Uy6nBUrHlq5FtevOl8TBH+Rbmh9eDpYKxHF6LUxRs0ejAkW2/b7E51Y1ucnyyiZeV8XJOmKVyskBNcLDpmpcLTixWjICfG/O1wEjgCczcWlcKcZ/zSZHKNJKoeEJgLZ5HE/6OHaAjjyaly5nuyoeyQtzO7xiMOJTVeudYGvBsOwLOKFIxRIzDhDO4qnLWd/kgd4L6egtiqwA0T2DdigXZ3K9oC4bQS0SLF958Ch5QG4kkdocnivJjmRdgJOv0EG1eXu+YBQ/maXRtZUNUG1stBibQt7jIUlOMgkntz2rNgeyt2R7Yz4U/jLryRnRpNG6jfkw6t/xFNrgUP4dCrevGuZOlglkm0+uXIYCJOJj82aWRSWhaSeH7VCOqn36krq9fNAIlU2KoZCxliwFOkpKSak3plai0dEiT95yqvenYdhfmgaT92InqN6T9zg/j7zkRfXdn3pIfhfJf3/UAPe/gHIIkrzwo9x3P4BVW4G89OvgTz4L+hu6CL/4M+XPhvuVj6WfAgwAx/B1isMqYE8AAAAASUVORK5CYII=" alt="" />
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

