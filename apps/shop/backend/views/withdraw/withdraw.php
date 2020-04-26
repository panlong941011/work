<div class="breadcrumb" style="display:none">
    <h2>供应商管理</h2>
    <h3>供应商提现申请</h3>
</div>

<style>
    .inputBox {
        width: 102px;
        border: 1px solid #c2cad8;
        position: relative;
    }

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
    .has-error{
        color: #e73d4a;
    }
</style>
<div class="row margin-top-10">

    <div class="col-md-12">
        <div class="note note-info">
            <h4 class="block"><?= Yii::t('app', '提示') ?></h4>
            <p>您可在此页面申请提现</p>
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
                <form name="objectform" action="/shop/withdraw/new"
                      class="horizontal-form" method="post" enctype="multipart/form-data">
                    <div class="form-body margin-top-10">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="row">
                                    <div class="form-group " sDataType="Text"
                                         sFieldAs="sDefSearchWord">
                                        <label class="control-label col-md-3">提现金额<span class="required"
                                                                                          aria-required="true">*</span>:</label>
                                        <div class="col-md-9">

                                            <div class="inputBox">
                                                <input type="text"
                                                       name="fMoney">
                                                <span class="bg-grey-steel">元</span>
                                            </div>
                                            <p class="help-block font-blue-dark">
                                                最多可提现<span id="mostMoney"><?= $fBalance?></span>元
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-body margin-top-10">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="row">
                                    <div class="form-group " sDataType="Text"
                                         sFieldAs="sDefSearchWord">
                                        <label class="control-label col-md-3 <?if(!$BankAccount){?>has-error<?}?>">选择提现账户<span class="required"
                                                                                        aria-required="true">*</span>:</label>
                                        <div class="col-md-4">
                                            <select class="form-control"
                                                    name="SupplierBankAccountID"
                                                    <?if(!$BankAccount){?>style="border-color: #e73d4a;"<?}?>>
                                                <? foreach ($BankAccount as $account){ ?>
                                                    <option <?if ($account->bDefault == 1) {?>
                                                            selected
                                                            <?}?>
                                                            value="<?= $account->lID ?>"><?= $account->sName ?>
                                                    </option>
                                                <?}?>
                                            </select>
                                            <?if(!$BankAccount){?>
                                                <p class="help-block has-error">您还未设置提现账户，无法提现！点击<a href="javascript:;"
                                                                       onclick="parent.addTab($(this).text(), '/shop/supplierbankaccount/new')">设置提现账户</a></p>
                                            <? } ?>
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
        
        if ($.trim($("input[name='fMoney']").val()) == "" || $.trim($("input[name='fMoney']").val()) == 0) {
            error("请输入提现金额");
            return false;
        }

        if (!$("input[name='fMoney']").val().match(/^[0-9\.]{1,}$/)) {
            error("请输入数字");
            return false;
        }

        if (Number($("input[name='fMoney']").val()) < 500) {
            error("提现金额必须500元起");
            return false;
        }

        if(Number($.trim($("input[name='fMoney']").val())) > Number($("#mostMoney").text())){
            error("输入的提现金额超过可提现上限");
            return false;
        }

        <? if(!$BankAccount){ ?>
            error("请设置提现账户");
            return false;
        <? } ?>
        
        document.objectform.submit();
    }

    <? if ($_GET['save'] == 'yes') { ?>
    $(document).ready(
        function () {
            success('保存成功');
        }
    );

    <? } ?>
</script>