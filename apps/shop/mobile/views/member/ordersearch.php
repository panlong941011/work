<?php $this->beginBlock('style') ?> 
	<link rel="stylesheet" href="/css/mescroll.min.css">
	<link rel="stylesheet" href="/css/order.css?<?=\Yii::$app->request->sRandomKey?>">
	<style>
		 .mescroll{
	      position: fixed;
	      bottom: 0;
	      height: auto;
	      width: 100%;
	      max-width: 16rem;
	      margin:0 auto;
	    }
	    .order_list,.order_content,.order_wrap {
	    	height: 100%;
	    }
	    .order_content {
	    	position: relative;
	    }
	    .ad_header {
	    	position: absolute;
	    	left: 0;
	    	top: 0;
	    	width: 100%;
	    	z-index: 10;
	    }
        .mescroll-upwarp {
            display: none;
        }
    </style>
<?php $this->endBlock() ?>

<div class="order_wrap" id="app" v-cloak>
    <div class="order_content" v-if="!isSearchShow">
        <div class="ad_header">
            <a href="javascript:;" onclick="goBack()" class="ad_back">
                <span class="icon">&#xe885;</span>
            </a>
             <div class="s_input flex" @click="showPage">
                <span></span>
                <h2><?=Yii::$app->request->get("keyword")?Yii::$app->request->get("keyword"):"收货人/手机号搜索订单"?></h2>
            </div>
            <span class="ad_more icon">&#xe602;</span>
        </div>
        <!-- 搜索为空时 -->
        <div class="order_empty empty_show" v-if="searchEmpty">
            <div class="empty_pic">
                <img src="/images/list_empty.png" alt="">
            </div>
            <p>啊哦~没有搜到相关订单</p>
        </div>
        <div class="order_list" v-if="dataList.length!== 0">
            <div id="mescroll" class="mescroll">
            <div class="mescroll-bounce">
	            <section class="list_layer s_list" v-for="item in dataList">
	                <a :href="item.link">
	                    <h3 class="layer_title ">
	                        <span class="title_word singleEllipsis" v-text="item.shopname"></span>
	                        <i class="title_arrow"></i>
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
	                                    <em v-text="'￥'+goods.price"></em>
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
	                        <a href="javascript:;" @click="pay(item.order_id)" class="pay_for">付款</a>
	                    </div>
	                    <div class="message layer flex" v-if="item.status == '已发货'">
	                        <a href="javascript:;" @click="trace(item.order_id)" class="connect_service">查看物流</a>
	                        <a href="javascript:;" class="pay_for" @click="confirmReceive(item.order_id)">确认收货</a>
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
                <div class="bottom_tip" v-if="!dataMore">没有更多了~</div>

	        </div>
	        </div>
        </div>
    </div>
    <!-- 搜索框组件 -->
    <search-part :partstatus="isSearchShow"  @status="returnStatus" @searchdata="returnData"></search-part>
</div>
<!-- 更多栏目 -->
<div class="nav_more_list">
    <div class="triangle-up"></div>
    <ul>
        <li class="flex"  onclick="location.href='<?= \Yii::$app->request->mallHomeUrl ?>'">
            <span class="icon">&#xe608;</span>
            <em>首页</em>
        </li>
        <li class="flex" onclick="location.href='<?= \yii\helpers\Url::toRoute(["/cart"], true) ?>'">
            <span class="icon">&#xe60a;</span>
            <em>购物车</em>
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
                var load = sessionStorage.getItem('s_orderLoad');
                if (load) {
                    location.reload();
                    sessionStorage.removeItem('s_orderLoad');
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
                 sessionStorage.setItem('s_orderLoad','true');
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
                                '<div class="search_btn" @click="search">搜索</div>'+
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
                orderId: 0,//订单ID
                type: '',
                currentType: '<?=$_GET['type']?>',//当前状态
                reqUrl: '/member/orderlistmore',
                reqData: {
                    type: '<?=$_GET['keyword']?>',
                    index: 1
                },
                dataList: dataList.data,
                searchEmpty: false, //搜索为空
                mescroll: null, //滚动对象
                mescrollLoading: false,
            },
            mounted: function () {
                var _self = this;
                var mySwiper = new Swiper('.swiper-container', {
                    slidesPerView: 'auto',
                    spaceBetween: 0,
                })
                //初始化数据为空时
                _self.dataList && _self.dataList.length == 0 && (_self.searchEmpty = true);

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
            },
            methods: {
            	//滚动回调
            	upCallback: function() {	
            		this.reqData.index++;

	                if( !this.dataMore){ //判断是否有更多
	                    this.mescroll.endSuccess();
	                    return;
	                }
                    this.mescrollLoading = true;
	                this.getData();

            	},
            	//获取数据
                getData: function () {
                   var _this = this;
                   $.ajax({
                        type: 'get', //实际要用get
                        url: _this.reqUrl,
                        data: _this.reqData,
                        dataType: 'json',
                        success: function (res) {
                            //滚动情况
                            if (res && res.data && res.data.length !== 0) {
                                _this.dataList = _this.dataList.concat(res.data);
                                _this.dataMore = res.isMore;
                            } else {
                                _this.dataMore = res.isMore;
                            }

                             _this.mescrollLoading = false;
                            _this.mescroll.endSuccess(); //数据加载完 状态处理
                        },
                        error: function (xhr, type) {
                           _this.mescroll.endErr(); //失败后调整
                        }
                    });
                },
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
                }
            }
        })

    </script>

<? if (\Yii::$app->params['isWeChat'] && Yii::$app->request->userIP != '127.0.0.1') { ?>
    <script src="https://res.wx.qq.com/open/js/jweixin-1.0.0.js" type="text/javascript" charset="utf-8"></script>
    <script type="text/javascript" charset="utf-8">
        wx.config(<?php echo \Yii::$app->wechat->js->config(['chooseWXPay']) ?>);
    </script>
<? } ?>
<?php $this->endBlock() ?>