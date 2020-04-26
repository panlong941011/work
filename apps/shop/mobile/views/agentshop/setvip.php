<?php $this->beginBlock('style') ?>
<link rel="stylesheet" href="/css/mescroll.min.css">
<link rel="stylesheet" href="/css/settlement.css">
<?php $this->endBlock() ?>
<style type="text/css">
    body, html {
        height: 100%;
    }

    .team_manage {
        height: 100%;
    }

    .t_m_content {
        width: 100%;
        z-index: 10;
        max-width: 16rem;
        margin: 0 auto;
    }

    .team_manage .team_person {
        background-color: #fff;
        background: linear-gradient(#fff, #fff);
        height: 6rem;
    }

    .item_a {
        display: flex;
        flex-direction: row;
        width: 100%;
        padding: .5rem;
        padding-bottom: 0.5rem;
    }

    .item_a .pic {
        width: 3.5rem;
        height: 3.5rem;
    }

    .item_a .pic img {
        width: 3rem;
        height: 3rem;
    }

    .item_a .txt {
        flex: 1;
        padding-left: .2rem;
        font-size: .5rem;
    }

    .item_a .txt > h6 {
        overflow: hidden;
        text-overflow: ellipsis;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        white-space: normal;
        font-size: 0.56rem;
    }

    .txt h6 {
        width: 80%;
        overflow: hidden;
        white-space: nowrap;
        text-overflow: ellipsis;
        float: left;
        color: rgba(56, 56, 56, 1);
    }

    .item_a .txt > h3 {
        font-size: .49rem;
        color: rgba(166, 166, 166, 1);
        margin-top: 10px;
    }

    .item_a .txt .info {
        color: rgba(56, 56, 56, 1);
    }

    .clearfloat {
        clear: both;
    }

    .team_main {
        clear: both;
        margin-top: 0.5rem;
    }

    .t_i_info input {
        width: 6rem;
        height: 1rem;
        border: grey 1px solid;
        font-size: 30px;
    }

    button {
        display: block;
        width: 14.72rem;
        height: 1.92rem;
        -webkit-border-radius: .2133333333rem;
        border-radius: .2133333333rem;
        background: #3394FF;
        text-align: center;
        line-height: 1.92rem;
        color: #fff;
        margin: 0 auto;
        margin-bottom: .64rem;
        margin-top: 1rem;
        font-size: 30px;
    }
</style>
<div class="team_manage">
    <div class="t_m_content">
        <div class="team_person">
            <a class="item_a" href="/product/detail/?id=<?= $product->lID ?>">
                <div class="pic">
                    <img src="http://product.aiyolian.cn/<?= $product->sMasterPic ?>" alt=""></div>
                <div class="txt">
                    <h6><?= $product->sName ?></h6>
                    <div class="clearfloat"></div>
                    <h3>零售价：<span>￥<?= $product->fPrice ?></span>
                        利润：<span>￥<?= $product->fPrice - $product->fSupplierPrice ?></span></h3>
                    <input type="hidden" id="fSupplierPrice" value="<?= $product->fSupplierPrice ?>">
                    <input type="hidden" id="ProductID" value="<?= $product->lID ?>">
                    <h3 class="info">供货价：<?= $product->fSupplierPrice ?></h3>
                    <h3 class="info">库存：<?= $product->lStock ?></h3>
                    <h3 class="info">上架：<?= $product->dNewDate ?></h3></div>
            </a>
        </div>
        <div class="team_main">

            <ul class="team_list">
                <? foreach ($arrVip as $vip) { ?>
                    <li class="team_item">
                        <div class="flex">
                            <div class="t_i_pic">
                                <img src="<?= $vip->sAvatarPath ?>">
                            </div>
                            <div class="t_i_info">
                                <h3><?= $vip->sName ?></h3>
                                <input type="hidden" name="vipID[]" value="<?= $vip->lID ?>">
                                <h3>拿货价：<input class="viprice" type="text" vip="<?= $vip->lID ?>" name="vipPrice[]"
                                               value="<?= $vip->viprice ? $vip->viprice->fPrice : $vipPrice ?>"></h3>
                            </div>
                        </div>
                    </li>
                <? } ?>
            </ul>
        </div>
        <input type="hidden" id="csrf" name="_csrf" value="<?= \Yii::$app->request->getCsrfToken() ?>">
        <button data-finished="finished" class="submit" id="submit">确认</button>
    </div>
</div>

<?php $this->beginBlock('foot') ?>
<script>
    $('#submit').bind('click', function () {
        var supplerPrice = $('#fSupplierPrice').val();
        var csrf = $('#csrf').val();
        var ProductID = $('#ProductID').val();
        var vipList='';
        var priceList='';
        var error=false;
        $('.viprice').each(function (obj) {
            if ($(this).val() < supplerPrice) {
                shoperm.showTip('会员价不能低于供应价');
                error=true;
                return;
            }
            vipList+=$(this).attr('vip')+',';
            priceList+=$(this).val()+',';
        });
        if(error){
            return;
        }
        $.post(
            '/agentshop/setvip',
            {
                _csrf: csrf,
                vipList: vipList,
                priceList: priceList,
                ProductID: ProductID
            },
            function (data) {
                if (data.status) {
                    shoperm.showTip(data.message);
                } else {
                    shoperm.showTip(data.message);
                }
            }
        )
    });
</script>
<?php $this->endBlock() ?>

