<script src="<?= Yii::$app->homeUrl ?>/js/pages/scripts/new.js" type="text/javascript"></script>

<div class="breadcrumb" style="display:none">
    <h2>物流跟踪</h2>
    <h3><?= \myerm\common\models\ExpressCompany::findOne($_GET['CompanyID'])->sName ?></h3>
</div>


<div class="row margin-top-10">
    <div class="col-md-12">
        <div class="portlet light bordered">

            <div class="portlet-body form">
                <form name="objectform" action="/shop/mallconfig/wechat"
                      class="horizontal-form" method="post" enctype="multipart/form-data">
                    <input type="hidden" name="_csrf" value="<?= Yii::$app->request->getCsrfToken() ?>">
                    <div class="form-body">
                        <div class="row">
                            <div class="col-md-12">
                                <? foreach ($express['data'] as $data) { ?>
                                    <div class="row">
                                        <div class="form-group " sObjectName="Shop/Supplier" sDataType="Text"
                                             sFieldAs="sName">
                                            <label class="control-label col-md-2"><?= $data['time'] ?></label>
                                            <div class="col-md-9"><?= $data['context'] ?>
                                            </div>
                                        </div>
                                    </div>
                                <? } ?>
                            </div>
                        </div>

                    </div>

                </form>
            </div>
        </div>
    </div>
</div>


<script>
    <? if (!$express['status']) { ?>
    $(document).ready(
        function () {
            error("<?=$express['message']?>");
        }
    );
    <? } ?>
</script>