<div class="breadcrumb" style="display:none">
    <h2>发货地址管理</h2>
    <h3>编辑</h3>
</div>

<style>
    .inputBox input {
        width: 70px;
        height: 30px;
        border: 0px solid #c2cad8;
    }

    .inputBox span {
        position: absolute;
        width: 30px;
        height: 30px;
        text-align: center;
        line-height: 30px;
        color: #000;
        font-weight: 700;
        right: 0;
        top: 0;
        border-left: 1px solid #ccc;
    }

    #ExpressBusinessSpan {
        display: none;
    }
</style>
<div class="row margin-top-10">

    <div class="col-md-12">
        <div class="note note-info">
            <h4 class="block"><?= Yii::t('app', '提示') ?></h4>
            <p>您可在此页面设置用于打印快递面单的发货地址信息</p>
            <p>快递账号等信息请根据实际合作快递公司要求填写</p>
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
                <form name="objectform" action="/shop/kdshipaddress/edit"
                      class="horizontal-form" method="post" enctype="multipart/form-data">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="row">
                                <div class="form-group">
                                    <label class="control-label col-md-3">模板名称<span class="required"
                                                                                    aria-required="true">*</span>:</label>
                                    <div class="col-md-9">
                                        <input type="text" class="form-control" name="sName"
                                               value="<?= $ShipAddressInfo['sName'] ?>">
                                        <input type="hidden" name="lID" value="<?= $_GET['ID'] ?>">
                                        <p class="help-block"></p>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group">
                                    <label class="control-label col-md-3">发货人<span class="required"
                                                                                   aria-required="true">*</span>:</label>
                                    <div class="col-md-9">
                                        <input type="text" class="form-control" name="sShipper"
                                               value="<?= $ShipAddressInfo['sShipper'] ?>">
                                        <p class="help-block"></p>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group">
                                    <label class="control-label col-md-3">手机号<span class="required"
                                                                                   aria-required="true">*</span>:</label>
                                    <div class="col-md-9">
                                        <input type="text" class="form-control" name="sMobile"
                                               value="<?= $ShipAddressInfo['sMobile'] ?>">
                                        <p class="help-block"></p>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group">
                                    <label class="control-label col-md-3">邮编<span class="required"
                                                                                  aria-required="true">*</span>:</label>
                                    <div class="col-md-9">
                                        <input type="text" class="form-control" name="sPostCode"
                                               value="<?= $ShipAddressInfo['sPostCode'] ?>">
                                        <p class="help-block"></p>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group">
                                    <label class="control-label col-md-3">省份<span class="required"
                                                                                  aria-required="true">*</span>:</label>
                                    <div class="col-md-9">
                                        <select id="ProvinceID" name="ProvinceID" onchange="ProvinceCityLinkage()">
                                            <option value="">请选择省份</option>
                                            <? foreach ($Province as $Province) { ?>
                                                <option value="<?= $Province->ID ?>"
                                                    <? if ($Province->ID == $ShipAddressInfo['ProvinceID']) { ?>
                                                        selected
                                                    <? } ?>
                                                ><?= $Province->sName ?></option>
                                            <? } ?>
                                        </select>
                                        <p class="help-block"></p>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group">
                                    <label class="control-label col-md-3">城市<span class="required"
                                                                                  aria-required="true">*</span>:</label>
                                    <div class="col-md-9">
                                        <select id="CityID" name="CityID" onchange="CityAreaLinkage()">
                                            <option value="">请选择城市</option>
                                            <? foreach ($City as $City) { ?>
                                                <option value="<?= $City->ID ?>"
                                                    <? if ($City->ID == $ShipAddressInfo['CityID']) { ?>
                                                        selected
                                                    <? } ?>
                                                ><?= $City->sName ?></option>
                                            <? } ?>
                                        </select>
                                        <p class="help-block"></p>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group">
                                    <label class="control-label col-md-3">区/县:</label>
                                    <div class="col-md-9">
                                        <select id="AreaID" name="AreaID">
                                            <option value="">请选择区/县</option>
                                            <? foreach ($Area as $Area) { ?>
                                                <option value="<?= $Area->ID ?>"
                                                    <? if ($Area->ID == $ShipAddressInfo['AreaID']) { ?>
                                                        selected
                                                    <? } ?>
                                                ><?= $Area->sName ?></option>
                                            <? } ?>
                                        </select>
                                        <p class="help-block"></p>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group">
                                    <label class="control-label col-md-3">发货地址<span class="required"
                                                                                    aria-required="true">*</span>:</label>
                                    <div class="col-md-9">
                                        <input type="text" class="form-control" name="sAddress"
                                               value="<?= $ShipAddressInfo['sAddress'] ?>">
                                        <p class="help-block"></p>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group">
                                    <label class="control-label col-md-3">是否默认地址:</label>
                                    <div class="col-md-9">
                                        <div class="radio-list input-group">
                                            <label class="radio-inline">
                                                <input type="radio" name="bDefault" value="1"
                                                    <? if ($ShipAddressInfo['bDefault'] == 1) { ?>
                                                        checked
                                                    <? } ?>
                                                > 是
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio" name="bDefault" value="0"
                                                    <? if ($ShipAddressInfo['bDefault'] == 0) { ?>
                                                        checked
                                                    <? } ?>
                                                > 否
                                            </label>
                                        </div>
                                        <p class="help-block"></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="row">
                                <div class="form-group">
                                    <label class="control-label col-md-3">快递<span class="required"
                                                                                  aria-required="true">*</span>:</label>
                                    <div class="col-md-9">
                                        <select id="sKdbirdCode" name="sKdbirdCode" onchange="ChooseExpressBusiness()">
                                            <option value="">选择快递</option>
                                            <? foreach ($ExpressCompany as $ExpressCompany) { ?>
                                                <option value="<?= $ExpressCompany->sKdBirdCode ?>"
                                                    <? if ($ExpressCompany->sKdBirdCode == $ShipAddressInfo['sKdBirdCode']) { ?>
                                                        selected
                                                    <? } ?>
                                                ><?= $ExpressCompany->sName ?></option>
                                            <? } ?>
                                        </select>
                                        <span id="ExpressBusinessSpan">
                                            选择业务:
                                            <select name="ExpressBusinessID" id="ExpressBusinessID">

                                            </select>
                                        </span>
                                        <p class="help-block"></p>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group">
                                    <label class="control-label col-md-3">结算方式:</label>
                                    <div class="col-md-9">
                                        <select name="SettlementMethodID">
                                            <option value="">请选择</option>
                                            <option value="new_clearing"
                                                <? if ($ShipAddressInfo['SettlementMethodID'] == "new_clearing") { ?>
                                                    selected
                                                <? } ?>
                                            >现结
                                            </option>
                                            <option value="to_pay"
                                                <? if ($ShipAddressInfo['SettlementMethodID'] == "to_pay") { ?>
                                                    selected
                                                <? } ?>
                                            >到付</option>
                                            <option value="monthly_clearing"
                                                <? if ($ShipAddressInfo['SettlementMethodID'] == "monthly_clearing") { ?>
                                                    selected
                                                <? } ?>
                                            >月结</option>
                                            <option value="third_party_payment"
                                                <? if ($ShipAddressInfo['SettlementMethodID'] == "third_party_payment") { ?>
                                                    selected
                                                <? } ?>
                                            >第三方支付</option>
                                        </select>
                                        <p class="help-block"></p>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group">
                                    <label class="control-label col-md-3">快递账号:</label>
                                    <div class="col-md-9">
                                        <input type="text" class="form-control" name="sExpressName"
                                               value="<?= $ShipAddressInfo['sExpressName'] ?>">
                                        <p class="help-block"></p>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group">
                                    <label class="control-label col-md-3">快递密码:</label>
                                    <div class="col-md-9">
                                        <input type="text" class="form-control" name="sExpressPassword"
                                               value="<?= $ShipAddressInfo['sExpressPassword'] ?>">
                                        <p class="help-block"></p>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group">
                                    <label class="control-label col-md-3">月结编码:</label>
                                    <div class="col-md-9">
                                        <input type="text" class="form-control" name="sExpressCode"
                                               value="<?= $ShipAddressInfo['sExpressCode'] ?>">
                                        <p class="help-block"></p>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group">
                                    <label class="control-label col-md-3">快递合作密钥:</label>
                                    <div class="col-md-9">
                                        <input type="text" class="form-control" name="sExpressKey"
                                               value="<?= $ShipAddressInfo['sExpressKey'] ?>">
                                        <p class="help-block"></p>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group">
                                    <label class="control-label col-md-3">快递网点名称:</label>
                                    <div class="col-md-9">
                                        <input type="text" class="form-control" name="sExpressSendSite"
                                               value="<?= $ShipAddressInfo['sExpressSendSite'] ?>">
                                        <p class="help-block"></p>
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
        if ($("input[name='sName']").val() == "") {
            error("请填写模板名称");
            return false;
        }
        if ($("input[name='sShipper']").val() == "") {
            error("请填写发货人");
            return false;
        }
        if ($("input[name='sMobile']").val() == "") {
            error("请填写手机号");
            return false;
        }
        
        var sMobile = $("input[name='sMobile']").val();
        
        if (sMobile.substring(0,3) == '400'){
            if(sMobile.length != 10){
                error("请填写正确手机号");
                return false;
            }
        }else{
            if(sMobile.length != 11){
                error("请填写正确手机号");
                return false;
            }
        }
        if ($("input[name='sPostCode']").val() == "") {
            error("请填写邮编");
            return false;
        }
        if(!/^[0-9][0-9]{5}$/.test($("input[name='sPostCode']").val())){
            error("请填写正确邮编");
            return false;
        }
        if ($("select[name='ProvinceID']").val() == "") {
            error("请选择省份");
            return false;
        }
        if ($("select[name='CityID']").val() == "") {
            error("请选择城市");
            return false;
        }
        if ($("input[name='sAddress']").val() == "") {
            error("请填写发货地址");
            return false;
        }
        if ($("select[name='sKdbirdCode']").val() == "") {
            error("请选择快递");
            return false;
        }
        document.objectform.submit();
    }

    //省份-城市联动
    function ProvinceCityLinkage() {
        $.post
        (
            '/shop/kdshipaddress/subareas',
            {
                ID: $("#ProvinceID").val()
            },
            function (res) {
                $("#CityID").empty();
                $("#AreaID").empty();
                var data = JSON.parse(res);
                var cityStr = "<option value=''>请选择城市</option>";
                var areaStr = "<option value=''>请选择区/县</option>";
                for (var i = 0; i < data.length; i++) {
                    cityStr += "<option value='" + data[i]['ID'] + "'>" + data[i]['sName'] + "</option>"
                }
                $("#CityID").append(cityStr);
                $("#AreaID").append(areaStr);
            }
        )
    }

    //城市-区/县联动
    function CityAreaLinkage() {
        $.post
        (
            '/shop/kdshipaddress/subareas',
            {
                ID: $("#CityID").val()
            },
            function (res) {
                $("#AreaID").empty();
                var data = JSON.parse(res);
                var areaStr = "<option value=''>请选择区/县</option>";
                for (var i = 0; i < data.length; i++) {
                    areaStr += "<option value='" + data[i]['ID'] + "'>" + data[i]['sName'] + "</option>"
                }
                $("#AreaID").append(areaStr);
            }
        )
    }

    //快递业务
    function ChooseExpressBusiness($Code = "") {
        var sKdbirdCode = $("#sKdbirdCode").val();
        var BusinessStr = "";
        $("#ExpressBusinessID").empty();
        if (sKdbirdCode == "SF" || sKdbirdCode == "DBL") {
            $.post
            (
                '/shop/kdshipaddress/expressbusiness',
                {
                    sKdBirdCode: sKdbirdCode
                },
                function (res) {
                    var data = JSON.parse(res);
                    for (var i = 0; i < data.length; i++) {
                        BusinessStr += "<option value='" + data[i]['lID'] + "'>" + data[i]['sName'] + "</option>";
                    }
                    $("#ExpressBusinessID").append(BusinessStr);
                    $("#ExpressBusinessID option[value='<?= $ShipAddressInfo['ExpressBusinessID']?>']").attr("selected", true);
                    $("#ExpressBusinessSpan").css("display", "inline");
                }
            )
        } else {
            $("#ExpressBusinessSpan").css("display", "none");
        }
    }

    <? if ($ShipAddressInfo['sKdBirdCode'] == 'SF' || $ShipAddressInfo['sKdBirdCode'] == "DBL") { ?>
    $(document).ready(
        ChooseExpressBusiness()
    );
    <? } ?>
</script>