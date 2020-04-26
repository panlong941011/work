<?php $this->beginBlock('style') ?>
    <link rel="stylesheet" href="/css/mescroll.min.css">
    <link rel="stylesheet" href="/css/order.css?<?=\Yii::$app->request->sRandomKey?>">
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
        .order_list,.order_content,.order_wrap {
            height: 100%;
        }
        .mescroll{
          position: fixed;
          bottom: 0;
          height: auto;
          width: 100%;
          max-width: 16rem;
          margin:0 auto;
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
    </style>
<?php $this->endBlock() ?>
    <div class="order_wrap" id="app" v-cloak>
        <div class="order_content" v-if="!isSearchShow">
            <div class="order_top">
                <div class="ad_header">
                    <a href="javascript:;" onclick="goBack()" class="ad_back">
                        <span class="icon">&#xe885;</span>
                    </a>
                     <div class="s_input flex" @click="showPage">
                        <span></span>
                        <h2>收货人/手机号搜索订单</h2>
                    </div>
                    <span class="ad_more icon">&#xe602;</span>
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
                        <a href="javascript:;">
                            <h3 class="layer_title ">
                                <span class="title_word singleEllipsis" v-text="item.sName"></span>
                                <span class="pay_status" v-text="item.status"></span>
                            </h3>
                        </a>
                        <div class="list_content" v-for="goods in item.commodity">
                            <a :href="goods.link" class="commodity_link">
                                <div class="list_item flex">
                                    <div class="pic"><img :src="goods.images" alt=""></div>
                                    <div class="info">
                                        <h4 class="title multiEllipsis" v-text="goods.title"></h4>
                                        <div class="prop multiEllipsis">
                                            {{goods.spec}}
                                            <i class="num" v-text="'X'+goods.num"></i>
                                        </div>
                                        <div class="price flex">
                                            <em v-text="'￥'+goods.price"></em></em>
                                            <span v-if="goods.refund_status" v-text="goods.refund_status"></span>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <div class="list_footer">
                            <div class="total layer flex">
                                <div class="all_product">
                                    共<i v-text="item.allNum"> </i>件商品
                                </div>
                                <div class="total_price">合计: <em v-text="'￥'+item.all"></em></div>
                                <div class="freight">(含运费 <em v-text="item.freight"></em>)</div>
                            </div>
                            <div class="message layer flex" v-if="item.status == '未付款'">
                                <a href="tel:<?= \myerm\shop\common\models\MallConfig::getValueByKey('sServiceNum') ?>"
                                   class="connect_service">联系客服</a>
                                <a :href="'javascript:;'" class="pay_for" v-if="item.OrderType == '1'" @click="checkout(item.order_id,item.sName,item.all)">付款</a>
                            </div>
                            <div class="message layer flex" v-if="item.status == '已发货'">
                                <a href="javascript:;" @click="trace(item.order_id)" class="connect_service">查看物流</a>
                                <a href="javascript:;" v-if="item.bself=='1'" class="pay_for" @click="confirmReceive(item.order_id)">确认收货</a>
                            </div>
                            <div class="message layer flex" v-if="item.status == '付款异常'">
                                <a href="" class="connect_service">联系客服</a>
                            </div>
                            <div class="message layer flex" v-if="item.shipped&&item.status !== '已发货'">
                                <a href="javascript:;" @click="trace(item.order_id)" class="connect_service">查看物流</a>
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
                        <img src="/images/order/search.png" alt="">
                    </div>
                    <p>(⊙o⊙)啊哦，没有更多订单啦~</p>
                </div>

                </div>
            </div>
        </div>
        <!-- 搜索框组件 -->
        <search-part :partstatus="isSearchShow"  @status="returnStatus" @searchdata="returnData"></search-part>
    </div>
    <div class="nav_more_list">
        <div class="triangle-up"></div>
        <ul>
            <li class="flex"  onclick="location.href='<?= \Yii::$app->request->mallHomeUrl ?>'">
                <span class="icon">&#xe608;</span>
                <em>首页</em>
            </li>
            <li class="flex" onclick="location.href='<?= \yii\helpers\Url::toRoute(["/member"],
                true) ?>'">
                <span class="icon">&#xe64a;</span>
                <em>我的</em>
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
<script type="text/javascript" src="https://res.wx.qq.com/open/js/jweixin-1.3.2.js"></script>
<script>
    function checkout(id,sTradeno,fPaid) {
        var ua = window.navigator.userAgent.toLowerCase();
        if (ua.match(/MicroMessenger/i) == 'micromessenger') {
            //判断是否是微信环境
            //微信环境
            wx.miniProgram.getEnv(function (res) {
                if (res.miniprogram) {
                    // 小程序环境下逻辑
                    wx.miniProgram.navigateTo({
                        url: '/pages/pay/pay?sTradeNo=' + sTradeno + '&fPaid=' + fPaid
                    });
                } else {
                    //非小程序环境下逻辑
                    location.href = "https://yl.aiyolian.cn/cart/cashier?no="+id;
                }
            })
        }
    }
</script>
<?php $this->beginBlock('foot') ?>
    <script src="/js/swiper.min.js"></script>
    <script src="/js/mescroll.min.js"></script>

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
           if( !isIOS() ) {
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

           $('.commodity_link').on('click',function() {
                 sessionStorage.setItem('orderLoad','true');
            })
        })
        //搜索框组件
        var searchPart = Vue.extend({
            template:  '<div class="search_wrap" v-if="isShow">'+
                            '<div class="search_header flex">'+
                                '<div class="s_close_wrap" @click="closePage">'+
                                    '<span class="s_close icon">&#xe885;</span>'+
                                '</div>'+
                               ' <div class="search_input flex">'+
                                   ' <span></span>'+
                                   ' <input type="text" placeholder="收货人/手机号搜索订单" v-model="keys">'+
                               ' </div>'+
                                '<div class="search_btn" @click="search" style="color:#E0B991">搜索</div>'+
                           ' </div>'+
                           '<div class="search_content">'+
                                '<div class="history_search">'+
                                '<div class="h_s_header flex" v-if="optShow">'+
                                    '<span class="h_s_name">历史搜索</span>'+
                                    '<div class="del_icon" @click="clearHistory">'+
                                        '<span class="h_s_icon"></span>'+
                                    '</div>'+
                                '</div>'+
                                '<div class="h_s_content">'+
                                   ' <a href="javaScript:;" v-for="item in keyAttr" @click="goHref(item)">{{item}}</a>'+    
                                '</div>'+
                               '</div>'+
                            '</div>'+
                        '</div>',
            props: ['partstatus'],
            data:function() {
                return {
                    isShow: this.partstatus,
                    keys: '',
                    cache: false,
                    keyAttr: [],
                    optShow: true,
                }
            },
            watch: {
                partstatus: function () {
                    this.isShow = this.partstatus;
                }
            },
            mounted: function() {
                this.init();
            },
            methods: {
                //初始化页面缓存
                init:function() {
                    var local = localStorage.getItem('keyWordArr');
                    if (local) {
                        this.keyAttr = local.split(',');
                        this.optShow = true;
                    } else {
                       this.optShow = false;
                        this.keyAttr = [];
                    }

                },
                //搜索
                search: function() {
                    var keys = this.keys.replace(/\s+/g,"");
                   /* if( this.keys == '' ) {
                         shoperm.showTip('输入收货人或订单号');
                    }*/
                    //设置缓存
                    if (keys !== '') {
                        var sWords = localStorage.getItem('keyWordArr'), //获取缓存值
                            searchWordArr = [];

                        if (!sWords) { //判断没有的话 新建
                            searchWordArr.unshift(keys);
                            localStorage.setItem("keyWordArr", searchWordArr);
                        } else { //有的话 分割字符串 重新组装
                            searchWordArr = sWords.split(',');
                            var isEqual = searchWordArr.every(function (item, index) {
                                return item !== keys;
                            })

                            if (isEqual) {
                                searchWordArr.unshift(keys);
                                localStorage.setItem("keyWordArr", searchWordArr);
                            }
                        }
                    }else {
                        
                    }
                   
                    location.href = "<?=\yii\helpers\Url::toRoute([\Yii::$app->request->shopUrl . "/member/ordersearch"],true)?>?keyword=" + encodeURI(keys);

                    //搜索后输入清空 缓存加入
                  /*  this.keys = '';
                    this.init();*/

                },
                closePage: function() {
                    
                    this.$emit('status', false);
                },
                goHref: function(val) {
                    location.href = "<?=\yii\helpers\Url::toRoute([\Yii::$app->request->shopUrl . "/member/ordersearch"],true)?>?keyword=" + encodeURI(val);
                },
                //清除缓存
                clearHistory: function() {
                    shoperm.selection('是否清除缓存',this.clear,this.cancel);
                    $('.mask').show();
                },
                clear: function() {
                     localStorage.removeItem("keyWordArr");
                     $('.mask').hide();
                     this.init();
                },
                cancel: function() {
                    $('.mask').hide();
                }
            }
        })

        new Vue({
            el: "#app",
            components: {
                'search-part': searchPart
            },
            data: {
                dataMore: dataList.isMore,//是否加载更多
                isSearchShow: false,//控制搜索框
                isEmpty: false,
                isLoading: false,
                orderId: 0,//订单ID
                type: '',
                currentType: '<?=$_GET['type']?>',//当前状态
                reqUrl: '/member/orderlist',
                reqData: {
                    type: '<?=$_GET['type']?>',
                    index: 1
                },
                orderStatus: [
                    {
                        type: '',
                        text: '全部'
                    },
                    {
                        type: 'unpaid',
                        text: '未付款'
                    },
                    {
                        type: 'paid',
                        text: '未发货'
                    },
                    {
                        type: 'delivered',
                        text: '未收货'
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
                navShow: true, //导航展示
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

                //判断页面一开始时为空时的处理
                var elem =  document.getElementById("mescroll");
                if( elem ) {
                     _self.mescroll = new MeScroll("mescroll", {
                        up: {
                            auto:false, //初始化不加载
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
                upCallback: function() {
                     
                    this.reqData.index++;
                    if( !this.dataMore){ //判断是否有更多
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

                    window.history.replaceState(null,null,'?type='+_this.reqData.type);

                    _this.dataMore = false;
                    _this.isLoading = true;

                    _this.mescroll.scrollTo( 0, 0 );
                    _this.getData();

                },
                //获取数据
                getData: function (me) {
                    var _this = this;
                   
                   $.ajax({
                        type: 'get', //实际要用get
                        url: _this.reqUrl,
                        data: _this.reqData,
                        dataType: 'json',
                        success: function (res) {
                             console.log(res);
                            if (me) {
                                //滚动情况
                                if (res && res.data && res.data.length !== 0 ) {
                                    _this.dataList = _this.dataList.concat(res.data);
                                    _this.dataMore = res.isMore;
                                } 
                                if( !res.isMore ) {
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
                                    if(res.data.length <= 4) { //不足4条数据的情况
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
                },
                //收货弹窗
                confirmReceive: function (order_id) {
                    var _this = this;
                    _this.orderId = order_id;
                    $('.mask').show();
                    shoperm.selection('是否确认收货',_this.sureReceive,_this.cancelReceive);                
                },
                sureReceive: function() {
                    var _this = this;
                    var order_id = _this.orderId
                    $(".weui-loading_toast").show();
                    $.post
                    (
                        '/member/confirmreceive?id='+order_id,
                        {_csrf:'<?= \Yii::$app->request->getCsrfToken() ?>'},
                        function (data) {
                            $(".weui-loading_toast").hide();
                            if (!data.status) {
                                $(".alert_title").html(data.message);
                                $('.message_alert').show();
                                $('.mask').show();
                            } else {
                                _this.reqData.type='delivered';
                                _this.getData();
                                $('.mask').hide();
                            }
                        }
                    )
                },
                cancelReceive: function() {
                     $('.mask').hide();
                },
                /**
                 * 去付款
                 */
                pay: function (order_id) {
                    $(".weui-loading_toast").show();
                    $.post
                    (
                        '/member/wxpay?id=' + order_id,
                        {_csrf:'<?= \Yii::$app->request->getCsrfToken() ?>'},
                        function (data) {
                            $(".weui-loading_toast").hide();
                            if (data.status) {
                                var config = data.config;
                                wx.chooseWXPay({
                                    timestamp: config.timestamp,
                                    nonceStr: config.nonceStr,
                                    package: config.package,
                                    signType: config.signType,
                                    paySign: config.paySign, // 支付签名
                                    success: function (res) {
                                        if (res.errMsg == "chooseWXPay:ok") {
                                            location.reload();
                                        } else {
                                            shoperm.showTip(res.errMsg);
                                        }
                                    },
                                    cancel: function (res) {
                                    }
                                });
                            } else {
                                shoperm.showTip(data.message);
                            }
                        }
                    )
                },
                /** 查看物流
                 *
                 */
                trace: function (order_id) {
                    location.href = "/member/trace?id=" + order_id
                },
                //显示搜索栏
                showPage: function() {
                    this.isSearchShow = true;
                },
                //搜索返回值
                returnData: function(data) {
                    
                },
                returnStatus: function(val) {
                    this.isSearchShow = val;
                },
                //取消订单
                cancelOrder:function(order_id){
                    var _this = this;
                    _this.orderId = order_id;
                    $('.mask').show();
                    shoperm.selection('是否确认取消订单',_this.sureCancel,_this.cancelCancel);
                },
                sureCancel: function() {
                    var _this = this;
                    var order_id = _this.orderId;
                    $(".weui-loading_toast").show();
                    $.post
                    (
                        '/member/cancelorder?id='+order_id,
                        {_csrf:'<?= \Yii::$app->request->getCsrfToken() ?>'},
                        function (data) {
                            $(".weui-loading_toast").hide();
                            if (!data.status) {
                                $(".alert_title").html(data.message);
                                $('.message_alert').show();
                                $('.mask').show();
                            } else {
                                _this.reqData.type='unpaid';
                                _this.getData();
                                $('.mask').hide();
                            }
                        }
                    )
                },
                cancelCancel: function() {
                    $('.mask').hide();
                },
            }
        })
    </script>

<?php $this->endBlock() ?>

