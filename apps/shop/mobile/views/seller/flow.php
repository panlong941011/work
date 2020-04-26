<?php $this->beginBlock('style') ?>
<link rel="stylesheet" type="text/css" href="/css/LCalendar.css">
<link rel="stylesheet" href="/css/mescroll.min.css">
<link rel="stylesheet" href="/css/settlement.css?<?= \Yii::$app->request->sRandomKey ?>">
<?php $this->endBlock() ?>
<style type="text/css">
    body,html {
         height: 100%;
    }
    .payments {
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
    .payments_top {
        position: fixed;
       left: 0;
       right: 0;
       top:0;
       bottom: 0;
       height: auto;
       width: 100%;
       max-width: 16rem;
       margin: 0 auto;
    }
</style>
<div class="payments" id="app" v-cloak>
        <div class="payments_top">
            <div class="ad_header">
                <a href="javascript:;" class="ad_back" onclick="goBack()">
                    <span class="icon">&#xe885;</span>
                </a>
                <h2>收支明细</h2>
                <a href="/seller/flowdesc" class="ad_more icon more_link">&#xe670;</a>
            </div>
            <div class="calendar flex">
                <div class="date_time flex" id="datepiker">
                    <span class="show_date">全部</span>
                    <input id="dDeliverDate" hidden type="text" class="date_input" readonly name="dDeliverDate" placeholder="全部"/>
                    <i class="icon">&#xe64e;</i>
                    <div class="date_arrow"></div>
                </div>
                <em>¥<?=number_format($seller->fSumIncome, 2)?></em>
            </div>
            <ul class="top_list flex">
                <li class="top_item" :class='{active: item.isMark}' v-for="(item,index) in choseList"
                    v-text="item.name"
                    @click="choseItem(index)"
                >全部</li>
            </ul>
        </div>
        <!-- 为空 -->
        <div class="detial_empty" v-if="isDetailShow">
            <div class="detail_pic">
                <img src="/images/supplier/detail.png">
            </div>
            <p class="d_e_tip">您还没有相关明细</p>
            <a href="/seller/flowdesc" class="d_e_link">收支明细说明</a>
        </div>

        <div id="mescroll" class="mescroll payments_mescroll">
        <div class="mescroll-bounce">

        <div class="detailed_content" v-if="!isDetailShow">
            <ul>
                <li class="detailed_item" v-for="item in dataList">
                    <a :href="item.link">
                        <div class="detailed_top flex">
                            <h2 class="singleEllipsis" v-text="item.orderTitle"></h2>
                            <em  v-if="item.contrast == 'up'" v-text="item.commission"></em>
                            <em class="down" v-if="item.contrast == 'down'" v-text="item.commission"></em>
                        </div>
                        <div class="detailed_bottom flex">
                            <p v-text="item.orderTime">2017-02-21 17:13：15</p>
                            <span v-text="item.orderType">销售提成</span>
                        </div>
                    </a>
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
<script src="/js/LCalendar.js"></script>
<script>
    $(function() {
        $('body').on('click','.gearDate',function() {
            $('.gearDate').remove(); 
        })
        $('body').on('click','.date_ctrl',function(event) {
             event.stopPropagation();
        })
    })
</script>
<script>
    var data = <?=$sDataJson?>;
    console.log(data);
    new Vue({
        el: '#app',
        data: {
            choseList: [{name:'全部',isMark: false},{name:'收入',isMark: true},{name:'支出',isMark: false}],
            dataList: data,
            isDetailShow: false,
            reqData: {
                'type': '全部',
                'date': '',
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

            if( _self.dataList.length < 7 ) {
                _self.fullData = false;
                _self.dataMore = false;
            }

            //日历写法
            var calendar = new LCalendar();
            calendar.init({
                'trigger': '#datepiker',//标签id
                'type': 'ym',//date 调出日期选择 datetime 调出日期时间选择 time 调出时间选择 ym 调出年月选择
                'minDate': '1970-1',//最小日期 注意：该值会覆盖标签内定义的日期范围
                'maxDate': '2050-12',//最大日期 注意：该值会覆盖标签内定义的日期范围
                'closeFn': function (oInput) {
                    var dDeliverDate = document.getElementById('dDeliverDate');
                    dDeliverDate.value = oInput.value;
                    $('.show_date').html(oInput.value);

                    _self.calendar(oInput.value);
                }
            });

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
                _this.reqData.page++;

                _this.mescrollLoading = true;

                _this.getData(true);

            },
            choseItem: function(index) {
                var length = this.choseList.length;
                for( var i = 0;i < length; i++ ) {
                    this.choseList[i].isMark = false;
                }
                this.choseList[index].isMark = true;

                this.reqData.type = this.choseList[index].name;
                this.reqData.page = 1;

                this.getData();
            },
            calendar: function(val) {
                this.reqData.date = val;
                this.getData();
            },
            getData: function(bol) {
                var _this = this;

                  $.ajax({
                    type: 'GET',
                    url: '/seller/flowitem',
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

                                if(res.length < 7) { //不足4条数据的情况
                                     _this.mescrollLoading = false;
                                }else {

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