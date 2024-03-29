<?php

namespace myerm\kuaidi100\models;


/**
 * 快递公司
 * ============================================================================
 * 版权所有 2010-2017 三只象（厦门）网络科技有限公司，并保留所有权利。
 * 网站地址: http://www.myerm.cn
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和
 * 使用；不允许对程序代码以任何形式任何目的的再发布。
 * ============================================================================
 * @author 陈鹭明
 * @since 2017年10月27日 23:48:55
 * @version v2.0
 */
class ExpressCompany extends \myerm\common\models\ExpressCompany
{
    public static function getCompanyCode($ID)
    {
        return static::findOne($ID)->sCompanyCode;
    }
}