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
        .lsj_tab_list li span{
            font-size: 0.7rem;
        }
        .lsj_tab_list li.cur span {
            color: red;
            border-bottom: 2px solid red;
            height: 100%;
            padding: 0 .2rem;
            display: inline-block;
        }
        .lsj_tab_list li.cur span a{
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
            background: #fff;
        }
        .top_search{
            opacity: 1;
        }
        #keyWord{
            height: 100%;
        }
    </style>
<?php $this->endBlock() ?>

    <div class="index_wrap" id="app1" v-cloak>
        <!-- 搜索框-->
        <div class="top_search flex" id="search"><span class="mirror"></span> <span class="word">搜索商品</span></div>
        <!-- 轮播图结构 -->
        <div class="banner_swiper">
            <div class="swiper-container">
                <div class="swiper-wrapper">
                    <? foreach ($arrScrollImage['arrPic'] as $item) { ?>
                        <div class="swiper-slide" onclick="location.href='<?= $item['sLink'] ?>'">
                            <img src="<?= Func::handleImagePath($item['sPic']) ?>" alt="">
                        </div>
                    <? } ?>
                </div>
                <div class="swiper-pagination"></div>
            </div>
        </div>
        <ul class="lsj_tab_list">
            <li><span><a href="<?=Url::toRoute([\Yii::$app->request->shopUrl . "/home/index"], true)?>">一件代发</a></span></li>
            <li class="cur"><span><a href="<?=Url::toRoute([\Yii::$app->request->shopUrl . "/home/indexgroup"], true)?>">线上团购</a></span></li>
            <li><span><a href="<?=Url::toRoute([\Yii::$app->request->shopUrl . "/home/indexbulk"], true)?>">线下大宗</a></span></li>
        </ul>
        <!--   商品结构 -->
        <div class="data_wrap">
            <div class="list_wrap" v-for="item in dataList">
                <div>
                    <div class="commodity_list">
                        <!--  <div class="line"></div> -->
                        <ul>
                            <li v-for="(lItem,index) in item.commodity">
                                <a :href="lItem.link" class="commodity flex">
                                    <div class="commodity_pic">
                                        <img src="/images/home/list.png" :x-src="lItem.image" alt=""
                                             class="lazy">
                                    </div>
                                    <div class="commodity_detail" style="margin-top:1.2rem">
                                        <h2 class="multiEllipsis" v-text="lItem.title"></h2>
                                        <div class="rob_status" v-if="lItem.saleout == '已售罄'">已售罄</div>
                                        <div class="commodity_price" style="position: static">
                                            <p v-text="lItem.sGroupKeyword" class="sGroupKeyword"></p>
                                            <p v-text="lItem.fSupplierPrice"></p>
                                            <p v-text="lItem.price"></p>
                                            <p v-text="lItem.market_price"></p>
                                        </div>
                                    </div>
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <div class="loading_wrap" v-if="mescrollLoading">
            <p class="loading"></p>
            <p class="loading_tip">加载中...</p>
        </div>
        <div class="bottom_tip" v-if="!isMore">
            <? if (\Yii::$app->frontsession->member->bActive > 0) { ?>
                没有更多商品了~
            <? } elseif(!\Yii::$app->frontsession->member||!\Yii::$app->frontsession->member->sMobile) { ?>
                <a href="<?= Url::toRoute([\Yii::$app->request->shopUrl . "/member/reg"], true) ?>">申请入驻，查看更多</a>
            <? }else{ ?>
                <a href="<?= Url::toRoute([\Yii::$app->request->shopUrl . "/wholesaler/applydesc"], true) ?>">申请入驻，查看更多</a>
            <?}?>
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

    <footer style="display: none;">
        <div class="bottom_fixed flex">
            <a href="<?= Url::toRoute([\Yii::$app->request->shopUrl . "/home"], true) ?>" class="on">
                <span class="icon">&#xe614;</span>
                <p>首页</p>
            </a>
            <a href="<?= Url::toRoute([\Yii::$app->request->shopUrl . "/product/year"], true) ?>">
                <span class="icon">&#xe655;</span>
                <p>年度计划</p>
            </a>
            <a href="<?= Url::toRoute([\Yii::$app->request->shopUrl . "/product/category"], true) ?>">
                <span class="icon">&#xe6b0;</span>
                <p>分类</p>
            </a>
            <a href="<?= Url::toRoute([\Yii::$app->request->shopUrl . "/product/recommend"], true) ?>">
                <span class="icon">&#xe62d;</span>
                <p>推荐</p>
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
        .commodity_price:first-child {
            color: #ABABAB;
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
        [data-dpr="3"] .commodity_price .sGroupKeyword{
            color: red;
            font-size: 38px;
        }
    </style>
<?php $this->beginBlock('foot') ?>

    <script src="/js/swiper.min.js"></script>
    <script src="/js/mescroll.min.js"></script>
    <script src="/js/template.js"></script>
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
            el: '#app1',
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
                            index: _self.page,
                            type: 1
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
                var imgTop = $(this).offset().top - $(window).scrollTop()-200; //计算距离顶部的距离
                var data_src = $(this).attr('x-src');
                if (imgTop <= contentHeight) {
                    $(this).attr('src', data_src)
                }
            })
        }
    </script>

<?php $this->endBlock() ?>