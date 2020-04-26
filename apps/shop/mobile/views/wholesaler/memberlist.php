<?php $this->beginBlock('style') ?>
    <link rel="stylesheet" href="/css/mescroll.min.css">
    <link rel="stylesheet" href="/css/searchList.css?<?= time() ?>">
    <link rel="stylesheet" href="/css/memberlist.css?<?=time()?>">
<?php $this->endBlock() ?>
    <style>
        .search_wrap {
            width: 100%;
            background: #fff;
            height: 2rem;
        }
    </style>
    <div class="search_wrap">
        <div class="search_header flex">
            <div class="s_close_wrap">
                <span class="s_close"></span>
            </div>
            <div class="search_input flex">
                <span></span> <input type="text" placeholder="<?= $_GET['keyword'] ? $_GET['keyword'] : '请输入用户/微信昵称' ?>" id="keyWord">
            </div>
            <div class="search_btn">搜索</div>
        </div>
    </div>

    <div class="teamlist">
		<? if ($arrMember) { ?>
            <div class="team-list">
                <div class="team-list-h" id=""></div>
                <ul class="team-list-bd">
					<? foreach ($arrMember as $k => $value) { ?>
                        <li class="team-list-item">
                            <a href="/wholesaler/newwholesale?ProductID=<?=$_GET['ProductID']?>&SellerID=<?=$value->lID?>" class="list-item-link">
                                <div class="advar">
                                    <img src="<?= $value->avatar ?>" alt="">
                                </div>
                                <span class="name"><?= $value->sName ?></span> </a>
                        </li>
					<? } ?>
                </ul>
            </div>
		<? } else { ?>
            <div class="el-empty">
                <p>没有数据哦~</p>
            </div>
		<? } ?>
    </div>



    <div class="mask"></div>
    <!-- 提示遮罩 -->
    <div class="select_mask"></div>


<?php $this->beginBlock('foot') ?>


    <script>
        $(function () {
            //其他页面跳转栏目
            $('.s_l_more').on('click', function () {
                event.stopPropagation();
                $(".nav_more_list").toggle();
            })

            $('.mescroll').on('scroll', function () {
                $(".nav_more_list").hide();
            })

            $('body').on('click', function () {
                $(".nav_more_list").hide();
            })

            //搜索页
            $('.s_input').on('click', function () {
                $('.search_wrap').show();
                $('.searchList_wrap').hide();
            })
            $('.s_close_wrap').on('click', function () {
                window.history.go(-1);
                $('.search_wrap').hide();
                $('.searchList_wrap').show();
            })
            var local = localStorage.getItem('searchWordArr'),
                spanHtml = '';
            if (local) {
                local = local.split(',');
                for (var i = 0; i < local.length; i++) { //取缓存里面的值  循环成span标签插入
                    if (i > 4) { //只显示5个
                        break;
                    }
                    spanHtml += '<span onclick="toSearch(\'' + local[i] + '\')">' + local[i] + '</span>';

                }
                $('.h_s_content').html(spanHtml);
            } else {
                $('.history_search').hide();
            }

            $('.search_btn').on('click', function () {

                //设置缓存
                var value = $.trim($('#keyWord').val());
                if (value !== '') {
                    var sWords = localStorage.getItem('searchWordArr'), //获取缓存值
                        searchWordArr = [];

                    if (!sWords) { //判断没有的话 新建
                        searchWordArr.unshift(value);
                        localStorage.setItem("searchWordArr", searchWordArr);
                    } else { //有的话 分割字符串 重新组装
                        searchWordArr = sWords.split(',');
                        var isEqual = searchWordArr.every(function (item, index) {
                            return item !== value;
                        })

                        if (isEqual) {
                            searchWordArr.unshift(value);
                            localStorage.setItem("searchWordArr", searchWordArr);
                        }
                    }
                } else {
                    value = $('#keyWord').attr('placeholder');
                }

                $('#keyWord').val('');
                $('.searchList_wrap').show();

                setTimeout(function (args) {
                    location.href = "<?=\yii\helpers\Url::toRoute([\Yii::$app->request->shopUrl . "/wholesaler/memberlist"],
						true)?>?keyword=" + encodeURI(value)+ '&ProductID=<?=$_GET["ProductID"]?>'; //跳转模拟
                }, 500);


            })

            $('.select_cancel').on('click', function () {
                $('.selection_bar').hide();
                $('.select_mask').hide();
            })

            $('.select_sure').on('click', function () {
                $('.selection_bar').hide();
                $('.select_mask').hide();
                $('.history_search').hide();

                localStorage.removeItem('searchWordArr');
            })
        })

        //以下为处理IOS返回时 记录之前操作遗留的弹框的问题
        function isIOS() {
            var u = navigator.userAgent;
            return !!u.match(/\(i[^;]+;( U;)? CPU.+Mac OS X/); //ios终端
        }

        //搜索历史加链接
        function toSearch(keyword) {
            var bWholesale = '<?=urldecode($_GET['bWholesale'])?>';
            var url = '<?=\yii\helpers\Url::toRoute([\Yii::$app->request->shopUrl . "/product/list"], true)?>';
            location.href = url + '?keyword=' + keyword + "&bWholesale=1";
            if (isIOS()) {
                $('.search_wrap').hide();
                $('.searchList_wrap').show();
            }
        }

        function closeSearch() {
            if (isIOS()) {
                $('.search_wrap').hide();
                $('.searchList_wrap').show();
            }
        }
    </script>

<?php $this->endBlock() ?>