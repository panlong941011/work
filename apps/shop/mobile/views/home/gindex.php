<?php

use yii\helpers\Url;
use myerm\common\components\Func;

?>
<?php $this->beginBlock('style') ?>
    <link rel="stylesheet" href="/css/swiper.min.css">
    <link rel="stylesheet" href="/css/mescroll.min.css">
    <link rel="stylesheet" href="/css/home.css">
    <link rel="stylesheet" href="/css/address.css">
    <link rel="stylesheet" href="/css/ydui.css">
    <style>
        .lsj_tab_list {
            display: flex;
            flex-direction: row;
            background-color: #fff;
            color: #333;
            padding-top: .5rem;
        }

        .lsj_tab_list li {
            list-style-type: none;
        }

        .lsj_tab_list li span {
            font-size: 0.7rem;
        }

        .lsj_tab_list li.cur span {
            color: red;
            border-bottom: 0.1rem solid red;
            height: 100%;
            padding: 0 .2rem;
            display: inline-block;
            margin-bottom: 1px;
        }

        .lsj_tab_list li span a {
            font-weight: bold;
        }

        .lsj_tab_list li.cur span a {
            color: red;
            font-weight: bold;
        }

        .lsj_tab_list li {
            height: 1.3rem;
            line-height: .5rem;
            color: #fff;
            width: 7.5rem;
            text-align: center;
            margin: .05rem 0;
            color: #333;
        }



        .commodity_pic {
            height: 7.1rem;
            width: 100%;
            clear: both;
        }

        .commodity_pic img {
            height: 7rem;
            width: 7.5rem;
            border-radius: 0.5rem 0 0 0.5rem;
            float: left;
        }
        .commodity_pic .imgright {
            height: 7rem;
            width: 7.5rem;
            border-radius: 0 0.5rem 0.5rem 0;
            margin-left: -1px;
        }
        .commodity_detail {
            border: 0;
        }

        .commodity_list li:first-child {
            padding-top: 0.5rem;
        }
    </style>
    <style>
        .buy {
            text-align: right;
            height: 1.5rem;
            margin-right: 0.5rem;
            width: 9.6rem;
            background-color: #fff;
            z-index: 88;
            margin-bottom: 1px;
            position: relative;
        }

        .buy .icon {
            font-size: 0.7rem;
            margin-right: 1rem;
            z-index: 88;
        }

        .redimg {
            position: fixed;
            width: 6rem;
            top: 30%;
            left: 30%;
            z-index: 101;
            display: none;
        }

        .redimg img {
            height: auto;
            width: 8rem;
        }

        footer {
            position: fixed;
            left: 0;
            bottom: 0;
            width: 100%;
            z-index: 100;
        }

        .fix_btn {
            height: 2.1333333333rem;
            border-top: 1px solid #ccc;
            background: #fff;
            max-width: 16rem;
            margin: 0 auto;
        }

        .service {
            width: 4.6rem;
            height: 100%;
        }

        .service a img {
            width: 0.8533333333rem;
            height: 0.8533333333rem;
            margin: 0 auto;
        }

        .service a {
            -webkit-flex-direction: column;
            flex-direction: column;
            -webkit-justify-content: center;
            justify-content: center;
            height: 100%;
            width: 100%;
        }

        .service a span {
            display: block;
            text-align: center;
            color: #333;
        }

        .buy_now {
            width: 8rem;
            height: 100%;
            line-height: 2.1333333333rem;
            text-align: center;
            color: #fff;
            background: #f42323;
        }

        .buy_now {
            font-size: 0.6rem;
        }

        .timer {
            font-size: 0.6rem;
            background-color: #fff;
            line-height: 1rem;
            height: 1.7rem;
            padding-top: 0.3rem;
            margin-top: 0.1rem;
            clear: both;
        }

        .timer img {
            margin-top: 0.1rem;
        }

        .timer span {
            font-size: 0.5rem;
            background-color: #E0B991;
            color: #fff;
            padding: 0.1rem;
            border-radius: 0.2rem;
        }

        .divtiper {
            position: absolute;
            top: 3rem;
            left: 1rem;
            z-index: 10;
            font-size: 0.6rem;
            background-color: #eee;
            border-radius: 0.6rem;
            line-height: 1rem;
            padding: 0.1rem;
            background: rgba(0, 0, 0, 0.3);
            color: #fff;
        }

        .divtiper img {
            height: 1.1rem;
            width: 1.1rem;
            border-radius: 50%;
            display: block;
            float: left;
        }
    </style>
    <style>
        .commodity_btn {
            position: absolute;
            border: 1px solid #cccccc;
            right: 2.5rem;
            top: 0.2rem;
            background: #fff;
        }

        .commodity_btn span {
            width: 1.1rem;
            height: 1.1rem;
            line-height: 1.1rem;
            text-align: center;
            color: #333;
            display: inline-block;
        }

        .commodity_btn span {
            font-size: 36px;
        }

        .commodity_btn .btn_input {
            border-left: 1px solid #cccccc;
            border-right: 1px solid #ccc;
            width: 1.5rem;
            height: 1rem;
            line-height: 1.1rem;
            text-align: center;
            background: #fff;
            font-size: 32px;
        }

        .commodity_price p {
            line-height: 0.9rem;
        }
        .price_line{
            height: 1rem;
        }
        .price_line p{
            float: left;
        }
        .cartno{
            position: absolute;
            top: 0.1rem;
            z-index: 99;
            font-size: 0.5rem;
            height: 0.7rem;
            width: 0.7rem;
            line-height: 0.7rem;
            color: white;
            right: 0.25rem;
            text-align: center;
            border-radius: 100%;
            background: #ea3529;
        }
        .content_div h2{
            font-weight: bold;
            margin-top: 0.1rem;
            font-family: 宋体;
            font-size:.7rem !important;
        }
        .content_div h3{
            color:#A1A1A1;
        }
    </style>
<?php $this->endBlock() ?>

    <div class="index_wrap">
        <!-- 轮播图结构 -->
        <div class="banner_swiper">
            <div class="swiper-container">
                <div class="swiper-wrapper">
                    <div class="swiper-slide" onclick="">
                        <img src="/userfile/upload/2018/group.jpg?1" alt="">
                    </div>
                    <div class="swiper-slide" onclick="">
                        <img src="/userfile/upload/2018/group2.jpg?2" alt="">
                    </div>
                    <div class="swiper-slide" onclick="">
                        <img src="/userfile/upload/2018/group3.jpg?2" alt="">
                    </div>
                </div>
                <div class="swiper-pagination"></div>
            </div>
        </div>
        <!-- 菜单切换 -->
        <ul class="lsj_tab_list" style="display: none">
            <li class="cur"><span><a href="<?= Url::toRoute([\Yii::$app->request->shopUrl . "/home/index"], true) ?>">好货拼团</a></span>
            </li>
            <li><span><a href="<?= Url::toRoute([\Yii::$app->request->shopUrl . "/home/home"], true) ?>">精选到家</a></span>
            </li>
        </ul>
        <!-- 倒计时 -->
        <div class="timer">
            <div style="float: left;">
                <img src="/images/foot/timer.png" style="height: 1.3rem; width: auto;">
            </div>

            <div style="float: left;margin-left: 1rem;margin-top: 0.3rem" id="promotionDate">
                距结束：<span>12</span>：<span>00</span>：<span>00</span>
            </div>

        </div>
        <!--   商品结构 -->
        <div class="data_wrap">
            <div class="list_wrap">
                <? if ($arrProduct['status']) { ?>
                <? if ($arrProduct['data']['commodity']) { ?>
                    <div>
                        <div class="commodity_list">
                            <!--  <div class="line"></div> -->
                            <ul>
                                <? foreach ($arrProduct['data']['commodity'] as $key => $lItem) { ?>
                                    <li onclick="linkUrl('<?= $lItem['link'] ?>')">
                                        <div class="commodity_pic">
                                            <img src="<?= $lItem['image'] ?>" alt="" style="float: left"/>
                                            <img class="imgright" src="<?= $lItem['imageright'] ?>" alt=""style="float: left"/>
                                            <?if($timerMsg=='距截单'){?>
                                            <img src="/images/home/ms1.png" style="width: 3.3rem;height: 1.3rem;position: absolute;top: 0rem;left: .5rem;border-radius: 0 0 0.5rem 0.5rem">
                                            <?}?>
                                        </div>
                                        <div class="commodity flex">

                                            <div class="commodity_detail content_div">
                                                <h2
                                                    onclick="linkUrl('<?= $lItem['link'] ?>')"
                                                    class="multiEllipsis"><?= $lItem['title'] ?></h2>
                                                <h3 onclick="linkUrl('<?= $lItem['link'] ?>')"
                                                    class="multiEllipsis"><?= $lItem['sRecomm'] ?></h3>
                                                <div style="width: 100%;height: 2.2rem;border-bottom: 1px #ccc solid">
                                                    <div style="width: 3.5rem;float: left">
                                                        <span style="color: #66C480;display: block;clear: both;width: 3.5rem;border-bottom: 1px solid #7B7B7B">
                                                        <span style="font-size: .6rem">￥</span>
                                                        <span style="font-size: 1.0rem"><?= $lItem['price'] ?></span>
                                                    </span>
                                                        <span style="color:#7B7B7B;font-size: .45rem;display: block;clear: both;width: 3.5rem">
                                                        日 常 价：<span style="text-decoration: line-through"><?= $lItem['market_price'] ?></span>
                                                    </span>
                                                    </div>
                                                    <span style="color: #66C480;float: right;line-height: 1.3rem;border-radius: .2rem;border: .05rem solid #66C480;margin: .1rem .2rem;padding: 0 .1rem;font-weight: 200;font-size: .8rem;">
                                                        <?if($timerMsg=='距开始'){?>
                                                            即将开抢
                                                        <?}else{?>
                                                            抢购
                                                        <?}?>
                                                    </span>
                                                </div>
                                            </div>

                                        </div>
                                    </li>
                                <? } ?>
                            </ul>
                        </div>
                    </div>
                <? } if($arrProduct['data']['sellout']) { ?>
                    <div>
                        <div class="commodity_list">
                            <!--  <div class="line"></div> -->
                            <ul>
                                <? foreach ($arrProduct['data']['sellout'] as $key => $lItem) { ?>
                                    <li>
                                        <div onclick="linkUrl('<?= $lItem['link'] ?>')" class="commodity_pic">
                                            <img src="<?= $lItem['image'] ?>" alt=""/>
                                            <img class="imgright" src="<?= $lItem['imageright'] ?>" alt=""style="float: left"/>
                                            <img src="/images/detail/sellout.png" style="width: 5rem;height: 5rem;position: absolute;top: 0;left: 5rem;">
                                        </div>
                                        <div class="commodity flex">

                                            <div class="commodity_detail content_div">
                                                <h2
                                                    onclick="linkUrl('<?= $lItem['link'] ?>')"
                                                    class="multiEllipsis"><?= $lItem['title'] ?></h2>
                                                <h3 onclick="linkUrl('<?= $lItem['link'] ?>')"
                                                    class="multiEllipsis"><?= $lItem['sRecomm'] ?></h3>
                                                <div style="width: 100%;height: 2.2rem;border-bottom: 1px #ccc solid">
                                                    <div style="width: 3.5rem;float: left">
                                                        <span style="color: #66C480;display: block;clear: both;width: 3.5rem;border-bottom: 1px solid #7B7B7B">
                                                        <span style="font-size: .6rem">￥</span>
                                                        <span style="font-size: 1.0rem"><?= $lItem['price'] ?></span>
                                                    </span>
                                                        <span style="color:#7B7B7B;font-size: .45rem;display: block;clear: both;width: 3.5rem">
                                                        日 常 价：<span style="text-decoration: line-through"><?= $lItem['market_price'] ?></span>
                                                    </span>
                                                    </div>
                                                </div>
                                            </div>

                                        </div>
                                    </li>
                                <? } ?>
                            </ul>
                        </div>
                    </div>
                <? }} else { ?>
                    <div>
                        <img src="/images/home/nextgroup.jpg?1">
                    </div>
                <? } ?>

            </div>
        </div>
    </div>
    <div class="mask"></div>
    <div class="redimg"><img src="/images/home/redproduct.png"></div>
    <input type="hidden" id="addressID" value="<?= $bAddressShow ?>">
<?= $this->render('/layouts/foot', ['bIndex' => true]) ?>
    <!-- 填写地址弹框 -->
    <div class="add_mask" style="display: none;height: 3334px;">
        <div class="addr_main">
            <div class="addr_title"> 新增团长信息</div>
            <div class="address_receiver">
                <input class="addr_name" id="name" placeholder="店铺名" type="text">
            </div>
            <div class="address_receiver">
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
    <div class="divtiper" style="display: none">
        <img id="tiperimg"
             src="http://thirdwx.qlogo.cn/mmopen/vi_32/H0YxpJyXxuKLSFTHalnQfUA9K0n5Nap6088x03LgwTT6icH1G7YZcySl2WU2UBKXgs8wwlpEJmyicP1xETyY3DjA/132">
        <span id="tipersp">小丑鱼刚刚下了一单</span>
    </div>
    <style>
        .commodity_price p {
            color: #ABABAB;
        }

        .commodity_price > p:first-child {
            color: #333;
        }

        [data-dpr="1"] .commodity_price p {
            font-size: 14px;
        }

        [data-dpr="2"] .commodity_price p {
            font-size: 26px;
        }

        [data-dpr="3"] .commodity_price p {
            font-size: 37px;
        }

        .commodity_detail{
            width: 100%;
        }
        [data-dpr="1"] .commodity_detail h3 {
            font-size: 17px;
        }

        [data-dpr="2"] .commodity_detail h3 {
            font-size: 27px;
        }

        [data-dpr="3"] .commodity_detail h3 {
            font-size: 37px;
        }

        .buy p {
            display: inline-block;
            text-align: left;
            float: left;
            line-height: 1.5rem
        }

        [data-dpr="1"] .buy p {
            font-size: 20px;
        }

        [data-dpr="2"] .buy p {
            font-size: 30px;
        }

        [data-dpr="3"] .buy p {
            font-size: 40px;
        }

        .commodity_list li {
            padding-top: 1rem;
        }
    </style>
<?php $this->beginBlock('foot') ?>
    <script src="/js/swiper.min.js"></script>
    <script src="/js/mescroll.min.js"></script>
    <script>
        var redbag =<?=$bRedProduct?>;
        if (redbag) {
            $('.mask').show();
            $('.redimg').show();
        }
        $('.mask,.redimg').on('click', function () {
            $('.redimg').hide();
            $('.mask').hide();
        });


        function addcart(lID, bShow) {
            var quantity = parseInt($('#cart' + lID).val());
            quantity = quantity ? quantity : 1;
            var specData = {
                productid: lID,
                quantity: quantity,
                group: 'group',
                _csrf: '<?=\Yii::$app->request->getCsrfToken()?>',
            }
            //加入购物车
            $.post('<?= Url::toRoute([\Yii::$app->request->shopUrl . "/cart/addtocart"], true) ?>', specData,
                function (res) {
                    if (bShow) {
                        shoperm.showTip(res.message);
                        $('#cartno').html(parseInt($('#cartno').html())+quantity);
                    }
                }, 'json')
        }

        function addGroup(lID, obj) {
            if ($(obj).attr('tag') == 1) {
                $(obj).html('&#xe60b;');
                $(obj).attr('tag', '0');
            } else {
                $(obj).html('&#xe62b;');
                $(obj).attr('tag', '1');
            }
        }

        function buyNow() {
            if(<?=$timerMsg=='距开始'?1:0?>){
                shoperm.showTip('团购暂未开始，请稍等待');
                return;
            }
            var ProductIDs = '';
            var quantitys = '';
            $(".btn_input").each(function () {
                var k=parseInt($(this).val());
                if(k>0) {
                    ProductIDs += $(this).attr('pid') + ',';
                    quantitys += k + ',';
                }
            });
            if (ProductIDs == '') {
                shoperm.showTip('请勾选商品立即购买');
                return;
            } else {

                var specData = {
                    productid: ProductIDs,
                    quantity: quantitys,
                    group: 'group',
                    _csrf: '<?=\Yii::$app->request->getCsrfToken()?>',
                }
                //加入购物车
                $.post('<?= Url::toRoute([\Yii::$app->request->shopUrl . "/cart/addgroupcart"], true) ?>', specData,
                    function (res) {
                        location.href = '<?=\yii\helpers\Url::toRoute([\Yii::$app->request->shopUrl . "/cart/checkoutcart"], true)?>';
                    }, 'json')
            }

        }

        function setGroup() {
            var bAddressShow = $('#addressID').val();
            if (bAddressShow == 0) {
                $('.add_mask').show();
                return;
            }
            var csrf = '<?= \Yii::$app->request->getCsrfToken() ?>';
            var sName = '<?=$product->sName?>';
            var ProductIDs = '';
            $(".checkgroup[tag='1']").each(function () {
                addcart($(this).attr('val'), 0);
                ProductIDs += $(this).attr('val') + ',';
            });
            if (ProductIDs == '') {
                shoperm.showTip('请勾选商品开团');
                return false;
            } else {
                $.post('/product/setgroup', {
                    _csrf: csrf,
                    sName: sName,
                    ProductID: ProductIDs
                }, function (data) {
                    var url = '<?=\yii\helpers\Url::toRoute([\Yii::$app->request->shopUrl . "/product/membergroup"], true)?>';
                    location.href = url + '?id=' + data.id;
                });
            }

        }

        function linkUrl(URL) {
            location.href = URL;
        }

        function add(index) {
            $('.btn_input').eq(index).val(parseInt($('.btn_input').eq(index).val()) + 1)
        }

        function reduce(index) {
            var i = parseInt($('.btn_input').eq(index).val());
            i--;
            if (i < 0) {
                i = 0;
            }
            $('.btn_input').eq(index).val(i)
        }
    </script>
    <script>
        var nowTime =  <?=time();?>;
        var endTime =<?= strtotime($timerDate) ?>;
        var timerMsg = '<?= $timerMsg ?>';
        promotionToEnd(nowTime, endTime, timerMsg);

        function promotionToEnd(nowTime, endTime, timerMsg) {
            var t = setInterval(function () {
                var i, a, s = new Date;
                i = new Date(1000 * endTime);
                a = i.getTime() - 1000 * nowTime;
                if (a <= 0) {
                    clearTimeout(t);
                }

                nowTime++;
                var n = a / 86400000, r = Math.floor(n);
                var day = r;
                var o = 24 * (n - r);
                var hours, minutes, seconds;

                if (Math.floor(o) >= 10) {
                    hours = Math.floor(o);
                }
                else {
                    hours = "0" + Math.floor(o);
                }
                var d = 60 * (o - hours);
                if (Math.floor(d) >= 10) {
                    minutes = Math.floor(d);
                } else {
                    minutes = "0" + Math.floor(d);
                }
                var l = 60 * (d - minutes);

                if (day > 0) {
                    hours = Math.floor(hours) + day * 24
                }
                if (Math.floor(l) >= 10) {
                    seconds = Math.floor(l);
                } else {
                    seconds = "0" + Math.floor(l);
                }
                $('#promotionDate').html(timerMsg + '：<span>' + hours + '</span> : <span>' + minutes + '</span> : <span>' + seconds + '</span> ');
            }, 2000);
        }

    </script>
    <script src='/js/jquery.min.js'></script>
    <script src="/js/ydui.citys.js"></script>
    <script src="/js/ydui.js"></script>
    <script>

        $(function () {
            var areaType = '' //地址处理类型
            //设置默认地址
            $('.address_set').on('click', function () {
                var id = $(this).parents('li').data('id');
                $('.address_set_btn').removeClass('default');
                $(this).children('.address_set_btn').addClass('default');
                $('.weui-loading-toast').show();
                $.get('/address/setdef', //post 看效果 实际用get,具体看接口需要特别注意
                    {
                        id: id
                    },
                    function (res) {
                        console.log(res);
                        $('.weui-loading-toast').hide();
                    }, 'json');
            })
            //新建地址
            $('.add_new').on('click', function () {
                areaType = 'new';
                $('.addr_title').html('添加新收货地址');
                //清空所有赋值
                $('#name').val('');
                $('#mobile').val('');
                $('#J_Address').val('');
                $('#address_text').val('');

                $('.add_mask').show();
                $('body,html').css('overflow', 'hidden');
                $('.add_mask').height($(window).height());

                $('body').on('touchmove', function (event) {
                    event.preventDefault()
                })
            })
            $('.addr_close').on('click', function (event) {
                event.stopPropagation();
                $('.add_mask').hide();

                $('body,html').css('overflow', '');
                $("body").unbind("touchmove");
            })
            /*$('.add_mask').on('click',function() {
             $(this).hide();
             })
             $('.addr_main').on('click',function(event) {
             event.stopPropagation();
             })*/

            //编辑地址
            var editId = '';
            $('.address_edit').on('click', function () {
                areaType = 'edit'
                editId = $(this).parents('li').data('id');
                $('.addr_title').html('编辑地址');
                $('.weui-loading-toast').show();
                $.ajax({
                    url: '/address/edit',
                    dataType: 'json',
                    data: {addressid: editId},
                    type: 'get',
                    success: function (data, status, xhr) {
                        console.log(data);

                        $('.weui-loading-toast').hide();

                        if (!data.status) {
                            shoperm.showTip(data.message);
                            return false;
                        }

                        $('#name').val(data.name);
                        $('#mobile').val(data.mobile);
                        $('#J_Address').val(data.area);
                        $('#address_text').val(data.address);
                        $('.add_mask').show();
                        listenerVal();

                    }
                });

            })

            var delId = '';
            //删除地址
            $('.address_del').on('click', function () {
                delId = $(this).parents('li').data('id');
                $('.mask').show();
                shoperm.selection('确定要删除该地址么', delAddress, cancelDel);
            })

            //先确定 再取消
            function delAddress() {
                $('.mask').hide();
                $('.weui-loading-toast').show();
                $.post('/address/del',
                    {
                        id: delId
                    }, function (res) {

                        location.reload();

                    }, 'json')
            }

            function cancelDel() {
                $('.mask').hide();
            }

            //保存地址
            $('.addr_save').on('click', function () {
                var sName = $.trim($('.addr_name').val()),
                    sPhone = $.trim($('.addr_mobile').val()),
                    sArea = $('.c_area').val().split(' ').join(','), //分割成后端的数据格式
                    sPosition = $.trim($('.detail_position').val()),
                    validate = /^1\d{10}$/,
                    csrf = '<?= \Yii::$app->request->getCsrfToken() ?>',
                    areaData = {
                        _csrf: csrf,
                        name: sName,
                        mobile: sPhone,
                        area: sArea,
                        address: sPosition
                    };
                if (sName == '') {
                    shoperm.showTip("店铺名字不能为空");
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

                $('.weui-loading-toast').show();
                //判断新建还是编辑
                $.post('/product/groupaddress', areaData,
                    function (res) {
                        console.log(res);

                        if (res.status) {
                            $('.weui-loading-toast').hide();
                            $('.add_mask').hide();
                            $('#addressID').val(res.addressid);
                            setGroup();
                        } else {
                            shoperm.showTip(res.message);
                        }

                    }, 'json')
            })

            //信息填写完全时显示保存按钮颜色
            $('.addr_name,.addr_mobile,.detail_position').on('input', function () {
                listenerVal();
            });
            $('.addr_name,.addr_mobile,.detail_position').on('click', function () {
                $(this).removeClass('input_error');
            });

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
        //省市联动插件
        var $target = $('#J_Address');
        $target.citySelect();
        $target.on('click', function (event) {
            event.stopPropagation();
            $target.citySelect('open');

        });
        $target.on('done.ydui.cityselect', function (ret) {

            $(this).val(ret.provance + ' ' + ret.city + ' ' + ret.area);
            listenerVal();
        });

        $('body').on('touchmove', '.m-cityselect', function (event) {
            event.stopPropagation(); //解决手机上划不动的问题
        })

    </script>
    <script>
        var buyers =<?=$buyers?>;
        if (buyers && buyers.length > 0) {
            $('.divtiper').show();
            console.log(buyers);
            var i = 0;
            var k = 0;
            var tt = setInterval(function () {
                if (i < 110) {
                    if (i % 2 == 0) {
                        $('.divtiper').hide(300);
                    } else {
                        k++;
                        if (k>buyers.length-1){
                            k=0;
                        }
                        $('#tiperimg').attr('src', buyers[k].logo);
                        $('#tipersp').html(buyers[k].sName + '刚刚又下了一单');
                        $('.divtiper').show(300);
                    }
                    i++;
                } else {
                    $('.divtiper').hide();
                    clearInterval(tt);
                }
            }, 1000);
        }
    </script>
<?php $this->endBlock() ?>