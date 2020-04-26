<a href="javascript:;" class="btn green btn-sm" onclick="this.listtable.export()"> <?= Yii::t('app', '导出') ?> </a>

<a href="javascript:;" class="btn green btn-sm" onclick="this.listtable.refresh()"> <?= Yii::t('app', '刷新') ?> </a>

<a href="javascript:;" class="btn green btn-sm" onclick="OrderRefund(this.listtable)"> <?= Yii::t('app', '申请退款') ?> </a>

<script>
    function OrderRefund(listtable)
    {
        var lLength = listtable.getSelectedLength();

        if (lLength == 0) {
            error("请选择一条记录。");
            return;
        }
        if(lLength > 1 || lLength == -1){
            error("只能选择一条记录。");
            return;
        }

        var postData = listtable.getPostData();

        parent.addTab('订单退款', '/shop/wholesaleorder/refund?ID='+postData.sSelectedID)
    }
</script>