<h3 class="form-section"><?=Yii::t('app', '团队成员')?></h3>
<div class="row">
    <div class="form-group col-md-4">
        <label class="control-label col-md-3 bold"><?=Yii::t('app', '成员')?>:</label>
        <div class="col-md-9">
		<? foreach ($arrTeamUser as $user) { ?>
            <p class="form-control-static"><?=$user->sysuser->sName?></p>
        <? } ?>    		
    	</div>
    </div> 
</div>  