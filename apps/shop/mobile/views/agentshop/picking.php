<?php
/**
 * 商品列表页
 * Created by PhpStorm.
 * User: oyyz <oyyz@3elephant.com>
 * Date: 2017/10/7 0007
 * Time: 上午 10:41
 */

use yii\helpers\Url;

?>

<?php $this->beginBlock('style') ?>
<link rel="stylesheet" href="/css/classify.css?<?= \Yii::$app->request->sRandomKey ?>">
<style>
    .item-lists a {
        display: flex;
        flex-direction: row;
        width: 90%;
        padding-top: .5rem;
    }

    .item-lists .pic {
        width: 5rem;
        height: 5rem;
    }

    .item-lists .pic img {
        width: 5rem;
        height: 5rem;
    }

    .item-lists .txt {
        flex: 1;
        padding-left: .2rem;
        font-size: .5rem;
    }

    .item-lists .txt > h6 {
        overflow: hidden;
        text-overflow: ellipsis;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        white-space: normal;
        font-size: 0.6rem;
        line-height: 0.8rem;
        margin-bottom: 1rem;
    }

    .item-lists .txt > h3 {
        font-size: .55rem;
        color: rgba(166, 166, 166, 1);
        margin-top: 0.2rem;
    }

    .item-lists .txt > h3 > span {
        color: rgba(238, 82, 82, 1);
    }

    .descript {
        padding: 0.15rem 0.4266666667rem 0.4266666667rem;
        font-size: .45rem;
        color: rgba(255, 87, 51, 1);
        margin-top: 10px;
        background: #ffffff;
    }

    .agentrecommendword {
        left: 77px;
        padding: 0.2rem 0.3rem;
        top: 138px;
        color: rgba(140, 137, 137, 1);
        background-color: rgba(229, 229, 229, 1);
        font-size: .4rem;
        text-align: center;
    }

    .txt h6 {
        width: 90%;
        overflow: hidden;
        white-space: nowrap;
        text-overflow: ellipsis;
        color: rgba(56, 56, 56, 1);
        line-height: 0.6rem;
    }

    .delete_recommend {
        width: .9rem;
        padding-top: .5rem;
    }

    .item_a {
        width: 95%;
        float: left;
    }

    .clearfloat {
        clear: both;
    }

    .message_alert {
        position: fixed;
        top: 50%;
        left: 50%;
        -webkit-transform: translate(-50%, -50%);
        transform: translate(-50%, -50%);
        width: 10.6666666667rem;
        height: 5.9733333333rem;
        -webkit-border-radius: 0.2133333333rem;
        border-radius: 0.2133333333rem;
        background: #fff;
        z-index: 220;
        display: none;
    }

    .message_alert .alert_title {
        color: rgba(80, 80, 80, 1);
        padding: .9rem 0.8533333333rem 0.8533333333rem;
        font-size: .8rem;
        width: 90%;
        border-bottom: 1px solid rgba(221, 221, 221, 1);
        margin: 0 auto;
    }

    .message_alert .alert_btn {
        width: 4.5333333333rem;
        height: 1.7066666667rem;
        -webkit-border-radius: 0.2133333333rem;
        border-radius: 0.2133333333rem;
        text-align: center;
        line-height: 1.7066666667rem;
        margin-left: .4rem;
        margin-top: .5rem;
        display: inline-block;
        font-size: 0.65rem;
    }

    .cancle_delete_recommend, .cancle_delete_select {
        background-color: rgba(229, 229, 229, 1);
        color: rgba(80, 80, 80, 1);
    }

    .confirm_delete_recommend, .confirm_delete_select {
        background-color: rgba(212, 48, 48, 1);
        color: rgba(255, 255, 255, 1);
    }

    .fixed {
        display: inline-block;
        height: 2.4rem;
    }

    .cat-con {
        box-sizing: border-box;
        margin: 0 0 0 4.352rem;
        padding: 0;
        height: calc(100vh - 3.35rem);
        overflow-y: auto;
        -webkit-overflow-scrolling: touch;
        margin-left: 0.2rem;
    }

    .selected {
        position: absolute;
        bottom: 0.5rem;
        font-size: 0.6rem;
        display: block;
        width: 3rem;
        height: 1.2rem;
        line-height: 1.2rem;
        right: .2rem;
        text-align: center;
        border-radius: 0.4rem;
        color: rgba(255, 255, 255, 1);
        background-color: rgba(128, 128, 128, 1);
    }

    .unselect {
        position: absolute;
        bottom: 0.5rem;
        font-size: 0.6rem;
        display: block;
        width: 3rem;
        height: 1.2rem;
        line-height: 1.2rem;
        right: .2rem;
        text-align: center;
        border-radius: 0.4rem;
        color: rgba(255, 255, 255, 1);
        background-color: rgba(212, 48, 48, 1);
    }

    .addrecommend {
        position: absolute;
        top: 2.8rem;
        font-size: 0.5rem;
        display: block;
        width: 3rem;
        height: 1rem;
        line-height: 1rem;
        right: 2.2rem;
        text-align: center;
        border-radius: 0.4rem;
        color: rgba(255, 255, 255, 1);
        background-color: rgba(212, 48, 48, 1);
    }

    .addrecommend2 {
        position: absolute;
        top: 2.8rem;
        font-size: 0.5rem;
        display: block;
        width: 2.4rem;
        height: 1rem;
        line-height: 1rem;
        right: .2rem;
        text-align: center;
        border-radius: 0.4rem;
        color: rgba(255, 255, 255, 1);
        background-color: rgba(212, 48, 48, 1);
    }

    .item-lists {
        position: relative;
        align-items: center;
        border-bottom: 1px solid #ddd;
        padding-bottom: 0.5rem;
    }

    .cat-con .item-cur {
        margin-bottom: 5rem;
    }


    /* 分块栏 */
    .producttype {
        background-color: rgba(255, 255, 255, 1);
        height: 2rem;
    }

    .type_point {
        color: rgba(212, 48, 48, 1);
    }

    .part_point {
        border-bottom: 2px solid rgba(212, 48, 48, 1);
    }

    .productpart {
        width: 5rem;
        height: 2rem;
        margin-left: 15%;
        font-size: .6rem;
        font-weight: lighter;
        float: left;
        vertical-align: middle;
        line-height: 2rem;
        text-align: center;
    }
</style>
<?php $this->endBlock() ?>
<div class="producttype">
    <div class="productpart"><a href="<?= Url::toRoute([\Yii::$app->request->shopUrl . "/supplier/alliance"], true) ?>">联盟商</a>
    </div>
    <div class="productpart part_point type_point">爆品推荐</div>
    <div class="clearfloat"></div>
</div>
<div class="descript">
    <p>
        进货说明：挑中的商品将按分类展示在商城；进货单可以查看您的货源
    </p>
</div>

<div class="category_wrap">
    <? if ($arrCat) { ?>
        <div class="cat-viewport">

            <div class="cat-con" id="cat-con">
                <!--初始化第一个加item-cur-->
                <div class="con-item item-cur">
                </div>
            </div>
        </div>
    <? } ?>
</div>

<div class="fixed">
    &nbsp;
</div>
<?= $this->render('/layouts/foot', ['bIndex' => true]) ?>

<div class="message_alert alert_recommend">
    <h2 class="alert_title">是否移出店长推荐</h2>
    <div class="alert_reason"></div>
    <div class="alert_btn cancle_delete_recommend">否</div>
    <div class="alert_btn confirm_delete_recommend">是</div>
    <span class="alert_close"></span>
</div>

<div class="message_alert alert_select">
    <h2 class="alert_title">确定不展示在商城</h2>
    <div class="alert_reason"></div>
    <div class="alert_btn cancle_delete_select">否，保留</div>
    <div class="alert_btn confirm_delete_select">是</div>
    <span class="alert_close"></span>
</div>


<!-- 遮罩 -->
<div class="mask"></div>


<?php $this->beginBlock('foot') ?>

<script>
    var ProductID = '';
    var bTop = '<?=$bTop?>';
    $(function () {
        //TAB切换
        $('#cat-nav ul li').on('click', function () {
            var index = $('#cat-nav ul li').index(this);
            $(this).addClass('cur').siblings().removeClass('cur');
            $('.con-item').eq(index).addClass('item-cur').siblings().removeClass('item-cur');

            $(".item-lists").remove();
            getCatProduct($(this).attr('catid'));
        });

        var CatID = $('#cat-nav .cur').attr('catid');
        var bRecommend = $('#cat-nav .cur').attr('bRecommend');
        getCatProduct(CatID);
    });

    function getCatProduct(CatID) {
        var str = '';
        $.post(
            '/agentshop/catproduct',
            {
                CatID: CatID,
                _csrf: '<?=\Yii::$app->request->getCsrfToken()?>'
            },
            function (res) {
                $.each(res.data, function (i, val) {
                    str += '<div class="item-lists">';
                    str += '<a class="item_a" href=/product/detail/?id=' + val.lID + '>';
                    str += '<div class="pic">';
                    str += '<img src="' + val.sMasterPic + '" alt=""></div>';
                    str += '<div class="txt">';
                    str += '<h6>' + val.sName + '</h6>';
                    str += '<div class="clearfloat"></div>';
                    str += '<h3>价格：<span>' + val.fPrice + '</span></h3>';
                    str += '<h3>利润：<span>' + val.fProfit + '</span></h3>';
                    str += '</div></a>';

                    // if (res.bRecommend){
                    //     if (val.RecommendStatus == 1){
                    //         str +='<img onclick="deleteRecommend(' + val.lID + ')" class="delete_recommend" productid="' + val.lID + '" src="/images/agentshopproduct/category_logo1.png" alt="">';
                    //         str +='<div class="selected">已选</div> ';
                    //     }else{
                    //         //str +='<div class="addrecommend2" onclick="addRecommend(' + val.lID + ')">加入推荐</div> ';
                    //     }
                    // }else{
                    //判断用户是否已进货商品
                    if (val.bSelect) {
                        str += '<img onclick="cancelSelete(' + val.lID + ')" class="delete_recommend" productid="' + val.lID + '" src="/images/agentshopproduct/category_logo1.png" alt="">';
                        str += '<div class="selected">已选</div> ';
                    } else {
                        str += '<div class="unselect" onclick="select(' + val.lID + ')">进货</div> ';
                    }
                    //str +='<div class="addrecommend" onclick="addRecommend(' + val.lID + ')">加入推荐</div> ';
                    // }

                    str += '<div class="clearfloat"></div>';
                    str += '</div>';
                });

                if (res.bRecommend) {
                    $(".agentrecommendword").show();
                } else {
                    $(".agentrecommendword").hide();
                }

                $(".item-cur").append(str);
            }, 'json')
    }

    //删除推荐弹窗
    function deleteRecommend(ID) {
        console.log(ID);
        ProductID = ID;
        $('.alert_recommend').show();
        $(".mask").show();
    }

    //取消进货弹窗
    function cancelSelete(ID) {
        console.log(ID);
        ProductID = ID;
        $('.alert_select').show();
        $(".mask").show();
    }

    //遮罩层点击事件
    $(".mask").click(function () {
        $('.alert_recommend').hide();
        $('.alert_select').hide();
        $(".mask").hide();
    });

    //取消删除推荐点击事件
    $(".cancle_delete_recommend").click(function () {
        $('.message_alert').hide();
        $(".mask").hide();
    });

    //取消删除推荐点击事件
    $(".cancle_delete_select").click(function () {
        $('.message_alert').hide();
        $(".mask").hide();
    });

    //确认删除推荐点击事件
    $(".confirm_delete_recommend").click(function () {
        console.log(ProductID);
        console.log('<?=\Yii::$app->request->getCsrfToken()?>');
        $.post(
            '/agentshop/canclerecommend',
            {
                ID: ProductID,
                _csrf: '<?=\Yii::$app->request->getCsrfToken()?>'
            },
            function (res) {
                if (res.status) {
                    $('.message_alert').hide();
                    $(".mask").hide();
                }
                shoperm.showTip(res.msg);
            }, 'json');
    });

    //确认取消进货点击事件
    $(".confirm_delete_select").click(function () {
        console.log(ProductID);
        $.post(
            '/agentshop/cancelselect',
            {
                ID: ProductID,
                _csrf: '<?=\Yii::$app->request->getCsrfToken()?>'
            },
            function (res) {
                if (res.status) {
                    $('.message_alert').hide();
                    $(".mask").hide();
                }
                shoperm.showTip(res.msg);
            }, 'json');
    });

    //点击进货
    function select(ID) {
        if (bTop == 1) {
            $.post(
                '/agentshop/select',
                {
                    prodcutID: ID,
                    catID: 1,//默认1
                    _csrf: '<?=\Yii::$app->request->getCsrfToken()?>'
                },
                function (res) {
                    if (res.status) {
                        $('.message_alert').hide();
                        $(".mask").hide();
                    }
                    shoperm.showTip(res.msg);
                }, 'json');
        }
        else {
            location.href = '<?= Url::toRoute([\Yii::$app->request->shopUrl . "/supplier/alliancereg"], true) ?>';
        }
    }

    //加入店长推荐
    function addRecommend(ID) {
        $.post(
            '/agentshop/addrecommend',
            {
                ID: ID,
                _csrf: '<?=\Yii::$app->request->getCsrfToken()?>'
            },
            function (res) {
                shoperm.showTip(res.msg);
            }, 'json');
    }
</script>

<?php $this->endBlock() ?>
