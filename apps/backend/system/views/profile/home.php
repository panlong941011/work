<div class="breadcrumb" style="display:none">
    <h2><?=Yii::$app->backendsession->sysuser->sName?></h2>
    <h3><?=Yii::t('app', '个人信息')?></h3>
</div>
<div class="magin-top-10"></div>
<div class="row">
    <div class="col-md-12">
        <!-- BEGIN PROFILE SIDEBAR -->
        <div class="profile-sidebar bordered">
            <!-- PORTLET MAIN -->
            <div class="portlet light profile-sidebar-portlet ">
                <!-- SIDEBAR USERPIC -->
                <div class="profile-userpic">
                    <img src="<?=Yii::$app->homeUrl?>/js/pages/img/avatar250x250.jpg" class="img-responsive" alt=""> </div>
                <!-- END SIDEBAR USERPIC -->
                <!-- SIDEBAR USER TITLE -->
                <div class="profile-usertitle">
                    <div class="profile-usertitle-name"> <?=Yii::$app->backendsession->sysuser->sName?> </div>
                    <div class="profile-usertitle-job"> <?=Yii::$app->backendsession->sysrole->sName?> </div>
                </div>
                <!-- END SIDEBAR USER TITLE -->
                <!-- SIDEBAR MENU -->
                <div class="profile-usermenu">
                    <ul class="nav">

                    </ul>
                </div>
                <!-- END MENU -->
            </div>
            <!-- END PORTLET MAIN -->
        </div>
        <!-- END BEGIN PROFILE SIDEBAR -->
        <!-- BEGIN PROFILE CONTENT -->
        <div class="profile-content">
            <div class="row">
                <div class="col-md-12">
                    <div class="portlet light ">
                        <div class="portlet-title tabbable-line">
                            <div class="caption caption-md">
                                <i class="icon-globe theme-font hide"></i>
                                <span class="caption-subject font-blue-madison bold uppercase"><?=Yii::t('app', '个人信息')?></span>
                            </div>
                            <ul class="nav nav-tabs">
                                <li class="active">
                                    <a href="#tab_1_1" data-toggle="tab"><?=Yii::t('app', '基本信息')?></a>
                                </li>
                                <li>
                                    <a href="#tab_1_3" data-toggle="tab"><?=Yii::t('app', '修改密码')?></a>
                                </li>
                            </ul>
                        </div>
                        <div class="portlet-body">
                            <div class="tab-content">
                                <!-- PERSONAL INFO TAB -->
                                <div class="tab-pane active" id="tab_1_1">
                                    <form name="profileform" action="./saveprofile" method="post">
                                        <div class="form-group">
                                            <label class="control-label"><?=Yii::t('app', '姓名')?><span class="required" aria-required="true">*</span>:</label>
                                            <input type="text" placeholder="" class="form-control" name="profile[sName]" value="<?=Yii::$app->backendsession->sysuser->sName?>">
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label"><?=Yii::t('app', '邮箱')?><span class="required" aria-required="true">*</span>:</label>
                                            <input type="text" placeholder="" class="form-control" name="profile[sEMail]" value="<?=Yii::$app->backendsession->sysuser->sEMail?>">
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label"><?=Yii::t('app', '手机')?><span class="required" aria-required="true">*</span>:</label>
                                            <input type="text" placeholder="" class="form-control" name="profile[sMobile]" value="<?=Yii::$app->backendsession->sysuser->sMobile?>">
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label"><?=Yii::t('app', 'QQ')?><span class="required" aria-required="true">*</span>:</label>
                                            <input type="text" placeholder="" class="form-control" name="profile[sQQ]" value="<?=Yii::$app->backendsession->sysuser->sQQ?>">
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label"><?=Yii::t('app', '语言')?><span class="required" aria-required="true">*</span>:</label>
                                            <select class="form-control" name="profile[LanguageID]">
                                                <? foreach ($arrLanguage as $LanguageID => $sValue) { ?>
                                                    <option value="<?=$LanguageID?>" <? if ($LanguageID == Yii::$app->backendsession->sysuser->LanguageID) { ?>selected<? } ?>><?=$sValue?></option>
                                                <? } ?>
                                            </select>
                                        </div>
                                        <div class="margiv-top-10">
                                        	<a href="javascript:parent.closeCurrTab();" class="btn default"> <?=Yii::t('app', '取消')?> </a>
                                            <a href="javascript:saveProfile();" class="btn green"> <?=Yii::t('app', '保存')?> </a>
                                        </div>
                                    </form>
                                </div>
                                <!-- END PERSONAL INFO TAB -->
                                <!-- CHANGE AVATAR TAB -->
                                <div class="tab-pane" id="tab_1_2">
                                    <p> Anim pariatur cliche reprehenderit, enim eiusmod high life accusamus terry richardson ad squid. 3 wolf moon officia aute, non cupidatat skateboard dolor brunch. Food truck quinoa nesciunt laborum
                                        eiusmod. </p>
                                    <form action="#" role="form">
                                        <div class="form-group">
                                            <div class="fileinput fileinput-new" data-provides="fileinput">
                                                <div class="fileinput-new thumbnail" style="width: 200px; height: 150px;">
                                                    <img src="http://www.placehold.it/200x150/EFEFEF/AAAAAA&amp;text=no+image" alt=""> </div>
                                                <div class="fileinput-preview fileinput-exists thumbnail" style="max-width: 200px; max-height: 150px;"> </div>
                                                <div>
                                                    <span class="btn default btn-file">
                                                        <span class="fileinput-new"> Select image </span>
                                                        <span class="fileinput-exists"> Change </span>
                                                        <input type="file" name="..."> </span>
                                                    <a href="javascript:;" class="btn default fileinput-exists" data-dismiss="fileinput"> Remove </a>
                                                </div>
                                            </div>
                                            <div class="clearfix margin-top-10">
                                                <span class="label label-danger">NOTE! </span>
                                                <span>Attached image thumbnail is supported in Latest Firefox, Chrome, Opera, Safari and Internet Explorer 10 only </span>
                                            </div>
                                        </div>
                                        <div class="margin-top-10">
                                            <a href="javascript:;" class="btn green"> Submit </a>
                                            <a href="javascript:;" class="btn default"> Cancel </a>
                                        </div>
                                    </form>
                                </div>
                                <!-- END CHANGE AVATAR TAB -->
                                <!-- CHANGE PASSWORD TAB -->
                                <div class="tab-pane" id="tab_1_3">
                                    <form name="passwordform" action="./savepassword" method="post">
                                        <div class="form-group">
                                            <label class="control-label"><?=Yii::t('app', '当前密码')?><span class="required" aria-required="true">*</span>:</label>
                                            <input type="password" class="form-control" name="sCurrPass"> </div>
                                        <div class="form-group">
                                            <label class="control-label"><?=Yii::t('app', '新密码')?><span class="required" aria-required="true">*</span>:</label>
                                            <input type="password" class="form-control" name="sNewPass"> </div>
                                        <div class="form-group">
                                            <label class="control-label"><?=Yii::t('app', '重新输入新密码')?><span class="required" aria-required="true">*</span>:</label>
                                            <input type="password" class="form-control" name="sNewPassConfirm"> </div>
                                        <div class="margin-top-10">
                                        	<a href="javascript:parent.closeCurrTab();" class="btn default"> <?=Yii::t('app', '取消')?> </a>
                                            <a href="javascript:savePassword();" class="btn green"> <?=Yii::t('app', '保存')?> </a>
                                        </div>
                                    </form>
                                </div>
                                <!-- END CHANGE PASSWORD TAB -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- END PROFILE CONTENT -->
    </div>
</div>
<script src="<?=Yii::$app->homeUrl?>/system/profile/js" type="text/javascript"></script>
<link href="<?=Yii::$app->homeUrl?>/js/pages/css/profile.min.css" rel="stylesheet" type="text/css" />