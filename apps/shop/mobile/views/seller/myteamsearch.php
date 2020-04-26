<?php $this->beginBlock('style') ?>
<link rel="stylesheet" href="/css/mescroll.min.css">
<link rel="stylesheet" href="/css/settlement.css?<?= \Yii::$app->request->sRandomKey ?>">
<?php $this->endBlock() ?>
<style type="text/css">
    body,html {
         height: 100%;
    }
    .team_search {
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
</style>
<div class="team_search" id="app" v-cloak>
	<div class="search_header flex">
	    <div class="s_close_wrap" @click="window.history.go(-1)">
	        <span class="s_close icon">&#xe885;</span>
	    </div>
	   <div class="search_input flex">
	        <span></span>
	     	<input type="text" placeholder="用户昵称/手机号" v-model="keys">
	   </div>
	   <div class="search_btn" @click="search">搜索</div>
	</div>

	<!-- 为空时 -->
	<div class="search_empty" v-if="isEmpty">
		<div class="empty_pic">
			<img src="../../images/list_empty.png">
		</div>
		<p>啊哦~没有搜到相关经销商</p>
	</div>
	<div id="mescroll" class="mescroll search_mescroll" v-if="!isEmpty">
    <div class="mescroll-bounce">
		<ul class="team_list">
			<li class="team_item" v-for="item in dataList.personList">
				<a :href="item.link" class="flex">
					<div class="t_i_pic">
						<img :src="item.personLogo">
					</div>
					<div class="t_i_info">
						<h3 v-text="item.personName"></h3>
						<p  v-text="'个人业绩：¥'+item.achieve"></p>
						<div class="mom flex">
							<span>环比：</span>
							<div class="mom_icon " v-if="item.contrast == 'down'">
								<i class="icon">&#xe612;</i>
								<em v-text="item.mom"></em>
							</div>
							<div class="mom_icon downward" v-if="item.contrast == 'up'">
								<i class="icon">&#xe612;</i>
								<em v-text="item.mom"></em>
							</div>
							<div class="equal" v-if="item.contrast == '--'">--</div>
						</div>
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
<?php $this->beginBlock('foot') ?>
<script src="/js/mescroll.min.js"></script>

<script>
	var data = <?=$sDataJson?>;
	new Vue({
        el: '#app',
        components: {
           // 'search-part': searchPart
        },
        data: {
        	keys: '<?=\Yii::$app->request->get("keyword")?>',
        	dataList: data,
            isEmpty: false,
            isSearchShow: false, //搜索框
            mescroll: null, //滚动对象
            mescrollLoading: false,//底部加载
            dataMore: true,
            fullData: true,
            reqData: {
                keyword: '<?=\Yii::$app->request->get("keyword")?>',
                page: 1,
            },
        },
        mounted: function() {
            var _self = this;
            //suwen 修改
            if( _self.dataList&&_self.dataList.personList&&_self.dataList.personList.length==0 ) {
                 _self.isEmpty = true;
            }

            if( _self.dataList.personList&&_self.dataList.personList.length < 7 ) {
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
            //回调
            upCallback: function() {

                var _this = this;
                _this.page++;
                if( !_this.dataMore){ //判断是否有更多
                    _this.mescroll.endSuccess();
                    return;
                }
                _this.mescrollLoading = true;

                _this.reqData.page++;
                _this.getData();
            },
            getData: function() {
                var _this = this;
                $.ajax({
                    type: 'get', //实际要用get
                    url: '/seller/myteamitem',
                    data: _this.reqData,
                    dataType: 'json',
                    success: function (res) {
                        //滚动情况
                        if (res.personList && res.personList.length !== 0) {
                            _this.dataList.personList = _this.dataList.personList.concat(res.personList);
                            
                        } else {
                            _this.dataMore = false;
                            _this.mescrollLoading = false;
                        }
                         _this.mescroll.endSuccess(); //数据加载完 状态处理
                    },
                    error: function (xhr, type) {
                        _this.mescroll.endErr(); //失败后调整
                    }
                });
            },
            search: function() {
                location.href = "<?=\yii\helpers\Url::toRoute([\Yii::$app->request->shopUrl . "/seller/myteamsearch"],true)?>?keyword=" + encodeURI(this.keys);
            }
        }

    })
</script>
<?php $this->endBlock() ?>