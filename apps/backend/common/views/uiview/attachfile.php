<?php
if ($data === null) {
	echo "&nbsp;";
} else {
	if ($field->attr['attachType'] == 'isimg') {
?>
	<a target="_blank" href="<?=stristr($sLinkFieldValue, "://") ? $sLinkFieldValue : Yii::$app->params['sUploadUrl']."/".$sLinkFieldValue?>"><img <?=$field->attr['lImageWidth'] ? "width='".$field->attr['lImageWidth']."'" : "width='100'"?> <?=$field->attr['lImageHeight'] ? "height='".$field->attr['lImageHeight']."'" : "height='100'"?> src="<?=stristr($sLinkFieldValue, "://") ? $sLinkFieldValue : Yii::$app->params['sUploadUrl']."/".$sLinkFieldValue?>" title="<?=$data?>" /></a>
<?
	} else { 
?>
	<a target="_blank" href="<?=stristr($sLinkFieldValue, "://") ? $sLinkFieldValue : Yii::$app->params['sUploadUrl']."/".$sLinkFieldValue?>"><?=$data?></a>
<?	
	}
}
?>