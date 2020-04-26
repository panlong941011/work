<?php
/**
 * 后台计划任务监控
 * ============================================================================
 * 版权所有 2010-2019 三只象（厦门）网络科技有限公司，并保留所有权利。
 * 网站地址: http://www.myerm.cn
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和
 * 使用；不允许对程序代码以任何形式任何目的的再发布。
 * ============================================================================
 * @author 石水木  <289787324@qq.com>
 * @SchedulesController.php
 * @created at 2019/4/15 8:58
 */

namespace console\controllers;

use yii\console\Controller;

class SchedulesController extends Controller
{
    //预设任务端口
    public $tasksPorts = ['1234', '5678'];

    /**
     * 重启单任务
     * @param $port 端口号
     * @param $taskCommand 需要执行的任务命令
     * @param string $yiiCommand Yii命令
     */
    public function actionRestart($port, $taskCommand, $yiiCommand = './shop')
    {
        if ($this->taskStopped($port)) {
            $taskStatus = $this->taskRestarted($port, $taskCommand, $yiiCommand);
            if ($taskStatus) {
                echo "重启动端口 $port 任务成功！";
            } else {
                echo "重启动端口 $port 任务失败！";
            }
        }
    }

    /**
     * 调用关闭任务
     * @param $port 端口号
     * @return int
     */
    public function taskStopped($port)
    {
        return $this->actionStop($port);
    }

    /**
     * 停止单个任务
     * @param $port 只需传端口号
     * @return int
     */
    public function actionStop($port)
    {
        $port = trim($port);

        if ($port == '') {
            echo '请输入端口号！';
            return 0;
        }

        if (!is_numeric($port)) {
            echo '端口号必须是数字！';
            return 0;
        }

        //只允许关闭$tasksPorts里已设定的端口任务，防止错误关闭其它应用
        if (!$this->taskInSchedulesController($port)) {
            echo '只允许停止SchedulesController $tasksPorts里已设定的端口任务！';
            return 0;
        }

        exec('netstat -lntp', $activeTasks, $status); //获取所有已运行的任务
        $task = $this->getTaskByPort($port, $activeTasks);

        if ($task == null) {
            echo "此端口号 $port 的任务没有运行，无需停止！";
            return 0;
        } else {
            echo "正在关闭端口 $port 任务 ...";
            $pid = $this->getPID($task);

            exec('kill -9 ' . $pid, $result, $taskStatus); //执行任务
            if (!$taskStatus) {
                //print_r($taskStatus);
                echo "关闭端口 $port 任务成功！" . !$taskStatus;
                return !$taskStatus;
            } else {
                echo "关闭端口 $port 任务失败！";
                return !$taskStatus;
            }
        }
    }

    /**
     * 检查是否是当前控制器已设定的端口，防止错误关闭其它应用
     * @param $port 端口号
     * @return bool
     */
    public function taskInSchedulesController($port)
    {
        return in_array($port, $this->tasksPorts);
    }

    /**
     * 按端口号查看任务是否存在
     * @param $port 端口号
     * @param $activeTasks 所有任务列表
     * @return null
     */
    public function getTaskByPort($port, $activeTasks)
    {
        $tempTask = null;

        foreach ($activeTasks as $task) {
            if ($port == $this->getPort($task)) {
                $tempTask = $task;
                return $tempTask;
            }
        }
        return $tempTask;
    }

    /**
     * 获取任务端口
     * @param $task 传入任务完整信息
     * @return string
     */
    public function getPort($task)
    {
        $port1 = substr($task, strpos($task, ':') + 1, strlen($task));
        $port2 = trim(substr($port1, 0, strpos($port1, ' ')));

        return $port2;
    }

    /**
     * 获取任务PID
     * @param $task 传入任务完整信息
     * @return string
     */
    public function getPID($task)
    {
        $pid1 = substr($task, strpos($task, 'LISTEN') + 6, strlen($task));
        $pid2 = trim(substr($pid1, 0, strpos($pid1, '/')));

        return $pid2;
    }

    /**
     * 调用启动任务
     * @param $port 端口号
     * @param $taskCommand 需要执行的任务命令
     * @param string $yiiCommand Yii命令
     * @return int
     */
    public function taskRestarted($port, $taskCommand, $yiiCommand)
    {
        return $this->actionStart($port, $taskCommand, $yiiCommand);
    }

    /**
     * 启动单个任务
     * @param $port 端口号
     * @param $taskCommand 需要执行的任务命令
     * @param string $yiiCommand Yii命令
     * @return int
     */
    public function actionStart($port, $taskCommand, $yiiCommand = './shop')
    {
        $port = trim($port);

        if ($port == '') {
            echo '请输入端口号！';
            return 0;
        }

        if (!is_numeric($port)) {
            echo '端口号必须是数字！';
            return 0;
        }

        if (trim($taskCommand) == '') {
            echo '请输入需要执行的任务！';
            return 0;
        }

        exec('netstat -lntp', $activeTasks, $status); //获取所有已运行的任务
        $task = $this->getTaskByPort($port, $activeTasks);

        if ($task != null) {
            echo "此端口号 $port 的任务已运行中，无需开启！";
            return 0;
        } else {
            echo "正在启动端口 $port 任务 ...";

            exec($yiiCommand . ' ' . $taskCommand, $result, $taskStatus); //执行任务
            if (!$taskStatus) {
                print_r($result);
                echo "启动端口 $port 任务成功！";
                return !$taskStatus;
            } else {
                echo "启动端口 $port 任务失败！";
                return !$taskStatus;
            }
        }
    }

    public function actionTest()
    {
        echo 'test action executed';
    }
}