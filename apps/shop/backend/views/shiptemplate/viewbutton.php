<?if(\Yii::$app->backendsession->SysRoleID == 1){?>
<a href="<?=Yii::$app->homeUrl?>/<?=strtolower($this->context->sObjectName)?>/edit?ID=<?=$_GET['ID']?>" class="btn green btn-sm"><?=Yii::t('app', '编辑')?></a>
<?}?>
<a href="javascript:parent.closeCurrTab()" class="btn green btn-sm"><?=Yii::t('app', '关闭')?></a>
<a href="<?=Yii::$app->homeUrl?>/<?=strtolower($this->context->sObjectName)?>/view?ID=<?=$_GET['ID']?>" class="btn green btn-sm"><?=Yii::t('app', '刷新')?></a>