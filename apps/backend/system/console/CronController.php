<?php
namespace myerm\backend\system\console;

use myerm\backend\system\syscron\SysCron;
use myerm\backend\common\libs\SystemTime;
use myerm\backend\common\libs\File;

/**
 * 计划任务调度控制器
 * ============================================================================
 * 版权所有 2010-2017 三只象（厦门）网络科技有限公司，并保留所有权利。
 * 网站地址: http://www.myerm.cn
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和
 * 使用；不允许对程序代码以任何形式任何目的的再发布。
 * ============================================================================
 *
 * @author 陈鹭明 <lumingchen@qq.com>
 * @since 2016-1-13 10:30
 * @version v2.0
 */
class CronController extends \yii\console\Controller
{
    public function actionRun($lID=0) 
    {
        set_time_limit(0);
        ini_set("memory_limit", "18048M");

        try {
            if ($lID) {
                $cron = SysCron::find()->where(['lID'=>$lID])->asArray()->one();
            } else {
                $cron = SysCron::find()->where("bActive>'0' AND dNextRun<='" . SystemTime::getCurLongDate() . "'")
                    ->orderBy("dNextRun")
                    ->asArray()
                    ->one();
            }

            if ($cron) {
                
                File::createDirectory(\Yii::$app->runtimePath.'/cron');

                $lockfile = \Yii::$app->runtimePath.'/cron/' . $cron['lID'] . '.lock';
                
                if (is_writable($lockfile) && filemtime($lockfile) > time() - 600) {
                    echo "task ID {$cron['lID']} is running \r\n";
                    return self::EXIT_CODE_NORMAL;
                } else {
                    @touch($lockfile);
                }
                
                echo "task ID {$cron['lID']} start at " . SystemTime::getLongDate() . "\r\n";
                
                $cron['minute'] = explode("\t", $cron['sMinute']);
                $this->cronNextRun($cron);
                
                $arrController = \Yii::$app->createController($cron['sRoute']);
                $c = $arrController[0];
                if ($c && method_exists($c, $arrController[1])) {
                    $c->{$arrController[1]}();
                }

                @unlink($lockfile);
                
                echo "task ID {$cron['lID']} end at " . SystemTime::getLongDate(). "\r\n";
            } else {
                echo "none task run at " . SystemTime::getLongDate();
            }
        }
        catch ( \yii\base\UserException $e ) {
            @unlink($lockfile);
            echo $e->getMessage();
        }        
        
        //运行命令
        File::createDirectory(\Yii::$app->runtimePath.'/commands');
        $arrCommandFile = File::findFiles(\Yii::$app->runtimePath.'/commands');
        foreach ($arrCommandFile as $sFile) {
            exec(file_get_contents($sFile), $arrOutput);
            foreach ($arrOutput as $sMsg) {
                echo $sMsg."\r\n";
            }
            
            @unlink($sFile);
        }

        return self::EXIT_CODE_NORMAL;
    }
    
    /**
     * 根据路由的文件获得类名
     *
     * @param string $sTargetPage 路由文件名
     * @return string
     */
    private function getClassName($sTargetPage)
    {
        return substr($sTargetPage, strrpos($sTargetPage, ".") + 1);
    }
    
    private function cronNextRun($cron)
    {
        $timestamp = time();
        if (!$cron) {
            return FALSE;
        }

        if (!is_array($cron['sMinute'])) {
            $cron['minute'] = explode("\t", trim($cron['sMinute']));
        } else {
            $cron['minute'] = $cron['sMinute'];
        }
        
        list ($yearnow, $monthnow, $dayNow, $weekdaynow, $hournow, $minutenow) = explode('-', gmdate('Y-m-d-w-H-i', $timestamp + SystemTime::getTimeOffset() * 3600));
        
        if ($cron['lWeekDay'] == - 1) {
            if ($cron['lDay'] == - 1) {
                $firstday = $dayNow;
                $secondday = $dayNow + 1;
            } else {
                $firstday = $cron['lDay'];
                $secondday = $cron['lDay'] + gmdate('t', $timestamp + SystemTime::getTimeOffset() * 3600);
            }
        } else {
            $firstday = $dayNow + ($cron['lWeekDay'] - $weekdaynow);
            $secondday = $firstday + 7;
        }
        
        if ($firstday < $dayNow) {
            $firstday = $secondday;
        }
        
        if ($firstday == $dayNow) {
            $todaytime = $this->cronTodayNextRun($cron);
            if ($todaytime['lHour'] == - 1 && $todaytime['minute'] == - 1) {
                $cron['lDay'] = $secondday;
                $nexttime = $this->cronTodayNextRun($cron, 0, - 1);
                $cron['lHour'] = $nexttime['lHour'];
                $cron['minute'] = $nexttime['minute'];
            } else {
                $cron['lDay'] = $firstday;
                $cron['lHour'] = $todaytime['lHour'];
                $cron['minute'] = $todaytime['minute'];
            }
        } else {
            $cron['lDay'] = $firstday;
            $nexttime = $this->cronTodayNextRun($cron, 0, - 1);
            $cron['lHour'] = $nexttime['lHour'];
            $cron['minute'] = $nexttime['minute'];
        }
    
        $nextrun = @gmmktime(intval($cron['lHour']), intval($cron['minute']), 0, intval($monthnow), intval($cron['lDay']), intval($yearnow)) - SystemTime::getTimeOffset() * 3600;

        return SysCron::updateAll(['dLastRun'=>SystemTime::getLongDate($timestamp), 'dNextRun'=>SystemTime::getLongDate($nextrun)], ['lID'=>$cron['lID']]);
    }
    
    
    private function cronTodayNextRun($cron, $hour = -2, $minute = -2)
    {
        $timestamp = time();
    
        $hour = $hour == -2 ? gmdate('H', $timestamp + SystemTime::getTimeOffset() * 3600) : $hour;
        $minute = $minute == -2 ? gmdate('i', $timestamp + SystemTime::getTimeOffset() * 3600) : $minute;
        
        $nexttime = array();
        if ($cron['lHour'] == - 1 && !$cron['minute']) {
            $nexttime['lHour'] = $hour;
            $nexttime['minute'] = $minute + 1;
        } elseif ($cron['lHour'] == - 1 && $cron['minute'] != '') {
            $nexttime['lHour'] = $hour;
            if (($nextminute = $this->cronNextMinute($cron['minute'], $minute)) === false) {
                ++ $nexttime['lHour'];
                $nextminute = $cron['minute'][0];
            }
            $nexttime['minute'] = $nextminute;
        } elseif ($cron['lHour'] != - 1 && $cron['minute'] == '') {
            if ($cron['lHour'] < $hour) {
                $nexttime['lHour'] = $nexttime['minute'] = - 1;
            } elseif ($cron['lHour'] == $hour) {
                $nexttime['lHour'] = $cron['lHour'];
                $nexttime['minute'] = $minute + 1;
            } else {
                $nexttime['lHour'] = $cron['lHour'];
                $nexttime['minute'] = 0;
            }
        } elseif ($cron['lHour'] != - 1 && $cron['minute'] != '') {
            $nextminute = $this->cronNextMinute($cron['minute'], $minute);
            if ($cron['lHour'] < $hour || ($cron['lHour'] == $hour && $nextminute === false)) {
                $nexttime['lHour'] = - 1;
                $nexttime['minute'] = - 1;
            } else {
                $nexttime['lHour'] = $cron['lHour'];
                $nexttime['minute'] = $nextminute;
            }
        }
    
        return $nexttime;
    }

    private function cronNextMinute($nextminutes, $minutenow)
    {
        foreach ($nextminutes as $nextminute) {
            if ($nextminute > $minutenow) {
                return $nextminute;
            }
        }
        
        return false;
    }
}