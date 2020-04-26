<?php $this->beginBlock('style') ?>
<link rel="stylesheet" href="/css/mescroll.min.css">
<link rel="stylesheet" href="/css/settlement.css?<?= \Yii::$app->request->sRandomKey ?>">
<?php $this->endBlock() ?>
<style type="text/css">
    body,html {
         height: 100%;
    }
    .team_manage {
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
      top: 6rem;
    }

    .t_m_content {
        position: fixed;
        left: 0;
        top: 0;
        right:0;
        width: 100%;
        z-index: 10;
        max-width: 16rem;
        margin: 0 auto;
    }
    .upgrade{
        border: 1px solid #f42323;
        color: #f42323;
        margin-left: 0.4266666667rem;
        width: 2.9866666667rem;
        height: 1.0666666667rem;
        line-height: 1.0666666667rem;
        text-align: center;
        border-radius: 0.2133333333rem;
        font-size: 26px;
        display: inline-block;
        position: absolute;
        right: .4rem;
    }

    #topdiv{
        width: 9.733333rem;
    }

    #searchkeyword{
        font-size: 29px;
    }

    #search{
        width: 2rem;
    }
</style>
<div class="team_manage" id="app">
    <div class="t_m_content" v-if="!isSearchShow" v-cloak>
        <div class="ad_header" style="display: none">
            <a href="javascript:;" class="ad_back" onclick="goBack()">
                <span class="icon">&#xe885;</span>
            </a>
            <div class="s_input flex" id="topdiv">
                <span></span>
                <input id="searchkeyword" type="text" placeholder="用户昵称/手机号">
            </div>
            <a href="javascript:;" class="ad_more icon more_link" id="search" @click="search()" >搜索</a>
        </div>
        <div class="options_area flex" style="display: none">
            <div class="area_left">
                <span class="o_a_item" :class="{active: isOpt=='普通用户'}" @click="changeItem('普通用户')">普通用户</span>
                <span class="o_a_item" :class="{active: isOpt=='VIP用户'}" @click="changeItem('VIP用户')">VIP用户</span>
                <span class="o_a_item"  :class="{active: isOpt=='全部'}" @click="changeItem('全部')">全部</span>
            </div>
        </div>
        <div class="team_person">
            <h3 class="c_m_title">
                <i class="icon">&#xe723;</i>
                <span>好友人数</span>
            </h3>
            <div class="c_m_person">
                <em v-text="dataList.teamPerson"></em>
            </div>
        </div>
        

        <div class="team_main">
            <div class="team_option flex">
                <div class="t_o_person" :class="{down: currentTab.name==tab.name&&currentTab.status=='down',up:currentTab.name==tab.name&&currentTab.status=='up'}" @click="switchTab(index)" v-for="(tab,index) in tabList" v-text="tab.name"></div>
            </div>
            <div id="mescroll" class="mescroll team_mescroll">
            <div class="mescroll-bounce">
                <!-- 为空时 -->
                <div class="team_empty" v-if="dataList&&dataList.personList.length==0">
                    <div class="empty_pic">
                        <img src="/images/supplier/teamBg.png">
                    </div>
                    <p>你还没有好友哦~</p>
                </div>
                <ul class="team_list">
                    <li class="team_item" v-for="item in dataList.personList">
                        <div class="flex">
                            <div class="t_i_pic">
                                <img :src="item.personLogo">
                            </div>
                            <div class="t_i_info">
                                <h3 v-text="item.personName"></h3>
                                <h3 v-text="item.dNewDate"></h3>
                            </div>
                            <a v-if="0" href="javascript:;" :value="item.ID" onclick="upgrade(this)" class="upgrade">升级</a>
                            <a v-if="0" href="javascript:;" :value="item.ID" onclick="downGrade(this)" class="upgrade">取消VIP</a>
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


    <commission-part :slectdata="commissionList" v-if="commissionShow" @selected="selectItem" @partopt="closePart"></commission-part>
</div>

<?php $this->beginBlock('foot') ?>
<script src="/js/mescroll.min.js"></script>
<script src="/js/template.js"></script>
<script>
    var data = <?=$sDataJson?>;

    new Vue({
        el: '#app',
        components: {
            'commission-part': commission,
        },
        data: {
            commissionList: <?=$sTypeJson?>,
            commissionShow: false,
            commissionName: '<?=Yii::$app->request->get("sellertype")?Yii::$app->request->get("sellertype"):"全部"?>',
            dataList: data,
            isChoseOpt: 'person',
            isEmpty: false,
            isSearchShow: false, //搜索框
            isOpt: '<?=Yii::$app->request->get("type")?Yii::$app->request->get("type"):"普通用户"?>',
            isAll: true,//第一次切换
            isTime: true,//第一次切换
            mescroll: null, //滚动对象
            mescrollLoading: false,//底部加载
            dataMore: true,
            fullData: true,//数据是否满屏
            reqData: {
                    'orderby': '<?=Yii::$app->request->get("orderby")?Yii::$app->request->get("orderby"):"个人业绩"?>',
                    'orderbydir': '<?=\Yii::$app->request->get("orderbydir")?\Yii::$app->request->get("orderbydir"):'down'?>',
                    'type':'<?=Yii::$app->request->get("type")?Yii::$app->request->get("type"):"普通用户"?>',
                    'page': '<?=Yii::$app->request->get("page")?Yii::$app->request->get("page"):"1"?>',
                    'sellertype':'<?=Yii::$app->request->get("sellertype")?Yii::$app->request->get("sellertype"):"全部"?>',
                    'keyword':'',
            },
            tabList:[//切换列表 suwen
                //{item:0,name:'个人业绩',status:''},
                {item:1,name:'入驻时间',status:'down'}
            ],
            currentTab:{item:1,name:'<?=Yii::$app->request->get("orderby")?Yii::$app->request->get("orderby"):"入驻时间"?>',status:'<?=\Yii::$app->request->get("orderbydir")?\Yii::$app->request->get("orderbydir"):'down'?>'},//当前tab  suwen
        },
        mounted: function() {
            var _self = this;

            if( _self.dataList.personList.length == 0) {
                 _self.isEmpty = false;
            }

            if( _self.dataList.personList.length < 5 ) {
                 _self.dataMore = false;
                 _self.fullData = false;
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
            search: function() {
                this.reqData.page = 0;
                this.mescroll.scrollTo( 0, 0 );
                var keyword = $("#searchkeyword").val();
                this.reqData.keyword = keyword;
                this.getData();
            },
            //切换tab suwen
            switchTab:function(index){
                this.reqData.page = 1;
                this.currentTab.item = index;
                this.reqData.orderby = this.currentTab.name = this.tabList[index].name;
                this.reqData.orderbydir = this.currentTab.status=this.currentTab.status =='down'?'up':'down';
                this.mescroll.scrollTo( 0, 0 );//回到顶部
                this.getData();
            },
            //回调
            upCallback: function() {
                var _this = this;

                if( !_this.dataMore){ //判断是否有更多
                    _this.mescroll.endSuccess();
                    return;
                }
                 _this.page++;

                _this.mescrollLoading = true;

                _this.reqData.page++;
                _this.getData(true);
            },
            getData: function(bol) {
                var _this = this;
                $.ajax({
                    type: 'get', //实际要用get
                    url: '/seller/myteamitem',
                    data: _this.reqData,
                    dataType: 'json',
                    success: function (res) {
                        //滚动情况
                        if (res && res.personList && res.personList.length !== 0) {
                            //切换时重置对象数组而非拼接
                            if (!bol){
                                _this.dataList.personList = res.personList;
                            }else {
                                _this.dataList.personList = _this.dataList.personList.concat(res.personList);
                            }
                            _this.dataList.teamPerson = res.teamPerson;
                            _this.dataMore = res.bMore;
                        }

                        if (!_this.dataMore){
                            _this.mescrollLoading = false;
                        }

                         _this.mescroll.endSuccess(); //数据加载完 状态处理


                        if (_this.reqData.keyword){
                            shoperm.showTip('搜索成功')
                        }else if (!bol){
                            shoperm.showTip('切换成功');
                        }

                        _this.reqData.keyword = '';
                    },
                    error: function (xhr, type) {
                        _this.mescroll.endErr(); //失败后调整
                    }
                });
            },
            //显示提成选项
            showCommission: function() {
                this.commissionShow = true;
            },
             //弹出框选择
            selectItem: function(val) {
                
                this.commissionName = val;
                this.reqData.sellertype = val;
                this.reqData.page = 1;
                this.mescroll.scrollTo( 0, 0 );
                this.getData();
            },
            //关闭弹窗
            closePart: function(val) {
                this.commissionShow = val;
            },

            //搜索
            showSearch: function() {
                this.isSearchShow = true;
            },
            returnStatus: function(val) {
                this.isSearchShow = val;
            },
            //选项切换
            changeItem: function(val) {
                this.isOpt = val;
                this.reqData.type = val;
                this.reqData.page = 1;
                this.getData();
            }
        }

    });

    /**
     * 升级用户为VIP用户（初级用户）
     * @param obj
     *  panlong
     *  2019年9月10日16:49:25
     */
    function upgrade(obj) {
        if(confirm('确认升级?')) {
            var data = {};
            data.ID = $(obj).attr('value');
            console.log(data.ID);
            $(obj).hide();
            $.ajax({
                type: 'post',
                url: '/seller/upgrade',
                data: data,
                dataType: 'json',
                success: function (res) {
                    shoperm.showTip(res.msg);
                },
                error: function (xhr, type) {
                    _this.mescroll.endErr(); //失败后调整
                }
            });
        }
    }
    function downGrade(obj) {
        if(confirm('确认取消VIP?')) {
            var data = {};
            data.ID = $(obj).attr('value');
            console.log(data.ID);
            $(obj).hide();
            $.ajax({
                type: 'post',
                url: '/seller/downgrade',
                data: data,
                dataType: 'json',
                success: function (res) {
                    shoperm.showTip(res.msg);
                },
                error: function (xhr, type) {
                    _this.mescroll.endErr(); //失败后调整
                }
            });
        }
    }
</script>
<?php $this->endBlock() ?>

