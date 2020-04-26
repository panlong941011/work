<?php

use yii\db\Migration;

/**
 * Class m190315_005951_add_fBuyerPrice_to_SalesPromotion
 */
class m190315_005951_add_fBuyerPrice_to_SalesPromotionDetail extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function up()
    {
	    $this->addColumn('SalesPromotionDetail', 'fBuyerPrice', $this->decimal(19,2)->defaultValue(0)->comment('促销渠道价'));
    }

    /**
     * {@inheritdoc}
     */
    public function down()
    {
	    $this->dropColumn('SalesPromotionDetail','fBuyerPrice');
    }
    
}
