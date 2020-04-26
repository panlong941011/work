<body>
<? foreach($data as $k=>$v) {?>
    <? if(count($data)-$k !=1 ){?>
        <div style="height:454px;margin: 0;padding: 0"  >
            <table class="print_paper table_first tc" style='margin: 0;padding: 0'>
                <tr class="vt">
                    <td class="vt">
                        <div style="height:86px"></div>
                    </td>
                </tr>
            </table>
            <table class="print_paper ">
                <tr>
                    <td class="vt" width="181" height="49">
                        <div class="f8">始发地:<?=$v['OriginName']?></div>
                        <div class="f20 b tc"><?=$v['OriginCode']?></div>
                    </td>
                    <td class="vt">
                        <div class="f8">目的地:<?=$v['DestinatioName']?></div>
                        <div class="f20 b tc"><?=$v['DestinatioCode']?></div>
                    </td>
                </tr>
            </table>
            <table class="print_paper f16 b tc ohide" height="26.4">
                <tr>
                    <td width="25">&nbsp;</td>
                    <td  width="155"><div style="white-space: nowrap;overflow: hidden;width:155px"><?=$v['PackageName']?></div></td>
                    <td width="37.5"><?=$v['PackageCode']?></td>
                    <td class="tc">1/1</td>
                </tr>
            </table>
            <table class="print_paper f9" height="73">
                <tr>
                    <td class=" pl5" width="20">
                        客户信息
                    </td>
                    <td class="vt pl5" width="188">
                        <div class="pt5">
                            <?=$v['receiverProvinceName']?><?=$v['receiverCityName']?><?=$v['receiverExpAreaName']?><?=$v['receiverAddress']?>
                        </div>
                        <br />
                        <div class="pt5"><?=$v['receiverName']?> <?=$v['receiverMobile']?></div>
                    </td>
                    <td class="vt">
                        <table class="print_paper  w1 f10">
                            <tr height="31">
                                <td class="bln" width="30">
                                    客户
                                    签字
                                </td>
                                <td class="tc brn">
                                    &nbsp;
                                </td>
                            </tr>
                            <tr height="31">
                                <td class="bln bbn">
                                    应收金额
                                </td>
                                <td class="tc bbn brn">
                                    &nbsp;元
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
            <div class="print_paper f6  pt5 tc" style="height: 17px"><span class="pl80"></span></div>
            <table class="print_paper  tc f16 b" height="26.4">
                <tr>
                    <td>运单号 <?=$v['LogisticCode']?></td>
                </tr>
            </table>
            <table class="print_paper" style=" height:67px;">
                <tr style="height:66px">
                    <td width="225">
                        <div class="f8">客户信息:<?=$v['receiverName']?> <?=$v['receiverMobile']?></div>
                        <div class="tc f8 b">
                            <div style="height:45px"></div>
                            <!--<span>VA40284819282</span>-->
                        </div>
                    </td>
                    <td class="f8 vt">
                        <div style="height:66px;">
                            <?php foreach ($v['sProductInfo'] as $value):?>
                                <?=$value['sName']?>*<?=$value['lQuantity']?><br/>
                            <?php endforeach;?>
                        </div>
                    </td>
                </tr>
            </table>
            <table class="print_paper f6" height="53" >
                <tr class="vt" height="27">
                    <td width="225">

                        <div>寄方信息:
                            <?=$v['senderProvinceName']?><?=$v['senderCityName']?><?=$v['senderExpAreaName']?><?=$v['senderAddress']?>
                        </div>
                        <div>寄方电话：<?=$v['senderMobile']?></div>
                    </td>
                    <td>
                        <div></div>
                        <div>B商家订单号:<?=$v['OrderCode']?></div>
                    </td>
                </tr>
                <tr class="vt" style="height: 27px;">
                    <td class="f5" width="225">
                        <div class="pt5" style="font-size: 8px;">请您确认包裹完好,保留此包裹联时,代表您已经正常签收并确认外包裹完好</div>
                        <div class="">http://www.jd-ex.com 客服电话：400-603-3600</div>
                    </td>
                    <td>
                        <span class="l">始发城市:</span>
                        <span class="f10 l pt5"><?=$v['senderCityName']?></span>
                    </td>
                </tr>
            </table>
        </div>
    <?}else{?>
        <div style="height:430px;margin: 0;padding: 0;overflow: hidden;"  >
            <table class="print_paper table_first tc" style='margin: 0;padding: 0'>
                <tr class="vt">
                    <td class="vt">
                        <div style="height:86px"></div>
                    </td>
                </tr>
            </table>
            <table class="print_paper ">
                <tr>
                    <td class="vt" width="181" height="49">
                        <div class="f8">始发地:<?=$v['OriginName']?></div>
                        <div class="f20 b tc"><?=$v['OriginCode']?></div>
                    </td>
                    <td class="vt">
                        <div class="f8">目的地:<?=$v['DestinatioName']?></div>
                        <div class="f20 b tc"><?=$v['DestinatioCode']?></div>
                    </td>
                </tr>
            </table>
            <table class="print_paper f16 b tc ohide" height="26.4">
                <tr>
                    <td width="25">&nbsp;</td>
                    <td  width="155"><div style="white-space: nowrap;overflow: hidden;width:155px"><?=$v['PackageName']?></div></td>
                    <td width="37.5"><?=$v['PackageCode']?></td>
                    <td class="tc">1/1</td>
                </tr>
            </table>
            <table class="print_paper f9" height="73">
                <tr>
                    <td class=" pl5" width="20">
                        客户信息
                    </td>
                    <td class="vt pl5" width="188">
                        <div class="pt5">
                            <?=$v['receiverProvinceName']?><?=$v['receiverCityName']?><?=$v['receiverExpAreaName']?><?=$v['receiverAddress']?>
                        </div>
                        <br />
                        <div class="pt5"><?=$v['receiverName']?> <?=$v['receiverMobile']?></div>
                    </td>
                    <td class="vt">
                        <table class="print_paper  w1 f10">
                            <tr height="31">
                                <td class="bln" width="30">
                                    客户
                                    签字
                                </td>
                                <td class="tc brn">
                                    &nbsp;
                                </td>
                            </tr>
                            <tr height="31">
                                <td class="bln bbn">
                                    应收金额
                                </td>
                                <td class="tc bbn brn">
                                    &nbsp;元
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
            <div class="print_paper f6  pt5 tc" style="height: 17px"><span class="pl80"></span></div>
            <table class="print_paper  tc f16 b" height="26.4">
                <tr>
                    <td>运单号 <?=$v['LogisticCode']?></td>
                </tr>
            </table>
            <table class="print_paper" style=" height:67px;">
                <tr style="height:66px">
                    <td width="225">
                        <div class="f8">客户信息:<?=$v['receiverName']?> <?=$v['receiverMobile']?></div>
                        <div class="tc f8 b">
                            <div style="height:45px"></div>
                            <!--<span>VA40284819282</span>-->
                        </div>
                    </td>
                    <td class="f8 vt">
                        <div style="height:66px;">
                            <?php foreach ($v['sProductInfo'] as $value):?>
                                <?=$value['sName']?>*<?=$value['lQuantity']?><br/>
                            <?php endforeach;?>
                        </div>
                    </td>
                </tr>
            </table>
            <table class="print_paper f6" height="53" >
                <tr class="vt" height="27">
                    <td width="225">
                        <div>寄方信息:
                            <?=$v['senderProvinceName']?><?=$v['senderCityName']?><?=$v['senderExpAreaName']?><?=$v['senderAddress']?>                        </div>
                        <div>寄方电话：<?=$v['senderMobile']?></div>
                    </td>
                    <td>
                        <div></div>
                        <div>B商家订单号:<?=$v['OrderCode']?></div>
                    </td>
                </tr>
                <tr class="vt" style="height: 27px;">
                    <td class="f5" width="225">
                        <div class="pt5" style="font-size: 8px;">请您确认包裹完好,保留此包裹联时,代表您已经正常签收并确认外包裹完好</div>
                        <div class="">http://www.jd-ex.com 客服电话：400-603-3600</div>
                    </td>
                    <td>
                        <span class="l">始发城市:</span>
                        <span class="f10 l pt5"><?=$v['senderCityName']?></span>
                    </td>
                </tr>
            </table>
        </div>

    <?}?>
<?}?>
</body>
