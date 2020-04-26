<?php $this->beginBlock('style') ?>
    <link rel="stylesheet" href="/css/refundApply.css">
    <style>
        .apply_pic_list .apply_pic {
            width: 100%;
            height: 100%;
            -webkit-border-radius: .1066666667rem;
            border-radius: .1066666667rem;
            overflow: hidden;
        }

        .apply_pic_list .apply_pic_item {
            width: 3.2rem;
            height: 3.2rem;
            -webkit-border-radius: .1066666667rem;
            border-radius: .1066666667rem;
            border: .1066666667rem dashed #eee;
            position: relative;
            margin-right: .2133333333rem;
            float: left;
            margin-top: 0.5rem;
        }

        .paginations button {
            display: block;
            width: 14.72rem;
            height: 1.92rem;
            -webkit-border-radius: .2133333333rem;
            border-radius: .2133333333rem;
            background: #3394FF;
            text-align: center;
            line-height: 1.92rem;
            color: #fff;
            margin: 0 auto;
            margin-bottom: .64rem;
            font-size: 30px;
        }

        .ewm_wrap {
            height: 15rem;
            width: 11.7rem;
            margin-left: auto;
            margin-right: auto;
            background: url("/images/home/shopcode.jpg") no-repeat;
            background-size: 100%;
        }

        .shopname {
            clear: both;
            background-color: #fff;
            width: 9rem;
            margin-left: 1rem;
            margin-top: 3rem;
            height: 1.8rem;
            border-radius: 0.3rem;
            font-size: 40px;
            font-weight: bold;
            line-height: 1.8rem;
            text-align: center;
        }

        #qrcode {
            height: 5rem;
            width: 5rem;
            margin-left: auto;
            margin-right: auto;
            margin-top: 1.5rem;
        }
        .msg{
            clear: both;
            width: 100%;
            margin-top: 0.5rem;
            height: 1.3rem;
            font-size: 30px;
            line-height: 1.3rem;
            text-align: center;
        }
        .ewm_canvas{
            margin-left: auto;
            margin-right: auto;
            height: 15rem;
            width: 11rem;
        }
    </style>
<?php $this->endBlock() ?>
    <div class="refund_apply" id="app" v-cloak>
        <div class="ad_header">
            <a href="javascript:;" class="ad_back" onclick="goBack()">
                <span class="icon back">&#xe885;</span>
            </a>
            <h2>修改店铺二维码</h2>
        </div>
        <div class="apply_pic_list">
            <div class="apply_pic_item" v-for="(imgItem,index) in picList">
                <div class="upload_before" v-if="!imgList[index]">
                    <p>更换店铺二维码</p>
                    <input type="file" name="" accept="image/*" class="pic_file" @change="upImg(index)">
                </div>
                <div class="uploading" v-if="controlShow[index].picLoading">
                    <div class="sk-circle">
                        <div class="sk-circle1 sk-child"></div>
                        <div class="sk-circle2 sk-child"></div>
                        <div class="sk-circle3 sk-child"></div>
                    </div>
                    <div class="uploading_tip">上传中</div>
                </div>
                <div class="apply_pic" v-if="imgList[index]">
                    <img :src="imgList[index]">
                    <span class="close" @click="imgDelte(index)"></span>
                </div>

            </div>
        </div>
        <div style="height:3rem;width: 100%;clear:both;"></div>
        <div class="paginations">
            <button class="submit" data-finished="finished" @click="submitData">提交
            </button>
        </div>
    </div>
    <div class="mask"></div>
    <div class="ewm_wrap" id="ewmWrap">
        &nbsp;
        <div class="shopname">
            <?=$shop->sName?>
        </div>
        <div class="ewm_pic" id="qrcode">
            <? if ($sLogoPath) { ?>
                <img src="/<?= $sLogoPath ?>">
            <? } ?>
        </div>
    </div>
    <div class="ewm_canvas"></div>
    <div class="msg">长按可保存图片</div>
    <script src='/js/jquery.min.js'></script>
    <script src="/js/qrcode.min.js"></script>
    <!-- 1.0.0-alpha.8 -->
    <script src="/js/html2canvas.js"></script>
    <script src="/js/canvas2image.js"></script>
<?php $this->beginBlock('foot') ?>
    <script src="/js/jquery.min.js"></script>
    <script src="/js/lrz.all.bundle.js"></script>
    <script src="/js/template.js"></script>
    <script>
        new Vue({
            el: "#app",
            data: {
                imgList: [], //存储图片 传给后端的图片数据
                controlShow: [  //控制加载图显示隐藏
                    {picLoading: false},
                    {picLoading: false},
                    {picLoading: false}
                ],
                num: 6, // 任意值 用于添加图片时 检测数组的值变化
                picList: [0], //循环图片列表
                parentMsg: {}, //给子级传值的数组
                isBtnOff: false,
            },
            mounted: function () {
                this.init();
            },
            methods: {
                //初始化
                init: function () {
                    //图片初始化
                    for (var i = 0; i < this.imgList.length; i++) {
                        if (i < 2) {
                            this.picList.push(this.num++);
                        }

                    }
                },

                //自定义事件
                returnVal: function (val) {
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
                    var formData = {
                        imglist: _this.imgList,
                        _csrf: '<?=\Yii::$app->request->getCsrfToken()?>'
                    };
                    console.log(formData);
                    _this.isBtnOff = true;
                    $.post('/member/modifyqrcode',
                        formData, function (data) {
                            if (data.status) {
                                shoperm.showTip(data.message);
                                location.href = '/member/modifyqrcode';
                            }
                        }, 'json');
                }

            }
        });
    </script>
    <script>
        <? if (!$sLogoPath) { ?>
        //安卓个别机子二维码显示问题（偏大）2019/3/11 wqw
        var qrcodeWidth = $(".ewm_wrap .ewm_pic").width();
        //生成二维码
        var qrcode = new QRCode(document.getElementById("qrcode"), {
            text: '<?='https://yl.aiyolian.cn/shop'.$shop->lID.'/home'?>',
            width: qrcodeWidth,
            height: qrcodeWidth,
            // correctLevel:QRCode.CorrectLevel.L

        });
        <?}?>
        //生成图片
        var width = $(".ewm_wrap").innerWidth(); //获取二维码dom的 宽高
        var height = $(".ewm_wrap").innerHeight();
        var canvas = document.createElement("canvas"); //新建画布
        //要将 canvas 的宽高设置成容器宽高的 2 倍，处理手机上模糊问题
        canvas.width = width * 2;
        canvas.height = height * 2;
        canvas.getContext("2d").scale(2, 2); //初始化2倍
        var opts = {
            scale: 2,
            canvas: canvas,
            width: width,
            height: height,
            useCORS: true //允许图片跨域 需要后端配合
        };

        html2canvas(document.getElementById('ewmWrap'), opts)
            .then(
                function (canvas) {
                    //画图转图片的插件 Canvas2Image，转为base64
                    var img = Canvas2Image.convertToImage(canvas, canvas.width, canvas.height);
                    $('.ewm_canvas').append(img);
                    $(".ewm_canvas").find("img").css({
                        "width": canvas.width / 2 + "px",
                        "height": canvas.height / 2 + "px",
                    })
                    $('#ewmWrap').hide();
                });
    </script>
<?php $this->endBlock() ?>