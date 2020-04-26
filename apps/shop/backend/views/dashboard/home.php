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

    .profile-info {
        height: 120px;
        line-height: 40px;
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
                                    <span data-counter="counterup"><?= $lPaidOrders ?></span>
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
                                    <span data-counter="counterup" data-value="12,5"><?= $lRefundWaitApply ?></span>
                                </div>
                                <div class="desc"> 退款售后订单</div>
                            </div>
                            <a class="more" href="javascript:;"
                               onclick="parent.addTab('退款/售后', '/shop/refund/home?sTabID=wait')">
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
                                    <span data-counter="counterup" data-value="549">￥<?= number_format($fProfit,
                                            2) ?></span>
                                </div>
                                <div class="desc"> 平台今日利润</div>
                            </div>
                            <a class="more" href="javascript:;"
                               onclick="parent.addTab('今日完成订单', '/shop/order/home?dReceiveDate=<?= rawurlencode($sToday) ?>')">
                                更多
                                <i class="m-icon-swapright m-icon-white"></i>
                            </a>
                        </div>
                    </div>
                </div>
                <!--end: Demo 1 -->

                <!-- begin: Demo 1 -->
                <h3 class="">提现/充值待处理</h3>
                <hr>

                <div class="row">
                    <div class="col-md-3">
                        <div class="dashboard-stat dashboard-stat-v2 purple" href="#">
                            <div class="visual">
                                <i class="fa fa-globe"></i>
                            </div>
                            <div class="details">
                                <div class="number">
                                    <span data-counter="counterup" data-value="89"><?= $lWithdraw ?></span>
                                </div>
                                <div class="desc"> 供应商提现申请</div>
                            </div>
                            <a class="more" href="javascript:;"
                               onclick="parent.addTab('提现记录', '/shop/withdraw/home')"> 更多
                                <i class="m-icon-swapright m-icon-white"></i>
                            </a>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="dashboard-stat green">
                            <div class="visual">
                                <i class="fa fa-group fa-icon-medium"></i>
                            </div>
                            <div class="details">
                                <div class="number"> <?= $lRecharge ?></div>
                                <div class="desc"> 渠道商充值申请</div>
                            </div>
                            <a class="more" href="javascript:;"
                               onclick="parent.addTab('充值记录', '/shop/recharge/home')"> 更多
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
                        <div class="portlet light bordered" style="height: 200px;">
                            <div class="portlet-body">
                                <div class="col-md-6">
                                    <div class="profile-userpic">
                                        <img src="<?= Yii::$app->homeUrl ?>/js/pages/img/avatar250x250.jpg" alt="">
                                    </div>
                                </div>
                                <div class="profile-info">
                                    <div><?= Yii::$app->backendsession->sysuser->sName ?></div>
                                    <div class="font-grey-cascade"
                                         style="font-size: 12px"><?= Yii::$app->backendsession->sysrole->sName ?></div>
                                    <a href="javascript:;"
                                       onclick="parent.addTab('个人信息', '/system/profile/home')">编辑个人信息</a>
                                </div>
                                <div style="margin-top: 10px">
                                    <div class="col-md-4">
                                        <a href="javascript:;"
                                           onclick="parent.addTab('新建 供应商', '/shop/supplier/new')"
                                           class="btn green btn-block"> 新建供应商 </a>
                                    </div>
                                    <div class="col-md-4">
                                        <a href="javascript:;"
                                           onclick="parent.addTab('新建 渠道商', '/shop/buyer/new')"
                                           class="btn green btn-block"> 新建渠道商 </a>
                                    </div>
                                    <div class="col-md-4">
                                        <a href="javascript:;"
                                           onclick="parent.addTab('新建 商品', '/shop/product/new')"
                                           class="btn green btn-block"> 发布商品 </a>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                    <div class="col-md-5">
                        <div class="portlet light bordered">
                            <div class="portlet-body">
                                <div class="font-grey-cascade" style="font-size: 16px;margin-bottom: 10px">关键指数</div>
                                <div class="alert alert-info">
                                    <strong>昨日成交单数</strong>
                                    <span style="float: right;"><?= $lYesOrders ?></span>
                                </div>
                                <div class="alert alert-info">
                                    <strong>昨日成交金额</strong>
                                    <span style="float: right;">￥<?= number_format($fYesMoney, 2) ?></span>
                                </div>
                                <div class="alert alert-info">
                                    <strong>昨日退款金额</strong>
                                    <span style="float: right;">￥<?= number_format($fYesRefund, 2) ?></span>
                                </div>
                                <div class="alert alert-info">
                                    <strong>昨日平台利润</strong>
                                    <span style="float: right;">￥<?= number_format($fYesProfit, 2) ?></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

<script src="/js/clipboard.min.js" type="text/javascript"></script>
<script>

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

    function showHomeQrcode() {
        $.get
        (
            '/shop/dashboard/qrcode',
            function (data) {
                var modal = openModal(data, 400, 370);
            }
        );
    }
</script>