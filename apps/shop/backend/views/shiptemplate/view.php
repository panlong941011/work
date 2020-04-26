<div class="breadcrumb" style="display:none">
    <h2><a href="<?= Yii::$app->homeUrl ?>/<?= strtolower($sysObject->sObjectName) ?>/home"><?= $sysObject->sName ?></a>
    </h2>
    <h3><?= Yii::t('app', '详情页') ?></h3>
</div>
<div class="margin-top-10">
    <div class="portlet light bordered">
        <div class="portlet-title">
            <div class="caption">
                <span class="caption-subject font-green-haze bold uppercase"><?= $data['sName'] ?></span>
            </div>
        </div>
        <div class="form-actions">
            <div class="row">
                <div class="col-md-12">
                    <?= $this->context->getViewButton() ?>
                </div>
                <div class="col-md-6"></div>
            </div>
        </div>
        <div class="portlet-body form">
            <form action="#" class="form-horizontal">
                <div class="form-body">
                    <h3 class="form-section">基本信息</h3>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="control-label col-md-4 bold">名称:</label>
                                <div class="col-md-8">
                                    <p class="form-control-static"><?= $data['sName'] ?></p>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-md-4 bold">宝贝地址:</label>
                                <div class="col-md-8">
                                    <p class="form-control-static"><?= $data['DeliveryAddress'] ?></p>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-md-4 bold">计价方式:</label>
                                <div class="col-md-8">
                                    <p class="form-control-static"><?= $data['sValuationName'] ?></p>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-md-4 bold">运送方式:</label>
                                <div class="col-md-8">
                                    <p class="form-control-static"><?= $data['sShipMethodName'] ?></p>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-md-4 bold">是否指定条件包邮:</label>
                                <div class="col-md-8">
                                    <p class="form-control-static"><?= $data['bSetFree'] ? "是" : "否" ?></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- 运费模板明细 -->
<div class="margin-top-10">
    <div class="portlet light bordered">
        <div class="portlet-title">
            <div class="caption">
                <span class="caption-subject bold uppercase">运费模板明细</span>
            </div>
        </div>
        <div class="table-scrollable">
            <table class="table table-striped table-bordered table-hover table-checkable order-column dataTable no-footer table-header-fixed"
                   id="listtable">
                <thead>
                <tr role="row">
                    <th>运送方式</th>
                    <th style="text-align: center;">运送到</th>
                    <th style="text-align: center;"><?= $table['Start'] ?></th>
                    <th style="text-align: center;"><?= $table['Postage'] ?></th>
                    <th style="text-align: center;"><?= $table['Plus'] ?></th>
                    <th style="text-align: center;"><?= $table['Postageplus'] ?></th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($dataDetail as $value) { ?>
                    <tr class="gradeX odd" role="row">
                        <td nowrap="nowrap"><?= $value['sShipMethodName'] ?></td>
                        <td nowrap="nowrap"
                            style="word-wrap: break-word; word-break: normal;  width:500px;"><?= $value['sAreaName'] ?></td>
                        <td nowrap="nowrap" style="text-align: center;"><?= $value['lStart'] ?></td>
                        <td nowrap="nowrap" style="text-align: center;"><?= $value['fPostage'] ?></td>
                        <td nowrap="nowrap" style="text-align: center;"><?= $value['lPlus'] ?></td>
                        <td nowrap="nowrap" style="text-align: center;"><?= $value['fPostageplus'] ?></td>
                    </tr>
                <?php } ?>

                </tbody>
            </table>
        </div>
    </div>
</div>
<!-- 判断是否指定条件包邮 -->
<?php if ($data['bSetFree']) { ?>
    <div class="margin-top-10">
        <div class="portlet light bordered">
            <div class="portlet-title">
                <div class="caption">
                    <span class="caption-subject bold uppercase">指定条件包邮明细</span>
                </div>
            </div>
            <div class="table-scrollable">
                <table class="table table-striped table-bordered table-hover table-checkable order-column dataTable no-footer table-header-fixed"
                       id="listtable">
                    <thead>
                    <tr role="row">
                        <th>运送方式</th>
                        <th style="text-align: center;">运送到</th>
                        <th style="text-align: center;">快递类型</th>
                        <th style="text-align: center;">包邮条件类型</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($dataFreeDetail as $value) { ?>
                        <tr class="gradeX odd" role="row">
                            <td nowrap="nowrap"><?= $value['sShipMethodName'] ?></td>
                            <td nowrap="nowrap"
                                style="word-wrap: break-word; word-break: normal;  width:500px;"><?= $value['sAreaName'] ?></td>
                            <td nowrap="nowrap"
                                style="text-align: center;"><?= $value['sFreeExpressType'] ? $value['sFreeExpressType'] : "(无)" ?></td>
                            <td nowrap="nowrap" style="text-align: center;"><?= $value['FreeType'] ?></td>
                        </tr>
                    <?php } ?>

                    </tbody>
                </table>
            </div>
        </div>
    </div>
<?php } ?>

<?php if ($arrNoDelivery) { ?>
    <div class="margin-top-10">
        <div class="portlet light bordered">
            <div class="portlet-title">
                <div class="caption">
                    <span class="caption-subject bold uppercase">指定不发货地区</span>
                </div>
            </div>
            <div class="table-scrollable">
                <div>
                    <?php foreach ($arrNoDelivery as $i => $delivery) { ?>
                        <span style="padding: 10px"><?= $delivery['provice'] ?></span>
                    <?php } ?>
                </div>
                <div class="table-scrollable">
                    <table class="table table-striped table-bordered table-hover table-checkable order-column dataTable no-footer table-header-fixed"
                           id="listtable" style="display: none">
                        <thead>
                        <tr role="row">
                            <th>序号</th>
                            <th>不发货地区 </th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($arrNoDelivery as $i => $delivery) { ?>
                            <tr class="gradeX odd" role="row">
                                <td >
                                    <?=$i+1?>
                                </td>
                                <td><? foreach ($delivery as $d) {
                                        echo $d->sName.",";
                                    } ?> </td>
                            </tr>
                        <?php } ?>

                        </tbody>
                    </table>
            </div>
        </div>
    </div>
<?php } ?>
