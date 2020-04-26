<?php

namespace myerm\common\components;
use myerm\backend\common\libs\File;

/**
 *  图片类
 * ============================================================================
 * 版权所有 2010-2017 三只象（厦门）网络科技有限公司，并保留所有权利。
 * 网站地址: http://www.myerm.cn
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和
 * 使用；不允许对程序代码以任何形式任何目的的再发布。
 * ============================================================================
 * @author 陈鹭明  <lumingchen@qq.com>
 * @since 2017年9月27日 15:16:05
 * @version v2.0
 */
class Image extends \yii\web\Request
{
    public static function resize($sImageString, $lSize, $sFileName)
    {
        $im = imagecreatefromstring($sImageString);

        $size_src = [imagesx($im), imagesy($im)];
        $w = $size_src[0];
        $h = $size_src[1];

        if ($w > $h) {
            $w = $lSize;
            $h = $h * ($lSize / $size_src['0']);
        } else {
            $h = $lSize;
            $w = $w * ($lSize / $size_src['1']);
        }

        $image = imagecreatetruecolor($w, $h);

        //关键函数，参数（目标资源，源，目标资源的开始坐标x,y, 源资源的开始坐标x,y,目标资源的宽高w,h,源资源的宽高w,h）
        imagecopyresampled($image, $im, 0, 0, 0, 0, $w, $h, $size_src['0'], $size_src['1']);

        imagejpeg($image, $sFileName, 80);
        imagedestroy($im);
    }
}