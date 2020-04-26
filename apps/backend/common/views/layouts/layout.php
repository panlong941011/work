<!DOCTYPE html>
<!--[if IE 8]> <html lang="en" class="ie8 no-js"> <![endif]-->
<!--[if IE 9]> <html lang="en" class="ie9 no-js"> <![endif]-->
<!--[if !IE]><!-->
<html lang="en">
    <!--<![endif]-->
    <!-- BEGIN HEAD -->
    <head>
        <meta charset="utf-8" />
        <title><?=Yii::$app->params['sSystemTitle']?></title>
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta content="width=device-width, initial-scale=1" name="viewport" />
        <meta content="" name="description" />
        <meta content="MyERM" name="author" />
        <!-- BEGIN GLOBAL MANDATORY STYLES -->
        <link href="<?=Yii::$app->homeUrl?>/js/pages/css/font.css" rel="stylesheet" type="text/css" />
        <link href="<?=Yii::$app->homeUrl?>/js/global/plugins/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css" />
        <link href="<?=Yii::$app->homeUrl?>/js/global/plugins/simple-line-icons/simple-line-icons.min.css" rel="stylesheet" type="text/css" />
        <link href="<?=Yii::$app->homeUrl?>/js/global/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
        <link href="<?=Yii::$app->homeUrl?>/js/global/plugins/uniform/css/uniform.default.css" rel="stylesheet" type="text/css" />
        <link href="<?=Yii::$app->homeUrl?>/js/global/plugins/bootstrap-switch/css/bootstrap-switch.min.css" rel="stylesheet" type="text/css" />
        <link href="<?=Yii::$app->homeUrl?>/js/global/plugins/loadmask/jquery.loadmask.css" rel="stylesheet" type="text/css" />
        <!-- END GLOBAL MANDATORY STYLES -->
        <!-- BEGIN THEME GLOBAL STYLES -->
        <link href="<?=Yii::$app->homeUrl?>/js/global/css/components.min.css" rel="stylesheet" id="style_components" type="text/css" />
        <link href="<?=Yii::$app->homeUrl?>/js/global/css/plugins.min.css" rel="stylesheet" type="text/css" />
        
        <link href="<?=Yii::$app->homeUrl?>/js/global/plugins/bootstrap-toastr/toastr.min.css" rel="stylesheet" type="text/css" />
        <link href="<?=Yii::$app->homeUrl?>/js/global/plugins/bootstrap-modal/css/bootstrap-modal-bs3patch.css" rel="stylesheet" type="text/css" />
        <link href="<?=Yii::$app->homeUrl?>/js/global/plugins/bootstrap-modal/css/bootstrap-modal.css" rel="stylesheet" type="text/css" />
        <link href="<?=Yii::$app->homeUrl?>/js/global/plugins/bootstrap-multiselect/bootstrap-multiselect.css" rel="stylesheet" type="text/css" />
        <link href="/js/global/plugins/bootstrap-sweetalert/sweetalert.css" rel="stylesheet" type="text/css" />
        <!-- END THEME GLOBAL STYLES -->
        <!-- BEGIN THEME LAYOUT STYLES -->
        <link href="<?=Yii::$app->homeUrl?>/js/layouts/layout/css/layout.min.css" rel="stylesheet" type="text/css" />
        <link href="<?=Yii::$app->homeUrl?>/js/layouts/layout/css/themes/darkblue.min.css" rel="stylesheet" type="text/css" id="style_color" />
        <link href="<?=Yii::$app->homeUrl?>/js/layouts/layout/css/custom.min.css" rel="stylesheet" type="text/css" />
        
        <link href="<?=Yii::$app->homeUrl?>/js/pages/css/myerm.css" rel="stylesheet" type="text/css" />
        
        <link rel="shortcut icon" href="/favicon.ico" />
        
        <script src="<?=Yii::$app->homeUrl?>/js/global/plugins/jquery.js" type="text/javascript"></script>

        <script>
        var sHomeUrl = "<?=Yii::$app->homeUrl?>";
		var sObjectName = "<?=$this->context->sObjectName?>".toLowerCase();
        </script>
       	        
        <!-- END THEME LAYOUT STYLES -->
	</head>
    <!-- END HEAD -->

    <body class="page-header-fixed page-sidebar-closed-hide-logo page-container-bg-solid">

        <!-- BEGIN CONTENT -->
        <div class="page-content-wrapper">
            <!-- BEGIN CONTENT BODY -->
            <div class="page-content">
            
                <!-- BEGIN PAGE BAR -->
                <div class="page-bar">
                    <ul class="page-breadcrumb">
                        <li>
                            <a href="<?=Yii::$app->homeUrl?>/system/dashboard/home"><?=Yii::t('app', '首页')?></a>
                            <i class="fa fa-circle"></i>
                        </li>
                    </ul>
                </div>
                <!-- END PAGE BAR -->            
				<?=$content?>
            </div>
            <!-- END CONTENT BODY -->
        </div>
		<!-- END CONTENT -->

        <script src="<?=Yii::$app->homeUrl?>/<?=strtolower($this->context->sObjectName)?>/jslang" type="text/javascript"></script>

        <!--[if lt IE 9]>
        <script src="<?=Yii::$app->homeUrl?>/js/global/plugins/respond.min.js"></script>
        <script src="<?=Yii::$app->homeUrl?>/js/global/plugins/excanvas.min.js"></script>
        <![endif]-->
        <!-- BEGIN CORE PLUGINS -->        
        <script src="<?=Yii::$app->homeUrl?>/js/global/plugins/bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
        <script src="<?=Yii::$app->homeUrl?>/js/global/plugins/js.cookie.min.js" type="text/javascript"></script>
        <script src="<?=Yii::$app->homeUrl?>/js/global/plugins/bootstrap-hover-dropdown/bootstrap-hover-dropdown.min.js" type="text/javascript"></script>
        <script src="<?=Yii::$app->homeUrl?>/js/global/plugins/jquery-slimscroll/jquery.slimscroll.min.js" type="text/javascript"></script>
        <script src="<?=Yii::$app->homeUrl?>/js/global/plugins/jquery.blockui.min.js" type="text/javascript"></script>
        <!--  <script src="<?=Yii::$app->homeUrl?>/js/global/plugins/uniform/jquery.uniform.min.js" type="text/javascript"></script> -->
        <script src="<?=Yii::$app->homeUrl?>/js/global/plugins/bootstrap-switch/js/bootstrap-switch.min.js" type="text/javascript"></script>
        <script src="<?=Yii::$app->homeUrl?>/js/global/plugins/loadmask/jquery.loadmask.min.js" type="text/javascript"></script>
        <!-- END CORE PLUGINS -->
        <!-- BEGIN THEME GLOBAL SCRIPTS -->
        <script src="<?=Yii::$app->homeUrl?>/js/global/scripts/app.min.js" type="text/javascript"></script>
        <!-- END THEME GLOBAL SCRIPTS -->
        
        <script src="<?=Yii::$app->homeUrl?>/js/global/plugins/bootstrap-toastr/toastr.min.js" type="text/javascript"></script>
        <script src="<?=Yii::$app->homeUrl?>/js/global/plugins/bootstrap-confirmation/bootstrap-confirmation.js" type="text/javascript"></script>
        <script src="<?=Yii::$app->homeUrl?>/js/global/plugins/bootstrap-modal/js/bootstrap-modalmanager.js" type="text/javascript"></script>
        <script src="<?=Yii::$app->homeUrl?>/js/global/plugins/bootstrap-modal/js/bootstrap-modal.js" type="text/javascript"></script>
		<script src="<?=Yii::$app->homeUrl?>/js/global/plugins/bootstrap-multiselect/bootstrap-multiselect.js" type="text/javascript"></script>
        <script src="<?=Yii::$app->homeUrl?>/js/global/plugins/bootstrap-sweetalert/sweetalert.min.js" type="text/javascript"></script>
        <!-- BEGIN THEME LAYOUT SCRIPTS -->
        <script src="<?=Yii::$app->homeUrl?>/js/layouts/layout/scripts/layout.min.js" type="text/javascript"></script>
        <!-- END THEME LAYOUT SCRIPTS -->
        
        <script src="<?=Yii::$app->homeUrl?>/js/pages/scripts/myerm.js" type="text/javascript"></script>
        <div id="myermmodal" class="modal fade" tabindex="-1" data-focus-on="input:first"></div>
        <form name="formdata" method="post" style="display:none"></form>
    </body>

</html>