<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>登录页</title>
    <link href="<?= Yii::$app->homeUrl ?>/js/pages/css/font.css" rel="stylesheet" type="text/css"/>
    <link href="<?= Yii::$app->homeUrl ?>/js/global/plugins/font-awesome/css/font-awesome.min.css" rel="stylesheet"
          type="text/css"/>
    <link href="<?= Yii::$app->homeUrl ?>/js/global/plugins/simple-line-icons/simple-line-icons.min.css"
          rel="stylesheet" type="text/css"/>
    <link href="<?= Yii::$app->homeUrl ?>/js/global/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet"
          type="text/css"/>
    <link href="<?= Yii::$app->homeUrl ?>/js/global/plugins/uniform/css/uniform.default.css" rel="stylesheet"
          type="text/css"/>
    <link href="<?= Yii::$app->homeUrl ?>/js/global/plugins/bootstrap-switch/css/bootstrap-switch.min.css"
          rel="stylesheet" type="text/css"/>
    <!-- END GLOBAL MANDATORY STYLES -->
    <!-- BEGIN PAGE LEVEL PLUGINS -->
    <link href="<?= Yii::$app->homeUrl ?>/js/global/plugins/select2/css/select2.min.css" rel="stylesheet"
          type="text/css"/>
    <link href="<?= Yii::$app->homeUrl ?>/js/global/plugins/select2/css/select2-bootstrap.min.css" rel="stylesheet"
          type="text/css"/>
    <!-- END PAGE LEVEL PLUGINS -->
    <!-- BEGIN THEME GLOBAL STYLES -->
    <link href="<?= Yii::$app->homeUrl ?>/js/global/css/components.min.css" rel="stylesheet" id="style_components"
          type="text/css"/>
    <link href="<?= Yii::$app->homeUrl ?>/js/global/css/plugins.min.css" rel="stylesheet" type="text/css"/>
    <!-- END THEME GLOBAL STYLES -->
    <!-- BEGIN PAGE LEVEL STYLES -->
    <link href="<?= Yii::$app->homeUrl ?>/js/pages/css/login-5.min.css" rel="stylesheet" type="text/css"/>
    <!-- END PAGE LEVEL STYLES -->
    <!-- BEGIN THEME LAYOUT STYLES -->
    <!-- END THEME LAYOUT STYLES -->
    <link rel="shortcut icon" href="/favicon.ico?1"/>
</head>
<link href="<?= Yii::$app->homeUrl ?>/css/login.css" rel="stylesheet" type="text/css"/>
<style>
    .lg-content .h-h {
        padding: 0;
        font-size: 40px;
    }

    .xy-content {
        border: 1px #fff5f6 solid;
        height: 350px;
        width: 50%;
        margin-left: 25%;
        position: absolute;
        top: 20%;
        z-index: 1000;
        box-shadow: 0px 0px 5px 5px #888888;
        display: none;
    }

    .xy {
        line-height: 35px;
        width: 362px;
        color: #fff;
        margin-left: auto;
        margin-right: auto;
        margin-top: 20px
    }

    .xy_info div, .xy_info button {
        margin-left: 8%;
    }

    .xy a {
        color: #fff;

        text-decoration: underline;
    }

</style>
</head>
<body>
<div class="content-wrap">
    <div class="bg-line">
        <div class="baner-wrap">
            <div banner-container class="banner-container">
                <div data-group class="center animating-enter-up">
                    <div data-base-layer>
                        <div class="banner-row" data-parallel="">
                            <div class="layer right-image hover-image  no-transition layer1" data-zindex="50"
                                 style="transform: translateZ(50px);">
                                <img src="/img/earth1.png" alt="">
                            </div>
                            <div class="layer right-image hover-image  no-transition layer2" data-zindex="100"
                                 style="transform: translateZ(100px);">
                                <img src="/img/earth2.png" alt="">
                            </div>
                            <div class="layer right-image hover-image  no-transition layer3" data-zindex="150"
                                 style="transform: translateZ(150px);">
                                <img src="/img/earth3.png" alt="">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="lg-content" id="lgcontent">
            <div class="slogan-content">
                <div style="height: 60px;background-image: url(/img/logo.png);margin-left: -20px;"></div>
                <!--                    <h2 class="h-m">大农云•新农业•云智慧•心服务</h2>-->
                <p class="h-p">
                    致力于为渠道商开发全国优质农特产，助力全国优质农特产上行，通过云技术构建渠道联盟和供应链联盟，降低供应链成本，提升供应链流通效率，赋能供应链生态圈。
                </p>
                <div style="height: 33px; background-image:url(/img/log.png);">

                </div>
            </div>
            <div class="login-inner">
                <div class="qc-pt-login-content" id="loginBox">
                    <div class="qc-pt-login-content J-commonLoginContent ">
                        <div class="login-tab">
                            <form action="<?= Yii::$app->homeUrl ?>/system/login/home" method="post" name="loginform"
                                  class="login-form">
                                <input type="hidden" id="csrf" name="_csrf"
                                       value="<?= \Yii::$app->request->getCsrfToken() ?>">
                                <div class="login-tab-title J-txtLoginTitle">
                                    <p class="title-en">USER LOGIN</p>
                                    <p class="title-zh">用户登录</p>
                                </div>
                                <div class="login-box J-loginContentBox J-qcloginBox" style="">
                                    <div class="login-form">
                                        <div class="tc-msg error" style="display:none"><span class="msg-icon"></span>
                                            <div class="tip-info J-loginTip"></div>
                                        </div>
                                        <ul class="form-list">
                                            <li>
                                                <div class="form-input">
                                                    <div class="form-unit tip-unit"><label class="input-tips"
                                                                                           style="display: none;">管理员账号</label>
                                                        <div class="form-group field-admin-admin_user">
                                                            <input class="qc-log-input-text lg J-username" type="text"
                                                                   autocomplete="off" placeholder="用户名"
                                                                   name="sLoginName" value="<?= $sLoginName ?>"/>
                                                            <i class="l-icon l-icon1"></i>
                                                            <p class="help-block help-block-error"></p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </li>
                                            <li>
                                                <div class="form-input">
                                                    <div class="form-unit"><label class="input-tips"
                                                                                  style="display: none;">密码</label>
                                                        <div class="form-group field-admin-admin_pass">
                                                            <input class="qc-log-input-text lg J-password"
                                                                   type="password" autocomplete="off" placeholder="密码"
                                                                   name="sPassword"
                                                                   onkeypress="if(event.keyCode == 13){ this.form.submit() }"/>
                                                            <i class="l-icon l-icon2"></i>
                                                            <p class="help-block help-block-error"></p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </li>
                                            <li class="J-vcodeArea">
                                                <?php
                                                if ($sErrMsg) {
                                                    ?>
                                                    <div class="alert alert-danger">
                                                        <button class="close" data-close="alert"></button>
                                                        <span><?= $sErrMsg ?></span>
                                                    </div>
                                                    <?php
                                                } else {
                                                    ?>
                                                    <div class="alert alert-danger display-hide">
                                                        <button class="close" data-close="alert"></button>
                                                        <span>请输入用户名和密码</span>
                                                    </div>
                                                    <?php
                                                }
                                                ?>

                                            </li>
                                        </ul>
                                    </div>
                                    <div class="op-btn">
                                        <button type="submit" class="qc-log-btn layui-btn" name="login-button">登录
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="xy-content" id="xycontent">
    <div class="xy">
        <div style="height: 60px;background-image: url(/img/logo.png)"></div>
        <div class="xy_info">
            <input type="hidden" id="SysUserID" value="<?= $SysUserID ?>">
            <input type="hidden" id="status" value="<?= $status ?>">
            <div style="margin-top: 40px;">1、点击查看： <a href="/userfile/ylgysgz.pdf" target="_blank">有链供货商管理规则</a></div>
            <div><input type="checkbox" value="1" id="gz"><label for="gz">已阅读有链供货商管理规则</label></div>
            <div>2、点击查看：<a href="/userfile/ylgysxy.pdf" target="_blank">有链供货商入驻服务协议</a></div>
            <div><input type="checkbox" value="1" id="xy"><label for="xy">已阅读有链供货商入驻服务协议</label></div>
            <button type="button" class="qc-log-btn layui-btn" style="width: 100px;color: red" name="login-button"
                    onclick="submitprotocol()">确认
            </button>
        </div>
    </div>
</div>
<div class="xy-content" id="qdxycontent">
    <div class="xy">
        <div style="height: 60px;background-image: url(/img/logo.png)"></div>
        <div class="xy_info">
            <div style="margin-top: 40px;">1、点击查看： <a href="/userfile/syxy.pdf" target="_blank">有链科技有限公司供应链服务试用协议</a></div>
            <div><input type="checkbox" value="1" id="syxy"><label for="syxy">已阅读有链科技有限公司供应链服务试用协议</label></div>
            <button type="button" class="qc-log-btn layui-btn" style="width: 100px;color: red" name="login-button"
                    onclick="submitsyxy()">确认
            </button>
        </div>
    </div>
</div>
<!-- END : LOGIN PAGE 5-1 -->
<!--[if lt IE 9]>
<script src="<?=Yii::$app->homeUrl?>/js/global/plugins/respond.min.js"></script>
<script src="<?=Yii::$app->homeUrl?>/js/global/plugins/excanvas.min.js"></script>
<![endif]-->
<!-- BEGIN CORE PLUGINS -->
<script src="<?= Yii::$app->homeUrl ?>/js/global/plugins/jquery.min.js" type="text/javascript"></script>
<script src="<?= Yii::$app->homeUrl ?>/js/global/plugins/bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
<script src="<?= Yii::$app->homeUrl ?>/js/global/plugins/js.cookie.min.js" type="text/javascript"></script>
<script src="<?= Yii::$app->homeUrl ?>/js/global/plugins/bootstrap-hover-dropdown/bootstrap-hover-dropdown.min.js"
        type="text/javascript"></script>
<script src="<?= Yii::$app->homeUrl ?>/js/global/plugins/jquery-slimscroll/jquery.slimscroll.min.js"
        type="text/javascript"></script>
<script src="<?= Yii::$app->homeUrl ?>/js/global/plugins/jquery.blockui.min.js" type="text/javascript"></script>
<script src="<?= Yii::$app->homeUrl ?>/js/global/plugins/uniform/jquery.uniform.min.js" type="text/javascript"></script>
<script src="<?= Yii::$app->homeUrl ?>/js/global/plugins/bootstrap-switch/js/bootstrap-switch.min.js"
        type="text/javascript"></script>
<!-- END CORE PLUGINS -->
<!-- BEGIN PAGE LEVEL PLUGINS -->
<script src="<?= Yii::$app->homeUrl ?>/js/global/plugins/jquery-validation/js/jquery.validate.min.js"
        type="text/javascript"></script>
<script src="<?= Yii::$app->homeUrl ?>/js/global/plugins/jquery-validation/js/additional-methods.min.js"
        type="text/javascript"></script>
<script src="<?= Yii::$app->homeUrl ?>/js/global/plugins/select2/js/select2.full.min.js"
        type="text/javascript"></script>
<script src="<?= Yii::$app->homeUrl ?>/js/global/plugins/backstretch/jquery.backstretch.min.js"
        type="text/javascript"></script>
<!-- END PAGE LEVEL PLUGINS -->
<!-- BEGIN THEME GLOBAL SCRIPTS -->
<script src="<?= Yii::$app->homeUrl ?>/js/global/scripts/app.min.js" type="text/javascript"></script>
<!-- END THEME GLOBAL SCRIPTS -->
<!-- BEGIN PAGE LEVEL SCRIPTS -->
<script src="<?= Yii::$app->homeUrl ?>/js/pages/scripts/login-5.min.js" type="text/javascript"></script>
<!-- END PAGE LEVEL SCRIPTS -->
<!-- BEGIN THEME LAYOUT SCRIPTS -->
<!-- END THEME LAYOUT SCRIPTS -->
<script>
    $(function () {
        var banner = $(".banner-container");
        var inWidth = $(window).innerWidth();
        var inHeight = $(window).innerHeight();
        banner.on("mousemove", function (e) {
            var ax = -(inWidth / 2 - e.pageX) / 40;
            var ay = (inHeight / 2 - e.pageY) / 30;
            $('.banner-row').attr("style", "transform: rotateY(" + ax + "deg) rotateX(" + ay + "deg);-webkit-transform: rotateY(" + ax + "deg) rotateX(" + ay + "deg);-moz-transform: rotateY(" + ax + "deg) rotateX(" + ay + "deg)");
        });

        if ($('#SysUserID').val() != "") {
            if($('#status').val()=='check') {
                $('#lgcontent').hide();
                $('#xycontent').show();
            }
            else if($('#status').val()=='checkqd') {
                $('#lgcontent').hide();
                $('#qdxycontent').show();
            }
        }

    })

    function submitprotocol() {
        var SysUserID = $('#SysUserID').val();

        if (!$('#gz').prop('checked')) {
            alert('已阅读有链供货商管理规则！');
            return;
        }
        if (!$('#xy').prop('checked')) {
            alert('已阅读有链供货商入驻服务协议！');
            return;
        }
        var csrf = $('#csrf').val();

        $.post('<?=Yii::$app->homeUrl?>/system/login/check', {
            gz: 1,
            xy: 1,
            SysUserID: SysUserID,
            _csrf: csrf
        }, function (data) {
            if (data.status == 1000) {
                location.href = '' + '/system/frame/home';
            }
        });

    }
    function submitsyxy() {
        var SysUserID = $('#SysUserID').val();
        if (!$('#syxy').prop('checked')) {
            alert('已阅读有链科技有限公司供应链服务试用协议！');
            return;
        }

        var csrf = $('#csrf').val();

        $.post('<?=Yii::$app->homeUrl?>/system/login/check', {
            syxy: 1,
            SysUserID: SysUserID,
            _csrf: csrf
        }, function (data) {
            if (data.status == 1000) {
                location.href = '' + '/system/frame/home';
            }
        });

    }
</script>
</body>
</html>