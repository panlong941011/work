<link href="<?= Yii::$app->homeUrl ?>/js/global/plugins/bootstrap-daterangepicker/daterangepicker.min.css"
      rel="stylesheet" type="text/css"/>
<script src="<?= Yii::$app->homeUrl ?>/js/global/plugins/moment.js" type="text/javascript"></script>
<script src="<?= Yii::$app->homeUrl ?>/js/global/plugins/bootstrap-daterangepicker/daterangepicker.min.js"
        type="text/javascript"></script>
<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
    <h4 class="modal-title"><?= $sysObject->sName ?></h4>
</div>
<div class="modal-body" id="reftable">
    <div class="row">
        <? if ($list->advancedsearchfield) { ?>
            <div class="col-md-3">
                <div class="portlet light bordered">
                    <div class="portlet-title">
                        <div class="caption">
                            <i class="icon-equalizer font-blue-hoki"></i>
                            <span class="caption-subject font-blue-hoki bold uppercase"><?= Yii::t('app',
                                    '快速搜索') ?></span>
                        </div>
                    </div>
                    <div class="portlet-body form">
                        <!-- BEGIN FORM-->
                        <form action="#" class="horizontal-form">
                            <div class="form-actions left">
                                <button type="button" class="btn red" onclick="refListTable.loadData()"><i
                                            class="fa fa-check"></i> <?= Yii::t('app', '确定') ?></button>
                                <button type="button" class="btn default"
                                        onclick="refListTable.emptySearchValue()"><?= Yii::t('app', '清空') ?></button>
                            </div>
                            <div class="form-body">
                                <? foreach ($list->advancedsearchfield as $i => $s) { ?>
                                    <? if ($s->field->sName) { ?>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label class="control-label"><?= $s->field->sName ?></label>
                                                    <? if ($s->field->sDataType == 'Text' || $s->field->sDataType == 'ListTable' || $s->field->sDataType == 'AttachFile') { ?>
                                                        <input type="text" class="form-control search-field"
                                                               soper="center" name="<?= $s->field->sFieldAs ?>"
                                                               onkeypress="if(event.keyCode == '13'){ refListTable.loadData() }">
                                                    <? } elseif ($s->field->sDataType == 'Int' || $s->field->sDataType == 'Float') { ?>
                                                        <input type="text" class="form-control search-field"
                                                               soper="equal" name="<?= $s->field->sFieldAs ?>"
                                                               onkeypress="if(event.keyCode == '13'){ refListTable.loadData() }">
                                                    <? } elseif ($s->field->sDataType == 'Bool') { ?>
                                                        <select class="bs-select form-control search-field"
                                                                name="<?= $s->field->sFieldAs ?>" soper="equal"
                                                                sDataType="<?= $s->field->sDataType ?>">
                                                            <option value=""><?= Yii::t('app', '全部') ?></option>
                                                            <option value="1"><?= Yii::t('app', '是') ?></option>
                                                            <option value="0"><?= Yii::t('app', '否') ?></option>
                                                        </select>
                                                    <? } elseif ($s->field->sDataType == 'List') { ?>
                                                        <select class="multiselect form-control search-field" size="3"
                                                                multiple="multiple" name="<?= $s->field->sFieldAs ?>"
                                                                soper="in" sDataType="<?= $s->field->sDataType ?>">
                                                            <? foreach ($s->field->options as $option) { ?>
                                                                <option value="<?= $option['ID'] ?>"><?= $option['sName'] ?></option>
                                                            <? } ?>
                                                        </select>
                                                    <? } elseif ($s->field->sDataType == 'MultiList') { ?>
                                                        <select class="multiselect form-control search-field" size="3"
                                                                multiple="multiple" name="<?= $s->field->sFieldAs ?>"
                                                                soper="center" sDataType="<?= $s->field->sDataType ?>">
                                                            <? foreach ($s->field->options as $option) { ?>
                                                                <option value="<?= $option['ID'] ?>"><?= $option['sName'] ?></option>
                                                            <? } ?>
                                                        </select>
                                                    <? } elseif ($s->field->sDataType == 'Date') { ?>
                                                        <div class="input-group datepicker" colindex="1">
                                                            <input type="text" class="form-control search-field"
                                                                   name="<?= $s->field->sFieldAs ?>"
                                                                   sDataType="<?= $s->field->sDataType ?>">
                                                            <span class="input-group-btn">
                                                    <button class="btn default date-range-toggle" type="button">
                                                        <i class="fa fa-calendar"></i>
                                                    </button>
                                                </span>
                                                        </div>
                                                    <? } ?>
                                                </div>
                                            </div>
                                        </div>
                                        <!--/row-->
                                    <? } ?>
                                <? } ?>
                            </div>
                        </form>
                        <!-- END FORM-->
                    </div>
                </div>
            </div>
        <? } ?>
        <div class="col-md-9" style="padding-left:0px">
            <div class="portlet light bordered">
                <div class="portlet-body">
                    <div class="table-toolbar">
                        <div class="row">
                            <div class="col-md-12" id="btngroup">
                                <?= $this->context->getRefButton($list->sKey) ?>
                            </div>
                        </div>
                    </div>
                    <div class="dataTables_wrapper no-footer" id="listtable-container">

                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

<script src="<?= Yii::$app->homeUrl ?>/js/pages/scripts/listtable.js" type="text/javascript"></script>
<script src="http://cdn.bootcss.com/jquery-scrollTo/2.1.2/jquery.scrollTo.js" type="text/javascript"></script>
<script>
    clearToastr();
    function ok() {
        var lLength = refListTable.getSelectedLength();
        if (lLength == 0) {
            error("<?=Yii::t('app', '必须选择一条记录。')?>");
            return;
        } else if (lLength != 1) {
            error("<?=Yii::t('app', '只能选择一条记录。')?>");
            return;
        }

        $.post
        (
            '<?=Yii::$app->homeUrl?>/<?=strtolower($sysObject->sObjectName)?>/refsave',
            {
                ID: refListTable.getFirstSelected(),
                sObjectName: '<?=$_GET['sObjectName']?>',
                sFieldAs: '<?=$_GET['sFieldAs']?>'
            },
            function (data) {
                eval("var data=" + data);
                refSave('<?=$_GET['sObjectName']?>', '<?=$_GET['sFieldAs']?>', data);
                closeModal();
            }
        );
    }

    var config = {
        sUrl: '<?=Yii::$app->homeUrl?>/<?=strtolower($sysObject->sObjectName)?>', /*必填*/
        container: $("#reftable"), /*必填*/
        sDataUrl: '<?=Yii::$app->homeUrl?>/<?=strtolower($sysObject->sObjectName)?>/getlistdata', /*必填*/
        ListID: '<?=$list->ID?>', /*必填*/
        sListKey: '<?=$list->sKey?>', /*必填*/
        bSingle: <?=$list->bSingle ? "true" : "false"?>,
        sExtra: typeof getExtra == 'function' ? getExtra() : '<?=$_GET['sObjectName']?>/<?=$_GET['sFieldAs']?>', /*额外字段，用于给视图传特殊的值用于检索*/
        onLoad: function () {
            $("#quicksearchbar").hide();
        }
    }

    var refListTable = new ListTable(config);
    refListTable.loadData();
</script>