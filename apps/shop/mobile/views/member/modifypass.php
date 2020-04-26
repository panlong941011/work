<?php $this->beginBlock('style') ?>
<link rel="stylesheet" href="/css/login.css?<?= \Yii::$app->request->sRandomKey ?>">
<?php $this->endBlock() ?>
<div class="register">
    <div class="ad_header">
        <a href="javascript:;" class="ad_back" onclick="goBack()">
            <span class="icon back">&#xe885;</span>
        </a>
        <h2>修改密码</h2>
    </div>
    <div class="form_wrap">
        <form name="regform">
            <input type="hidden" name="_csrf" value="<?=\Yii::$app->request->getCsrfToken()?>">
            <input type="hidden" name="sReturnUrl" value="/member">
            <div class="input_wrap flex">
                <i class="verification"></i>
                <input type="text" name="verifycode" placeholder="请输入验证码" id="code">
                <span class="verify_code">发送验证码</span>
            </div>
            <div class="input_wrap flex">
                <i class="password"></i>
                <input type="password" name="password" placeholder="请输入密码" id="password" class="input_word">
                <span class="look"></span>
            </div>
            <div class="input_wrap flex">
                <i class="password"></i>
                <input type="password" name="password2" placeholder="请再次输入密码" id="s_password" class="input_word">
                <span class="look"></span>
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
        var pattern = /^1\d{10}$/; //验证手机
        $('#btn').on('click', function () {
            var code = $.trim($('#code').val()),
                password = $.trim($('#password').val()),
                sPassword = $.trim($('#s_password').val());


            if (code == '') {
                shoperm.showTip('请输入验证码');
                return;
            }
            if (password == '') {
                shoperm.showTip('请输入设置的密码');
                return;
            }
            if (sPassword == '') {
                shoperm.showTip('请再次输入密码');
                return;
            }
            if (password !== sPassword) {
                shoperm.showTip('两次输入密码不一致');
                return;
            }

            $(".weui-loading_toast").show();

            $.post('/member/modifypass',
             $(document.regform).serialize(),
             function(data) {

                 $(".weui-loading_toast").hide();

                 if (data.status) {
                     shoperm.showTip("修改成功");
                     setTimeout("goBack();", 1000);
                 } else {
                     shoperm.showTip(data.message);
                 }
             },'json')
        })

        $('.look').on('click', function () {
            //$(this).toggleClass("look_all");
            var hasClass = $(this).hasClass("look_all");
            if (hasClass) {
                $(this).removeClass("look_all");
                $(this).siblings('.input_word').attr('type', 'password');
            } else {
                $(this).addClass("look_all");
                $(this).siblings('.input_word').attr('type', 'text');
            }
        })


        $(".verify_code").click(
            function () {

                var phone = $.trim($('#phone').val());

                $(".weui-loading_toast").show();

                $.post(
                    '/member/sendmodifypasscode',
                    {_csrf: '<?= \Yii::$app->request->getCsrfToken() ?>'},
                    function (data) {

                        $(".weui-loading_toast").hide();

                        if (data.status) {
                            countdown();
                            codecountdown = setInterval("countdown()", 1000);
                            shoperm.showTip(data.message);
                        } else {
                            shoperm.showTip(data.message);
                        }
                    }
                )
            }
        )

    });

    var lCountDown = 60;
    function countdown() {
        $('.verify_code').html(lCountDown + 's');
        lCountDown--;

        if (lCountDown == 0) {
            clearInterval(codecountdown);
            $('.verify_code').html('发送验证码');
            lCountDown = 60;
        }

    }
</script>
<?php $this->endBlock() ?>
