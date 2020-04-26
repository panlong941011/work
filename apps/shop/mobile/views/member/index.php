<?php use yii\helpers\Url;

$this->beginBlock('style') ?>
<link rel="stylesheet" href="/css/member.css">
<link rel="stylesheet" href="/css/supplierMain.css?<?= \Yii::$app->request->sRandomKey ?>">
<style>
    .nav .nav_item .mav_icon {
        width: 1.2rem;
        height: 1.2rem;
    }

    .nav .nav_list a {
        margin-left: 4%;
    }

    header {
        position: static;
        background-color: #E84D27;
    }

    header .portrait {
        margin: 0.8rem 0 0 0.64rem;
    }

    .person_name_wrap {
        height: 4rem;
    }

    .profit {
        clear: both;
        height: 2.6rem;
        width: 80%;
        background-color: #fff;
        border-radius: 0.5rem;
        margin-left: 10%;
        margin-top: -1.3rem;
        z-index: 1;
    }

    .profit .pr {
        font-size: 24px;
        width: 30%;
        float: left;
        margin-left: 2.5%;
        height: 2.6rem;
    }

    .profit .pf {
        height: 1.3rem;
        clear: both;
        width: 100%;
        float: none;
        text-align: center;
        line-height: 1.3rem;
    }

    .profit .pf:nth-child(odd) {
        line-height: 2rem;
    }

    .member {
        min-height: 10rem;
        margin-bottom: 2rem;
    }
</style>
<?php $this->endBlock() ?>
<div class="member">
    <header class="flex" style="background: linear-gradient(to bottom right, #E0B991, #AC7C4E);">
        <? if ($member) { ?>
            <div class="portrait" style="border: 0">
                <img src="<?= $member->sAvatarPath ? $member->sAvatarPath : "/images/order/person.png" ?>">
            </div>
            <div class="person_name_wrap flex">
                <h3 class="person_title"><?= $member->sName ?></h3>
                <? if ($seller) { ?>
                    <h3 class="person_title"><?= '来瓜分团长' ?></h3>
                <? } ?>
            </div>
        <? } else { ?>
            <div class="portrait">
                <img src="/images/order/person.png">
            </div>
            <h3 class="person_name" onclick="location.href='/member/logout'">请登录</h3>
        <? } ?>
    </header>

    <?if($seller){?>
    <nav>
        <div style="height: 1.8773333333rem;border-bottom: 1px solid #eeeeee;margin: 0 0.64rem;">
            <a class="flex" style="align-items: center;height: 100%;"> <em style="font-size: 30px">我的收入</em>
                <span class="to_withdrawals" style="font-size: 25px;width: 2.56rem;height: 1.0666666667rem;-webkit-border-radius: .64rem;border-radius: .64rem;background: #A7A7A7;color: #ffffff;text-align: center;line-height: 1.0666666667rem;margin-left: .5rem">提现</span> </a>
        </div>
        <div class="distribution">
            <div class="income" style="margin-top: 0;">
                <ul class="income_item flex">
                    <li>
                        <a>
                            <em><?= number_format($seller->fUnsettlement, 2) ?></em>
                            <span>待结算</span>
                        </a>
                    </li>
                    <li>
                        <a>
                            <em><?= number_format($seller->fWithdrawed, 2) ?></em>
                            <span>提现中</span>
                        </a>
                    </li>
                    <li>
                        <a>
                            <em><?= number_format($seller->fWithdraw, 2) ?></em>
                            <span>已提现</span>
                        </a>
                    </li>
                    <li>
                        <a>
                            <em><?= number_format($seller->fUnsettlement + $seller->fSettlement+$seller->fWithdraw, 2) ?></em>
                            <span>累计收入</span>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    <?}?>

    <nav>
        <div class="person_order" style="margin-top: .2rem">
            <a href="<?= \yii\helpers\Url::toRoute(["/member/orderlist"], true) ?>" class="flex"> <em>我的订单</em>
                <span>查看全部订单</span> </a>
        </div>
        <div class="nav_list flex">
            <a href="<?= \yii\helpers\Url::toRoute(["/member/orderlist", 'type' => "unpaid"], true) ?>">
                <div class="nav_item flex">
                    <i class="mav_icon icon_one" style="background: url(../images/order/unpaid.png) no-repeat;
    background-size: 100%;"></i> <span class="item_title">未付款</span>
                    <? if ($arrStatusCount['lUnpaidCount'] > 0) { ?>
                        <em class="wait_pay_num">
                            <? if ($arrStatusCount['lUnpaidCount'] > 99) { ?>
                                ...
                            <? } else { ?>
                                <?= $arrStatusCount['lUnpaidCount'] ?>
                            <? } ?>
                        </em>
                    <? } ?>
                </div>
            </a> <a href="<?= \yii\helpers\Url::toRoute(["/member/orderlist", 'type' => "paid"], true) ?>">
                <div class="nav_item flex">
                    <i class="mav_icon icon_two" style="background: url(../images/order/undeliver.png) no-repeat;
    background-size: 100%;"></i> <span class="item_title">未发货</span>
                    <? if ($arrStatusCount['lPaidCount'] > 0) { ?>
                        <em class="wait_pay_num">
                            <? if ($arrStatusCount['lPaidCount'] > 99) { ?>
                                ...
                            <? } else { ?>
                                <?= $arrStatusCount['lPaidCount'] ?>
                            <? } ?>
                        </em>
                    <? } ?>
                </div>
            </a> <a href="<?= \yii\helpers\Url::toRoute(["/member/orderlist", 'type' => "delivered"], true) ?>">
                <div class="nav_item flex">
                    <i class="mav_icon icon_three" style="background: url(../images/order/delivering.png) no-repeat;
    background-size: 100%;"></i> <span class="item_title">递送中</span>
                    <? if ($arrStatusCount['lShipCount'] > 0) { ?>
                        <em class="wait_pay_num">
                            <? if ($arrStatusCount['lShipCount'] > 99) { ?>
                                ...
                            <? } else { ?>
                                <?= $arrStatusCount['lShipCount'] ?>
                            <? } ?>
                        </em>
                    <? } ?>
                </div>
            </a> <a href="<?= \yii\helpers\Url::toRoute(["/member/orderlist", 'type' => "success"], true) ?>">
                <div class="nav_item flex">
                    <i class="mav_icon icon_four" ></i> <span class="item_title">已完成</span>
                    <? if ($arrStatusCount['lSuccessCount'] > 0) { ?>
                        <em class="wait_pay_num">
                            <? if ($arrStatusCount['lSuccessCount'] > 99) { ?>
                                ...
                            <? } else { ?>
                                <?= $arrStatusCount['lSuccessCount'] ?>
                            <? } ?>
                        </em>
                    <? } ?>
                </div>
            </a> <a href="<?= \yii\helpers\Url::toRoute(["/member/refundlist"], true) ?>">
                <div class="nav_item flex">
                    <i class="mav_icon icon_five" style="background: url(../images/order/refund.png) no-repeat;
    background-size: 100%;"></i> <span class="item_title">退款/售后</span>
                    <? if ($arrStatusCount['lRefundCount'] > 0) { ?>
                        <em class="wait_pay_num">
                            <? if ($arrStatusCount['lRefundCount'] > 99) { ?>
                                ...
                            <? } else { ?>
                                <?= $arrStatusCount['lRefundCount'] ?>
                            <? } ?>
                        </em>
                    <? } ?>
                </div>
            </a>
        </div>
    </nav>
    <?if ($seller) { ?>
        <nav class="nav">
            <div class="nav_list flex">
                <a href="<?= \yii\helpers\Url::toRoute(["/member/profitdetail"], true) ?>" style="width: 28%">
                    <div class="nav_item flex">
                        <i class="mav_icon shop_four" style="background: url(../images/member/profitlist.png) no-repeat;background-size: 100%;"></i> <span class="item_title">收益明细</span>
                    </div>
                </a>
                <a href="<?= \yii\helpers\Url::toRoute(["/member/profitorder"], true) ?>" style="width: 28%">
                    <div class="nav_item flex">
                        <i class="mav_icon shop_seven" style="background: url(../images/member/orderlist.png) no-repeat;background-size: 100%;"></i> <span class="item_title">销售订单</span>
                    </div>
                </a>
                <a href="<?= \yii\helpers\Url::toRoute(["/seller/myteam"], true) ?>" style="width: 28%">
                    <div class="nav_item flex">
                        <i class="mav_icon shop_two" style="background: url(../images/member/friend.png) no-repeat;background-size: 100%;"></i> <span class="item_title">好友列表</span>
                    </div>
                </a>
            </div>
        </nav>
    <? } ?>
    <section>
        <? if (strpos($_SERVER['REQUEST_URI'], 'shop0')) { ?>
            <div class="personal_list ">
                <a href="<?= \yii\helpers\Url::toRoute([\Yii::$app->request->shopUrl . "/supplier/alliancereg"], true) ?>"
                   class="flex">
                    <div class="person_item">
                        <i class="address"></i> <span>加入我们</span>
                    </div>
                    <div class="arrow"></div>
                </a>
            </div>
        <? } ?>
        <div class="personal_list ">
            <a href="<?= \yii\helpers\Url::toRoute(["/member/addresslist"], true) ?>" class="flex">
                <div class="person_item">
                    <i class="address" style="background: url(../images/home/address.png) no-repeat;background-size: 100% 100%"></i> <span>收货地址管理</span>
                </div>
                <div class="arrow"></div>
            </a>
        </div>

    </section>
</div>
<?= $this->render('/layouts/foot', ['bIndex' => true]) ?>
<? if (!$member) { ?>
    <?php $this->beginBlock('foot') ?>
    <script type="text/javascript">
        var isPageHide = false;
        window.addEventListener('pageshow', function () {
            if (isPageHide) {
                window.location.reload();
            }
        });

        window.addEventListener('pagehide', function () {
            isPageHide = true;
        });
        $(function () {
            if (!isIOS()) {
                var load = sessionStorage.getItem('memberLoad');
                if (load) {
                    location.reload();
                    sessionStorage.removeItem('memberLoad');
                }
            }
            $('.portrait,.person_name').on('click', function () {
                location.href = '/member/login';
            })
            $('nav').on('click', function () {
                sessionStorage.setItem('memberLoad', 'true');
            })

            $('.to_withdrawals').on('click', function () {
                location.href = "/seller/withdrawreq";
            })
        })
    </script>
    <?php $this->endBlock() ?>
<? } ?>
