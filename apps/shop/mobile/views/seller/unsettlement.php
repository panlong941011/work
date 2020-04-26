<?php $this->beginBlock('style') ?>
<link rel="stylesheet" href="/css/mescroll.min.css">
<link rel="stylesheet" href="/css/settlement.css?<?= \Yii::$app->request->sRandomKey ?>">
<link rel="stylesheet" href="/css/sw.css?<?= \Yii::$app->request->sRandomKey ?>">
<style type="text/css">
    body,html {
         height: 100%;
    }
    .wait_settlement {
        height: 100%;
    }
    .mescroll{
      position: fixed;
      left: 0;
      top:0;
      bottom: 0;
      height: auto;
      width: 100%;
      max-width: 16rem;
      margin:0 auto;
    }
    .mescroll-upwarp {
        display: none;
    }
</style>
<?php $this->endBlock() ?>
<div class="wait_settlement" id="app" v-cloak>
    <div id="mescroll" class="mescroll">
        <div class="mescroll-bounce">
            <div class="ad_header">
                <a href="javascript:;" class="ad_back" onclick="goBack()">
                    <span class="icon">&#xe885;</span>
                </a>
                <h2>待结算</h2>
                <span class="ad_more icon">&#xe602;</span>
            </div>
            <div class="wait_top">
                <h3 class="wait_income">待结算收入</h3>
                <div class="wait_amount">
                    <span>¥</span>
                    <em v-text="dataList.fUnSettlement"></em>
                </div>
                <div class="tag" @click="showCommission" v-text="commissionName">全部</div>
            </div>
            <section v-for="item in dataList.data">
                <div class="commission flex">
                    <i class="icon" :class="[ {level_one: item.level == 1}, {level_two: item.level == 2} ]" >&#xe630;</i>
                    <span class="commission_content" v-text="item.title"></span>
                </div>
                <div class="commission_item_wrap">
                    <a :href="goods.link" class="commission_item" v-for="goods in item.items">
                        <div class="commission_top flex">
                            <h3 class="commission_title singleEllipsis" v-text="goods.commodityName"></h3>
                            <em class="commission_amount" v-text="goods.commodityPrice"></em>
                        </div>
                        <div class="commission_bottom flex">
                            <p class="commission_time" v-text="goods.time"></p>
                            <span class="commission_status" v-text="goods.status"></span>
                        </div>
                    </a>
                </div>
            </section>
            <div class="order_empty empty_show" v-if="dataList.data.length==0">
                <div class="empty_pic">
                    <img src="/images/supplier/settlement.png">
                </div>
                <p>啊哦~您还没有待结算收入</p>
            </div>
            <div class="loading_wrap" v-if="mescrollLoading">
                <p class="loading"></p>
                <p class="loading_tip">加载中...</p>
            </div>
            <div class="bottom_tip" v-if="!dataMore&&fullData">没有更多数据了~</div>
        </div>
    </div>

    <commission-part :slectdata="commissionList" v-if="commissionShow" @selected="selectItem"
                     @partopt="closePart"></commission-part>
</div>

<div class="nav_more_list">
    <div class="triangle-up"></div>
    <ul>
        <li class="flex" onclick="location.href='<?= \Yii::$app->request->mallHomeUrl ?>'">
            <span class="icon">&#xe608;</span>
            <em>首页</em>
        </li>
        <li class="flex"
            onclick="location.href='<?= \yii\helpers\Url::toRoute(["/cart"], true) ?>'">
            <span class="icon">&#xe60a;</span>
            <em>购物车</em>
        </li>
        <li class="flex"
            onclick="location.href='<?= \yii\helpers\Url::toRoute(["/member"],
                true) ?>'">
            <span class="icon">&#xe64a;</span>
            <em>我的</em>
        </li>
    </ul>
</div>
<?php $this->beginBlock('foot') ?>
<script src="/js/mescroll.min.js"></script>
<script src="/js/template.js"></script>
<script>
    $(function() {

        $('.ad_more').on('click', function () {
            event.stopPropagation();
            $(".nav_more_list").toggle();
        })
        $(window).on('click', function () {
            $(".nav_more_list").hide();
        })
    })
</script>
<script>
    //初始化数据
    var data = <?=$sDataJson?>;
    console.log(data);

    new Vue({
        el: '#app',
        components: {
            'commission-part': commission
        },
        data: {
            commissionList: [{name:'全部',isMark: true},{name:'销售提成',isMark: false},{name:'一级团队提成',isMark: false},{name:'二级团队提成',isMark: false}],
            commissionShow: false,
            commissionName: '全部',
            dataList: data,
            mescroll: null, //滚动对象
            mescrollLoading: false,//底部加载
            page: 1,
            dataMore: true,
            fullData: true,//数据是否满屏
        },
        mounted: function() {
            var _self = this;

            if( _self.dataList.data.length < 4 ) {
                _self.fullData = false;
                _self.dataMore = false;
            }

            _self.mescroll = new MeScroll("mescroll", {
                 up: {
                     auto:false, //初始化不加载
                     callback: _self.upCallback, //上拉回调
                 },
                 down: {
                    use: false,
                 }
             });
             document.getElementById("app").style.display="block";
        },
        methods: {
            //回调
            upCallback: function() {
                var _this = this;
                
                if( !_this.dataMore){ //判断是否有更多
                    _this.mescroll.endSuccess();
                    return;
                }
                _this.page++;

                _this.mescrollLoading = true;
                
                $.get('/seller/unsettlementitem',
                    {
                        'page': _this.page,
                        'type': _this.commissionName
                    },
                    function(res) {
                         console.log(res);
                         if (res  && res.data && res.data.length !== 0) {
                              
                            _this.dataList.data = _this.dataList.data.concat(res.data);

                        } else {
                            _this.dataMore = false;
                            _this.mescrollLoading = false;
                        }

                         _this.mescroll.endSuccess();

                       
                },'json')
            },
            //显示提成选项
            showCommission: function() {
                this.commissionShow = true;
            },
            //切换
            selectItem: function(val) {
                var _this = this;
                _this.commissionName = val;

               $.get('/seller/unsettlementitem',
                    {
                        'page': 1,
                        'type': val
                    },
                    function(res) {
                       if( res  && res.data && res.data.length !== 0 ) {
                             _this.dataList = res;
                            //根据条数 判断是否显示更多结构
                             _this.fullData = _this.dataList.data.length < 4 ? false: true;
                             _this.dataMore = _this.dataList.data.length < 4 ? false: true;

                        }else{
                            _this.dataList = res;
                            _this.dataList.data = [];
                            _this.fullData = false;
                            _this.dataMore = false;
                        }
                        
                        console.log(res);
                },'json')

            },
            closePart: function(val) {
                this.commissionShow = val;
            }
        }

    })
</script>
<?php $this->endBlock() ?>
