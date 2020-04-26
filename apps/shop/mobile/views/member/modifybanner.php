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

    </style>
<?php $this->endBlock() ?>
    <div class="refund_apply" id="app" v-cloak>
        <div class="ad_header">
            <a href="javascript:;" class="ad_back" onclick="goBack()">
                <span class="icon back">&#xe885;</span>
            </a>
            <h2>修改轮播图</h2>
        </div>
        <div class="oldLogo">
            <? if ($sImg) { foreach ($sImg as $img){?>
                <img src="/<?=$img ?>">
            <? }} ?>
        </div>
        <div class="apply_pic_list">
            <div class="apply_pic_item" v-for="(imgItem,index) in picList">
                <div class="upload_before" v-if="!imgList[index]">
                    <p>更换轮播图</p>
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
                    $.post('/member/modifybanner',
                        formData, function (data) {
                            if (data.status) {
                                shoperm.showTip(data.message);
                                location.href = '/member/modifybanner';
                            }
                        }, 'json');
                }

            }
        });
    </script>
<?php $this->endBlock() ?>