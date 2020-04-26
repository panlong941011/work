<?php

namespace myerm\shop\common\models;

use myerm\kuaidi100\models\ExpressCompany;
use myerm\shop\common\models\Returns;

/**
 * 退货信息
 * ============================================================================
 * 版权所有 2010-2017 三只象（厦门）网络科技有限公司，并保留所有权利。
 * 网站地址: http://www.myerm.cn
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和
 * 使用；不允许对程序代码以任何形式任何目的的再发布。
 * ============================================================================
 * @author Mars
 * @time 2019年4月26日09:25:54
 * @version v1.1
 */
class RefundReturn extends ShopModel
{

    public function getShipCompany()
    {
        return $this->hasOne(ExpressCompany::className(), ['sCompanyCode' => 'ShipCompanyID']);
    }

    public function getRefund()
    {
        return $this->hasOne(\myerm\shop\mobile\models\Refund::className(), ['lID' => 'RefundID']);
    }

    /**
     * 确认收货
     * @param $ID 退货ID
     * @author Mars
     * @time 2019年4月26日10:03:41
     */
    public function receive()
    {
        $this->StatusID = Returns::RECEIVED;
        $this->save();
    }

    /**
     * 拒绝收货
     * @param $ID 退货ID
     * @author Mars
     * @time 2019年4月28日09:49:16
     */
    public function denyReceive()
    {
        $this->StatusID = Returns::REFUSE;
        $this->save();
    }
}