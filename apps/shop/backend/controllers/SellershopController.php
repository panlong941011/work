<?php

namespace myerm\shop\backend\controllers;


use myerm\backend\common\controllers\ObjectController;
use myerm\backend\common\libs\File;
use myerm\backend\common\libs\NewID;
use myerm\backend\system\models\SysUser;
use myerm\shop\common\models\Supplier;


/**
 * 经销商店铺
 * ============================================================================
 * 版权所有 2010-2017 三只象（厦门）网络科技有限公司，并保留所有权利。
 * 网站地址: http://www.myerm.cn
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和
 * 使用；不允许对程序代码以任何形式任何目的的再发布。
 * ============================================================================
 * @author 陈鹭明  <lumingchen@qq.com>
 * @since 2017年12月19日 09:39:50
 * @version v1.0
 */
class SellershopController extends ObjectController
{
    public function formatListData($arrData)
    {
        foreach ($arrData as $lKey => $data) {
            $arrData[$lKey]['sUrl'] = "<a href='" . \myerm\shop\common\models\MallConfig::getValueByKey('sMallRootUrl') . "/shop".$data['lID']."/home' target='_blank'>" . \myerm\shop\common\models\MallConfig::getValueByKey('sMallRootUrl') . "/shop".$data['lID']."/home</a>";
        }


        return parent::formatListData($arrData); // TODO: Change the autogenerated stub
    }
}