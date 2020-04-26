<?php $this->beginBlock('style') ?>
    <link rel="stylesheet" href="/css/confirmOrder.css?<?= \Yii::$app->request->sRandomKey ?>">
    <link rel="stylesheet" href="/css/ydui.css">
    <style>
        .order-addr::before,.order-addr::after{ display: none}
        .header {
            background: linear-gradient(to bottom right, #E0B991, #AC7C4E);
        }

        .header .portrait {
            width: 4rem;
            height: 4rem;
            margin-top: 0.5rem;
            margin-left: 0.5rem;
            -webkit-border-radius: 50%;
            border-radius: 50%;
            border: 2px solid #eee;
            overflow: hidden;
            background: #fff;
            margin-top: 0.8rem;
            margin-bottom: 0.3rem;
        }
        .header{
            height: 6rem;
        }
        .header .person_name_wrap {
            margin-top: 1rem;
        }

        .person_name_wrap {
            height: 3rem;
            width: 12rem;
        }
        .person_title {
            padding-left: 0.4rem;
            font-size: 0.56rem;
            display: block;
            clear: both;
            height: 1rem;
            width: 100%;
        }

    </style>
<?php $this->endBlock() ?>
    <div class="check">
        <div class="ad_header">
            <a href="javascript:;" onclick="goBack()" class="ad_back"> <span class="icon">&#xe885;</span> </a>
            <h2>订单确认</h2>
        </div>

        <header class="header flex">
            <div class="portrait">
                <img src="<?= $member->sAvatarPath ? $member->sAvatarPath : "/images/order/person.png" ?>">
            </div>
            <div class="person_name_wrap">
                <h3 class="person_title" style="font-size: 0.66rem"><?= $seller->sName ?></h3>
                <h3 class="person_title">手机：<?= $address->tel ?></h3>
                <h3 class="person_title">
                    取货地址：<?= $address->province->sName . $address->city->sName . $address->area->sName . $address->sAddress ?></h3>

            </div>
        </header>


        <form name="checkoutform">
            <input type="hidden" name="_csrf" value="<?= \Yii::$app->request->getCsrfToken() ?>">
            <input type="hidden" name="addressid" value="<?= $address->lID ?>">
            <input type="hidden" name="redbagID" value="<?= $redbag ? $redbag->lID : 0 ?>">
            <section class="list-wrap">
                <section class="list-layer">
                    <?php foreach ($checkoutProduct as $cproduct) { ?>
                        <h3 class="layer-title"><?= $cproduct->product->supplier->sName ?></h3>
                        <div class="list-content">
                            <div class="list-item">
                                <div class="pic">
                                    <img src="<?= $cproduct->product->masterPic ?>" alt="">
                                </div>
                                <div class="info">
                                    <h4 class="title"><?= $cproduct->product->sName ?></h4>
                                    <div class="prop">
                                        <i class="num"> X<?= $cproduct->lQuantity ?></i>
                                    </div>
                                    <div class="price">￥<?= $cproduct->product->fGroupPrice ?></div>
                                </div>
                            </div>
                        </div>
                    <? } ?>
                    <div class="list-footer">
                        <div class="express layer">
                            <span> 运费</span>
                            <? if ($bNoDeliver) { ?>
                                <span>该地区不发货</span>
                            <? } else { ?>
                                <span> <?= $shipcount == '0.00' ? "免运费" : "￥" . number_format($shipcount, 2) ?></span>
                            <? } ?>
                        </div>
                        <? if ($redbag) { ?>
                            <div class="express layer">
                                <span style="color: red;">满减券，满<?= $redbag->fTopMoney ?>,减<?= $redbag->fChange ?></span>
                                <span> <?= number_format($redbag->fChange, 2) ?></span>
                            </div>
                        <? } ?>
                        <? if ($redProduct) { foreach ($redProduct as $rp){?>
                            <div class="express layer">
                                <span style="color: red;"><?=$rp['sName']?>，购买立减<?=$rp['fChange']?>&nbsp;&nbsp;X<?=$rp['lQuantity']?></span>
                                <span> <?= number_format($rp['fChange']*$rp['lQuantity'], 2) ?></span>
                            </div>
                        <? }} ?>
                        <? if ($fService) { ?>
                            <div class="express layer">
                                <span> 服务费</span>
                                <span> <?= number_format($fService, 2) ?></span>
                            </div>
                        <? } ?>

                        <div class="message layer">
                            <label for=""> 买家留言</label> <input type="text" name="message" placeholder="选填，不超过80字"
                                                               class="message_input" maxlength='80'>
                        </div>
                        <? if ($redbag) { ?>
                            <div class="total layer"> 小计：<em
                                        class="price-text">￥<?= number_format($totalPrice - $redbag->fChange, 2) ?></em>
                            </div>
                        <? } else { ?>
                            <div class="total layer"> 小计：<em
                                        class="price-text">￥<?= number_format($totalPrice, 2) ?></em>
                            </div>
                        <? } ?>
                    </div>
                </section>
            </section>
        </form>
    </div>
    <!-- 底部支付按钮 -->
    <section class="footer-confirm">
        <? if ($redbag) { ?>
            <div class="all-total">
                总计：<em
                        class="price-text">￥<i
                            id="price_total"><?= number_format($totalPrice - $redbag->fChange, 2) ?></i></em>
            </div>
        <? } else { ?>
            <div class="all-total">
                总计：<em
                        class="price-text">￥<i id="price_total"><?= number_format($totalPrice, 2) ?></i></em>
            </div>
        <? } ?>
        <!--没有可以购买的商品时 class为off-->
        <div style="background: #E0B991" class="btn-confirm <? if ($bNoDeliver) {
            echo 'off';
        } ?>"> 提交订单
        </div>
    </section>

    <!-- 弹框提示 -->
    <div class="message_alert">
        <h2 class="alert_title">未达起送条件</h2>
        <div class="alert_reason"></div>
        <div class="alert_btn" onclick="errorres();">继续选购</div>
        <span class="alert_close"></span>
    </div>


    <!-- 遮罩 -->
    <div class="mask"></div>

    <script src='/js/jquery.min.js'></script>
    <script type="text/javascript" src="https://res.wx.qq.com/open/js/jweixin-1.3.2.js"></script>
    <script>
        function errorres() {
            $('.message_alert').hide();
            $('.mask').hide();
            var msg = $('.alert_reason').html();

            location.href = "<?=\yii\helpers\Url::toRoute([\Yii::$app->request->shopUrl . "/home"], true)?>";

        }
    </script>
<?php $this->beginBlock('foot') ?>
    <script src="/js/ydui.citys.js"></script>
    <script src="/js/ydui.js"></script>
    <script>
        var bLogin = <?=intval(Yii::$app->frontsession->bLogin)?>; //是否登录
        $(function () {
            if (!bLogin) {
                $('.wx_mask').show();
                $('.login_wrap').show();
            }
            //立即支付
            $('.btn-confirm').on('click', function () {
                //不发货地区不可提交订单 panlong 2019年9月16日14:26:14
                <?if($bNoDeliver){?>
                return false;
                <?}?>

                var isEmpty = $('.ad_empty'); //没有那个空地址的div时
                if (isEmpty.length) {
                    $('.add_mask').show();
                    $('body').css('overflow', 'hidden');
                    return;
                }
                var price = $('#price_total').html();
                $('.weui-loading-toast').show();
                //提交接口
                var url = '/cart/saveorder';
                $.post(url, $("form").serialize(),
                    function (res) {
                        $('.weui-loading-toast').hide();
                        if (!res.status) {
                            $(".message_alert").show();
                            $(".message_alert .alert_reason").html(res.message);
                            $(".mask").show();
                        } else {
                            location.href = "https://yl.aiyolian.cn/cart/cashier?no=" + res.strTradeNo;
                        }
                    }, 'json');
            });
        })
    </script>


<?php $this->endBlock() ?>