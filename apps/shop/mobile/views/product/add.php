<?php $this->beginBlock('style') ?>
    <link rel="stylesheet" href="/css/refundApply.css?1">
    <style>
        .popup .ul_area li {
            height: 1.5rem;
            border-bottom: 1px solid #eeeeee;
            font-size: 0.6rem;
            line-height: 1.5rem;
        }

        .popup .ul_area li .ecity2 {
            float: left;
        }

        .popup .ul_area li .provinceList {
            float: left;
            margin-left: 0.5rem;
        }

        input[type="checkbox"] {
            margin-left: 0.1rem;
            -webkit-appearance: checkbox;
            height: 0.5rem;
            width: 0.5rem;
        }

        #showArea {
            min-height: 2rem;
            clear: both;
            width: 100%;
            font-size: 0.6rem;
            line-height: 1rem;
            margin-bottom: 4rem;
        }

        #showArea span {
            padding: 0.2rem;
        }

        .submit_btn {
            bottom: 0.5rem;
        }


    </style>
<?php $this->endBlock() ?>
    <div class="refund_apply" id="app" v-cloak>
        <div class="ad_header">
            <a href="javascript:goBack();" class="ad_back">
                <span class="icon">&#xe885;</span>
            </a>
            <h2>发布商品</h2>
        </div>
        <section>
            <div class="apply_price flex">
                <div class="item_title">商品名称：</div>
                <div class="item_content">
                    <input type="text" class="price" v-model="product.sName" placeholder="请输入商品名称">
                </div>
            </div>
            <div class="apply_price flex">
                <div class="item_title">库存：</div>
                <div class="item_content">
                    <input type="text" class="price" v-model="product.lStock" placeholder="请输入商品库存">
                </div>
            </div>
            <div class="apply_price flex">
                <div class="item_title">供货价(包邮)：</div>
                <div class="item_content">
                    <i>¥</i>
                    <input type="text" class="price" v-model="product.fSupplierPrice" placeholder="请输入供货价">
                </div>
            </div>
            <div class="apply_price flex">
                <div class="item_title">促销价(包邮)：</div>
                <div class="item_content">
                    <i>¥</i>
                    <input type="text" class="price" v-model="product.fPrice" placeholder="请输入促销价">
                </div>
            </div>
            <div class="item_title" style="height:1rem;clear: both;padding-left: 0.64rem">轮播图(尺寸 1:1)</div>
            <div class="apply_pic_list flex">
                <div class="apply_pic_item" v-for="(imgItem,index) in picList">
                    <div class="upload_before" v-if="!imgList[index]">
                        <p>上传图片</p>
                        <p>( 最多五张 )</p>
                        <input type="file" accept="image/*" class="pic_file" @change="upImg(index,'img')">
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
                        <span class="close" @click="imgDelte(index,'img')"></span>
                    </div>

                </div>
            </div>
            <div class="item_title" style="height:1rem;clear: both;padding-left: 0.64rem;margin-top: 1rem;">详情图</div>
            <div class="apply_pic_list flex">
                <div class="apply_pic_item" v-for="(imgItem,index) in graphList">
                    <div class="upload_before" v-if="!photoList[index]">
                        <p>上传图片</p>
                        <input type="file" accept="image/*" class="pic_file" @change="upImg(index,'photo')">
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
                    <div class="apply_pic" v-if="photoList[index]">
                        <img :src="photoList[index]">
                        <span class="close" @click="imgDelte(index,'photo')"></span>
                    </div>

                </div>
            </div>
            <div class="apply_item flex" id="J_showType" @click="selectArea()">
                <div class="item_title">不发货地区：</div>
                <div class="item_content" v-text="area"></div>
            </div>
        </section>
        <div class="submit_btn_wrap">
            <button class="submit_btn" :class="{off: isBtnOff}" @click="submitData">提交</button>
        </div>

    </div>
    <div class="mask"></div>
    <div id="showArea">
        <span>港澳台海外,默认不发货</span><br>
    </div>
    <div id="div_area" class="m-actionsheet popup actionsheet-toggle" style="display: none">
        <h2>不发货地区</h2>
        <ul class="ul_area">
            <li>
                <div class="clearfix">
                    <div class="ecity2">
                        <input type="checkbox" value="310000,320000,330000,340000,360000" id="J_Group0" class="parea">
                        <label for="J_Group0">华东</label>
                    </div>
                    <div class="provinceList">
                        <div class="ecity2">
                            <span>
                                <input type="checkbox" value="310000" id="J_Province2_310000">
                                <label for="J_Province2_310000">上海</label>
                            </span>
                        </div>
                        <div class="ecity2">
                            <span>
                                <input type="checkbox" value="320000" id="J_Province2_320000">
                                <label for="J_Province2_320000">江苏</label>
                            </span>
                        </div>
                        <div class="ecity2">
                            <span>
                                <input type="checkbox" value="330000" id="J_Province2_330000">
                                <label for="J_Province2_330000">浙江</label>
                            </span>
                        </div>
                        <div class="ecity2">
                            <span>
                                <input type="checkbox" value="340000" id="J_Province2_340000">
                                <label for="J_Province2_340000">安徽</label>
                            </span>
                        </div>
                        <div class="ecity2">
                            <span>
                                <input type="checkbox" value="360000" id="J_Province2_360000">
                                <label for="J_Province2_360000">江西</label>
                            </span>
                        </div>
                    </div>
                </div>
            </li>
            <li>
                <div class="clearfix">
                    <div class="ecity2">
                        <input type="checkbox" value="110000,120000,140000,370000,130000,150000" id="J_Group1"
                               class="parea">
                        <label for="J_Group1">华北</label>
                    </div>
                    <div class="provinceList">
                        <div class="ecity2">
                            <span>
                                <input type="checkbox" value="110000" id="J_Province2_110000">
                                <label for="J_Province2_110000">北京</label>
                            </span>
                        </div>
                        <div class="ecity2">
                            <span>
                                <input type="checkbox" value="120000" id="J_Province2_120000">
                                <label for="J_Province2_120000">天津</label>
                            </span>
                        </div>
                        <div class="ecity2">
                            <span>
                                <input type="checkbox" value="140000" id="J_Province2_140000">
                                <label for="J_Province2_140000">山西</label>
                            </span>
                        </div>
                        <div class="ecity2">
                            <span>
                                <input type="checkbox" value="370000" id="J_Province2_370000">
                                <label for="J_Province2_370000">山东</label>
                            </span>
                        </div>
                        <div class="ecity2">
                            <span>
                                <input type="checkbox" value="130000" id="J_Province2_130000">
                                <label for="J_Province2_130000">河北</label>
                            </span>
                        </div>
                        <div class="ecity2">
                            <span>
                                <input type="checkbox" value="150000" id="J_Province2_150000">
                                <label for="J_Province2_150000">内蒙古</label>
                            </span>
                        </div>
                    </div>
                </div>
            </li>
            <li>
                <div class="clearfix">
                    <div class="ecity2">
                        <input type="checkbox" value="430000,420000,410000" id="J_Group2" class="parea">
                        <label for="J_Group2">华中</label>
                    </div>
                    <div class="provinceList">
                        <div class="ecity2">
                            <span>
                                <input type="checkbox" value="430000" id="J_Province2_430000">
                                <label for="J_Province2_430000">湖南</label>
                            </span>
                        </div>
                        <div class="ecity2">
                            <span>
                                <input type="checkbox" value="420000" id="J_Province2_420000">
                                <label for="J_Province2_420000">湖北</label>
                            </span>
                        </div>
                        <div class="ecity2">
                            <span>
                                <input type="checkbox" value="410000" id="J_Province2_410000">
                                <label for="J_Province2_410000">河南</label>
                            </span>
                        </div>
                    </div>
                </div>
            </li>
            <li>
                <div class="clearfix">
                    <div class="ecity2">
                        <input type="checkbox" value="440000,450000,350000,460000" id="J_Group3" class="parea">
                        <label for="J_Group3">华南</label>
                    </div>
                    <div class="provinceList">
                        <div class="ecity2">
                            <span>
                                <input type="checkbox" value="440000" id="J_Province2_440000">
                                <label for="J_Province2_440000">广东</label>
                            </span>
                        </div>
                        <div class="ecity2">
                            <span>
                                <input type="checkbox" value="450000" id="J_Province2_450000">
                                <label for="J_Province2_450000">广西</label>
                            </span>
                        </div>
                        <div class="ecity2">
                            <span>
                                <input type="checkbox" value="350000" id="J_Province2_350000">
                                <label for="J_Province2_350000">福建</label>
                            </span>
                        </div>
                        <div class="ecity2">
                            <span>
                                <input type="checkbox" value="460000" id="J_Province2_460000">
                                <label for="J_Province2_460000">海南</label>
                            </span>
                        </div>
                    </div>
                </div>
            </li>
            <li>
                <div class="clearfix">
                    <div class="ecity2">
                        <input type="checkbox" value="210000,220000,230000" id="J_Group4" class="parea">
                        <label for="J_Group4">东北</label>
                    </div>
                    <div class="provinceList">
                        <div class="ecity2">
                            <span>
                                <input type="checkbox" value="210000" id="J_Province2_210000">
                                <label for="J_Province2_210000">辽宁</label>
                            </span>
                        </div>
                        <div class="ecity2">
                            <span>
                                <input type="checkbox" value="220000" id="J_Province2_220000"
                                >
                                <label for="J_Province2_220000">吉林</label>
                            </span>
                        </div>
                        <div class="ecity2">
                            <span>
                                <input type="checkbox" value="230000" id="J_Province2_230000"
                                >
                                <label for="J_Province2_230000">黑龙江</label>
                            </span>
                        </div>
                    </div>
                </div>
            </li>
            <li>
                <div class="clearfix">
                    <div class="ecity2">
                        <input type="checkbox" value="610000,650000,620000,640000,630000" id="J_Group5" class="parea">
                        <label for="J_Group5">西北</label>
                    </div>
                    <div class="provinceList">
                        <div class="ecity2">
                            <span>
                                <input type="checkbox" value="610000" id="J_Province2_610000"
                                >
                                <label for="J_Province2_610000">陕西</label>
                            </span>
                        </div>
                        <div class="ecity2">
                            <span>
                                <input type="checkbox" value="650000" id="J_Province2_650000"
                                >
                                <label for="J_Province2_650000">新疆</label>
                            </span>
                        </div>
                        <div class="ecity2">
                            <span>
                                <input type="checkbox" value="620000" id="J_Province2_620000"
                                >
                                <label for="J_Province2_620000">甘肃</label>
                            </span>
                        </div>
                        <div class="ecity2">
                            <span>
                                <input type="checkbox" value="640000" id="J_Province2_640000"
                                >
                                <label for="J_Province2_640000">宁夏</label>
                            </span>
                        </div>
                        <div class="ecity2">
                            <span>
                                <input type="checkbox" value="630000" id="J_Province2_630000"
                                >
                                <label for="J_Province2_630000">青海</label>
                            </span>
                        </div>
                    </div>
                </div>
            </li>
            <li>
                <div class="clearfix">
                    <div class="ecity2">
                        <input type="checkbox" value="500000,530000,520000,540000,510000" id="J_Group6"
                               class="parea">
                        <label for="J_Group6">西南</label>
                    </div>
                    <div class="provinceList">
                        <div class="ecity2">
                                        <span>
                                            <input type="checkbox" value="500000" id="J_Province2_500000"
                                            >
                                            <label for="J_Province2_500000">重庆</label>
                                        </span>
                        </div>
                        <div class="ecity2">
                                        <span>
                                            <input type="checkbox" value="530000" id="J_Province2_530000"
                                            >
                                            <label for="J_Province2_530000">云南</label>
                                        </span>
                        </div>
                        <div class="ecity2">
                                        <span>
                                            <input type="checkbox" value="520000" id="J_Province2_520000"
                                            >
                                            <label for="J_Province2_520000">贵州</label>
                                        </span>
                        </div>
                        <div class="ecity2">
                                        <span>
                                            <input type="checkbox" value="540000" id="J_Province2_540000"
                                            >
                                            <label for="J_Province2_540000">西藏</label>
                                        </span>
                        </div>
                        <div class="ecity2">
                                        <span>
                                            <input type="checkbox" value="510000" id="J_Province2_510000"
                                            >
                                            <label for="J_Province2_510000">四川</label>
                                        </span>
                        </div>
                    </div>
                </div>
            </li>
        </ul>
        <div id="areaCancel" class="popup_cancel">关闭</div>
    </div>
<?php $this->beginBlock('foot') ?>
    <script src="/js/jquery.min.js"></script>
    <script src="/js/ydui.js"></script>
    <script src="/js/lrz.all.bundle.js"></script>
    <script>
        $(function () {
            <? if($RefundID){?>
            var url = '/member/refunddetail?id=<?=$RefundID?>';
            location.replace(url);
            <? }?>
            //ydUI的用法
            var $myAs = $('#J_ActionSheet');

            $('#J_ShowActionSheet').on('click', function () {
                $myAs.actionSheet('open');
            });
            /*关闭弹框*/
            $('#J_Cancel').on('click', function () {
                $myAs.actionSheet('close');
            });
            /*关闭弹框*/
            $('#areaCancel').on('click', function () {
                $('#div_area').hide();
                $('.mask').hide();
            });
            $('.mask').on('click', function () {
                $('#div_area').hide();
                $('.mask').hide();
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
        new Vue({
            el: "#app",
            data: {
                area: '请选择不发货区域', //退款类型
                imgList: [], //轮播图
                picList: [0], //轮播图
                photoList: [], //详情图
                graphList: [0], //详情图
                controlShow: [  //控制加载图显示隐藏
                    {picLoading: false},
                    {picLoading: false},
                    {picLoading: false},
                    {picLoading: false},
                    {picLoading: false},
                    {picLoading: false}
                ],
                num: 6, // 任意值 用于添加图片时 检测数组的值变化
                parentMsg: {}, //给子级传值的数组
                isBtnOff: false,
                product: <?=$product?>,
                arrArea: {
                    'title': '请选择不发货地址',
                    'data': []
                }

            },
            mounted: function () {
                this.init();
            },
            methods: {
                //初始化
                init: function () {
                    //轮播图图片初始化
                    if(this.product.sPic){
                        for (var i = 0; i < this.product.sPic.length; i++) {
                            this.imgList.push('http://product.aiyolian.cn/'+this.product.sPic[i]);
                        }
                    }
                    for (var i = 0; i < this.imgList.length; i++) {
                        this.picList.push(this.num++);
                    }
                    //详情图photoList
                    if(this.product.PathID){
                        for (var i = 0; i < this.product.PathID.length; i++) {
                            this.photoList.push('http://product.aiyolian.cn/'+this.product.PathID[i]);
                        }
                    }
                    for (var i = 0; i < this.photoList.length; i++) {
                        this.graphList.push(this.num++);
                    }


                },
                //弹出选择列表
                selectShow: function (key) {
                    this.parentMsg = this.reasonData;
                    this.isItem = 'reason';
                },
                selectArea: function () {
                    $('.mask').show();
                    $('#div_area').show();
                },
                //上传图片
                upImg: function (index, type) {
                    var _this = this;

                    _this.controlShow[index].picLoading = true; //加载动画显示

                    var files = event.currentTarget.files[0];

                    lrz(files)
                        .then(function (rst) {
                            // 处理成功会执行

                            _this.addImg(rst.base64, index, type);
                        })
                        .catch(function (err) {
                            shoperm.showTip('上传正确图片格式');
                            return;
                        })
                        .always(function () {
                            // 不管是成功失败，都会执行
                            _this.controlShow[index].picLoading = false;
                        });
                },
                //添加图片
                addImg: function (dataUrl, index, type) {
                    var self = this;

                    if (type == 'img') {
                        if (self.picList.length < 5) {
                            self.picList.push(self.num++);
                        }
                        self.imgList.push(dataUrl);
                    }
                    else {
                        self.graphList.push(self.num++);
                        self.photoList.push(dataUrl);
                    }
                },
                //删除图片
                imgDelte: function (index, type) {
                    $('.repic_file').eq(index).val(''); //解决删除图片后 改图不能再上传问题
                    if (type == 'img') {
                        this.imgList.splice(index, 1);

                        if (this.imgList.length === 0) {
                            this.imgList = [];
                            this.picList = [];
                            this.picList.push(0);
                        }

                        if (this.imgList.length === 1) {
                            this.picList.splice(index, 1);
                        }
                    }
                    else {
                        this.photoList.splice(index, 1);

                        if (this.photoList.length === 0) {
                            this.photoList = [];
                            this.graphList = [];
                            this.graphList.push(0);
                        }

                        if (this.photoList.length === 1) {
                            this.graphList.splice(index, 1);
                        }
                    }
                },
                submitData: function () {
                    var _this = this;
                    var pattern = /^\d+(\.\d+)?$/; //小数或整数
                    var sName = _this.product.sName;
                    var lStock = _this.product.lStock;
                    var fSupplierPrice = _this.product.fSupplierPrice;
                    var fPrice = _this.product.fPrice;

                    if (sName == '') {
                        shoperm.showTip('商品名称不得为空');
                        return;
                    }

                    if (!pattern.test(lStock)) {
                        shoperm.showTip('请输入正确的库存');
                        return;
                    }
                    if (!pattern.test(fSupplierPrice)) {
                        shoperm.showTip('请输入正确的供货价');
                        return;
                    }
                    if (!pattern.test(fPrice)) {
                        shoperm.showTip('请输入正确的促销价');
                        return;
                    }

                    if (parseFloat(fSupplierPrice) > parseFloat(fPrice)) {
                        shoperm.showTip('促销价不得低于供货价');
                        return;
                    }
                    //不发货地区
                    var areaList = '';
                    $('.provinceList').find('input').each(
                        function () {
                            if ($(this).prop('checked')) {
                                areaList += $(this).val() + ',';
                            }
                        }
                    );
                    var jsondata = {
                        '_csrf': '<?=\Yii::$app->request->getCsrfToken()?>',
                        'imglist': _this.imgList,
                        'photoList': _this.photoList,
                        'sName': sName,
                        'lStock': lStock,
                        'fSupplierPrice': fSupplierPrice,
                        'fPrice': fPrice,
                        'areaList': areaList,
                        'ProductID':'<?=$_GET['ProductID']?>'
                    }
                    _this.isBtnOff = true;
                    $.post('/product/add', jsondata, function (data) {
                        if (data.status) {
                            $('.weui-loading-toast').hide();
                        } else {
                            //location.replace(url);
                        }
                    }, 'json');
                }
            }
        })

    </script>
    <script>
        $('.parea').bind('click', function () {
            var res = false;
            if ($(this).prop('checked')) {
                res = true;
            }
            $(this).parent().next().find('input').each(
                function () {
                    $(this).prop("checked", res);
                }
            );
            getArea();
        });
        $('.provinceList input').bind('click', function () {
            var res = false;
            if ($(this).prop('checked')) {
                res = true;
                //判断同节点下，是否有其它未选中的节点
                $(this).parent().parent().parent().find('input').each(function () {
                    if (!$(this).prop('checked')) {
                        res = false;
                        return;
                    }
                });
            }
            $(this).parent().parent().parent().prev().find('input').prop("checked", res);
            getArea();
        });
        function getArea() {
            var str = '<span>港澳台海外,默认不发货</span><br>';
            $('.provinceList').each(
                function () {
                    $(this).find('input').each(
                        function () {
                            if ($(this).prop('checked')) {
                                str += '<span>' + $(this).next().html() + '</span>'
                            }
                        }
                    );
                    if (str != '<span>港澳台海外,默认不发货</span><br>') {
                        //换行
                        str += '<br>';
                    }
                }
            );
            $('#showArea').html(str);
        }

        $(function () {
            var sNoDelivery=<?=$sNoDelivery?>;
            for (var i=0;i<sNoDelivery.length;i++){
                $('.provinceList').find('input').each(
                    function () {
                        if ($(this).val()==sNoDelivery[i]) {
                            $(this).prop("checked", true);
                        }
                    }
                );
                getArea();
            }
        });
    </script>
<?php $this->endBlock() ?>