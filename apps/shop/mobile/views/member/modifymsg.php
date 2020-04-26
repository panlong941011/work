<?php $this->beginBlock('style') ?>
<link rel="stylesheet" href="/css/login.css?<?= \Yii::$app->request->sRandomKey ?>">
<style>
    form {
        height: 190px;
        padding: 10px;
        text-align: left;
    }

    #sMsg {
        height: 180px;
        width: 100%;
        font-size: 46px;
        text-align: left;
    }
    #btn{
        background-color: #3394FF;
    }
</style>
<?php $this->endBlock() ?>
<div class="register">
    <div class="ad_header">
        <a href="javascript:;" class="ad_back" onclick="goBack()">
            <span class="icon back">&#xe885;</span>
        </a>
        <h2>修改店长说</h2>
    </div>
    <div class="form_wrap">
        <form name="regform">
            <input type="hidden" id="csrf" name="_csrf" value="<?= \Yii::$app->request->getCsrfToken() ?>">
            <textarea id="sMsg"><?= $sMsg ?></textarea>

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
                var sMsg = $('#sMsg').val();
                $.post(
                    '/member/modifymsg',
                    {
                        _csrf: csrf,
                        sMsg: sMsg
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
