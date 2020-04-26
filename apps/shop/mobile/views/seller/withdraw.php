<?php $this->beginBlock('style') ?>
<link rel="stylesheet" href="/css/mescroll.min.css">
<link rel="stylesheet" href="/css/settlement.css?<?= \Yii::$app->request->sRandomKey ?>">
<?php $this->endBlock() ?>
<style type="text/css">
    body,html {
         height: 100%;
    }
    .withdraw_record {
        height: 100%;
    }
    .mescroll{
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
    .withdraw_top {
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        z-index: 10;
        width: 100%;
        max-width: 16rem;
        margin: 0 auto;
    }
</style>
<div class="withdraw_record" id="app" v-cloak>
    <div class="withdraw_top">
        <div class="ad_header">
            <a href="javascript:;" class="ad_back" onclick="goBack()">
                <span class="icon">&#xe885;</span>
            </a>
            <h2>提现记录</h2>
        </div>
        <div class="w_r_opt flex">
            <span class="opt_item" :class="{active : item.isMark}" 
            v-for="(item,index) in choseList" 
            v-text="item.name"
            @click="switchTab(index)"></span>
        </div>
    </div>
        <!-- 为空 -->
        <div class="record_empty" v-if="isDetailShow">
            <div class="record_pic">
                <img src="/images/supplier/record.png">
            </div>
            <p class="d_e_tip">您还没有提现记录</p>
        </div>
        <div id="mescroll" class="mescroll withdraw_mescroll">
        <div class="mescroll-bounce">
            <div class="withdraw_record_main" v-if="!isDetailShow">
                
                <ul class="w_r_list">
                    <li class="w_r_item" v-for="item in dataList">
                        <a :href="item.link">
                            <div class="w_r_top flex">
                                <h2 v-text="item.title">申请提现</h2>
                                <em v-text="'￥'+item.price"></em>
                            </div>
                            <div class="w_r_bottom flex">
                                <p v-text="item.time"></p>
                                <span class="withdrawing" v-if="item.status == '提现中'" v-text="item.status"></span>
                                <span class="withdrawed"  v-if="item.status == '已提现'" v-text="item.status">已提现</span>
                            </div>
                        </a>
                    </li>
                </ul>
            </div>
            <div class="loading_wrap" v-if="mescrollLoading">
                <p class="loading"></p>
                <p class="loading_tip">加载中...</p>
            </div>
            <div class="bottom_tip" v-if="!dataMore&&fullData">没有更多数据了~</div>
        </div>
        </div>
</div>

<?php $this->beginBlock('foot') ?>
<script src="/js/mescroll.min.js"></script>
<script>
    var dataList = <?=$sDataJson?>;
    console.log(dataList);
    new Vue({
        el: '#app',
        data: {
            choseList: [{name:'全部',isMark: false},{name:'提现中',isMark: false},{name:'已提现',isMark: true}],
            dataList: dataList,
            isDetailShow: false,
            reqData: {
                'type': '全部',
                'page': 1
            },
            mescroll: null, //滚动对象
            mescrollLoading: false,//底部加载
            dataMore: true,
            fullData: true,
        },
        mounted: function() {
            var _self = this;
            //根据数据多少显示对应的结构
            if( _self.dataList.length == 0 ) {
                _self.isDetailShow = true;
            }

            if( _self.dataList.length <= 7 ) {
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
                _this.reqData.page++;

                if( !_this.dataMore){ //判断是否有更多
                    _this.mescroll.endSuccess();
                    return;
                }
                _this.mescrollLoading = true;

                _this.getData(true);

            },
            switchTab: function(index) {
                var length = this.choseList.length;
                for( var i = 0;i < length; i++ ) {
                    this.choseList[i].isMark = false;
                }
                this.choseList[index].isMark = true;

                this.reqData.type = this.choseList[index].name;
                this.reqData.page = 1;

                this.getData();
            },
            getData: function(bol) {
                var _this = this;

                  $.ajax({
                    type: 'GET',
                    url: '/seller/withdrawitem',
                    data: _this.reqData,
                    dataType: 'json',
                    success: function (res) {
                       console.log(res);

                        if ( bol ) { 
                            //滚动情况
                            if (res && res.length !== 0) {
                              
                                _this.dataList = _this.dataList.concat(res);

                            } else {
                                _this.dataMore = false;
                                _this.mescrollLoading = false;
                            }
                            _this.mescroll.endSuccess(); //数据加载完 状态处理

                        } else { //点击情况

                            if (res && res.length !== 0) {
                                
                                _this.isDetailShow = false;

                                _this.dataList = res;

                                if(res.length < 7) { //不足4条数据的情况禁止滚动
                                     _this.mescrollLoading = false;
                                }else { //否则放开

                                    _this.fullData = true;
                                    _this.dataMore = true;
                                }

                            } else {
                                _this.dataList = [];
                                _this.isDetailShow = true;
                                _this.dataMore = false;

                            }
                            _this.mescroll.endSuccess();
                         
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