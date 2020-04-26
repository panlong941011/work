<?php $this->beginBlock('style') ?>
    <link rel="stylesheet" href="/css/refundDetail.css?<?=\Yii::$app->request->sRandomKey?>">
<?php $this->endBlock() ?>
    <div class="refund_detail_wrap">
        <div class="ad_header">
            <a href="javascript:goBack();" class="ad_back">
                <span class="icon">&#xe885;</span>
            </a>
            <h2>退款详情</h2>
        </div>
        <? if ($refund->StatusID == 'wait') { ?>
            <!-- 等待 -->
            <div class="refund_detail_status">
                <div class="refund_wait">
                    <i class="icon">&#xe69b;</i>
                    等待卖家确认退款申请
                </div>
                <div class="refund_explain">若卖家 <em class="countdown"></em> 内未处理，将自动同意退款</div>
            </div>
        <? } elseif ($refund->StatusID == 'denyapply') { ?>
            <!-- 拒绝 -->
            <div class="refund_detail_status refuse">
                <div class="refund_wait">
                    <i class="icon">&#xe698;</i>
                    卖家拒绝退款
                </div>
                <div class="refund_explain">拒绝原因 <span><?= $refund->sDenyApplyReason ?></span></div>
                <div class="refund_explain">拒绝说明 <span><?= $refund->sDenyApplyExplain ?></span></div>
            </div>
            <div class="refund_tip">
                若您没有在 <em class="countdown"></em> 内重新修改退货信息，退款申请将自动关闭
            </div>
        <? } elseif ($refund->StatusID == 'denyconfirmreceive') { ?>
            <!-- 拒绝 -->
            <div class="refund_detail_status refuse">
                <div class="refund_wait">
                    <i class="icon">&#xe698;</i>
                    卖家拒绝确认收货
                </div>
                <div class="refund_explain">拒绝说明 <span><?= $refund->sDenyReceiveExplain ?></span></div>
            </div>
            <div class="refund_tip">
                若您没有在 <em class="countdown"></em> 内重新修改退货信息，退款申请将自动关闭
            </div>
        <? } elseif ($refund->StatusID == 'agreeapply') { ?>
            <!-- 同意 -->
            <div class="refund_detail_status agree">
                <div class="refund_wait">
                    <i class="icon">&#xe67d;</i>
                    卖家已同意，请退货
                </div>

                <div class="refund_explain flex">
                    <span>退货地址</span>
                    <p><?= $refund->sAddress ?></p>
                </div>
            </div>
            <div class="refund_tip">
                若您没有在 <em class="countdown"></em> 内未处理，退款申请将自动关闭
            </div>
        <? } elseif ($refund->StatusID == 'success') { ?>
            <!-- 成功 -->
            <div class="refund_detail_status success">
                <div class="refund_wait">
                    <i class="icon">&#xe67d;</i>
                    退款成功
                </div>
                <div class="refund_explain">退款金额 <span>¥<?= number_format($refund->fRefundReal, 2) ?></span></div>
                <div class="refund_explain">到账时间 <span><?=$refund->refundMoneyBack->dSuccessDate?></span></div>
            </div>
        <? } elseif ($refund->StatusID == 'closed') { ?>
            <!-- 关闭 -->
            <div class="refund_detail_status close">
                <div class="refund_wait">
                    <i class="icon">&#xe662;</i>
                    退款关闭
                </div>
            </div>
            <!-- 退货 -->
        <? } elseif ($refund->StatusID == 'returned') { ?>
            <div class="refund_detail_status returned">
                <div class="refund_wait">
                    <i class="icon">&#xe67d;</i>
                    已退货，等待卖家确认收货
                </div>
            </div>
            <div class="refund_tip">
                若卖家在 <em class="countdown"></em> 内未处理，将自动确认收货，返回退款
            </div>
        <? } ?>
        <div class="refund_info">
            <h3 class="layer_title">
                <span class="title_word singleEllipsis">退款信息</span>
            </h3>
            <div class="list_item flex">
                <div class="pic"><img src="<?= Yii::$app->request->imgUrl ?>/<?= $orderDetail->sPic ?>" alt=""></div>
                <div class="info">
                    <h4 class="title "><?= $orderDetail->sName ?></h4>
                    <div class="prop"><?= $orderDetail->sSKU ?></div>
                </div>
            </div>
            <div class="detailed_info">
                <ul>
                    <li class="flex">
                        <span>退款类型</span>
                        <div><?= $refund->sType ?></div>
                    </li>
                    <li class="flex">
                        <span>商品总数量</span>
                        <div><?= $refund->lItemTotal ?></div>
                    </li>
                    <li class="flex">
                        <span>退款数量</span>
                        <div><?= $refund->lRefundItem ?></div>
                    </li>
                    <li class="flex">
                        <span>退款金额</span>
                        <div>¥<?= number_format($refund->fRefundApply, 2) ?></div>
                    </li>
                    <li class="flex">
                        <span>退款原因</span>
                        <div><?= $refund->sReason ?></div>
                    </li>
                    <li class="flex more_list">
                        <span>退款说明</span>
                        <div><?= $refund->sExplain ?></div>
                    </li>
                    <li class="flex more_list">
                        <span>退款编号</span>
                        <div><?= $refund->sName ?></div>
                    </li>
                    <li class="flex more_list">
                        <span>申请时间</span>
                        <div><?= $refund->dNewDate ?></div>
                    </li>
                    <li class="flex more_list">
                        <span>退款凭证</span>
                        <div class="refund_pic">
                            <? $imgList = json_decode($refund->sRefundVoucher, 2) ?>
                            <? if ($imgList) { ?>
                                <? foreach ($imgList as $img) { ?>
                                    <img src="<?= Yii::$app->request->imgUrl ?>/<?= $img ?>" alt="" >
                                <? } ?>
                            <? } else { ?>
                                --
                            <? } ?>
                        </div>
                    </li>
                    <li class="flex more">更多</li>
                </ul>
            </div>
        </div>
    </div>
    <div class="refund_opt flex">
        <a href="/member/refundlog?id=<?= $_GET['id'] ?>">协商记录</a>
        <? if ($refund->StatusID == 'denyapply' || $refund->StatusID == 'wait') { ?>
            <a class="opt_link" href="/member/modifyrefund?id=<?= $_GET['id'] ?>">修改申请</a>
        <? } ?>
        <? if ($refund->StatusID == 'agreeapply') { ?>
            <a class="opt_text opt_link" href="/member/refundship?id=<?= $_GET['id'] ?>">填写退货信息</a>
        <? } ?>

        <? if ($refund->StatusID == 'denyconfirmreceive') { ?>
            <a class="opt_text opt_link" href="/member/modifyrefundship?id=<?= $_GET['id'] ?>">修改退货信息</a>
        <? } ?>

        <? if ($refund->StatusID != 'closed' && $refund->StatusID != 'success') { ?>
        <a href="javascript:cancelApply()" class="red">撤销申请</a>
        <? } ?>

        <? if ($refund->StatusID == 'success') { ?>
        <a href="/member/refundmoney?id=<?= $_GET['id'] ?>">退款去向</a>
        <? } ?>
    </div>
    <div class="mask"></div>
<?php $this->beginBlock('foot') ?>

   <script src="/js/zoomerang.js"></script>
    <script>

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
            //是否刷新
            if( !isIOS() ) {
                var load = sessionStorage.getItem('detailload');
                if (load) {
                    location.reload();
                    sessionStorage.removeItem('detailload');
                }
            }
            
            //图片放大
             Zoomerang.config({
                    maxHeight: 500,
                    maxWidth: 500,
                    bgColor: '#000',
                    bgOpacity: .8
                })
                .listen('.refund_pic img')
           //查看更多
            $('.more').on('click', function () {
                $('.more_list').css('display', 'flex');
                $(this).hide();
            })
            //设置缓存
            $('.opt_link').on('click',function() {
                sessionStorage.setItem('detailload', 'true');
            })

            <? if ($refund->StatusID == 'wait') {
            $lTimeLeft = \myerm\shop\common\models\MallConfig::getValueByKey('lRefundApplyTimeOut') * 86400 + strtotime($refund->dEditDate) - time();
            ?>
            lTimeLeft = <?=$lTimeLeft?>;
            countDown();
            setInterval("lTimeLeft--;countDown()", 1000);
            <? } elseif ($refund->StatusID == 'denyapply') {
            $lTimeLeft = \myerm\shop\common\models\MallConfig::getValueByKey('lRefundDenyApplyTimeOut') * 86400 + strtotime($refund->dDenyApplyDate) - time();
            ?>
            lTimeLeft = <?=$lTimeLeft?>;
            countDown();
            setInterval("lTimeLeft--;countDown()", 1000);
            <? } elseif ($refund->StatusID == 'agreeapply') {
            $lTimeLeft = \myerm\shop\common\models\MallConfig::getValueByKey('lRefundAgreeTimeOut') * 86400 + strtotime($refund->dAgreeApplyDate) - time();
            ?>
            lTimeLeft = <?=$lTimeLeft?>;
            countDown();
            setInterval("lTimeLeft--;countDown()", 1000);
            <? } elseif ($refund->StatusID == 'returned') {
            $lTimeLeft = \myerm\shop\common\models\MallConfig::getValueByKey('lRefundShipTimeOut') * 86400 + strtotime($refund->dShipDate) - time();
            ?>
            lTimeLeft = <?=$lTimeLeft?>;
            countDown();
            setInterval("lTimeLeft--;countDown()", 1000);
            <? } elseif ($refund->StatusID == 'denyconfirmreceive') {
            $lTimeLeft = \myerm\shop\common\models\MallConfig::getValueByKey('lRefundDenyReceiveTimeOut') * 86400 + strtotime($refund->dDenyReceiveDate) - time();
            ?>
            lTimeLeft = <?=$lTimeLeft?>;
            countDown();
            setInterval("lTimeLeft--;countDown()", 1000);

            <? } ?>
        })

        function countDown() {
            if (lTimeLeft >= 0) {
                var d = Math.floor(lTimeLeft / 86400);
                var h = Math.floor((lTimeLeft - d * 86400) / 3600);
                var m = Math.floor((lTimeLeft - d * 86400 - 3600 * h) / 60);
                var i = lTimeLeft - d * 86400 - 3600 * h - m * 60;

                $(".countdown").html(d + "天" + h + "小时" + m + "分" + i + "秒");
            } else {
                $(".countdown").html("0小时0分0秒");
            }
        }
        
        function cancelApply() {
            $('.mask').show();
            shoperm.selection('确认撤销退款申请?',revoke,cancel);
           
        }
        function revoke() {
            $('.mask').hide();
             $.post('/member/cancelapply?id=<?=$_GET['id']?>',
                {
                    '_csrf': '<?=\Yii::$app->request->getCsrfToken()?>',
                },
                function () {
                    location.reload();
                }
            )
        }
        function cancel() {
            $('.mask').hide();
        }
    </script>
<?php $this->endBlock() ?>