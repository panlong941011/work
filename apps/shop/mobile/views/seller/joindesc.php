<?php $this->beginBlock('style') ?>
<link rel="stylesheet" href="/css/supplierMain.css?<?= \Yii::$app->request->sRandomKey ?>">
<?php $this->endBlock() ?>
<div class="be_supplier">
    <div class="ad_header">
        <a href="javascript:;" class="ad_back" onclick="goBack()"> <span class="icon">&#xe885;</span> </a>
        <h2>成为经销商</h2>
        <span class="ad_more icon">&#xe602;</span>
    </div>
    <div class="sup_content" style="text-indent: 0em">
		<?= \myerm\shop\common\models\SellerConfig::findValueByKey('sJoinDesc') ?>
    </div>
    <div class="apply">立即申请</div>
</div>
<div class="nav_more_list">
    <div class="triangle-up"></div>
    <ul>
        <li class="flex" onclick="location.href='<?= \Yii::$app->request->mallHomeUrl ?>'">
            <span class="icon">&#xe608;</span> <em>首页</em>
        </li>
        <li class="flex"
                onclick="location.href='<?= \yii\helpers\Url::toRoute(["/cart"], true) ?>'">
            <span class="icon">&#xe60a;</span> <em>购物车</em>
        </li>
        <li class="flex"
                onclick="location.href='<?= \yii\helpers\Url::toRoute(["/member"],
					true) ?>'">
            <span class="icon">&#xe64a;</span> <em>我的</em>
        </li>
    </ul>
</div>
<?php $this->beginBlock('foot') ?>

<script>
    $(function () {
        $('.ad_more').on('click', function () {
            event.stopPropagation();
            $(".nav_more_list").toggle();
        })
        $(window).on('click', function () {
            $(".nav_more_list").hide();
        })

        $('.apply').on('click', function () {
			<? if (!Yii::$app->frontsession->bLogin) { ?>
            shoperm.showTip('请登录');
            setTimeout(function () {
                location.href = "/member/login";
            }, 2000);
			<? } elseif (Yii::$app->frontsession->seller) { ?>
            alert('您已经是经销商，不得再提交申请');
            location.href = sHomeUrl;
			<? } elseif (Yii::$app->frontsession->wholesaler) { ?>
            alert('您已经是渠道商，不得申请经销商');
            location.href = sHomeUrl;
			<? } else { ?>
            location.href = '<?=\yii\helpers\Url::toRoute("/seller/giftcheck", true)?>';
			<? } ?>
        })
    })
</script>
<? if (\Yii::$app->params['isWeChat'] && Yii::$app->request->userIP != '127.0.0.1') { ?>
    <script src="https://res.wx.qq.com/open/js/jweixin-1.0.0.js" type="text/javascript" charset="utf-8"></script>
    <script type="text/javascript" charset="utf-8">
        //微信分享配置信息
        wx.config(<?php echo \Yii::$app->wechat->js->config(array(
			'onMenuShareTimeline',
			'onMenuShareAppMessage',
			'onMenuShareQQ',
			'onMenuShareWeibo',
			'onMenuShareQZone'
		), false) ?>);
        //微信分享
        sharePage('<?= \myerm\shop\common\models\MallConfig::getValueByKey('sSellerJoinShareTitle') ?>', '<?=Yii::$app->request->imgUrl?>/<?=\myerm\shop\common\models\MallConfig::getValueByKey('sMallLogo')?>', '', location.href);
    </script>
<? } ?>
<?php $this->endBlock() ?>

