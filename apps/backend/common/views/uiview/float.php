<?php
if ($data === null) {
	echo "&nbsp;";
} else {
	echo number_format($data, $field->lDeciLength);
}

