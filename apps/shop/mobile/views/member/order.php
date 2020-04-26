<?php $this->beginBlock('style')?>
    <link rel="stylesheet" href="/css/orderDetail.css?<?=\Yii::$app->request->sRandomKey?>">
<?php $this->endBlock() ?>
    <div class="order_detail_wrap">
        <div class="ad_header">
            <a href="javascript:;" onclick="goBack()" class="ad_back">
                <span class="icon">&#xe885;</span>
            </a>
            <h2>订单详情</h2>
            <span class="ad_more icon">&#xe602;</span>
        </div>
        <div class="detail_status" style="background: linear-gradient(to bottom right, #E0B991, #AC7C4E);">
            <em><?= $order->sStatus ?></em>
            <? if ($order->StatusID == 'unpaid') { ?>
                <p class="unpaid">
                    <span></span>内付款，超时订单将自动关闭
                </p>
            <? } ?>
        </div>
        <div class="order_info">
            <!-- 物流信息 -->
            <?
            $arrTraceInfo = [];
            foreach ($order->arrShipDetail as $detail) {
                $traceInfo = json_decode($detail->trace->sTraceInfo, true);
                if ($traceInfo[0]) {
                    $arrTraceInfo[$detail->sExpressNo] = $traceInfo[0];
                }
            }
            ?>
            <? foreach ($arrTraceInfo as $i => $traceInfo) { ?>
                <div class="logistics flex"
                     onclick="location.href='/member/trace?id=<?= $order->lID ?>&index=<?= $i ?>'">
                    <i class="logistics_icon"></i>
                    <div class="logistics_info">
                        <h5><?= $traceInfo['context'] ?></h5>
                        <p class="logistics_time"><?= $traceInfo['time'] ?></p>
                    </div>
                </div>
            <? } ?>
            <!-- 个人信息 -->
            <div class="person">
                <div class="title flex">
                    <span class="name"><?= $order->orderAddress->sName ?></span>
                    <span class="tel"><?= $order->orderAddress->sMobile ?></span>
                </div>
                <div class="con"><?= $order->orderAddress->province->sName ?><?= $order->orderAddress->city->sName ?><?= $order->orderAddress->area->sName ?>
                    <?= $order->orderAddress->sAddress ?></div>
            </div>
        </div>
        <section>
            <h3 class="layer_title" onclick="location.href='<?= \yii\helpers\Url::toRoute([
                \Yii::$app->request->shopUrl . "/supplier/detail",
                'id' => $order->supplier->lID
            ], true) ?>'">
                <span class="title_word singleEllipsis"><?= $order->supplier->sName ?></span>
                <i class="title_arrow"></i>
            </h3>
            <div class="list_content">
                <? foreach ($order->arrDetail as $detail) { ?>
                    <div class="list_item_warp">
                        <a href="<?= \yii\helpers\Url::toRoute([
                            \Yii::$app->request->shopUrl . "/product/detail",
                            'id' => $detail->ProductID
                        ], true) ?>">
                            <div class="list_item flex">
                                <div class="pic"><img src="<?= Yii::$app->request->imgUrl ?>/<?= $detail->sPic ?>"
                                                      alt=""></div>
                                <div class="info">
                                    <h4 class="title"><?= $detail->sName ?></h4>
                                    <div class="prop"><?= $detail->sSKU ?>
                                        <i class="num">X<?= $detail->lQuantity ?></i>
                                    </div>
                                    <div class="price flex">
                                        <em>￥<?= number_format($detail->fPrice, 2) ?></em>
                                    </div>
                                </div>
                            </div>
                        </a>
                        <? if ($canRefund&&$order->MemberID==\Yii::$app->frontsession->MemberID) { ?>
                            <div class="operation">
                                <a href="<?= \yii\helpers\Url::toRoute([
                                    "/member/refundapply",
                                    'id' => $order->lID
                                ], true) ?>"  class="opt_back_btn refundapply">申请退款</a>
                            </div>
                        <? } elseif ($detail->bRefunding) { ?>
                            <div class="operation">
                                <a href="<?= \yii\helpers\Url::toRoute([
                                    "/member/refunddetail",
                                    'id' => $detail->RefundID
                                ], true) ?>" <? if (!$bMyOrder) { ?>onclick="return false"<? } ?> class="opt_back_btn refundapply" style="border:0;color:#ff0000">退款中</a>
                            </div>
                        <? } elseif ($detail->StatusID == 'success') { ?>
                            <div class="operation">
                                <a href="<?= \yii\helpers\Url::toRoute([
                                    "/member/refunddetail",
                                    'id' => $detail->RefundID
                                ], true) ?>" <? if (!$bMyOrder) { ?>onclick="return false"<? } ?> class="opt_back_btn" style="border:0;color:#ff0000">退款成功</a>
                            </div>
                        <? } ?>
                    </div>
                <? } ?>
            </div>
            <div class="list_footer">
                <div class="fright flex">
                    <span>运费</span>
                    <span><?= $order->fSupplierShip > 0 ? "￥" . number_format($order->fSupplierShip, 2) : "免运费" ?></span>
                </div>
                <div class="pay">
                    <? if ($order->StatusID == 'unpaid' || $order->StatusID == 'exception') { ?>
                        应付款：<em>￥<?= number_format($order->fDue, 2) ?></em>
                    <? } else { ?>
                        实付款：<em>￥<?= number_format($order->fPaid, 2) ?></em>
                    <? } ?>
                </div>
            </div>
        </section>

        <? if ($order->sMessage) { ?>
        <div class="buyer_operation">
            <div class="message flex">
                <span>买家留言:</span>
                <p><?= $order->sMessage ?></p>
            </div>
<!--            <a href="tel:--><?//= \myerm\shop\common\models\MallConfig::getValueByKey('sServiceNum') ?><!--"-->
<!--               class="connect_service">-->
<!--                <i></i>-->
<!--                <span>联系客服</span>-->
<!--            </a>-->
        </div>
        <? } ?>

        <div class="order_detail_info">
            <div class="order_number flex">
                <span>订单编号:</span>
                <span><?= $order->sName ?></span>
            </div>
            <div class="order_time flex">
                <span>下单时间:</span>
                <span><?= $order->dNewDate ?></span>
            </div>
            <? if ($order->dPayDate) { ?>
                <div class="pay_time flex">
                    <span>付款时间:</span>
                    <span><?= $order->dPayDate ?></span>
                </div>
            <? } ?>
            <? if ($order->arrShipDetail) { ?>
                <?
                $arrShipDetail = [];
                foreach ($order->arrShipDetail as $detail) {
                    $arrShipDetail[$detail->sExpressNo] = $detail->dShipDate;
                }
                foreach ($arrShipDetail as $dShipDate) {
                    ?>
                    <div class="send_time flex">

                    <span>发货时间:</span>
                    <span>
                    <?
                    echo $dShipDate . "<br>";

                    ?>
                </span>
                    </div><? } ?>
            <? } ?>
            <? if ($order->dReceiveDate) { ?>
                <div class="pay_time flex">
                    <span>确认收货:</span>
                    <span><?= $order->dReceiveDate ?></span>
                </div>
            <? } ?>
            <? if ($order->dCloseDate) { ?>
                <div class="pay_time flex">
                    <span>关闭时间:</span>
                    <span><?= $order->dCloseDate ?></span>
                </div>
            <? } ?>
        </div>
    </div>
    <div class="bottom_operation flex" style="display: none;">
        <? if ($order->arrShipDetail) { ?>
            <a href="/member/trace?id=<?= $order->lID ?>">查看物流</a>
        <? } ?>
        <? if ($order->StatusID == 'delivered' && $bMyOrder) { ?>
            <a href="javascript:;" onclick="confirmReceive()" class="red">确认收货</a>
        <? } ?>
        <? if ($order->StatusID == 'unpaid' && $bMyOrder) { ?>
            <a href="javascript:;" onclick="cancelOrder()" class="red">取消订单</a>
        <? } ?>
    </div>
    <div class="nav_more_list">
        <div class="triangle-up"></div>
        <ul>
            <li class="flex" onclick="location.href='<?= \Yii::$app->request->mallHomeUrl ?>'">
                <span class="icon">&#xe608;</span>
                <em>首页</em>
            </li>
            <li class="flex" onclick="location.href='<?= \yii\helpers\Url::toRoute(["/cart"], true) ?>'">
                <span class="icon">&#xe60a;</span>
                <em>购物车</em>
            </li>
            <li class="flex" onclick="location.href='<?= \yii\helpers\Url::toRoute(["/member"],
                true) ?>'">
                <span class="icon">&#xe64a;</span>
                <em>我的</em>
            </li>
        </ul>
    </div>
    <!-- 遮罩 -->
    <div class="mask"></div>
    <!-- 弹框提示 -->
    <div class="message_alert">
        <h2 class="alert_title">有商品退款中，无法确认收货</h2>
        <div class="alert_btn">知道了</div>
    </div>
    <!-- 加载图 -->
    <div class="weui-loading_toast" style="display: none;">
        <div class="weui-mask_transparent"></div>
        <div class="weui-toast">
            <i class="weui-loading weui-icon_toast"></i>
        </div>
    </div>
<?php $this->beginBlock('foot') ?>
    <script type="text/javascript">
        var isPageHide = false;
        window.addEventListener('pageshow', function () {
            if (isPageHide) {
                window.location.reload();
            }
        });

        window.addEventListener('pagehide', function () {
            isPageHide = true;
        });


        $(function () {
            if( !isIOS() ) {
                var load = sessionStorage.getItem('isreload');
                if (load) {
                    location.reload();
                    sessionStorage.removeItem('isreload');
                }
            }

            $('.ad_more').on('click', function () {
                event.stopPropagation();
                $(".nav_more_list").toggle();
            })

            $(window).on('scroll', function () {
                $(".nav_more_list").hide();
            })

            $('body').on('click', function () {
                $(".nav_more_list").hide();
            })
            $('.connect_service').on('touchstart', function () {
                $(".nav_more_list").hide();
            })
            $('.alert_btn').on('click', function () {
                $('.message_alert').hide();
                $('.mask').hide();
            })

            if ($(".bottom_operation a").length > 0) {
                $(".bottom_operation").show();
            }

            $('.refundapply').on('click', function () {
                sessionStorage.setItem('isreload', 'true');
            })
        })

        <?php
        $lTimeLeft = \myerm\shop\common\models\MallConfig::getValueByKey('lOrderAutoCloseTime') * 3600 + strtotime($order->dNewDate) - time();
        ?>
        var lTimeLeft = <?=intval($lTimeLeft)?>;

        function countDown() {
            if (lTimeLeft >= 0) {
                var d = Math.floor(lTimeLeft / 86400);
                var h = Math.floor((lTimeLeft - d * 86400) / 3600);
                var m = Math.floor((lTimeLeft - d * 86400 - 3600 * h) / 60);
                var i = lTimeLeft - d * 86400 - 3600 * h - m * 60;

                $(".unpaid span").html(d + "天" + h + "小时" + m + "分" + i + "秒");
            } else {
                $(".unpaid span").html("0小时0分0秒");
            }
        }
        setInterval("lTimeLeft--;countDown()", 1000);
        countDown();

        function pay() {
            $(".weui-loading_toast").show();
            $.post
            (
                '/member/wxpay?id=<?=$_GET['id']?>',
                {_csrf: '<?= \Yii::$app->request->getCsrfToken() ?>'},
                function (data) {
                    $(".weui-loading_toast").hide();
                    if (data.status) {
                        var config = data.config;
                        wx.chooseWXPay({
                            timestamp: config.timestamp,
                            nonceStr: config.nonceStr,
                            package: config.package,
                            signType: config.signType,
                            paySign: config.paySign, // 支付签名
                            success: function (res) {
                                if (res.errMsg == "chooseWXPay:ok") {
                                    location.reload();
                                } else {
                                    shoperm.showTip(res.errMsg);
                                }
                            },
                            cancel: function (res) {
                            }
                        });
                    } else {
                        shoperm.showTip(data.message);
                    }
                }
            )
        }

        function confirmReceive() {
            $('.mask').show();
            shoperm.selection('是否确认收货', sureReceive, cancelReceive);
        }
        //确认收货
        function sureReceive() {
            $(".weui-loading_toast").show();
            $.post
            (
                '/member/confirmreceive?id=<?=$_GET['id']?>',
                {_csrf: '<?= \Yii::$app->request->getCsrfToken() ?>'},
                function (data) {
                    $(".weui-loading_toast").hide();
                    if (!data.status) {
                        $(".alert_title").html(data.message);
                        $('.message_alert').show();
                        $('.mask').show();
                    } else {
                        location.reload();
                    }
                }
            )
        }
        //取消
        function cancelReceive() {
            $('.mask').hide();
        }

        //取消订单
        function cancelOrder() {
            $('.mask').show();
            shoperm.selection('是否取消订单', sureCancel, cancelCancel);
        }
        //确认取消
        function sureCancel() {
            $(".weui-loading_toast").show();
            $.post
            (
                '/member/cancelorder?id=<?=$_GET['id']?>',
                {_csrf: '<?= \Yii::$app->request->getCsrfToken() ?>'},
                function (data) {
                    $(".weui-loading_toast").hide();
                    if (!data.status) {
                        $(".alert_title").html(data.message);
                        $('.message_alert').show();
                        $('.mask').show();
                    } else {
                        location.reload();
                    }
                }
            )
        }
        //取消
        function cancelCancel() {
            $('.mask').hide();
        }
    </script>
<? if (\Yii::$app->params['isWeChat'] && Yii::$app->request->userIP != '127.0.0.1') { ?>
    <script src="https://res.wx.qq.com/open/js/jweixin-1.0.0.js" type="text/javascript" charset="utf-8"></script>
    <script type="text/javascript" charset="utf-8">
        wx.config(<?php echo \Yii::$app->wechat->js->config(['chooseWXPay']) ?>);
    </script>
<? } ?>
<?php $this->endBlock() ?>