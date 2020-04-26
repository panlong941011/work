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

        .carthome {
            background: url(../images/foot/shop0.svg) no-repeat;
            background-size: 100% 100%;
            border: none !important;
        }
    </style>
<?php $this->endBlock() ?>
    <div id="app" v-cloak>
        <div class="car_wrap">
            <div class="car_main">
                <div class="car_header">
                    <a href="javascript:;" onclick="goBack()" class="car_back">
                        <span class="icon">&#xe885;</span>
                    </a>
                    <h2>购物车</h2>
                    <div v-if="proList&&proList.length">
                        <span class="car_deit" @click="edit" v-if="!isEdit">编辑</span>
                        <span class="car_deit" @click="complete" v-else>完成</span>
                    </div>
                </div>
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
                        <a class="commodity_title flex" :href="item.shop_link">
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
                                            </a>
                                            <p class="commodity_stock">{{pro.spec}}</p>
                                            <em>¥{{pro.price}}</em>
                                            <div class="commodity_btn flex">
                                                <span @click="reduce(itemIndex, pIndex,$event)"
                                                      v-if="pro.num>1">-</span>
                                                <span @click="reduce(itemIndex, pIndex,$event)" class="disable"
                                                      v-else>-</span>
                                                <input class="btn_input" type="tel" v-model="pro.num"
                                                       @click="inputFocus($event)"
                                                       @input="editNum(itemIndex, pIndex,$event)"
                                                       @blur="submitData(itemIndex, pIndex,$event)">
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
        <footer>
            <div class="accounts_cell flex" v-if="proList&&proList.length">
                <div class="account_info flex">
                    <div class="chose_btn flex" @click="checkAll($event)">
                        <span class="chose_icon car_on" v-if="isSlectedAll"></span>
                        <span class="chose_icon" v-else></span>
                        <em>全选</em>
                    </div>
                    <div class="chose_btn flex" @click="backhome()">
                        <span class="chose_icon carthome"></span>
                        <span class="chose_icon" v-else></span>
                        <em>挑选更多</em>
                    </div>
                    <div class="accounts_price flex" v-if="!isEdit">
                        <span class="accounts_total">
                        总计:
                        <em>¥{{totalPrice.toFixed(2)}}</em>
                    </span>
                        <span class="accounts_tip" style="color: red">(118起送)</span>
                    </div>
                </div>
                <div class="account_btn" @click="toDelete" v-if="isEdit">
                    <span>删除</span>
                </div>
                <div class="account_btn" @click="toSettle" v-else>
                    <span>去结算</span>
                    <em>({{totalNum}}件)</em>
                </div>
            </div>
        </footer>

        <div class="mask"></div>
    </div>


<?php $this->beginBlock('foot') ?>

    <script>

        var totalPrice = <?=$fTotal?>; //商品总金额
        var totalNum = <?=$lQty?>; //总数量
        //正常商品列表
        var proList = <?=json_encode(array_values($arrSupplier))?>;

        //失效商品列表
        var invalidList = <?=json_encode($arrInvalid)?>;

        //满减券
        var arrRed =<?=json_encode($arrRed)?>;
        console.log(arrRed);
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
                msg: ''
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
                    console.log(ids);

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
                    $.post('/cart/updateqty',
                        {
                            cartid: pro.id,
                            quantity: pro.num,
                            _csrf: '<?=\Yii::$app->request->getCsrfToken()?>'
                        }, function (res) {
                            console.log(res);
                            if (res.status) {
                                _self.calcTotalData();

                            } else {
                                //--pro.num;
                                //
                            }
                        }, 'json')

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
                    $.post('/cart/updateqty',
                        {
                            cartid: pro.id,
                            quantity: pro.num,
                            _csrf: '<?=\Yii::$app->request->getCsrfToken()?>'
                        }, function (res) {

                            if (res.status) {
                                _self.calcTotalData();
                            } else {
                                --pro.num;
                                shoperm.showTip(res.message);
                            }
                        }, 'json')


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
                //提交修改的数量
                submitData: function (itemIndex, proIndex) {
                    var _self = this,
                        pro = _self.proList[itemIndex]['products'][proIndex],
                        reg = /^[1-9]\d*$/;
                    if (pro.num < 1) {
                        shoperm.showTip("输入值不能小于1");
                        pro.num = 1;
                        return;
                    }
                    if (pro.num == "") {
                        shoperm.showTip("数量不能少于1件");
                        pro.num = 1;
                        return;
                    }
                    var isNum = reg.test(pro.num);
                    if (!isNum) {
                        pro.num = 1;
                        shoperm.showTip("请输入正确的数量");
                        return;
                    }

                    $.post('/cart/updateqty',
                        {
                            cartid: pro.id,
                            quantity: pro.num,
                            _csrf: '<?=\Yii::$app->request->getCsrfToken()?>'

                        }, function (res) {
                            console.log(res);
                            if (res.status) {

                                _self.calcTotalData();
                            } else {

                                shoperm.showTip(res.message);
                            }
                        }, 'json')
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
                    if (!this.isSomeChecked()) {
                        shoperm.showTip('请选择商品');
                        return;
                    }

                    var selectData = this.seletedData;
                    var carIds = [];
                    selectData.forEach(function (item) {
                        carIds.push(item.id);
                    })
                    console.log(carIds);
                    $('.weui-loading-toast').show();
                    $.post('cart/addtocheckout',
                        {
                            cartid: carIds,
                            _csrf: '<?=\Yii::$app->request->getCsrfToken()?>'
                        }, function (res) {
                            $('.weui-loading-toast').hide();
                            if (res.status) {
                                location.href = '<?= \yii\helpers\Url::toRoute(["/cart/checkout"], true) ?>';
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
                    _self.msg ='';
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

                    if(last>0) {
                        var lastMoney=parseInt(_self.arrRed[last-1].fTopMoney);
                        if (parseInt(_self.topMoney) < lastMoney) {
                            var fee = parseInt(lastMoney - _self.totalPrice);
                            _self.msg = '，再买' + fee + '减'+_self.arrRed[last-1].fChange;
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

<?php $this->endBlock() ?>