<?php $this->beginBlock('style') ?>
    <link rel="stylesheet" href="/css/login.css?<?= \Yii::$app->request->sRandomKey ?>">
<?php $this->endBlock() ?>
    <div class="login">
        <div class="ad_header">
            <a href="javascript:;" class="ad_back" onclick="goBack()">
                <span class="icon back">&#xe885;</span>
            </a>
            <h2>登录</h2>
        </div>
        <div class="form_wrap">
            <form name="loginform">
                <input type="hidden" name="_csrf" value="<?= \Yii::$app->request->getCsrfToken() ?>">
                <input type="hidden" name="sReturnUrl" value="<?= $sReturnUrl ?>">
                <div class="input_wrap flex">
                    <i class="telphone"></i>
                    <input type="tel" name="mobile" placeholder="输入手机号码" id="phone">
                </div>
                <div class="input_wrap flex">
                    <i class="password"></i>
                    <input type="password" name="password" placeholder="请输入密码" id="password" class="input_word">
                    <span class="look"></span>
                </div>
            </form>
            <div class="find_word">
                <a href="/member/forgot">找回密码</a>
            </div>
        </div>
        <div class="submit_btn">
            <button class="login_bnt">登录</button>
        </div>
        <div class="submit_btn">
            <a href="/member/reg?sReturn=<?= urlencode($sReturnUrl) ?>" class="register_btn">新用户注册</a>
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
            $('.login_bnt').on('click', function () {
                var phone = $.trim($('#phone').val()),
                    password = $.trim($('#password').val());

                if (phone == '') {
                    shoperm.showTip('手机号不得为空');
                    console.log('手机号不得为空');
                    return;
                }
                if (!pattern.test(phone)) {
                    shoperm.showTip('请输入正确的手机号');
                    console.log('请输入正确的手机号');
                    return;
                }
                if (password == '') {
                    shoperm.showTip('请输入密码');
                    return;
                }

                $(".weui-loading_toast").show();

                $.post('/member/loginpost',
                    $(document.loginform).serialize(),
                    function (data) {
                        $(".weui-loading_toast").hide();
                        if (!data.status) {
                            shoperm.showTip(data.message);
                        } else {
                            location.href = document.loginform.sReturnUrl.value;
                        }
                    }, 'json');
            })

            $('.look').on('click', function () {
                //$(this).toggleClass("look_all");
                var hasClass = $(this).hasClass("look_all");
                if (hasClass) {
                    $(this).removeClass("look_all");
                    $('.input_word').attr('type', 'password');
                } else {
                    $(this).addClass("look_all");
                    $('.input_word').attr('type', 'text');
                }
            })
        })
    </script>
<?php $this->endBlock() ?>