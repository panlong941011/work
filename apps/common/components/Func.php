<?php
/**
 * Created by PhpStorm.
 * User: oyyz <oyyz@3elephant.com>
 * Date: 2017/11/14 0014
 * Time: 上午 10:27
 */

namespace myerm\common\components;


class Func
{
    /**
     * 打印数据
     * @param array $data
     * @param bool $bExit
     * @author oyyz <oyyz@3elephant.com>
     * @time 2017-11-14 11:16:20
     */
    public static function console($data = [], $bExit = true)
    {
        echo '<h1>------ start ------</h1><br>';
        echo '<pre>';
        print_r($data);
        echo '</pre>';
        echo '<h1>------ end ------</h1><br>';

        if ($bExit) {
            exit;
        }
    }

    /**
     * 获取当前时间
     * @return false|string
     * @author oyyz <oyyz@3elephant.com>
     * @time 2017-11-14 11:40:02
     */
    public static function getDate()
    {
        return \Yii::$app->formatter->asDatetime(time());
    }

    /**
     * 保留两位小数点
     * @param $data
     * @return string
     * @author YanZhongOuYang
     * @time: 2017-4-17 08:49:30
     */
    public static function numbleFormat($data, $decimals = '2')
    {
        if ($data) {
            return number_format($data, $decimals, '.', '');
        }
        return 0.00;
    }

    /**
     * 取汉字的第一个字的首字母
     * @param $str
     * @return null|string
     * @author oyyz <oyyz@3elephant.com>
     * @time 2017-12-21 10:48:45
     */
    public static function getFirstCharter($str)
    {
        if (empty($str)) {
            return '';
        }
        $fchar = ord($str{0});
        if ($fchar >= ord('A') && $fchar <= ord('z')) return strtoupper($str{0});
        $s1 = iconv('UTF-8', 'gb2312', $str);
        $s2 = iconv('gb2312', 'UTF-8', $s1);
        $s = $s2 == $str ? $s1 : $str;
        $asc = ord($s{0}) * 256 + ord($s{1}) - 65536;
        if ($asc >= -20319 && $asc <= -20284) return 'A';
        if ($asc >= -20283 && $asc <= -19776) return 'B';
        if ($asc >= -19775 && $asc <= -19219) return 'C';
        if ($asc >= -19218 && $asc <= -18711) return 'D';
        if ($asc >= -18710 && $asc <= -18527) return 'E';
        if ($asc >= -18526 && $asc <= -18240) return 'F';
        if ($asc >= -18239 && $asc <= -17923) return 'G';
        if ($asc >= -17922 && $asc <= -17418) return 'H';
        if ($asc >= -17417 && $asc <= -16475) return 'J';
        if ($asc >= -16474 && $asc <= -16213) return 'K';
        if ($asc >= -16212 && $asc <= -15641) return 'L';
        if ($asc >= -15640 && $asc <= -15166) return 'M';
        if ($asc >= -15165 && $asc <= -14923) return 'N';
        if ($asc >= -14922 && $asc <= -14915) return 'O';
        if ($asc >= -14914 && $asc <= -14631) return 'P';
        if ($asc >= -14630 && $asc <= -14150) return 'Q';
        if ($asc >= -14149 && $asc <= -14091) return 'R';
        if ($asc >= -14090 && $asc <= -13319) return 'S';
        if ($asc >= -13318 && $asc <= -12839) return 'T';
        if ($asc >= -12838 && $asc <= -12557) return 'W';
        if ($asc >= -12556 && $asc <= -11848) return 'X';
        if ($asc >= -11847 && $asc <= -11056) return 'Y';
        if ($asc >= -11055 && $asc <= -10247) return 'Z';
        return null;
    }

    /**
     * 本月开始日期
     * @return false|string
     * @author oyyz <oyyz@3elephant.com>
     * @time 2018-1-2 18:55:30
     */
    public static function monthStart($time = '')
    {
        if ($time) {
            $m = date("m", $time);
            $y = date("Y", $time);
        } else {
            $m = date("m");
            $y = date("Y");
        }
        return date("Y-m-d H:i:s", mktime(0, 0, 0, $m, 1, $y));
    }

    /**
     * 本月结束日期
     * @return false|string
     * @author oyyz <oyyz@3elephant.com>
     * @time 2018-1-2 18:55:39
     */
    public static function monthEnd($time = '')
    {
        if ($time) {
            $m = date("m", $time);
            $y = date("Y", $time);
            $t = date("t", $time);
        } else {
            $m = date("m");
            $y = date("Y");
            $t = date("t");
        }
        return date("Y-m-d H:i:s", mktime(23, 59, 59, $m, $t, $y));
    }

    /**
     * 过滤特殊符号（包括微信标签符号）
     * @author ouyangyz <ouyangyanzhong@163.com>
     * @time 2018-11-7 11:32:35
     */
    public static function replaceSpecialChar($string)
    {
        //过滤微信表情
        $string = preg_replace('/[\x{1F600}-\x{1F64F}]/u', '', $string);
        $string = preg_replace('/[\x{1F300}-\x{1F5FF}]/u', '', $string);
        $string = preg_replace('/[\x{1F680}-\x{1F6FF}]/u', '', $string);
        $string = preg_replace('/[\x{2600}-\x{26FF}]/u', '', $string);
        $string = preg_replace('/[\x{2700}-\x{27BF}]/u', '', $string);
        $string = str_replace(array('"', '\''), '', $string);
        $string = addslashes(trim($string));

        //过滤特殊符号
        $string = str_replace('`', '', $string);
        $string = str_replace('·', '', $string);
        $string = str_replace('~', '', $string);
        $string = str_replace('!', '', $string);
        $string = str_replace('！', '', $string);
        $string = str_replace('@', '', $string);
        $string = str_replace('#', '', $string);
        $string = str_replace('$', '', $string);
        $string = str_replace('￥', '', $string);
        $string = str_replace('%', '', $string);
        $string = str_replace('^', '', $string);
        $string = str_replace('……', '', $string);
        $string = str_replace('&', '', $string);
        $string = str_replace('*', '', $string);
        $string = str_replace('(', '', $string);
        $string = str_replace(')', '', $string);
        $string = str_replace('（', '', $string);
        $string = str_replace('）', '', $string);
        $string = str_replace('-', '', $string);
        $string = str_replace('_', '', $string);
        $string = str_replace('——', '', $string);
        $string = str_replace('+', '', $string);
        $string = str_replace('=', '', $string);
        $string = str_replace('|', '', $string);
        $string = str_replace('\\', '', $string);
        $string = str_replace('[', '', $string);
        $string = str_replace(']', '', $string);
        $string = str_replace('【', '', $string);
        $string = str_replace('】', '', $string);
        $string = str_replace('{', '', $string);
        $string = str_replace('}', '', $string);
        $string = str_replace(';', '', $string);
        $string = str_replace('；', '', $string);
        $string = str_replace(':', '', $string);
        $string = str_replace('：', '', $string);
        $string = str_replace('\'', '', $string);
        $string = str_replace('"', '', $string);
        $string = str_replace('“', '', $string);
        $string = str_replace('”', '', $string);
        $string = str_replace(',', '', $string);
        $string = str_replace('，', '', $string);
        $string = str_replace('<', '', $string);
        $string = str_replace('>', '', $string);
        $string = str_replace('《', '', $string);
        $string = str_replace('》', '', $string);
        $string = str_replace('.', '', $string);
        $string = str_replace('。', '', $string);
        $string = str_replace('/', '', $string);
        $string = str_replace('、', '', $string);
        $string = str_replace('?', '', $string);
        $string = str_replace('？', '', $string);
        return $string;
    }


    /**
     * 指定日期开始时间
     * @param string $time
     * @return false|string
     * @author ouyangyz <ouyangyanzhong@163.com>
     * @time 2019/3/13 13:59
     */
    public static function dayStart($time = '')
    {
        $time = $time ? $time : strtotime(self::getDate());
        return date('Y-m-d 00:00:00', $time);
    }

    /**
     * 指定日期结束时间
     * @param string $time
     * @return false|string
     * @author ouyangyz <ouyangyanzhong@163.com>
     * @time 2019/3/13 13:59
     */
    public static function dayEnd($time = '')
    {
        $time = $time ? $time : strtotime(self::getDate());
        return date('Y-m-d 23:59:59', $time);
    }


    /**
     * 本周开始日期
     * @param $time
     * @param $first $first =1 表示每周星期一为开始日期 0表示每周日为开始日期
     * @return false|string
     * @author YanZhongOuYang
     * @time: 2017-4-17 08:53:08
     */
    public static function weekStart($time = '', $first = 1)
    {
        //当前日期
        if (!$time) $time = time();
        $sdefaultDate = date("Y-m-d", $time);
        //获取当前周的第几天 周日是 0 周一到周六是 1 - 6
        $w = date('w', strtotime($sdefaultDate));
        //获取本周开始日期，如果$w是0，则表示周日，减去 6 天
        $week_start = date('Y-m-d', strtotime("$sdefaultDate -" . ($w ? $w - $first : 6) . ' days'));
        //本周结束日期
        return $week_start;
    }


    /**
     * 本周结束日期
     * @param $time
     * @param $first $first =1 表示每周星期一为开始日期 0表示每周日为开始日期
     * @return false|string
     * @author YanZhongOuYang
     * @time: 2017-4-17 08:53:16
     */
    public static function weekEnd($time = '', $first = 1)
    {
        //当前日期
        if (!$time) $time = time();
        $sdefaultDate = date("Y-m-d", $time);
        //获取当前周的第几天 周日是 0 周一到周六是 1 - 6
        $w = date('w', strtotime($sdefaultDate));
        //获取本周开始日期，如果$w是0，则表示周日，减去 6 天
        $week_start = date('Y-m-d', strtotime("$sdefaultDate -" . ($w ? $w - $first : 6) . ' days'));
        //本周结束日期
        $week_end = date('Y-m-d', strtotime("$week_start +6 days"));
        return $week_end;
    }

    /**
     * 上N周开始时间
     * @param string $time
     * @param int $weeks
     * @return false|string
     * @author ouyangyz <ouyangyanzhong@163.com>
     * @time 2019/3/17 10:23
     */
    public static function lastWeekStart($time = '', $weeks = 1)
    {
        $now = $time ? Func::weekStart($time) : Func::weekStart();
        $date = date("Y-m-d H:i:s", strtotime("-" . $weeks . "weeks", strtotime($now)));
        return $date;
    }

    /**
     * 上N周结束时间时间
     * @param string $time
     * @param int $weeks
     * @return false|string
     * @author ouyangyz <ouyangyanzhong@163.com>
     * @time 2019/3/17 10:25
     */
    public static function lastWeekEnd($time = '', $weeks = 1)
    {
        $now = $time ? Func::weekEnd($time) : Func::weekEnd();
        $date = date("Y-m-d H:i:s", strtotime("-" . $weeks . "weeks", strtotime($now)));
        return $date;
    }

    /**
     * 处理图片地址
     * @param string $url , 图片地址
     * @return string
     */
    public static function handleImagePath($url = '')
    {
        if (strstr($url, 'http://') > -1) {
            return $url;
        }

        return 'http://product.aiyolian.cn/' . $url;
    }

    /**
     * curl传输数据（post形式）
     * @param $url
     * @param $post
     * @return mixed
     * @author hechengcheng
     * @time 2019/5/9 10:05
     */
    public static function sendPost($url, $post)
    {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
        $data = curl_exec($ch);
        return $data;
    }

    /**
     * curl传输json数据（post形式）
     * @param $url
     * @param $json
     * @return mixed
     * @author hechengcheng
     * @time 2019/5/9 10:06
     */
    public static function sendJsonPost($url, $json)
    {
        $data_string = json_encode($json);
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);//$data JSON类型字符串
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json', 'Content-Length: ' . strlen($data_string)));
        $result = curl_exec($ch);
        return $result;
    }

    public static function getNonce($length = 32)
    {
        $str = null;
        $strPol = "ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789abcdefghijklmnopqrstuvwxyz";
        $max = strlen($strPol) - 1;
        for ($i = 0; $i < $length; $i++) {
            $str .= $strPol[rand(0, $max)];//rand($min,$max)生成介于min和max两个数之间的一个随机整数
        }
        return $str;
    }

    public static function arrayToString($arrOrg = [])
    {
        $arrRes = [];
        ksort($arrOrg);
        foreach ($arrOrg as $k => $v) {
            array_push($arrRes, ($k . "=" . $v));
        }
        return implode("&", $arrRes);
    }

    public static function HttpRequest($sUrl = "", $arrOption = [])
    {
        $header[0] = "Content-type: text/xml";
        $ch = curl_init($sUrl);

        $sXmlData = '<xml>';
        foreach ($arrOption as $key => $val) {
            if (is_numeric($val)) {
                $str = "<" . $key . ">" . $val . "</" . $key . ">";
            } else {
                $str = "<" . $key . "><![CDATA[" . $val . "]]></" . $key . ">";
            }
            $sXmlData .= $str;
        }
        $sXmlData .= '</xml>';
        curl_setopt($ch, CURLOPT_URL, $sUrl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $sXmlData);

        $data = curl_exec($ch);
        if (curl_errno($ch)) {
            print curl_error($ch);
        }
        curl_close($ch);
        //微信api返回xml数据，需要转成array格式
        $data = self::xml_array($data);
        return $data;
    }

    public static function xml_array($str)
    {
        //将XML转为array
        //禁止引用外部xml实体
        libxml_disable_entity_loader(true);
        return json_decode(json_encode(simplexml_load_string($str, 'SimpleXMLElement', LIBXML_NOCDATA)), true);
    }

    /**
     * 分账接口专用
     * @param string $sUrl
     * @param array $arrOption
     * @return mixed
     */
    public static function HttpRequestShare($sUrl = "", $arrOption = [])
    {
        $header[0] = "Content-type: text/xml";
        $ch = curl_init($sUrl);

        $sXmlData = '<xml>';
        foreach ($arrOption as $key => $val) {
            if (is_numeric($val)) {
                $str = "<" . $key . ">" . $val . "</" . $key . ">";
            } else {
                $str = "<" . $key . "><![CDATA[" . $val . "]]></" . $key . ">";
            }
            $sXmlData .= $str;
        }
        $sXmlData .= '</xml>';
        curl_setopt($ch, CURLOPT_URL, $sUrl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $sXmlData);
        curl_setopt($ch, CURLOPT_SSLCERTTYPE, 'PEM');
        curl_setopt($ch, CURLOPT_SSLCERT, realpath(\Yii::getAlias("@myerm") . "/../resources/wxpay/apiclient_cert.pem"));
//默认格式为PEM，可以注释
        curl_setopt($ch, CURLOPT_SSLKEYTYPE, 'PEM');
        curl_setopt($ch, CURLOPT_SSLKEY, realpath(\Yii::getAlias("@myerm") . "/../resources/wxpay/apiclient_key.pem"));

        $data = curl_exec($ch);
        if (curl_errno($ch)) {
            print curl_error($ch);
        }
        curl_close($ch);
        //微信api返回xml数据，需要转成array格式
        $data = self::xml_array($data);
        return $data;
    }
}