<style>
    .common_ui_module {
        width: 100%;
        float: left;
        margin-top: 4px 0;
    }

    .common_ui_label {
        float: left;
        margin: 0 10px 0 0;
        text-align: center;
        line-height: 35px;
    }


    .common_ui_input {
        width: 450px;
        height: 70px;
        border: 1px solid #D8D8D8;
        padding-left: 5px;
    }

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

    .express_type {
        margin: 8px 0 0 0;
    }

    .express_type div {
        float: left;
    }

    .express_type input {
        margin: 0 5px 0 10px;
    }

    #orderDeliver td {

    }


</style>
<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
    <h4 class="modal-title">审核</h4>
</div>

<form name="checkform" class="form-horizontal" onsubmit="return false;">
    <input type="hidden" name="_csrf" value="<?= \Yii::$app->request->getCsrfToken() ?>">
    <input type="hidden" name="lID" value="<?=$_GET['ID']?>">
    <div class="modal-footer">
        <div class="common_ui_module">
            <label class="common_ui_label">是否审核：</label>
            <div class="express_type">
                <div>
                    <input type="radio"
                           name="bCheck"
                           value="1"
                           checked
                    />是
                    <input type="radio"
                           name="bCheck"
                           value="0"
                    />否
                </div>
            </div>
        </div>
        <div class="common_ui_module">
            <div class="express_type" style="text-align: left">
                备注： <input type="text" name="sRemark" class="common_ui_input" value="">
            </div>
        </div>
        <div class="submitButton">
                <button onclick="deliver()">提 交</button>
        </div>
    </div>
</form>

<script>
    function deliver() {
        //提交部分
        info("正在提交修改，请稍等。。。");
        $.post('<?=Yii::$app->homeUrl?>/<?=strtolower($this->context->sObjectName)?>/check', $(document.checkform).serialize(), function (data) {
            console.log(data);
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