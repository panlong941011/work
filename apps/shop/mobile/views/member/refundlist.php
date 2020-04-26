<?php $this->beginBlock('style') ?>
    <link rel="stylesheet" href="/css/mescroll.min.css">
    <link rel="stylesheet" href="/css/afterSales.css?<?=\Yii::$app->request->sRandomKey?>">
    <style type="text/css">
        .mescroll-upwarp {
            display: none;
        }
        .bottom_tip {
            padding: .64rem;
            text-align: center;
            background: #eeeeee;
            color: #999;
        }
        [data-dpr="1"] .bottom_tip {
            font-size: 12.5px
        }

        [data-dpr="2"] .bottom_tip {
            font-size: 25px
        }

        [data-dpr="3"] .bottom_tip {
            font-size: 37.5px
        }
    </style>
<?php $this->endBlock() ?>
<div class="after_sale" id="app" v-cloak>
    <div class="ad_header">
        <a href="javascript:goBack();" class="ad_back">
            <span class="icon">&#xe885;</span>
        </a>
        <h2>退款/售后</h2>
        <span class="ad_more icon">&#xe602;</span>
    </div>
    <!-- 没有订单的情况 -->
    <div class="after_empty" style="display: none;">
        <div class="pic_wrap">
            <img src="/images/order/refund_empty.png">
        </div>
        <p>亲，您还没有退货信息哦~</p>
    </div>
    <div class="order_list" >
        <section class="list_layer" v-for="item in dataList">
            <h3 class="layer_title ">
            	<a :href="item.shoplink" style="width: 100%;display: block;">
					<span class="title_word singleEllipsis" v-text='item.shopName'></span>
                	<span class="pay_status" v-text="item.status"></span>
            	</a>	
            </h3>
            <div class="list_content">
                <a :href="item.link">
                    <div class="list_item flex">
                        <div class="pic">
                            <div>
                                <img :src="item.images" alt="">
                            </div>
                        </div>
                        <div class="info">
                            <div>
                                <h4 class="title multiEllipsis" v-text="item.title"></h4>
                            </div>
                            <div class="prop">
                                <p v-text="item.spec" class="multiEllipsis"></p>                                               
                            </div>
                        </div>
                    </div>

                </a>

            </div>
            <div class="list_footer flex">
                <div class="pay">实付款: ¥<em v-text="item.pay"></em></div>
                <div class="reund">退款金额: ¥<em v-text="item.refund"></em></div>
            </div>
        </section>
        <div class="bottom_tip" v-if="!isMore">没有更多商品了~</div>
    </div>
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
        <li class="flex" onclick="location.href='<?= \yii\helpers\Url::toRoute(["/member"],
            true) ?>'">
            <span class="icon">&#xe64a;</span>
            <em>我的</em>
        </li>
    </ul>
</div>
<?php $this->beginBlock('foot') ?>
<script src="/js/mescroll.min.js"></script>

<script>
    var data = <?=$data?>;
    new Vue({
        el: "#app",
        data: {
            dataList: data.datalist,
            index: 1,
            isMore: data.isMore,
            mescroll: null,//滚动对象
        },
        mounted: function() {
            var _self = this;

            //初始化构造页面滚动结构
            _self.mescroll = new MeScroll("body", {
                up: {
                    auto: false, //初始化不加载
                    callback: _self.upCallback, //上拉回调
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
                _self.index++;

                $.ajax({
                    type: 'GET',
                    url: '/member/refundlistitem',
                    data: {
                        index: _self.index
                    },
                    dataType: 'json',
                    success: function (res) {
                        if (res) {
                             _self.dataList = _self.dataList.concat(res.datalist);
                             _self.isMore = res.isMore;
                        }
                        _self.mescroll.endSuccess(); //数据加载完 状态处理
                    },
                    error: function (xhr, type) {
                        _self.mescroll.endErr(); //失败后调整
                    }
                });
            }
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