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
        padding-top: .5rem;
        border-bottom: 1px solid #ddd;
    }

    .item-lists .pic {
        width: 3.5rem;
        height: 3.5rem;
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
        font-size: 0.5rem;
    }

    .item-lists .txt > h3 {
        font-size: .45rem;
        color: #f42323;
        margin-top: 10px;
    }
</style>
<?php $this->endBlock() ?>

<div class="category_wrap">
	<? if ($arrCat) { ?>
        <div class="cat-viewport">
            <div class="cat-nav" id="cat-nav">
                <ul>
                    <!--初始化第一个加cur-->
                    <!-- 一级分类-->
					<? foreach ($arrCat as $key => $value) { ?>
                        <li catid=<?= $value['lID'] ?> <? if ($key == 0) {
							echo 'class="cur"';
						} ?>><?= $value['sName'] ?></li>
					<? } ?>
                </ul>
            </div>
            <div class="cat-con" id="cat-con">
                <!--初始化第一个加item-cur-->
				<? foreach ($arrCat as $key => $value) { ?>
                    <div class="con-item <? if ($key == 0) {
						echo 'item-cur';
					} ?>">
                    </div>
				<? } ?>
            </div>
        </div>
	<? } ?>
</div>

<footer>
    <div class="bottom_fixed flex">
        <a href="<?= Url::toRoute([\Yii::$app->request->shopUrl . "/home"], true) ?>" >
            <span class="icon">&#xe614;</span>
            <p>首页</p>
        </a>
        <a href="<?= Url::toRoute([\Yii::$app->request->shopUrl . "/product/year"], true) ?>">
            <span class="icon">&#xe655;</span>
            <p>年度计划</p>
        </a>
        <a href="<?= Url::toRoute([\Yii::$app->request->shopUrl . "/product/category"], true) ?>" class="on">
            <span class="icon">&#xe6b0;</span>
            <p>分类</p>
        </a>
        <a href="<?= Url::toRoute([\Yii::$app->request->shopUrl . "/product/recommend"], true) ?>">
            <span class="icon">&#xe62d;</span>
            <p>推荐</p>
        </a>
        <a href="<?= \yii\helpers\Url::toRoute(["/member"], true) ?>"> <span class="icon">&#xe64a;</span>
            <p>我的</p>
        </a>
    </div>
</footer>

<div class="mask"></div>


<?php $this->beginBlock('foot') ?>

<script>
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
        getCatProduct(CatID);
    });

    function getCatProduct(CatID) {
        var str = '';
        $.post(
            '/product/catproduct',
            {
                CatID: CatID,
                _csrf: '<?=\Yii::$app->request->getCsrfToken()?>'
            },
            function (res) {
                $.each(res,function(i,val){
                    str +='<div class="item-lists">';
                    str +='<a href=/product/detail/?id='+val.lID+'>';
                    str +='<div class="pic">';
                    str +='<img src="'+val.sMasterPic+'" alt=""></div>';
                    str +='<div class="txt">';
                    str +='<h6>'+val.sName+'</h6>';
                    str +='<h3>'+val.fSupplierPrice+'</h3>';
                    str +='<h3>'+val.price+'</h3>';
                    str +='<h3>'+val.market_price+'</h3>';
                    str +='</div></a></div>';
                });
                
                $(".item-cur").append(str);
            }, 'json')
    }
</script>

<?php $this->endBlock() ?>
