<?php $this->beginBlock('style') ?>
<link rel="stylesheet" href="/css/login.css?<?= \Yii::$app->request->sRandomKey ?>">
<style>
    #btn {
        background-color: #3394FF;
    }

    .register .input_wrap input {
        font-size: 46px;
        height: 2rem;
        width: 100%;
    }
</style>
<?php $this->endBlock() ?>
<div class="register">
    <div class="ad_header">
        <a href="javascript:;" class="ad_back" onclick="goBack()">
            <span class="icon back">&#xe885;</span>
        </a>
        <h2>修改客服电话</h2>
    </div>
    <div class="form_wrap">
        <form name="regform">
            <input type="hidden" id="csrf" name="_csrf" value="<?= \Yii::$app->request->getCsrfToken() ?>">
            <div class="input_wrap flex">
                <input type="text" id="sMobile" value="<?= $shop->sMobile ?>" class="input_word">
            </div>
        </form>
    </div>
    <div class="submit_btn">
        <button id="btn">确认</button>
    </div>
</div>

<div class="weui-loading_toast" style="display: none;">
    <div class="weui-mask_transparent"></div>
    <div class="weui-toast">
        <i class="weui-loading weui-icon_toast"></i>
    </div>
</div>
<?php $this->beginBlock('foot') ?>

<script>
    $(function () {
        $("#btn").click(
            function () {
                var csrf = $('#csrf').val();
                var sMobile = $('#sMobile').val();
                $.post(
                    '/member/modifymobile',
                    {
                        _csrf: csrf,
                        sMobile: sMobile
                    },
                    function (data) {
                        $(".weui-loading_toast").hide();
                        if (data.status) {
                            shoperm.showTip(data.message);
                        } else {
                            shoperm.showTip(data.message);
                        }
                    }
                )
            }
        )

    });


</script>
<?php $this->endBlock() ?>
