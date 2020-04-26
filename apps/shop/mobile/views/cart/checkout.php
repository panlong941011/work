<?php $this->beginBlock('style') ?>
    <link rel="stylesheet" href="/css/confirmOrder.css?<?= \Yii::$app->request->sRandomKey ?>">
    <link rel="stylesheet" href="/css/ydui.css">
<?php $this->endBlock() ?>
    <div class="check">
        <div class="ad_header">
            <a href="javascript:;" onclick="goBack()" class="ad_back"> <span class="icon">&#xe885;</span> </a>
            <h2>订单确认</h2>
        </div>
        <? if (!$address) { ?>
            <section class="ad_empty">
                <div class="add_btn">
                    <p> 添加收货地址</p>
                </div>
                <div class="adorn"></div>
            </section>
        <? } else { ?>
            <section class="order-addr"
                     onclick="location.href='<?= \yii\helpers\Url::toRoute([
                         "/address/list",
                         'addressid' => $address->lID
                     ],
                         true) ?>';">
                <div class="title">
                    <span class="name"> <?= $address->sName ?></span> <span class="tel"> <?= $address->sMobile ?></span>
                </div>
                <div
                        class="con"><?= $address->province->sName ?><?= $address->city->sName ?><?= $address->area->sName ?> <?= $address->sAddress ?></div>
                <div class="adorn"></div>
            </section>
        <? } ?>
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
                                    <div class="price">￥<?= $cproduct->product->fPrice ?></div>
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
                合计：<em
                        class="price-text">￥<i
                            id="price_total"><?= number_format($totalPrice - $redbag->fChange, 2) ?></i></em>
            </div>
        <? } else { ?>
            <div class="all-total">
                合计：<em
                        class="price-text">￥<i id="price_total"><?= number_format($totalPrice, 2) ?></i></em>
            </div>
        <? } ?>
        <!--没有可以购买的商品时 class为off-->
        <div class="btn-confirm <? if ($bNoDeliver) {
            echo 'off';
        } ?>"> 提交订单
        </div>
    </section>


    <!-- 填写地址弹框 -->
    <div class="add_mask" style="display: none;">
        <div class="addr_main">
            <div class="addr_title"> 添加新收货地址</div>
            <div class="address_receiver">
                <input class="addr_name" id="name" placeholder="名字" type="text">
                <input class="addr_mobile" id="mobile" placeholder="电话" type="tel">
            </div>
            <div class="addr_region">
                <input type="text" readonly id="J_Address" class="c_area flexOne" name="input_area" placeholder="请选择地区">
            </div>
            <div class="addr_address">
                <textarea id="address_text" placeholder="详细地址（可填写街道、小区、大厦）" class="detail_position"></textarea>
            </div>
            <div class="addr_save"> 保存</div>
            <div class="addr_close">
                <i></i>
            </div>
        </div>
    </div>

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
        function checkout(id, sTradeno, fPaid) {
            var ua = window.navigator.userAgent.toLowerCase();
            if (ua.match(/MicroMessenger/i) == 'micromessenger') {
                //判断是否是微信环境
                //微信环境
                wx.miniProgram.getEnv(function (res) {
                    if (res.miniprogram) {
                        // 小程序环境下逻辑
                        wx.miniProgram.navigateTo({
                            url: '/pages/pay/pay?sTradeNo=' + sTradeno + '&fPaid=' + fPaid
                        });
                    } else {
                        //非小程序环境下逻辑
                        location.href = "https://yl.aiyolian.cn/cart/cashier?no=" + id;
                    }
                })
            }
        }

        function errorres() {
            $('.message_alert').hide();
            $('.mask').hide();
            var msg = $('.alert_reason').html();
             
                location.href ="<?=\yii\helpers\Url::toRoute([\Yii::$app->request->shopUrl . "/home"], true)?>";

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
                        console.log(res);
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
            //地址关闭按钮
            $('.addr_close').on('click', function (event) {
                event.stopPropagation();
                $('.add_mask').hide();

                $('body,html').css('overflow', '');
            })

            //保存地址
            $('.addr_save').on('click', function () {
                var sName = $.trim($('.addr_name').val()),
                    sPhone = $.trim($('.addr_mobile').val()),
                    sArea = $('.c_area').val().split(' ').join(','), //分割成后端的数据格式
                    sPosition = $.trim($('.detail_position').val()),
                    validate = /^1\d{10}$/,
                    areaData = {
                        name: sName,
                        mobile: sPhone,
                        area: sArea,
                        address: sPosition
                    };
                if (sName == '') {
                    shoperm.showTip("收货人名字不能为空");
                    return;
                }
                if (sName.length > 8) {
                    shoperm.showTip("名字不能超过8个字");
                    $('.addr_name').addClass('input_error');
                    return;
                }
                if (sPhone == '') {
                    shoperm.showTip("收货人电话不能为空");
                    return;
                }
                if (!validate.test(sPhone)) {
                    $('.addr_mobile').addClass('input_error');
                    shoperm.showTip("请输入正确的手机号");
                    return;
                }
                if (sArea == '') {
                    shoperm.showTip("请选择地区");
                    return;
                }
                if (sPosition == '') {
                    shoperm.showTip("详细地址不能为空");
                    return;
                }
                if (sPosition.length > 60) {
                    $('.detail_position').addClass('input_error');
                    shoperm.showTip("地址不能超过60个字");
                    return;
                }

                //保存地址接口
                $.post('/address/new', areaData,
                    function (res) {
                        console.log(res);
                        location.reload();//刷新页面
                    }, 'json')
            })

            //信息填写完全时显示保存按钮颜色
            $('.addr_name,.addr_mobile,.detail_position').on('input', function () {
                listenerVal();
            });
            $('.addr_name,.addr_mobile,.detail_position').on('click', function () {
                $(this).removeClass('input_error');
            });

            $(".ad_empty").click(
                function () {
                    $('.add_mask').show();
                    $('body').css('overflow', 'hidden');
                }
            )

        })

        //保存按钮是否显示红色
        function listenerVal() {
            var name = $.trim($('.addr_name').val()),
                phone = $.trim($('.addr_mobile').val()),
                area = $('.c_area').val(),
                position = $.trim($('.detail_position').val());
            if (name !== '' && phone !== '' && area !== '' && position !== '') {
                $('.addr_save').addClass('active');
            } else {
                $('.addr_save').removeClass('active');
            }
        }

    </script>
    <script>

        var $target = $('#J_Address');
        $target.citySelect();
        $target.on('click', function (event) {
            event.stopPropagation();
            $target.citySelect('open');

        });
        $target.on('done.ydui.cityselect', function (ret) {

            $(this).val(ret.provance + ' ' + ret.city + ' ' + ret.area);
            listenerVal();

            var sProvince = ret.provance;
            var sCity = '';

        });


    </script>

<?php $this->endBlock() ?>