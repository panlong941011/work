<?php

use yii\helpers\Url;
use myerm\common\components\Func;

?>
<?php $this->beginBlock('style') ?>
    <link rel="stylesheet" href="/css/swiper.min.css">
    <link rel="stylesheet" href="/css/mescroll.min.css">
    <link rel="stylesheet" href="/css/home.css">
    <style>
        .lsj_tab_list li {
            list-style-type: none;
        }

        .lsj_tab_list li span {
            font-size: 0.7rem;
        }

        .lsj_tab_list li.cur span {
            color: red;
            border-bottom: 2px solid red;
            height: 100%;
            padding: 0 .2rem;
            display: inline-block;
        }

        .lsj_tab_list li.cur span a {
            color: red;
        }

        .lsj_tab_list li {
            height: 1.3rem;
            line-height: .5rem;
            color: #fff;
            width: 5rem;
            text-align: center;
            margin: .05rem 0;
            color: #333;
        }

        #search {
            position: absolute;
            top: 0.2rem;
            z-index: 1000;
            width: 70%;
            margin-left: 15%;
            border: 2px #333 solid;
            height: 1.3rem;
            background: #fff;
        }

        .top_search {
            opacity: 1;
        }

        #keyWord {
            height: 100%;
        }

        .commodity_list {
            padding: 0 0.4266666667rem 0.4266666667rem;
        }

        .commodity_list ul {
            flex-wrap: wrap;
            display: flex;
            -webkit-justify-content: space-between;
            justify-content: space-between;
            background-color: #ffffff;
            width: 98%;
            margin-left: 1%;
        }

        .commodity_list li {
            width: 50%;
            padding: 0;
            padding-bottom: .2rem;
            border-bottom: 1px solid #ddd;
            padding: 0.3rem;
        }

        .commodity_list li:nth-child(odd) {
            border-right: 1px solid #ddd;
        }

        .commodity_list li:nth-child(1) {
            border-top: 1px solid #ddd;
        }

        .commodity_list li:nth-child(2) {
            border-top: 1px solid #ddd;
        }

        .commodity_pic {
            width: 100%;
            height: 7.36rem;
            position: relative;
        }

        .productli {
            display: block;
        }

        .commodity_detail {
            /*padding: 0.256rem 0.3413333333rem;*/
            background: #fff;
            width: 100%;
            border: none;
        }

        .descript {
            padding: 0.15rem 0.4266666667rem 0.4266666667rem;
            font-size: 0.5rem;
            color: rgba(80, 80, 80, 1);
            margin-top: 10px;
            background: #ffffff;
        }

        .icon_323X1 {
            width: 70px;
            height: 40px;
            left: 11px;
        }

        .descript_title {
            width: 56px;
            height: 23px;
            left: 30px;
            top: 230px;
            color: rgba(80, 80, 80, 1);
            font-size: 0.6rem;
            line-height: 130%;
            text-align: left;
            font-weight: bold;
        }

        .descript p {
            margin-top: 5px;
            font-size: 0.65rem;
            margin-left: 15px;
        }

        .cat_title {
            color: rgba(255, 87, 51, 1);
            width: 3.5rem;
            font-size: .65rem;
            text-align: center;
            margin-left: 0.6rem;
            height: 1.5rem;
            line-height: 1.5rem;
            font-weight: bold;
        }

        .cat_title span {
            color: rgba(255, 87, 51, 1);
            padding-right: 0.18rem;
        }

        .list_wrap {
            background-color: #ffffff;
        }

        .data_wrap {
            margin-top: .2rem;
        }

        .commodity_pic img {
            border-radius: 0.3rem;
            height: 7rem;
            width: 7rem;
        }

        .fix_btn {
            height: 2.1333333333rem;
            border-top: 1px solid #ccc;
            background: #fff;
            max-width: 16rem;
            margin: 0 auto;
        }

        .buy_now {
            width: 9.6rem;
            height: 100%;
            line-height: 2rem;
            text-align: center;
            color: #fff;
            background: #f42323;
            font-size: 0.65rem;
        }

        .service {
            font-size: 0.65rem;
            width: 6.6rem;
            height: 100%;
            line-height: 2rem;
            text-align: center;
            color: #f42323;
            background: #fff;
        }
        .top_btn {
            position: absolute;
            left: 0rem;
            top: 0.8533333333rem;
            width: 100%;
            padding: 0 0.64rem;
            overflow: hidden;
            z-index: 11;
        }
        .top_btn a {
            width: 1.3653333333rem;
            height: 1.3653333333rem;
            background: #677170;
            -webkit-border-radius: 50%;
            border-radius: 50%;
            line-height: 1.3653333333rem;
            text-align: center;
            color: #fff;
            opacity: 0.8;
        }
        .icon {
            font-family: "iconfont" !important;
            font-size: 16px;
            font-style: normal;
            -webkit-font-smoothing: antialiased;
            -webkit-text-stroke-width: 0.2px;
            -moz-osx-font-smoothing: grayscale;
        }
        header{
            background-color: transparent;
        }
        .top_btn .icon {
            font-size: 0.9rem;
        }
    </style>
<?php $this->endBlock() ?>

    <div class="index_wrap">
        <header class="top_btn">
            <a href="javascript:;" onclick="goBack()" class="go_back"> <span class="icon">&#xe885;</span> </a>
        </header>
        <!-- 搜索框-->
        <!-- 轮播图结构 -->
        <div class="banner_swiper">
            <div class="swiper-container">
                <div class="swiper-wrapper">
                    <? foreach ($arrScrollImage as $item) { ?>
                        <div class="swiper-slide">
                            <img src="<?= 'https://yl.aiyolian.cn/' . $item ?>" alt="">
                        </div>
                    <? } ?>
                </div>
                <div class="swiper-pagination"></div>
            </div>
        </div>
        <div class="descript">
            <img class="icon_323X1" src="/images/home/index_logo1.svg" alt="">
            <span class="descript_title">简介</span>
            <p>
                <?= $suppler->sContent ?>
            </p>
        </div>

        <!--   商品结构 -->
        <div class="data_wrap">
            <div class="list_wrap">
                <div>
                    <div class="commodity_list">
                        <!--  <div class="line"></div> -->
                        <ul>
                            <? foreach ($arrProduct as $value) { ?>
                                <li>
                                    <a href="<?= Url::toRoute([\Yii::$app->request->shopUrl . "/product/showdetail", 'id' => $value['lID']], true) ?>"
                                       class="commodity flex productli">
                                        <div class="commodity_pic">
                                            <img src="<?= 'http://product.aiyolian.cn/' . $value['sMasterPic'] ?>"
                                                 alt=""
                                                 class="lazy">
                                        </div>
                                        <div class="commodity_detail" style="margin-top:.2rem">
                                            <h2 class="multiEllipsis"><?= $value['sName'] ?></h2>
                                            <? if ($value['saleout'] == '已售罄') { ?>
                                                <div class="rob_status">已售罄</div>
                                            <? } ?>

                                            <div class="commodity_price">
                                                <p><?= '促销价：' . $value['fPrice'] ?></p>
                                                <?if($bAlliaceFun){?>
                                                <p><?= '供货价：' . $value['fSupplierPrice'] ?></p>
                                                <?}else{?>
                                                    <p>供货价：联盟可见</p>
                                                <?}?>
                                            </div>
                                        </div>
                                    </a>
                                </li>
                            <? } ?>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

    </div>
    <div class="search_wrap" style="display: none;">
        <div class="search_header flex">
            <div class="s_close_wrap">
                <span class="s_close"></span>
            </div>
            <div class="search_input flex">
                <span></span>
                <input type="text" placeholder="" id="keyWord">
            </div>
            <div class="search_btn">搜索</div>
        </div>
        <div class="search_content">
            <div class="history_search">
                <div class="h_s_header flex">
                    <span class="h_s_name">历史搜索</span>
                    <div class="del_icon">
                        <span class="h_s_icon"></span>
                    </div>
                </div>
                <div class="h_s_content"><span onclick="toSearch('红酒')">红酒</span></div>
            </div>
            <div class="hot_search" style="display: none">
                <div class="hot_header">
                    <span>热门搜索</span>
                </div>
                <div class="hot_content">
                </div>
            </div>
        </div>
    </div>
    <div class="backTop"></div>
    <footer>
        <div class="fix_btn flex">
            <div class="service" onclick="showwx()">
                微信联系
            </div>
            <div class="buy_now " onclick="add()">
                我要联盟
            </div>
        </div>
    </footer>
    <div class="mask" onclick="closewx()"></div>
    <div class="wx_div">
        <? if ($shop) { ?>
            <img class="wx_img" src="/<?= $shop->sWXQrcode ?>">
        <? } else { ?>
            当前联盟商暂未上传微信二维码
        <? } ?>
    </div>
    <style>
        .commodity_detail h2 {
            font-size: 0.6rem;
        }
        .commodity_price {
           position: static;
        }
        .commodity_price p {
            font-size: 0.65rem;
            color: red;
        }

        .mask {
            width: 100%;
            height: 100%;
            position: fixed;
            left: 0;
            top: 0;
            z-index: 100;
            background: rgba(0, 0, 0, 0.5);
            display: none;
        }

        .wx_div {
            height: 10rem;
            width: 100%;
            position: absolute;
            bottom: 2.2rem;
            background-color: #fff;
            z-index: 1000;
            display: none;
            line-height: 2.2rem;
            font-size: 30px;
        }

        .wx_img {
            width: 6.5rem;
            position: relative;
            bottom: -2rem;
            left: 4.5rem;
        }
    </style>
<?php $this->beginBlock('foot') ?>

    <script src="/js/swiper.min.js"></script>
    <script src="/js/mescroll.min.js"></script>
    <script src="/js/template.js"></script>
    <script>
        var bTop = '<?=$bTop?>';
        var isPageHide = false;
        window.addEventListener('pageshow', function () {
            if (isPageHide) {
                window.location.reload();
            }
        });
        window.addEventListener('pagehide', function () {
            isPageHide = true;
        });

        function showwx() {
            if(bTop==1) {
                $('.mask').show();
                $('.wx_div').show();
            }
            else {
                location.href='<?= Url::toRoute([\Yii::$app->request->shopUrl."/supplier/alliancereg"], true) ?>';
            }
        }


        function closewx() {
            $('.mask').hide();
            $('.wx_div').hide();
        }

        function add() {
            if(bTop==1) {
                var specData = {
                    supplierID: '<?=$_GET['supplierID']?>',
                    _csrf: '<?=\Yii::$app->request->getCsrfToken()?>',
                }
                //加入购物车
                $.post('<?= Url::toRoute([\Yii::$app->request->shopUrl."/supplier/alliaceadd"], true) ?>', specData,
                    function (res) {
                        shoperm.showTip(res.msg);
                    }, 'json')
            }else {
                location.href='<?= Url::toRoute([\Yii::$app->request->shopUrl."/supplier/alliancereg"], true) ?>';
            }
        }
    </script>
    <script>
        $(function () {
            //轮播图
            var mySwiper = new Swiper('.swiper-container', {
                autoplay: 2000,//可选选项，自动滑动
                pagination: '.swiper-pagination',
            })


            //回到顶部
            $('.backTop').on('click', function () {
                $(window).scrollTop(0);
            })
            //搜索栏效果
            $('.top_search').on('click', function () {
                $('.search_wrap').show();
                $('.index_wrap').hide();
                $('footer').hide();
            })
            $('.s_close_wrap').on('click', function () {
                $('.search_wrap').hide();
                $('.index_wrap').show();
                $('footer').show();
            })
            var local = localStorage.getItem('searchWordArr'),
                spanHtml = '';
            if (local) {
                local = local.split(',');
                for (var i = 0; i < local.length; i++) {  //取缓存里面的值  循环成span标签插入
                    if (i > 4) { //只显示5个
                        break;
                    }

                    spanHtml += '<span onclick="toSearch(\'' + local[i] + '\')">' + local[i] + '</span>';
                }
                $('.h_s_content').html(spanHtml);
            } else {
                $('.history_search').hide();
            }

            //去搜索处理
            $('.search_btn').on('click', function () {

                //设置缓存
                var value = $.trim($('#keyWord').val());
                //有输入的 情况
                if (value !== '') {
                    var sWords = localStorage.getItem('searchWordArr'), //获取缓存值
                        searchWordArr = [];

                    if (!sWords) { //判断没有的话 新建
                        searchWordArr.unshift(value);
                        localStorage.setItem("searchWordArr", searchWordArr);
                    } else { //有的话 分割字符串 重新组装
                        searchWordArr = sWords.split(',');
                        var isEqual = searchWordArr.every(function (item, index) {
                            return item !== value;
                        })

                        if (isEqual) {
                            searchWordArr.unshift(value);
                            localStorage.setItem("searchWordArr", searchWordArr);
                        }
                    }
                } else {
                    //placeholder 是默认设置的 关键字
                    value = $('#keyWord').attr('placeholder');
                }

                $('#keyWord').val('');

                //value 输入的值
                location.href = "<?=Url::toRoute([\Yii::$app->request->shopUrl . "/product/list"],
                    true)?>?keyword=" + encodeURI(value);
            })

            //清空历史记录
            $('.del_icon').on('click', function () {
                $('.mask').show();
                shoperm.selection('确认清空历史记录吗');
            })
            //取消
            $('.select_cancel').on('click', function () {
                $('.selection_bar').hide();
                $('.mask').hide();
            })
            //确认
            $('.select_sure').on('click', function () {
                $('.selection_bar').hide();
                $('.mask').hide();
                $('.history_search').hide();

                localStorage.removeItem('searchWordArr');
            })
        })

        //历史搜索使用的
        function toSearch(keyword) {
            var url = '<?= Url::toRoute([\Yii::$app->request->shopUrl . "/product/list"], true)?>';
            location.href = url + '?keyword=' + keyword;
        }
    </script>
    <script>

        $(function () {

            //$('input').focus(focustext)

            /*******用以下三行代码即可实现*******/

            $('input').click(function () {

                $(this).focus().select();//保险起见，还是加上这句。
                this.selectionStart = 0;

                this.selectionEnd = this.val().length;

            })

        })

        function focustext() {

            var input = this;

            setTimeout(function () {
                input.selectionStart = 0;
                input.selectionEnd = input.val().length;
            }, 100)

        }

    </script>
<?php $this->endBlock() ?>