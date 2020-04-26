<?php
/**
 * Created by PhpStorm.
 * User: ouyangyz <ouyangyanzhong@163.com>
 * Date: 2018-08-08
 * Time: 下午 3:21
 */
?>

<?php $this->beginBlock('style') ?>
<link rel="stylesheet" href="/css/wholesaleApply.css?<?= \Yii::$app->request->sRandomKey ?>">
<?php $this->endBlock() ?>
<div class="refund_apply" id="app" v-cloak>
    <div class="ad_header">
        <a href="javascript:goBack();" class="ad_back"> <span class="icon">&#xe885;</span> </a>
        <h2>渠道商品设置</h2>
    </div>
    <section>
        <div class="apply_content">
            <div class="list_item flex">
                <div class="pic"><img src="<?= $product->masterPic ?>" alt=""></div>
                <div class="info">
                    <h4 class="title"><?= $product->sName ?></h4>
                    <div class="price flex">
                        <em>售价￥<?= number_format($product->fPrice, 2) ?> / 供货价￥<?= number_format($product->wholesaleCostPrice, 2) ?></em>
                    </div>
                </div>
            </div>
        </div>
        <div class="apply_price flex">
            <div class="item_title">经销商：</div>
            <div class="item_content">
                <input type="text" id="SellerID" value="<?= $seller->lID ?>" hidden>
                <input type="text" name="" class="price" value="<?= $seller->sName ?>" onclick="location.href='<?= \yii\helpers\Url::toRoute(["/wholesaler/memberlist", 'ProductID' => $_GET['ProductID']], true) ?>'" placeholder="点击选择经销商">
            </div>
        </div>
        <div class="apply_price flex">
            <div class="item_title"><span>*</span>渠道价：</div>
            <div class="item_content">
                <i>¥</i> <input type="text" name="" id="price" class="price" v-model="wholesalePrice">
            </div>
        </div>
        <button style="position:relative;" class="submit_btn" :class="{off: isBtnOff}" @click="submitData">提交申请</button>
    </section>
    <div class="submit_btn_wrap">
        
    </div>
</div>
<div class="mask"></div>
<?php $this->beginBlock('foot') ?>
<script src="/js/jquery.min.js"></script>
<script src="/js/ydui.js"></script>
<script src="/js/lrz.all.bundle.js"></script>
<script src="/js/template.js"></script>
<script>
    $(function () {
        //ydUI的用法
        var $myAs = $('#J_ActionSheet');

        $('#J_ShowActionSheet,#J_showType').on('click', function () {
            $myAs.actionSheet('open');
        });
        /*关闭弹框*/
        $('#J_Cancel').on('click', function () {
            $myAs.actionSheet('close');
        });

        $('.popup').on('click', '.reason_item', function () {

            setTimeout(function () {
                $myAs.actionSheet('close');
            }, 200)
        });
        //给body设置初始高度
        var bodyHi=$(window).height();
        $('html ,body').css("height",bodyHi);
        $("section").css("height",bodyHi-$(".ad_header").height()-10);
        $(".submit_btn").css("margin-top",bodyHi-($(".ad_header").height()*7));
        var warpTop = $('.submit_btn_wrap').offset().top,
            btnTop = $('.submit_btn').offset().top;
        //处理手机端点击输入框  页面固定结构弹出问题
        $('.explain_word,.price').on('focus', function () {
            if (/Android/.test(navigator.appVersion) || /iPhone/.test(navigator.appVersion)) {
                setTimeout(function () {
                    //$('.submit_btn').css('position', 'relative');
                    //$('.submit_btn_wrap').css('marginTop', btnTop - warpTop + 50);
                }, 200)
            }
        })
        $('.explain_word,.price').on('blur', function () {
            $('html ,body').animate({
                scrollTop: 50
            }, 0);
            if (/Android/.test(navigator.appVersion) || /iPhone/.test(navigator.appVersion)) {
                setTimeout(function () {
                   // $('.submit_btn').css('position', 'relative');
                    //$('.submit_btn_wrap').css('marginTop', btnTop - warpTop + 50);
                }, 200)
            }
        })
    })
</script>
<script>
    console.log(popup);

    new Vue({
        el: "#app",
        components: {
            'select-part': popup
        },
        data: {
            wholesalePrice: '',//渠道价
            wholesaleCostPrice: '<?=number_format($product->wholesaleCostPrice, 2, ".", "")?>',//供货价
            SellerID: '',//
            parentMsg: {},
            isBtnOff: false,
        },
        methods: {
            submitData: function () {
                var _this = this;
                var pattern = /^\d+(\.\d+)?$/; //小数或整数
                var wholesalePrice = $('#price').val();
                var SellerID = $('#SellerID').val();
                var ProductID = '<?=$_GET['ProductID']?>';
                _this.wholesalePrice = wholesalePrice;
                _this.ProductID = ProductID;
                _this.SellerID = SellerID;
                /*if (_this.SellerID == "") {
                    shoperm.showTip('请选择经销商');
                    return;
                }*/
                if (_this.wholesalePrice == '') {
                    shoperm.showTip('渠道价不得为空');
                    return;
                }

                if (_this.ProductID == '') {
                    shoperm.showTip('请先选择渠道商品');
                    return;
                }

                if (!pattern.test(_this.wholesalePrice)) {
                    shoperm.showTip('请输入正确的渠道价');
                    return;
                }
                if (parseFloat(_this.wholesalePrice) < parseFloat(_this.wholesaleCostPrice)) {
                    shoperm.showTip('渠道价低于供货价');
                    return;
                }
                _this.isBtnOff = true;
                $(".weui-loading_toast").hide();
                $.post('/wholesaler/newwholesale?id=<?=$_GET['id']?>',
                    {
                        'money': _this.wholesalePrice,
                        'SellerID': _this.SellerID,
                        'ProductID': _this.ProductID,
                        '_csrf': '<?=\Yii::$app->request->getCsrfToken()?>',
                    }, function (data) {
                        if (data.status) {
                            $('.weui-loading-toast').hide();
                            var url = '/product/wholesaledetail?name=' + data.WholesaleID;
                            location.replace(url);
                        } else {
                            shoperm.showTip(data.msg);
                            _this.isBtnOff = false;
                        }
                    }, 'json');

            }
        }
    })

</script>
<?php $this->endBlock() ?>
