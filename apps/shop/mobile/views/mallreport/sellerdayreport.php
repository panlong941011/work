<?php
/**
 * Created by PhpStorm.
 * User: ouyangyz <ouyangyanzhong@163.com>
 * Date: 2019/3/14
 * Time: 10:10
 */
?>
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
        <ul>
            <li <?= $type[1] == 'day' ? 'class="cur"' : '' ?>>
                <a href="<?= \Yii::$app->request->shopRootUrl ?>/mallreport/sellerdayreport">日排行</a>
            </li>
            <li <?= $type[1] == 'week' ? 'class="cur"' : '' ?>>
                <a href="<?= \Yii::$app->request->shopRootUrl ?>/mallreport/sellerweekreport">周排行</a>
            </li>
            <li <?= $type[1] == 'month' ? 'class="cur"' : '' ?>>
                <a href="<?= \Yii::$app->request->shopRootUrl ?>/mallreport/sellermonthreport">月排行</a>
            </li>
        </ul>
    </div>
    <div class="tab tabdiv">
        <table cellpadding="0" cellspacing="0" border="0">
            <thead>
            <tr>
                <th colspan="2">当<?= $type['0'] ?>：<?= $dDate ?><?= $dEndDate ? ' / ' . $dEndDate : ''; ?></th>
            </tr>
            <tr>
                <th>昵称</th>
                <th>营业额</th>
            </tr>
            </thead>
            <tbody class="tab-tbody">
			<? if ($report) { ?>
				<? foreach ($report as $value) { ?>
                    <tr>
                        <td><?= $value->seller->sName ?></td>
                        <td><?= $value->fPerformance ?></td>
                    </tr>
				<? }
			} else { ?>
                <tr>
                    <td>暂无数据</td>
                </tr>
			<? } ?>
            </tbody>

        </table>
    </div>
    <div class="btns">
        <a href="<?= \Yii::$app->request->shopRootUrl ?>/mallreport/seller<?= $type['1'] ?>report?dDate=<?= $dLastDate ?>"><< 前一<?= $type['0'] ?></a>
		<? if ($bToday) { ?>
            <a href="javascript:;" style="background-color: #EEE">后一<?= $type['0'] ?> >></a>
		<? } else { ?>
            <a href="<?= \Yii::$app->request->shopRootUrl ?>/mallreport/seller<?= $type['1'] ?>report?dDate=<?= $dNextDate ?>">后一<?= $type['0'] ?> >></a>
		<? } ?>
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
