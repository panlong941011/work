<?php

namespace myerm\common\components;

/**
 *  继承Yii核心的Request类
 * ============================================================================
 * 版权所有 2010-2017 三只象（厦门）网络科技有限公司，并保留所有权利。
 * 网站地址: http://www.myerm.cn
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和
 * 使用；不允许对程序代码以任何形式任何目的的再发布。
 * ============================================================================
 * @author 陈鹭明  <lumingchen@qq.com>
 * @since 2017年9月4日 23:24:12
 * @version v2.0
 */
class Request extends \yii\web\Request
{
    /**
     * 修正在CDN或者负载均衡下无法获取真实IP
     * @return null|string
     */
    public function getUserIP()
    {
        $sIP = array_key_exists('HTTP_X_FORWARDED_FOR',
            $_SERVER) ? $_SERVER['HTTP_X_FORWARDED_FOR'] : parent::getUserIP();

        try {
            if (strstr($sIP, ',')) {
                $sIP = explode(',', $sIP)[0];
            }
        } catch (\Exception $e) {
            $sIP = parent::getUserIP();
        }

        return $sIP;
    }
}