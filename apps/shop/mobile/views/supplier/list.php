<?php $this->beginBlock('style') ?>
<link rel="stylesheet" href="/css/mescroll.min.css">
<link rel="stylesheet" href="/css/car.css?<?=\Yii::$app->request->sRandomKey?>">
<link rel="stylesheet" href="/css/supplier.css?<?=\Yii::$app->request->sRandomKey?>">
<style>
    [v-cloak] {
        display: none
    }
    .mescroll-upwarp {
        display: none;
    }
</style>
<?php $this->endBlock() ?>
<div class="car_header" >
    <a href="javascript:;" onclick="goBack()" class="car_back">
        <span class="icon">&#xe885;</span>
    </a>
    <h2>全部商品</h2>
    <span class="ad_more icon">&#xe602;</span>
</div>

<div class="car_selling s-list" id="app" v-cloak>
    <div class="selling_list">
        <ul>
            <li class="sellings" v-for="item in dataList">
                <a :href=item.link>
                    <div class="sell_pic " :class='{sellout: item.sellout, s_loot_all: item.seckill.status == "已抢光", s_over: item.seckill.status == "已结束"}' >
                        <img :src="item.pic" alt="">
                        <div class="seckill" v-if="item.seckill.length !== 0"></div>
                         <div class="label" v-if="item.seckill.length == 0" :style="{ backgroundImage:'url('+item.icon+')'}"></div>  
                         
                        <spc-clocker :datatime="item.seckill.start" v-if="item.seckill.status == '未开始'"></spc-clocker>
                        <div class="buy_stock" v-if="item.seckill.status == '未抢光'" 
                        v-text="'仅剩'+item.seckill.stock+'件'"></div>

                        
                    </div>
                    <div class="sell_info">
                        <h3 class="multiEllipsis" v-text="item.name"></h3>
                        <span class="sell_price" v-text="'¥'+item.price"></span>
                    </div>
                </a>
            </li>
        </ul>
    </div>
    <div class="dropload-noData" v-if="!isMore">啊哦~没有更多了</div>
</div>

<div class="nav_more_list">
    <div class="triangle-up"></div>
    <ul>
        <li class="flex" onclick="location.href='<?= \Yii::$app->request->mallHomeUrl ?>'">
            <span class="icon">&#xe608;</span>
            <em>首页</em>
        </li>
        <li class="flex" onclick="location.href='<?= \yii\helpers\Url::toRoute(["/cart"], true) ?>'">
            <span class="icon">&#xe60a;</span>
            <em>购物车</em>
        </li>
        <li class="flex" onclick="location.href='<?= \yii\helpers\Url::toRoute(["/member"], true) ?>'">
            <span class="icon">&#xe64a;</span>
            <em>我的</em>
        </li>
    </ul>
</div>

<?php $this->beginBlock('foot') ?>
<script src="/js/mescroll.min.js"></script>
<script>
    var data = <?=$data?>;
    var dataList = data.data;
    var supppilerID = '<?=$_GET['id']?>';
    
    // 倒计时组件 
        var spcClocker = Vue.extend({
            template: '<div class="buy_time" v-text="isContent" v-if="!timeOut"></div>',
            props:['datatime'],
            data: function() {
                return {
                    isTime: this.datatime,
                    isContent: '',
                    isTime: null,
                    timeOut: false,
                }
            },
            watch: {},
            mounted: function() {
                var nowTime = (new Date(dNow).getTime())/1000; //后端时间数据转成秒
                this.init(nowTime);
            },
            methods: {
                init:function(nowTime) {
                    var _this = this;
                    var leftTime = _this.datatime.replace(/\-/g, "/"),
                        endTime = new Date(leftTime),
                        disTime = endTime.getTime() - nowTime*1000, //这里时间数据转成毫秒
                        day = Math.floor( disTime/(1000*60*60*24) ),
                        hour = Math.floor( disTime/(1000*60*60)%24 ),
                        minute = Math.floor( disTime/(1000*60)%60 ),
                        second = Math.floor( disTime/1000%60 ); 
                    if( disTime <= 0 ) {
                        clearTimeout(_this.isTime);
                        _this.timeOut = true;
                    }
                    day = _this.checkTime(day);
                    hour = _this.checkTime(hour);
                    minute = _this.checkTime(minute);
                    second = _this.checkTime(second);
                    _this.isContent = '距开抢:'+day+ '天'+ hour +'时'+ minute +'分'+ second +'秒';

                    var isTime = setTimeout(function(){
                         nowTime = nowTime + 1;
                        _this.init(nowTime);
                    },1000)
                },
                checkTime:function(i) {
                    if( i < 10 ) {
                        i = '0' + i;
                    }
                    return i;
                },
            }
        });
    new Vue({
        el: '#app',
        components: {
            'spc-clocker': spcClocker,
        },
        data: {
            dataList: dataList,
            isMore: data.isMore,
            page: 1,
            id: supppilerID,
            mescroll: null,//滚动对象
        },
        mounted: function() {
            var _this = this;

            //初始化构造页面滚动结构
            _this.mescroll = new MeScroll("body", {
                up: {
                    auto: false, //初始化不加载
                    callback: _this.upCallback, //上拉回调
                },
                down: {
                    use: false,
                }
            });
        },
        methods: {
            upCallback: function () {
                var _self = this;
                if (!_self.isMore) {
                    return;
                }
                _self.page++;

                $.ajax({
                    type: 'GET',
                    url: '<?= \yii\helpers\Url::toRoute([\Yii::$app->request->shopUrl . "/supplier/item", 'id'=>$_GET['id']], true) ?>',
                    data: {
                        index: _self.page
                    },
                    dataType: 'json',
                    success: function (res) {

                        if ( res && res.data.length !== 0 ) {
                             _self.dataList = _self.dataList.concat(res.data);
                             //_self.isMore = res.isMore;
                        }else {
                            _self.isMore = false;
                        }
                        _self.mescroll.endSuccess(); //数据加载完 状态处理
                    },
                    error: function (xhr, type) {
                        _self.mescroll.endErr(); //失败后调整
                    }
                });
            },
            countDown: function() {
                var _this = this;
                for(var i = 0; i< $('.buy_time').length ;i ++){
                    var leftTime = $('.buy_time').eq(i).data('time'),
                        nowTime = new Date(),
                        endTime = new Date(leftTime),
                        disTime = endTime.getTime() - nowTime.getTime(),
                        day = Math.floor( disTime/(1000*60*60*24) ),
                        hour = Math.floor( disTime/(1000*60*60)%24 ),
                        minute = Math.floor( disTime/(1000*60)%24 ),
                        second = Math.floor( disTime/1000%60 );

                    if( disTime < 0 ) {
                         $('.buy_time').eq(i).hide();
                          continue;
                    }
                    day = _this.checkTime(day);
                    hour = _this.checkTime(hour);
                    minute = _this.checkTime(minute);
                    second = _this.checkTime(second);
                    $('.buy_time').eq(i).html('距开抢:'+day+ '天'+ hour +'时'+ minute +'分'+ second +'秒');
                }
                setTimeout(function() {
                    _this.countDown();
                },1000)
            },
            checkTime:function(i) {
                if( i < 10 ) {
                    i = '0' + i;
                }
                return i;
            },
        }

    })
</script>
<script>
     $(function() {
        $('.ad_more').on('click',function() {
            event.stopPropagation(); 
            $(".nav_more_list").toggle();
        })

        $(window).on('scroll',function() {
             $(".nav_more_list").hide();
        })

        $('body').on('click',function() {
             $(".nav_more_list").hide();
        })

    })
</script>
<?php $this->endBlock() ?>