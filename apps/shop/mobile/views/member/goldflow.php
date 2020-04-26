<?php $this->beginBlock('style') ?>
<link rel="stylesheet" href="/css/supplierMain.css?<?= \Yii::$app->request->sRandomKey ?>">
<link rel="stylesheet" href="/css/mescroll.min.css">
<style type="text/css">
    body,html {
        height: 100%;
    }
    .mescroll-upwarp {
        display: none;
    }
</style>

<?php $this->endBlock() ?>
<div class="gold_detail" id="app" v-cloak>
    <div class="ad_header gold_header">
        <a href="javascript:goBack()" class="ad_back">
            <span class="icon">&#xe885;</span>
        </a>
        <h2>金币明细</h2>
    </div>
    <ul class="top_list flex">
        <li class="top_item" :class='{active: item.isMark}' v-for="(item,index) in choseList"
            v-text="item.name"
            @click="choseItem(index)"
        >全部</li>
    </ul>
    <div id="mescroll" class="mescroll gold_mescroll">
        <div class="mescroll-bounce">
            <div class="detial_empty" v-if="isDetailShow">
                <div class="detail_pic">
                    <img src="../../images/supplier/gold_empty.png">
                </div>
                <p class="d_e_tip">您还没有金币明细</p>
            </div>
            <div class="detailed_content" v-if="!isDetailShow">
                <ul>
                    <li class="detailed_item" v-for="item in dataList">
                        <div class="detailed_top flex">
                            <h2 v-text="item.detailTitle"></h2>
                            <em v-text="item.commission"></em>
                        </div>
                        <div class="detailed_bottom flex">
                            <p v-text="item.detailTime"></p>
                        </div>
                    </li>
                </ul>
            </div>
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
            choseList: [{name:'全部',isMark: true},{name:'收入',isMark: false},{name:'支出',isMark: false}],
            dataList: dataList,
            isDetailShow: false,
            dataMore: true,
            page: 1,
            mescroll: null,
            commissionName: '全部' //类型名称
        },
        mounted: function() {
            var _self = this;

            if( _self.dataList &&_self.dataList.length === 0 ) {
                _self.isDetailShow = true;
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
            //滚动回调
            upCallback: function() {
                var _this = this;

                if( !_this.dataMore){ //判断是否有更多
                    _this.mescroll.endSuccess();
                    return;
                }
                _this.page++;

                $.get('/member/goldflowitem',
                    {
                        'page': _this.page,
                        'type': _this.commissionName
                    },
                    function(res) {
                        //console.log(res);
                        if (res && res.length !== 0) {

                            _this.dataList = _this.dataList.concat(res);

                        } else {
                            _this.dataMore = false;

                        }
                        _this.mescroll.endSuccess();
                    },'json')
            },
            //点击切换
            choseItem: function(index) {
                var _this = this;
                var length = _this.choseList.length;
                for( var i = 0;i < length; i++ ) {
                    _this.choseList[i].isMark = false;
                }
                _this.choseList[index].isMark = true;

                _this.commissionName = _this.choseList[index].name;
                _this.page = 1;
                _this.mescroll.scrollTo( 0, 0 );//切换栏目时回到顶部

                $.get('/member/goldflowitem',
                    {
                        'page': _this.page,
                        'type': _this.commissionName
                    },
                    function(res) {
                       // console.log(res);
                        if (res && res.length !== 0) {
                            _this.dataMore = true;
                            _this.isDetailShow = false; //为空时
                            _this.dataList = res;

                        } else {
                            _this.dataMore = false;
                            _this.isDetailShow = true;
                        }
                        _this.mescroll.endSuccess();
                    },'json')
            }
        }

    })
</script>
<?php $this->endBlock() ?>