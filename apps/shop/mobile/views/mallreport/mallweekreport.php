<?php
/**
 * Created by PhpStorm.
 * User: ouyangyz <ouyangyanzhong@163.com>
 * Date: 2019/3/13
 * Time: 18:40
 */
?>
<?php $this->beginBlock('style') ?>
<link rel="stylesheet" href="/css/mallreport.css?<?= \Yii::$app->request->sRandomKey . time() ?>">
<link rel="stylesheet" href="/css/member.css?<?= \Yii::$app->request->sRandomKey . time() ?>">
<?php $this->endBlock() ?>
<div class="member_set">
    <div class="ad_header">
        <a href="javascript:;" class="ad_back" onclick="goBack()"> <span class="icon">&#xe885;</span> </a>
        <h2><?=$this->title?></h2>
        <span class="ad_more icon">&#xe602;</span>
    </div>
</div>
<div class="content">
    <div class="con-nav">
        <ul>
            <li>
                <a href="<?= \Yii::$app->request->shopRootUrl ?>/mallreport/malldayreport">日报表</a>
            </li>
            <li class="cur">
                <a href="javascript:;">周报表</a>
            </li>
            <li>
                <a href="<?= \Yii::$app->request->shopRootUrl ?>/mallreport/mallmonthreport">月报表</a>
            </li>
        </ul>
    </div>
    <div class="tab">
        <table cellpadding="0" cellspacing="0" border="0">
            <thead>
            <tr>
                <th>&nbsp;</th>
                <th><?= $dDate.' / '.$dEndDate ?></th>
                <th><?= $dLastDate.' / '.$dLastEndDate ?></th>
            </tr>
            </thead>
            <tbody class="tab-tbody">
            <tr>
                <td>营业额</td>
                <td><?= $report->fTotalPerformance ?></td>
                <td><?= $lastDayReport->fTotalPerformance ?></td>
            </tr>
            <tr>
                <td>总订单数</td>
                <td><?= $report->lTotalOrder ?></td>
                <td><?= $lastDayReport->lTotalOrder ?></td>
            </tr>
            <tr>
                <td>总购买人数</td>
                <td><?= $report->lTotalBuyer ?></td>
                <td><?= $lastDayReport->lTotalBuyer ?></td>
            </tr>
            <tr>
                <td>客单价</td>
                <td><?= $report->fPCT ?></td>
                <td><?= $lastDayReport->fPCT ?></td>
            </tr>
            <tr>
                <td>有销售经销商总数</td>
                <td><?= $report->lHaveSales ?></td>
                <td><?= $lastDayReport->lHaveSales ?></td>
            </tr>
            <tr>
                <td>新增经销商总数</td>
                <td><?= $report->lNewSeller ?></td>
                <td><?= $lastDayReport->lNewSeller ?></td>
            </tr>
            <tr>
                <td>总退款订单数</td>
                <td><?= $report->lOrderRefund ?></td>
                <td><?= $lastDayReport->lOrderRefund ?></td>
            </tr>
            </tbody>

        </table>
    </div>
    <div class="btns">
        <a href="<?= \Yii::$app->request->shopRootUrl ?>/mallreport/mallweekreport?dDate=<?= $dLastDate ?>"><< 上一周</a>
		<? if ($bToday) { ?>
            <a href="javascript:;" style="background-color: #EEE">下一周 >></a>
		<? } else { ?>
            <a href="<?= \Yii::$app->request->shopRootUrl ?>/mallreport/mallweekreport?dDate=<?= $dNextDate ?>">下一周 >></a>
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
<script src="/js/common.js?<?= \Yii::$app->request->sRandomKey ?>"></script>
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























