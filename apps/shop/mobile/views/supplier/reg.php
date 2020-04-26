<?php $this->beginBlock('style') ?>
    <link rel="stylesheet" href="/css/refundApply.css">
    <style>
        .vote-entry {
            clear: both;
            height: auto;
        }

        .basecontent .vote-entry {
            display: block;
            clear: both;
            height: 0.7rem;
            line-height: 1rem;
        }

        .vote-details .control_title .title {
            font-size: 0.65rem;
            line-height: 1.5rem;
            height: 1.5rem;
            color: #333;
        }

        .vote-details .basetitle {
            font-size: 0.65rem;
            color: #333;
            margin-left: 2.5%;
            height: 1.5rem;
            line-height: 1.5rem;
            font-weight: bold;
        }

        .basecontent .vote-entry .control_title {
            width: 30%;
            float: left;
        }

        .vote-entry .control_conts {
            margin: 0;
            padding: 0;
            width: 65%;
        }

        .vote-entry .control_conts .w_txt {
            height: 1rem;
            line-height: 1rem;
            width: 100%;
            text-align: right;
            font-size: 0.65rem;
            -webkit-box-shadow: none;
            box-shadow: none;
            margin: 0;
            padding: 0;
            display: block;
        }

        .basecontent .vote-entry .control_conts {
            float: right;
        }

        .basecontent {
            display: block;
            border: 1px solid #ccc;
            width: 96%;
            margin-left: 2%;
            padding: 5px;
            height: auto;
            background-color: #fff;
        }

        .basecontent .vote-entry .apply_pic_list {
            clear: both;
            height: 4rem;
            display: block;
        }

        .basecontent .question-41 {
            height: 5rem;
        }

        .opercontent {
            border: 1px solid #ccc;
            width: 96%;
            margin-left: 2%;
            padding: 5px;
            height: auto;
            background-color: #fff;
        }

        input[type="checkbox"] {
            -webkit-appearance: checkbox;
            height: 0.5rem;
            width: 0.5rem;
        }

        .wrapper label {
            font-size: 0.65rem;
        }

        .wrapper {
            float: left;
            margin-right: 0.2rem;
        }

        .box {
            display: inline-block;
            width: 0.65rem;
            height: 0.65rem;
        }

        textarea {
            height: 4rem;
            line-height: 0.65rem;
            width: 100%;
            text-align: left;
            font-size: 0.65rem;
            border: 1px #33AAAA solid;
        }

        .paginations button {
            display: inline-block;
            background-color: #3394FF;
            height: 1.5rem;
            width: 80%;
            line-height: 0.65rem;
            font-size: 0.65rem;
            color: #fff;
            text-align: center;
            border: 0px none;
            outline: none;
            -webkit-background-clip: padding-box;
            -moz-background-clip: padding;
            border-radius: 3px;
            background-clip: padding-box;
            cursor: pointer;
            padding: 0;
            margin-left: 10%;

        }

        .paginations {
            width: 100%;
            margin: 0.5rem auto;
        }
        .question-6 .control_conts{
            width: 100%;
            clear: both;
        }
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
    </style>
<?php $this->endBlock() ?>
    <div class="refund_apply" id="app" v-cloak>
        <div class="ad_header">
            <a href="javascript:goBack();" class="ad_back">
                <span class="icon">&#xe885;</span>
            </a>
            <h2>供应商入驻</h2>
        </div>
        <div class="vote-details" id="app" v-cloak>
            <div class="vote-entry custom-richtext">
                <p style="margin-bottom:0px;margin-top:0px;line-height:0em;">
                    <img data-origin-height="450" src="http://product.aiyolian.cn/userfile/upload/20190725/8.jpg">
                </p>
            </div>
            <div class="basetitle">基础信息*</div>
            <div class="basecontent">
                <div class="vote-entry question-1" data-type="text" data-title="公司名称">
                    <div class="control_title">
                        <h3 class="title">公司名称</h3>
                    </div>
                    <div class="control_conts">
                        <input name="sCompanyName" id="sCompanyName" class="w_txt" type="text"
                               placeholder="请输入公司名称">
                    </div>
                </div>
                <div class="vote-entry question-2" data-type="text" data-title="联系人姓名" data-index="2">
                    <div class="control_title">
                        <h3 class="title">联系人</h3>
                    </div>
                    <div class="control_conts">
                        <input name="sName" id="sName" class="w_txt" type="text" placeholder="请输入联系人">
                    </div>
                </div>
                <div class="vote-entry question-3" data-type="text" data-title="联系人手机号" data-index="3">
                    <div class="control_title">
                        <h3 class="title">联系电话</h3>
                    </div>
                    <div class="control_conts">
                        <input name="sTel" id="sTel" class="w_txt" type="text" placeholder="请输入联系电话">
                    </div>
                </div>
                <div class="vote-entry question-4" data-type="text" data-title="您的所在地" data-index="4" data-code="0"
                >
                    <div class="control_title">
                        <h3 class="title">您的所在地</h3>
                    </div>
                    <div class="control_conts">
                        <input name="sAddress" id="sAddress" class="w_txt" type="text" placeholder="如：福建省厦门市">
                    </div>
                </div>
                <div class="vote-entry question-41" data-type="text" data-title="营业执照" data-index="4" data-code="0"
                >
                    <div class="control_title" style="clear: both;height: 1rem;line-height: 1rem;width: 100%;">
                        <h3 class="title">营业执照(1张)</h3>
                    </div>
                    <div class="apply_pic_list">
                        <div class="apply_pic_item" v-for="(imgItem,index) in picList">
                            <div class="upload_before" v-if="!imgList[index]">
                                <p>上传证件</p>
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
                </div>

            </div>
            <div class="basetitle">经营信息*</div>
            <div class="opercontent">
                <div class="vote-entry question-5" data-type="text" data-title="主营品类" data-index="5">
                    <div class="control_title">
                        <h3 class="title">
                            1. 主营品类（可多选）</h3>
                    </div>
                    <div class="control_conts">
                        <div style="clear: both;height: 64px;">
                            <div class="wrapper">
                                <input type="checkbox" value="时令生鲜" id="c1"/>
                                <label for="c1">时令生鲜</label>
                            </div>
                            <div class="wrapper">
                                <input type="checkbox" value="肉禽蛋类" id="c2"/>
                                <label for="c2">肉禽蛋类</label>
                            </div>
                        </div>
                        <div style="clear: both;height: 64px;">
                            <div class="wrapper">
                                <input type="checkbox" value="粮油调料" id="c3"/>
                                <label for="c3">粮油调料</label>
                            </div>
                            <div class="wrapper">
                                <input type="checkbox" value="地方特色" id="c4"/>
                                <label for="c4">地方特色</label>
                            </div>
                        </div>
                        <div style="clear: both;height: 64px;">
                            <div class="wrapper">
                                <input type="checkbox" value="零食干货" id="c5"/>
                                <label for="c5">零食干货</label>
                            </div>
                            <div class="wrapper">
                                <input type="checkbox" value="其他农副产品" id="c6"/>
                                <label for="c6">其他农副产品</label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="vote-entry question-8" data-type="text" data-title="是否有电商经验" data-index="5">
                    <div class="control_title">
                        <h3 class="title">2. 是否有电商经验</h3>
                    </div>
                    <div class="control_conts">
                        <div style="clear: both;height: 64px;">
                            <div class="wrapper">
                                <input type="checkbox" value="是" id="bEshopY"/>
                                <label for="bEshop">是</label>
                            </div>
                            <div class="wrapper">
                                <input type="checkbox" value="否" id="bEshopN"/>
                                <label for="bEshopN">否</label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="vote-entry question-7" data-type="text" data-title="意向发货市场" data-index="7">
                    <div class="control_title">
                        <h3 class="title">
                            3. 意向发货市场（可多选） </h3>
                    </div>
                    <div class="control_conts">
                        <div style="clear: both;height: 80px;">
                            <div class="wrapper">
                                <div class="box">
                                    <input type="checkbox" value="电商平台" id="c71"/>
                                </div>
                                <label for="c71">电商平台</label>
                            </div>
                            <div class="wrapper">
                                <div class="box">
                                    <input type="checkbox" value="微商个体" id="c72"/>
                                </div>
                                <label for="c72">微商个体</label>
                            </div>
                        </div>
                        <div style="clear: both;height: 80px;">
                            <div class="wrapper">
                                <div class="box">
                                    <input type="checkbox" value="社区团购" id="c73"/>
                                </div>
                                <label for="c73">社区团购</label>
                            </div>
                            <div class="wrapper">
                                <div class="box">
                                    <input type="checkbox" value="自媒体" id="c74"/>
                                </div>
                                <label for="c74">自媒体</label>
                            </div>
                        </div>
                        <div style="clear: both;height: 80px;">
                            <div class="wrapper">
                                <div class="box">
                                    <input type="checkbox" value="渠道零售" id="c75"/>
                                </div>
                                <label for="c75">渠道零售</label>
                            </div>
                            <div class="wrapper">
                                <div class="box">
                                    <input type="checkbox" value="商超门店" id="c76"/>
                                </div>
                                <label for="c76">商超门店</label>
                            </div>
                        </div>
                        <div style="clear: both;height: 80px;">
                            <div class="wrapper">
                                <div class="box">
                                    <input type="checkbox" value="酒店/物业" id="c77"/>
                                </div>
                                <label for="c77">酒店/物业</label>
                            </div>
                            <div class="wrapper">
                                <div class="box">
                                    <input type="checkbox" value="更多" id="c78"/>
                                </div>
                                <label for="c78">更多</label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="vote-entry question-6" data-type="text" data-title="公司介绍" data-index="6">
                    <div class="control_title">
                        <h3 class="title">
                            4. 公司介绍</h3>
                    </div>
                    <div class="control_conts">
                        <textarea name="sDes" id="sDes" cols="30" rows="10"></textarea>
                    </div>
                </div>
            </div>

        </div>
        <div class="paginations clearfix first">
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
                    var sName = $('#sName').val();
                    var sCompanyName = $('#sCompanyName').val();
                    var sTel = $('#sTel').val();
                    var sAddress = $('#sAddress').val();
                    var sDes = $('#sDes').val();
                    var sCat = '';
                    $(".question-5 input[type=checkbox]:checked").each(function () { //由于复选框一般选中的是多个,所以可以循环输出
                        sCat += ',' + $(this).val();
                    });
                    var sMark = '';
                    $(".question-7 input[type=checkbox]:checked").each(function () { //由于复选框一般选中的是多个,所以可以循环输出
                        sMark += ',' + $(this).val();
                    });
                    var bEshopY = 0;
                    if ($('#bEshopY').prop('checked')) {
                        bEshopY = 1;
                    }
                    if (sCompanyName == '') {
                        alert("请输入公司名称");
                        return;
                    }
                    if (sName == '') {
                        alert("请输入联系人名称");
                        return;
                    }
                    if (sTel == '') {
                        alert("请输入联系人手机号码");
                        return;
                    }
                    if (sAddress == '') {
                        alert("请输入所在地");
                        return;
                    }
                    if (sCat == '') {
                        alert("请选择主营品类");
                        return;
                    }
                    if (sDes == '') {
                        alert("请输入产品名称及卖点");
                        return;
                    }
                    if (sMark == '') {
                        alert("请选择意向发货市场");
                        return;
                    }
                    var formData = {
                        sName: sName,
                        sCompanyName: sCompanyName,
                        sTel: sTel,
                        sAddress: sAddress,
                        sDes: sDes,
                        sCat: sCat,
                        sMark: sMark,
                        bEshop: bEshopY,
                        imglist: _this.imgList,
                        _csrf: '<?=\Yii::$app->request->getCsrfToken()?>'
                    };
                    console.log(formData);
                    _this.isBtnOff = true;
                    $.post('/supplier/supplierreg',
                        formData, function (data) {
                            console.log(data);
                            if (data.status) {
                                location.href = '/supplier/success';
                            } else {
                                alert('提交失败，请联系管理员');
                            }
                        }, 'json');
                }

            }
        });


    </script>
    <script>

        $(function(){

            //$('input').focus(focustext)

            /*******用以下三行代码即可实现*******/

            $('input').click(function(){

                $(this).focus().select();//保险起见，还是加上这句。
                this.selectionStart = 0;

                this.selectionEnd = this.val().length;

            })

        })

        function focustext() {

            var input = this;

            setTimeout(function(){ input.selectionStart = 0; input.selectionEnd = input.val().length; }, 100)

        }

    </script>
<?php $this->endBlock() ?>