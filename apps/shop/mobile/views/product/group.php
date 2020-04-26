<?php
/**
 * 商品详情页
 * Created by PhpStorm.
 * User: oyyz <oyyz@3elephant.com>
 * Date: 2017/10/7 0007
 * Time: 下午 3:19
 */

use yii\helpers\Url;

?>

<?php $this->beginBlock('style') ?>
<link rel="stylesheet" href="/css/swiper.min.css">
<link rel="stylesheet" href="/css/detail.css?<?= \Yii::$app->request->sRandomKey ?>">
<link rel="stylesheet" href="/css/ydui.css">
<style>

    .buy_now {
        width: 8rem;
        height: 100%;
        line-height: 2.1333333333rem;
        text-align: center;
        color: #fff;
        background: #f42323;
    }

    .service {
        width: 4.6rem;
        height: 100%;
    }

    .detail_wrap .header {
        position: static;
    }

    .detail_wrap .header {
        width: 100%;
        position: relative;
    }

    .header .portrait {
        width: 2.7rem;
        height: 2.7rem;
        margin-top: 0.5rem;
        margin-left: 0.5rem;
        -webkit-border-radius: 50%;
        border-radius: 50%;
        border: 2px solid #eee;
        overflow: hidden;
        background: #fff;
    }

    .header .person_name_wrap {
        margin-top: 0.8rem;
    }

    .person_name_wrap {
        height: 3rem;
        width: 12rem;
    }

    .person_title {
        padding-left: 0.4rem;
        font-size: 0.66rem;
        display: block;
        clear: both;
        height: 1rem;
        width: 100%;
    }

    .div_promotion {
        position: relative;
        bottom: -1px;
        left: 0;
        width: 100%;
        height: 2.6rem;
        z-index: 4;
        background: #e71a27;
        display: inline-block;
        padding-left: .1rem;
    }

    .div_promotionPrice {
        font-size: 1rem;
        color: #fff;
        z-index: 4;
        height: 1.5rem;
    }

    .div_TuanBox {
        padding-left: .1rem;
        color: #fff;
        font-size: 0.6rem;
    }

    .div_promotionDateBox {
        position: absolute;
        right: -1px;
        top: 0;
        width: 5.5rem;
        background: #f8c33e;
        height: 100%;
    }

    .div_promotionPrice > del {
        font-size: 0.5rem;
        color: #eee;
    }

    .div_promotionLogo {
        position: absolute;
        left: 1rem;
        top: 0px;
        color: #fff;
        font-size: 0.9rem;
    }

    .div_promotionLogo span {
        display: inline-block;
        line-height: 1rem;
        color: #91350e;
        font-weight: bold;
        font-size: 0.7rem;
    }

    .icon-arr {
        width: 0px;
        height: 0px;
        border-width: 0.6rem;
        border-style: dashed solid dashed dashed;
        border-color: transparent #f8c33e transparent transparent;
        position: absolute;
        left: -1rem;
        z-index: 99;
        top: 0.8rem;
    }

    .div_promotionLogo img {
        height: 0.6rem;
        width: 0.6rem;
        display: inline-block;
        margin-top: 0.2rem;
        margin-right: 0.1rem;
    }

    .div_promotionDate {
        position: absolute;
        left: 1rem;
        bottom: 0;
        font-size: 0.6rem;
        color: white;
        height: 1rem;
    }

    .div_promotionDate span {
        display: inline-block;
        height: 0.7rem;
        width: 0.7rem;
        border-radius: 3px;
        background-color: #ef9634;
        text-align: center;
        line-height: 0.7rem;
    }

    .describe {
        padding: 0.3rem;
        position: relative;
    }

    .sales_volume {
        margin-top: 0.2rem;
    }

    /* 小弹窗提示购买消息数 2018/12/12 15:41 wqw */
    .bubble-container {
        z-index: 888;
        position: fixed;
        top: 10rem;
        width: 8rem;
        height: 1rem;
        background: #333;
        opacity: 0.8;
        border-radius: .1rem;
        padding: .05rem 0;
        margin-left: .1rem;
        font-size: 0.6rem;
        text-align: center;
        line-height: 1rem;
        overflow: hidden;
    }

    .bubble-container .side-item {
        z-index: 889;
        top: .07rem;
        color: #fff;
        left: .25rem;
        height: .35rem;
        width: auto;
        min-width: 1.5rem;
    }

    .bubble-container .pic {
        width: .9rem;
        height: .9rem;
        display: inline-block;
        border-radius: 50%;
        overflow: hidden;
        vertical-align: middle;
        position: absolute;
        left: .06rem;
        top: .06rem;
    }

    .bubble-container .pic > img {
        width: 100%;
        height: 100%;
    }

    .bubble-container .txt {
        display: inline-block;
        color: #fff;
        padding-left: .5rem;
        padding-right: .1rem;
    }

    /* 小弹窗提示购买消息数 2018/12/12 15:41 end */
</style>
<?php $this->endBlock() ?>

<div class="detail_wrap">

    <!-- 轮播图结构 -->
    <div class="banner_swiper">
        <? if ($product->pics) { ?>
            <div class="swiper-container">
                <div class="swiper-wrapper">
                    <? foreach ($product->pics as $val) { ?>
                        <div class="swiper-slide">
                            <img src="<?= $val ?>" alt="">
                        </div>
                    <? } ?>
                </div>
                <div class="swiper-pagination"></div>
            </div>
        <? } ?>
    </div>
    <div class="div_promotion" id="div_promotion" style="display: block">
        <div class="div_promotionPrice"><span><i>￥</i><?= $fShowSalePrice ? $fShowSalePrice : '' ?></span>
            <del>￥<?= $product['fShowPrice'] ?></del>
        </div>
        <div class="div_TuanBox">
            <span>已拼<?= $TotalNum ?>件</span>
        </div>
        <div class="div_promotionDateBox">
            <div class="div_promotionLogo">
                <img src="/images/home/timer.png" alt=""><span id="sp_promotionTxt">限时拼团</span>
            </div>
            <i class="icon-arr"></i>
            <div id="promotionDate" class="div_promotionDate">
                <span>12</span> : <span>11</span> : <span>24</span></div>
        </div>
    </div>
    <div class="describe">
        <h1 class="multiEllipsis"><?= $product['sName'] ?></h1>
        <p class="multiEllipsis d_describe"><?= $product['sRecomm'] ?></p>
        <div class="sole_price flex" style="display: none;">
            <em class="s_p_price"><?= $fShowSalePrice ? '¥' . $fShowSalePrice : '' ?></em>
        </div>
        <del class="old_price"
             style="display: none;"><?= $product['fShowPrice'] ? '¥' . number_format($product['fShowPrice'], 2) : '' ?></del>
        <div class="sales_volume flex">
            <span class="stock">库存<?= intval($product['lStock']) ?></span>
        </div>
        <div class="share_icon">
            <i class="icon">&#xe66e;</i> <span class="icon_name">分享</span>
        </div>
    </div>
    <div class="line" style="height: 0.2rem"></div>
    <header class="header flex">
        <div class="portrait">
            <img src="<?= $member->sAvatarPath ? $member->sAvatarPath : "/images/order/person.png" ?>">
        </div>
        <div class="person_name_wrap">
            <h3 class="person_title"><?= $shop->sName ?></h3>
            <h3 class="person_title" style="font-size: 0.55rem"><?= $shop->sMsg ?></h3>
        </div>
    </header>
    <? if ($arrParamValue) { ?>
        <!-- 产品参数 -->
        <div class="parameter">
            <div class="para_link">
                商品参数
            </div>
            <div class="para_info">
                <? foreach ($arrParamValue as $sKey => $paramValue) { ?>
                    <? if ($sKey < 2) { ?>
                        <? if (strlen($paramValue->sValue) > 0) { ?>
                            <div class="info_wrap flex">
                                <span class="info_name"><?= $paramValue->param->sName ?></span>
                                <p class="info_detail"><?= $paramValue->sValue ?></p>
                            </div>
                        <? } ?><? } ?>
                <? } ?>
            </div>
        </div>
        <div class="line"></div>
    <? } ?>

    <!--供应商-->
    <div class="supplier" style="display: none;">
        <a href="javascript:;" class="flex">
            <div class="sup_pic">
                <img src="/images/detail/stroe.png" alt="">
            </div>
            <div class="sup_name">
                <span>供应商</span>
                <h2 class="singleEllipsis"><?= $product->supplier->sName ?></h2>
            </div>
        </a>
    </div>
    <div class="line" style="height: 0.2rem"></div>
    <div class="commodity_details">
        <div class="details_title flex">
            <i class="detail_btn active">商品详情</i> <i class="service_btn">售后说明</i>
        </div>
        <!--商品详情-->
        <div class="details_content">
            <?= $product['sContent'] ? $product['sContent'] : "<img src=\"/images/detail/content_empty.png\" alt=\"\">" ?>
        </div>
        <!--售后说明-->
        <div class="details_content">
            <div class="service_con">
                <img src="/images/detail/shouhou_empty.jpg">
            </div>
        </div>
    </div>

    <!--售罄提示栏-->

    <? if ($product->bSaleOut) { ?>
        <div class="sell_out">
            该商品已售罄
        </div>
    <? } elseif ($product->bOffSale) { ?>
        <div class="sell_out">
            该商品已下架
        </div>
    <? } elseif (!$bWholesale) { ?>
        <div class="sell_out">
            您不是渠道人员
        </div>
    <? } ?>

    <!--底部控件-->
    <footer>
        <div class="fix_btn flex">
            <div class="service">
                <a href="<?= \yii\helpers\Url::toRoute([\Yii::$app->request->shopUrl . "/home"], true) ?>"
                   class="flex on" class="flex">
                    <img src="/images/foot/shop0.svg" alt=""> <span>首页</span> </a>
            </div>
            <div class="service">
                <a href="tel:<?= $shop ? $shop->sMobile : \myerm\shop\common\models\MallConfig::getValueByKey("sServiceNum") ?>"
                   class="flex">
                    <img src="/images/detail/kefu.png" alt=""> <span>客服</span> </a>
            </div>
            <div class="buy_now <? if (!$product->bOnSale || !$bWholesale) { ?>buy_off<? } ?>">
                立即购买
            </div>
        </div>
    </footer>
</div>
<!-- 规格 -->
<section>
    <div class="chose_item">
        <div class="sample flex">
            <div class="s_img" id="s_img">
                <img src="<?= \Yii::$app->request->imgUrl . '/' . $product['sMasterPic'] ?>" alt="">
            </div>
            <div class="s_cont">
                <h2 class="multiEllipsis"><?= $product['sName'] ?></h2>
                <em>¥<i id="price" style="font-weight:700"><?= $fShowSalePrice ?></i></em>
            </div>
            <div class="close_btn"></div>
        </div>
        <ul class="spec">
            <? foreach ($arrSpec['arrSpec'] as $key => $value) { ?>
                <li>
                    <h3 class="spec_title"><?= $key ?></h3>
                    <div class="spec_list flex">
                        <? foreach ($value as $k => $v) { ?>
                            <span attr_id='<?= $v['id'] ?>'><?= $v['value'] ?></span>
                        <? } ?>
                    </div>
                </li>
            <? } ?>
            <div class="number flex">
                <div class="number_wrap">
                    <span class="amount">数量</span> <span class="stock">库存<i
                                id="stock"><?= $product['lStock'] ?></i></span>
                </div>
                <div class="number_btn flex">
                    <span id="reduce" class="disable">-</span> <span class="chose_num" id="num">1</span>
                    <span id="add">+</span>
                </div>
            </div>
        </ul>
        <div class="sure_btn">确定</div>
    </div>
</section>
<!-- 更多栏目 -->
<div class="nav_more_list">
    <div class="triangle-up"></div>
    <ul>
        <li class="flex" onclick="location.href='<?= \Yii::$app->request->mallHomeUrl ?>'">
            <span class="icon">&#xe608;</span> <em>首页</em>
        </li>
        <li class="flex"
            onclick="location.href='<?= \yii\helpers\Url::toRoute(["/member"],
                true) ?>'">
            <span class="icon">&#xe64a;</span> <em>我的</em>
        </li>
    </ul>
</div>
<!--商品大图-->
<div class="swiper_mask">
    <div class="imgShow">
        <? if ($product->pics) { ?>
            <div class="swiper-container1">
                <div class="swiper-wrapper">
                    <? foreach ($product->pics as $val) { ?>
                        <div class="swiper-slide">
                            <img src="<?= $val ?>" alt="">
                        </div>
                    <? } ?>
                </div>
            </div>
        <? } ?>
    </div>
</div>
<!-- 分享提示遮罩 -->
<div class="share_prompt">
    <div class="share_pic"></div>
    <div class="share_main">
        <p class="shre_word">请点击右上角【···】通过【发送给朋友】，</p>
        <p class="shre_word">邀请好友购买</p>
    </div>
</div>
<? if ($arrParamValue) { ?>
    <!-- 商品参数 -->
    <div class="parameter_wrap">
        <div class="product_parameter">
            <h2 class="parameter_title">商品参数</h2>
            <ul class="parameter_info">
                <? foreach ($arrParamValue as $sKey => $paramValue) { ?>
                    <? if (strlen($paramValue->sValue) > 0) { ?>
                        <li class="flex">
                            <span class="p_i_name"><?= $paramValue->param->sName ?></span>
                            <p class="p_i_detail"><?= $paramValue->sValue ?></p>
                        </li>
                    <? }
                } ?>
            </ul>
            <div class="para_close">关闭</div>
        </div>
    </div>
<? } ?>
<!-- 二维码 -->
<div class="ewm_wrap" id="ewmWrap">
    <div class="ewm_header flex">
        <div class="header_pic"
             style="background-image: url(<?= $logo ?>)">
            <!--  <img src=""> -->
        </div>
        <p><?= \Yii::$app->request->sMallName ?></p>
    </div>
    <div class="ewm_goods">
        <img src="<?= \Yii::$app->request->imgUrl . '/' . $product->sMasterPic ?>">
    </div>
    <div class="ewm_footer flex">
        <div class="ewm_pic" id="qrcode"></div>
        <div class="goods_info">
            <h2 class="goods_title"><?= $product->sName ?></h2>
            <div class="goods_price flex">
                <span class="now_price">¥<?= number_format($product->fPrice, 2) ?></span>
                <? if ($product->fShowPrice) { ?>
                    <span class="del_price">¥<?= number_format($product->fShowPrice, 2) ?></span>
                <? } ?>
            </div>
        </div>
    </div>
    <div class="ewm_tip">扫码自助购买，或分享给朋友</div>
    <!-- <div class="ewm_close"></div> -->
</div>
<div class="ewm_canvas"></div>

<!-- 回到顶部 -->
<div class="backTop"></div>
<div class="mask"></div> <!-- 一阶遮罩 -->
<div class="g_mask"></div> <!-- 二阶遮罩 -->
<div class="bubble-container" id="bubble" style="display: none">
    <div class="side-item" id="bubbleItem">
        <div class="pic">
            <img id="bubbleImg" src="http://wx.qlogo.cn/mmopen/O5IB5rptd1oWJzM8Hfu9PqKkZnkq1XWiaMkGTmonQydqEryU2MHZicZbaIJKic52hO0tbmOwJ2oEAic1YfGzeLDzrUwliakqFicc8j/0">
        </div>
        <div class="txt" id="bubbleTxt">(***正在团此商品</div>
    </div>
</div>
<?php $this->beginBlock('foot') ?>

<script src='/js/jquery.min.js'></script>
<script src="/js/qrcode.min.js"></script>
<script src="/js/swiper.min.js"></script>
<script src="/js/ydui.citys.js"></script>
<script src="/js/ydui.js"></script>
<!-- 1.0.0-alpha.8 -->
<script src="/js/html2canvas.js"></script>
<script src="/js/canvas2image.js"></script>

<script>
    var reScroll = 0, //用于记录scroll的值
        isBtn = '';//用于区分加入购物车 还是购买
    //商品ID
    var pId = '<?=$product["lID"]?>';
    //解决返回不刷新的问题 安卓似乎无效
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
        if (!isIOS()) {
            var load = sessionStorage.getItem('detailReload'); //这是是兼容安卓的返回问题
            if (load) {
                location.reload();
                sessionStorage.removeItem('detailReload');
            }
        }

        //轮播图
        var mySwiper = new Swiper('.swiper-container', {
            autoplay: 3000,//可选选项，自动滑动
            pagination: '.swiper-pagination',
        })
        //点击查看大图处理
        var mySwiper1 = new Swiper('.swiper-container1', {
            //autoplay: 2000,//可选选项，自动滑动
        })

        mySwiper.params.control = mySwiper1;//需要在Swiper2初始化后，Swiper1控制Swiper2
        mySwiper1.params.control = mySwiper;//需要在Swiper1初始化后，Swiper2控制Swiper1

        $('.swiper_mask').on('click', function () {
            $(this).hide();
            $(this).css('zIndex', '-100')
            mySwiper.startAutoplay();
        });
        $('.swiper-container').on('click', function () {
            $('.swiper_mask').css('zIndex', '200');
            $('.swiper_mask').show();
            mySwiper.stopAutoplay();
        });
        $('.swiper_mask').hide();

        //滚动显示导航栏
        $(window).on('scroll', function () {

            top = $(this).scrollTop();

            if (top > 600) {
                $('.fix_title').show();
            } else {
                $('.fix_title').hide();
            }
            $('.fix_one ').addClass('on').siblings('span').removeClass('on');
            $('.nav_more_list').hide();

        });
        //导航点击事件
        $('.fix_one ').on('click', function () {
            $(window).scrollTop(0);
        })
        $('.label_btn').on('click', function () {
            var com_top = $('.commodity_details').offset().top - 100;
            $(window).scrollTop(com_top);
        })

        //安卓个别机子二维码显示问题（偏大）2019/3/11 wqw
        var qrcodeWidth = $(".ewm_wrap .ewm_pic").width();
        //生成二维码
        var qrcode = new QRCode(document.getElementById("qrcode"), {
            text: location.href,
            width: qrcodeWidth,
            height: qrcodeWidth,
            // correctLevel:QRCode.CorrectLevel.L

        });
        $(".ewm_pic>img").css({
            "width": "100%",
            "height": "100%"
        })

        //查看二维码
        $('.ewm,.share_icon').on('click', function () {
            /* var src = $('.ewm_pic img').attr('x-src');
             $('.ewm_pic img').attr('src', src);*/

            $('.weui-loading-toast').show();//加载图
            $('.ewm_wrap').show();

            //生成图片
            var width = $(".ewm_wrap").innerWidth(); //获取二维码dom的 宽高
            var height = $(".ewm_wrap").innerHeight();
            var canvas = document.createElement("canvas"); //新建画布
            //要将 canvas 的宽高设置成容器宽高的 2 倍，处理手机上模糊问题
            canvas.width = width * 2;
            canvas.height = height * 2;
            canvas.getContext("2d").scale(2, 2); //初始化2倍
            var opts = {
                scale: 2,
                canvas: canvas,
                width: width,
                height: height,
                useCORS: true //允许图片跨域 需要后端配合
            };

            html2canvas(document.getElementById('ewmWrap'), opts)
                .then(
                    function (canvas) {
                        //画图转图片的插件 Canvas2Image，转为base64
                        var img = Canvas2Image.convertToImage(canvas, canvas.width, canvas.height);
                        $('.ewm_canvas').append(img);
                        $(".ewm_canvas").find("img").css({
                            "width": canvas.width / 2 + "px",
                            "height": canvas.height / 2 + "px",
                        })
                        $('.weui-loading-toast').hide();
                    });

            $('.mask').show();

        })
        $('.mask,.ewm_close').on('click', function () {

            $('.ewm_canvas').find('img').remove();
            $('.ewm_wrap').hide();
            $('.mask').hide();
        })

        //底部商品详情切换
        $('.details_title i').on('click', function () {
            var index = $(this).index();
            $(this).addClass('active');
            $(this).siblings('i').removeClass('active');
            $('.details_content').eq(index).show();
            $('.details_content').eq(index).siblings('.details_content').hide();
        })


        $('.sure_btn').on('click', function () { //规格确定按钮
            //获取规格数据
            var specResult = getSpecResult(),
                number = $('.chose_num').text();
            if (!specResult) {
                return;
            }
            var specData = {
                productid: pId,
                quantity: number,
                spec: specResult,
                GroupID: "<?=$_GET['id']?>",
                _csrf: '<?=\Yii::$app->request->getCsrfToken()?>',
                wholesaleid: '<?= $bWholesale ? $wholesale->lID : ""?>'
            }
            console.log(specData);
            $('.weui-loading-toast').show();
            //加入购物车
            if (isBtn == 'car') {
                $.post('<?= Url::toRoute(["/cart/addtocart"], true) ?>', specData,
                    function (res) {
                        $('.weui-loading-toast').hide();

                        if (res.status) {

                            shoperm.showTip(res.message);
                            console.log(parseInt(res.cartproductnum));
                            if (parseInt(res.cartproductnum) >= 100) {
                                $('.add_num i').text('•••');
                            } else {
                                $('.add_num i').text(res.cartproductnum);
                            }
                            $('.add_num').show();

                        } else {
                            shoperm.showTip(res.message);
                        }
                    }, 'json')
            }
            //立即购买
            if (isBtn == 'buy') {
                $.post('<?= Url::toRoute(["/cart/buy"], true) ?>', specData,
                    function (res) {
                        $('.weui-loading-toast').hide();
                        if (res.status) {
                            location.href = "<?= Url::toRoute(["/cart/checkout"], true) ?>"
                        } else {
                            shoperm.showTip(res.message);
                        }
                    }, 'json')
            }

            $('section').hide();
            $('.g_mask').hide();
            $('html,body').removeClass('forbidScroll');
            $(window).scrollTop(reScroll);
        })
        //点击关闭规格
        $('.close_btn,.g_mask').on('click', function () {
            $('section').hide();
            $('.g_mask').hide();
            $('html,body').removeClass('forbidScroll');
            $(window).scrollTop(reScroll);
        })
        //更多栏目
        $(".do_more,.fix_more").click(function (event) {
            event.stopPropagation();
            $(".nav_more_list").toggle();

        });
        $("body").click(function (event) {

            $(".nav_more_list").hide();
        });

        $('.service').on('touchstart', function () {
            $(".nav_more_list").hide();
            console.log(2);
        })

        //加入购物车
        $('.add_car').on('click', function () {
            isBtn = 'car';
            var isOff = $(this).hasClass('add_off');
            if (isOff) {
                return;
            }

            doInteractive();
        });
        //立即购买
        $('.buy_now').on('click', function () {
            isBtn = 'buy';
            var isOff = $(this).hasClass('buy_off');
            if (isOff) {
                return;
            }
            doInteractive();
        });

        //设置缓存
        $('.car').on('click', function () {
            sessionStorage.setItem('detailReload', 'true');
        })
        //分享
        // $('.share_icon').on('click', function () {
        //     $('.share_prompt').show();
        // })
        // $('.share_prompt').on('click', function () {
        //     $(this).hide();
        // })
        //查看参数
        $('.para_link').on('click', function () {
            $('.parameter_wrap').show();
        })
        $('.para_close,.parameter_wrap').on('click', function () {
            $('.parameter_wrap').hide();
        })
        $('.product_parameter').on('click', function (event) {
            event.stopPropagation();
        })

        //加载二维码
        var src = $('.ewm_pic img').attr('x-src');
        $('.ewm_pic img').attr('src', src);
    })

    //加入购物车和立即购买的调用函数
    function doInteractive() {
        var areaValue = $('.c_area').val() || ' ',
            //value = $('.c_area').val().split(' '),
            postData = {
                productid: pId,
                //province: value[0],
                //city: value[1],
                //wholesaleid: '<?= $bWholesale ? $wholesale->lID : ""?>'
            };

        $('.weui-loading-toast').show();

        $.get('<?= Url::toRoute([\Yii::$app->request->shopUrl . "/product/canbuy"], true) ?>', postData,
            function (res) {
                $('.weui-loading-toast').hide();
                if (res.status) {
                    reScroll = $(window).scrollTop();
                    $('section').show();
                    $('.g_mask').show();
                    $('html,body').addClass('forbidScroll');
                } else {
                    shoperm.showTip(res.message);
                }
            }, 'json')
    }
</script>
<script>
    var nowTime =  <?=time();?>;
    var endTime =<?= strtotime($group->dEnd) ?>;
    promotionToEnd(nowTime, endTime);

    function promotionToEnd(nowTime, endTime) {
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
            if (Math.floor(l) >= 10) {
                seconds = Math.floor(l);
            } else {
                seconds = "0" + Math.floor(l);
            }
            $('#promotionDate').html('<span>' + hours + '</span> : <span>' + minutes + '</span> : <span>' + seconds + '</span> ');
        }, 1000);
    }

    var groupMember =<?=$groupMember?>;
    //向上滑动消失
    if(groupMember.length>0) {
        var len=groupMember.length;
        var j=0;
        var i = 80;
        $('#bubble').show();
        $('#bubbleImg').attr('src',groupMember[j].sAvatar);
        $('#bubbleTxt').html(groupMember[j].sName+'已团'+groupMember[j].lNum+'件');
        var t = setInterval(function () {
            i--;
            var k = i / 10;
            if (i > 50) {
                $('#bubble').css('top', k + 'rem');
            } else if (i < 40) {
                j++;
                i = 80;
                if(j>=len){
                    j=0;
                }
                $('#bubbleImg').attr('src',groupMember[j].sAvatar);
                $('#bubbleTxt').html(groupMember[j].sName+'已团'+groupMember[j].lNum+'件');
                $('#bubble').css('top', '8rem');
                $('#bubble').show();
                //换新的标签内容
            } else {
                $('#bubble').fadeOut(200);
            }
        }, 100);
    }
</script>
<script src="https://res.wx.qq.com/open/js/jweixin-1.0.0.js" type="text/javascript" charset="utf-8"></script>
<script type="text/javascript">
    var totalPrice = <?=empty($product['fPrice']) ? 0 : $product['fPrice']?>;
    var totalStock = <?=empty($product['lStock']) ? 0 : $product['lStock']?>;
    var defaultImg = "<?=\Yii::$app->request->imgUrl . '/' . $product['sMasterPic']?>";
    //销售属性集
    var keys = <?=empty($arrSpec['arrSpecID']) ? '[]' : $arrSpec['arrSpecID']?>;
    var keysLen = keys.length;
    //后台读取结果集
    var data = <?=empty($arrSpec['sJsonGroup']) ? '[]' : $arrSpec['sJsonGroup']?>;

    //保存最后的组合结果信息
    var SKUResult = {};

    //获得data对象的key
    function getObjKeys(obj) {
        if (obj !== Object(obj)) throw new TypeError('Invalid object');
        var keys = [];
        for (var key in obj)
            if (Object.prototype.hasOwnProperty.call(obj, key))
                keys[keys.length] = key;
        return keys;
    }

    //把组合的key放入结果集SKUResult
    function add2SKUResult(combArrItem, sku) {
        var key = combArrItem.join(";");
        if (SKUResult[key]) { //SKU信息key属性·
            SKUResult[key].count += sku.count;
            SKUResult[key].prices.push(sku.price);
            SKUResult[key].image = sku.image;
        } else {
            SKUResult[key] = {
                count: sku.count,
                prices: [sku.price],
                image: sku.image
            };
        }
    }

    //初始化得到结果集
    function initSKU() {
        var i, j, skuKeys = getObjKeys(data);
        for (i = 0; i < skuKeys.length; i++) {
            var skuKey = skuKeys[i]; //一条SKU信息key
            var sku = data[skuKey]; //一条SKU信息value
            var skuKeyAttrs = skuKey.split(";"); //SKU信息key属性值数组
            skuKeyAttrs.sort(function (value1, value2) {
                return parseInt(value1) - parseInt(value2);
            });

            //对每个SKU信息key属性值进行拆分组合
            var combArr = combInArray(skuKeyAttrs);
            for (j = 0; j < combArr.length; j++) {
                add2SKUResult(combArr[j], sku);
            }

            //结果集接放入SKUResult
            SKUResult[skuKeyAttrs.join(";")] = {
                count: sku.count,
                prices: [sku.price],
                image: sku.image
            }
        }
        // console.log(SKUResult);
    }

    /**
     * 从数组中生成指定长度的组合
     * 方法: 先生成[0,1...]形式的数组, 然后根据0,1从原数组取元素，得到组合数组
     */
    function combInArray(aData) {
        if (!aData || !aData.length) {
            return [];
        }

        var len = aData.length;
        var aResult = [];

        for (var n = 1; n < len; n++) {
            var aaFlags = getCombFlags(len, n);
            while (aaFlags.length) {
                var aFlag = aaFlags.shift();
                var aComb = [];
                for (var i = 0; i < len; i++) {
                    aFlag[i] && aComb.push(aData[i]);
                }
                aResult.push(aComb);
            }
        }
        return aResult;
    }


    /**
     * 得到从 m 元素中取 n 元素的所有组合
     * 结果为[0,1...]形式的数组, 1表示选中，0表示不选
     */
    function getCombFlags(m, n) {
        if (!n || n < 1) {
            return [];
        }

        var aResult = [];
        var aFlag = [];
        var bNext = true;
        var i, j, iCnt1;

        for (i = 0; i < m; i++) {
            aFlag[i] = i < n ? 1 : 0;
        }

        aResult.push(aFlag.concat());

        while (bNext) {
            iCnt1 = 0;
            for (i = 0; i < m - 1; i++) {
                if (aFlag[i] == 1 && aFlag[i + 1] == 0) {
                    for (j = 0; j < i; j++) {
                        aFlag[j] = j < iCnt1 ? 1 : 0;
                    }
                    aFlag[i] = 0;
                    aFlag[i + 1] = 1;
                    var aTmp = aFlag.concat();
                    aResult.push(aTmp);
                    if (aTmp.slice(-n).join("").indexOf('0') == -1) {
                        bNext = false;
                    }
                    break;
                }
                aFlag[i] == 1 && iCnt1++;
            }
        }
        return aResult;
    }

    //价格、库存、图片变动提示
    //价格和库存都是要在规格全部选完后，才显示规格的价格、库存。否则都默认为商品所设置的售价及总库存
    function changeProps(selectedIds) {
        //10;24;31;40
        var img = SKUResult && SKUResult[selectedIds.join(';')] && SKUResult[selectedIds.join(';')].image;
        if (img) {
            $('#s_img img').attr('src', img);
        } else {
            $('#s_img img').attr('src', defaultImg);
        }
        if (keysLen == selectedIds.length) {
            var prices = SKUResult[selectedIds.join(';')].prices;
            var stock = SKUResult[selectedIds.join(';')].count;
            $('#price').text(prices);
            $('#stock').text(stock);
            $('#tip').text('');
        } else {
            $('#price').text(totalPrice.toFixed(2));
            $('#stock').text(totalStock);
            $('#tip').text('请选择规格');
        }
    }

    //获取规格选择结果
    function getSpecResult() {
        var specRes = {},
            num = $('#num').text(),
            $selectedSpec = $('.spec .on');
        if ($selectedSpec.length !== keysLen) {
            shoperm.showTip('请选择商品规格');
            return false;
        }
        $('.spec li').each(function (list) {
            var key = $(this).find('.spec_title').text();
            specRes[key] = $(this).find('.on').text();
        })
        // $selectedSpec.each(function () {
        //     specRes.push($(this).text());
        // })
        return specRes;
    }

    //初始化用户选择事件
    $(function () {
        initSKU();
        $('.spec_list span').each(function () {
            var self = $(this);
            var attr_id = self.attr('attr_id');
            if (!SKUResult[attr_id]) {
                self.addClass('off');
            }
            //当某组规格只有1类时，进入规格页面时，默认选中该规格
            if (!self.hasClass('off') && self.siblings().length == 0) {
                var selectedObjs = $('.spec .on');
                var selectedIds = getSelectedIds(selectedObjs);
                changeProps(selectedIds);
                self.addClass('on');
            }
        }).click(function () {
            if ($(this).hasClass('off')) return;
            var self = $(this);
            //选中自己，兄弟节点取消选中
            self.toggleClass('on').siblings().removeClass('on');
            //已经选择的节点
            var selectedObjs = $('.spec .on');
            if (selectedObjs.length) {
                //获得组合key价格
                var selectedIds = getSelectedIds(selectedObjs);
                var len = selectedIds.length;
                changeProps(selectedIds);
                //用已选中的节点验证待测试节点 underTestObjs
                $(".spec_list span").not(selectedObjs).not(self).each(function () {
                    var siblingsSelectedObj = $(this).siblings('.on');
                    var testAttrIds = []; //从选中节点中去掉选中的兄弟节点
                    if (siblingsSelectedObj.length) {
                        var siblingsSelectedObjId = siblingsSelectedObj.attr('attr_id');
                        for (var i = 0; i < len; i++) {
                            (selectedIds[i] != siblingsSelectedObjId) && testAttrIds.push(selectedIds[i]);
                        }
                    } else {
                        testAttrIds = selectedIds.concat();
                    }
                    testAttrIds = testAttrIds.concat($(this).attr('attr_id'));
                    testAttrIds.sort(function (value1, value2) {
                        return parseInt(value1) - parseInt(value2);
                    });
                    if (!SKUResult[testAttrIds.join(';')]) {
                        $(this).addClass('off').removeClass('on');
                    } else {
                        $(this).removeClass('off');
                    }
                });
            } else {
                //设置默认价格
                $('#price').text(totalPrice.toFixed(2));
                $('#stock').text(totalStock);
                $('#tip').text('请选择规格');
                //设置属性状态
                $('.spec_list span').each(function () {
                    SKUResult[$(this).attr('attr_id')] ? $(this).removeClass('off') : $(this).addClass('off').removeClass('on');
                })
            }
        });

        //获取选中的Id
        function getSelectedIds(selectedObjs) {
            var selectedIds = [];
            selectedObjs.each(function () {
                selectedIds.push($(this).attr('attr_id'));
            });
            selectedIds.sort(function (value1, value2) {
                return parseInt(value1) - parseInt(value2);
            });
            return selectedIds;
        }

        //加减数量
        $('#add').on('click', function () {
            var maxNum = parseInt($('#stock').text());
            var num = +($('#num').text());
            if (num >= maxNum) {
                shoperm.showTip('已选数量超过库存');
                $(this).addClass('disable');
                return;
            } else {
                $('#reduce').removeClass('disable');
                $('#num').text(++num);
            }
        })

        $('#reduce').on('click', function () {
            var num = +($('#num').text());
            if (num <= 1) {
                $(this).addClass('disable');
                return;
            } else {
                $('#num').text(--num);
                $(this).removeClass('disable');
            }

        })
    });

</script>
<? if (\Yii::$app->params['isWeChat'] && Yii::$app->request->userIP != '127.0.0.1') { ?>
    <script src="https://res.wx.qq.com/open/js/jweixin-1.0.0.js" type="text/javascript" charset="utf-8"></script>
    <script type="text/javascript" charset="utf-8">
        //微信分享配置信息
        wx.config(<?php echo \Yii::$app->wechat->js->config(array(
            'onMenuShareTimeline',
            'onMenuShareAppMessage',
            'onMenuShareQQ',
            'onMenuShareWeibo',
            'onMenuShareQZone'
        ), false) ?>);
        //微信分享
        sharePage('<?= $product['sName'] ?>', '<?=$product->masterPic?>', '<?=$product['sRecomm']?>', location.href);
    </script>
<? } ?>
<?php $this->endBlock() ?>
