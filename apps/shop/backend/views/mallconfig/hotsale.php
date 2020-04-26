<div class="breadcrumb" style="display:none">
    <h2>商城设置</h2>
    <h3>热销商品设置</h3>
</div>

<div class="row margin-top-10">

    <div class="col-md-12">
        <div class="note note-info">
            <h4 class="block"><?= Yii::t('app', '提示') ?></h4>
            <p>您可在此设置商城购物车等页面推荐的热销商品</p>
        </div>
    </div>


    <div class="col-md-12">
        <div class="portlet light bordered">
            <div class="form-actions margin-top-10">
                <div class="row">
                    <div class="col-md-12">
                        <button type="button" class="btn default" onclick="parent.closeCurrTab()">取消</button>
                        <button type="submit" class="btn green" onclick="beforeObjectSubmit()"><i
                                    class="fa fa-check"></i> 保存
                        </button>
                    </div>
                    <div class="col-md-6"></div>
                </div>
            </div>
            <div class="portlet-body form margin-top-10">
                <form name="objectform" action="/shop/mallconfig/hotsaleconfig"
                      class="horizontal-form" method="post" enctype="multipart/form-data">
                    <input type="hidden" name="_csrf" value="<?= Yii::$app->request->getCsrfToken() ?>">
                    <div class="form-body margin-top-10">


                        <div class="row margin-top-10">
                            <div class="col-md-12">
                                <div class="portlet light bordered">
                                    <div class="portlet-title">
                                        <div class="caption">
                                            <p class="caption-subject font-green-haze bold uppercase">请添加热销商品</p>
                                            <span class="caption-helper">提示：热销商品不得多于8个</span>
                                        </div>
                                    </div>
                                    <div class="portlet-body" style=" padding-top:0px">
                                        <div class="dataTables_wrapper no-footer">
                                            <div class="table-scrollable">
                                                <table class="table table-striped table-bordered table-hover table-checkable order-column dataTable no-footer table-header-fixed"
                                                       id="detailTable">
                                                    <thead class="flip-content">
                                                    <tr>
                                                        <th width="50"><i id="newDetailRowBtn" class="fa fa-plus-square"
                                                                          title="新增一行"> </i></th>
                                                        <th> 商品名称<span class="required" aria-required="true">*</span>
                                                        </th>
                                                        <th> 排序<span class="required"
                                                                     aria-required="true">*</span><label
                                                                    class="control-label">（序号越小，展示越靠前）</label>
                                                        </th>
                                                    </tr>
                                                    </thead>
                                                    <tbody>
                                                    <? foreach ($arrHotSale as $detail) {
                                                        $product = \myerm\shop\backend\models\Product::findByID($detail['ProductID']);
                                                        ?>
                                                        <tr>
                                                            <td>
                                                                <i class="fa fa-copy detailCloneRowBtn"
                                                                   title="复制这行数据"></i>
                                                                <i class="fa fa-minus-square detailDelRowBtn"
                                                                   title="删除这行"></i>
                                                            </td>
                                                            <td class="required" sobjectname="Shop/MallConfig"
                                                                sDataType="Text"
                                                                sFieldAs="sName">
                                                                <div class="input-group">
                                                                    <input type="text" class="form-control"
                                                                           ondblclick="showDetailRef(this, 'Shop/MallConfig', 'Shop/Product', 'sProductDetail')"
                                                                           placeholder="双击进行选择" sdatatype="ListTable"
                                                                           ignore="true"
                                                                           onchange="$('input[name=\'arrObjectData[Shop/MallConfig][sProductDetail][]\']').val('');$(this).val('')"
                                                                           value="<?= $product->sName ?>"
                                                                           name="arrObjectData[Shop/MallConfig][sProductDetailName][]">
                                                                    <input type="hidden"
                                                                           name="arrObjectData[Shop/MallConfig][sProductDetail][]"
                                                                           value="<?= $product->lID ?>"
                                                                           sfieldas="sProductDetail">
                                                                    <p class="help-block font-blue-dark"></p>
                                                                </div>
                                                            </td>
                                                            <td class="required" sobjectname="Shop/MallConfig"
                                                                sDataType="Int"
                                                                sFieldAs="lPos">
                                                                <input type="text" style="width:50px;"
                                                                       class="form-control"
                                                                       placeholder="" value="<?= $detail['lPos'] ?>"
                                                                       name="arrObjectData[Shop/MallConfig][lPos][]">
                                                                <p class="help-block font-blue-dark"></p>
                                                            </td>
                                                        </tr>
                                                    <? } ?>
                                                    <tr id="detailCloneRow" class="hide">
                                                        <td>
                                                            <i class="fa fa-copy detailCloneRowBtn" title="复制这行数据"></i>
                                                            <i class="fa fa-minus-square detailDelRowBtn"
                                                               title="删除这行"></i>
                                                        </td>
                                                        <td class="required" sobjectname="Shop/MallConfig"
                                                            sDataType="Text"
                                                            sFieldAs="sName">
                                                            <div class="input-group">
                                                                <input type="text" class="form-control"
                                                                       ondblclick="showDetailRef(this, 'Shop/MallConfig', 'Shop/Product', 'sProductDetail')"
                                                                       placeholder="双击进行选择" sdatatype="ListTable"
                                                                       ignore="true"
                                                                       onchange="$('input[name=\'arrObjectData[Shop/MallConfig][sProductDetail][]\']').val('');$(this).val('')"
                                                                       value=""
                                                                       name="arrObjectData[Shop/MallConfig][sProductDetailName][]">
                                                                <input type="hidden"
                                                                       name="arrObjectData[Shop/MallConfig][sProductDetail][]"
                                                                       value="" sfieldas="sProductDetail">
                                                                <p class="help-block font-blue-dark"></p>
                                                            </div>
                                                        </td>
                                                        <td class="required" sobjectname="Shop/MallConfig"
                                                            sDataType="Int"
                                                            sFieldAs="lPos">
                                                            <input type="text" style="width:50px;" class="form-control"
                                                                   sfieldas="lDetailPos"
                                                                   placeholder="" sdatatype="Float" value=""
                                                                   name="arrObjectData[Shop/MallConfig][lPos][]">
                                                            <p class="help-block font-blue-dark"></p>
                                                        </td>
                                                    </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>


                </form>
            </div>
        </div>
    </div>
</div>
<script src="<?= Yii::$app->homeUrl ?>/js/pages/scripts/new.js" type="text/javascript"></script>

<script>
    function beforeObjectSubmit() {

        clearToastr();

        var bValidate = true;

        if ($("#detailTable tr[id!='detailCloneRow']").length > 9) {
            error("热销商品不得多于8个");
            return false;
        }

        $(".form-group").removeClass("has-error");
        $("p.has-error").remove();


        $("td").removeClass("has-error");
        $("tr[id!='detailCloneRow'] td", document.objectform).each
        (
            function () {

                var sValue = $(this).find("input").val();


                //必填项
                if ($(this).hasClass("required")) {
                    if ($.trim(sValue) == "") {
                        bValidate = false;
                        $(this).addClass("has-error");
                    }
                }


                if ($(this).attr('sDataType') == 'Int' && $.trim(sValue) != "") {
                    if (!sValue.match(/^[0-9]{1,}$/)) {
                        $(this).addClass("has-error");
                        $(this).find(".help-block").after("<p class='help-block has-error'>请输入数字</p>");
                        bValidate = false;
                    }
                }
            }
        );

        if (!bValidate) {
            error("请修正表单中红色框的内容。");
            return false;
        }

        $("#detailCloneRow").remove();

        document.objectform.submit();
    }

    <? if ($_GET['save'] == 'yes') { ?>
    $(document).ready(
        function () {
            success('保存成功');
        }
    )

    <? } ?>
</script>