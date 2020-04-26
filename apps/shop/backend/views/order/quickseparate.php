<style>
    .common_ui_module span:first-child {
        color: red;
    }

    .common_ui_module span {
        float: left;
        margin-right: 5px;
    }

    .submitButton {
        text-align: center;
    }

    .submitButton button {
        width: 160px;
        height: 35px;
        border: 0;
        background: #32C5D2;
        color: #fff;
        cursor: pointer;
        margin-top: 10px;
    }

    .express_type div {
        float: left;
    }

    .express_type input {
        margin: 0 5px 0 10px;
    }

    .orderDeliver {
        table-layout: fixed;
        text-align: center;
    }
</style>

<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
    <h4 class="modal-title">一键拆分订单物流</h4>
</div>

<form name="SeparateForm" class="form-horizontal" onsubmit="return false;">
    <input type="hidden" name="_csrf" value="<?= \Yii::$app->request->getCsrfToken() ?>">
    <!-- 原始订单数据-->
    <div class="modal-body">
        <!-- 提示-->
        <div class="row">
            <div class="col-md-12">
                <div class="alert alert-info">
                    <p>一键拆分订单物流是将相同订单中的商品按对应拆分数量拆分物流</p>
                    <p>不足拆分数量的商品为一个物流</p>
                </div>
            </div>
        </div>
        <table class="table table-bordered table-hover orderDeliver"
                id="OrderDetail">
            <tr role="row">
                <td>商品</td>
                <td>规格</td>
                <td>拆分数量</td>
            </tr>
            <?foreach($productInfo as $key => $product){?>
                <tr>
                    <td>
                        <?= $product['sName']?>
                        <input type="hidden" value="<?= $product['ProductID']?>" name="productInfo[<?= $key?>][ProductID]">
                    </td>
                    <td>
                        <?= $product['sSKU']?>
                        <input type="hidden" value="<?= $product['sSKU']?>" name="productInfo[<?= $key?>][sSKU]">
                    </td>
                    <td><input type="text" class="separateNum" name="productInfo[<?= $key?>][lQuantity]" value="0"></td>
                </tr>
            <?}?>
        </table>
        <?foreach($OrderID as $ID){?>
            <input type="hidden" value="<?= $ID?>" name="OrderID[]">
        <?}?>
    </div>

    <div class="modal-footer">
        <div class="submitButton">
            <button onclick="deliver()">提 交</button>
        </div>
    </div>
</form>
<script>
    /**
     * 提交拆分数量
     * @author hechengcheng
     * @time 2018年7月11日15:22:34
     */
    function deliver() {
        //验证部分
        var bValidate = true;
        $(".separateNum").each(function(){
            var lQuantity = Number($(this).val());
            if(lQuantity == 0){
                error("请输入拆分数量");
                bValidate = false;
                return false;
            }else if(!/(^[1-9]\d*$)/.test(lQuantity)){
                error("请输入有效数字");
                bValidate = false;
                return false;
            }
        });

        if(!bValidate){
            return false;
        }

        //提交部分
        info("正在提交修改，请稍等。。。");
        $.post('/shop/orderlogistics/quickseparate', $(document.SeparateForm).serialize(), function (data) {
            if (data.status) {
                success(data.msg);
                closeModal();
                reload();
            } else {
                error(data.msg);
            }
        }, 'json');
    }
</script>