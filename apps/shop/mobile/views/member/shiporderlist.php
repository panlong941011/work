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

        .loading_wrap {
            background: #eee;
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
    </style>
<?php $this->endBlock() ?>
    <div class="order_wrap" id="app" v-cloak>
        <div class="order_content" v-if="!isSearchShow">
            <div class="order_top">
                <div class="ad_header">
                    <a href="javascript:;" onclick="goBack()" class="ad_back">
                        <span class="icon">&#xe885;</span>
                    </a>
                     <div class="s_input">
                        发货提醒
                    </div>
                    <span class="ad_more icon">&#xe602;</span>
                </div>
            </div>

            <div class="order_list">
                <div id="mescroll" class="mescroll">
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
                                            {{goods.receiver}}
                                        </div>
                                        <div class="prop multiEllipsis">
                                            {{goods.sMobile}}
                                        </div>
                                        <div class="prop multiEllipsis">
                                            {{goods.sAddress}}
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
                            </div>
                        </div>
                    </section>
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
                reqUrl: '/member/orderlist',
                reqData: {
                    type: '<?=$_GET['type']?>',
                    index: 1
                },
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
                }
            }
        })
    </script>

<?php $this->endBlock() ?>

