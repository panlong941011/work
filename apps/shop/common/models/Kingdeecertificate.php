<?php

namespace myerm\shop\common\models;

use myerm\backend\system\models\SysField;
use myerm\common\components\CommonEvent;
use myerm\shop\common\models\Product;
use myerm\shop\common\models\ProductSKU;
use myerm\shop\common\models\Buyer;
use myerm\shop\mobile\models\OrderAddress;
use myerm\shop\common\models\DealFlow;

/**
 * 订单类
 * ============================================================================
 * 版权所有 2010-2017 三只象（厦门）网络科技有限公司，并保留所有权利。
 * 网站地址: http://www.myerm.cn
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和
 * 使用；不允许对程序代码以任何形式任何目的的再发布。
 * ============================================================================
 * @author 陈鹭明
 * @time 2017年10月22日 23:07:33
 * @version v1.0
 */
class Kingdeecertificate extends ShopModel
{
    public static function tableName()
    {
        return 'Kingdeecertificate';
    }
}