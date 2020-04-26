<!DOCTYPE html>
<!--[if IE 8]> <html lang="en" class="ie8 no-js"> <![endif]-->
<!--[if IE 9]> <html lang="en" class="ie9 no-js"> <![endif]-->
<!--[if !IE]><!-->
<html lang="en">
    <!--<![endif]-->
    <!-- BEGIN HEAD -->
    <head>
        <meta charset="utf-8" />
        <title>有链供应链管理系统</title>
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta content="width=device-width, initial-scale=1" name="viewport" />
        <meta content="" name="description" />
        <meta content="" name="author" />
        <!-- BEGIN GLOBAL MANDATORY STYLES -->
        <link href="<?=Yii::$app->homeUrl?>/js/pages/css/font.css" rel="stylesheet" type="text/css" />
        <link href="<?=Yii::$app->homeUrl?>/js/global/plugins/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css" />
        <link href="<?=Yii::$app->homeUrl?>/js/global/plugins/simple-line-icons/simple-line-icons.min.css" rel="stylesheet" type="text/css" />
        <link href="<?=Yii::$app->homeUrl?>/js/global/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
        <link href="<?=Yii::$app->homeUrl?>/js/global/plugins/uniform/css/uniform.default.css" rel="stylesheet" type="text/css" />
        <link href="<?=Yii::$app->homeUrl?>/js/global/plugins/bootstrap-switch/css/bootstrap-switch.min.css" rel="stylesheet" type="text/css" />
        <!-- END GLOBAL MANDATORY STYLES -->
        <!-- BEGIN THEME GLOBAL STYLES -->
        <link href="<?=Yii::$app->homeUrl?>/js/global/css/components.min.css" rel="stylesheet" id="style_components" type="text/css" />
        <link href="<?=Yii::$app->homeUrl?>/js/pages/css/frame.css" rel="stylesheet" type="text/css" />
        <!-- END THEME GLOBAL STYLES -->
        <!-- BEGIN THEME LAYOUT STYLES -->
        <link href="<?=Yii::$app->homeUrl?>/js/layouts/layout/css/layout.min.css" rel="stylesheet" type="text/css" />
        <link href="<?=Yii::$app->homeUrl?>/js/layouts/layout/css/themes/darkblue.min.css" rel="stylesheet" type="text/css" id="style_color" />
        <link href="<?=Yii::$app->homeUrl?>/js/layouts/layout/css/custom.min.css" rel="stylesheet" type="text/css" />
        
        <!-- END THEME LAYOUT STYLES -->
        <link rel="shortcut icon" href="/favicon.ico" />
    </head>
    <!-- END HEAD -->

    <body class="page-header-fixed page-sidebar-closed-hide-logo page-content-white page-sidebar-fixed page-footer-fixed">
        <!-- BEGIN HEADER -->
        <div class="page-header navbar navbar-fixed-top">
            <!-- BEGIN HEADER INNER -->
            <div class="page-header-inner ">
                <!-- BEGIN LOGO -->
                <div class="page-logo">
                    <a href="<?=Yii::$app->homeUrl?>/system/frame/home">
                        <!--                        <img src="-->
                        <!--                        --><?//=Yii::$app->homeUrl?>
                        <!--                        /js/pages/img/logo.png" alt="logo" class="logo-default" /> -->
                    </a>
                    <div class="menu-toggler sidebar-toggler"> </div>
                </div>
                <!-- END LOGO -->
                <!-- BEGIN RESPONSIVE MENU TOGGLER -->
                <a href="javascript:;" class="menu-toggler responsive-toggler" data-toggle="collapse" data-target=".navbar-collapse"> </a>
                <!-- END RESPONSIVE MENU TOGGLER -->
                
                
                <!-- BEGIN TABS TABS标题 -->
                <div class="tab-table-box">
                    <div class="tabbable tabbable-tabdrop pull-left">
                        <ul class="nav nav-pills pull-left">
                            <li id='tt-<?=Yii::t('app', '首页')?>' class='tabs-title active' onclick='switchTab(this)'><a  data-toggle='tab' href='#con-<?=Yii::t('app', '首页')?>'><?=Yii::t('app', '首页')?></a></li>
                        </ul>
                    </div>
                    <ul class="nav pull-left tab-dropdow-box">
                             <li class="dropdown pull-right tabdrop">
                                <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                                    <i class="fa fa-ellipsis-v"></i>&nbsp;
                                    <i class="fa fa-angle-down"></i> 
                                    <b class="caret"></b>
                                </a>
                                 <ul class="dropdown-menu">
                                       <li id='di-<?=Yii::t('app', '首页')?>' onclick='showTab(this)'><a href='#con-<?=Yii::t('app', '首页')?>' data-toggle='tab'><?=Yii::t('app', '首页')?></a></li>
                                 </ul>
                            </li>
                    </ul>
                </div>            
                <!-- END TABS TABS标题 -->
                
                <!-- BEGIN TOP NAVIGATION MENU -->
                <div class="top-menu">
                    <ul class="nav navbar-nav pull-right">
                        <!-- BEGIN USER LOGIN DROPDOWN -->
                        <!-- DOC: Apply "dropdown-dark" class after below "dropdown-extended" to change the dropdown styte -->
                        <li class="dropdown dropdown-user">
                            <a href="javascript:;" class="dropdown-toggle" data-toggle="dropdown" data-hover="dropdown" data-close-others="true">
                                <img alt="" class="img-circle" src="<?=Yii::$app->homeUrl?>/js/pages/img/avatar.jpg" />
                                <span class="username username-hide-on-mobile"> <?=Yii::$app->backendsession->sysuser->sName?> </span>
                                <i class="fa fa-angle-down"></i>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-default">
                                <li>
                                    <a href="javascript:;" onClick="addTab('<?=Yii::t('app', '个人信息')?>', '<?=Yii::$app->homeUrl?>/system/profile/home')">
                                        <i class="icon-user"></i> <?=Yii::t('app', '个人信息')?> </a>
                                </li>
                                <li class="divider"> </li>
                                <li>
                                    <a href="<?=Yii::$app->homeUrl?>/system/login/logout">
                                        <i class="icon-key"></i> <?=Yii::t('app', '退出')?> </a>
                                </li>
                            </ul>
                        </li>
                        <!-- END USER LOGIN DROPDOWN -->
                        <!-- BEGIN QUICK SIDEBAR TOGGLER -->
                        <!-- DOC: Apply "dropdown-dark" class after below "dropdown-extended" to change the dropdown styte -->
                        <li class="dropdown dropdown-quick-sidebar-toggler">
                            <a href="<?=Yii::$app->homeUrl?>/system/login/logout" class="dropdown-toggle">
                                <i class="icon-logout"></i>
                            </a>
                        </li>
                        <!-- END QUICK SIDEBAR TOGGLER -->
                    </ul>
                </div>
                <!-- END TOP NAVIGATION MENU -->
            </div>
            <!-- END HEADER INNER -->
        </div>
        <!-- END HEADER -->
        <!-- BEGIN HEADER & CONTENT DIVIDER -->
        <div class="clearfix"> </div>
        <!-- END HEADER & CONTENT DIVIDER -->
        <!-- BEGIN CONTAINER -->
        <div class="page-container">
            <!-- BEGIN SIDEBAR -->
            <div class="page-sidebar-wrapper">
                <!-- BEGIN SIDEBAR -->
                <!-- DOC: Set data-auto-scroll="false" to disable the sidebar from auto scrolling/focusing -->
                <!-- DOC: Change data-auto-speed="200" to adjust the sub menu slide up/down speed -->
                <div class="page-sidebar navbar-collapse collapse">
                    <!-- BEGIN SIDEBAR MENU -->
                    <!-- DOC: Apply "page-sidebar-menu-light" class right after "page-sidebar-menu" to enable light sidebar menu style(without borders) -->
                    <!-- DOC: Apply "page-sidebar-menu-hover-submenu" class right after "page-sidebar-menu" to enable hoverable(hover vs accordion) sub menu mode -->
                    <!-- DOC: Apply "page-sidebar-menu-closed" class right after "page-sidebar-menu" to collapse("page-sidebar-closed" class must be applied to the body element) the sidebar sub menu mode -->
                    <!-- DOC: Set data-auto-scroll="false" to disable the sidebar from auto scrolling/focusing -->
                    <!-- DOC: Set data-keep-expand="true" to keep the submenues expanded -->
                    <!-- DOC: Set data-auto-speed="200" to adjust the sub menu slide up/down speed -->
                    <ul class="page-sidebar-menu  page-header-fixed " data-keep-expanded="false" data-auto-scroll="true" data-slide-speed="200" style="padding-top: 20px">
                        <li class="nav-item start ">
                            <a href="javascript:;" class="nav-link nav-toggle" onClick="addTab('<?=Yii::t('app', '首页')?>', '<?=Yii::$app->homeUrl?>/system/dashboard/home')">
                                <i class="icon-home"></i
                                <span class="title"><?=Yii::t('app', '首页')?></span>
                            </a>
                        </li>                    
                    	<? foreach ($solution->navheadings as $heading) { ?>
                        <li class="heading">
                            <h3 class="uppercase"><?=$heading->sName?></h3>
                        </li>
                        <? foreach ($heading->navtabs as $navTab) { ?>
                        <li class="nav-item ">
                            <a href="javascript:;" class="nav-link nav-toggle">
                                <span class="title"><i class="icon-settings"></i> <?=$navTab->sName?></span>
                                <span class="arrow"></span>
                            </a>
                            <ul class="sub-menu">
                            	<? foreach ($navTab->items as $item) { ?>
                                <li class="nav-item  ">
                                    <a href="javascript:;" onClick="addTab('<?=$item->navitem->sName?>', '<?=Yii::$app->homeUrl?>/<?=$item->navitem->sAction?>')" class="nav-link">
                                        <span class="title"><?=$item->navitem->sName?></span>
                                    </a>
                                </li>
                                <? } ?>
                            </ul>
                        </li>
                        <? } ?>
						<? } ?>

                    </ul>
                    <!-- END SIDEBAR MENU -->
                    <!-- END SIDEBAR MENU -->
                </div>
                <!-- END SIDEBAR -->
            </div>
            <!-- END SIDEBAR -->
            <!-- BEGIN CONTENT -->
            <div class="page-content-wrapper">
                <!-- BEGIN CONTENT BODY -->
                <div class="page-content">
                <div class="tab-content">
                    <div class='tab-pane active' id='con-<?=Yii::t('app', '首页')?>'><iframe id='ifr-<?=Yii::t('app', '首页')?>' name='ifr-<?=Yii::t('app', '首页')?>' scrolling='auto' frameborder='0' src='<?=Yii::$app->homeUrl?>/system/dashboard/home' style='width:100%;height:100%;'></iframe>
                    </div>
                                   
                </div>
                </div>
                 
                  
                <!-- END CONTENT BODY -->
            </div>
            <!-- END CONTENT -->
                

        </div>
        <!-- END CONTAINER -->
        <!-- BEGIN FOOTER -->
        <div class="page-footer">
            <div class="page-footer-inner">  &copy; 闽ICP备19014229号 厦门有链科技有限公司
            </div>
            <div class="scroll-to-top">
                <i class="icon-arrow-up"></i>
            </div>
        </div>
        <!-- END FOOTER -->
        <!--[if lt IE 9]>
        <script src="<?=Yii::$app->homeUrl?>/js/global/plugins/respond.min.js"></script>
        <script src="<?=Yii::$app->homeUrl?>/js/global/plugins/excanvas.min.js"></script>
        <![endif]-->
        <!-- BEGIN CORE PLUGINS -->
        <script src="<?=Yii::$app->homeUrl?>/js/global/plugins/jquery.min.js" type="text/javascript"></script>
        <script src="<?=Yii::$app->homeUrl?>/js/global/plugins/bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
        <script src="<?=Yii::$app->homeUrl?>/js/global/plugins/js.cookie.min.js" type="text/javascript"></script>
        <script src="<?=Yii::$app->homeUrl?>/js/global/plugins/bootstrap-hover-dropdown/bootstrap-hover-dropdown.min.js" type="text/javascript"></script>
        <script src="<?=Yii::$app->homeUrl?>/js/global/plugins/jquery-slimscroll/jquery.slimscroll.min.js" type="text/javascript"></script>
        <script src="<?=Yii::$app->homeUrl?>/js/global/plugins/jquery.blockui.min.js" type="text/javascript"></script>
        <script src="<?=Yii::$app->homeUrl?>/js/global/plugins/uniform/jquery.uniform.min.js" type="text/javascript"></script>
        <script src="<?=Yii::$app->homeUrl?>/js/global/plugins/bootstrap-switch/js/bootstrap-switch.min.js" type="text/javascript"></script>
        <!-- END CORE PLUGINS -->
        <!-- BEGIN THEME GLOBAL SCRIPTS -->
        <script src="<?=Yii::$app->homeUrl?>/js/global/scripts/app.min.js" type="text/javascript"></script>
        <!-- END THEME GLOBAL SCRIPTS -->
        <!-- BEGIN THEME LAYOUT SCRIPTS -->
        <script src="<?=Yii::$app->homeUrl?>/js/layouts/layout/scripts/layout.min.js" type="text/javascript"></script>
        <!-- END THEME LAYOUT SCRIPTS -->
        <!-- 自定义JS -->
        <script src="<?=Yii::$app->homeUrl?>/js/pages/scripts/frame.js" type="text/javascript"></script>
        
    </body>

</html>