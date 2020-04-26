<?php $this->beginBlock('style') ?>
    <link rel="stylesheet" href="/css/supplierMain.css?<?= \Yii::$app->request->sRandomKey ?>">
<?php $this->endBlock() ?>
    <div class="shop_setting">
        <div class="ad_header">
            <a href="javascript:;" class="ad_back">
                <span class="icon" onclick="goBack()">&#xe885;</span>
            </a>
            <h2>店铺设置</h2>
            <span class="ad_more icon">&#xe602;</span>
        </div>
        <div class="shop_logo">
            <div class="logo_pic">
                <img src="/images/supplier/shopLogo.png" class="logo_show">
                <input type="file" name="" class="logo_file">
                <span class="close"></span>
            </div>
            <h3>店铺logo</h3>
        </div>
        <div class="shop_name flex" style="margin-bottom: 0.066667rem">
            <span class="s_name">店铺名称</span>
            <input type="text" name="shopname" placeholder="不得多于10个字" maxlength="10" class="sName"
                   value="<?= $seller->shop->sName ?>">
        </div>

        <div class="shop_name flex" style="margin-bottom: 0.066667rem">
            <span class="s_name">真实姓名</span>
            <input type="text" name="sname" class="sName"
                   value="<?= $seller->sName ?>">
        </div>

        <div class="shop_name flex">
            <span class="s_name">手机号码</span>
            <input type="number" name="mobile" class="sName"
                   value="<?= $seller->sMobile ?>">
        </div>
        <a href="javascript:;" class="keep">保存</a>
    </div>
    <div class="nav_more_list">
        <div class="triangle-up"></div>
        <ul>
            <li class="flex" onclick="location.href='<?= \Yii::$app->request->mallHomeUrl ?>'">
                <span class="icon">&#xe608;</span>
                <em>首页</em>
            </li>
            <li class="flex"
                onclick="location.href='<?= \yii\helpers\Url::toRoute(["/cart"], true) ?>'">
                <span class="icon">&#xe60a;</span>
                <em>购物车</em>
            </li>
            <li class="flex"
                onclick="location.href='<?= \yii\helpers\Url::toRoute(["/member"],
                    true) ?>'">
                <span class="icon">&#xe64a;</span>
                <em>我的</em>
            </li>
        </ul>
    </div>
    <div class="weui-loading_toast" style="display: none;">
        <div class="weui-mask_transparent"></div>
        <div class="weui-toast">
            <i class="weui-loading weui-icon_toast"></i>
        </div>
    </div>
<?php $this->beginBlock('foot') ?>

    <script src="/js/lrz.all.bundle.js"></script>
    <script>
        $(function () {
            $('.ad_more').on('click', function () {
                event.stopPropagation();
                $(".nav_more_list").toggle();
            })
            $(window).on('click', function () {
                $(".nav_more_list").hide();
            })
            //图片上传
            $('.logo_file').on('change', function (event) {

                var files = event.currentTarget.files[0];

                lrz(files)
                    .then(function (rst) {
                        // 处理成功会执行
                        addImg(rst.base64);

                    })
                    .catch(function (err) {
                        console.log(err);
                        // 处理失败会执行
                        shoperm.showTip('上传正确图片格式');
                        return;
                    })
                    .always(function () {
                        // 不管是成功失败，都会执行

                    });
            })

            $('.close').on('click', function () {
                $(this).hide();
                $('.logo_file').show();
                $('.logo_file').val('');
                $('.logo_show').attr('src', '../../images/supplier/shopLogo.png');
            })

            $('.keep').on('click', function () {
                $(".weui-loading_toast").show();
                $.post(
                    '/seller/shopconfig',
                    {
                        logo: $('.logo_show').attr('src'),
                        shopname: $("input[name='shopname']").val(),
                        name: $("input[name='sname']").val(),
                        mobile: $("input[name='mobile']").val()
                    },
                    function (data) {
                        $(".weui-loading_toast").hide();
                        location.href = "/seller";
                    }
                );
            })

            function addImg(file) {
                $('.logo_show').attr('src', file);
                $('.close').show();
                $('.logo_file').hide();
            }
        })
    </script>
<?php $this->endBlock() ?>