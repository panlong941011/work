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


    .service {
        width: 4.6rem;
        height: 100%;
    }
</style>
<?php $this->endBlock() ?>

<div class="detail_wrap">
    <header class="top_btn">
        <a href="javascript:;" onclick="goBack()" class="go_back"> <span class="icon">&#xe885;</span> </a>
    </header>
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
    <div class="describe">
        <h1 class="multiEllipsis"><?= $product['sName'] ?></h1>
        <p class="multiEllipsis d_describe"><?= $product['sRecomm'] ?></p>
        <div class="sole_price flex">
            <em class="s_p_price"><?= $fShowSalePrice ? '¥' . $fShowSalePrice : '' ?></em>
        </div>
        <del class="old_price"
             style="display: none;"><?= $product['fShowPrice'] ? '¥' . number_format($product['fShowPrice'], 2) : '' ?></del>
        <div class="sales_volume flex">
            <span class="stock">库存<?= intval($product['lStock']) ?></span>
        </div>

    </div>
    <div class="line"></div>

    <div class="line"></div>
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
    <div class="line"></div>
    <div class="commodity_details">
        <div class="details_title flex">
            <i class="detail_btn active">商品详情</i>
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


<!-- 回到顶部 -->
<div class="backTop"></div>
<div class="mask"></div> <!-- 一阶遮罩 -->
<div class="g_mask"></div> <!-- 二阶遮罩 -->
<footer>
    <div class="fix_btn flex">
        <div class="service">
            <a href="<?=\yii\helpers\Url::toRoute([\Yii::$app->request->shopUrl . "/home"], true)?>"  class="flex on" class="flex">
                <img src="/images/foot/shop0.svg" alt=""> <span>首页</span> </a>
        </div>
        <div class="service">
            <a href="tel:<?= $shop ? $shop->sMobile : \myerm\shop\common\models\MallConfig::getValueByKey("sServiceNum") ?>" class="flex">
                <img src="/images/detail/kefu.png" alt=""> <span>客服</span> </a>
        </div>
    </div>
</footer>
<?php $this->beginBlock('foot') ?>

<script src='/js/jquery.min.js'></script>
<script src="/js/swiper.min.js"></script>
<script src="/js/ydui.citys.js"></script>
<script src="/js/ydui.js"></script>


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


        $(".ewm_pic>img").css({
            "width": "100%",
            "height": "100%"
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
                            <?if(strpos($_SERVER['REQUEST_URI'], 'shop01')){?>
                            location.href = "<?= Url::toRoute(["shop01/cart/checkout"], true) ?>"
                            <?}else{?>
                            //location.href = "<?//= Url::toRoute(["shop" . \Yii::$app->frontsession->urlSeller->MemberID ."/cart/checkout"], true) ?>//"
                            location.href = "<?= Url::toRoute(["/cart/checkout"], true) ?>"
                            <?}?>

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


    })

</script>

<?php $this->endBlock() ?>
