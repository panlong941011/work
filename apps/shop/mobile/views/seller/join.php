<?php $this->beginBlock('style') ?>
<link rel="stylesheet" href="/css/supplierMain.css?<?= \Yii::$app->request->sRandomKey ?>">
<link rel="stylesheet" href="/css/confirmOrder.css?<?= \Yii::$app->request->sRandomKey ?>">
<link rel="stylesheet" href="/css/ydui.css">
<style type="text/css">
    body, html {
        width: 100%;
        height: 100%;
        position: relative;
        overflow: hidden;
    }

    .settled_apply {

    }
</style>
<?php $this->endBlock() ?>
<div class="settled_apply">
    <div class="ad_header">
        <a href="javascript:;" class="ad_back" onclick="goBack()">
            <span class="icon">&#xe885;</span>
        </a>
        <h2>入驻申请</h2>
        <span class="ad_more icon">&#xe602;</span>
    </div>
    <div class="apply_content">
        <form class="apply_form">
            <? if (!$address) { ?>
                <section class="ad_empty">
                    <div class="add_btn">
                        <p> 添加收货地址</p>
                    </div>
                    <div class="adorn"></div>
                </section>
            <? } else { ?>
                <section class="order-addr"
                         onclick="if (!bOrder) { location.href='<?= \yii\helpers\Url::toRoute([
                             "/address/list",
                             'addressid' => $address->lID,
                             'from' => 'SellerJoin',//页面来源
                         ],
                             true) ?>';}">
                    <div class="title">
                        <span class="name"> <?= $address->sName ?></span>
                        <span class="tel"> <?= $address->sMobile ?></span>
                    </div>
                    <div
                        class="con"><?= $address->province->sName ?><?= $address->city->sName ?><?= $address->area->sName ?> <?= $address->sAddress ?></div>
                    <div class="adorn"></div>
                </section>
            <? } ?>
            <br>
            <input type="hidden" name="_csrf" value="<?= \Yii::$app->request->getCsrfToken() ?>">
            <div class="apply_input">
                <input type="text" name="" placeholder="真实姓名" id="sName">
            </div>
            <div class="apply_input">
                <input type="text" name="" placeholder="请输入手机号" id="sPhone"
                       value="<?= \Yii::$app->frontsession->member->sMobile ?>">
                <span class="code" style="cursor:pointer" onclick="sendCode()">获取短信验证码</span>
            </div>
            <div class="apply_input">
                <input type="text" name="" placeholder="请输入短信验证码" id="sCode">
            </div>
        </form>
        <div class="apply_type" style="display: none">
            <input type="text" name="" value="<?= $sellType->sName ?>" readonly="readonly" placeholder="请选择经销商类型"
                   id="sType">
        </div>
        <br>
        <div class="apply_input">
            <input type="text" value="<?= $sGiftTitle ?>" readonly name="" id="giftTitle">
        </div>

        <p class="apply_cost">入驻费用：¥ <?= number_format($fMoney, 2) ?></p>
    </div>
</div>
<div class="apply_sure" style="overflow:hidden">
    <div class="agreement flex" style="padding-right:85px">
        <i class="apply_icon active"></i>
        <p>我已阅读，并同意<a
                href="/seller/joincontract">《<?= \myerm\shop\common\models\MallConfig::getValueByKey('sMallName') ?>
                经销商协议》</a></p>
    </div>
    <a href="javascript:;" class="submit_btn" style="display:block">确认</a>
</div>

<!-- 经销商类型 -->
<div class="distributor_wrap">
    <div class="distributor">
        <h2>经销商类型</h2>
        <ul>
            <li class="distributor_type" id="<?= $sellType->lID ?>"
                money="<?= $sellType->fJoin ?>"><?= $sellType->sName ?></li>
        </ul>
        <div class="distributor_cancel">关闭</div>
    </div>
</div>


<div class="nav_more_list">
    <div class="triangle-up"></div>
    <ul>
        <li class="flex" onclick="location.href='<?= \Yii::$app->request->mallHomeUrl ?>'">
            <span class="icon">&#xe608;</span>
            <em>首页</em>
        </li>
        <li class="flex"
            onclick="location.href='<?= \yii\helpers\Url::toRoute(["/cart"], true) ?>'">
            <span class="icon">&#xe60a;</span>
            <em>购物车</em>
        </li>
        <li class="flex"
            onclick="location.href='<?= \yii\helpers\Url::toRoute(["/member"],
                true) ?>'">
            <span class="icon">&#xe64a;</span>
            <em>我的</em>
        </li>
    </ul>
</div>

<div class="weui-loading_toast" style="display: none;">
    <div class="weui-mask_transparent"></div>
    <div class="weui-toast">
        <i class="weui-loading weui-icon_toast"></i>
    </div>
</div>

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

<?php $this->beginBlock('foot') ?>

<script>

    var pattern = /^1\d{10}$/; //验证手机
    var jsPayConfig = {};
    var bOrder = false;//是否已经生成了订单

    $(function () {
        //键盘将Fixed元素撑起问题
        $('.apply_input input[type="text"]').bind('focus', function () {
            $('.apply_sure').css({'position': 'relative', 'marginTop': '442px'});
        }).bind('blur', function () {
            $('.apply_sure').css({'position': 'absolute'});
        })
        //更多栏目
        $('.ad_more').on('click', function () {
            event.stopPropagation();
            $(".nav_more_list").toggle();
        })
        $(window).on('click', function () {
            $(".nav_more_list").hide();
        })

        $('.apply_icon').on('click', function () {
            $('.apply_icon').toggleClass('active');
        })
        //供应商类型
        $('.apply_type').on('click', function () {
            $('.distributor_wrap').show();
        })
        $('.distributor_wrap,.distributor_cancel').on('click', function () {
            $('.distributor_wrap').hide();
        })
        $('.distributor_type').on('click', function (event) {
            event.stopPropagation();
            var value = $(this).html();
            $(this).addClass('active').siblings().removeClass('active');
            $('#sType').val(value);
        })

        //提交
        $('.submit_btn').on('click', function () {

            var pattern = /^1\d{10}$/; //验证手机
            var name = $.trim($('#sName').val()),
                phone = $.trim($('#sPhone').val()),
                code = $.trim($('#sCode').val()),
                type = $.trim($('#sType').val()),
                agreement = $('.apply_icon').hasClass('active'),
                giftTitle = $('#giftTitle').val(),
                addressid = <?= $address->lID ? $address->lID : 0  ?>;

            if (bOrder) {
                pay(jsPayConfig);
                return false;
            }
            if (!addressid) {
                shoperm.showTip("请添加收货地址");
                return;
            }
            if (!agreement) {
                shoperm.showTip("请同意经销商协议");
                return;
            }

            if (name == '') {
                shoperm.showTip("请输入姓名");
                return;
            }

            if (phone == '') {
                shoperm.showTip("手机号不得为空");
                return;
            }

            if (!pattern.test(phone)) {
                shoperm.showTip("请输入正确的手机号");
                return;
            }
            if (code == '') {
                shoperm.showTip("验证码不得为空");
                return;
            }
            if (type == '') {
                shoperm.showTip("请选择经销商类型");
                return;
            }
            if (!addressid) {
                shoperm.showTip("请选择收货地址");
                return;
            }

            $(".weui-loading_toast").show();

            $.post(
                '/<?=Yii::$app->request->shopUrl?>/seller/join',
                {
                    name: name,
                    phone: phone,
                    code: code,
                    type: type,
                    addressid: addressid,
                    giftTitle: giftTitle,
                    _csrf: '<?=\Yii::$app->request->getCsrfToken()?>'
                },
                function (data) {

                    $(".weui-loading_toast").hide();

                    if (data.status) {
                        if (data.pay) {
                            bOrder = true;
                            jsPayConfig = data.config
                            pay(data.config);
                        } else {
                            location.href = "/seller";
                        }
                    } else {
                        shoperm.showTip(data.message);
                    }
                }
            );
        })

        <? if (!Yii::$app->frontsession->bLogin) { ?>
        alert("请登录");
        location.href = "/member/login";
        <? } elseif (Yii::$app->frontsession->seller) { ?>
        alert("您已经是经销商，不得再提交申请");
        location.href = '/seller/index';
        <? } ?>

        $(".ad_empty").click(
            function () {
                $('.add_mask').show();
                $('body').css('overflow', 'hidden');
            }
        )
    })

    function sendCode() {
        if ($.trim($('#sPhone').val()) == "") {
            shoperm.showTip('手机号不得为空');
            return false;
        }

        if (!pattern.test($('#sPhone').val())) {
            shoperm.showTip('请输入正确的手机号');
            return;
        }

        $(".weui-loading_toast").show();

        $.post(
            '/seller/sendjoincode?mobile=' + $('#sPhone').val(),
            {_csrf: '<?= \Yii::$app->request->getCsrfToken() ?>'},
            function (data) {

                $(".weui-loading_toast").hide();

                if (data.status) {
                    countdown();
                    codecountdown = setInterval("countdown()", 1000);
                } else {
                    shoperm.showTip(data.message);
                }
            }
        )
    }

    var lCountDown = 60;
    function countdown() {
        $('.code').html(lCountDown + 's');
        lCountDown--;

        if (lCountDown == 0) {
            clearInterval(codecountdown);
            $('.code').html('获取短信验证码');
            lCountDown = 60;
        }
    }

    function pay(config) {
        wx.chooseWXPay({
            timestamp: config.timestamp,
            nonceStr: config.nonceStr,
            package: config.package,
            signType: config.signType,
            paySign: config.paySign, // 支付签名
            success: function (res) {
                if (res.errMsg == "chooseWXPay:ok") {
                    location.href = "/seller/joinpaysuccess";
                } else {
                    alert(res.errMsg);
                }
            },
            cancel: function (res) {

            }
        });
    }
</script>
<script src='/js/jquery.min.js'></script>
<script src="/js/ydui.citys.js"></script>
<script src="/js/ydui.js"></script>
<script>
    var jsPayConfig = {};
    var bOrder = false;//是否已经生成了订单
    var bLogin = <?=intval(Yii::$app->frontsession->bLogin)?>; //是否登录

    console.log(isMiniProgram());


    //hashChange();
    $(function () {

        if (!bLogin) {
            $('.wx_mask').show();
            $('.login_wrap').show();
        }
        //立即支付
        $('.btn-confirm').on('click', function () {
            var isOff = $(this).hasClass('off');
            if (isOff) {
                return false;
            }

            if (bOrder) {
                pay(jsPayConfig);
                return false;
            }

            var isEmpty = $('.ad_empty'); //没有那个空地址的div时
            if (isEmpty.length) {
                $('.add_mask').show();
                $('body').css('overflow', 'hidden');
                return;
            }

            $('.weui-loading-toast').show();

            //提交接口
            var jqxhr = $.post('/cart/saveorder', $("form").serialize(),
                function (res) {
                    console.log(res);
                    $('.weui-loading-toast').hide();

                    if (!res.status) {
                        $(".message_alert").show()
                        $(".message_alert .alert_reason").html(res.message);
                        $(".mask").show();
                    } else {

                        location.href = "/cart/cashier?no=" + res.sTradeNo;
                        return;

                        bOrder = true;
                        $(".btn-confirm").html("正在支付<br>请稍后");
                        $(".order-addr").click(
                            function () {
                                shoperm.showTip("订单已生成，信息不可更改");
                                return false;
                            }
                        );

                        jsPayConfig = res.config;

                        pay(jsPayConfig);
                    }


                }, 'json');
        });
        //地址关闭按钮
        $('.addr_close').on('click', function (event) {
            event.stopPropagation();
            $('.add_mask').hide();

            $('body,html').css('overflow', '');
            // $("body").unbind("touchmove");
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

        var pattern = /^1\d{10}$/; //验证手机

        // 登录
        $('.l_login').on('click', function () {
            var phone = $.trim($('.login_phone').val()),
                pwd = $.trim($('.login_pwd').val());
            if (phone == '') {
                shoperm.showTip("手机号不得为空");
                return;
            }
            if (!pattern.test(phone)) {
                shoperm.showTip("请输入正确的手机号");
                return;
            }
            if (pwd == '') {
                shoperm.showTip("密码不得为空");
                return;
            }

            $.post('/member/loginpost',
                {
                    mobile: phone,
                    password: pwd,
                    _csrf: '<?= \Yii::$app->request->getCsrfToken() ?>',
                },
                function (data) {
                    if (!data.status) {
                        shoperm.showTip(data.message);
                    } else {
                        location.reload();
                    }
                }, 'json');

        })


        $('.input_box input').on('click', function () {
            $(this).parent().addClass('active').siblings().removeClass('active');
        })


        //登录框关闭
        $('.close').on('click', function () {
            $('.login_wrap').hide();
        })

        //监听
        $(window).on('hashchange', function () {
            hashChange();
        })

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
    //地址栏变化改变结构
    function hashChange() {
        var hash = location.hash;
        console.log(hash);
        switch (hash) {
            case '':
                $('.check,.footer-confirm,.login_wrap').show();
                $('.login').hide();
                $('.register').hide();
                break;
            case '#find':
                $('.check,.footer-confirm,.login_wrap').hide();
                $('.login').show();
                break;
            case '#register':
                $('.check,.footer-confirm,.login_wrap').hide();
                $('.register').show();
                $('.protocol').hide();
                break;
            case '#protocol':
                $('.check,.footer-confirm,.login_wrap').hide();
                $('.register').hide();
                $('.protocol').show();
                break;
        }
    }
</script>
<script>

    var $target = $('#J_Address');
    $target.citySelect();
    $target.on('click', function (event) {
        //alert(123);return;
        //event.stopPropagation();
        $target.citySelect('open');

    });
    $target.on('done.ydui.cityselect', function (ret) {

        $(this).val(ret.provance + ' ' + ret.city + ' ' + ret.area);
        listenerVal();

        var sProvince = ret.provance;
        var sCity = '';

        /*if (sProvince == "北京" || sProvince == "上海" || sProvince == "天津" || sProvince == "重庆") {
         sCity = sProvince;
         } else {
         sCity = ret.city;
         }
         setFreight(ProductID, sProvince, sCity);*/
    });


    function pay(config) {
        wx.chooseWXPay({
            timestamp: config.timestamp,
            nonceStr: config.nonceStr,
            package: config.package,
            signType: config.signType,
            paySign: config.paySign, // 支付签名
            success: function (res) {
                if (res.errMsg == "chooseWXPay:ok") {
                    location.href = "/pay/success";
                } else {
                    alert(res.errMsg);
                }
            },
            cancel: function (res) {
                $(".btn-confirm").html("继续支付");
            }
        });
    }

</script>
<? if (\Yii::$app->params['isWeChat'] && Yii::$app->request->userIP != '127.0.0.1') { ?>
    <script src="https://res.wx.qq.com/open/js/jweixin-1.0.0.js" type="text/javascript" charset="utf-8"></script>

    <script type="text/javascript" charset="utf-8">
        wx.config(<?php echo \Yii::$app->wechat->js->config(['chooseWXPay']) ?>);
    </script>
<? } ?>
<?php $this->endBlock() ?>

