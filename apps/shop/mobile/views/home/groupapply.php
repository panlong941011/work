<?php

use yii\helpers\Url;

?>
<?php $this->beginBlock('style') ?>
    <link rel="stylesheet" href="/css/address.css">
    <link rel="stylesheet" href="/css/ydui.css">
    <style>
        .add_mask {
            background-color: #fff;
        }

        .addr_main {
            width: 100%;
            top: 0;
            margin-left: 0;
            left: 0;
        }
        .addr_address,.addr_region,.address_receiver {
            font-size: 0.65rem;
            height: 2.2rem;
            border-bottom: 1px solid #ccc;
            line-height: 2rem;
            clear: both;
        }
        .address_receiver input,.addr_address textarea{
            padding: 0;
            margin: 0;
            padding-left: 0.6rem;
            height: 1.7rem;
            width: 100%;
        }
        input[type="radio"] {
            -webkit-appearance: radio;
            height: 0.5rem;
            width: 0.5rem;
        }
        .cityselect-item-box>a span {
            height: 1.5rem;
            font-size: 0.65rem;
            line-height: 1.5rem;
        }


    </style>
<?php $this->endBlock() ?>
    <!-- 填写地址弹框 -->
    <div class="add_mask" style="height: 3334px;">
        <div class="addr_main">
            <img src="/images/home/groupapply.jpg">
            <div class="addr_title"> 新增团长信息</div>
            <div class="address_receiver">
                <input class="addr_name" id="name" placeholder="小区名称" type="text">
            </div>
            <div class="address_receiver">
                <input class="addr_mobile" id="mobile" placeholder="联系方式" type="tel">
            </div>
            <div class="address_receiver">
                <span style="padding-left: 0.5rem;color: #9c9c9c;">是否有门店：  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span><input type="radio" name="shop" value="1" id="bShop"/>
                <label for="bShop">是</label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                <input type="radio" name="shop" value="0" id="shop"/>
                <label for="shop">否</label>
            </div>
            <div class="address_receiver">
                <span style="padding-left: 0.5rem;color: #9c9c9c;">是否有小区群： &nbsp;&nbsp;&nbsp;&nbsp;</span><input type="radio" name="group" value="1" id="bGroup"/>
                <label for="bGroup">是</label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                <input type="radio" name="group" value="0" id="group"/>
                <label for="group">否</label>
            </div>
            <div class="address_receiver">
                <span style="padding-left: 0.5rem;color: #9c9c9c;">是否有团购经验：&nbsp;</span><input type="radio" name="cat" value="1" id="bHas"/>
                <label for="bHas">是</label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                <input type="radio" name="cat" value="0" id="cat"/>
                <label for="cat">否</label>
            </div>
            <div class="address_receiver">
                <input class="addr_name" id="sMsg" placeholder="其他资源" type="text">
            </div>
            <div class="addr_region">
                <input type="text" readonly id="J_Address" class="c_area flexOne" name="input_area" placeholder="请选择地区">
            </div>
            <div class="addr_address">
                <textarea id="address_text" placeholder="详细地址（可填写街道、小区、大厦）" class="detail_position"></textarea>
            </div>
            <div class="addr_save"> 保存</div>
        </div>
    </div>

    <style>
        .commodity_price p {
            color: #ABABAB;
        }

        .commodity_price > p:first-child {
            color: #333;
        }

        [data-dpr="1"] .commodity_price p {
            font-size: 14px;
        }

        [data-dpr="2"] .commodity_price p {
            font-size: 26px;
        }

        [data-dpr="3"] .commodity_price p {
            font-size: 37px;
        }

        [data-dpr="1"] .commodity_detail h3 {
            font-size: 17px;
        }

        [data-dpr="2"] .commodity_detail h3 {
            font-size: 27px;
        }

        [data-dpr="3"] .commodity_detail h3 {
            font-size: 37px;
        }

        .buy p {
            display: inline-block;
            text-align: left;
            float: left;
            line-height: 1.5rem
        }

        [data-dpr="1"] .buy p {
            font-size: 20px;
        }

        [data-dpr="2"] .buy p {
            font-size: 30px;
        }

        [data-dpr="3"] .buy p {
            font-size: 40px;
        }

        .commodity_list li {
            padding-top: 1rem;
        }
    </style>
<?php $this->beginBlock('foot') ?>
    <script src='/js/jquery.min.js'></script>
    <script src="/js/ydui.citys.js"></script>
    <script src="/js/ydui.js"></script>
    <script>

        $(function () {
            var areaType = '' //地址处理类型
            //设置默认地址
            $('.address_set').on('click', function () {
                var id = $(this).parents('li').data('id');
                $('.address_set_btn').removeClass('default');
                $(this).children('.address_set_btn').addClass('default');
                $('.weui-loading-toast').show();
                $.get('/address/setdef', //post 看效果 实际用get,具体看接口需要特别注意
                    {
                        id: id
                    },
                    function (res) {
                        console.log(res);
                        $('.weui-loading-toast').hide();
                    }, 'json');
            })
            //新建地址
            $('.add_new').on('click', function () {
                areaType = 'new';
                $('.addr_title').html('添加新收货地址');
                //清空所有赋值
                $('#name').val('');
                $('#mobile').val('');
                $('#J_Address').val('');
                $('#address_text').val('');

                $('.add_mask').show();
                $('body,html').css('overflow', 'hidden');
                $('.add_mask').height($(window).height());

                $('body').on('touchmove', function (event) {
                    event.preventDefault()
                })
            })
            $('.addr_close').on('click', function (event) {
                event.stopPropagation();
                $('.add_mask').hide();

                $('body,html').css('overflow', '');
                $("body").unbind("touchmove");
            })
            /*$('.add_mask').on('click',function() {
             $(this).hide();
             })
             $('.addr_main').on('click',function(event) {
             event.stopPropagation();
             })*/

            //编辑地址
            var editId = '';
            $('.address_edit').on('click', function () {
                areaType = 'edit'
                editId = $(this).parents('li').data('id');
                $('.addr_title').html('编辑地址');
                $('.weui-loading-toast').show();
                $.ajax({
                    url: '/address/edit',
                    dataType: 'json',
                    data: {addressid: editId},
                    type: 'get',
                    success: function (data, status, xhr) {
                        console.log(data);

                        $('.weui-loading-toast').hide();

                        if (!data.status) {
                            shoperm.showTip(data.message);
                            return false;
                        }

                        $('#name').val(data.name);
                        $('#mobile').val(data.mobile);
                        $('#J_Address').val(data.area);
                        $('#address_text').val(data.address);
                        $('.add_mask').show();
                        listenerVal();

                    }
                });

            })

            var delId = '';
            //删除地址
            $('.address_del').on('click', function () {
                delId = $(this).parents('li').data('id');
                $('.mask').show();
                shoperm.selection('确定要删除该地址么', delAddress, cancelDel);
            })

            //先确定 再取消
            function delAddress() {
                $('.mask').hide();
                $('.weui-loading-toast').show();
                $.post('/address/del',
                    {
                        id: delId
                    }, function (res) {

                        location.reload();

                    }, 'json')
            }

            function cancelDel() {
                $('.mask').hide();
            }

            //保存地址
            $('.addr_save').on('click', function () {
                var sName = $.trim($('.addr_name').val()),
                    sPhone = $.trim($('.addr_mobile').val()),
                    bGroup = $('input:radio[name=group]:checked').val(),
                    bShop = $('input:radio[name=shop]:checked').val(),
                    bHas = $('input:radio[name=cat]:checked').val(),
                    sMsg = $('#sMsg').val(),
                    sArea = $('.c_area').val().split(' ').join(','), //分割成后端的数据格式
                    sPosition = $.trim($('.detail_position').val()),
                    validate = /^1\d{10}$/,
                    csrf = '<?= \Yii::$app->request->getCsrfToken() ?>',
                    areaData = {
                        _csrf: csrf,
                        name: sName,
                        mobile: sPhone,
                        area: sArea,
                        address: sPosition,
                        bGroup: bGroup,
                        bShop: bShop,
                        bHas: bHas,
                        sMsg: sMsg
                    };
                if (sName == '') {
                    shoperm.showTip("小区名字不能为空");
                    return;
                }

                if (sPhone == '') {
                    shoperm.showTip("收货人电话不能为空");
                    return;
                }
                if (!validate.test(sPhone)) {
                    $('.addr_mobile').addClass('input_error');
                    shoperm.showTip("请输入正确的手机号");
                    return;
                }
                if (sArea == '') {
                    shoperm.showTip("请选择地区");
                    return;
                }
                if (sPosition == '') {
                    shoperm.showTip("详细地址不能为空");
                    return;
                }
                if (sPosition.length > 60) {
                    $('.detail_position').addClass('input_error');
                    shoperm.showTip("地址不能超过60个字");
                    return;
                }

                $('.weui-loading-toast').show();
                //判断新建还是编辑
                $.post('/product/groupapply', areaData,
                    function (res) {
                        if (res.status) {
                            $('.weui-loading-toast').hide();
                            $('.add_mask').hide();
                            $('#addressID').val(res.addressid);
                            location.href = '/home/invite';
                        } else {
                            shoperm.showTip(res.message);
                        }

                    }, 'json')
            })

            //信息填写完全时显示保存按钮颜色
            $('.addr_name,.addr_mobile,.detail_position').on('input', function () {
                listenerVal();
            });
            $('.addr_name,.addr_mobile,.detail_position').on('click', function () {
                $(this).removeClass('input_error');
            });

        })

        //保存按钮是否显示红色
        function listenerVal() {
            var name = $.trim($('.addr_name').val()),
                phone = $.trim($('.addr_mobile').val()),
                area = $('.c_area').val(),
                position = $.trim($('.detail_position').val());

            if (name !== '' && phone !== '' && area !== '' && position !== '') {
                $('.addr_save').addClass('active');
            } else {
                $('.addr_save').removeClass('active');
            }
        }
    </script>
    <script>
        //省市联动插件
        var $target = $('#J_Address');
        $target.citySelect();
        $target.on('click', function (event) {
            event.stopPropagation();
            $target.citySelect('open');

        });
        $target.on('done.ydui.cityselect', function (ret) {

            $(this).val(ret.provance + ' ' + ret.city + ' ' + ret.area);
            listenerVal();
        });

        $('body').on('touchmove', '.m-cityselect', function (event) {
            event.stopPropagation(); //解决手机上划不动的问题
        })

    </script>

<?php $this->endBlock() ?>