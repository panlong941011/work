<div class="breadcrumb" style="display:none">
    <h2><a href="<?=Yii::$app->homeUrl?>/<?=strtolower($this->context->sysObject->sObjectName)?>/home"><?=$this->context->sysObject->sName?></a></h2>
    <h3><?=Yii::t('app', '详情页')?></h3>
</div>

<div class="row margin-top-10">
    <div class="col-md-12">
        <div class="portlet light bordered">
        	<div class="portlet-title">
                <div class="caption">
                    <span class="caption-subject font-green-haze bold uppercase"><?=$solution['sName']?></span>
                </div>
                <div class="tools">
                </div>
            </div>
            <div class="form-actions">
                <div class="row">
                    <div class="col-md-12">
                    	<a href="javascript:void(0)" class="btn green btn-sm" onClick="newNavHeading('<?=$_GET['ID']?>')">新增分类</a>
                        <a href="javascript:void(0)" class="btn green btn-sm" onClick="sortNavHeading('<?=$_GET['ID']?>')">分类排序</a>
                    	<a href="javascript:void(0)" class="btn green btn-sm" onClick="apply('<?=$_GET['ID']?>')">应用</a>
                        <a href="<?=Yii::$app->homeUrl?>/<?=strtolower($this->context->sObjectName)?>/edit?ID=<?=$_GET['ID']?>" class="btn green btn-sm">编辑</a>
                        <a href="<?=Yii::$app->homeUrl?>/<?=strtolower($this->context->sObjectName)?>/view?ID=<?=$_GET['ID']?>" class="btn green btn-sm">刷新</a>
                    </div>
                    <div class="col-md-12"> </div>
                </div>
            </div>
            <div class="portlet-body form">
            	<? foreach ($solution->navheadings as $heading) { ?>
                <form method="post" action="./deltab?ID=<?=$_GET['ID']?>">
                <div class="portlet box green">
                    <div class="portlet-title">
                        <div class="caption">
                            <i class="fa fa-cogs"></i><?=$heading->sName?> </div>
                            <div class="actions">
                                <a href="javascript:;" class="btn btn-default btn-sm" onclick="confirmation(this, '确定要删除该分类？', function(obj){ location.href='<?=Yii::$app->homeUrl?>/<?=strtolower($this->context->sObjectName)?>/delnavheading?ID=<?=$heading->ID?>&SolutionID=<?=$_GET['ID']?>'; }, 'left')"> <?=Yii::t('app', '删除')?> </a>
							</div>
                    </div>
                    <div class="portlet-body flip-scroll">
                        <div class="row">
                            <div class="col-md-12">
							                 	<a href="javascript:;" class="btn btn-default btn-sm green" onClick="newNavTab('<?=$heading->ID?>')"><?=Yii::t('app', '新增菜单')?> </a>
                                <a href="javascript:;" class="btn green btn-sm" onclick="editNavTab($(this).closest('.portlet-body'))">编辑</a>
                                <a href="javascript:void(0)" class="btn green btn-sm" onClick="sortNavTab('<?=$heading->ID?>')">菜单排序</a>                                
                                <a href="javascript:;" class="btn btn-default btn-sm green" onclick="confirmation(this, '确定要删除菜单？', function(obj){ delNavTab($(obj).closest('.portlet-body')) })"> <?=Yii::t('app', '删除')?> </a>
                            </div>
                            <div class="col-md-12"> </div>
                        </div>
                        <table class="table table-bordered table-striped table-condensed flip-content">
                            <thead class="flip-content">
                                <tr>
                                    <th width="50"> <?=Yii::t('app', '选择')?> </th>
                                    <th> <?=Yii::t('app', '菜单')?> </th>
                                    <th> <?=Yii::t('app', '菜单项')?> </th>
                                </tr>
                            </thead>
                            <tbody>
                            	<? foreach ($heading->navtabs as $tab) { ?>
                                <tr>
                                    <td>
                                    	<div class="checker"><span><input name="tabSelected[]" type="checkbox" class="group-checkable" value="<?=$tab->ID?>"></span></div>
                                    </td>
                                    <td><?=$tab->sName?></td>
									<td>
                                    <? foreach ($tab->items as $item) { ?>
                                    	<?=$item->navitem->sName?>&nbsp;&nbsp;
                                    <? } ?>
                                    </td>
                                </tr>
                                <? } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                </form>
                <? } ?>
			</div>
        </div>
    </div>
</div>
<script src="<?=Yii::$app->homeUrl?>/<?=strtolower($this->context->sObjectName)?>/js" type="text/javascript"></script>
