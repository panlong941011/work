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
		.order_list,.order_content,.order_wrap,.search_result {
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
		.order_content,.search_result {
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
		<div class="order_content" v-if="!isSearchShow&&nowPage == 'list' ">
			<div class="order_top">
				<div class="ad_header">
					<a href="javascript:;" onclick="goBack()" class="ad_back">
						<span class="icon">&#xe885;</span>
					</a>
					<div class="order_type flex">
						<span class="order_my" :class="{on: isTab == 'mine'}" @click="switchTab('mine')">我卖的</span>
						<span class="order_all" :class="{on: isTab == 'all'}" @click="switchTab('all')">全部</span>
					</div>
					<div class="search_order customer_search" @click="showPage"></div>
					<span class="ad_more icon" @click="showList($event)">&#xe602;</span>
				</div>
				<nav v-if="navShow">
					<div class="swiper-container">
						<div class="swiper-wrapper">
							<div class="swiper-slide" v-for="(status,index) in orderStatus" v-text='status.text'
								:class="{'active':status.type === sCurrentType}" @click="changeStatus(status.type)">全部
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
						<section class="list_layer order_item" v-for="item in dataList">
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
											<div class="prop">
												<p class="multiEllipsis"> {{goods.spec}}</p>
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
							<div class="order_info">
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
								<div class="o_c_item" v-text="'销售:'+item.seller"></div>
								<div class="o_c_item"  v-for="com in item.commissionLevelList">
									<span v-text="com.commissionLevel"></span> : <em v-text="'￥'+com.commission"></em>
								</div>
							</div>
						</section>
						<div class="loading_wrap" v-if="mescrollLoading">
							<p class="loading"></p>
							<p class="loading_tip">加载中...</p>
						</div>
						<div class="bottom_tip" v-if="!dataMore&&!isLoading&&(dataList.length >= 3)">没有更多了~</div>
					</div>
					
					<!-- 订单为空时 -->
					<div class="order_empty" v-if="isEmpty" >
						<div class="empty_pic">
							<img src="/images/order/order_empty.png" alt="">
						</div>
						<p>你还没有订单哦~</p>
					</div>
				
				</div>
			</div>
		</div>
		<!-- 搜索框组件 -->
		<search-part :partstatus="isSearchShow"  @status="returnStatus" @searchdata="returnData"></search-part>
		
		<!-- 搜索结果页 -->
		<div class="search_result" v-if="!isSearchShow&&nowPage == 'search'">
			<div class="ad_header">
				<a href="javascript:;" onclick="goBack()" class="ad_back">
					<span class="icon">&#xe885;</span>
				</a>
				<div class="s_input flex" @click="showPage">
					<span></span>
					<h2>收货人/手机号搜索订单</h2>
				</div>
				<span class="ad_more icon" @click="showList($event)">&#xe602;</span>
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
						<section class="list_layer order_item" v-for="item in dataList">
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
											<div class="prop">
												<p class="multiEllipsis"> {{goods.spec}}</p>
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
							<div class="order_info">
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
								<div class="o_c_item" v-text="'销售:'+item.seller"></div>
								
								<div class="o_c_item"  v-for="com in item.commissionLevelList">
									<span v-text="com.commissionLevel"></span> : <em v-text="'￥'+com.commission"></em>
								</div>
							</div>
						</section>
						
						
						<div class="bottom_tip" v-if="!dataMore">没有更多了~</div>
					
					</div>
				</div>
			</div>
		</div>
	
	</div>
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
        // console.log(dataList);
        //当前显示对应的页面结构
        var nowPage = '<?=isset($_GET['keyword']) ? "search" : "list" ?>';
        var keyword = '<?=$_GET['keyword']?>';

        //返回刷新
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
            //安卓刷新
            if( !isIOS() ) {
                var load = sessionStorage.getItem('orderLoad');
                if (load) {
                    location.reload();
                    sessionStorage.removeItem('orderLoad');
                }
            }

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

                    location.href = "<?=\yii\helpers\Url::toRoute([\Yii::$app->request->shopUrl . "/seller/orderlist"],true)?>?keyword=" + encodeURI(keys);

                    //搜索后输入清空 缓存加入
                    /*  this.keys = '';
					  this.init();*/

                },
                closePage: function() {

                    this.$emit('status', false);
                },
                goHref: function(val) {
                    location.href = "<?=\yii\helpers\Url::toRoute([\Yii::$app->request->shopUrl . "/seller/orderlist"],true)?>?keyword=" + encodeURI(val);
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
                dataMore: true,//是否加载更多
                isSearchShow: false,//控制搜索框
                isEmpty: false,
                isLoading: false,
                orderId: 0,//订单ID
                type: '',
                currentType: '<?=$_GET['range']?>',//当前大状态
                sCurrentType: '<?=$_GET['type']?>',//当前小状态
                reqUrl: '/seller/orderlistmore',
                reqData: {},
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
                dataList: dataList&&dataList.data,
                searchEmpty: false, //搜索为空
                navShow: true, //导航展示
                mescroll: null, //滚动对象
                mescrollLoading: false,//底部加载
                isTab: '',
                nowPage: nowPage,
            },
            mounted: function () {
                var _self = this;
                //轮播图
                var mySwiper = new Swiper('.swiper-container', {
                    slidesPerView: 'auto',
                    spaceBetween: 0,
                })
                //初始化数据为空时
                _self.dataList && _self.dataList.length == 0 && (_self.isEmpty = true);
                if(  _self.nowPage == 'search' && _self.dataList.length == 0 ) {
                    _self.searchEmpty = true;
                }

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
                }else {
                    _self.mescroll = {};
                }

                //页面初始化判断
                //我卖的和全部
                _self.isTab = _self.currentType == '' ? 'mine': 'all';

                //列表和搜索
                if( nowPage == 'list' ) {
                    var listData = {
                        type: '<?=$_GET['type']?>',
                        index: 1,
                        range:'<?=$_GET['range']?>'
                    };
                    _self.reqData = listData;
                }
                if(  nowPage == 'search' ) {
                    var searchData = {
                        type: '<?=$_GET['type']?>',
                        index: 1,
                        keyword: keyword,
                        range:''
                    };
                    _self.reqData = searchData;
                }


            },
            methods: {
                //加载数据回调
                upCallback: function() {

                    //console.log(this.dataMore);
                    if( !this.dataMore){ //判断是否有更多
                        this.mescroll.endSuccess();
                        return;
                    }
                    this.reqData.index++;

                    this.mescrollLoading = true;
                    this.getData(true);
                },
                //订单状态选项切换
                changeStatus: function (stu) {
                    var _this = this;
                    _this.sCurrentType = stu;
                    //_this.reqData.type = stu == 'all' ? '' : stu;
                    _this.reqData.type = stu;
                    _this.reqData.index = 1;
                    _this.dataMore = false;
                    _this.isLoading = true;
                    _this.mescroll.scrollTo( 0, 0 );

                    _this.getData();

                },
                getData: function (me) {
                    var _this = this;
                    var hash = $.param(_this.reqData);

                    window.history.replaceState(null,null,'?'+hash);
                    $.ajax({
                        type: 'get', //实际要用get
                        url: _this.reqUrl,
                        data: _this.reqData,
                        dataType: 'json',
                        success: function (res) {
                            // console.log(res);
                            if (me) {
                                //滚动情况
                                if (res && res.data && res.data.length !== 0) {
                                    _this.dataList = _this.dataList.concat(res.data);

                                } else {

                                    _this.dataMore = false;
                                    _this.mescrollLoading = false;
                                }
                                _this.mescroll.endSuccess(); //数据加载完 状态处理

                            } else { //点击切换的情况
                                _this.isLoading = false;
                                if (res && res.data && res.data.length !== 0) {
                                    _this.dataList = res.data;
                                    _this.isEmpty = false;
                                    _this.dataMore = true;
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
                //头部切换
                switchTab: function(val) {
                    var _this = this;
                    var range = '';
                    _this.isTab = val;

                    if( val == 'all' ) {

                        _this.reqData.range = '全部';
                    }else{

                        _this.reqData.range = '';
                    }

                    _this.reqData.index = 1;
                    _this.mescroll.scrollTo( 0, 0 ); //重置回到顶部
                    _this.dataMore = true;
                    _this.getData();

                },
                showList: function(event) {
                    event.stopPropagation();
                    $(".nav_more_list").toggle();
                },
            }
        })
	
	</script>

<?php $this->endBlock() ?>