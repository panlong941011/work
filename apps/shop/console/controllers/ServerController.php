<?php

namespace console\controllers;

use yii\console\Controller;

class ServerController extends Controller
{
    //端口号
    public $port;

    //开启任务数
    public $tasknum;

    //开始worker数
    public $workernum;


    public function options()
    {
        return ['port', 'tasknum', 'workernum'];
    }

    public function optionAliases()
    {
        return ['port' => 'port', 'tn' => 'tasknum', 'wn' => 'workernum'];
    }

    public function actionRestart()
    {
        $arrParam = $this->getRunparam();
        if ($arrParam['pid']) {
            //exec("kill -15 " . $arrParam['pid']);
            posix_kill($arrParam['pid'], 15);
            sleep(5);
        }

        $this->port = $arrParam['port'];
        $this->tasknum = $arrParam['tasknum'];
        $this->workernum = $arrParam['workernum'];
        $this->actionStart();
    }

    public function getRunparam()
    {
        return json_decode(file_get_contents(\Yii::$app->runtimePath . "/server.run"), true);
    }

    public function actionStart()
    {
        if (!$this->port) {
            echo "port必须传入！\n";
            return 1;
        }

        $http = new \swoole_http_server("0.0.0.0", $this->port);

        $config = [];

        if ($this->tasknum) {
            $config['task_worker_num'] = $this->tasknum;
        }

        if ($this->workernum) {
            $config['worker_num'] = $this->workernum;
        }

        $config['daemonize'] = 1;//蜕变成守护进程
        $config['reload_async'] = true;//异步重启
        $config['log_file'] = '/root/swoole.log';

        $http->set($config);

        //GET/POST请求
        $http->on('request', function ($request, $response) use ($http) {

            $output = [];
            try {
                include __DIR__ . "/../include/inc_common.php";
                include __DIR__ . "/../include/inc_coroutine_mysql.php";

                if (!is_file(__DIR__ . "/../request/" . basename($request->server['path_info']))) {
                    throw new \Exception("可执行文件/request/" . basename($request->server['path_info']) . "不存在");
                } else {
                    $ret = include __DIR__ . "/../request/" . basename($request->server['path_info']);
                }

                $output['status'] = 0;
                $output['data'] = $ret;

                $response->end(json_encode($output));
            } catch (\Exception $e) {
                $output['status'] = -1;
                $output['errmsg'] = $e->getMessage();
                $response->end(json_encode($output));
            }

            //2>&1参数表示即使执行有错误也输出到$out
            //exec("php /root/myerm/shop " . trim($request->server['path_info'] . " " . $request->get['param'] . " 2>&1",
            //"/"), $out);
            // $response->end(implode("<br>", $out));
        });

        //异步任务
        $http->on('task', function ($serv, $task_id, $from_id, $data) {

            $output = [];
            try {
                if ($data['script']) {
                    if (!is_file(__DIR__ . "/../task/" . $data['script'])) {
                        throw new \Exception("可执行文件/task/" . $data['script'] . "不存在");
                    } else {
                        $output['data'] = include __DIR__ . "/../task/" . $data['script'];
                        $output['status'] = 0;
                    }
                } else {
                    throw new \Exception("请传入可执行文件的参数".json_encode($data));
                }
            } catch (\Exception $e) {
                $output['status'] = -1;
                $output['errmsg'] = $e->getMessage();
            }

            return $output;
        });

        //异步任务回调
        $http->on('Finish', function ($serv, $task_id, $data) {

        });

        //服务器启动回调
        $http->on('start', function (\swoole_http_server $server) {
            $arrSetting = [
                'pid' => $server->master_pid,
                'port' => $this->port,
                'workernum' => $this->workernum,
                'tasknum' => $this->tasknum,
            ];

            file_put_contents(\Yii::$app->runtimePath . "/server.run", json_encode($arrSetting));
        });

        $http->start();
    }

    public function actionReload()
    {
        $arrParam = $this->getRunparam();
        if ($arrParam['pid']) {
            exec("kill -USR1 " . $arrParam['pid']);
        }
    }

    public function actionStop()
    {
        $arrParam = $this->getRunparam();
        if ($arrParam['pid']) {
            exec("kill -15 " . $arrParam['pid'], $out);
            echo implode("\n", $out) . "\n";
        } else {
            echo "Server is not started\n";
            return 1;
        }
    }

}