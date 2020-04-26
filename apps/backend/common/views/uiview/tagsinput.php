<?php
if ($data === null) {
    echo "&nbsp;";
} else {
    $sComm = "";
    foreach ($data as $arr) {

        if ($field->sEnumTable && $field->sEnumTable != "System/SysFieldEnum") {
            echo $sComm.'<a href="javascript:;" onclick="parent.addTab($(this).text(), \''.Yii::$app->homeUrl.'/'.strtolower($field->sEnumTable).'/viewredirect?ID='.$arr['ID'].'&FieldID='.$field->ID.'\')">'.$arr['sName'].'</a>';
        } else {
            echo $sComm.$arr['sName'];
        }

        $sComm = " ; ";
    }
}