<div class="breadcrumb" style="display:none">
    <h2>商城设置</h2>
    <h3>首页设置</h3>
</div>

<style>
    .dropzone-file-area {
        border: 2px dashed red;
        margin: 0 auto;
        padding: 0px;
        text-align: center;
        color: red;
    }
</style>

<div class="row margin-top-10">
    <div class="col-md-12">
        <div class="portlet light bordered">
            <div class="form-actions margin-top-10">
                <div class="row">
                    <div class="col-md-12">
                        <button type="button" class="btn default" onclick="parent.closeCurrTab()">取消</button>
                        <button type="submit" class="btn green" onclick="objectSubmit()"><i class="fa fa-check"></i> 保存
                        </button>
                    </div>
                    <div class="col-md-6"></div>
                </div>
            </div>
            <div class="portlet-body form margin-top-10">

                <form name="objectform" action="/shop/mallconfig/homeconfig"
                      class="horizontal-form" method="post" enctype="multipart/form-data">
                    <input type="hidden" name="_csrf" value="<?= Yii::$app->request->getCsrfToken() ?>">
                    <input type="hidden" name="currtab" value="">
                    <div class="tabbable-line tabbable tabbable-tabdrop">
                        <ul class="nav nav-tabs">
                            <li <?=$_GET['currtab'] == 'tab_scrollingimage' || !$_GET['currtab'] ? "class=\"active\"" : ""?>>
                                <a href="#tab_scrollingimage" data-id="" data-toggle="tab" data-slinkurl="">轮播图</a>
                            </li>
                            <li <?=$_GET['currtab'] == 'tab_shortcut' ? "class=\"active\"" : ""?>>
                                <a href="#tab_shortcut" data-id="" data-toggle="tab" data-slinkurl="">快捷菜单</a>
                            </li>
                            <li <?=$_GET['currtab'] == 'tab_window' ? "class=\"active\"" : ""?>>
                                <a href="#tab_window" data-id="" data-toggle="tab" data-slinkurl="">橱窗</a>
                            </li>
                        </ul>
                        <div class="tab-content">

                            <div class="tab-pane <?=$_GET['currtab'] == 'tab_scrollingimage' || !$_GET['currtab'] ? "active" : ""?>"
                                 id="tab_scrollingimage">
                                <div class="form-body margin-top-10">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="row">
                                                <div class="form-group " sDataType="Text"
                                                     sFieldAs="sMallName">
                                                    <label class="control-label col-md-1">滚动间隔</label>
                                                    <div class="col-md-11">
                                                        <select class="form-control" style="width: 100px"
                                                                name="homeConfig[scrollimage][lScrollSpeed]">
                                                            <option value="3">3s</option>
                                                            <option value="5" <?= $arrScrollImage['lScrollSpeed'] == 5 ? "selected" : "" ?>>
                                                                5s
                                                            </option>
                                                        </select>
                                                        <p class="help-block font-blue-dark">
                                                            即商城首页轮播图滚动的速度；</p>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="form-group " sDataType="Text"
                                                     sFieldAs="sMallDesc">
                                                    <label class="control-label col-md-1">轮播图<span class="required"
                                                                                                   aria-required="true">*</span>:</label>
                                                    <div class="col-md-11">

                                                        <? foreach ($arrScrollImage['arrPic'] as $scrollImage) { ?>
                                                            <div class="row margin-top-10 ScrollingImage">
                                                                <div class="col-md-3">
                                                                    <img id="imageuploader"
                                                                         src="/<?= $scrollImage['sPic'] ? $scrollImage['sPic'] : "js/pages/img/u81.png" ?>"
                                                                         width="237" height="103"><br>
                                                                    <button id="btn-del" type="button"
                                                                            class="btn btn-xs">
                                                                        删除
                                                                    </button>
                                                                    <button id="btn-replace" type="button"
                                                                            class="btn btn-xs">替换图片
                                                                    </button>
                                                                    <input type="file" class="upFile hide"
                                                                           accept="image/gif,image/jpeg,image/png">
                                                                    <input type="hidden" class="form-control" id="sPic"
                                                                           placeholder=""
                                                                           value="<?= $scrollImage['sPic'] ?>"
                                                                           name="homeConfig[scrollimage][sPic][]"
                                                                    >

                                                                </div>
                                                                <div class="col-md-8">
                                                                    <div class="row">
                                                                        <label class="control-label col-md-2">排序：</label>
                                                                        <div class="col-md-10">
                                                                            <input type="text" class="form-control"
                                                                                   sDataType="Text" sFieldAs="sName"
                                                                                   style="width: 100px;"
                                                                                   placeholder=""
                                                                                   value="<?= $scrollImage['lPos'] ?>"
                                                                                   name="homeConfig[scrollimage][lPos][]"
                                                                            >
                                                                            <p class="help-block font-blue-dark"></p>
                                                                        </div>
                                                                    </div>
                                                                    <div class="row">
                                                                        <label class="control-label col-md-2">链接：</label>
                                                                        <div class="col-md-10">
                                                                            <input type="text" class="form-control"
                                                                                   sDataType="Text" sFieldAs="sName"
                                                                                   placeholder=""
                                                                                   value="<?= $scrollImage['sLink'] ?>"
                                                                                   name="homeConfig[scrollimage][sLink][]"
                                                                            >
                                                                            <p class="help-block font-blue-dark"></p>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        <? } ?>


                                                        <div class="row margin-top-10" id="addScrollingImageBtn"
                                                        >
                                                            <div class="col-md-3">
                                                                <div class="dropzone dropzone-file-area dz-clickable  <?= count($arrScrollImage['arrPic']) == 5 ? 'hide' : '' ?>"
                                                                     id="my-dropzone" style=""
                                                                     onclick="addScrollingImage($(this).closest('#addScrollingImageBtn'))">
                                                                    <span style="font-size: 48px;font-weight: 700;">+</span>
                                                                    <p>点击可添加轮播图<br>建议尺寸:750X348</p>
                                                                </div>
                                                                <p class="help-block">建议：轮播图不得多于5张</p>
                                                            </div>
                                                        </div>

                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane <?=$_GET['currtab'] == 'tab_shortcut' ? "active" : ""?>" id="tab_shortcut">

                                <div class="form-body margin-top-10">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="row">
                                                <div class="form-group " sDataType="Text"
                                                     sFieldAs="sMallName">
                                                    <label class="control-label col-md-1">是否启用</label>
                                                    <div class="col-md-11">
                                                        <input type="checkbox"
                                                               value="1" <?= $arrShortcut['bActive'] ? "checked" : "" ?>
                                                               class="make-switch" data-size="normal"
                                                               name="homeConfig[shortcut][bActive]">
                                                        <p class="help-block font-blue-dark">启用时，快捷菜单将在首页显示，不启用则不显示</p>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="form-group " sDataType="Text"
                                                     sFieldAs="sMallName">
                                                    <label class="control-label col-md-1">模块背景</label>
                                                    <div class="col-md-11">

                                                        <div class="row">
                                                            <div class="col-md-2">
                                                                <img src="/<?= $arrShortcut['sBgPic'] ? $arrShortcut['sBgPic'] : "js/pages/img/u250.png" ?>"
                                                                     width="85" height="70"><br>
                                                                <button style="width:85px;" type="button"
                                                                        class="btn green btn-sm"
                                                                        onclick="uploadBgImage(this)">选择
                                                                </button>
                                                                <input type="file" class="upFile hide"
                                                                       accept="image/gif,image/jpeg,image/png">
                                                                <input type="hidden" class="form-control" id="sBgPic"
                                                                       placeholder=""
                                                                       value="<?= $arrShortcut['sBgPic'] ?>"
                                                                       name="homeConfig[shortcut][sBgPic]">
                                                            </div>
                                                            <div class="col-md-10">
                                                            </div>
                                                        </div>

                                                        <p class="help-block font-blue-dark">
                                                            建议尺寸：只有一排快捷菜单时，两排快捷菜单时<br>
                                                            图片不得大于<font class="font-red">3</font>M；<br>
                                                            若选择了模块背景图，则快捷菜单的背景为该图片；
                                                        </p>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="form-group ">
                                                    <label class="control-label col-md-1">快捷菜单<span class="required"
                                                                                                    aria-required="true">*</span>:</label>
                                                    <div class="col-md-11">

                                                        <? foreach ($arrShortcut['arrPic'] as $scrollImage) { ?>
                                                            <div class="row margin-top-10 ShortImage">
                                                                <div class="col-md-2">
                                                                    <img id="imageuploader"
                                                                         src="/<?= $scrollImage['sPic'] ? $scrollImage['sPic'] : "js/pages/img/u250.png" ?>"
                                                                         width="85" height="70"><br>

                                                                    <button id="btn-del" type="button"
                                                                            class="btn btn-xs">
                                                                        删除
                                                                    </button>
                                                                    <button id="btn-replace" type="button"
                                                                            class="btn btn-xs">替换
                                                                    </button>
                                                                    <input type="file" class="upFile hide"
                                                                           accept="image/gif,image/jpeg,image/png">
                                                                    <input type="hidden" class="form-control" id="sPic"
                                                                           placeholder=""
                                                                           value="<?= $scrollImage['sPic'] ?>"
                                                                           name="homeConfig[shortcut][sPic][]"
                                                                    >

                                                                </div>
                                                                <div class="col-md-10">
                                                                    <div class="row">
                                                                        <label class="control-label col-md-2">排序：</label>
                                                                        <div class="col-md-10">
                                                                            <input type="text" class="form-control"
                                                                                   sDataType="Text" sFieldAs="sName"
                                                                                   style="width: 100px;"
                                                                                   placeholder=""
                                                                                   value="<?= $scrollImage['lPos'] ?>"
                                                                                   name="homeConfig[shortcut][lPos][]"
                                                                            >
                                                                            <p class="help-block font-blue-dark"></p>
                                                                        </div>
                                                                    </div>
                                                                    <div class="row">
                                                                        <label class="control-label col-md-2">菜单名称：</label>
                                                                        <div class="col-md-10">
                                                                            <input type="text" class="form-control"
                                                                                   sDataType="Text" sFieldAs="sName"
                                                                                   placeholder=""
                                                                                   value="<?= $scrollImage['sName'] ?>"
                                                                                   name="homeConfig[shortcut][sName][]"
                                                                            >
                                                                            <p class="help-block font-blue-dark"></p>
                                                                        </div>
                                                                    </div>
                                                                    <div class="row">
                                                                        <label class="control-label col-md-2">链接：</label>
                                                                        <div class="col-md-10">
                                                                            <input type="text" class="form-control"
                                                                                   sDataType="Text" sFieldAs="sName"
                                                                                   placeholder=""
                                                                                   value="<?= $scrollImage['sLink'] ?>"
                                                                                   name="homeConfig[shortcut][sLink][]"
                                                                            >
                                                                            <p class="help-block font-blue-dark"></p>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        <? } ?>


                                                        <div class="row margin-top-10" id="addShortcutBtn">
                                                            <div class="col-md-2">
                                                                <div class="dropzone dropzone-file-area dz-clickable  <?= count($arrShortcut['arrPic']) == 10 ? 'hide' : '' ?>"
                                                                     id="my-dropzone" style=""
                                                                     onclick="addShortcut($(this).closest('#addShortcutBtn'))">
                                                                    <span style="font-size: 48px;font-weight: 700;">+</span>
                                                                    <p>点击可添加<br>建议尺寸:80X80</p>
                                                                </div>

                                                            </div>
                                                        </div>
                                                        <div class="row margin-top-10">
                                                            <div class="col-md-10">
                                                                <p class="help-block">
                                                                    为了页面整齐好看，建议您总共设置5个或10个快捷菜单，最多不可超过10个。<br>文件格式GIF、JPG、JPEG、PNG，序号越小越靠前
                                                                </p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane  <?=$_GET['currtab'] == 'tab_window' ? "active" : ""?>" id="tab_window">
                                <div class="form-body margin-top-10">
                                    <div class="row">
                                        <div class="col-md-12">

                                            <div class="row">
                                                <div class="form-group ">
                                                    <label class="control-label col-md-1">橱窗样式</label>
                                                    <div class="col-md-11">

                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="form-group " sDataType="Text"
                                                     sFieldAs="sMallName">
                                                    <label class="control-label col-md-1">是否启用</label>
                                                    <div class="col-md-11">
                                                        <input type="checkbox"
                                                               value="1" <?= $arrWindow['bActive'] ? "checked" : "" ?>
                                                               class="make-switch" data-size="normal"
                                                               name="homeConfig[window][bActive]">
                                                        <p class="help-block font-blue-dark">启用时，橱窗将在首页显示，不启用则不显示</p>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="form-group ">
                                                    <label class="control-label col-md-1">橱窗图片<span class="required"
                                                                                                    aria-required="true">*</span>:</label>
                                                    <div class="col-md-11">

                                                        <div class="row margin-top-10 WindowImage">
                                                            <div class="col-md-2">
                                                                <img id="imageuploader"
                                                                     src="/<?= $arrWindow['arrPic'][0]['sPic'] ? $arrWindow['arrPic'][0]['sPic'] : "js/pages/img/u250.png" ?>"
                                                                     width="85" height="70"><br>

                                                                <button id="btn-replace" type="button"
                                                                        class="btn btn-xs">替换
                                                                </button>
                                                                <input type="file" class="upFile hide"
                                                                       accept="image/gif,image/jpeg,image/png">
                                                                <input type="hidden" class="form-control" id="sPic"
                                                                       placeholder=""
                                                                       value="<?= $arrWindow['arrPic'][0]['sPic'] ?>"
                                                                       name="homeConfig[window][sPic][]"
                                                                >

                                                            </div>
                                                            <div class="col-md-10">
                                                                <div class="row">
                                                                    <label class="control-label col-md-2">排序：</label>
                                                                    <div class="col-md-10">
                                                                        1
                                                                    </div>
                                                                </div>
                                                                <div class="row">
                                                                    <label class="control-label col-md-2">建议尺寸：</label>
                                                                    <div class="col-md-10">
                                                                        374X360
                                                                    </div>
                                                                </div>
                                                                <div class="row">
                                                                    <label class="control-label col-md-2">链接：</label>
                                                                    <div class="col-md-10">
                                                                        <input type="text" class="form-control"
                                                                               sDataType="Text" sFieldAs="sName"
                                                                               placeholder=""
                                                                               value="<?= $arrWindow['arrPic'][0]['sLink'] ?>"
                                                                               name="homeConfig[window][sLink][]"
                                                                        >
                                                                        <p class="help-block font-blue-dark"></p>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="row margin-top-10 WindowImage">
                                                            <div class="col-md-2">
                                                                <img id="imageuploader"
                                                                     src="/<?= $arrWindow['arrPic'][1]['sPic'] ? $arrWindow['arrPic'][1]['sPic'] : "js/pages/img/u250.png" ?>"
                                                                     width="85" height="70"><br>

                                                                <button id="btn-replace" type="button"
                                                                        class="btn btn-xs">替换
                                                                </button>
                                                                <input type="file" class="upFile hide"
                                                                       accept="image/gif,image/jpeg,image/png">
                                                                <input type="hidden" class="form-control" id="sPic"
                                                                       placeholder=""
                                                                       value="<?= $arrWindow['arrPic'][1]['sPic'] ?>"
                                                                       name="homeConfig[window][sPic][]"
                                                                >

                                                            </div>
                                                            <div class="col-md-10">
                                                                <div class="row">
                                                                    <label class="control-label col-md-2">排序：</label>
                                                                    <div class="col-md-10">
                                                                        2
                                                                    </div>
                                                                </div>
                                                                <div class="row">
                                                                    <label class="control-label col-md-2">建议尺寸：</label>
                                                                    <div class="col-md-10">
                                                                        376X180
                                                                    </div>
                                                                </div>
                                                                <div class="row">
                                                                    <label class="control-label col-md-2">链接：</label>
                                                                    <div class="col-md-10">
                                                                        <input type="text" class="form-control"
                                                                               sDataType="Text" sFieldAs="sName"
                                                                               placeholder=""
                                                                               value="<?= $arrWindow['arrPic'][1]['sLink'] ?>"
                                                                               name="homeConfig[window][sLink][]"
                                                                        >
                                                                        <p class="help-block font-blue-dark"></p>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="row margin-top-10 WindowImage">
                                                            <div class="col-md-2">
                                                                <img id="imageuploader"
                                                                     src="/<?= $arrWindow['arrPic'][2]['sPic'] ? $arrWindow['arrPic'][2]['sPic'] : "js/pages/img/u250.png" ?>"
                                                                     width="85" height="70"><br>

                                                                <button id="btn-replace" type="button"
                                                                        class="btn btn-xs">替换
                                                                </button>
                                                                <input type="file" class="upFile hide"
                                                                       accept="image/gif,image/jpeg,image/png">
                                                                <input type="hidden" class="form-control" id="sPic"
                                                                       placeholder=""
                                                                       value="<?= $arrWindow['arrPic'][2]['sPic'] ?>"
                                                                       name="homeConfig[window][sPic][]"
                                                                >

                                                            </div>
                                                            <div class="col-md-10">
                                                                <div class="row">
                                                                    <label class="control-label col-md-2">排序：</label>
                                                                    <div class="col-md-10">
                                                                        3
                                                                    </div>
                                                                </div>
                                                                <div class="row">
                                                                    <label class="control-label col-md-2">建议尺寸：</label>
                                                                    <div class="col-md-10">
                                                                        376X180
                                                                    </div>
                                                                </div>
                                                                <div class="row">
                                                                    <label class="control-label col-md-2">链接：</label>
                                                                    <div class="col-md-10">
                                                                        <input type="text" class="form-control"
                                                                               sDataType="Text" sFieldAs="sName"
                                                                               placeholder=""
                                                                               value="<?= $arrWindow['arrPic'][2]['sLink'] ?>"
                                                                               name="homeConfig[window][sLink][]"
                                                                        >
                                                                        <p class="help-block font-blue-dark"></p>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
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
<script>

    function addScrollingImage(obj) {
        var copy = $(".ScrollingImage:first").clone();

        copy.insertBefore(obj);
        $("input", copy).val('');
        $("#imageuploader", copy).attr('src', "/js/pages/img/u81.png");

        initImageUploader($("#imageuploader", copy));

        if ($("#tab_scrollingimage img[id='imageuploader']").length == 5) {
            $("#addScrollingImageBtn .dropzone").addClass("hide");
        }
    }

    function addShortcut(obj) {
        var copy = $(".ShortImage:first").clone();

        copy.insertBefore(obj);
        $("input", copy).val('');
        $("#imageuploader", copy).attr('src', "/js/pages/img/u250.png");

        initImageUploader($("#imageuploader", copy));

        if ($("#tab_shortcut img[id='imageuploader']").length == 10) {
            $("#addShortcutBtn .dropzone").addClass("hide");
        }
    }

    function initImageUploader(image) {
        var btnDel = $(image).parent().find("#btn-del");
        var btnRpl = $(image).parent().find("#btn-replace");


        $(btnDel).click(
            function () {
                if ($(this).closest('#tab_scrollingimage').length) {
                    $("#addScrollingImageBtn .dropzone").removeClass("hide");

                    if ($("#tab_scrollingimage img[id='imageuploader']").length == 1) {
                        error("至少要保留一个轮播图");
                        return false;
                    }
                } else if ($(this).closest('#tab_shortcut').length) {
                    $("#addShortcutBtn .dropzone").removeClass("hide");

                    if ($("#tab_shortcut img[id='imageuploader']").length == 1) {
                        error("至少要保留一个快捷菜单");
                        return false;
                    }
                }

                $(this).closest(".row").remove();
            }
        )


        $(btnRpl).click(
            function () {
                $(this).parent().find(".upFile").click();
            }
        )

        $(image).parent().find(".upFile").on('change', function (event) {

            var files = event.target.files || event.srcElement.files,
                file = files[0],
                reader = new FileReader();
            console.log(file);

            if (files.length === 0) {
                return;
            }
            if (file.type.indexOf('image') == -1) { // 判断是否是图片
                error('请上传正确的图片格式');
                return;
            }

            var maxSize = 3000000; //设置上传图片的质量 这里是3M
            if (file.size > maxSize) {
                error("上传图片大小不得大于3M");
                return;
            }

            reader.readAsDataURL(file);
            reader.onload = function (event) {
                $(image).closest(".row").find("#imageuploader").attr('src', this.result);
                $(image).parent().find("#sPic").val(this.result)

            }
        });

    }

    $("[id='imageuploader']").each(
        function () {
            initImageUploader(this);
        }
    )

    $(".nav-tabs a[data-toggle='tab']").click
    (
        function () {
            var tabId = $(this).attr("href");
            $(tabId).addClass("active");
        }
    )


    function uploadBgImage(obj) {
        $(obj).parent().find(".upFile").click();

        $(obj).parent().find(".upFile").on('change', function (event) {

            var files = event.target.files || event.srcElement.files,
                file = files[0],
                reader = new FileReader();
            console.log(file);

            if (files.length === 0) {
                return;
            }
            if (file.type.indexOf('image') == -1) { // 判断是否是图片
                error('请上传正确的图片格式');
                return;
            }

            var maxSize = 3000000; //设置上传图片的质量 这里是3M
            if (file.size > maxSize) {
                error("上传图片大小不得大于3M");
                return;
            }

            reader.readAsDataURL(file);
            reader.onload = function (event) {
                $(obj).parent().find("img").attr('src', this.result);
                $(obj).parent().find("#sBgPic").val(this.result);

            }
        });
    }


    function objectSubmit() {
        document.objectform.currtab.value = $(".nav-tabs li.active a").attr('href').replace('#', '');
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