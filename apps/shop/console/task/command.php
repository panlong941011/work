<?php

exec("php " . realpath(__DIR__ . "/../../../../shop") . " " . $data['command'] . " " . $data['param']." 2>&1", $out);

return implode("\n", $out);