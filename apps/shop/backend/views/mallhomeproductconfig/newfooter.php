<div class="row margin-top-10">
    <div class="col-md-12">
        <div class="portlet light bordered">
            <div class="portlet-title">
                <div class="caption">
                    <p class="caption-subject font-green-haze bold uppercase">添加商品</p>
                </div>
            </div>
            <div class="portlet-body" style=" padding-top:0px">
                <div class="dataTables_wrapper no-footer">
                    <div class="table-scrollable">
                        <table class="table table-striped table-bordered table-hover table-checkable order-column dataTable no-footer table-header-fixed"
                               id="detailTable">
                            <thead class="flip-content">
                            <tr>
                                <th width="50"><i id="newDetailRowBtn" class="fa fa-plus-square" title="新增一行"> </i></th>
                                <th> 商品名称<span class="required" aria-required="true">*</span></th>
                                <th> 排序<span class="required" aria-required="true">*</span><label class="control-label">（序号越小，在首页展示越靠前）</label>
                                </th>
                                <th> 商品图片 <label class="control-label">（只有展示方式选择图片式时才要上传商品图片，建议尺寸：750的宽，不限高）</label>
                                </th>
                            </tr>
                            </thead>
                            <tbody>
                            <? foreach ($arrDetail as $detail) { ?>
                                <tr>
                                    <td>
                                        <i class="fa fa-copy detailCloneRowBtn" title="复制这行数据"></i>
                                        <i class="fa fa-minus-square detailDelRowBtn" title="删除这行"></i>
                                    </td>
                                    <td class="required" sobjectname="Shop/MallHomeProductConfig" sDataType="ListTable"
                                        sFieldAs="sProductDetail">
                                        <div class="input-group">
                                            <input type="text" class="form-control"
                                                   ondblclick="showDetailRef(this, 'Shop/MallHomeProductConfig', 'Shop/Product', 'sProductDetail')"
                                                   placeholder="双击进行选择" sdatatype="ListTable" ignore="true"
                                                   onchange="$('input[name=\'arrObjectData[Shop/MallHomeProductConfig][sProductDetail][]\']').val('');$(this).val('')"
                                                   value="<?= $detail['product']->sName ?>"
                                                   name="arrObjectData[Shop/MallHomeProductConfig][sProductDetailName][]">
                                            <input type="hidden"
                                                   name="arrObjectData[Shop/MallHomeProductConfig][sProductDetail][]"
                                                   value="<?= $detail['product']->lID ?>" sfieldas="sProductDetail">
                                            <p class="help-block font-blue-dark"></p>
                                        </div>
                                    </td>
                                    <td class="required" sobjectname="Shop/MallHomeProductConfig" sDataType="Int"
                                        sFieldAs="lDetailPos">
                                        <input type="text" style="width:50px;" class="form-control" sfieldas="fPrice"
                                               placeholder="" sdatatype="Float" value="<?= $detail['lPos'] ?>"
                                               name="arrObjectData[Shop/MallHomeProductConfig][lDetailPos][]">
                                        <p class="help-block font-blue-dark"></p>
                                    </td>
                                    <td sobjectname="Shop/MallHomeProductConfig" sDataType="AttachFile"
                                        sFieldAs="sLogo">
                                        <div class="input-group">
                                            <input type="text" class="form-control" sFieldAs="sLogo"
                                                   sDataType="AttachFile"
                                                   ondblclick="showDetailUpload(this, 'Shop/MallHomeProductConfig', 'sLogo', 'sLogoPath')"
                                                   onchange="$('input[name=\'arrObjectData[Shop/MallHomeProductConfig][sLogoPath]\']').val('')"
                                                   placeholder="<?= Yii::t('app', '双击上传附件') ?>"
                                                   value="<?= $detail['sPic'] ?>"
                                                   name="arrObjectData[Shop/MallHomeProductConfig][sLogo][]">
                                            <input type="hidden" sFieldAs="sLogoPath"
                                                   name="arrObjectData[Shop/MallHomeProductConfig][sLogoPath][]"
                                                   value="<?= $detail['sPicPath'] ?>"/>
                                            <p class="help-block font-blue-dark"></p>
                                        </div>
                                    </td>
                                </tr>
                            <? } ?>
                            <tr id="detailCloneRow" class="hide">
                                <td>
                                    <i class="fa fa-copy detailCloneRowBtn" title="复制这行数据"></i>
                                    <i class="fa fa-minus-square detailDelRowBtn" title="删除这行"></i>
                                </td>
                                <td class="required" sobjectname="Shop/MallHomeProductConfig" sDataType="ListTable"
                                    sFieldAs="sProductDetail">
                                    <div class="input-group">
                                        <input type="text" class="form-control"
                                               ondblclick="showDetailRef(this, 'Shop/MallHomeProductConfig', 'Shop/Product', 'sProductDetail')"
                                               placeholder="双击进行选择" sdatatype="ListTable" ignore="true"
                                               onchange="$('input[name=\'arrObjectData[Shop/MallHomeProductConfig][sProductDetail][]\']').val('');$(this).val('')"
                                               value="<?= $product['sProductName'] ?>"
                                               name="arrObjectData[Shop/MallHomeProductConfig][sProductDetailName][]">
                                        <input type="hidden"
                                               name="arrObjectData[Shop/MallHomeProductConfig][sProductDetail][]"
                                               value="<?= $product['ProductID'] ?>" sfieldas="sProductDetail">
                                        <p class="help-block font-blue-dark"></p>
                                    </div>
                                </td>
                                <td class="required" sobjectname="Shop/MallHomeProductConfig" sDataType="Int"
                                    sFieldAs="lDetailPos">
                                    <input type="text" style="width:50px;" class="form-control" sfieldas="lDetailPos"
                                           placeholder="" sdatatype="Float" value=""
                                           name="arrObjectData[Shop/MallHomeProductConfig][lDetailPos][]">
                                    <p class="help-block font-blue-dark"></p>
                                </td>
                                <td sobjectname="Shop/MallHomeProductConfig" sDataType="AttachFile" sFieldAs="sLogo">
                                    <div class="input-group">
                                        <input type="text" class="form-control" sFieldAs="sLogo" sDataType="AttachFile"
                                               ondblclick="showDetailUpload(this, 'Shop/MallHomeProductConfig', 'sLogo', 'sLogoPath')"
                                               onchange="$('input[name=\'arrObjectData[Shop/MallHomeProductConfig][sLogoPath]\']').val('')"
                                               placeholder="<?= Yii::t('app', '双击上传附件') ?>" value="<?= $data ?>"
                                               name="arrObjectData[Shop/MallHomeProductConfig][sLogo][]">
                                        <input type="hidden" sFieldAs="sLogoPath"
                                               name="arrObjectData[Shop/MallHomeProductConfig][sLogoPath][]"
                                               value="<?= $sLinkFieldValue ?>"/>
                                        <p class="help-block font-blue-dark"></p>
                                    </div>
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

<script>
    function beforeObjectSubmit() {
        var bSuccess = true;

        if ($("select[name='arrObjectData[Shop/MallHomeProductConfig][ShowID]']").val() == 'pic') {

            $("tr[id!='detailCloneRow'] td[sFieldAs='sLogo'] input[sFieldAs='sLogo']", document.objectform).each
            (
                function () {
                    $(this).closest('td').find("p.has-error").remove();
                    $(this).closest('td').removeClass("has-error");
                    if ($(this).val() == "") {
                        $(this).closest('td').addClass("has-error");
                        $(this).closest('td').find(".help-block").after("<p class='help-block has-error'>请上传图片</p>");
                        bSuccess = false;
                    }
                }
            );

        }

        if($("tr[id!='detailCloneRow'] td[sFieldAs='sLogo'] input[sFieldAs='sLogo']", document.objectform).length == 0) {
            error("请添加商品");
            bSuccess = false;
        } else if (!bSuccess) {
            error("请修正表单中红色框的内容。");
        }

        return bSuccess;
    }
</script>