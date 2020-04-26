<?php $this->beginBlock('style') ?>
    <link rel="stylesheet" href="/css/mescroll.min.css">
    <link rel="stylesheet" href="/css/settlement.css?<?= \Yii::$app->request->sRandomKey ?>">
    <link rel="stylesheet" href="/css/sw.css?<?= \Yii::$app->request->sRandomKey ?>">
    <link rel="stylesheet" href="/css/sw.css?<?= time() ?>">
<?php $this->endBlock() ?>
    <style type="text/css">
        body, html {
            height: 100%;
        }

        .customer_management {
            height: 100%;
        }

        .mescroll {
            position: fixed;
            left: 0;
            right: 0;
            bottom: 0;
            height: auto;
            width: 100%;
            max-width: 16rem;
            margin: 0 auto;
        }

        .mescroll-upwarp {
            display: none;
        }

        .customer_mescroll {
            top: 7.426667rem;
        }

        .customer_top {
            position: fixed;
            left: 0;
            top: 0;
            right: 0;
            width: 100%;
            z-index: 10;
            max-width: 16rem;
            margin: 0 auto;
        }
    </style>
    <div class="customer_management" id="app" v-cloak>
        <div class="customer_top">
            <div class="ad_header">
                <a href="javascript:;" class="ad_back" onclick="goBack()"> <span class="icon">&#xe885;</span> </a>
                <h2>经销商管理</h2>
            </div>
            <div class="c_m_top">
                <h3 class="c_m_title">
                    <i class="icon">&#xe723;</i> <span>人数</span>
                </h3>
                <div class="c_m_person">
                    <em v-text="dataList.userNumber"></em>
                </div>
            </div>
        </div>
        <div class="top-fill"></div>
        <!--  为空时 -->
        <div class="customer_empty" v-if="isEmpty">
            <div class="customer_pic">
                <img src="/images/supplier/customerBg.png">
            </div>
            <p>你还没有客户哦~</p>
            <a href="/seller/customerdesc">如何发展客户？</a>
        </div>
        <div class="customer_main" v-if="!isEmpty">

            <div id="mescroll" class="mescroll customer_mescroll">
                <div class="mescroll-bounce">
                    <ul class="customer_list">
                        <!--<a :href="item.link">  </a> TODO 链接还没添加-->
                        <li class="customer_item flex" v-for="item in dataList.userList">
                            <div class="customer_logo">
                                <a :href="item.link"><img :src="item.customerLogo"></a>
                            </div>
                            <div class="customer_info">
                                <a :href="item.link">
                                    <h2 v-text="item.customerName"></h2>
                                    <p v-text="'累计收入：¥'+item.consumption"></p>
                                    <p v-text="item.registerTime"></p>
                                </a>
                            </div>

                        </li>
                    </ul>
                    <div class="loading_wrap" v-if="mescrollLoading">
                        <p class="loading"></p>
                        <p class="loading_tip">加载中...</p>
                    </div>
                    <div class="bottom_tip" v-if="!dataMore&&fullData">没有更多数据了~</div>
                </div>
            </div>
        </div>
    </div>
<?php $this->beginBlock('foot') ?>
    <script src="/js/mescroll.min.js"></script>
    <script src="/js/template.js"></script>
    <script>
        var data =  <?=$sDataJson?> ;
        console.log(data);
        $(function () {
            $('.commodity_link').on('click', function () {
                sessionStorage.setItem('orderLoad', 'true');
            })
        })
        new Vue({
            el: '#app',
            components: {
                'commission-part': commission
            },
            data: {
                dataList: data,
                isFilter: 'time',
                isEmpty: false,

                mescroll: null, //滚动对象
                mescrollLoading: false,//底部加载
                dataMore: true,
                fullData: true,//数据是否满屏
                reqData: {
                    'orderby': '注册时间',
                    'orderbydir': 'up',
                    'type': '全部',
                    'page': '1'
                },
            },
            mounted: function () {
                var _self = this;
                //初始化判断是否为空 和是否满屏
                if (!_self.dataList.userList) {
                    _self.isEmpty = true;
                }
                if (_self.dataList.userList && _self.dataList.userList.length < 6) {
                    _self.dataMore = false;
                    _self.fullData = false;
                }
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
                //回调
                upCallback: function () {
                    var _this = this;
                    _this.page++;
                    if (!_this.dataMore) { //判断是否有更多
                        _this.mescroll.endSuccess();
                        return;
                    }
                    _this.mescrollLoading = true;

                    _this.reqData.page++;
                    _this.getData(true);
                },
                getData: function (bol) {
                    var _this = this;

                    $.ajax({
                        type: 'get', //实际要用get
                        url: '/wholesaler/mycustomeritem',
                        data: _this.reqData,
                        dataType: 'json',
                        success: function (res) {
                            console.log(res);
                            if (bol) {
                                //滚动情况
                                if (res && res.userList && res.userList.length !== 0) {
                                    _this.dataList.userList = _this.dataList.userList.concat(res.userList);

                                } else {
                                    _this.dataMore = false;
                                    _this.mescrollLoading = false;
                                }
                                _this.mescroll.endSuccess(); //数据加载完 状态处理


                            } else { //点击切换的情况

                                if (res && res.userList && res.userList.length !== 0) {
                                    _this.dataList = res;
                                    _this.isEmpty = false;

                                    if (res.userList.length < 6) { //不足4条数据的情况
                                        _this.mescrollLoading = false;
                                    } else {
                                        _this.dataMore = true;
                                        _this.fullData = true;
                                    }

                                } else {
                                    _this.dataMore = false;
                                    _this.dataList.userList = [];
                                    _this.isEmpty = true;

                                }

                                _this.mescroll.endSuccess(); //数据加载完 状态处理

                            }

                        },
                        error: function (xhr, type) {
                            _this.mescroll.endErr(); //失败后调整
                        }
                    });
                },
                //遮罩操作
                closePart: function (val) {
                    this.commissionShow = val;
                },
            }

        })


    </script>
<?php $this->endBlock() ?>