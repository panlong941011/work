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
    body {
        background-color: #f1f1f1;
    }

    .item-lists a {
        display: flex;
        flex-direction: row;
        width: 100%;
        padding: .5rem;
        border-bottom: 1px solid #ddd;
    }

    .item-lists .pic {
        width: 3.5rem;
        height: 3.5rem;
    }

    .item-lists .pic img {
        width: 3rem;
        height: 3rem;
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
        font-size: 0.56rem;
    }

    .item-lists .txt > h3 {
        font-size: .49rem;
        color: rgba(166, 166, 166, 1);
        margin-top: 10px;
    }

    .item-lists .txt > h3 > span {
        color: rgba(238, 82, 82, 1);
    }

    .descript {
        padding: 0.15rem 0.4266666667rem 0.4266666667rem;
        font-size: 22px;
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
        font-size: 0.6rem;
        text-align: center;
    }

    .txt h6 {
        width: 80%;
        overflow: hidden;
        white-space: nowrap;
        text-overflow: ellipsis;
        float: left;
        color: rgba(56, 56, 56, 1);
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
        width: 11rem;
        height: 8rem;
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
        font-size: 0.7rem;
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
        margin: 0;
        padding: 0;
        height: calc(100vh - 3.35rem);
        overflow-y: auto;
        background: #f1f1f1;
        -webkit-overflow-scrolling: touch;
    }

    .selected {
        position: absolute;
        top: 2.8rem;
        font-size: 0.5rem;
        display: block;
        width: 1.4rem;
        height: 1rem;
        line-height: 1rem;
        right: .2rem;
        text-align: center;
        border-radius: 0.4rem;
        color: rgba(255, 255, 255, 1);
        background-color: rgba(128, 128, 128, 1);
    }

    .unselect {
        position: absolute;
        top: 2.8rem;
        font-size: 0.5rem;
        display: block;
        width: 1.4rem;
        height: 1rem;
        line-height: 1rem;
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
        width: 2.4rem;
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
        margin-bottom: .5rem;
        background-color: #ffffff;
    }

    /* 搜索栏 */
    .s_input {
        width: 14.7333333333rem;
        height: 1.28rem;
        padding: 0.32rem 0.4266666667rem;
        -webkit-border-radius: 0.2133333333rem;
        border-radius: 0.2133333333rem;
        margin: 0.4266666667rem auto;
        display: flex;
        color: rgba(189, 189, 189, 1);
        background-color: rgba(229, 229, 229, 0.4444444444444444);
    }

    .s_input span {
        display: block;
        width: 0.64rem;
        height: 0.64rem;
        background: url(/images/home/mirror.png) no-repeat;
        background-size: 100% 100%;
    }

    [data-dpr="2"] .s_input h2 {
        font-size: 0.6rem;
    }

    .s_input h2 {
        padding-left: 0.4266666667rem;
        line-height: 1.2;
        text-align: center;
        width: 100%;
    }

    .s_input_div {
        background-color: rgba(255, 255, 255, 1);
        height: 2.2rem;
        margin: 0 auto;
        padding: .1rem 0.4rem;
        display: none;
    }

    .s_input input {
        font-size: 0.6rem;
        color: rgba(189, 189, 189, 1);
        background-color: rgba(229, 229, 229, 0.4444444444444444);
        width: 14rem;
        height: .8rem;
        text-align: center;
    }

    /* 分块栏 */
    .producttype {
        background-color: rgba(255, 255, 255, 1);
        height: 2rem;
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

    .part_point {
        border-bottom: 2px solid rgba(212, 48, 48, 1);
    }

    .partdiv1 {
        width: 3.8rem;
        height: 2rem;
        color: rgba(80, 80, 80, 1);
        font-size: .5rem;
        font-weight: lighter;
        float: left;
        vertical-align: middle;
        line-height: 2rem;
        text-align: center;
    }

    .partdiv2 {
        width: 2.7rem;
        height: 2rem;
        color: rgba(80, 80, 80, 1);
        font-size: .5rem;
        font-weight: lighter;
        float: right;
        vertical-align: middle;
        line-height: 2rem;
        text-align: center;
    }

    /* 商品区操作按钮 */
    .productbutton {
        width: 5rem;
        height: 2rem;
        color: rgba(128, 128, 128, 1);
        font-size: .6rem;
        font-weight: lighter;
        float: left;
        vertical-align: middle;
        line-height: 2rem;
        text-align: center;
    }

    .item-lists .txt .info {
        color: rgba(56, 56, 56, 1);
    }

    .type_point {
        color: rgba(212, 48, 48, 1);
    }

    .category_wrap {
        height: 80%;
    }

    .mask {
        width: 100%;
        height: 100%;
        position: fixed;
        left: 0;
        top: 0;
        z-index: 100;
        background: rgba(0, 0, 0, 0.5);
        display: none;
    }

    .alert_reason select {
        width: 9rem;
        height: 1.7rem;
        text-align: left;
        margin-left: .4rem;
        font-size: 0.6rem;
    }

    .alert_reason select option {
        font-size: 0.6rem;
    }
    .publishProduct {
        width: 2rem;
        height: 2rem;
        -webkit-border-radius: 50%;
        border-radius: 0.5rem;
        background-size: contain;
        position: fixed;
        right: 0.64rem;
        top: 75%;
        z-index: 100;
        font-size: 0.7rem;
        background-color: rgb(255, 50, 115);
        background-image:none;
        display: block;
        text-align: center;
        color: #fff;
    }
</style>
<?php $this->endBlock() ?>
<div class="s_input_div">
    <div class="s_input flex">
        <input type="text" placeholder="请输入商品名称">
        <span></span>
    </div>
</div>

<div class="producttype">
    <div class="productpart part_point type_point" onclick="changetab(this,1)">自有商品（<span
                id="selfnum"><?= $lSelfTotal ?></span>）
    </div>
    <div class="productpart" onclick="changetab(this,0)">代理商品（<span id="agentnum"><?= $lAgentTotal ?></span>）</div>
    <div class="clearfloat"></div>
</div>

<div class="producttype">
    <div class="partdiv1 type_point" onclick="changetype(1)">出售中（<span id="onsale"><?= $lSelfOnSale ?></span>）</div>
    <div class="partdiv1" onclick="changetype(0)">待上架（<span id="unsale"><?= $lSelfUnSale ?></span>）</div>
    <div class="partdiv2" onclick="sort('stock')">库存</div>
    <div class="partdiv2">总销量</div>
    <div class="partdiv2" onclick="sort('time')">上架时间</div>
    <div class="clearfloat"></div>
</div>

<div class="category_wrap">
    <div class="cat-viewport">
        <div class="cat-con" id="cat-con">
            <div class="con-item item-cur">
            </div>
        </div>
    </div>
</div>

<div class="fixed">

</div>

<?= $this->render('/layouts/foot', ['bIndex' => true]) ?>


<div class="mask"></div>
<div class="message_alert alert_select">
    <h2 class="alert_title">设置品类</h2>
    <input type="hidden" id="ProdcutID">
    <div class="alert_reason">
        <select id="catID">
            <? foreach ($arrCat as $cat) { ?>
                <option value="<?= $cat->CatID ?>"><?= $cat->sName ?></option>
            <? } ?>
        </select>
    </div>
    <div class="alert_btn cancle_delete_select" onclick="cancleShelf()">取消</div>
    <div class="alert_btn confirm_delete_select" onclick="confirmShelf()">确认</div>
    <span class="alert_close"></span>
</div>


<!-- 遮罩 -->
<div class="mask"></div>

<div class="publishProduct"><a href="/product/add">发布<br/>商品</a></div>
<?php $this->beginBlock('foot') ?>

<script>
    var ProductID = '';
    var type = '1';//商品类型，1=自有商品，0=代理商品
    var status = '1';//状态，1=出售中，0=已下架
    var timesort = 'asc';
    var stocksort = 'asc';

    $(function () {
        getCatProduct(1, 1);
    });

    //根据商品类型请求商品列表
    function changetab(obj, bSelf) {
        if (bSelf) {
            getCatProduct(1, 1);
            type = 1;
        } else {
            getCatProduct(0, 1);
            type = 0;
        }
        $('.productpart').attr('class', 'productpart');
        $(obj).attr('class', 'productpart part_point type_point');
        $(".partdiv1").attr('class', 'partdiv1');
        $('.partdiv1').eq(0).attr('class', 'partdiv1 type_point');
    }

    //根据商品销售状态请求商品列表
    function changetype(bSale) {
        getCatProduct(type, bSale);
        $(".partdiv1").attr('class', 'partdiv1');
        if (bSale) {
            $('.partdiv1').eq(0).attr('class', 'partdiv1 type_point');
        } else {
            $('.partdiv1').eq(1).attr('class', 'partdiv1 type_point');
        }
    }

    //获取商品列表
    function getCatProduct(bSelf, bSale, sorttype, sort) {
        var str = '';
        console.log('bSelf:' + bSelf);
        console.log('bSale:' + bSale);

        type = bSelf;
        status = bSale;

        $.post(
            '/agentshop/list',
            {
                bSelf: bSelf,
                bSale: bSale,
                sorttype: sorttype,
                sort: sort,
                _csrf: '<?=\Yii::$app->request->getCsrfToken()?>'
            },
            function (res) {
                var url="<?=\yii\helpers\Url::toRoute([\Yii::$app->request->shopUrl . "/product/detail"], true)?>";
                $.each(res.data, function (i, val) {
                    str += '<div class="item-lists">';
                    str += '<a class="item_a" href="'+url+'?id=' + val.lID + '">';
                    str += '<div class="pic">';
                    str += '<img src="' + val.sMasterPic + '" alt=""></div>';
                    str += '<div class="txt">';
                    str += '<h6>' + val.sName + '</h6>';
                    str += '<div class="clearfloat"></div>';
                    str += '<h3>零售价：<span>￥' + val.fPrice + '</span> 利润：<span>￥' + val.fProfit + '</span></h3>';
                    str += '<h3 class="info">库存：' + val.lStock + '</h3>';
                    str += '<h3 class="info">上架：' + val.dNewDate + '</h3>';
                    str += '</div></a>';
                    str += '<div>';

                    if (bSale) {
                        str += '<div class="productbutton" onclick="unsale(' + val.lID + ')" >下架</div>';
                    } else {
                        str += '<div class="productbutton" onclick="onsale(' + val.lID + ')" >上架</div>';
                    }
                    if (bSelf) {
                        str += '<div class="productbutton"  onclick="setvip(' + val.lID + ')" >VIP售价设置</div>';
                    }
                    str += '</div>';
                    str += '<div class="clearfloat"></div>';
                    str += '</div>';
                });

                if (bSelf) {
                    $("#selfnum").text(res.lTotalNum);
                } else {
                    $("#agentnum").text(res.lTotalNum);
                }

                $("#onsale").text(res.lOnSale);
                $("#unsale").text(res.lUnSale);

                $(".item-cur").html(str);
            }, 'json')
    }

    //确认取消进货点击事件
    function unsale(ID) {
        console.log(ID);
        $.post(
            '/agentshop/cancelselect',
            {
                ID: ID,
                _csrf: '<?=\Yii::$app->request->getCsrfToken()?>'
            },
            function (res) {
                shoperm.showTip(res.msg);
            }, 'json');
    };

    //点击进货
    function onsale(ID) {
        $('.mask').show();
        $('.message_alert').show();
        $('#ProdcutID').val(ID);
    }

    //排序
    function sort(name) {
        if (name == 'stock') {
            if (stocksort == 'asc') {
                getCatProduct(type, status, 'lStock', 'asc');
                stocksort = 'desc';
            } else {
                getCatProduct(type, status, 'lStock', 'desc');
                stocksort = 'asc';
            }
            $(".partdiv2").attr('class', 'partdiv2');
            $(".partdiv2").eq(0).attr('class', 'partdiv2 type_point');
        } else if (name == 'time') {
            if (timesort == 'asc') {
                getCatProduct(type, status, 'dNewDate', 'asc');
                timesort = 'desc';
            } else {
                getCatProduct(type, status, 'dNewDate', 'desc');
                timesort = 'asc';
            }
            $(".partdiv2").attr('class', 'partdiv2');
            $(".partdiv2").eq(2).attr('class', 'partdiv2 type_point');
        }
    }

    function setvip(ProductID) {
        location.href = '/agentshop/setvip?ProductID=' + ProductID;
    }

    function cancleShelf() {
        $('.mask').hide();
        $('.message_alert').hide();
    }

    function confirmShelf() {
        var catID = $('#catID').val();
        var prodcutID = $('#ProdcutID').val();
        $('.mask').hide();
        $('.message_alert').hide();
        $.post(
            '/agentshop/select',
            {
                prodcutID: prodcutID,
                catID: catID,
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
</script>
<?php $this->endBlock() ?>

