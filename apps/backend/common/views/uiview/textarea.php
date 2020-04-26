<?php
if ($data == "") {
	echo "&nbsp;";
} elseif ($field->bEnableRTE) {
	echo $data;	
} else {
	echo nl2br($data);	
}