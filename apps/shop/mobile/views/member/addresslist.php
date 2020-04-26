<?php $this->beginBlock('style') ?>
    <link rel="stylesheet" href="/css/address.css?<?=\Yii::$app->request->sRandomKey?>">
    <link rel="stylesheet" href="/css/ydui.css">
    <style>
        .cityselect-item-box>a{
            height: 1.8rem;
            line-height: 1.8rem;
        }
        .addr_address{
            height: 3rem;
        }
        .addr_address textarea{
            height: 3rem;
        }
    </style>
<?php $this->endBlock() ?>
    <div class="address_wrap">
        <div class="ad_header">
            <a href="javascript:;" onclick="goBack()" class="ad_back">
                <span class="icon">&#xe885;</span>
            </a>
            <h2>收货地址管理</h2>
        </div>
        <? if ($arrAddress) { ?>
            <div class="ad_list">
                <ul>
                    <? foreach ($arrAddress as $address) { ?>
                        <li data-id="<?= $address->lID ?>">
                            <div class="address_item chose_item">
                                <div class="address_info flex">
                                    <span>收货人：<?= $address->sName ?></span>
                                    <span><?= $address->sMobile ?></span>
                                </div>
                                <div class="address_detail">
                                    收货地址：<?= $address->province->sName ?><?= $address->city->sName ?><?= $address->area->sName ?><?= $address->sAddress ?></div>
                            </div>
                            <div class="address_ops flex">
                                <div class="address_set">
                                    <span class="address_set_btn <? if ($address->bDefault) { ?>default<? } ?>">已设为默认</span>
                                </div>
                                <div class="address_other flex">
                                    <div class="address_edit">
                                        <span>编辑</span>
                                    </div>
                                    <div class="address_del">
                                        <span>删除</span>
                                    </div>
                                </div>
                            </div>
                        </li>
                    <? } ?>
                </ul>
            </div>
        <? } else { ?>
            <!-- 地址为空时 -->
            <div class="no_address">
                <div class="no_pic">
                    <img src="/images/address/no_address.png" alt="">
                </div>
                <p class="no_top">亲，您还没有收货地址哦~</p>
            </div>
        <? } ?>
        <div class="add_new_wrap">
            <div class="add_new">添加新地址</div>
        </div>
    </div>
    <!-- <div class="add_new">添加新地址</div> -->

    <div class="add_mask" style="display: none;">
        <div class="addr_main">
            <div class="addr_title">添加新收货地址</div>
            <div class="address_receiver">
                <input class="addr_name" id="name" placeholder="名字" type="text">
                <input class="addr_mobile" id="mobile" placeholder="电话" type="tel">
            </div>
            <div class="addr_region">
                <input type="text" readonly id="J_Address" class="c_area flexOne" name="input_area" placeholder="请选择地区">
            </div>
            <div class="addr_address">
                <textarea id="address_text" placeholder="详细地址（可填写街道、小区、大厦）" class="detail_position"></textarea>
            </div>
            <div class="addr_save">保存</div>
            <div class="addr_close">
                <i></i>
            </div>
        </div>
    </div>

    <div class="selection_bar">
        <div class="select_name"></div>
        <div class="select_chose flex">
            <span class="select_cancel flexOne">取消</span>
            <span class="select_sure flexOne">确认</span>
        </div>
    </div>
    <div id="tip"></div>

    <div class="weui-loading_toast" style="display: none;">
        <div class="weui-mask_transparent"></div>
        <div class="weui-toast">
            <i class="weui-loading weui-icon_toast"></i>
        </div>
    </div>

    <div class="mask"></div>


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
                var sName = $.trim( $('.addr_name').val() ),
                    sPhone = $.trim( $('.addr_mobile').val() ),
                    sArea = $('.c_area').val().split(' ').join(','), //分割成后端的数据格式
                    sPosition = $.trim( $('.detail_position').val() ),
                    validate = /^1\d{10}$/,
                    areaData = {
                        name: sName,
                        mobile: sPhone,
                        area: sArea,
                        address: sPosition
                    };
                if (sName == '') {
                    shoperm.showTip("收货人名字不能为空");
                    return;
                }
                if (sName.length > 8) {
                    shoperm.showTip("名字不能超过8个字");
                    $('.addr_name').addClass('input_error');
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
                if (areaType == 'new') {

                    $.post('/address/new', areaData,
                        function (res) {
                            console.log(res);

                            $('.weui-loading-toast').hide();
                            $('.add_mask').hide();

                            if (res.status) {
                                window.location.reload();
                            } else {
                                shoperm.showTip(res.message);
                            }

                        }, 'json')

                }
                if (areaType == 'edit') {
                    areaData.addressid = editId;
                    $.post('/address/edit', areaData,
                        function (res) {
                            console.log(res);

                            $('.weui-loading-toast').hide();
                            $('.add_mask').hide();

                            if (res.status) {
                                window.location.reload();
                            } else {
                                shoperm.showTip(res.message);
                            }
                        }, 'json')

                }

            })

            //信息填写完全时显示保存按钮颜色
            $('.addr_name,.addr_mobile,.detail_position').on('input', function () {
                listenerVal();
            });
            $('.addr_name,.addr_mobile,.detail_position').on('click', function () {
                $(this).removeClass('input_error');
            });

            //使用地址
            $('.address_item').on('click', function () {
                var id = $(this).parent().data('id'); //选择的id
                console.log(id);
                location.href = "<?= \yii\helpers\Url::toRoute(["/cart/checkout"], true) ?>?addressid=" + id;
            })

        })

        //保存按钮是否显示红色
        function listenerVal() {
            var name = $.trim( $('.addr_name').val() ),
                phone = $.trim( $('.addr_mobile').val() ),
                area = $('.c_area').val(),
                position = $.trim( $('.detail_position').val() );
                
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