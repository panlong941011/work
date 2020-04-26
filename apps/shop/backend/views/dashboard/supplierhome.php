<style>
    .profile-userpic {
        text-align: center;
    }

    .profile-userpic img {
        float: none;
        margin: 0 auto;
        width: 120px;
        height: 120px;
        -webkit-border-radius: 50% !important;
        -moz-border-radius: 50% !important;
        border-radius: 50% !important;
    }
</style>


<div class="breadcrumb" style="display:none">
    <h2>控制台</h2>
</div>

<div class="row margin-top-20">
    <div class="col-md-12">
        <div class="portlet light bordered">
            <div class="portlet-body">


                <!--                <button type="button" class="btn btn-success"-->
                <!--                        onclick="showHomeQrcode();">-->
                <!--                    访问商城-->
                <!--                </button>-->

                <!-- begin: Demo 1 -->
                <h3 class="">订单统计</h3>
                <hr>

                <div class="row">
                    <div class="col-md-3">
                        <div class="dashboard-stat dashboard-stat-v2 blue" href="#">
                            <div class="visual">
                                <i class="fa fa-comments"></i>
                            </div>
                            <div class="details">
                                <div class="number">
                                    <span data-counter="counterup"><?= $lShipOrders ?></span>
                                </div>
                                <div class="desc"> 待发货订单</div>
                            </div>
                            <a class="more" href="javascript:;"
                               onclick="parent.addTab('待发货订单', '/shop/order/home?sTabID=paid')">
                                更多
                                <i class="m-icon-swapright m-icon-white"></i>
                            </a>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                        <div class="dashboard-stat dashboard-stat-v2 red" href="#">
                            <div class="visual">
                                <i class="fa fa-bar-chart-o"></i>
                            </div>
                            <div class="details">
                                <div class="number">
                                    <span data-counter="counterup" data-value="12,5"><?= $lRefundingOrders ?></span>
                                </div>
                                <div class="desc"> 退款中订单</div>
                            </div>
                            <a class="more" href="javascript:;"
                               onclick="parent.addTab('退款中订单', '/shop/order/home?sTabID=refund')">
                                更多
                                <i class="m-icon-swapright m-icon-white"></i>
                            </a>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                        <div class="dashboard-stat dashboard-stat-v2 green" href="#">
                            <div class="visual">
                                <i class="fa fa-shopping-cart"></i>
                            </div>
                            <div class="details">
                                <div class="number">
                                    <span data-counter="counterup" data-value="549"><?= $lSuccessOrders ?></span>
                                </div>
                                <div class="desc"> 今日已完成订单</div>
                            </div>
                            <a class="more" href="javascript:;"
                               onclick="parent.addTab('今日已完成订单', '/shop/order/home?sTabID=success&dReceiveDate=<?= urlencode($sToday) ?>')">
                                更多
                                <i class="m-icon-swapright m-icon-white"></i>
                            </a>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                        <div class="dashboard-stat green">
                            <div class="visual">
                                <i class="fa fa-group fa-icon-medium"></i>
                            </div>
                            <div class="details">
                                <div class="number">￥<?= number_format($fProfit, 2) ?></div>
                                <div class="desc"> 今日成交额</div>
                            </div>
                            <a class="more" href="javascript:;"
                               onclick="parent.addTab('今日成交额', '/shop/order/home?sTabID=success&dReceiveDate=<?= urlencode($sToday) ?>')">
                                更多
                                <i class="m-icon-swapright m-icon-white"></i>
                            </a>
                        </div>
                    </div>
                </div>
                <!--end: Demo 1 -->
                <h3 class="">系统信息</h3>
                <hr>
                <div class="row">
                    <div class="col-md-6">
                        <div class="portlet light bordered" id="blockui_sample_1_portlet_body" style="height: 150px;">
                            <div class="portlet-body">
                                <div class="col-md-6">
                                    <div class="profile-userpic">
                                        <img src="<?= Yii::$app->homeUrl ?>/js/pages/img/avatar250x250.jpg" alt="">
                                    </div>
                                </div>
                                <div style="height:120px;line-height: 40px">
                                    <div><?= Yii::$app->backendsession->sysuser->sName ?></div>
                                    <div class="font-grey-cascade"
                                         style="font-size: 12px"><?= Yii::$app->backendsession->sysrole->sName ?></div>
                                    <a href="javascript:;"
                                       onclick="parent.addTab('个人信息', '/system/profile/home')">编辑个人信息</a>
                                </div>
                            </div>
                        </div>

                    </div>
                    <div class="col-md-6">
                        <div class="portlet light bordered" id="blockui_sample_1_portlet_body">
                            <div class="portlet-body">
                                <div class="font-grey-cascade"
                                     style="font-size: 16px;margin-bottom: 10px;height:30px;line-height: 30px">
                                    账户信息
                                    <span style="float:right">
                                    <a href="javascript:;" class="btn green btn-sm"
                                       onclick="parent.addTab('提现记录', '/shop/withdraw/new')"> 提现申请 </a>
                                    <a href="javascript:;" class="btn green btn-sm"
                                       onclick="parent.addTab('交易记录', '/shop/dealflow/home')"> 交易记录 </a>
                                    </span>
                                </div>
                                <div class="alert alert-info">
                                    <strong>可提现</strong>
                                    <span style="float: right;">￥<?= number_format($fBalance, 2) ?></span>
                                </div>
                                <div class="alert alert-info">
                                    <strong>待结算</strong>
                                    <span style="float: right;">￥<?= number_format($fUnsettlement, 2) ?></span>
                                </div>
                                <div class="alert alert-info">
                                    <strong>已提现</strong>
                                    <span style="float: right;">￥<?= number_format($fWithdrawed, 2) ?></span>
                                </div>
                                <div class="alert alert-info">
                                    <strong>累积收入</strong>
                                    <span style="float: right;">￥<?= number_format($fSumIncome, 2) ?></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function showHomeQrcode() {
            $.get
            (
                '/shop/dashboard/qrcode',
                function (data) {
                    var modal = openModal(data, 400, 370);
                }
            );
        }

        jQuery.getScript("/js/clipboard.min.js", function (data, status, jqxhr) {
            var clipboard2 = new Clipboard('#copy');
            clipboard2.on('success', function (e) {
                console.log(e);
                alert("复制成功！")
            });

            clipboard2.on('error', function (e) {
                console.log(e);
                alert("复制失败！请手动复制")
            });
        });
    </script>