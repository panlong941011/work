<?php $this->beginBlock('style') ?>
<link rel="stylesheet" href="/css/mallreport.css?<?= \Yii::$app->request->sRandomKey . time() ?>">
<link rel="stylesheet" href="/css/member.css?<?= \Yii::$app->request->sRandomKey . time() ?>">
<?php $this->endBlock() ?>
<div class="member_set">
    <div class="ad_header">
        <a href="javascript:;" class="ad_back" onclick="goBack()"> <span class="icon">&#xe885;</span> </a>
        <h2><?= $this->title ?></h2>
        <span class="ad_more icon">&#xe602;</span>
    </div>
</div>
<div class="content">
    <div class="con-nav">
        <ul id='change'>
            <li <? if ($sType == 'fTotal'){ ?>class="cur"<? } ?>>
                <a href="<?= \Yii::$app->request->shopRootUrl ?>/mallreport/productrank?type=fTotal">金额排名</a>
            </li>
            <li <? if ($sType == 'lQuantity'){ ?>class="cur"<? } ?>>
                <a href="<?= \Yii::$app->request->shopRootUrl ?>/mallreport/productrank?type=lQuantity
">数量排名</a>
            </li>
        </ul>
    </div>
    <div class="tab">
        <table cellpadding="0" cellspacing="0" border="0">
            <thead>
            <tr>
                <th style="flex: 1">&nbsp;排名</th>
                <th style="flex: 3">商品名称</th>
                <th style="flex: 2">统计</th>
            </tr>
            </thead>
            <tbody class="tab-tbody">
			<? if ($arrRank) { ?>
				<? foreach ($arrRank as $key => $item) { ?>
                    <tr>
                        <td style="flex: 1"><?= $key + 1 ?></td>
                        <td style="flex: 3"><?= $item['sName'] ?></td>
                        <td style="flex: 2">
							<? if ($sType == 'fTotal') { ?>
								<?= $item['fTotal'] ?>
							<? } else { ?>
								<?= $item['lQuantity'] ?>
							<? } ?>
                        </td>
                    </tr>
				<? } ?>
			<? } else { ?>
                <tr>
                    <td>暂无数据</td>
                </tr>
			<? } ?>
            </tbody>

        </table>
    </div>
</div>

<div class="nav_more_list">
    <div class="triangle-up"></div>
    <ul>
        <li class="flex" onclick="location.href='<?= \Yii::$app->request->mallHomeUrl ?>'">
            <span class="icon">&#xe608;</span> <em>首页</em>
        </li>
        <li class="flex" onclick="location.href='<?= \yii\helpers\Url::toRoute(["/cart"], true) ?>'">
            <span class="icon">&#xe60a;</span> <em>购物车</em>
        </li>
        <li class="flex" onclick="location.href='<?= \yii\helpers\Url::toRoute(["/member"], true) ?>'">
            <span class="icon">&#xe64a;</span> <em>我的</em>
        </li>
    </ul>
</div>
<?php $this->beginBlock('foot') ?>
<script src="/js/common.js?<?= \Yii::$app->request->sRandomKey . time() ?>"></script>
<script>
    $(function () {
        $('#change li').click(function () {
            $(this).addClass('cur').siblings().removeClass('cur');
        });

    })
    $(function () {
        $('.ad_more').on('click', function () {
            event.stopPropagation();
            $(".nav_more_list").toggle();
        })
        $(window).on('click', function () {
            $(".nav_more_list").hide();
        })
    })
</script>
<script>
    function goBack() {
        location.href = "<?= \Yii::$app->request->shopRootUrl ?>/mallreport/index";
    }
</script>
<?php $this->endBlock() ?>