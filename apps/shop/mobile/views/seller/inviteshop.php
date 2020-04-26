<?php $this->beginBlock('style') ?>
    <script src="/js/qrcode.min.js"></script>
    <link rel="stylesheet" href="/css/settlement.css?<?= \Yii::$app->request->sRandomKey ?>">
    <style>
        html, body {
            margin: 0;
            padding: 0;
            width: 100%;
            height: 100%;
            background: #fff;
        }

        #backgroundImg {
            z-index: 0;
        }

        .detail_content {
            position: relative;
            height: 10.5rem;
        }

        .qrcode_content {
            width: 4rem;
            position: absolute;
            top: 125%;
            right: 4%;
            z-index: 5;
            text-align: center;
        }
    </style>
<?php $this->endBlock() ?>
    <div class="ad_header">
        <a href="javascript:;" class="ad_back" onclick="goBack()">
            <span class="icon">&#xe885;</span>
        </a>
        <h2>邀请开店</h2>
    </div>
    <div class="detail_content">
        <img id="backgroundImg" src="<?= Yii::$app->request->imgUrl . '/' . $img; ?>">
        <div class="qrcode_content" id="qrcode">
            <img src="<?= $url; ?>">
        </div>
    </div>
<? if (\myerm\shop\common\models\MallConfig::getValueByKey("bUpgradeSellerAfterBuyAnything")) { ?>
    <script>
        //生成二维码
        var qrcode = new QRCode(document.getElementById("qrcode"), {
            text: "<?=Yii::$app->request->mallHomeUrl?>?sFrom=inviteshop"
        });
    </script>
<? } ?>