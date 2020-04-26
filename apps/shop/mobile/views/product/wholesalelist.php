<?php $this->beginBlock('style') ?>
    <link rel="stylesheet" href="/css/mescroll.min.css">
    <link rel="stylesheet" href="/css/searchList.css?<?= \Yii::$app->request->sRandomKey ?>">
    <link rel="stylesheet" href="/css/searchList.css?<?= time() ?>">
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
    </style>
<?php $this->endBlock() ?>

    <style type="text/css">
        .s_l_list, .s_l_content {
            height: 100%;
        }

        .mescroll {
            position: fixed;
            bottom: 0;
            height: auto;
            width: 100%;
            max-width: 16rem;
            margin: 0 auto;
        }

        .mescroll-upwarp {
            display: none;
        }
    </style>
    <div class="searchList_wrap" id="app" v-cloak>
        <!-- 头部导航 -->
        <div class="top_colum">
            <div class="s_l_top flex">
                <div class="s_l_back" onclick="goBack()">
                    <span class="icon">&#xe885;</span>
                </div>
                <div class="s_input flex">
                    <span></span>
                    <h2><?= empty($_GET['keyword']) ? '搜索商品' : $_GET['keyword'] ?></h2>
                </div>
                <div class="s_l_more flexOne">
                    <span class="icon">&#xe602;</span>
                </div>
            </div>
            <div class="select_area" v-if="!isEmpty">
                <div class="area_list flex">
                    <span :class="{ 'on':'default'== isSelect }" @click="changeCondition('default')">默认排序</span>
                    <span class="s_price" :class="{ 'on':'sale'== isSelect }" @click="changeCondition('sale')">销量</span>
                    <span class="arrow_wrap" :class="{ 'on':'price'== isSelect }" @click="changeCondition('price')">
						价格
        			<i class="arrow_top" :class="{ 'up':'up'== sortBy }"></i>
        			<i class="arrow_bottom" :class="{ 'down':'down'== sortBy }"></i>
        		</span>

                </div>
                <!-- <div class="area_right flex">
					<span class="icon">&#xe635;</span>
					<em>筛选</em>
				</div> -->
            </div>
        </div>
        <!-- 列表 -->
        <div class="s_l_content" v-if="!isEmpty">
            <div id="loading" class="sn-html5-loading" v-if="isLoading">
                <div class="blueball"></div>
                <div class="orangeball"></div>
            </div>
            <div class="s_l_list" v-else>
                <div id="mescroll" class="mescroll">
                    <div class="mescroll-bounce">
                        <ul>
                            <li v-for="item in dataList">
                                <a :href="item.link" class="flex">
                                    <div class="list_img">
                                        <img :src="item.image" alt="">
                                        <div class="list_img_mask" v-if="item.saleout">
                                            <div class="l_wrap">
                                                <img src="/images/home/sellout.png" alt="" v-if="item.saleout">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="list_content flexOne">
                                        <h2 class="multiEllipsis" v-text="item.title"></h2>
                                        <!--                                        <del v-text="item.market_price"></del>-->
                                        <!--                                        <em v-text="item.price" class="price"></em>-->
                                        <!--                                        <span v-text="'已售'+item.sold+'件'" class="wholesalesold"></span>-->
                                        <!--                                        <span v-text="'供货价：'+item.wholesaleCostPrice" class="wholesaleCostPrice"></span>-->
                                        <div class="commodity_price" style="position: static">
                                            <p v-text="item.fSupplierPrice"></p>
                                            <p v-text="item.price"></p>
                                            <p v-text="item.market_price"></p>
                                        </div>
                                    </div>
                                </a>
                            </li>
                        </ul>
                        <!-- 由于插件中的结构不能很好的适应这里的场景所以自己写了提示结构 -->
                        <div class="loading_wrap" v-if="mescrollLoading">
                            <p class="loading"></p>
                            <p class="loading_tip">加载中...</p>
                        </div>
                        <div class="bottom_tip" v-if="dataMore&&!isLoading">没有更多商品了~</div>
                    </div>
                </div>
            </div>
        </div>
        <!-- 筛选为空时 -->
        <div class="empty_list" v-if="isEmpty">
            <div class="empty_pic">
                <img src="/images/list_empty.png" alt="">
            </div>
            <p>啊哦~没有搜到相关商品</p>
            <a href="<?= Yii::$app->request->mallHomeUrl ?>">回到首页</a>
        </div>

        <!-- 筛选框 -->
        <div class="filter_wrap">
            <div class="filter_top flex">
                <span class="cancel_btn">取消</span>
                <h3>筛选</h3>
                <span class="sure_btn" @click="choseTarget()">确认</span>
            </div>
            <div class="filter_content">
                <? if ($arrTag) { ?>
                    <div class="f_label">
                        <div class="f_l_top flex">
                            <div class="l_t_name">标签</div>
                            <div class="l_t_all">
                                展开全部 <i></i>
                            </div>
                        </div>
                        <div class="f_l_content">
                            <span class="all <?= $_GET['tag'] ? "" : "on" ?>" data-id="">全部</span>
                            <!-- 第一个要特殊处理 class all其他span不能有 -->
                            <?
                            $arrGetTag = $_GET['tag'] ? explode(",", $_GET['tag']) : [];
                            foreach ($arrTag as $key => $val) { ?>
                                <? if ($key <= 6) { ?>
                                    <span data-id="<?= $val['lID'] ?>" <?= in_array($val['lID'], $arrGetTag) ? "class=\"on\"" : "" ?>><?= $val['sName'] ?></span>
                                <? } else { ?>
                                    <? if ($key == 8) { ?>
                                        <div class="f_l_more">
                                    <? } ?>
                                    <span dat-aid="<?= $val['lID'] ?>" <?= in_array($val['lID'], $arrGetTag) ? "class=\"on\"" : "" ?>><?= $val['sName'] ?></span>
                                    <? if ($key + 1 == count($arrTag)) { ?>
                                        </div>
                                    <? } ?>
                                <? } ?>
                            <? } ?>
                        </div>
                    </div>
                <? } ?>
                <? if ($arrBrand) { ?>
                    <div class="f_classify">
                        <div class="f_c_top flex">
                            <div class="c_t_name">品牌</div>
                            <div class="c_t_all">
                                展开全部 <i></i>
                            </div>
                        </div>
                        <div class="f_c_content">
                            <span class="all <?= $_GET['brand'] ? "" : "on" ?>" data-id="">全部</span>
                            <!-- 第一个要特殊处理 class all其他span不能有 -->
                            <?
                            $arrGetBrand = $_GET['brand'] ? explode(",", $_GET['brand']) : [];
                            foreach ($arrBrand as $key => $val) { ?>
                                <? if ($key <= 6) { ?>
                                    <span data-id="<?= $val['lID'] ?>" <?= in_array($val['lID'], $arrGetBrand) ? "class=\"on\"" : "" ?>><?= $val['sName'] ?></span>
                                <? } else { ?>
                                    <? if ($key == 8) { ?>
                                        <div class="f_l_more">
                                    <? } ?>
                                    <span data-id="<?= $val['lID'] ?>" <?= in_array($val['lID'], $arrGetBrand) ? "class=\"on\"" : "" ?>><?= $val['sName'] ?></span>
                                    <? if ($key + 1 == count($arrBrand)) { ?>
                                        </div>
                                    <? } ?>
                                <? } ?>
                            <? } ?>
                        </div>
                    </div>
                <? } ?>
            </div>
        </div>
    </div>

    <div class="search_wrap" style="display: none;">
        <div class="search_header flex">
            <div class="s_close_wrap">
                <span class="s_close"></span>
            </div>
            <div class="search_input flex">
                <span></span> <input type="text" placeholder="<?= $_GET['keyword'] ?>" id="keyWord">
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
                <div class="h_s_content">
                </div>
            </div>
            <div class="hot_search" style="display: none">
                <div class="hot_header">
                    <span>热门搜索</span>
                </div>
                <div class="hot_content">
                    <? foreach ($arrHotSearchWord as $item) { ?>
                        <span onclick="location.href='<?= \yii\helpers\Url::toRoute([
                            \Yii::$app->request->shopUrl . "/product/list",
                            'keyword' => $item['sName']
                        ], true) ?>';closeSearch();"><?= $item['sName'] ?></span>
                    <? } ?>
                </div>
            </div>
        </div>
    </div>
    <div class="mask"></div>
    <!-- 提示遮罩 -->
    <div class="select_mask"></div>

    <div class="nav_more_list">
        <div class="triangle-up"></div>
        <ul>
            <li class="flex" onclick="location.href='<?= \Yii::$app->request->mallHomeUrl ?>'">
                <span class="icon">&#xe608;</span> <em>首页</em>
            </li>
            <li class="flex" onclick="location.href='<?= \yii\helpers\Url::toRoute(["/member"], true) ?>'">
                <span class="icon">&#xe64a;</span> <em>我的</em>
            </li>
        </ul>
    </div>


<?php $this->beginBlock('foot') ?>

    <script src="/js/mescroll.min.js"></script>
    <script src="/js/template.js"></script>
    <script>
        var item = <?=$sFirstPageListData?>; //初始化数据
        var initList = item.data.commodity;
        // console.log(initList);
        var initData = {
            sortby: '<?=urldecode($_GET['sortby'])?>',
            ascdesc: '<?=urldecode($_GET['ascdesc'])?>',
            catid: '<?=urldecode($_GET['catid'])?>',
            tag: '<?=urldecode($_GET['tag'])?>',
            brand: '<?=urldecode($_GET['brand'])?>',
            keyword: '<?=urldecode($_GET['keyword'])?>',
            bWholesale: '1',
            pageno: 1
        };//后端初始化数据
        // 倒计时
        var spcList = Vue.extend({
            template: '',
            props: ['datatime'],
            data: function () {
                return {
                    isTime: this.datatime,
                    isTime: null,
                    timeOut: false,
                    isDay: 0,
                    isHour: 0,
                    isMin: 0,
                    isSecond: 0,
                }
            },
            watch: {},
            mounted: function () {
                var nowTime = (new Date(dNow).getTime()) / 1000; //后端时间数据转成秒
                this.init(nowTime);
                console.log(this.datatime);
            },
            methods: {
                init: function (nowTime) {
                    var _this = this;
                    var leftTime = _this.datatime.replace(/\-/g, "/"),
                        endTime = new Date(leftTime),
                        disTime = endTime.getTime() - nowTime * 1000, //这里时间数据转成毫秒
                        day = Math.floor(disTime / (1000 * 60 * 60 * 24)),
                        hour = Math.floor(disTime / (1000 * 60 * 60) % 24),
                        minute = Math.floor(disTime / (1000 * 60) % 60),
                        second = Math.floor(disTime / 1000 % 60);
                    if (disTime <= 0) {
                        clearTimeout(_this.isTime);
                        _this.timeOut = true;
                    }
                    _this.isDay = _this.checkTime(day);
                    _this.isHour = _this.checkTime(hour);
                    _this.isMin = _this.checkTime(minute);
                    _this.isSecond = _this.checkTime(second);


                    var isTime = setTimeout(function () {
                        nowTime = nowTime + 1;
                        _this.init(nowTime);
                    }, 1000)
                },
                checkTime: function (i) {
                    if (i < 10) {
                        i = '0' + i;
                    }
                    return i;
                },
            }
        });


        new Vue({
            el: '#app',
            components: {
                'spc-list': spcList
            },
            data: {
                priceSort: false, //价格排序
                reqData: initData, //数据初始赋值
                dataList: initList,

                dataMore: false, //是否更多数据
                isLoading: false, //加载图
                isSelect: 'default', //默认选择
                isSort: initData.sortby, //后台传过来的当前选择值
                sortBy: '', //排序
                isEmpty: false,//是否为空
                mescroll: null, //滚动对象
                mescrollLoading: false,//底部加载
            },
            mounted: function () {

                var _self = this;

                _self.initSet();

                _self.mescroll = new MeScroll("mescroll", {
                    up: {
                        auto: false, //初始化不加载
                        callback: _self.upCallback, //上拉回调
                    },
                    down: {
                        use: false,
                    }
                });
                document.getElementById("app").style.display = "block";
            },
            methods: {
                //加载数据回调
                upCallback: function () {

                    this.reqData.pageno++;
                    if (this.dataMore) { //判断是否有更多
                        this.mescroll.endSuccess();
                        return;
                    }
                    this.mescrollLoading = true;
                    this.getData(true);
                },
                //初始化页面选择
                initSet: function () {
                    var selectName = this.isSort;
                    var isList = this.dataList;
                    if (isList.length == 0) {
                        this.isEmpty = true;
                    }
                    switch (selectName) {
                        case '':
                            this.isSelect = 'default';
                            break;
                        case 'sale':
                            this.isSelect = 'sale';
                            break;
                        case 'price':
                            this.isSelect = 'price';
                            this.sortBy = initData.ascdesc;
                            break;
                    }
                },
                //ajax请求函数
                getData: function (mark) {
                    var _this = this;
                    //地址栏变换 用于点击时地址hash变化
                    if (!mark) {
                        var hash = $.param(_this.reqData);
                        window.history.replaceState(null, null, '?' + hash);
                    }

                    $.ajax({
                        type: 'GET',
                        url: '<?=\yii\helpers\Url::toRoute([\Yii::$app->request->shopUrl . "/product/item"], true)?>',
                        data: _this.reqData,
                        dataType: 'json',
                        success: function (res) {
                            // console.log(res);
                            _this.isLoading = false;

                            if (mark) {
                                //滚动情况
                                if (res && res.data && res.data.commodity.length !== 0) {

                                    _this.dataList = _this.dataList.concat(res.data.commodity);

                                } else {
                                    _this.dataMore = true;
                                    _this.mescrollLoading = false;
                                }
                                _this.mescroll.endSuccess(); //数据加载完 状态处理

                            } else { //点击情况

                                if (res && res.data && res.data.commodity.length !== 0) {
                                    _this.dataList = res.data.commodity;

                                    if (res.data.commodity.length <= 4) { //不足4条数据的情况
                                        _this.mescrollLoading = false;
                                    }
                                } else {
                                    _this.dataList = [];
                                    _this.isEmpty = true;
                                    _this.dataMore = false;

                                }
                                _this.mescroll.endSuccess();

                            }
                        },
                        error: function (xhr, type) {
                            _self.mescroll.endErr(); //失败后调整
                        }
                    });
                },
                //切换选择 函数
                changeCondition: function (value) {

                    switch (value) {
                        case 'default':
                            this.reqData.pageno = 1;
                            this.reqData.sortby = '';
                            break;
                        case 'sale':
                            this.reqData.pageno = 1;
                            this.reqData.sortby = 'sale';
                            break;
                        case 'price':
                            this.priceSort = !this.priceSort;
                            if (this.priceSort) {
                                this.reqData.ascdesc = "up";
                            } else {
                                this.reqData.ascdesc = "down";
                            }
                            this.reqData.pageno = 1;
                            this.reqData.sortby = 'price';
                            break;
                    }
                    this.dataMore = false;
                    this.isLoading = true;
                    this.mescroll.scrollTo(0, 0);//切换栏目时回到顶部
                    this.getData();

                },
                //筛选确定
                choseTarget: function () {

                    var that = this;
                    var tAttr = [], cAttr = [];

                    //选择标签
                    $('.f_l_content span').each(function () {
                        var isChose = $(this).hasClass("on"),
                            lText = $(this).data('id');
                        if (isChose) {
                            tAttr.push(lText);
                        }
                    });
                    that.reqData.tag = tAttr.join(',');

                    //选择品牌
                    $('.f_c_content span').each(function () {
                        var isChose = $(this).hasClass("on"),
                            cText = $(this).data('id');
                        if (isChose) {
                            cAttr.push(cText);
                        }
                    })
                    that.reqData.brand = cAttr.join(',');

                    this.dataMore = false; //是否更多重置
                    this.mescroll.scrollTo(0, 0);
                    this.reqData.pageno = 1;
                    this.getData();
                },
            }
        })
    </script>

    <script>
        $(function () {
            var bWholesale = '1';
            //处理商品不足时的 页面样式
            var listHeight = $('.s_l_list').height(),
                wrapHeight = $(window).height();

            if (listHeight < wrapHeight) {
                $('html, body, .searchList_wrap,.s_l_content').addClass('fullScreen');
            }

            var price_flag = 1;
            //标签选择
            $('.area_list span').on('click', function () {
                var index = $(this).index();
                $(this).addClass('on').siblings().removeClass('on');
                if (index !== 2) {
                    $('.arrow_top').removeClass('up');
                    $('.arrow_bottom').removeClass('down');

                }
            })
            //价格选择
            $('.arrow_wrap').on('click', function () {
                if (price_flag) {
                    $('.arrow_top ').addClass('up');
                    $('.arrow_bottom').removeClass('down');
                    price_flag = 0;
                } else {
                    $('.arrow_bottom').addClass('down');
                    $('.arrow_top').removeClass('up');
                    price_flag = 1;
                }

            })

            //筛选
            $('.select_filter').on('click', function () {
                $('.mask').show();
                $('.filter_wrap').show();
            })
            $('.cancel_btn,.mask').on('click', function () {
                $('.filter_wrap').hide();
                $('.mask').hide();
            })
            $('.sure_btn').on('click', function () {
                $('.mask').hide();
                $('.filter_wrap').hide();

            })

            //筛选标签选择
            $('.f_l_content span,.f_c_content span').on('click', function () {
                var isOn = $(this).hasClass("on")
                if (isOn) {
                    $(this).removeClass('on');
                } else {
                    $(this).addClass('on');
                }
                $(this).siblings('.all').removeClass('on');
            })

            $('.all').on('click', function () {
                $(this).siblings('span').removeClass('on');
                $(this).siblings('.f_l_more').find('span').removeClass('on');
            })

            $('.f_l_more span').on('click', function () {
                $(this).parent().siblings('.all').removeClass('on');
            })
            //更多
            $('.l_t_all, .c_t_all').on('click', function () {
                var isUP = $(this).find('i').hasClass("up");
                if (isUP) {
                    $(this).find('i').removeClass('up');
                    $(this).parent().siblings().find('.f_l_more').hide();
                } else {
                    $(this).find('i').addClass('up');
                    $(this).parent().siblings().find('.f_l_more').show();
                }
            })

            //其他页面跳转栏目
            $('.s_l_more').on('click', function () {
                event.stopPropagation();
                $(".nav_more_list").toggle();
            })

            $('.mescroll').on('scroll', function () {
                $(".nav_more_list").hide();
            })

            $('body').on('click', function () {
                $(".nav_more_list").hide();
            })

            //搜索页
            $('.s_input').on('click', function () {
                $('.search_wrap').show();
                $('.searchList_wrap').hide();

            })

            $('.s_close_wrap').on('click', function () {
                $('.search_wrap').hide();
                $('.searchList_wrap').show();

            })
            var local = localStorage.getItem('searchWordArr'),
                spanHtml = '';
            if (local) {
                local = local.split(',');
                for (var i = 0; i < local.length; i++) { //取缓存里面的值  循环成span标签插入
                    if (i > 4) { //只显示5个
                        break;
                    }
                    spanHtml += '<span onclick="toSearch(\'' + local[i] + '\')">' + local[i] + '</span>';

                }
                $('.h_s_content').html(spanHtml);
            } else {
                $('.history_search').hide();
            }

            $('.search_btn').on('click', function () {

                //设置缓存
                var value = $.trim($('#keyWord').val());
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
                    value = $('#keyWord').attr('placeholder');
                }

                $('#keyWord').val('');
                $('.search_wrap').hide();
                $('.searchList_wrap').show();

                setTimeout(function (args) {
                    location.href = "<?=\yii\helpers\Url::toRoute([\Yii::$app->request->shopUrl . "/product/list"],
                        true)?>?keyword=" + encodeURI(value) + "&bWholesale=1"; //跳转模拟
                }, 500);


            })

            $('.del_icon').on('click', function () {
                $('.select_mask').show();
                shoperm.selection('确认清空历史记录吗');
            })

            $('.select_cancel').on('click', function () {
                $('.selection_bar').hide();
                $('.select_mask').hide();
            })

            $('.select_sure').on('click', function () {
                $('.selection_bar').hide();
                $('.select_mask').hide();
                $('.history_search').hide();

                localStorage.removeItem('searchWordArr');
            })
        })

        //以下为处理IOS返回时 记录之前操作遗留的弹框的问题
        function isIOS() {
            var u = navigator.userAgent;
            return !!u.match(/\(i[^;]+;( U;)? CPU.+Mac OS X/); //ios终端
        }

        //搜索历史加链接
        function toSearch(keyword) {
            var bWholesale = '<?=urldecode($_GET['bWholesale'])?>';
            var url = '<?=\yii\helpers\Url::toRoute([\Yii::$app->request->shopUrl . "/product/list"], true)?>';
            location.href = url + '?keyword=' + keyword + "&bWholesale=1";
            if (isIOS()) {
                $('.search_wrap').hide();
                $('.searchList_wrap').show();
            }
        }

        function closeSearch() {
            if (isIOS()) {
                $('.search_wrap').hide();
                $('.searchList_wrap').show();
            }
        }
    </script>

<?php $this->endBlock() ?>