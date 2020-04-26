<?php

use yii\helpers\Url;
use myerm\common\components\Func;

?>
<?php $this->beginBlock('style') ?>
    <link rel="stylesheet" href="/css/swiper.min.css">
    <link rel="stylesheet" href="/css/mescroll.min.css">
    <link rel="stylesheet" href="/css/home.css?<?= \Yii::$app->request->sRandomKey ?>">
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

        .commodity_pic {
            height: 5rem;
            width: 5rem;
        }

        .commodity_pic img {
            height: 5rem;
            width: 5rem;
        }

        .commodity_detail {
            border: 0;
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
            position: relative;;
        }

        .buy .icon {
            font-size: 0.7rem;
            margin-right: 1rem;
            z-index: 88;
        }

        .fastbuy {
            font-size: 0.55rem;
            font-weight: 400;
            background-color: #f42323;
            border-radius: 0.2rem;
            padding: 0.1rem;
            color: #fff;
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

    </style>
<?php $this->endBlock() ?>

    <div class="index_wrap" id="app" v-cloak>
        <!-- 搜索框-->
        <div class="top_search flex" id="search" style="display: none"><span class="mirror"></span> <span class="word">搜索商品</span>
        </div>
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
        <?if($seller){?>
        <ul class="lsj_tab_list">
            <li><span><a href="<?=Url::toRoute([\Yii::$app->request->shopUrl . "/home/index"], true)?>">好货拼团</a></span></li>
            <li class="cur"><span><a href="<?=Url::toRoute([\Yii::$app->request->shopUrl . "/home/home"], true)?>">精选到家</a></span></li>
        </ul>
        <?}?>
        <!--   商品结构 -->
        <div class="data_wrap" style="margin-top: 0.1rem">
            <div class="list_wrap" v-for="item in dataList">
                <div>
                    <div class="commodity_list">
                        <!--  <div class="line"></div> -->
                        <ul>
                            <li v-for="(lItem,index) in item.commodity">
                                <div class="commodity flex">
                                    <div @click="linkUrl(lItem.link)" class="commodity_pic">
                                        <img src="/images/home/list.png" :x-src="lItem.image" alt=""
                                             class="lazy">
                                    </div>

                                    <div class="commodity_detail">
                                        <h2  style=" font-weight: bold;margin-top: 0.1rem" @click="linkUrl(lItem.link)"  class="multiEllipsis" v-text="lItem.title"></h2>
                                        <h3  @click="linkUrl(lItem.link)"  class="multiEllipsis" v-text="lItem.sRecomm"></h3>
                                        <div class="rob_status" v-if="lItem.saleout == '已售罄'">已售罄</div>
                                        <div class="commodity_price" style="position: static">
                                            <p v-text="lItem.fSupplierPrice"></p>
                                            <p v-text="lItem.lStock"></p>
                                            <p style="text-decoration:line-through" v-text="lItem.market_price"></p>

                                        </div>
                                        <div class="buy">
                                            <p style="color: red;font-weight: bold;" v-text="lItem.price"></p>
                                            <span @click="addcart(lItem.lID)" class="icon" style="color: red">&#xe60c;</span>
                                            <a :href="lItem.link">
                                                <span class="fastbuy">立即抢购</span>
                                            </a>
                                        </div>
                                    </div>

                                </div>

                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <div class="loading_wrap" style="display: none" v-if="mescrollLoading">
            <p class="loading"></p>
            <p class="loading_tip">加载中...</p>
        </div>
        <div class="bottom_tip" v-if="!isMore">
            没有更多商品了~
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
    <div class="mask"></div>
    <div class="redimg"><img src="/images/home/redimg.png"></div>
    <footer style="display: none;">
        <div class="bottom_fixed flex">
            <a href="<?= Url::toRoute([\Yii::$app->request->shopUrl . "/home"], true) ?>" class="on">
                <span class="icon">&#xe614;</span>
                <p>首页</p>
            </a>
            <a href="<?= Url::toRoute([\Yii::$app->request->shopUrl . "/cart"], true) ?>">
                <span class="icon">&#xe638;</span>
                <p>购物车</p>
            </a>
            <a href="<?= \yii\helpers\Url::toRoute(["/member"], true) ?>"> <span class="icon">&#xe64a;</span>
                <p>我的</p>
            </a>
        </div>
    </footer>
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
    <script src="/js/template.js"></script>
    <script src="/js/ydui.js"></script>
    <script>
        var isPageHide = false;
        window.addEventListener('pageshow', function () {
            if (isPageHide) {
                window.location.reload();
            }
        });
        window.addEventListener('pagehide', function () {
            isPageHide = true;
        });
    </script>
    <script>

        var item = <?=$sItemJson?>; //初始化数据 后端赋值
        var dataList = [
            item.data
        ];
        //主体部分
        new Vue({
            el: '#app',
            components: {
                'spec-clocker': squareClocker,
                'spc-list': listClocker
            },
            data: {
                page: 1,
                dataList: dataList, //后端返回值
                seckillShow: true,
                isType: '',
                isList: true, //显示列表
                isSquare: false, //显示方格
                isImage: true, //显示图片
                isMore: true, //是否更多
                mescroll: null,//滚动对象
                mescrollLoading: true, //滚动加载
            },
            mounted: function () {

                //轮播图
                var mySwiper = new Swiper('.swiper-container', {
                    autoplay: <?=$arrScrollImage['lScrollSpeed'] ? $arrScrollImage['lScrollSpeed'] * 1000 : 3000?>,//可选选项，自动滑动
                    pagination: '.swiper-pagination',
                })

                var _self = this;


                //初始化构造页面滚动结构
                _self.mescroll = new MeScroll("body", {
                    up: {
                        auto: false, //初始化不加载
                        callback: _self.upCallback, //上拉回调
                    },
                    down: {
                        use: false,
                    }
                });
            },
            methods: {
                upCallback: function () {
                    var _self = this;
                    _self.page++;
                    if (!_self.isMore) {
                        return;
                    }

                    $.ajax({
                        type: 'GET',
                        url: '<?=Url::toRoute([\Yii::$app->request->shopUrl . "/home/item"], true)?>',
                        data: {
                            index: _self.page
                        },
                        dataType: 'json',
                        success: function (res) {
                            if (res) {
                                //console.log(res.data.commodity);
                                _self.dataList = _self.dataList.concat(res.data);
                                _self.isMore = res.isMore;
                            }
                            if (!_self.isMore) {
                                _self.mescrollLoading = false;
                            }

                            _self.mescroll.endSuccess(); //数据加载完 状态处理
                        },
                        error: function (xhr, type) {
                            _self.mescroll.endErr(); //失败后调整
                        }
                    });
                },
                addcart: function (lID) {
                    var specData = {
                        productid: lID,
                        quantity: 1,
                        _csrf: '<?=\Yii::$app->request->getCsrfToken()?>',
                    }
                    //加入购物车
                    $.post('<?= Url::toRoute([\Yii::$app->request->shopUrl . "/cart/addtocart"], true) ?>', specData,
                        function (res) {
                            shoperm.showTip(res.message);
                        }, 'json')
                },
                linkUrl: function (URL) {
                    location.href = URL;
                }
            }
        })
    </script>
    <script>
        $(function () {
            //配合 v-cloak 的效果
            $('footer').show();
            lazyLoad();

            //添加缓存
            $('.section_product a,.commodity_list a,.footer_cart').on('click', function () {
                sessionStorage.setItem("indexReload", 'true');
            })
            //滚动触发事件
            $(window).on('scroll', function () {
                var scrollTop = $(this).scrollTop();
                if (scrollTop > 600) {
                    $('.backTop').show();
                } else {
                    $('.backTop').hide();
                }

                lazyLoad();
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
            var redbag =<?=\Yii::$app->frontsession->member->bReceiveRedbag?>;
            if (redbag != 1&&0) {
                $('.mask').show();
                $('.redimg').show();
            }
        })
        $('.mask,.redimg').on('click', function () {
            $('.redimg').hide();
            $('.mask').hide();
            $.get('<?= Url::toRoute([\Yii::$app->request->shopUrl . "/home/changeredstate"], true) ?>', {},
                function (res) {
                    location.href='<?= Url::toRoute([\Yii::$app->request->shopUrl . "/home/redbag"], true) ?>';
                }, 'json');
        })

        //历史搜索使用的
        function toSearch(keyword) {
            var url = '<?= Url::toRoute([\Yii::$app->request->shopUrl . "/product/list"], true)?>';
            location.href = url + '?keyword=' + keyword;
        }

        //图片懒加载函数
        function lazyLoad() {
            //获取屏幕的高
            var contentHeight = $(window).height();
            $('.lazy').each(function () {
                var imgTop = $(this).offset().top - $(window).scrollTop() - 200; //计算距离顶部的距离
                var data_src = $(this).attr('x-src');
                if (imgTop <= contentHeight) {
                    $(this).attr('src', data_src)
                }
            })
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