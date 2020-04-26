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

        .commodity_pic {
            width: 2rem;
            height: 2rem;
            margin-right: 0.2rem;
            position: relative;
        }

        .commodity_pic img {
            height: 1.8rem;
            width: 1.8rem;
        }

        .commodity_list li {
            background: #fff;
            padding: 0.64rem 0 0 0.4266666667rem;
            margin-top: 0.3rem;
            width: 98%;
            margin-left: 1%;
            border-radius: 0.3rem;
        }

         .commodity_spec {
            clear: both;
            height: 1.8rem;
            width: 100%;
            margin-top: 0.2rem;
            position: relative;
        }

        [data-dpr="2"] .commodity_detail h2 {
            font-size: 0.6rem;
        }

         .p_product {
            font-size: 0.65rem;
            color: #333;
            width: 14rem;
        }

        .p_count {
            font-size: 0.65rem;
            color: #ABABAB;
            width: 10rem;
        }

        .commodity_detail {
            border: none;
            width: 100%;
            padding-right: 0.2rem;
            margin-left: 0.3rem;
        }

        .commodity_detail h2 {
            font-weight: bold;
        }

          .p_right {
            font-size: 0.6rem;
            position: absolute;
            right: 0.5rem;
            top: 0.5rem;
        }

        /* 分块栏 */
        .producttype {
            background-color: rgba(255, 255, 255, 1);
            height: 2rem;
        }

        .type_point {
            color: rgba(212, 48, 48, 1);
        }

        .part_point {
            border-bottom: 2px solid rgba(212, 48, 48, 1);
        }

        .productpart {
            width: 5rem;
            height: 2rem;
            margin-left: 15%;
            font-size: .6rem;
            font-weight: lighter;
            float: left;
            vertical-align: middle;
            line-height: 2rem;
            text-align: center;
        }

        .checkimg img {
            display: inline-block;
        }

        .checkimg .check {
            width: 3.5rem;
        }

        .checkimg .flag {
            width: 0.6rem;
        }

        .productimg {
            clear: both;
            height: 5rem;
            width: 100%;
        }

        .productimg img {
            float: left;
            width: 4.8rem;
            height: 4.8rem;
            margin-left: 0.2rem;
        }
        .commodity_des{
            height: 1rem;
        }
        .commodity_des p{
            font-size: 0.6rem;
            color: #ABABAB;
        }
    </style>
<?php $this->endBlock() ?>
    <!-- 轮播图结构 -->
    <div class="banner_swiper">
        <div class="swiper-container">
            <div class="swiper-wrapper">
                <!--                --><? // foreach ($arrScrollImage as $item) { ?>
                <!--                    <div class="swiper-slide">-->
                <!--                        <img src="--><? //= 'https://yl.aiyolian.cn/' . $item ?><!--" alt="">-->
                <!--                    </div>-->
                <!--                --><? // } ?>
                <div class="swiper-slide">
                    <img src="/images/home/supplierbanner.jpg" alt="">
                </div>
            </div>
            <div class="swiper-pagination"></div>
        </div>
    </div>
    <div class="producttype">
        <div class="productpart part_point type_point">联盟商</div>
        <div class="productpart "><a
                    href="<?= Url::toRoute([\Yii::$app->request->shopUrl . "/agentshop/category"], true) ?>">爆品推荐</a>
        </div>
        <div class="clearfloat"></div>
    </div>
    <div class="index_wrap" id="app1" v-cloak>


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
                                        <img src="/images/home/list.png" :x-src="lItem.image" alt="" class="lazy">
                                    </div>
                                    <div class="commodity_detail">
                                        <h2 class="multiEllipsis" v-text="lItem.title"></h2>
                                        <div class="checkimg">
                                            <img src="/images/home/namechecked.png" :x-src="lItem.checkImg"
                                                 class="check lazy">
                                            <em v-for="(img,index) in lItem.arrFlag">
                                                <img src="" :x-src="img" class="flag lazy">
                                            </em>
                                        </div>
                                        <div class="commodity_price" style="position: static; display: none">
                                            <p v-text="lItem.nickname"></p>
                                            <p v-text="lItem.lProductNum"></p>
                                            <p v-text="lItem.lFanNum"></p>
                                            <p v-text="lItem.fFanOrder"></p>
                                        </div>
                                    </div>
                                </a>
                                <a :href="lItem.link">
                                <div class="commodity_spec">
                                    <p class="p_product" v-text="lItem.mainProduct"></p>
                                    <p class="p_count" v-text="lItem.fSumOrder"></p>
                                </div>
                                <div class="commodity_des">
                                    <p v-text="lItem.lFanNum" style="float: left;width: 5.5rem"></p>
                                    <p v-text="lItem.fFanOrder" style="float: left;margin-left: 0.3rem"></p>
                                </div>
                                <div class="productimg">
                                    <em v-for="(img,index) in lItem.arrProductImg">
                                        <img src="/images/home/namechecked.png" :x-src="img"
                                             class="check lazy">
                                    </em>
                                </div>
                                <div class="commodity_spec" style="display: none;">
                                    <p class="p_product" v-text="lItem.mainProduct"></p>
                                    <p class="p_count" v-text="lItem.fSumOrder"></p>
                                    <p class="p_right"><a :href="lItem.link" class="commodity flex">详情 > </a></p>
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
            没有更多了~
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

<?= $this->render('/layouts/foot', ['bIndex' => true]) ?>

    <style>
        .commodity_price p {
            color: #ABABAB;
        }

        .commodity_price > p:first-child {
            color: #333;
        }

        .commodity_price p {
            font-size: 0.6rem;
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
                        url: '<?=Url::toRoute([\Yii::$app->request->shopUrl . "/supplier/itemsupplier"], true)?>',
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