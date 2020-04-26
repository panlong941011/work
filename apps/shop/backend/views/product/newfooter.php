<!--<div name="商品参数">-->
<!--    <h3 class="form-section">商品参数</h3>-->
<!--    <div class="row">-->
<!--        <div class="col-md-4">-->
<!--            --><? // foreach ($arrParam as $param) { ?>
<!--                <div class="row">-->
<!--                    <div class="form-group" sobjectname="Shop/Product" sdatatype="Text">-->
<!--                        <label class="control-label col-md-3">--><? //= $param->sName ?><!--:</label>-->
<!--                        <div class="col-md-9">-->
<!--                            <input type="text" class="form-control" sdatatype="Text" placeholder=""-->
<!--                                   value="--><? //= $arrParamValue[$param->lID] ? $arrParamValue[$param->lID]->sValue : "" ?><!--"-->
<!--                                   name="paramValue[--><? //= $param->lID ?><!--]">-->
<!--                        </div>-->
<!--                    </div>-->
<!--                </div>-->
<!--            --><? // } ?>
<!--        </div>-->
<!--        <div class="col-md-4">-->
<!--        </div>-->
<!--        <div class="col-md-4">-->
<!--        </div>-->
<!--    </div>-->
<!--</div>-->

<script>
    $("div[sFieldAs='ProductCatID'] button").click
    (
        function (event) {
            //return false;
        }
    )

       // $("div[name='商品参数']").insertAfter($("div[name='基本信息']"));

    function beforeObjectSubmit() {

        var bValidate = true;

        $(".form-group").removeClass("has-error");
        $(".has-error").remove();

        if ($("input[sFieldAs='sName']").val().length > 100) {
            bValidate = false;
            $("input[sFieldAs='sName']").closest(".form-group").addClass("has-error");
        }


        if ($("input[sFieldAs='sRecomm']").val() != "") {
            if ($("input[sFieldAs='sRecomm']").val().length > 100) {
                bValidate = false;
                $("input[sFieldAs='sRecomm']").closest(".form-group").addClass("has-error");
            }
        }

        if ($("input[sFieldAs='fPrice']").val() != "") {
            var fValue = parseFloat($("input[sFieldAs='fPrice']").val());
            console.log(fValue);
            if (fValue < 0.01 && fValue > 9999999) {
                bValidate = false;
                $("input[sFieldAs='fPrice']").closest(".form-group").addClass("has-error");
            }
        }

        if ($("input[sFieldAs='fShowPrice']").val() != "") {
            var fValue = parseFloat($("input[sFieldAs='fShowPrice']").val());
            console.log(fValue);
            if (fValue < 0.01 && fValue > 9999999) {
                bValidate = false;
                $("input[sFieldAs='fShowPrice']").closest(".form-group").addClass("has-error");
            }
        }

        if ($("input[sFieldAs='fCostPrice']").val() != "") {
            var fValue = parseFloat($("input[sFieldAs='fCostPrice']").val());
            console.log(fValue);
            if (fValue < 0.01 && fValue > 9999999) {
                bValidate = false;
                $("input[sFieldAs='fCostPrice']").closest(".form-group").addClass("has-error");
            }
        }


        if (pics.picList.length == 0) {
            bValidate = false;
            $("div[sFieldAs='sPic']").addClass("has-error");
            $("div[sFieldAs='sPic']").find(".help-block").after("<p class='help-block has-error'>请上传商品图片</p>");
            error("请上传商品图片");
        } else {
            $("input[name='newProductPic[]']").remove();//保存前清空商品图片的表单数据，否则可能会造成重复。2018年6月21日 16:54:42，Mars
            for (i in pics.picList) {
                $(document.objectform).append("<input type='hidden' name='newProductPic[]' value='" + pics.picList[i] + "'>");
            }
        }


        if (!bValidate) {

        } else {

            $("input[placeholder='规格名']").each(
                function (i) {
                    var sSpecName = $(this).val();

                    $("input.spec-item", $(this).closest(".form-item")).each(
                        function (i) {
                            var sSpecNameVal = $(this).val();
                            if (sSpecNameVal != "") {
                                $(document.objectform).append("<input type='hidden' name='specConfig[" + sSpecName + "][" + sSpecNameVal + "]' value='" + ($("img", $(this).closest("li")).attr("src") || "") + "'>");
                            }
                        }
                    );
                }
            );

            $(".table-sku tbody tr").each(
                function (i) {
                    var sValue = "";
                    var sComm = "";
                    $("td", this).each(
                        function (k) {
                            if ($(this).find("input").length) {
                                sValue += sComm;
                                sValue += $(this).find("input").val();
                                sComm = ",";
                            }
                        }
                    )

                    $(document.objectform).append("<input type='hidden' name='specVal[]' value='" + sValue + "'>");
                }
            );
        }

        var specItem = $('.required_input');
        var lenth = $('.specItem').length;
        var isInput = true;
        for (var j = 0; j < length; j++) {
            if (specItem.eq(j).val() == '') {
                isInput = false;
            }
        }
        if (!isInput) {
            error("请填写规格部分带*号的信息");
            return false;
        }

        return bValidate;
    }

</script>