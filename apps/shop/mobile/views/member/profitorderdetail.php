<?php $this->beginBlock('style') ?>
    <link rel="stylesheet" href="/css/mescroll.min.css">
    <link rel="stylesheet" href="/css/order.css?<?=\Yii::$app->request->sRandomKey?>">
    <link rel="stylesheet" href="/css/swiper.min.css">
    <style>




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

        .s_input {
            width: 11.7333333333rem;
            height: 1.28rem;
            padding: 0.32rem 0.4266666667rem;
            background: #eee;
            -webkit-border-radius: 0.2133333333rem;
            border-radius: 0.2133333333rem;
            margin: 0.4266666667rem auto;
            text-align: center;
            font-size: 36px;
        }
        .list_layer .layer_title{
            height: 0.8rem;
            line-height: 0.8rem;
        }
        section{
            width: 96%;
            margin-left: 2%;
            border-radius: 2rem;
            margin-top: 0.5rem;
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
                     <div class="s_input ">
                        收益明细
                    </div>
                    <span class="ad_more icon">&#xe602;</span>
                </div>
            </div>

            <div class="order_list">
                <div id="mescroll" class="mescroll">
                <div class="mescroll-bounce" v-if="dataList.length!== 0">
                    <section class="list_layer" v-for="item in dataList">
                        <a href="javascript:;">
                            <h3 class="layer_title ">
                                <span class="singleEllipsis" v-text="item.sName"></span>
                            </h3>
                            <h3 class="layer_title ">
                                <span class="singleEllipsis" v-text="item.dPaydate"></span>
                            </h3>
                            <h3 class="layer_title ">
                                <span class="singleEllipsis" v-text="item.buyer"></span>
                            </h3>
                            <h3 class="layer_title ">
                                <span class="singleEllipsis" v-text="item.seller"></span>
                            </h3>
                            <h3 class="layer_title ">
                                <span class="singleEllipsis" v-text="item.fSumOrder"></span>
                            </h3>
                        </a>

                    </section>
                    <div class="bottom_tip" v-if="!dataMore">没有更多了~</div>
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
        <!-- 搜索框组件 -->

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
                reqUrl: '/member/profitdetail',
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
                        text: '待确认'
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
            }
        })
    </script>

<?php $this->endBlock() ?>

