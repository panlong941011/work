<?php $this->beginBlock('style') ?>
    <link rel="stylesheet" href="/css/refundApply.css?<?=\Yii::$app->request->sRandomKey?>">
<?php $this->endBlock() ?>
    <div class="refund_apply" id="app" v-cloak>
        <div class="ad_header">
            <a href="javascript:;" onclick="goBack()" class="ad_back">
                <span class="icon">&#xe885;</span>
            </a>
            <h2>修改退货物流信息</h2>
        </div>
        <section>
            <div class="apply_item flex" id="J_ShowActionSheet">
                <div class="item_title"><span>*</span>物流公司：</div>
                <div class="item_content" v-text="refundCompany">请选择物流公司</div>
            </div>
            <div class="apply_shipno flex">
                <div class="item_title"><span>*</span>快递单号：</div>
                <div class="item_content">
                    <input type="tel" name="" id="shipno" class="shipno" v-model="refundShipno" placeholder="请填写快递单号">
                </div>
            </div>
            <div class="apply_phone flex">
                <div class="item_title"><span>*</span>手机号：</div>
                <div class="item_content">
                    <input type="tel" name="" id="phone" class="phone" v-model="refundPhone"
                           placeholder="请填写手机号方便卖家联系您">
                </div>
            </div>
            <div class="apply_pic_list flex">
                <div class="apply_pic_item" v-for="(imgItem,index) in picList">
                    <div class="upload_before" v-if="!imgList[index]">
                        <p>上传凭证</p>
                        <p>( 最多三张 )</p>
                        <input type="file" name="" accept="image/*" capture="camera" class="pic_file"
                               @change="upImg(index)">
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
            <button class="submit_btn" :class="{off: isBtnOff}" @click="submitData()">提交</button>
        </div>

        <div class="m-actionsheet popup logistics_company" id="J_ActionSheet">
            <h2>物流公司</h2>
            <ul>
                <li class="reason_item" v-for="(company,index) in logisticsCompany" :class="{active: company.isMark }"
                    v-text="company.name" @click="isChose(index)"></li>
            </ul>
            <div class="popup_cancel logistics_btn" id="J_Cancel">关闭</div>
        </div>
    </div>

    <div class="mask"></div>

<?php $this->beginBlock('foot') ?>
    <script src="/js/jquery.min.js"></script>
    <script src="/js/ydui.js"></script>
    <script src="/js/lrz.all.bundle.js"></script>
    <script>
        $(function () {
            var $myAs = $('#J_ActionSheet');

            $('#J_ShowActionSheet').on('click', function () {
                $myAs.actionSheet('open');
            });

            $('#J_Cancel').on('click', function () {
                $myAs.actionSheet('close');
            });


            var warpTop = $('.submit_btn_wrap').offset().top,
            btnTop = $('.submit_btn').offset().top;
            $('.shipno, .phone').on('focus', function () {

                if (/Android/.test(navigator.appVersion) || /iPhone/.test(navigator.appVersion)) {
                    setTimeout(function() {
                        $('.submit_btn').css('position', 'relative');
                        $('.submit_btn_wrap').css('marginTop', btnTop - warpTop + 50);
                     },200)
                }
            })

            $('.shipno, .phone').on('blur', function () {

                if (/Android/.test(navigator.appVersion) || /iPhone/.test(navigator.appVersion)) {

                    setTimeout(function() {
                         $('.submit_btn').css('position', 'fixed');
                        $('.submit_btn_wrap').css('marginTop', 0);
                    },200)
                }
            })
        })
    </script>
    <script>
        new Vue({
            el: "#app",
            components: {
                //'select-part': part
            },
            data: {
                refundCompany: '<?=$refund->expressCompany->sName?>', //退款物流
                refundShipno: '<?=$refund->sShipNo?>',//退款订单号
                refundPhone: '<?=$refund->sMobile?>',//退款手机
                imgList: <?=$imgList?>, //存储图片 传给后端的图片数据
                controlShow: [  //控制加载图显示隐藏
                    {picLoading: false},
                    {picLoading: false},
                    {picLoading: false}
                ],
                num: 6, // 任意值 用于添加图片时 检测数组的值变化
                picList: [0], //循环图片列表
                logisticsCompany: [
                    <? foreach ($arrCompany as $c) { ?>
                    {'isMark': <? if ($c->sName == $refund->expressCompany->sName) { ?>true<? } else { ?>false<? } ?>, 'name': '<?=$c->sPinYin?>-<?=$c->sName?>'},
                    <? } ?>
                ],
                parentMsg: {}, //给子级传值的数组
                isBtnOff: false,
            },
            mounted: function () {
                this.init();
            },
            methods: {
                //初始化
                init: function () {

                    for (var i = 0; i < this.imgList.length; i++) {
                        if (i < 2) {
                            this.picList.push(this.num++);
                        }

                    }
                },
                //选择物流公司
                isChose: function (index) {
                    var len = this.logisticsCompany.length;
                    for (var i = 0; i < len; i++) {
                        this.logisticsCompany[i].isMark = false;
                    }
                    this.logisticsCompany[index].isMark = true;
                    this.refundCompany = this.logisticsCompany[index].name;
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

                            console.log('上传正确图片格式');
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
                    var pattern = /^1\d{10}$/; //验证手机
                    var shipNo = /^[0-9]{1,}$/;  //验证快递单号

                    if (_this.refundCompany == '请选择物流公司') {
                        shoperm.showTip('请选择物流公司');
                        return;
                    }

                    if (_this.refundShipno == '') {
                        shoperm.showTip('请填写快递单号');
                        return;
                    }

                    if (!shipNo.test(_this.refundShipno)) {
                        shoperm.showTip('快递单号有误');
                        return;
                    }

                    if (_this.refundPhone == '') {
                        shoperm.showTip('手机号不得为空');
                        return;
                    }

                    if (!pattern.test(_this.refundPhone)) {
                        shoperm.showTip('请输入正确的手机号');
                        return;
                    }

                    $('.weui-loading-toast').show();
                    _this.isBtnOff = true;
                    //后端接口及参数
                    $.post(
                        '/member/modifyrefundship?id=<?=$_GET['id']?>',
                        {
                            company: _this.refundCompany,
                            shipno: _this.refundShipno,
                            mobile: _this.refundPhone,
                            imglist: _this.imgList,
                            '_csrf': '<?=\Yii::$app->request->getCsrfToken()?>',
                        },
                        function (data) {
                            $('.weui-loading-toast').hide();
                            if (data.status) {
                                shoperm.showTip('提交成功');
                                setTimeout(function() {
                                     history.go(-1);

                                },2000)
                            }
                        }
                    )
                },
            }
        })
    </script>
<?php $this->endBlock() ?>