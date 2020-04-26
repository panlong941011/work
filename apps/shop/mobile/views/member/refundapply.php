<?php $this->beginBlock('style') ?>
    <link rel="stylesheet" href="/css/refundApply.css?<?= \Yii::$app->request->sRandomKey ?>">
<?php $this->endBlock() ?>
    <div class="refund_apply" id="app" v-cloak>
        <div class="ad_header">
            <a href="javascript:goBack();" class="ad_back">
                <span class="icon">&#xe885;</span>
            </a>
            <h2>申请退款</h2>
        </div>
        <section>
            <div class="apply_item flex" id="J_showType" @click="selectShow('type')">
                <div class="item_title"><span>*</span>退款类型：</div>
                <div class="item_content" v-text="refundType"></div>
            </div>
            <div class="apply_item flex" id="J_ShowActionSheet" @click="selectShow('reason')">
                <div class="item_title"><span>*</span>退款原因：</div>
                <div class="item_content" v-text="refundReason"></div>
            </div>
            <? if (!$unship) { ?>
                <div class="apply_price flex">
                    <div class="item_title"><span>*</span>商品总数量：</div>
                    <div class="item_content">
                        <input type="text" name="" id="totalnum" class="price"
                               value="<?= $defaultItem ?>" <? if ($unship) {
                            echo 'readonly';
                        } ?> v-on:input="changeAdvise" v-model="totalNum">
                    </div>
                </div>
                <div class="apply_price flex">
                    <div class="item_title"><span>*</span>退款数量：</div>
                    <div class="item_content">
                        <input type="text" name="" id="refundnum" class="price" <? if ($unship) {
                            echo 'readonly';
                        } ?> v-on:input="changeAdvise" v-model="refundNum">
                    </div>
                </div>
            <? } else { ?>
                <div class="apply_price flex">
                    <div class="item_title"><span>*</span>特别说明：</div>
                    <div class="item_content">
                        <input type="text" name="" class="price" value="商品未发货，申请全额退款">
                    </div>
                </div>
            <? } ?>
            <div class="apply_price flex">
                <div class="item_title"><span>*</span>退款金额：</div>
                <div class="item_content">
                    <i>¥</i>
                    <input type="text" name="" <? if ($unship) {
                        echo 'readonly';
                    } ?> id="price" class="price" v-model="refundPrice">
                </div>
            </div>
            <div class="price_max">（最多¥<em><?= number_format($fRefundMoney, 2) ?></em>）</div>
            <div class="apply_explain">
                <div class="item_title">退款说明：</div>
                <div class="item_content">
                    <textarea class="explain_word" maxlength="150" placeholder="最多150字"
                              v-model="refundExplain"></textarea>
                </div>
            </div>
            <div class="apply_pic_list flex">
                <div class="apply_pic_item" v-for="(imgItem,index) in picList">
                    <div class="upload_before" v-if="!imgList[index]">
                        <p>上传凭证</p>
                        <p>( 最多三张 )</p>
                        <input type="file" name="" accept="image/*" class="pic_file" @change="upImg(index)">
                    </div>
                    <div class="uploading" v-if="controlShow[index].picLoading">
                        <div class="sk-circle">
                            <div class="sk-circle1 sk-child"></div>
                            <div class="sk-circle2 sk-child"></div>
                            <div class="sk-circle3 sk-child"></div>
                            <div class="sk-circle4 sk-child"></div>
                            <div class="sk-circle5 sk-child"></div>
                            <div class="sk-circle6 sk-child"></div>
                            <div class="sk-circle7 sk-child"></div>
                            <div class="sk-circle8 sk-child"></div>
                            <div class="sk-circle9 sk-child"></div>
                            <div class="sk-circle10 sk-child"></div>
                            <div class="sk-circle11 sk-child"></div>
                            <div class="sk-circle12 sk-child"></div>
                        </div>
                        <div class="uploading_tip">上传中</div>
                    </div>
                    <div class="apply_pic" v-if="imgList[index]">
                        <img :src="imgList[index]">
                        <span class="close" @click="imgDelte(index)"></span>
                    </div>

                </div>
            </div>

        </section>
        <div class="submit_btn_wrap">
            <button class="submit_btn" :class="{off: isBtnOff}" @click="submitData">提交申请</button>
        </div>
        <!-- 退款选择组件 子传父用的自定义事件-->
        <select-part :selectname="parentMsg" @selected="returnVal">
            <select-part>
    </div>
    <div class="mask"></div>
<?php $this->beginBlock('foot') ?>
    <script src="/js/jquery.min.js"></script>
    <script src="/js/ydui.js"></script>
    <script src="/js/lrz.all.bundle.js"></script>
    <script src="/js/template.js"></script>
    <script>
        $(function () {
            <? if($RefundID){?>
            var url = '/member/refunddetail?id=<?=$RefundID?>';
            location.replace(url);
            <? }?>
            //ydUI的用法
            var $myAs = $('#J_ActionSheet');

            $('#J_ShowActionSheet,#J_showType').on('click', function () {
                $myAs.actionSheet('open');
            });
            /*关闭弹框*/
            $('#J_Cancel').on('click', function () {
                $myAs.actionSheet('close');
            });

            $('.popup').on('click', '.reason_item', function () {

                setTimeout(function () {
                    $myAs.actionSheet('close');
                }, 200)
            });


            var warpTop = $('.submit_btn_wrap').offset().top,
                btnTop = $('.submit_btn').offset().top;
            //处理手机端点击输入框  页面固定结构弹出问题
            $('.explain_word,.price').on('focus', function () {
                if (/Android/.test(navigator.appVersion) || /iPhone/.test(navigator.appVersion)) {

                    setTimeout(function () {
                        $('.submit_btn').css('position', 'relative');
                        $('.submit_btn_wrap').css('marginTop', btnTop - warpTop + 50);
                    }, 200)
                }
            })
            $('.explain_word,.price').on('blur', function () {

                if (/Android/.test(navigator.appVersion) || /iPhone/.test(navigator.appVersion)) {

                    setTimeout(function () {
                        $('.submit_btn').css('position', 'fixed');
                        $('.submit_btn_wrap').css('marginTop', 0);
                    }, 200)
                }
            })
        })
    </script>
    <script>
        console.log(popup);

        new Vue({
            el: "#app",
            components: {
                'select-part': popup
            },
            data: {
                refundType: '请选择退款类型', //退款类型
                refundReason: '请选择退款原因',//退款原因
                refundPrice: '<?=number_format($defaultMoney, 2, ".", "")?>',//退款金额
                maxRefundPrice: '<?=number_format($fRefundMoney, 2, ".", "")?>',//最大退款金额
                totalNum: '<?= $defaultItem?>', //商品总数量
                refundNum: '<?= $defaultItem?>',//退款数量
                refundExplain: '',//退款说明
                imgList: [], //存储图片 传给后端的图片数据
                unship: '<?= $unship?>', //商品是否已发货
                controlShow: [  //控制加载图显示隐藏
                    {picLoading: false},
                    {picLoading: false},
                    {picLoading: false}
                ],
                num: 6, // 任意值 用于添加图片时 检测数组的值变化
                picList: [0], //循环图片列表
                isItem: 'type', //判断当前点击的是哪个类型
                typeData: { //选择的数据格式
                    'title': '退款类型',
                    'data': [{'isMark': false, 'name': '仅退款'}<? if ($orderDetail->dShipDate) { ?>, {
                        'isMark': false,
                        'name': '退货退款'
                    }<? } ?>]
                },
                reasonData: {
                    'title': '退款原因',
                    'data': [
                        {'isMark': false, 'name': '买错/多买/不想要'},
                        {'isMark': false, 'name': '商品破损/少件'},
                        {'isMark': false, 'name': '商家发错货'},
                        {'isMark': false, 'name': '缺货'},
                        {'isMark': false, 'name': '其他'}
                    ]
                },
                parentMsg: {}, //给子级传值的数组
                isBtnOff: false,
            },
            mounted: function () {
                this.init();
            },
            methods: {

                //计算建议金额
                changeAdvise: function () {
                    var totalnum = Number(this.totalNum);
                    var refundnum = Number(this.refundNum);

                    if (totalnum && refundnum) {
                        var totalprice = <?=$orderDetail->fTotal?>;
                        var fTotalShip = <?=$fTotalShip?>;
                        var ship = 0;
                        if (refundnum > totalnum) {
                            refundnum = totalnum;
                            $('#refundnum').val(refundnum);
                        }
                        var refundprice = (totalprice + fTotalShip) * (refundnum / totalnum);
                        this.refundPrice = refundprice.toFixed(2);
                    }

                },

                //初始化
                init: function () {
                    //图片初始化
                    for (var i = 0; i < this.imgList.length; i++) {
                        if (i < 2) {
                            this.picList.push(this.num++);
                        }

                    }

                    //类型初始化
                    var len = this.typeData.data.length;
                    for (var j = 0; j < len; j++) {
                        if (this.typeData.data[j].name == this.refundType) {
                            this.typeData.data[j].isMark = true;
                        }
                    }

                    //原因型初始化
                    var len2 = this.reasonData.data.length;
                    for (var k = 0; k < len2; k++) {
                        if (this.reasonData.data[j].name == this.refundReason) {
                            this.reasonData.data[j].isMark = true;
                        }
                    }

                },
                //弹出选择列表
                selectShow: function (key) {
                    if (key == 'type') {
                        this.parentMsg = this.typeData;
                        this.isItem = 'type';
                    }
                    if (key == 'reason') {
                        this.parentMsg = this.reasonData;
                        this.isItem = 'reason';
                    }
                },
                //自定义事件
                returnVal: function (val) {
                    if (this.isItem == 'type') {
                        this.refundType = val;
                    }
                    if (this.isItem == 'reason') {
                        this.refundReason = val;
                    }
                },
                //上传图片
                upImg: function (index) {
                    var _this = this;

                    _this.controlShow[index].picLoading = true; //加载动画显示

                    var files = event.currentTarget.files[0];

                    lrz(files)
                        .then(function (rst) {
                            // 处理成功会执行

                            _this.addImg(rst.base64, index);
                        })
                        .catch(function (err) {
                            console.log(err);
                            // 处理失败会执行

                            shoperm.showTip('上传正确图片格式');

                            return;
                        })
                        .always(function () {
                            // 不管是成功失败，都会执行
                            _this.controlShow[index].picLoading = false;
                        });
                },
                //添加图片
                addImg: function (dataUrl, index) {
                    var self = this;

                    if (self.picList.length < 3) {
                        self.picList.push(self.num++);
                    }

                    self.imgList.push(dataUrl);
                },
                //删除图片
                imgDelte: function (index) {
                    $('.repic_file').eq(index).val(''); //解决删除图片后 改图不能再上传问题

                    this.imgList.splice(index, 1);

                    if (this.imgList.length === 0) {
                        this.imgList = [];
                        this.picList = [];
                        this.picList.push(0);
                    }

                    if (this.imgList.length === 1) {
                        this.picList.splice(index, 1);
                    }
                },
                submitData: function () {
                    var _this = this;
                    var pattern = /^\d+(\.\d+)?$/; //小数或整数
                    var refundprice = $('#price').val();
                    _this.refundPrice = refundprice;
                    if (_this.refundType == "请选择退款类型") {
                        shoperm.showTip('请选择退款类型');
                        return;
                    }
                    if (_this.refundReason == "请选择退款原因") {
                        shoperm.showTip('请选择退款原因');
                        return;
                    }
                    if (_this.totalNum == '') {
                        shoperm.showTip('商品总数量不得为空');
                        return;
                    }
                    if (Math.ceil(_this.totalNum) != _this.totalNum) {
                        shoperm.showTip('商品总数量必须为整数');
                        return;
                    }
                    if (_this.refundNum == '') {
                        shoperm.showTip('退款数量不得为空');
                        return;
                    }
                    if (Math.ceil(_this.refundNum) != _this.refundNum) {
                        shoperm.showTip('退款数量必须为整数');
                        return;
                    }
                    if (Number(_this.refundNum) > Number(_this.totalNum)) {
                        shoperm.showTip('退款数量不得大于商品总数量');
                        return;
                    }
                    if (_this.refundPrice == '') {
                        shoperm.showTip('退款金额不得为空');
                        return;
                    }

                    if (!pattern.test(_this.refundPrice)) {
                        shoperm.showTip('请输入正确的退款金额');
                        return;
                    }
                    if (parseFloat(_this.refundPrice) > parseFloat(_this.maxRefundPrice)) {
                        shoperm.showTip('退款金额超出最大值');
                        return;
                    }
                    _this.isBtnOff = true;
                    $('.weui-loading-toast').show();
                    $.post('/member/refundapply?id=<?=$_GET['id']?>',
                        {
                            'type': _this.refundType,
                            '_csrf': '<?=\Yii::$app->request->getCsrfToken()?>',
                            'reason': _this.refundReason,
                            'money': _this.refundPrice,
                            'explain': _this.refundExplain,
                            'imglist': _this.imgList,
                            'totalnum': _this.totalNum,
                            'refundnum': _this.refundNum,
                            'unship': _this.unship
                        }, function (data) {
                            if (data.status) {
                                $('.weui-loading-toast').hide();
                                var url = '/member/refunddetail?id=' + data.id;
                                location.replace(url);
                            } else {
                                var url = '/member/order?id=' + data.id;
                                location.replace(url);
                            }
                        }, 'json');
                }
            }
        })

    </script>
<?php $this->endBlock() ?>