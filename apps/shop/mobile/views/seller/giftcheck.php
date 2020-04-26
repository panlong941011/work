<?php $this->beginBlock('style') ?>
    <link rel="stylesheet" href="/css/car.css?<?= \Yii::$app->request->sRandomKey ?>">
    <style>
        .disable {
            background: #f5f5f5;
            color: #999;
        }


    </style>
<?php $this->endBlock() ?>
    <div id="app">
        <div class="car_wrap">
            <div class="car_main">
                <div class="car_header">
                    <a href="javascript:;" onclick="goBack()" class="car_back">
                        <span class="icon">&#xe885;</span>
                    </a>
                    <h2>升级大礼包</h2>
                </div>
                <div class="c_line"></div>
                <form action="join" method="get">
                    <div class="car_list">
                        <? foreach ($arrJuniorConfig as $key => $value) { ?>
                            <div class="car_commodity">
                                <a class="commodity_title flex">
                                    <div>
                                    <span class="all_icon" style="cursor: pointer"
                                          onclick="selectGift(this,'<?= $key ?>')"></span>
                                    </div>
                                    <h2 class="singleEllipsis"><?= $key ?>
                                    </h2>
                                </a>
                                <ul>
                                    <? foreach ($value as $product) { ?>
                                        <li>
                                            <div class="commodity_content flex">
                                                <div class="commodity_detail flex flexOne">
                                                    <div class="commodity_pic">
                                                        <a>
                                                            <img src="<?= $product['image'] ?>" alt="">
                                                        </a>
                                                        <div class="car_spckill"></div>
                                                    </div>
                                                    <div class="commodity_info flexOne">
                                                        <a>
                                                            <h2 class="multiEllipsis"><?= $product['sName'] ?></h2>
                                                        </a>
                                                        <p class="commodity_stock">数量：<?= $product['lQuantity'] ?></p>

                                                        <em>¥<?= number_format($product['fPrice'], 2) ?></em>
                                                    </div>
                                                </div>
                                            </div>
                                        </li>
                                    <? } ?>
                                </ul>
                            </div>
                        <? } ?>
                    </div>
                    <input type="hidden" name="giftTitle" id="giftTitle" value="">
                    <input type="submit" style="display: none" id="submit_form">
                </form>
            </div>

        </div>
        <footer>
            <div class="accounts_cell flex">
                <div class="account_info flex">
                </div>
                <div class="account_btn">

                    <span style="cursor: pointer" onclick="submitGift()">立即申请</span>
                </div>
            </div>
        </footer>

        <div class="mask"></div>
    </div>


<?php $this->beginBlock('foot') ?>

    <script>

        function selectGift(obj, giftTitle) {
            $('.all_icon').removeClass('car_on');
            $(obj).addClass('car_on');
            $('#giftTitle').val(giftTitle);
        }

        function submitGift() {
            var giftTitle = $('#giftTitle').val();
            if (!giftTitle) {
                shoperm.showTip('请选择大礼包');
                return;
            }

            $('#submit_form').click();
        }

    </script>

<?php $this->endBlock() ?>