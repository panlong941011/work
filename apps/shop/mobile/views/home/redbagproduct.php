<?php $this->beginBlock('style') ?>
    <link rel="stylesheet" href="/css/member.css?<?= \Yii::$app->request->sRandomKey ?>">
<?php $this->endBlock() ?>
    <section>
        <? foreach ($arrRed as $red) { ?>
            <div class="personal_list ">
                <?if($_GET['groupID']){?>
                <a href="<?= \yii\helpers\Url::toRoute([\Yii::$app->request->shopUrl . "/product/membergroup"], true).'?id='.$_GET['groupID'] ?>" class="flex">
                    <?}else{?>
                <a href="<?= \yii\helpers\Url::toRoute([\Yii::$app->request->shopUrl . "/home"], true) ?>" class="flex">
                    <?}?>
                    <div class="person_item">
                        <i class="shopabout"></i> <span> <?= $red->sName ?>购买立减<?= $red->fChange ?>，去抵用</span>
                    </div>
                    <div class="arrow"></div>
                </a>
            </div>
        <? } ?>
    </section>
    <div class="mask"></div>
    <div class="redimg"><img src="/images/home/redproduct.png"></div>
    <style>
        .change_pwd {
            line-height: 2rem;
            font-weight: bold;
            color: red;
            font-size: 0.6rem;
            padding-left: 1rem;
        }
        section .personal_list .arrow{
            width: 1rem;
        }
        .redimg {
            position: fixed;
            top: 3rem;
            left: 1rem;
            z-index: 101;
            display: none;
        }

        .redimg img {
            height: auto;
            width: 14rem
        }
    </style>
<?= $this->render('/layouts/foot', ['bIndex' => true]) ?>
<?php $this->beginBlock('foot') ?>
<script src='/js/jquery.min.js'></script>
<script>
    var redbag =<?=$bnew?>;
    if (redbag) {
        $('.mask').show();
        $('.redimg').show();
        $('section').hide();
    }
    $('.mask,.redimg').on('click', function () {
        $('.redimg').hide();
        $('.mask').hide();
        $('section').show();
    });
</script>

<?php $this->endBlock() ?>
