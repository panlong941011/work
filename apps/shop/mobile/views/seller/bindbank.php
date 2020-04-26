<?php $this->beginBlock('style') ?>
    <link rel="stylesheet" href="/css/supplierMain.css?<?= \Yii::$app->request->sRandomKey ?>">
<?php $this->endBlock() ?>
    <div class="bind">
        <div class="ad_header">
            <a href="javascript:;" class="ad_back" onclick="goBack()">
                <span class="icon">&#xe885;</span>
            </a>
            <h2>绑定银行卡</h2>
        </div>
        <div class="form_wrap">
            <form>
                <? if (\myerm\shop\common\models\MallConfig::getValueByKey('bBankCardVerify')) { ?>
                    <div class="bind_card_wrap flex">
                        <span>持卡人姓名</span>
                        <input type="text" name="" placeholder="请输入真实姓名" class="b_c_name" id="sName">
                    </div>
                    <div class="bind_card_wrap flex">
                        <span>银行卡号</span>
                        <input type="text" name="" placeholder="请输入银行卡号" class="b_c_number" id="sNumber">
                    </div>
                    <div class="bind_card_wrap bank flex">
                        <span>身份证号</span>
                        <input type="text" name="" placeholder="请输入身份证号" class="b_c_number" id="sIDCard">
                    </div>
                    <div class="bind_card_wrap flex">
                        <span>手机号</span>
                        <input type="text" name="" placeholder="请输入银行预留手机号" class="b_c_phone" id="sPhone">
                    </div>
                <? } else { ?>
                    <div class="bind_card_wrap flex">
                        <span>持卡人姓名</span>
                        <input type="text" name="" placeholder="请输入真实姓名" class="b_c_name" id="sName">
                    </div>
                    <div class="bind_card_wrap flex">
                        <span>银行卡号</span>
                        <input type="text" name="" placeholder="请输入银行卡号" class="b_c_number" id="sNumber">
                    </div>
                    <div class="bind_card_wrap bank flex">
                        <span>银行</span>
                        <input type="text" readonly="readonly" name="" placeholder="请选择银行" class="b_c_bank" id="sBank">
                    </div>
                <? } ?>
            </form>
            <a href="javascript:;" class="complete_btn">完成</a>
            <p class="bottom_tip">仅支持储蓄卡</p>
        </div>
    </div>

    <!-- 银行卡类型 -->
    <div class="bank_wrap">
        <div class="bank_type_wrap">
            <h2>银行类型</h2>
            <ul class="bank_list">
                <? foreach ($arrBank as $bank) { ?>
                    <li class="bank_type"><?= $bank->sName ?></li>
                <? } ?>
            </ul>
            <div class="bank_close">关闭</div>
        </div>
    </div>


    <div class="weui-loading_toast" style="display: none;">
        <div class="weui-mask_transparent"></div>
        <div class="weui-toast">
            <i class="weui-loading weui-icon_toast"></i>
        </div>
    </div>
<?php $this->beginBlock('foot') ?>
    <script>
        $(function () {
            //选择银行类型
            $('.bank').on('click', function () {
                $('.bank_wrap').show();
            })
            $('.bank_wrap,.bank_close').on('click', function () {
                $('.bank_wrap').hide();
            })
            $('.bank_type').on('click', function (event) {
                event.stopPropagation();
                var value = $(this).html();
                $(this).addClass('active').siblings().removeClass('active');
                $('.b_c_bank').val(value);
            })


            //提交
            $('.complete_btn').on('click', function () {
                var pattern = /^1\d{10}$/; //验证手机
                cardPattern = /^[0-9]{1,}$/;//验证卡号

                <? if (\myerm\shop\common\models\MallConfig::getValueByKey('bBankCardVerify')) { ?>
                var name = $.trim($('#sName').val()),
                    phone = $.trim($('#sPhone').val()),
                    card = $.trim($('#sNumber').val()),
                    idcard = $.trim($('#sIDCard').val()),
                    bank = null;
                ;

                if (name == '') {
                    shoperm.showTip("持卡人姓名不能为空");
                    return;
                }
                if (card == '') {
                    shoperm.showTip("银行卡号不能为空");
                    return;
                }
                if (!cardPattern.test(card)) {
                    shoperm.showTip("请输入正确的银行卡号");
                    return;
                }

                if (idcard == '') {
                    shoperm.showTip("请输入身份证号");
                    return;
                }

                if (phone == '') {
                    shoperm.showTip("请输入手机号");
                    return;
                }
                if (!pattern.test(phone)) {
                    shoperm.showTip("手机号格式不正确");
                    return;
                }
                <? } else { ?>

                var name = $.trim($('#sName').val()),
                    phone = null,
                    card = $.trim($('#sNumber').val()),
                    idcard = null,
                    bank = $.trim($('#sBank').val());

                if (name == '') {
                    shoperm.showTip("持卡人姓名不能为空");
                    return;
                }
                if (card == '') {
                    shoperm.showTip("银行卡号不能为空");
                    return;
                }
                if (!cardPattern.test(card)) {
                    shoperm.showTip("请输入正确的银行卡号");
                    return;
                }

                if (bank == '') {
                    shoperm.showTip("请选择银行");
                    return;
                }

                <? } ?>


                $(".weui-loading_toast").show();

                $.post(
                    '/seller/bindbank',
                    {
                        name: name,
                        phone: phone,
                        card: card,
                        idcard: idcard,
                        bank:bank
                    },
                    function (data) {

                        $(".weui-loading_toast").hide();

                        if (!data.status) {
                            shoperm.showTip(data.message);
                        } else {
                            location.href = "/seller";
                        }
                    }
                )
            })
        })
    </script>
<?php $this->endBlock() ?>