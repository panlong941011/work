<?php $this->beginBlock('style') ?>
<link rel="stylesheet" href="/css/mescroll.min.css">
<link rel="stylesheet" href="/css/settlement.css?<?= \Yii::$app->request->sRandomKey ?>">
<?php $this->endBlock() ?>
<style type="text/css">
    body,html {
         height: 100%;
    }
    .frozen {
        height: 100%;
    }
    .mescroll{
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
    .mescroll-upwarp {
        display: none;
    }
</style>
<div class="frozen" id="app"  v-cloak>
    <div id="mescroll" class="mescroll">
    <div class="mescroll-bounce">
        <div class="ad_header">
            <a href="javascript:;" class="ad_back" onclick="goBack()">
                <span class="icon">&#xe885;</span>
            </a>
            <h2>冻结金额</h2>
        </div>
        <!-- 为空时 -->
        <div class="frozen_empty" v-if="isEmpty">
            <div class='frozen_pic'>
                <img src="../../images/supplier/frozen.png">
            </div>
            <p>您当前没有冻结金额</p>
        </div>
        <div class="frozen_list" v-if="!isEmpty">
            <ul>
                <li class="frozen_item" v-for="item in dataList">
                    <div class="item_top flex">
                        <span v-text="item.title"></span>
                        <em  v-text="item.price"></em>
                    </div>
                    <p class="frozen_time" v-text="item.time"></p>
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
    new Vue({
        el: '#app',

        data: {
            dataList: dataList,
            mescroll: null, //滚动对象
            mescrollLoading: false,//底部加载
            page: 1,
            dataMore: true,
            isEmpty: false, //为空
            fullData: true,//数据是否满屏
        },
        mounted: function() {
            var _self = this;

            if( _self.dataList.length == 0 ) {
                _self.isEmpty = true;
            }
            if( _self.dataList.length <= 8 ) {
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
                _this.page++;
                if( !_this.dataMore){ //判断是否有更多
                    _this.mescroll.endSuccess();
                    return;
                }
                _this.mescrollLoading = true;
                
                $.get('/wholesaler/frozenitem',
                    {
                        'page': _this.page,
                    },
                    function(res) {
                         console.log(res);
                         if (res  && res.length !== 0) {
                              
                            _this.dataList = _this.dataList.concat(res);

                        } else {
                            _this.dataMore = false;
                            _this.mescrollLoading = false;
                        }

                         _this.mescroll.endSuccess();

                       
                },'json')
            },
        }

    })
</script>
<?php $this->endBlock() ?>
