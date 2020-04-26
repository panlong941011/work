<?php
use yii\helpers\Url;
?>
<?php $this->beginBlock('style') ?>
    <link rel="stylesheet" href="/css/car.css?<?= \Yii::$app->request->sRandomKey ?>">
    <style>

        .disable {
            background: #f5f5f5;
            color: #999;
        }

        [v-cloak] {
            display: none;
        }

        .header {
            background: linear-gradient(to bottom right, #E0B991, #AC7C4E);
            color:#ffffff;
        }

        .header .portrait {
            width: 4rem;
            height: 4rem;
            margin-top: 0.5rem;
            margin-left: 0.5rem;
            -webkit-border-radius: 50%;
            border-radius: 50%;
            border: 2px solid #eee;
            overflow: hidden;
            background: #fff;
            margin-top: 0.8rem;
            margin-bottom: 0.3rem;
        }
        .header{
            height: 6rem;
        }
        .header .person_name_wrap {
            margin-top: 1rem;
        }

        .person_name_wrap {
            height: 3rem;
            width: 12rem;
        }

        .person_title {
            padding-left: 0.4rem;
            font-size: 0.56rem;
            display: block;
            clear: both;
            height: 1rem;
            width: 100%;
        }

        .account_info {
            width: 11rem;
        }

        .account_btn {
            width: 4.8rem;
            background: #E0B991;
        }
        .timer{
            font-size: 0.6rem;
            background-color: #fff;
            line-height: 1rem;
            height: 1.5rem;
        }
        .timer img{
            margin-top: 0.1rem;
        }
        .timer span{
            font-size: 0.5rem;
            background-color: #E70012;
            color: #fff;
            padding: 0.1rem;
            border-radius: 0.2rem;
        }
        .member{
            height: 5rem;
            background-color: #eee;
            width: 98%;
            margin-left: 1%;
            border-radius: 0.3rem;
            margin-top: 0.3rem;
            margin-bottom: 0.3rem;
            font-size: 0.6rem;
        }
        .member span{
            font-size: 0.45rem;
            background-color: #E70012;
            color: #fff;
            padding: 0.1rem;
            border-radius: 0.1rem;
        }
        .redimg {
            position: fixed;
            width: 6rem;
            top: 30%;
            left: 30%;
            z-index: 101;
            display: none;
        }

        .redimg img {
            height: auto;
            width: 8rem;
        }
        .divtiper {
            position: absolute;
            top: 3rem;
            left: 1rem;
            z-index: 10;
            font-size: 0.6rem;
            background-color: #eee;
            border-radius: 0.6rem;
            line-height: 1rem;
            padding: 0.1rem;
            background: rgba(0, 0, 0, 0.3);
            color: #fff;
        }

        .divtiper img {
            height: 1.1rem;
            width: 1.1rem;
            border-radius: 50%;
            display: block;
            float: left;
        }
        .carthome {
            background: url(../images/foot/shop0.svg) no-repeat;
            background-size: 100% 100%;
            border: none !important;
        }
    </style>
<?php $this->endBlock() ?>
    <header class="header flex">
        <div class="portrait">
            <img src="<?= $member->sAvatarPath ? $member->sAvatarPath : "/images/order/person.png" ?>">
        </div>
        <div class="person_name_wrap">
            <h3 class="person_title" style="font-size: 0.66rem"><?= $shop->sName ?></h3>
            <h3 class="person_title">手机：<?= $groupaddress->tel ?></h3>
            <h3 class="person_title">
                取货地址：<?= $groupaddress->province->sName . $groupaddress->city->sName . $groupaddress->area->sName . $groupaddress->sAddress ?></h3>

        </div>
    </header>
    <div class="member" style="display: none">
        <div style="padding-left: 0.5rem;padding-top: 0.2rem">
            已团<span>12</span>件&nbsp;&nbsp;共￥<span>100</span>&nbsp;&nbsp;<span>12</span>人已团
        </div>
    </div>
    <div id="app" v-cloak>
        <div class="car_wrap">
            <div class="car_main">
                <div class="c_line"></div>
                <div class="no_commodity" v-if="proList&&proList.length==0">
                    <div class="no_c_pic">
                        <img src="/images/car/no_commodity.png" alt="">
                    </div>
                    <p>亲，您的购物车还没有宝贝哦~</p>
                    <a href="<?= \yii\helpers\Url::toRoute([\Yii::$app->request->shopUrl . "/home"], true) ?>"
                       class="to_index">逛逛去</a>
                </div>
                <div class="car_list" v-else>
                    <div class="car_commodity" v-for="(item,itemIndex) in proList">
                        <a class="commodity_title flex" :href="item.shop_link" style="display: none">
                            <div @click="checkAllByItem(itemIndex,$event)">
                                <span class="all_icon car_on" v-if="item.is_selected"></span>
                                <span class="all_icon" v-else></span>
                            </div>
                            <h2 class="singleEllipsis">{{item.shop_name}}
                                <span class="arrow"></span>
                            </h2>
                        </a>
                        <ul>
                            <li v-for="(pro,pIndex) in item.products">
                                <div class="commodity_content flex">
                                    <div class="commodity_icon" @click="checkOne(itemIndex,pIndex,$event)">
                                        <span class="car_on" v-if="pro.is_selected"></span>
                                        <span v-else></span>
                                    </div>
                                    <div class="commodity_detail flex flexOne">
                                        <div class="commodity_pic">
                                            <a :href="pro.link">
                                                <img :src="pro.pic" alt="">
                                            </a>
                                        </div>
                                        <div class="commodity_info flexOne">
                                            <a :href="pro.link">
                                                <h2 class="multiEllipsis">{{pro.name}}</h2>

                                            <h2 class="multiEllipsis">{{pro.stock}}</h2>
                                            <em style="text-decoration: line-through;color: #ABABAB">{{pro.fMarketPrice}}</em>
                                            <em>团购价：￥{{pro.price}}</em>
                                            </a>
                                            <div class="commodity_btn flex">
                                                <span @click="reduce(itemIndex, pIndex,$event)"
                                                      v-if="pro.num>1">-</span>
                                                <span @click="reduce(itemIndex, pIndex,$event)" class="disable"
                                                      v-else>-</span>
                                                <input class="btn_input" type="tel" v-model="pro.num"
                                                       @click="inputFocus($event)"
                                                       @input="editNum(itemIndex, pIndex,$event)">
                                                <span @click="add(itemIndex, pIndex,$event)">+</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <footer style="bottom: 2rem;" v-if="arrRed&&arrRed.length">
            <div class="commodity_title flex">
                <div><img src="/images/home/redbag.png" style="height: 1rem;width: 1rem;"></div>
                <h2 class="singleEllipsis" style="color: red">满{{topMoney}}，减{{topChange}}{{msg}}
                </h2></div>
        </footer>
        <footer style="bottom: 2rem;" v-if="redProduct&&redProduct.length">
            <div class="commodity_title flex"  v-for="(rd,rdindex) in redProduct">
                <div><img src="/images/home/redbag.png" style="height: 1rem;width: 1rem;"></div>
                <h2 class="singleEllipsis" style="color: red">{{rd.sName}}，购买立减{{rd.fChange}}元
                </h2></div>
        </footer>
        <footer>
            <div class="accounts_cell flex" v-if="proList&&proList.length">
                <div class="account_info flex">
                    <div class="chose_btn flex" @click="checkAll($event)">
                        <span class="chose_icon car_on" v-if="isSlectedAll"></span>
                        <span class="chose_icon" v-else></span>
                        <em>全选</em>
                    </div>
                    <div class="chose_btn flex" @click="backhome()">
                        <span class="chose_icon carthome" style="background: url(../images/home/home.png) no-repeat;background-size: 100% 100%"></span>
                        <em>首页</em>
                    </div>
                    <div class="accounts_price flex" v-if="!isEdit">
                        <span class="accounts_total">
                        总计:
                        <em>¥{{totalPrice.toFixed(2)}}</em>
                    </span>
                        <span class="accounts_tip" style="display: none">(不含运费)</span>
                    </div>
                </div>
                <div class="account_btn" @click="toSettle">
                    <span>立即拼团</span>
                    <em>({{totalNum}}件)</em>
                </div>
            </div>
        </footer>

        <div class="mask"></div>
        <div class="redimg"><img src="/images/home/redproduct.png"></div>
    </div>
    <div class="divtiper" style="display: none">
        <img id="tiperimg" src="http://thirdwx.qlogo.cn/mmopen/vi_32/H0YxpJyXxuKLSFTHalnQfUA9K0n5Nap6088x03LgwTT6icH1G7YZcySl2WU2UBKXgs8wwlpEJmyicP1xETyY3DjA/132">
        <span id="tipersp">小丑鱼刚刚下了一单</span>
    </div>
<?php $this->beginBlock('foot') ?>
    <script>

        var totalPrice = <?=$fTotal?>; //商品总金额
        var totalNum = <?=$lQty?>; //总数量
        //正常商品列表
        var proList = <?=json_encode(array_values($arrSupplier))?>;



        //满减券
        var arrRed =<?=json_encode($arrRed)?>;
        var redProduct =<?=json_encode($redProduct)?>;
        console.log(redProduct);
        console.log(111111111111111111111111111);
        /** 购物车相关操作
         * @auth suwen
         * @date 2017年10月19日09:37:37
         */
        new Vue({
            el: '#app',
            data: {
                totalPrice: parseFloat(totalPrice), //商品总价
                totalNum: parseInt(totalNum), //商品总数
                proList: proList || [], //正常商品
                invalidList: [], //失效商品
                isSlectedAll: true, //是否全选
                isEdit: false, //是否编辑
                seletedIds: [], //选中的ids,删除用
                invalidIds: [],
                seletedData: [], //选中结果集(id、num),结算用
                arrRed: arrRed,
                topMoney: 0,
                topChange: 0,
                msg: '',
                redProduct:redProduct,
                bRedProduct:<?=$bRedProduct?>
            },
            created: function () {
                this.calcTotalData();
            },
            mounted: function () {


            },
            methods: {

                //删除商品
                toDelete: function () {
                    var _self = this;
                    if (!_self.isSomeChecked()) {
                        shoperm.showTip('请选择要删除的商品');
                        return;
                    }
                    //if (!confirm("确认要删除选中的商品吗?")) return;
                    $('.mask').show();
                    shoperm.selection('确认要删除选中的商品吗', _self.delSure, _self.delCancel);

                },
                //点击删除
                delSure: function () {
                    $('.weui-loading-toast').show();
                    this.deleteTrue();
                    this.deleteDom();
                },
                //点击取消
                delCancel: function () {
                    $('.mask').hide();
                },
                //删除前端Dom（虚拟删除）
                deleteDom: function () {
                    this.isEdit = false;
                    for (var i = this.proList.length - 1; i >= 0; i--) {
                        var proItem = this.proList[i],
                            pro = proItem['products'];
                        if (proItem.is_selected) { //单项全选情况
                            proList.splice(i, 1);
                        } else {
                            for (var j = pro.length - 1; j >= 0; j--) {
                                if (pro[j].is_selected) {
                                    pro.splice(j, 1);
                                }
                            }
                        }
                    }
                },
                //删除（真实删除）
                deleteTrue: function () {
                    //获取选中id
                    var ids = this.seletedIds;

                    $.post('/cart/clear',
                        {
                            cartid: ids,
                            _csrf: '<?=\Yii::$app->request->getCsrfToken()?>'
                        },
                        function (res) {
                            console.log(res);
                            $('.weui-loading-toast').hide();
                            $('.mask').hide();
                            shoperm.showTip(res.message);

                        }, 'json')

                },
                //清空失效商品
                clear: function () {
                    $('.mask').show();
                    shoperm.selection('确认清空失效商品吗', this.clearSure, this.clearCancel);
                },
                clearSure: function () {
                    var _self = this;
                    this.invalidList.forEach(function (item) {
                        _self.invalidIds.push(item.id);
                    })
                    $('.weui-loading-toast').show();
                    $.post('/cart/clear',
                        {
                            cartid: _self.invalidIds,
                            _csrf: '<?=\Yii::$app->request->getCsrfToken()?>'
                        },
                        function (res) {
                            $('.weui-loading-toast').hide();
                            if (res.status) {
                                _self.invalidList = [];
                                $('.mask').hide();
                            } else {
                                shoperm.showTip(res.message);
                            }

                        }, 'json')

                },
                clearCancel: function () {
                    $('.mask').hide();
                },
                //完成
                complete: function () {
                    this.isEdit = false;
                },
                //编辑
                edit: function () {
                    this.isEdit = true;
                },
                //减
                reduce: function (itemIndex, proIndex, event) {
                    event.stopPropagation();
                    event.preventDefault();
                    var _self = this,
                        pro = _self.proList[itemIndex]['products'][proIndex];
                    if (pro.num <= 1) {
                        return;
                    }
                    --pro.num;

                    var specData = {
                        cartid: pro.id,
                        quantity: pro.num,
                        _csrf: '<?=\Yii::$app->request->getCsrfToken()?>',
                    }
                    //加入购物车
                    $.post('<?= Url::toRoute([\Yii::$app->request->shopUrl . "/cart/updateqty"], true) ?>', specData,
                        function (res) {

                        }, 'json');
                    _self.calcTotalData();
                },
                //加
                add: function (itemIndex, proIndex, event) {
                    event.stopPropagation();
                    event.preventDefault();
                    var _self = this,
                        pro = _self.proList[itemIndex]['products'][proIndex];

                    /* if (pro.num >= pro.stock) {
                          shoperm.showTip("库存不足");
                         return;
                     }*/

                    ++pro.num;
                    var specData = {
                        cartid: pro.id,
                        quantity: pro.num,
                        _csrf: '<?=\Yii::$app->request->getCsrfToken()?>',
                    }
                    //加入购物车
                    $.post('<?= Url::toRoute([\Yii::$app->request->shopUrl . "/cart/updateqty"], true) ?>', specData,
                        function (res) {
                        console.log(res);

                        }, 'json');
                    _self.calcTotalData();

                },
                //输入修改数量
                editNum: function (itemIndex, proIndex) {
                    var _self = this,
                        pro = _self.proList[itemIndex]['products'][proIndex],
                        reg = /^[0-9]{1,}$/;

                    /* var isNum = reg.test(pro.num);
                         if( !isNum ) {
                             pro.num = 1;
                             shoperm.showTip("数量不能少于1件");
                             return;
                         }*/
                    /* if (pro.num == "") {
                         return;
                     }
                     if (pro.num < 1) {
                          shoperm.showTip("输入不能小于1");
                          pro.num = 1;
                         return;
                     }
                     if (pro.num >= pro.stock) {
                          shoperm.showTip("库存不足");
                         return;
                     }
                     _self.calcTotalData();*/
                },

                inputFocus: function (event) {
                    event.stopPropagation();
                    event.preventDefault();
                },
                //全选
                checkAll: function (event) {
                    event.stopPropagation();
                    event.preventDefault();
                    var _self = this;
                    _self.isSlectedAll = !_self.isSlectedAll;
                    _self.proList.forEach(function (item) {
                        item.is_selected = _self.isSlectedAll;
                        item.products.forEach(function (pro) {
                            pro.is_selected = _self.isSlectedAll;
                        });
                    });
                    _self.calcTotalData();
                },
                //单项全选
                checkAllByItem: function (index, event) {
                    event.stopPropagation();
                    event.preventDefault();
                    var _self = this,
                        proItem = _self.proList[index];
                    proItem.is_selected = !proItem.is_selected;
                    proItem.products.forEach(function (pro) {
                        pro.is_selected = proItem.is_selected;
                    });
                    _self.isSlectedAll = _self.isCheckAll(); //触发全选
                    _self.calcTotalData();
                },
                //单项选择
                checkOne: function (itemIndex, proIndex, event) {
                    event.stopPropagation();
                    event.preventDefault();
                    var _self = this,
                        proList = _self.proList,
                        proItem = proList[itemIndex],
                        pro = proItem['products'][proIndex];
                    pro.is_selected = !pro.is_selected;
                    proItem.is_selected = _self.isItemCheckAll(itemIndex); //触发单项全选
                    _self.isSlectedAll = _self.isCheckAll(); //触发全选
                    _self.calcTotalData();
                },
                //是否单项全选
                isItemCheckAll: function (itemIndex) {
                    var _self = this,
                        pro = _self.proList[itemIndex]['products'];
                    return pro.every(function (item) {
                        return item.is_selected;
                    });
                },
                //是否全选
                isCheckAll: function () {
                    var _self = this;
                    return _self.proList.every(function (item) {
                        return item.is_selected;
                    });
                },
                //是否有选中
                isSomeChecked: function () {
                    var _self = this;
                    var checkedArr = [];
                    //获取单项中有选中的
                    _self.proList.forEach(function (item) {
                        checkedArr.push(item.products.some(function (pro) {
                            return pro.is_selected;
                        }));
                    });
                    //在结果中判断整个是否有选中
                    return checkedArr.some(function (checked) {
                        return checked;
                    });
                },
                //去结算
                toSettle: function () {
                    if(<?=$timerMsg=='距开始'?1:0?>){
                        shoperm.showTip('团购暂未开始，请稍等待');
                        return;
                    }
                    if (!this.isSomeChecked()) {
                        shoperm.showTip('请选择商品');
                        return;
                    }

                    var selectData = this.seletedData;

                    var carIds = [];
                    selectData.forEach(function (item) {
                        carIds.push(item.id);
                    })

                    $.post('<?= \yii\helpers\Url::toRoute(["/cart/addtocheckout"], true) ?>',
                        {
                            cartid: carIds,
                            _csrf: '<?=\Yii::$app->request->getCsrfToken()?>'
                        }, function (res) {
                            console.log(res);
                            if (res.status) {
                                location.href = '<?= \yii\helpers\Url::toRoute(["/cart/checkoutcart"], true) ?>';
                            } else {
                                shoperm.showTip(res.message);
                            }
                        }, 'json');
                },
                //计算总金额和总数量,获取id
                calcTotalData: function () {
                    var _self = this,
                        proList = _self.proList;
                    _self.totalPrice = 0; //初始化
                    _self.totalNum = 0;
                    _self.seletedIds = [];
                    _self.seletedData = [];
                    proList.forEach(function (item) {
                        var itemTotalPrice = 0;
                        item['products'].forEach(function (pro) {
                            var itemData = {};
                            if (pro.is_selected) {
                                var price = pro.price,
                                    num = pro.num;
                                itemTotalPrice += (price * parseInt(num));
                                _self.totalNum += parseInt(num);
                                //获取id
                                if (_self.seletedIds.indexOf(pro.id) == -1) {
                                    _self.seletedIds.push(pro.id);
                                }
                                //获取id及数量，后端结算
                                itemData['id'] = pro.id;
                                itemData['num'] = pro.num;
                                _self.seletedData.push(itemData);
                            }
                        })
                        _self.totalPrice += itemTotalPrice;

                    });
                    //计算满减券
                    _self.topMoney = 0; //初始化
                    _self.topChange = 0;
                    _self.msg = '';
                    var last = 0;
                    var i = 0;
                    _self.arrRed.forEach(function (item) {
                        var t = parseInt(item.fTopMoney);
                        var c = parseInt(item.fChange);
                        if (t < parseInt(_self.totalPrice) && parseInt(_self.topMoney) < t) {
                            _self.topMoney = t;
                            _self.topChange = c;
                            last = i;
                        }
                        i++;
                    });

                    if(_self.topMoney==0&&_self.arrRed&&_self.arrRed.length){
                        _self.topMoney = _self.arrRed[0].fTopMoney;
                        _self.topChange = _self.arrRed[0].fChange;
                    }

                    if (last > 0) {
                        var lastMoney = parseInt(_self.arrRed[last - 1].fTopMoney);
                        if (parseInt(_self.topMoney) < lastMoney) {
                            var fee = parseInt(lastMoney - _self.totalPrice);
                            _self.msg = '，再买' + fee + '减' + _self.arrRed[last - 1].fChange;
                        }
                    }
                },
                backhome: function () {
                    location.href = "<?=\yii\helpers\Url::toRoute([\Yii::$app->request->shopUrl . "/home"], true)?>";
                },
                backredbag: function () {
                    location.href = "<?=\yii\helpers\Url::toRoute([\Yii::$app->request->shopUrl . "/home/redbag"], true)?>";
                }

            }
        });
    </script>
    <script>
        var redbag =<?=$bRedProduct?>;
        if (redbag) {
            $('.mask').show();
            $('.redimg').show();
        }
        $('.mask,.redimg').on('click', function () {
            $('.redimg').hide();
            $('.mask').hide();
            $.get('<?= \yii\helpers\Url::toRoute([\Yii::$app->request->shopUrl . "/home/changeredstate"], true) ?>', {},
                function (res) {
                    //location.href='<?= \yii\helpers\Url::toRoute([\Yii::$app->request->shopUrl . "/home/redbag"], true) ?>';
                }, 'json');
        })
    </script>
    <script>
        var buyers =<?=$buyers?>;
        console.log(buyers.length);
        if (buyers&&buyers.length>0) {
            $('.divtiper').show();
            console.log(buyers);
            var i = 0;
            var k = 0;
            var tt = setInterval(function () {
                if (i < 110) {
                    if (i % 2 == 0) {
                        $('.divtiper').hide(300);
                    } else {
                        k++;
                        $('#tiperimg').attr('src',buyers[k].logo);
                        $('#tipersp').html(buyers[k].sName+'刚刚又下了一单');
                        $('.divtiper').show(300);
                    }
                    i++;
                }else {
                    $('.divtiper').hide();
                    clearInterval(tt);
                }
            }, 2000);
        }
    </script>
<?php $this->endBlock() ?>