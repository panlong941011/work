<?php

use myerm\backend\common\libs\SystemTime;

if ($data === null) {
	
} elseif ($field->attr['dFormat'] == 'short') {
	echo SystemTime::getShortDate($data, $field->attr['lTimeOffset']);
} else {
	echo SystemTime::getLongDate($data, $field->attr['lTimeOffset']);
}

