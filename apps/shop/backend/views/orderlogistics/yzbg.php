<body>
<? foreach($data as $k=>$v){?>
    <div style="width:375px;height:680px;">
        <table style="font-size:14px; border:1px solid black; width:375px;height:650px;overflow: hidden; border-collapse:collapse;">
            <!--头部-->
            <tr>
                <td colspan="3" style="height: 34px;border-bottom: 1px solid black;">
                    <div style="float: left;overflow: hidden;height: 34px;">
                        <img height="32" src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAHkAAAAiCAMAAABMbYZPAAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAAABhQTFRFFhYWWFhY1tbWf39/v7+/Pz8/AAAA////uiq4sAAAAoVJREFUeNrsl9tu7CAMRX0B5///uLYBOyEwk1bnaFSpPEwTAizYvlE4PtXgt5ALfYhchC+vlxbdUjfTWco7smR7QS5yaVcyeZ8Pp9oaw1uyPCUDRxsj9fBSSyH9WKENzx3CSZwN+dbV963L+e47uUYLcmdU22TNM9oj9G/lBXll4XMLcvTUJLNK4WSSXF3JujnTBpgW5JR4Fru48ZraJcnpUEm2DifzsI0+VN2QnZbC/TZkeUjmYYGyIIPQiaxW13lFcEPeWXpDDrlPdratOLkOipPtQz0wTL8m312sNN/qv5PaAOep6WEjipysjypC6LBRe0Veelgjo+l7V/sYhh5kFMgsA08SiEdV6kwRVR7P7sq8IvetBZkipO7kb2XPZQ5Lcg1Ds6B5WLWInsnzEpujz+SaWftu50ga3P0QQEBihTVZHpIhk9jJt+3Nz8zDwfxZowzU1FLpAfkHalc3rYJOQ0vnY/tzzp5tWv7u7D18a/GG+Gqo76kR6VIxps3Hsf//neROlk+QXew7WQMD2RyV3F1dTnYFGV1dvBRUq0gFAVx0sqjiuCLQFM8JWli6GNfis91oqhytAFsydKfCiBf36jZ0xDT4ZCbvZ76Tr5aerlDsjkszWQ9gZPJ6cAoAjSDwxa1GeCT56XlVMV6Sq8QWTC8YZE1PTtb1RxnSQlgYhXtBtHEqCGA53pMXYQVJVrk4yLooGxmAsR+6hTmOS4BvgCyP4Zb86j7UDoQyq22PmpBdiV4UI4e0ioh2D+Ih24q8ucIOh9VDqZ43D7MvViEgq2ZJVwNzqUp6IWPz8x+RNSI07eKRZHL79cLRYNUPmXmz9DkaVaY/PSH//Uf3R/5H7UuAAQBJVmQXqLW9eQAAAABJRU5ErkJggg==" alt="" />
                    </div>

                </td>
            </tr>
            <tr>
                <td colspan="3" style="height:56px;border-bottom: 1px solid black;">
                    <div style="overflow: hidden;width: 100%;font-family: '黑体';font-size: 26px;font-weight:bold;text-align: center">
						<?=$v['receiverExpAreaName']?>
                    </div>
                </td>
            </tr>

            <tr style="border-bottom: 1px solid black">
                <td colspan="2" style="font-family:'黑体';font-size: 22px;height:38px;font-weight:bold;border-bottom: 1px solid black">
					<?=$v['receiverProvinceName']?><?=$v['receiverCityName']?>
                </td>
                <td style="border-left: 1px solid black;border-bottom: 1px solid black  "></td>
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
                    <div style="width:170px;overflow:hidden;height:70px;border-right: 1px solid black;float: left;">
                        <div style="line-height:12px;font-size:12px;transform: scale(0.93)">快件送达收件人地址，经收件人或收件人（寄件人）允许的代收人签字，视为送达。您的签字代表您已验收此包裹，并已确认商品信息无误、包装完好、没有划痕、破损等表面质量问题。</div>

                    </div>
                    <div style=" width:130px;line-height:33px;font-size: 12px;border-right: 1px solid black;padding:0;margin:0;float: left">
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
                        <img height="32" src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAHkAAAAiCAMAAABMbYZPAAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAAABhQTFRFFhYWWFhY1tbWf39/v7+/Pz8/AAAA////uiq4sAAAAoVJREFUeNrsl9tu7CAMRX0B5///uLYBOyEwk1bnaFSpPEwTAizYvlE4PtXgt5ALfYhchC+vlxbdUjfTWco7smR7QS5yaVcyeZ8Pp9oaw1uyPCUDRxsj9fBSSyH9WKENzx3CSZwN+dbV963L+e47uUYLcmdU22TNM9oj9G/lBXll4XMLcvTUJLNK4WSSXF3JujnTBpgW5JR4Fru48ZraJcnpUEm2DifzsI0+VN2QnZbC/TZkeUjmYYGyIIPQiaxW13lFcEPeWXpDDrlPdratOLkOipPtQz0wTL8m312sNN/qv5PaAOep6WEjipysjypC6LBRe0Veelgjo+l7V/sYhh5kFMgsA08SiEdV6kwRVR7P7sq8IvetBZkipO7kb2XPZQ5Lcg1Ds6B5WLWInsnzEpujz+SaWftu50ga3P0QQEBihTVZHpIhk9jJt+3Nz8zDwfxZowzU1FLpAfkHalc3rYJOQ0vnY/tzzp5tWv7u7D18a/GG+Gqo76kR6VIxps3Hsf//neROlk+QXew7WQMD2RyV3F1dTnYFGV1dvBRUq0gFAVx0sqjiuCLQFM8JWli6GNfis91oqhytAFsydKfCiBf36jZ0xDT4ZCbvZ76Tr5aerlDsjkszWQ9gZPJ6cAoAjSDwxa1GeCT56XlVMV6Sq8QWTC8YZE1PTtb1RxnSQlgYhXtBtHEqCGA53pMXYQVJVrk4yLooGxmAsR+6hTmOS4BvgCyP4Zb86j7UDoQyq22PmpBdiV4UI4e0ioh2D+Ih24q8ucIOh9VDqZ43D7MvViEgq2ZJVwNzqUp6IWPz8x+RNSI07eKRZHL79cLRYNUPmXmz9DkaVaY/PSH//Uf3R/5H7UuAAQBJVmQXqLW9eQAAAABJRU5ErkJggg==" alt="" />                </div>
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
                <td colspan="3" style="overflow: hidden;font-size: 12px;line-height: 12px;position: relative;">
                    <div style="height: 80px;overflow: hidden;">
						<? foreach ($v['sProductInfo'] as $k => $value){?>
							<?=$value['sName']?>*<?=$value['lQuantity']?>
							<?if($k%2 != 0){?>
                                <br/>
							<?}?>
						<?}?>
                        <div style='position: absolute;font-size:12px;width:40px;overflow: hidden;bottom: 3px;right: 2px;'>已验视</div>
                    </div>
                </td>
            </tr>

        </table>
    </div>
<?}?>
</body>
