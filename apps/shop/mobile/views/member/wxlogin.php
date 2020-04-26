<style>
    html,body {
        width: 100%;
        height: 100%;
        overflow-x: hidden;
        overflow-y: auto;
    }
    .login_wrap {
        width: 100%;
        height: 100%;
        background: url(/images/login_bg.png) no-repeat;
        background-size: 100% 100%;
        position: relative;
    }
    .login_btn {
        position: fixed;
        bottom: 4rem;
        left: 50%;
        margin-left: -2.5rem;

        width: 5rem;
        height: 1.5rem;
        background: #fa3333;
        border: 1px solid #db2727;
        color: #fff;
        font-size: 0.55rem;
        text-align: center;
        line-height: 1.5rem;
        -webkit-border-radius: 0.5rem;
        border-radius: 0.5rem;
    }
    .login_icon {
        width: 0.8rem;
        height: 0.6rem;
        display: inline-block;
        background: url(/images/wx.png) no-repeat;
        background-size: 100% 100%;
        vertical-align: middle;
    }
</style>

<div class="login_wrap">
    <div class="login_btn" onclick="login()">
        <span class="login_icon"></span>
        微信登录
    </div>
</div>

<form name="loginform" action="<?=\Yii::$app->request->shopRootUrl?>/member/loginpost?sReturnUrl=<?=urlencode($sReturnUrl    )?>" method="post">
    <input type="hidden" name="_csrf" value="<?=Yii::$app->request->getCsrfToken()?>">
</form>

<script>

    var width = document.documentElement.clientWidth;
    if( width > 750 ) {
       
       /*  var ele = document.querySelector('.login_btn');
        ele.style.bottom = 1.2+'rem';*/
    } 

    function login() {
        document.loginform.submit();
    }
</script>
</html>