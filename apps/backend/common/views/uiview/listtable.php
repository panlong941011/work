<?php
if ($data === null) {
	echo "&nbsp;";
} elseif ($data) {
	echo '<a href="javascript:;" onclick="parent.addTab($(this).text(), \''.Yii::$app->homeUrl.'/'.strtolower($field->sRefKey).'/viewredirect?ID='.$data['ID'].'&FieldID='.$field->ID.'\')">'.$data['sName'].'</a>';
}