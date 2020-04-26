<div name="商品参数">
    <h3 class="form-section">商品参数</h3>
    <div class="row">
        <div class="col-md-4">
            <? foreach ($arrParam as $param) { ?>
                <div class="form-group">
                    <label class="control-label col-md-4 bold"><?= $param->sName ?>:</label>
                    <div class="col-md-8">
                        <p class="form-control-static"><?= $arrParamValue[$param->lID] ? $arrParamValue[$param->lID]->sValue : ""?></p>
                    </div>
                </div>
            <? } ?>
        </div>
    </div>
</div>

<script>
    $("div[name='商品参数']").insertAfter($("div[name='基本信息']"));
</script>