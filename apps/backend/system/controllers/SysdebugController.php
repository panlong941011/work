<?php

namespace myerm\backend\system\controllers;

use myerm\backend\common\controllers\ObjectController;

/**
 * 系统调试日志
 * ============================================================================
 * 版权所有 2010-2017 三只象（厦门）网络科技有限公司，并保留所有权利。
 * 网站地址: http://www.myerm.cn
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和
 * 使用；不允许对程序代码以任何形式任何目的的再发布。
 * ============================================================================
 *
 * @author 陈鹭明 <lumingchen@qq.com>
 * @since 2017年9月30日 00:01:14
 * @version v2.0
 */
class SysdebugController extends ObjectController
{
    public function actionView()
    {
        echo "<script>window.open('/debug/default/view?tag={$_GET['ID']}', '_blank');parent.closeCurrTab()</script>";
    }
}