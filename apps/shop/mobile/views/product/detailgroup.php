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

    .old_price {
        color: red;
        font-weight: bold;
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

    .r_mask {
        width: 100%;
        height: 100%;
        position: fixed;
        left: 0;
        top: 0;
        z-index: 100;
        background: rgba(0, 0, 0, 0.5);
        display: none;
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
            <em class="s_p_price">促销价：¥<?= number_format($product->fPrice, 2) ?></em>
        </div>
        <? if ($product->fPrice != $fShowSalePrice) { ?>
            <div class="old_price"><?= '赚：¥' . number_format($product->fPrice - $fShowSalePrice, 2) ?></div>
        <? } ?>
        <del class="old_price"
             style="display: none;"><?= $product['fShowPrice'] ? '¥' . number_format($product['fShowPrice'], 2) : '' ?></del>
        <div class="sales_volume flex">
            <span class="stock">库存<?= intval($product['lStock']) ?></span>
        </div>
        <div class="share_icon" style="display: none">
            <i class="icon">&#xe66e;</i> <span class="icon_name">分享</span>
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
            <i class="detail_btn active">商品详情</i> <i class="service_btn">售后说明</i>
        </div>
        <!--商品详情-->
        <div class="details_content">
            <? if ($product['SupplierID'] == 767) { ?>
                <img src="/images/home/product.jpg">
            <? } ?>
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
                <a href="<?= \yii\helpers\Url::toRoute([\Yii::$app->request->shopUrl . "/product/membergroup"], true).'?id='.$groupID ?>"
                   class="flex on" class="flex">
                    <img src="/images/foot/shop0.svg" alt=""> <span>拼团</span> </a>
            </div>
            <div id="buy_now" style="width: 80%"
                 class="buy_now <? if (!$product->bOnSale || !$bWholesale) { ?>buy_off<? } ?>">
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
                <em>¥<i id="price" style="font-weight:700"><?= $product->fPrice ?></i></em>
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
             style="background-image: url(/images/green.png)">
            <!--  <img src=""> -->
        </div>
        <p>来瓜分</p>
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
<div class="r_mask"></div>
<div class="redimg"><img src="/images/home/redimg.png"></div>
<div class="divtiper" style="display: none">
    <img id="tiperimg" src="http://thirdwx.qlogo.cn/mmopen/vi_32/H0YxpJyXxuKLSFTHalnQfUA9K0n5Nap6088x03LgwTT6icH1G7YZcySl2WU2UBKXgs8wwlpEJmyicP1xETyY3DjA/132">
    <span id="tipersp">小丑鱼刚刚下了一单</span>
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
    var groupID = '<?=$groupID?>';
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
            var number = $('.chose_num').text();

            $.post('<?= \yii\helpers\Url::toRoute(["/cart/addgroupcheckout"], true) ?>',
                {
                    groupID: groupID,
                    productIDs: [pId],
                    productNum: [number],
                    _csrf: '<?=\Yii::$app->request->getCsrfToken()?>'
                }, function (res) {
                    console.log(res);
                    if (res.status) {
                        location.href = '<?= \yii\helpers\Url::toRoute(["/cart/checkoutgroup"], true) ?>';
                    } else {
                        shoperm.showTip(res.message);
                    }
                }, 'json');
            return;

        })

        /////////////
        var redbag =<?=\Yii::$app->frontsession->member->bReceiveRedbag?>;
        if (redbag != 1&&0) {
            $('.r_mask').show();
            $('.redimg').show();
        }
        $('.r_mask,.redimg').on('click', function () {
            $('.redimg').hide();
            $('.r_mask').hide();
            $.get('<?= Url::toRoute([\Yii::$app->request->shopUrl . "/home/changeredstate"], true) ?>', {},
                function (res) {
                    location.href = '<?= Url::toRoute([\Yii::$app->request->shopUrl . "/home/redbag"], true) ?>';
                }, 'json');
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
        $('#buy_now').on('click', function () {
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
<script>
    var buyers =<?=$buyers?>;
    console.log(buyers.length);
    if (buyers&&buyers.length>0) {
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
                    $('#tiperimg').attr('src',buyers[k].logo);
                    $('#tipersp').html(buyers[k].sName+'刚刚又下了一单');
                    $('.divtiper').show(300);
                }
                i++;
            }else {
                $('.divtiper').hide();
                clearInterval(tt);
            }
        }, 1000);
    }
</script>
<?php $this->endBlock() ?>
