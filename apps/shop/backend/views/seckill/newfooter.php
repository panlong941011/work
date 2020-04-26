<style>
    .well {
        margin-bottom: 0px;
        padding-top: 10px;
    }

    .seckillproduct {
        background-color: white;
        padding: 20px;
    }
</style>
<div class="row">
    <div class="col-md-12">
        <div class="row">

            <label class="control-label col-md-1">秒杀商品<span class="required" aria-required="true">*</span>:</label>
            <div class="col-md-9">


                <div class="well">

                    <? foreach ($arrSecKillProduct as $product) { ?>
                        <div  class="seckillproduct margin-top-10">
                            <div class="row">
                                <div sobjectname="Shop/Product" sdatatype="ListTable"
                                     sfieldas="ProductCatID">
                                    <label class="control-label col-md-1">商品<span class="required"
                                                                                  aria-required="true">*</span>:</label>
                                    <div class="col-md-5">
                                        <div class="input-group">
                                            <input type="text" class="form-control" sdatatype="ListTable" ignore="true"
                                                   placeholder="" value="<?=$product->product->sName?>"
                                                   name="ProductIDName[]"
                                                   readonly="readonly">
                                            <input type="hidden" name="ProductID[]" ignore="true"
                                                   value="<?=$product->ProductID?>"
                                                   sfieldas="ProductID">
                                            <span class="input-group-btn">
                                                <button type="button" class="btn green" sdatatype="ListTable"
                                                        onclick="showRef($(this).closest('.input-group'))">选择</button>
                                            </span>

                                            <span class="input-group-btn">
                                                <button type="button" class="btn red" sdatatype="ListTable"
                                                        onclick="confirmation(this, '<?= Yii::t('app', '确定要删除？') ?>', function(obj){removeProduct('<?=$product->lID?>');$(obj).closest('.seckillproduct').remove()})"
                                                        >删除</button>
                                            </span>

                                        </div>
                                        <p class="help-block" style="color: red"></p>
                                    </div>
                                </div>
                            </div>

                            <div class="row" style="margin: 1px">
                                <div class="table-scrollable">
                                    <table class="table table-bordered table-hover">
                                        <thead>
                                        <tr>
                                            <th> 参与活动的规格<span class="required">*</span></th>
                                            <th> 秒杀价格<span class="required">*</span></th>
                                            <th> 活动库存<span class="required">*</span></th>
                                            <th> 操作</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <? foreach ($product->arrSku as $sku) { ?>
                                        <tr spec='<?=$sku->sName?>'>
                                            <td><?=$sku->sName?></td>
                                            <td><input name='seckill[<?=$product->ProductID?>][<?=$sku->sName?>][price]' value="<?=$sku->fPrice?>"></td>
                                            <td><input name='seckill[<?=$product->ProductID?>][<?=$sku->sName?>][stock]' value="<?=$sku->lStock?>"></td>
                                            <td><a href='javascript:;' onclick="removeSku('<?=$sku->lID?>');$(this).closest('tr').remove()">删除</a></td>
                                        </tr>
                                        <? } ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="row" style="margin: 1px">
                                <a href="javascript:;" onclick="selectSpec(this)">+ 添加参与活动的规格</a>
                            </div>


                        </div>
                    <? } ?>

                    <div id="clone" class="seckillproduct margin-top-10" style="display: none">
                        <div class="row">
                            <div sobjectname="Shop/Product" sdatatype="ListTable"
                                 sfieldas="ProductCatID">
                                <label class="control-label col-md-1">商品<span class="required"
                                                                              aria-required="true">*</span>:</label>
                                <div class="col-md-5">
                                    <div class="input-group">
                                        <input type="text" class="form-control" sdatatype="ListTable" ignore="true"
                                               placeholder="" value=""
                                               name="ProductIDName[]"
                                               readonly="readonly">
                                        <input type="hidden" name="ProductID[]" ignore="true"
                                               value=""
                                               sfieldas="ProductID">
                                        <span class="input-group-btn">
                                                <button type="button" class="btn green" sdatatype="ListTable"
                                                        onclick="showRef($(this).closest('.input-group'))">选择</button>
                                            </span>

                                        <span class="input-group-btn">
                                                <button type="button" class="btn red" sdatatype="ListTable"
                                                        onclick="$(this).closest('.seckillproduct').remove()">删除</button>
                                            </span>

                                    </div>
                                    <p class="help-block" style="color: red"></p>
                                </div>
                            </div>
                        </div>

                        <div class="row" style="margin: 1px">
                            <div class="table-scrollable">
                                <table class="table table-bordered table-hover">
                                    <thead>
                                    <tr>
                                        <th> 参与活动的规格<span class="required">*</span></th>
                                        <th> 秒杀价格<span class="required">*</span></th>
                                        <th> 活动库存<span class="required">*</span></th>
                                        <th> 操作</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="row" style="margin: 1px">
                            <a href="javascript:;" onclick="selectSpec(this)">+ 添加参与活动的规格</a>
                        </div>


                    </div>


                    <div class="row margin-top-10">

                        <label class="control-label col-md-1">
                            <button type="button" class="btn btn-success" onclick="addProduct(this)">
                                添加商品
                            </button>
                        </label>
                        <div class="col-md-5" style="margin-left: 10px; margin-top: 5px">
                            <p class="help-block">最多添加10个商品</p>
                        </div>
                    </div>

                </div>

            </div>

        </div>
    </div>
</div>

<script>

    function addProduct(obj) {

        if ($(".seckillproduct[id!='clone']").size() == 10) {
            error("最多只能添加10个商品");
            return false;
        }

        $("#clone").clone().attr("id", "").show().insertBefore($(obj).closest(".row"));


        return false;
    }

    function removeSku(SkuID) {
        $.get(
            '/shop/seckill/removesku?SkuID='+SkuID,
            function (data) {
                
            }
        )
    }

    function removeProduct(ProductID) {
        $.get(
            '/shop/seckill/removeproduct?ProductID='+ProductID,
            function (data) {

            }
        )
    }

    function refSave(sObjectName, sFieldAs, data) {
        var refobject = $("body").prop("refobject");

        $("input[name='ProductID[]']", $(refobject)).val(data.ID);
        $("input[name='ProductIDName[]']", $(refobject)).val(data.sName);

        $(refobject).closest(".seckillproduct").find(".table-scrollable tbody").html('');

        var ProductID = data.ID;
        $.get
        (
            '/shop/seckill/selectdefaultspec?ProductID=' + ProductID,
            function (data) {
                if (data.status) {
                    var tbody = $(refobject).closest(".seckillproduct").find(".table-scrollable tbody");
                    var sHTML = "<tr spec='默认规格'>";
                    sHTML += "<td>默认规格</td>";
                    sHTML += "<td><input class='form-control' name='seckill[" + ProductID + "][默认规格][price]'></td>";
                    sHTML += "<td><input class='form-control' name='seckill[" + ProductID + "][默认规格][stock]'></td>";
                    sHTML += "<td></td>";
                    sHTML += "</tr>";

                    $(tbody).append(sHTML);
                }
            }
        );


        $("body").prop("refobject", null);
    }

    function showRef(obj) {
        $("body").prop("refobject", obj);

        if ($("input[sFieldAs='dStartDate']").val() == "") {
            error("请选择开始时间");
            return false;
        }

        if ($("input[sFieldAs='dEndDate']").val() == "") {
            error("请选择结束时间");
            return false;
        }


        info('正在弹出窗口。。。。。');
        $.post
        (
            '/shop/product/showref?sFieldAs=SecKillProductID/'+$("input[sFieldAs='dStartDate']").val()+'/'+$("input[sFieldAs='dEndDate']").val()+'&sObjectName=Shop/Product',
            {},
            function (data) {
                var modal = openModal(data, $(window).width() * 0.8, $(window).height() * 0.8);
            }
        );
    }

    function selectSpec(obj) {
        var ProductID = $(obj).closest(".seckillproduct").find("input[name='ProductID[]']").val();

        if (ProductID == "") {
            error("请选择商品");
            return false;
        }

        $("body").prop("refobject", obj);

        $.get
        (
            '/shop/seckill/selectspec?ProductID=' + ProductID,
            function (data) {
                var modal = openModal(data, 700, 500);
            }
        );
    }

    function selectedSpec(input) {

        var refobject = $("body").prop("refobject");

        var ProductID = $(refobject).closest(".seckillproduct").find("input[name='ProductID[]']").val();
        var tbody = $(refobject).closest(".seckillproduct").find(".table-scrollable tbody");

        if ($("tr[spec='" + $(input).val() + "']", $(refobject).closest(".seckillproduct")).size() == 1) {
            return false;
        }

        var sHTML = "<tr spec='" + $(input).val() + "'>";
        sHTML += "<td>" + $(input).val() + "</td>";
        sHTML += "<td><input name='seckill[" + ProductID + "][" + $(input).val() + "][price]'></td>";
        sHTML += "<td><input name='seckill[" + ProductID + "][" + $(input).val() + "][stock]'></td>";
        sHTML += "<td><a href='javascript:;' onclick=\"$(this).closest('tr').remove()\">删除</a></td>";
        sHTML += "</tr>";

        $(tbody).append(sHTML);
    }

    function beforeObjectSubmit() {
        var bValidate = true;

        if ($("input[name='ProductID[]']").size() == 1) {
            error("请添加秒杀商品");
            return false;
        }

        $("input[name='ProductID[]']").each(
            function () {
                if ($(this).closest(".seckillproduct").attr('id') != 'clone') {
                    if ($(this).val() == "") {
                        $(this).closest(".input-group").addClass("has-error");
                        $(this).closest(".input-group").parent().find(".help-block").html("不能为空");
                        bValidate = false;
                    } else {
                        $(this).closest(".input-group").parent().find(".help-block").html("");
                    }
                }
            }
        );

        if (!bValidate) {
            error("秒杀商品是必填的。");
            return false;
        }

        $(".seckillproduct[id!='clone']").each(
            function () {
                if ($(".table-scrollable tbody tr", this).size() == 0) {
                    bValidate = false;
                }
            }
        );

        if (!bValidate) {
            error("请添加参与活动的规格");
            return false;
        }


        $(".seckillproduct[id!='clone'] input").each(
            function () {

                if ($(this).val() == "") {
                    bValidate = false;
                }
            }
        );

        if (!bValidate) {
            error("秒杀价格和活动库存是必填的。");
            return false;
        }

        return bValidate;
    }

    
</script>