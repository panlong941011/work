<div class="portlet light bordered">
    <div class="portlet-title">
        <div class="caption">
            <span class="caption-subject font-green-haze bold uppercase">新建 参数模板</span>
            <span class="caption-helper">&nbsp;</span>
        </div>
        <div class="tools">
        </div>
    </div>


    <div class="row margin-top-10">

        <div class="col-md-12">
            <div class="portlet light bordered">
                <div class="form-actions margin-top-10">
                    <div class="row">
                        <div class="col-md-12">
                            <button type="button" class="btn default" onclick="parent.closeCurrTab()">取消</button>
                            <button type="submit" class="btn green" onclick="beforeObjectSubmit()"><i class="fa fa-check"></i> 保存</button>
                        </div>
                        <div class="col-md-6"></div>
                    </div>
                </div>
                <div name="基本信息">
                    <h3 class="form-section">基本信息</h3>
                    <div class="portlet-body form margin-top-10">
                        <form name="objectform" action="/shop/productparamtemplate/newsave" class="horizontal-form"
                              method="post" enctype="multipart/form-data">
                            <input type="hidden" name="_csrf" value="<?= Yii::$app->request->getCsrfToken() ?>">
                            <div class="form-body margin-top-10">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="row">
                                            <div class="form-group " sDataType="Text" sFieldAs="sDefSearchWord">
                                                <label class="control-label col-md-3">商品参数模板<span class="required" aria-required="true">*</span>:</label>
                                                <div class="col-md-9">
                                                    <input type="text" class="form-control" sDataType="Text" sFieldAs="sDefSearchWord" placeholder="不得多于10字" value="<?= $sDefSearchWord ?>" name="sDefSearchWord">
                                                    <p class="help-block font-blue-dark"></p>
                                                </div>
                                            </div>
                                        </div>


                                    </div>
                                    <div class="col-md-4">
                                        <div class="row">
                                        </div>
                                        <div class="row">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="row">
                                        </div>
                                        <div class="row">
                                        </div>
                                    </div>
                                </div>


                                <div class="row margin-top-10">
                                    <div class="col-md-12">
                                        <div class="portlet light bordered">
                                            <div class="portlet-title">
                                                <div class="caption">
                                                    <p class="caption-subject font-green-haze bold uppercase">参数设置</p>
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
                                                                <th>参数<span class="required" aria-required="true">*</span></th>
                                                            </tr>
                                                            </thead>
                                                            <tbody>
                                                            <? foreach ($arrHotWord as $detail) { ?>
                                                                <tr>
                                                                    <td>
                                                                        <i class="fa fa-copy detailCloneRowBtn"
                                                                           title="复制这行数据"></i>
                                                                        <i class="fa fa-minus-square detailDelRowBtn"
                                                                           title="删除这行"></i>
                                                                    </td>
                                                                    <td class="required"
                                                                        sobjectname="Shop/MallHomeProductConfig"
                                                                        sDataType="Text" sFieldAs="sName">
                                                                        <div class="input-group">
                                                                            <input type="text" class="form-control"
                                                                                   name="hotword[sName][]"
                                                                                   value="<?= $detail['sName'] ?>">
                                                                            <p class="help-block font-blue-dark"></p>
                                                                        </div>
                                                                    </td>
                                                                </tr>
                                                            <? } ?>
                                                            <tr id="detailCloneRow" class="hide">
                                                                <td>
                                                                    <i class="fa fa-copy detailCloneRowBtn"
                                                                       title="复制这行数据"></i>
                                                                    <i class="fa fa-minus-square detailDelRowBtn"
                                                                       title="删除这行"></i>
                                                                </td>
                                                                <td class="required"
                                                                    sobjectname="Shop/MallHomeProductConfig"
                                                                    sDataType="Text" sFieldAs="sName">
                                                                    <div class="input-group">
                                                                        <input type="text" class="form-control"
                                                                               placeholder="不得多于10个字"
                                                                               name="hotword[sName][]"
                                                                               value="<?= $product['ProductID'] ?>"
                                                                               sfieldas="sProductDetail">
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


                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="<?= Yii::$app->homeUrl ?>/js/pages/scripts/new.js" type="text/javascript"></script>

    <script>
        function beforeObjectSubmit() {

            clearToastr();

            var bValidate = true;

            $(".form-group").removeClass("has-error");
            $("p.has-error").remove();

            if ($("input[name='sDefSearchWord']").val() == "") {
                $("input[name='sDefSearchWord']").closest(".form-group").addClass("has-error");
                $("input[name='sDefSearchWord']").parent().find(".help-block").after("<p class='help-block has-error'>请输入默认搜索词</p>");
                bValidate = false;
            } else if ($("input[name='sDefSearchWord']").val().length > 10) {
                $("input[name='sDefSearchWord']").closest(".form-group").addClass("has-error");
                $("input[name='sDefSearchWord']").parent().find(".help-block").after("<p class='help-block has-error'>不得多于10字</p>");
                bValidate = false;
            }


            $("td").removeClass("has-error");
            $("tr[id!='detailCloneRow'] td", document.objectform).each
            (
                function () {

                    var sValue = $(this).find("[name='hotword[" + $(this).attr('sFieldAs') + "][]']").val();


//必填项
                    if ($(this).hasClass("required")) {
                        if ($.trim(sValue) == "") {
                            bValidate = false;
                            $(this).addClass("has-error");
                        }
                    }

                    if ($(this).attr('sDataType') == 'Text' && sValue.length > 10) {
                        $(this).addClass("has-error");
                        $(this).find(".help-block").after("<p class='help-block has-error'>不得多于10字</p>");
                        bValidate = false;
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