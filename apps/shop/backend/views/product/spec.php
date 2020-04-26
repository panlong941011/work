<?php
$arrTemp = \myerm\shop\backend\models\ProductSpecification::getData($_GET['ID']);
$arrSpecData = $arrTemp[0];
$arrSpecImageData = $arrTemp[1];

$arrSkuData = \myerm\shop\backend\models\ProductSKU::getData($_GET['ID']);
$productInfo = \myerm\shop\backend\models\Product::findByID($_GET['ID']);
?>
<style>
    * {
        margin: 0;
        padding: 0;
    }

    body, div, h2, input, span, ul, li {
        margin: 0;
        padding: 0;
        border: 0;
        font: inherit;
        font-size: 14px;
        vertical-align: baseline;
    }

    li {
        list-style: none;
    }

    input {
        outline: none;
    }

    a {
        text-decoration: none;
    }

    [v-cloak] {
        display: none;
    }

    #app {
        background-color: #fff;
        margin: 0 auto;
        width: 1000px;
        overflow: hidden;
    }

    .load_img {
        position: absolute;
        left: 6px;
        top: 36px;
        width: 84px;
        background: #fff;
        -webkit-border-radius: 5px !important;
        -moz-border-radius: 5px !important;
        -o-border-radius: 5px !important;
        border-radius: 5px !important;
        border: 1px solid #dcdcdc;
    }

    .arrow {
        position: absolute;
        width: 0;
        height: 0;
        left: 44%;
        top: -6px;
        border-color: transparent;
        border-left: 6px solid transparent;
        border-right: 6px solid transparent;
        border-bottom: 6px solid #ccc;
    }

    .arrow:after {
        position: absolute;
        display: block;
        width: 0;
        height: 0;
        border-color: transparent;
        border-style: solid;
        top: -8px;
        margin-left: -10px;
        border-bottom-color: #fff;
        border-top-width: 0;
        border-width: 10px;
        content: "";
    }

    .load_content {
        width: 84px;
        height: 84px;
        line-height: 84px;
        text-align: center;
        font-size: 24px;
        color: #ddd;
        font-weight: 700;
        position: relative;
        cursor: pointer;
    }

    .load_content img {
        width: 100%;
        height: 100%;
    }

    .load_content .load_img_file {
        position: absolute;
        left: 0;
        top: 0;
        right: 0;
        bottom: 0;
        width: 100%;
        height: 100%;
        z-index: 10;
        opacity: 0;
        cursor: pointer;
        direction: rtl;
        font-size: 0px;
    }

    .on {
        border: 1px solid #5897fb;
        -webkit-box-shadow: 0 0px 10px #5897fb;
        -moz-box-shadow: 0 0px 10px #5897fb;
        box-shadow: 0 0px 10px #5897fb;
    }

    .addImg input {
        margin-right: 5px;
    }

    .image-box {
        position: absolute;
        left: 0px;
        top: 0px;
        width: 84px;
        height: 84px;
        background: #fff;
        -webkit-border-radius: 10px !important;
        -moz-border-radius: 10px !important;
        -o-border-radius: 10px !important;
        border-radius: 10px !important;
        -border: 1px solid #dcdcdc;
        z-index: 12;
    }

    .image-box img {
        width: 100%;
        height: 100%;
    }

    .img_close_btn {
        position: absolute;
        top: -8px;
        right: -8px;
        width: 18px;
        height: 18px;
        font-size: 14px;
        line-height: 18px;
        -webkit-border-radius: 9px !important;
        -moz-border-radius: 9px !important;
        -o-border-radius: 9px !important;
        border-radius: 9px !important;
        color: #fff;
        text-align: center;
        cursor: pointer;
        background: rgba(0, 0, 0, 0.6);
        z-index: 15;
    }

    .addImg {
        position: absolute;
        left: 135px;
        top: 16px;
    }

    button,
    input {
        -webkit-tap-highlight-color: rgba(0, 0, 0, 0);
        outline: none;
    }

    /* .btn {
        padding: 4px 12px;
        margin-bottom: 0;
        font-size: 14px;
        color: #333;
        vertical-align: middle;
        cursor: pointer;
        background-color: #fff;
        border: 1px solid #ddd;
        -webkit-border-radius: 2px;
        -moz-border-radius: 2px;
        border-radius: 2px;
    }

    .btn.active,
    .btn:active,
    .btn:focus,
    .btn:hover {
        text-decoration: none;
        color: #333;
        background-color: #fcfcfc;
        border-color: #ccc;
    } */
    /*table*/

    table {
        border: 0;
    }

    table.table-sku {
        background-color: #fff;
        width: 100%;
        border: 1px solid #e5e5e5;
        border-collapse: collapse;
    }

    table {
        border-collapse: collapse;
        border-spacing: 0;
    }

    table.table-sku thead {
        width: 100%;
    }

    table.table-sku td {
        border: 1px solid #e5e5e5;
        padding: 10px 8px;
        text-align: center;
    }

    table.table-sku th {
        border: 1px solid #e5e5e5;
        padding: 10px 8px;
    }

    table.table-sku td input {
        width: 70px;
        height: 20px;
        border: 1px solid #ccc;
        -webkit-border-radius: 5px !important;
        -moz-border-radius: 5px !important;
        -o-border-radius: 5px !important;
        border-radius: 5px !important;
        padding: 5px;
    }

    .form-title {
        background: #f8f8f8;
        padding: 10px;
        position: relative;
    }

    .form-title .label {
        color: #999;
    }

    .form-title .delete {
        width: 20px;
        height: 20px;
        line-height: 20px;
        border: 1px solid #ccc;
        position: absolute;
        right: 15px;
        top: 50%;
        margin-top: -10px;
        text-align: center;
        color: #fff;
        background: #ccc;
        cursor: pointer;
        -webkit-border-radius: 50% !important;
        -moz-border-radius: 50% !important;
        -o-border-radius: 50% !important;
        border-radius: 50% !important;
    }

    .form-title input {
        background: #fff;
        border: 1px solid #ccc;

        width: 95px;
        height: 100%;
        padding: 5px 0 5px 5px;
        -webkit-border-radius: 5px !important;
        -moz-border-radius: 5px !important;
        -o-border-radius: 5px !important;
        border-radius: 5px !important;
    }

    .form-list {
        padding: 10px;
        margin-top: 0;
    }

    .form-list li {
        display: inline-block;
        width: 90px;
        border: 1px solid #aaa;
        margin-right: 5px;
        margin-top: 10px;
        padding: 0 5px;
        -webkit-border-radius: 6px !important;
        -moz-border-radius: 6px !important;
        -o-border-radius: 6px !important;
        border-radius: 6px !important;
        background-color: #fff;
        position: relative;
    }

    .spec-item {
        width: 100px;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }

    .form-list,
    .form-title {
        text-align: left;
    }

    .form-list input {
        background: #fff;
        width: 100%;
        height: 25px
    }

    .form_group {
        padding: 10px;
        background-color: #f8f8f8;
        float: left;
        width: 560px;
    }

    .form-table {
        margin-top: 50px;
        width: 540px;
    }

    .form-btn-group {
        background: #f8f8f8;
        padding: 10px;
        border: 1px solid #eee;
    }

    .stock-title,
    .form-h {
        height: 40px;
        line-height: 40px;
    }

    .form-list .active {
        margin-bottom: 100px;
    }

    .form-item {
        position: relative;
        border: 1px solid #ccc;
        margin-bottom: 20px;
    }

    .form-table thead th {
        width: 100px !important;
    }

    .form-table sup {
        color: #f42323;
    }

    .form-table table.table-sku tfoot td {
        text-align: left;
    }
    .batch_type .active {
        color: #f42323;
    }
    .form_show {
        margin-top: 0;
    }
</style>
<script src="/js/vue2.min.js"></script>
<!-- <script src="jquery.min.js"></script> -->
<div id="app">
    <? if ($productInfo->bSale) { ?>
        <?= $this->render('onspec') ?>
    <? } else { ?>
        <?= $this->render('downspec') ?>
    <? } ?>
</div>

<script>
    <? if ($this->context->action->id == 'view') { ?>
    var isView = true;
    <? } else { ?>
    var isView = false;
    <? } ?>
    var attrs = <?=json_encode($arrSpecData, JSON_UNESCAPED_UNICODE)?>;
    var tbList = <?=json_encode($arrSkuData, JSON_UNESCAPED_UNICODE)?>;
    console.log(attrs);
    var imgFils = <?=json_encode($arrSpecImageData)?>;//专门放图片，没有话 必须为 ' ';
    new Vue({
        el: '#app',
        data: {
            attrs: attrs,
            tbList: tbList,
            specObj1: {},
            imgFiles: imgFils, //存储图片数组
            checkBtn: true, //添加图片选择按钮
            batchShow: false, //底部批量设置
            batchForm: false, //批量设置输入框显示
            batchValue: "", //批量数值设置
            batchName: '', //设置批量名称
            tableView: isView, //用于只展示商品的全部规格信息
        },
        computed: {
            //表格结构数据
            tableData: function () {
                var attrs = this.attrs,
                    len = attrs.length,
                    tData = [];
                if (len == 0) {
                    return;
                }
                //初始化tableData
                //遍历attrs数据，计算specLen（每个属性的数量）及rowspan（每个属性的跨行数）
                for (var i = 0; i < len; i++) {
                    var row = {};
                    row['pName'] = attrs[i]['pName'];
                    row['spec'] = [];
                    // row['price'] = {};
                    // row['number'] = {};
                    var len2 = attrs[i]['spec'].length;
                    var specLen = 0;
                    for (var j = 0; j < len2; j++) {
                        var spe = {};
                        var cName = attrs[i]['spec'][j]['cName'];
                        if (!cName) {
                            continue;
                        }
                        ++specLen;
                        spe['cName'] = cName;
                        row['spec'].push(spe);
                    }
                    row['specLen'] = specLen;
                    tData.push(row);
                }
                //获取rowspan
                //计算公式：当前规格跨行数量 = 其他规格数量相乘
                //比如，spec1_len = 1,spec2_len = 3,spec3_len = 4
                //那么：spec1_rowspan = spec2_len*spec3_len
                //以此类推:spec2_rowspan...
                for (var k = 0, len3 = tData.length; k < len3; k++) {
                    var rowspan = 1;
                    for (var k1 = k + 1; k1 < len3; k1++) {
                        var kSpecLen = tData[k1]['specLen'] || 1;
                        rowspan *= kSpecLen;
                    }
                    tData[k]['rowspan'] = rowspan;
                }
                return tData;
            },
            //表格总行数
            rows: function () {
                if (!this.tableData) {
                    return;
                }
                var rows = 1,
                    tableData = this.tableData,
                    len = tableData.length;
                for (var i = 0; i < len; i++) {
                    var specLen = tableData[i]['specLen'] || 1;
                    rows *= specLen;
                }
                //每条rowspan都为1情况
                if (rows == 1) {
                    return tableData[0]['spec'].length;
                }
                return rows;
            },
            //最终生成给后端的的数据
            //根据tbList，tbList含有很多脏数据，统一在tableList中过滤
            tableList: function () {
                if (!this.tableData) {
                    return;
                }
                var rows = this.rows,
                    specObj = {},
                    srcData = this.tableData;
                for (var i = 0; i < rows; i++) {
                    var cNames = this.getCNames(i);
                    //构建tbList
                    specObj[cNames] = {};
                    //初始化后端数据填充
                    if (this.tbList && this.tbList[cNames]) {
                        specObj[cNames]['price'] = this.tbList[cNames]['price'];
                        specObj[cNames]['number'] = this.tbList[cNames]['number'];
                        specObj[cNames]['buyingPrice'] = this.tbList[cNames]['buyingPrice'];
                        specObj[cNames]['buyerPrice'] = this.tbList[cNames]['buyerPrice'];
                    } else {
                        specObj[cNames]['price'] = "";
                        specObj[cNames]['number'] = "";
                        specObj[cNames]['buyingPrice'] = "";
                        specObj[cNames]['buyerPrice'] = "";

                    }
                }
                return specObj;
            },
            //最终生成后端的tableData数据
            tableDataRes: function () {
                if (!this.tableData) {
                    return;
                }
                var tData = this.tableData;
                var imgF = this.imgFiles;
                for (var i = 0; i < imgF.length; i++) {
                    if (imgF[i]) {
                        tData[0]['spec'][i]['img'] = imgF[i];
                    }
                }
                return tData;
            }
        },
        filters: {
            //获取规格名
            getName: function (obj, index) {
                if (obj) {
                    var r = parseInt((index - 1) / obj['rowspan']);
                    var l = obj['specLen'] || 1;
                    var key = r % l;
                    return obj['spec'] && obj['spec'][key] && obj['spec'][key]['cName'];
                }
            }
        },
        mounted: function () {

            if( this.attrs.length !== 0){
                $("input[sFieldAs='lStock']").attr('readonly','readonly');
            }

            $('#addBtn').on('click',function() {
                $("input[sFieldAs='lStock']").removeAttr('readonly');
            })
        
        },
        methods: {
            changeTableList: function () {
                if (!this.tableData) {
                    return;
                }
                this.batchShow = true;
                var rows = this.rows,
                    specObj = {},
                    srcData = this.tableData;
                //{"颜色":"红","尺寸":"大","price":"11","number":"55"}
                //组合形式：
                //{"红;大":{price:10,num:50}}
                for (var i = 0; i < rows; i++) {
                    var cNames = this.getCNames(i);
                    //构建tbList
                    if (!this.tbList || !this.tbList[cNames]) {
                        this.tbList[cNames] = {};
                        this.tbList[cNames]['price'] = "";
                        this.tbList[cNames]['buyerPrice'] = "";
                        this.tbList[cNames]['number'] = "";
                        this.tbList[cNames]['buyingPrice'] = "";
                    }
                }
            },
            //获取tableList的keys，如"红,大,"
            getCNames: function (rowIndex) {
                var cNames = "";
                for (var i = 0; i < this.tableData.length; i++) {
                    var sp = this.tableData[i]; //{"pName": "颜色","spec": [{"cName": "红"}]
                    //构造第一类目
                    var key = sp && sp['pName'];
                    var rowspan = sp && sp['rowspan'];
                    var len = sp && sp['specLen'];
                    if (!len) {
                        continue;
                    }
                    var spec = sp && sp['spec'];
                    //根据rowspan计算规格项在规格组中的index
                    //比如："颜色-红，黄"组合中，红色在颜色组中index为0，黄色为1
                    var index = parseInt(rowIndex / rowspan) % len;
                    cNames += spec[index]['cName'] + ',';
                }
                // return tbList[cNames]['price'];
                return cNames;
            },
            addItem: function () {
                var obj = {
                    pName: '',
                    // rowspan: 1,
                    spec: [{
                        cName: ''
                    }, {
                        cName: ''
                    }, {
                        cName: ''
                    }, {
                        cName: ''
                    }, {
                        cName: ''
                    }, {
                        cName: ''
                    }, {
                        cName: ''
                    }, {
                        cName: ''
                    }, {
                        cName: ''
                    }, {
                        cName: ''
                    }]
                };
                if (this.attrs.length >= 3) {
                    return;
                }
                this.attrs.push(obj);
            },
            toDelete: function (index) {
                this.attrs.splice(index, 1);
                if( this.attrs.length === 0 ) {
                   $("input[sFieldAs='lStock']").removeAttr('readonly');
                }
            },
            toConfirm: function () {
                // console.log(this.tableList);
                // console.log(JSON.stringify(this.attrs));
                // console.log(JSON.stringify(this.tableData))
                // console.log(JSON.stringify(this.tableData));
                console.log(this.tableDataRes);
            },

            //图片上传
            upImg: function (index) {
                var _this = this;
                var file = event.currentTarget.files[0];
                var reader = new FileReader();
                var img = new Image();
                if (file.type.indexOf('image') == -1) { // 判断是否是图片
                    alert('上传正确图片格式');
                    return;
                }
                reader.readAsDataURL(file);  //读取图片文件流
                reader.onload = function () {
                    var result = this.result;
                    _this.imgFiles.splice(index, 1, result);

                    // _this.specAttrs[0]['spec'][index].sLogoPath = result;
                    // _this.$set(_this.specAttrs[0]['spec'][index],'sLogoPath',result);
                    // console.log( _this.specAttrs);
                }

            },
            delImg: function (index) {
                this.imgFiles.splice(index, 1, '');
                /*this.specAttrs[0]['spec'][index].sLogoPath = '';*/
            },
            addImage: function () {
                this.checkBtn = this.checkBtn ? false : true; //添加图片的显示/隐藏
            },
            showInput: function (type) {
                this.batchForm = true;
                this.batchType = false;
               switch(type){
                   case 0:
                       this.batchName = 'price';//赋值设置 说明设置的是价格 不然就是 数量
                   break;
                   case 1:
                       this.batchName = 'number';
                       break;
                   case 2:
                       this.batchName = 'buyingPrice';
                   break;
                   case 3:
                       this.batchName = 'buyerPrice';
                   break;
                }
            },
            //判断选择是价格还是库存 根据type 值
            closeInput: function (type) {
                var tableList = this.tableList; //获取到所有要赋值的列表
                var pattrn = /^\d+(\.\d+)?$/;
                var isNum = pattrn.test(this.batchValue);

                switch (type) {
                    case 0:
                        if (this.batchValue <= 0) {
                            error("请输入正确的值");
                            return;
                        }
                        if (!isNum) {
                            error("请输入正确的值");
                            return;
                        }

                       if( this.batchName == 'price' ){ //设置的是价格
                            //列表所有值赋值
                            for(var i = 0,len=tableList.length;i < len;i++){
                                tableList[i].fPrice = this.batchValue;                      
                            }
                        
                            this.setBatchVal("price");
                        }
                        if( this.batchName == 'number'  ){
                            for(var i = 0,len=tableList.length;i < len;i++){
                                tableList[i].lStore = this.batchValue;
                            }
                            
                            this.setBatchVal("number");
                        }
                        if( this.batchName == 'buyingPrice' ) { 
                            for(var i = 0,len=tableList.length;i < len;i++){
                                tableList[i].bPrice = this.batchValue;
                            }
                            
                            this.setBatchVal("buyingPrice");
                        }
                        if( this.batchName == 'buyerPrice' ) {
                            for(var i = 0,len=tableList.length;i < len;i++){
                                tableList[i].buyerPrice = this.batchValue;
                            }

                            this.setBatchVal("buyerPrice");
                        }
                        this.batchValue = "";
                        break;
                    case 1: //设置取消 则回复原值
                        this.batchValue = "";
                        break;
                }
                this.batchForm = false; //对应结构显示或隐藏
                this.batchType = true;

            },
            setBatchVal: function (specName) {
                for (var item in this.tbList) {
                    this.tbList[item][specName] = this.batchValue;
                }
            }
        }
    })
</script>
<script>
    $(function () {
        //console.log( $("input[sFieldAs='lStock']") );
        //动态计算总库存总和
        function computer() {
            var allNum = 0;
            $('.sell').each(function (index, elm) {
                var num = Number($(elm).val());
                allNum += num;
            })
            $("input[sFieldAs='lStock']").val(allNum);
        }

        //控制数量的可否输入
        $('#addBtn').on('click',function() {
            $("input[sFieldAs='lStock']").attr('readonly','readonly');
        })
        $('.delete').on('click',function() {
            var len = $('.form-item').length;
            if( len == 0) {
                $("input[sFieldAs='lStock']").removeAttr('readonly');
            }
        })

        $('#app').on('input', '.sell', function () {
            //computer();
            var val = $(this).val() || 0,
                pattrn = /^[0-9]\d*$/,
                isNum = pattrn.test(val);

            if (!isNum) {
                error('请输入正确数值');
                return;
            }
            computer();
        })

        $('#app').on('click', '.keep_num', function () {
            computer();  
        })  

        $('#app').on('input', '.v_price', function () {
            var val = $(this).val() || 0,
                pattrn2 = /^\d+(\.\d+)?$/,
                isDecimal = pattrn2.test(val);

            if (!isDecimal) {
                error('请输入正确数值');
                return;
            }

        }) 

        $('#app').on('click','.batch_type a',function() {
            $(this).addClass('active').siblings().removeClass('active');
        })
        
    })
    //判断规格是否有输入值
    function isInputText() {
        var contentLength = $('.required_input').length;
        var reValue = true;
        if( contentLength!== 0 ) {
            for ( var i = 0;i < contentLength; i++ ) {
                if( $('.required_input').eq(i).val() == '' ) {
                    reValue = false;
                    break;
                }
            }
        }
        console.log(reValue);
        return reValue;
    }
</script>