<?php $this->beginBlock('style') ?>
    <link rel="stylesheet" href="/css/member.css?<?= \Yii::$app->request->sRandomKey ?>">
<?php $this->endBlock() ?>
    <section>
        <? foreach ($arrRed as $red) { ?>
            <div class="personal_list ">
                <a href="<?= \yii\helpers\Url::toRoute([\Yii::$app->request->shopUrl . "/home"], true) ?>" class="flex">
                    <div class="person_item">
                        <i class="shopabout"></i> <span> 满<?= $red->fTopMoney ?>，减<?= $red->fChange ?>，去购物</span>
                    </div>
                    <div class="arrow"></div>
                </a>
            </div>
        <? } ?>
    </section>
    <style>
        .change_pwd {
            line-height: 2rem;
            font-weight: bold;
            color: red;
            font-size: 0.6rem;
            padding-left: 1rem;
        }
    </style>
<?= $this->render('/layouts/foot', ['bIndex' => true]) ?>