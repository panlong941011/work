<?php $this->beginBlock('style');?>
<link rel="stylesheet" href="/css/wholesaler.css?<?= \Yii::$app->request->sRandomKey ?>">
<style type="text/css">
    body, html {
        width: 100%;
        height: 100%;
        position: relative;
        overflow: hidden;
    }
</style>
<?php $this->endBlock() ?>
<div class="settled_apply">
    <div class="ad_header">
        <a href="javascript:;" class="ad_back" onclick="goBack()">
            <span class="icon">&#xe885;</span>
        </a>
        <h2>渠道人员申请</h2>
    </div>
    <div class="apply_content">
        <form class="apply_form">
            <input type="hidden" name="_csrf" value="<?= \Yii::$app->request->getCsrfToken() ?>">
            <div class="apply_input">
                <input type="tel" name="" placeholder="请输入渠道手机号" id="sSupplierPhone">
            </div>
            <div class="apply_input">
                <input type="text" name="" placeholder="请输入真实姓名" id="sName">
            </div>
            <div class="apply_input">
                <input type="tel" name="" placeholder="请输入个人手机号" id="sPhone">
                <span class="code" style="cursor:pointer" onclick="sendCode()">获取短信验证码</span>
            </div>
            <div class="apply_input">
                <input type="text" name="" placeholder="请输入短信验证码" id="sCode">
            </div>
        </form>
    </div>
    <div class="submit_btn">
        <button class="login_bnt">确认</button>
    </div>
</div>

<div class="weui-loading_toast" style="display: none;">
    <div class="weui-mask_transparent"></div>
    <div class="weui-toast">
        <i class="weui-loading weui-icon_toast"></i>
    </div>
</div>

<script src='/js/jquery.min.js'></script>
<?php $this->beginBlock('foot') ?>

<script>
    var pattern = /^1\d{10}$/; //验证手机
    
    $(function () {
        <?if($member->SupplierID || $member->PurchaseID){?>
            shoperm.showTip('您已申请，请等待审核',function(){
                location.href='<?= Yii::$app->request->mallHomeUrl ?>'
            });
        <?}?>
        //提交
        $('.submit_btn').on('click', function () {
            var sSupplierPhone = $.trim($('#sSupplierPhone').val()),
                name = $.trim($('#sName').val()),
                phone = $.trim($('#sPhone').val()),
                code = $.trim($('#sCode').val());

            if (sSupplierPhone == '') {
                shoperm.showTip("请输入渠道手机号");
                return;
            }

            if (!pattern.test(sSupplierPhone)) {
                shoperm.showTip("请输入正确的渠道手机号");
                return;
            }
            
            if (name == '') {
                shoperm.showTip("请输入姓名");
                return;
            }

            if (phone == '') {
                shoperm.showTip("手机号不得为空");
                return;
            }

            if (!pattern.test(phone)) {
                shoperm.showTip("请输入正确的手机号");
                return;
            }
            
            if (code == '') {
                shoperm.showTip("验证码不得为空");
                return;
            }

            $(".weui-loading_toast").show();
            console.log(code);
            $.post(
                '/<?=Yii::$app->request->shopUrl?>/wholesaler/apply',
                {
                    sSupplierPhone:sSupplierPhone,
                    name: name,
                    phone: phone,
                    code: code,
                    _csrf: '<?=\Yii::$app->request->getCsrfToken()?>'
                },
                function (data) {
                    console.log(data);
                    $(".weui-loading_toast").hide();
                    
                    if (data.status) {
                        shoperm.showTip(data.message,function(){
                            location.href='<?= Yii::$app->request->mallHomeUrl ?>'
                        });
                    } else {
                        shoperm.showTip(data.message);
                    }
                },'json'
            );
        })
    });

    function sendCode() {
        if ($.trim($('#sPhone').val()) == "") {
            shoperm.showTip('手机号不得为空');
            return;
        }

        if (!pattern.test($('#sPhone').val())) {
            shoperm.showTip('请输入正确的手机号');
            return;
        }

        $(".weui-loading_toast").show();

        $.post(
            '/wholesaler/sendjoincode?mobile=' + $('#sPhone').val(),
            {_csrf: '<?= \Yii::$app->request->getCsrfToken() ?>'},
            function (data) {
                
                $(".weui-loading_toast").hide();

                if (data.status) {
                    countdown();
                    codecountdown = setInterval("countdown()", 1000);
                } else {
                    shoperm.showTip(data.message);
                }
            }
        )
    }

    var lCountDown = 60;
    function countdown() {
        $('.code').html(lCountDown + 's');
        lCountDown--;

        if (lCountDown == 0) {
            clearInterval(codecountdown);
            $('.code').html('获取短信验证码');
            lCountDown = 60;
        }
    }
    
</script>
<?php $this->endBlock() ?>