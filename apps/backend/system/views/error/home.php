<div class="breadcrumb" style="display:none">
    <h2><?=Yii::t('app', '系统')?></h2>
    <h3><?=Yii::t('app', '出错信息')?></h3>
</div>

<div class="row">
    <div class="col-md-12 page-404">
        <div class="number font-green"> 400 </div>
        <div class="details">
            <h3><?=$exception->getMessage()?></h3>
        </div>
    </div>
</div>
<link href="<?=Yii::$app->homeUrl?>/js/pages/css/error.min.css" rel="stylesheet" type="text/css" />