<?php
error_reporting(E_ALL & ~E_NOTICE & ~E_STRICT & ~E_WARNING);
if ($request) {
    if ($request->post) {
        $ret = $http->taskwait($request->post, 60);
    } else {
        $ret = $http->taskwait($request->get, 60);
    }

    if ($ret['status'] == -1) {
        throw new \Exception($ret['errmsg']);
    } else {
        return $ret['data'];
    }
} else {
    if ($_POST['script'] == 'command.php') {

        $output = [];
        $output['status'] = 0;
        exec("php " . realpath(__DIR__ . "/../../../../shop") . " " . $_POST['command'] . " " . $_POST['param'], $out);
        $output['data'] = implode("\n", $out);

        echo json_encode($output);
    }
}

