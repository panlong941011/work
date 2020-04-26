<?php $this->beginBlock('style') ?>
    <link rel="stylesheet" type="text/css" href="/css/LCalendar.css">
    <link rel="stylesheet" href="/css/mescroll.min.css">
    <!--<link rel="stylesheet" href="/css/order.css?<? /*= \Yii::$app->request->sRandomKey */ ?>">-->
    <link rel="stylesheet" href="/css/order.css?<?= time() ?>">
    <link rel="stylesheet" href="/css/swiper.min.css">
    <style>
        .sn-html5-loading {
            height: 0.866666667rem;
            width: .64rem;
            padding: .22rem 0 .32rem 0;
            margin: 0 auto;
            margin-top: .24rem;
            z-index: 180;
            position: relative
        }

        .sn-html5-loading .blueball, .sn-html5-loading .orangeball {
            display: block;
            width: .5546666667rem;
            height: .5546666667rem;
            position: absolute;
            top: .083rem;
            left: 0;
            border-radius: 50%;
            background: #f42323;
            -webkit-animation: bounce 1.2s infinite;
            -webkit-animation-timing-function: linear;
            z-index: 3
        }

        .sn-html5-loading .blueball {
            left: .1066666667rem;
            background: #353d44;
            -webkit-animation: bounce-left 1.2s infinite;
            -webkit-animation-timing-function: linear
        }

        @-webkit-keyframes bounce {
            0% {
                left: 0;
                z-index: 1
            }

            5% {
                left: 0;
                z-index: 1
            }

            25% {
                left: .3413333333rem;
                z-index: 1
            }

            50% {
                left: .64rem;
                z-index: 3
            }

            75% {
                left: .3413333333rem;
                z-index: 3
            }

            95% {
                left: 0
            }

            100% {
                left: 0;
                z-index: 3
            }
        }

        @-webkit-keyframes bounce-left {
            0% {
                left: .64rem
            }

            5% {
                left: .64rem
            }

            25% {
                left: .3413333333rem
            }

            50% {
                left: 0
            }

            75% {
                left: .3413333333rem
            }

            95% {
                left: .64rem
            }

            100% {
                left: .64rem
            }
        }

        .order_list, .order_content, .order_wrap {
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

        .order_content {
            position: relative;
        }

        .mescroll-upwarp {
            display: none;
        }

        .loading_wrap {
            background: #eee;
        }

        .order_wrap .date_time {
            color: #fff;
            position: relative;
            -webkit-align-items: center;
            align-items: center;
            width: 5rem;
        }

        .order_wrap .date_end_time {
            color: #fff;
            position: relative;
            -webkit-align-items: center;
            align-items: center;
            width: 5rem;
            /*right: 0.5rem;*/
        }
    </style>
<? if ($this->context->action->id == 'sellerorderlist') { ?>
    <style>
        .mescroll {
            position: fixed;
            bottom: 0;
            height: auto;
            width: 100%;
            max-width: 16rem;
            margin: 0 auto;
        }
    </style>
<? } else { ?>
    <style>
        .mescroll {
            position: fixed;
            bottom: 0;
            height: auto;
            width: 100%;
            max-width: 16rem;
            margin: 0 auto;
            top: 7rem;
        }
    </style>
<? } ?>
<?php $this->endBlock() ?>
    <!--<div class="payments" id="app" v-cloak>
        <div class="payments_top">
            <div class="ad_header">
                <a href="javascript:;" class="ad_back" onclick="goBack()"> <span class="icon">&#xe885;</span> </a>
                <h2>收支明细</h2>
            </div>
            <div class="calendar flex">
                <div class="date_time flex" id="datepiker">
                    <span class="show_date">全部</span>
                    <input id="dDeliverDate" hidden type="text" class="date_input" readonly name="dDeliverDate" placeholder="全部"/>
                    <i class="icon">&#xe64e;</i>
                    <div class="date_arrow"></div>
                </div>
                <em>¥<? /*= number_format($wholesaler->fSumIncome, 2) */ ?></em>
            </div>
        </div>
    </div>-->
    <div class="order_wrap" id="app" v-cloak>
        <div class="order_content" v-if="!isSearchShow">
            <div class="order_top">
                <div class="ad_header">
                    <a href="javascript:;" onclick="goBack()" class="ad_back"> <span class="icon">&#xe885;</span> </a>
                    <h2><?= $this->context->action->id == 'sellerorderlist' ? '经销商订单列表' : '渠道订单' ?></h2>
                    <span class="ad_more icon">&#xe602;</span>
                </div>
                <div class="calendar flex">
                    <div class="date_time flex" id="datepiker">
                        <span class="show_date">搜索：开始时间</span>
                        <input id="dDeliverDate" hidden type="text" class="date_input" readonly name="dDeliverDate" placeholder="开始"/>
                    </div>
                    <div class="date_end_time flex" id="datepiker2">
                        <span class="show_date2">结束时间</span>
                        <input id="dDeliverDateEnd" hidden type="text" class="date_input2" readonly name="dDeliverDateEnd" placeholder="结束"/>
                    </div>
                    <em id="fSumIncome">提成：¥<?= number_format($fSumIncome, 2) ?></em>
                </div>

                <nav v-if="navShow">
                    <div class="swiper-container">
                        <div class="swiper-wrapper">
                            <div class="swiper-slide" v-for="(status,index) in orderStatus" v-text='status.text'
                                    :class="{'active':status.type === currentType}" @click="changeStatus(status.type)">全部
                            </div>
                        </div>
                    </div>
                </nav>
            </div>

            <div class="order_list">
                <div id="mescroll" class="mescroll mescroll2">
                    <div class="mescroll-bounce" v-if="dataList.length!== 0">

                        <div id="loading" class="sn-html5-loading" v-if="isLoading">
                            <div class="blueball"></div>
                            <div class="orangeball"></div>
                        </div>
                        <section class="list_layer" v-for="item in dataList">
                            <!--TODO 链接没处理好，要链接到渠道商品订单-->
                            <!--<a :href="item.link">-->
                            <h3 class="layer_title ">
                                <span class="title_word singleEllipsis" v-text="item.shopname"></span>
                                <i class="title_arrow"></i> <span class="pay_status" v-text="item.status"></span>
                            </h3>
                            <!--</a>-->
                            <div class="list_content" v-for="goods in item.commodity">
                                <a :href="goods.link" class="commodity_link">
                                    <div class="list_item flex">
                                        <div class="pic"><img :src="goods.images" alt=""></div>
                                        <div class="info">
                                            <h4 class="title multiEllipsis" v-text="goods.title"></h4>
                                            <div class="prop">
                                                <p class="multiEllipsis"> {{goods.spec}}</p>
                                                <i class="num" v-text="'X'+goods.num"></i>
                                                <p v-if="goods.fCostPrice" class="multiEllipsis" style="margin-top: 0.8rem" v-text="'供货价：￥'+goods.fCostPrice"></p>
                                                <i v-if="goods.fWholesalePrice" class="wholesaleCostPrice" v-text="'渠道价：￥'+goods.fWholesalePrice"></i>
                                            </div>
                                            <div class="price flex">
                                                <em v-text="'￥'+goods.price"></em>
                                                <span v-if="goods.refund_status" v-text="goods.refund_status"></span>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            </div>
                            <div class="order_info">
                                <div class="order_buyer flex">
                                    <span>订单号：</span>
                                    <p v-text="item.sOrder"></p>
                                </div>
                                <div class="order_buyer flex">
                                    <span>下单时间：</span>
                                    <p v-text="item.dNewDate"></p>
                                </div>
                                <div class="order_buyer flex">
                                    <span>买家：</span>
                                    <p v-text="item.buyer"></p>
                                </div>
                                <div class="order_seller flex">
                                    <span>收件人：</span>
                                    <p v-text="item.receipter"></p>
                                </div>
                                <div class="order_phone flex">
                                    <span>手机号：</span>
                                    <p v-text="item.phone"></p>
                                </div>
                                <div class="order_ad flex">
                                    <span class="order_left">收货地址：</span>
                                    <p class="order_right" v-text="item.address"></p>
                                </div>
                            </div>
                            <div class="order_commission flex">
                                <div v-if="item.seller" class="o_c_item" v-text="'销售:'+item.seller"></div>
                                <div class="o_c_item" v-for="com in item.commissionLevelList">
                                    <span v-text="com.commissionLevel"></span> : <em v-text="'￥'+com.commission"></em>
                                </div>
                            </div>
                        </section>
                        <div class="loading_wrap" v-if="mescrollLoading">
                            <p class="loading"></p>
                            <p class="loading_tip">加载中...</p>
                        </div>
                        <div class="bottom_tip" v-if="!dataMore&&!isLoading&&(dataList.length >= 4)">没有更多了~</div>
                    </div>
                    <!-- 订单为空时 -->
                    <div class="order_empty" v-if="isEmpty">
                        <div class="empty_pic">
                            <img src="/images/order/order_empty.png" alt="">
                        </div>
                        <p>你还没有订单哦~</p>
                    </div>

                </div>
            </div>
        </div>
    </div>
    <div class="nav_more_list">
        <div class="triangle-up"></div>
        <ul>
            <li class="flex" onclick="location.href='<?= \Yii::$app->request->mallHomeUrl ?>'">
                <span class="icon">&#xe608;</span> <em>首页</em>
            </li>
            <li class="flex" onclick="location.href='<?= \yii\helpers\Url::toRoute(["/cart"], true) ?>'">
                <span class="icon">&#xe60a;</span> <em>购物车</em>
            </li>
            <li class="flex" onclick="location.href='<?= \yii\helpers\Url::toRoute(["/member"],
				true) ?>'">
                <span class="icon">&#xe64a;</span> <em>我的</em>
            </li>
        </ul>
    </div>

    <!-- 弹框提示 -->
    <div class="message_alert">
        <h2 class="alert_title">有商品退款中，无法确认收货</h2>
        <div class="alert_btn">知道了</div>
    </div>
    <div class="mask"></div>
    <div class="weui-loading_toast" style="display: none;">
        <div class="weui-mask_transparent"></div>
        <div class="weui-toast">
            <i class="weui-loading weui-icon_toast"></i>
        </div>
    </div>

<?php $this->beginBlock('foot') ?>
    <script src="/js/swiper.min.js"></script>
    <script src="/js/mescroll.min.js"></script>
    <script src="/js/LCalendar.js"></script>
    <script>
        var dataList = <?=$data?>;//后端数据
        console.log(dataList);
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
                var load = sessionStorage.getItem('orderLoad');
                if (load) {
                    location.reload();
                    sessionStorage.removeItem('orderLoad');
                }
            }

            $('.ad_more').on('click', function () {
                event.stopPropagation();
                $(".nav_more_list").toggle();
            })

            $('.mescroll').on('scroll', function () {
                $(".nav_more_list").hide();
            })

            $('body').on('click', function () {
                $(".nav_more_list").hide();
            })

            $('.connect_service').on('touchstart', function () {
                $(".nav_more_list").hide();
            })
            $('.alert_btn').on('click', function () {
                $('.message_alert').hide();
                $('.mask').hide();
            })

            $('.commodity_link').on('click', function () {
                sessionStorage.setItem('orderLoad', 'true');
            })
        })

        new Vue({
            el: "#app",
            data: {
                dataMore: dataList.isMore,//是否加载更多
                isSearchShow: false,//控制搜索框
                isEmpty: false,
                isLoading: false,
                orderId: 0,//订单ID
                type: '',
                currentType: '<?=$_GET['type']?>',//当前状态
                reqUrl: "<?=$this->context->action->id == 'sellerorderlist' ? '/wholesaler/sellerorderlist' : '/wholesaler/orderlist'?>",
                reqData: {
                    type: '<?=$_GET['type']?>',
                    index: 1,
                    SellerID:'<?=$_GET['SellerID']?>'
                },
                orderStatus: [
                    {
                        type: '',
                        text: '全部'
                    },
                    {
                        type: 'unpaid',
                        text: '待付款'
                    },
                    {
                        type: 'paid',
                        text: '待发货'
                    },
                    {
                        type: 'delivered',
                        text: '待收货'
                    },
                    {
                        type: 'success',
                        text: '已完成'
                    },
                    {
                        type: 'closed',
                        text: '已关闭'
                    }],
                dataList: dataList.data,
                searchEmpty: false, //搜索为空
                /*navShow: true, //导航展示*/
                navShow: <?=$this->context->action->id == 'sellerorderlist' ? 'false' : 'true'?>, //导航展示
                mescroll: null, //滚动对象
                mescrollLoading: false,//底部加载
            },
            mounted: function () {
                var _self = this;
                var mySwiper = new Swiper('.swiper-container', {
                    slidesPerView: 'auto',
                    spaceBetween: 0,
                })
                //初始化数据为空时
                _self.dataList && _self.dataList.length == 0 && (_self.isEmpty = true);

                //日历写法
                var calendar = new LCalendar();
                var calendar2 = new LCalendar();
                calendar.init({
                    'trigger': '#datepiker',//标签id
                    'type': 'date',//date 调出日期选择 datetime 调出日期时间选择 time 调出时间选择 ym 调出年月选择
                    'minDate': '1970-1-1',//最小日期 注意：该值会覆盖标签内定义的日期范围
                    'maxDate': '2050-12-1',//最大日期 注意：该值会覆盖标签内定义的日期范围
                    'closeFn': function (oInput) {
                        var dDeliverDate = document.getElementById('dDeliverDate');
                        dDeliverDate.value = oInput.value;
                        $('.show_date').html(oInput.value);
                        _self.calendar(oInput.value);
                    }
                });

                calendar2.init({
                    'trigger': '#datepiker2',//标签id
                    'type': 'date',//date 调出日期选择 datetime 调出日期时间选择 time 调出时间选择 ym 调出年月选择
                    'minDate': '1970-1-1',//最小日期 注意：该值会覆盖标签内定义的日期范围
                    'maxDate': '2050-12-1',//最大日期 注意：该值会覆盖标签内定义的日期范围
                    'closeFn': function (oInput) {
                        var dDeliverDate = document.getElementById('dDeliverDateEnd');
                        dDeliverDate.value = oInput.value;
                        $('.show_date2').html(oInput.value);

                        _self.calendar2(oInput.value);
                    }
                });

                /*calendar.init({
                    'trigger': '#datepiker2',//标签id
                    'type': 'ym',//date 调出日期选择 datetime 调出日期时间选择 time 调出时间选择 ym 调出年月选择
                    'minDate': '1970-1',//最小日期 注意：该值会覆盖标签内定义的日期范围
                    'maxDate': '2050-12',//最大日期 注意：该值会覆盖标签内定义的日期范围
                    'closeFn': function (oInput) {
                        var dDeliverDateEnd = document.getElementById('dDeliverDateEnd');
                        dDeliverDateEnd.value = oInput.value;
                        $('.show_date2').html(oInput.value);
                        alert(oInput.value);

                        _self.calendar.dDeliverDateEnd(oInput.value);
                    }
                });*/

                //判断页面一开始时为空时的处理
                var elem = document.getElementById("mescroll");
                if (elem) {
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
                }


            },
            methods: {
                //加载数据回调
                upCallback: function () {

                    this.reqData.index++;
                    if (!this.dataMore) { //判断是否有更多
                        this.mescroll.endSuccess();
                        return;
                    }
                    this.mescrollLoading = true;
                    this.getData(true);
                },
                //订单状态选项切换
                changeStatus: function (stu) {
                    var _this = this;

                    _this.currentType = stu;
                    //_this.reqData.type = stu == 'all' ? '' : stu;
                    _this.reqData.type = stu;
                    _this.reqData.index = 1;

                    window.history.replaceState(null, null, '?type=' + _this.reqData.type);

                    _this.dataMore = false;
                    _this.isLoading = true;

                    _this.mescroll.scrollTo(0, 0);
                    _this.getData();

                },
                calendar: function (val) {
                    this.reqData.dateStart = val;
                    this.getData();
                },
                calendar2: function (val) {
                    this.reqData.dateEnd = val;
                    this.getData();
                },

                //获取数据
                getData: function (me) {
                    var _this = this;
                    console.log(_this.reqData);
                    $.ajax({
                        type: 'get', //实际要用get
                        url: _this.reqUrl,
                        data: _this.reqData,
                        dataType: 'json',
                        success: function (res) {
                            var actionID = '<?=$this->context->action->id?>';
                            console.log(res.arrCountProfit);
                            if (actionID == 'sellerorderlist') {
                                var fSumIncome = '提成：¥' + res.arrCountProfit.fSellerProfit;
                            } else {
                                var fSumIncome = '提成：¥' + res.arrCountProfit.fWholesalerProfit;
                            }
                            
                            $('#fSumIncome').text(fSumIncome);
                            console.log(res);
                            if (me) {
                                //滚动情况
                                if (res && res.data && res.data.length !== 0) {
                                    _this.dataList = _this.dataList.concat(res.data);
                                    _this.dataMore = res.isMore;
                                }
                                if (!res.isMore) {
                                    _this.dataMore = res.isMore;
                                    _this.mescrollLoading = false;
                                }
                                _this.mescroll.endSuccess(); //数据加载完 状态处理

                            } else { //点击切换的情况
                                _this.isLoading = false;
                                if (res && res.data && res.data.length !== 0) {
                                    _this.dataList = res.data;
                                    _this.isEmpty = false;
                                    _this.dataMore = res.isMore;
                                    if (res.data.length <= 4) { //不足4条数据的情况
                                        _this.mescrollLoading = false;
                                    }

                                } else {
                                    _this.dataList = [];
                                    _this.isEmpty = true;

                                }

                                _this.mescroll.endSuccess(); //数据加载完 状态处理

                            }

                        },
                        error: function (xhr, type) {
                            _self.mescroll.endErr(); //失败后调整
                        }
                    });
                }
            }
        })

    </script>

<?php $this->endBlock() ?>