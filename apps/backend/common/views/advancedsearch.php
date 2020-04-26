<link href="<?= Yii::$app->homeUrl ?>/js/global/plugins/bootstrap-daterangepicker/daterangepicker.min.css"
      rel="stylesheet" type="text/css"/>
<script src="<?= Yii::$app->homeUrl ?>/js/global/plugins/moment.js" type="text/javascript"></script>
<script src="<?= Yii::$app->homeUrl ?>/js/global/plugins/bootstrap-daterangepicker/daterangepicker.min.js"
        type="text/javascript"></script>
<div class="portlet light bg-inverse">
    <div class="portlet-body form">
        <form action="#" class="form-horizontal">
            <div class="form-body">
                <? foreach ($arrSearchField as $i => $s) { ?>
                    <? if ($i % 4 == 0) { ?>
                        <div class="row">
                    <? } ?>
                    <div class="col-md-3" colindex="<?= $i ?>">
                        <div class="form-group">
                            <label class="control-label col-md-3"><?= $s->field->sName ?>:</label>
                            <? if ($s->field->sDataType == 'Text' || $s->field->sDataType == 'AttachFile') { ?>
                                <div class="col-md-9"><input type="text" class="form-control search-field"
                                                             soper="center" name="<?= $s->field->sFieldAs ?>"></div>
                            <? } elseif ($s->field->sDataType == 'ListTable' && $s->field->refobject->sNameField == $s->field->sShwField) { ?>
                                <div class="col-md-9"><input type="text"
                                                             value="<?= urldecode($_GET[$s->field->sFieldAs]) ?>"
                                                             class="form-control search-field" soper="equal"
                                                             name="<?= $s->field->sFieldAs ?>"></div>
                            <? } elseif ($s->field->sDataType == 'Virtual') { ?>
                                <div class="col-md-9"><input type="text" class="form-control search-field" soper="equal" value="<?= urldecode($_GET[$s->field->sFieldAs]) ?>"
                                                             name="<?= $s->field->sFieldAs ?>"></div>
                            <? } elseif ($s->field->sDataType == 'ListTable' && $s->field->sysobject->sIDField == $s->field->sShwField || $s->field->sFieldAs == $s->field->sysobject->sIDField) { ?>
                                <div class="col-md-9">
                                    <select class="bs-select form-control hide" style="width:40%;display:inline"
                                            onchange="if(this.value == 'benull'){$(this).parent().find('.search-field').hide()}else{$(this).parent().find('.search-field').show()}$(this).parent().find('.search-field').attr('soper', this.value)">
                                        <option value="equal">=</option>
                                        <option value="larger">&gt;</option>
                                        <option value="smaller">&lt;</option>
                                        <option value="lgeq">&gt;=</option>
                                        <option value="smeq">&lt;=</option>
                                        <option value="noteq">&lt;&gt;</option>
                                        <option value="benull"><?= \Yii::t('app', '空') ?></option>
                                    </select>
                                    <input type="text" class="form-control search-field"
                                           value="<?= urldecode($_GET[$s->field->sFieldAs]) ?>" soper="equal"
                                           name="<?= $s->field->sFieldAs ?>" style="display:inline">
                                </div>
                            <? } elseif ($s->field->sDataType == 'Int' || $s->field->sDataType == 'Float') { ?>

                                <div class="col-md-9">
                                    <select class="bs-select form-control" style="width:40%;display:inline"
                                            onchange="if(this.value == 'benull'){$(this).parent().find('.search-field').hide()}else{$(this).parent().find('.search-field').show()}$(this).parent().find('.search-field').attr('soper', this.value)">
                                        <option value="equal">=</option>
                                        <option value="larger">&gt;</option>
                                        <option value="smaller">&lt;</option>
                                        <option value="lgeq">&gt;=</option>
                                        <option value="smeq">&lt;=</option>
                                        <option value="noteq">&lt;&gt;</option>
                                        <option value="benull"><?= \Yii::t('app', '空') ?></option>
                                    </select>
                                    <input type="text" class="form-control search-field" soper="equal"
                                           name="<?= $s->field->sFieldAs ?>" style="width:58%;display:inline">
                                </div>


                            <? } elseif ($s->field->sDataType == 'Bool') { ?>
                                <div class="col-md-9">
                                    <select class="bs-select form-control search-field"
                                            name="<?= $s->field->sFieldAs ?>" soper="equal"
                                            sDataType="<?= $s->field->sDataType ?>">
                                        <option value=""><?= \Yii::t('app', '全部') ?></option>
                                        <option value="1"><?= \Yii::t('app', '是') ?></option>
                                        <option value="0"><?= \Yii::t('app', '否') ?></option>
                                    </select>
                                </div>
                            <? } elseif ($s->field->sDataType == 'List') { ?>
                                <div class="col-md-9">
                                    <? $arrOptSelected = explode(",", urldecode($_GET[$s->field->sFieldAs])); ?>
                                    <select class="multiselect search-field" multiple="multiple"
                                            name="<?= $s->field->sFieldAs ?>" soper="in"
                                            sDataType="<?= $s->field->sDataType ?>">
                                        <? foreach ($s->field->options as $option) { ?>
                                            <option value="<?= $option['ID'] ?>" <?=in_array($option['ID'],
                                                $arrOptSelected) ? "selected" : "" ?>><?= $option['sName'] ?></option>
                                        <? } ?>
                                    </select>
                                </div>
                            <? } elseif ($s->field->sDataType == 'MultiList') { ?>
                                <div class="col-md-9">
                                    <select class="multiselect search-field" multiple="multiple"
                                            name="<?= $s->field->sFieldAs ?>" soper="center"
                                            sDataType="<?= $s->field->sDataType ?>">
                                        <? foreach ($s->field->options as $option) { ?>
                                            <option value="<?= $option['ID'] ?>"><?= $option['sName'] ?></option>
                                        <? } ?>
                                    </select>
                                </div>
                            <? } elseif ($s->field->sDataType == 'Date') { ?>
                                <div class="col-md-9">
                                    <div class="input-group datepicker" colindex="<?= $i ?>">
                                        <input type="text" value="<?= urldecode($_GET[$s->field->sFieldAs]) ?>"
                                               class="form-control search-field" name="<?= $s->field->sFieldAs ?>" soper="between"
                                               sDataType="<?= $s->field->sDataType ?>">
                                        <span class="input-group-btn">
                                            <button class="btn default date-range-toggle" type="button">
                                                <i class="fa fa-calendar"></i>
                                            </button>
                                        </span>
                                    </div>
                                </div>
                            <? } ?>
                        </div>
                    </div>
                    <!--/span-->
                    <? if (($i + 1) % 4 == 0 || count($arrSearchField) == $i + 1) { ?>
                        </div>
                    <? } ?>
                <? } ?>
                <!--/row-->
            </div>
            <div class="form-actions">
                <div class="row">
                    <div class="col-md-6">
                        <div class="row">
                            <div class="col-md-9">
                                <button type="button" class="btn default"
                                        onclick="this.form.listtable.emptySearchValue()"><?= \Yii::t('app',
                                        '清空') ?></button>
                                <button type="button" class="btn green"
                                        onclick="this.form.listtable.search()"><?= \Yii::t('app', '确定') ?></button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>