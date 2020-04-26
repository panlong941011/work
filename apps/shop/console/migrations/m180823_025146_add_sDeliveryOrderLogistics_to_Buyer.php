<?php

use yii\db\Migration;

/**
 * Class m180823_025146_add_sDeliveryOrderLogistics_to_Buyer
 */
class m180823_025146_add_sDeliveryOrderLogistics_to_Buyer extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function Up()
    {
        $this->addColumn('Buyer','sDeliveryOrderLogistics',$this->string()->comment('订单物流表推送接口'));
    }

    /**
     * {@inheritdoc}
     */
    public function Down()
    {
        $this->dropColumn('Buyer','sDeliveryOrderLogistics');
    }

}
