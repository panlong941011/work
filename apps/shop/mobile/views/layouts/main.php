<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0" name="viewport"/>
    <meta name="format-detection" content="telephone=no,email=no,date=no,address=no">
    <meta name="wap-font-scale" content="no">
    <meta name="imagemode" content="force">
    <meta name="full-screen" content="yes">
    <meta name="x5-fullscreen" content="true">
    <meta http-equiv="Access-Control-Allow-Origin" content="*">
    <meta http-equiv="Expires" content="-1">
    <meta http-equiv="Cache-Control" content="no-cache">
    <meta http-equiv="Pragma" content="no-cache">

    <title><?= \yii\helpers\Html::encode($this->title) ?></title>
    <link rel="shortcut icon" href="/favicon.ico?1"/>
    <link rel="stylesheet" href="/css/common.css?<?= \Yii::$app->request->sRandomKey ?>">
    <?= $this->blocks['style']; ?>
    <script src="/js/hotcss.js?<?= \Yii::$app->request->sRandomKey ?>"></script>
    <script>
        var sHomeUrl = "<?=Yii::$app->request->mallHomeUrl?>";
        var dNow = "<?=Yii::$app->formatter->asDatetime(time())?>";
        dNow = dNow.replace(/\-/g, "/");
    </script>
</head>
<body>
<?= $content ?>

<!-- 选择框 -->
<div class="selection_bar">
    <div class="select_name"></div>
    <div class="select_chose flex">
        <span class="select_cancel flexOne">取消</span>
        <span class="select_sure flexOne">确认</span>
    </div>
</div>
<!-- 提示框 -->
<div id="massage"></div>
<!-- 加载图 -->
<div class="weui-loading-toast" style="display: none;">
    <div class="weui-mask_transparent"></div>
    <div class="weui-toast">
        <i class="weui-loading weui-icon_toast"></i>
    </div>
</div>

</body>
<script src="/js/fastclick.js"></script>
<script src="/js/zepto.min.js"></script>
<script src="/js/vue.min.js"></script>
<script src="/js/common.js?<?= \Yii::$app->request->sRandomKey ?>"></script>
<script>
    //解决点触延迟问题
    $(function() {
        FastClick.attach(document.body);
    });
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
        sharePage('来瓜分',
            '疫情无情，来瓜分有爱',
            'https://yl.aiyolian.cn/images/home/logo.png',
            location.href);
    </script>
<? } ?>
<?= isset($this->blocks['foot']) ? $this->blocks['foot'] : "" ?>

</html>