<?php

namespace myerm\backend\common\libs;

/**
 * 公共函数库-文件助手类
 * ============================================================================
 * 版权所有 2010-2017 三只象（厦门）网络科技有限公司，并保留所有权利。
 * 网站地址: http://www.myerm.cn
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和
 * 使用；不允许对程序代码以任何形式任何目的的再发布。
 * ============================================================================
 * @author 陈鹭明  <lumingchen@qq.com>
 * @since 2015-11-2 14:31
 * @version v2.0
 */
class File extends \myerm\common\libs\File
{
    /**
     * 通过$sRelatePath，系统给出文件的内容
     * @param $sRelatePath 相对路径
     */
    public static function getContent($sRelatePath)
    {
        return file_get_contents(\Yii::$app->params['sUploadDir'] . "/" . $sRelatePath);
    }


}
