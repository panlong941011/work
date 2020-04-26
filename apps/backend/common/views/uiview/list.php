<?php
if ($data === null) {
	echo "&nbsp;";
} else {
	if ($field->sEnumTable && $field->sEnumTable != "System/SysFieldEnum") {
		echo '<a href="javascript:;" onclick="parent.addTab($(this).text(), \''.Yii::$app->homeUrl.'/'.strtolower($field->sEnumTable).'/viewredirect?ID='.$data['ID'].'&FieldID='.$field->ID.'\')">'.$data['sName'].'</a>';
	} else {
		echo $data['sName'];
	}
}

