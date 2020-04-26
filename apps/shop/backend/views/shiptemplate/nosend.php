 <!-- 不发货设置  -->
<style>
body,h1,h2,h3,h4,h5,h6,hr,p,blockquote,dl,dt,dd,ul,ol,li,pre,form,fieldset,legend,button,input,textarea,th,
td {
    margin: 0;
    padding: 0
}
body,button,input,select,textarea {
    font: 12px/1.5 tahoma, arial, \5b8b\4f53, sans-serif
}
h1,h2,h3,h4,h5,h6 {
    font-size: 100%
}
address,cite,dfn,em,var {
    font-style: normal
}
code,kbd,pre,samp {
    font-family: courier new, courier, monospace
}
small {
    font-size: 12px
}
ul,ol,li {
    list-style: none
}

a {
    text-decoration: none
}

a:hover {
    text-decoration: underline
}

sup {
    vertical-align: text-top
}

sub {
    vertical-align: text-bottom
}

legend {
    color: #000
}

fieldset,
img {
    border: 0
}

button,input,select,textarea {
    font-size: 100%
}

table {
    border-collapse: collapse;
    border-spacing: 0
}

article,aside,details,figcaption,figure,footer,header,hgroup,menu,nav,section,summary,time,mark,audio,
video {
    display: block;
    margin: 0;
    padding: 0
}

button.btn:hover,
button.small-btn:hover,
button.long-btn:hover,
button.short-btn:hover,
button.small-long-btn:hover {
    text-decoration: none
}



.dialog-areas {
    position: absolute;
    width: 580px;
    border: 1px solid #c4d5df;
    background-color: #fff
}

.dialog-areas .title {
    padding-left: 10px;
    height: 22px;
    line-height: 22px;
    font-weight: 700;
    background-color: #e9f1f4;
    border-width: 1px;
    border-style: solid;
    border-color: #fff #fff #c4d5df
}

.dialog-areas .even {
    background-color: #ecf4ff
}

.dialog-areas .btns {
    padding: 5px 0 5px 10px;
    margin-left: 430px
}

.dialog-areas label {
    margin: 0 3px
}

.dialog-areas ul {
    border-bottom: 1px solid #c4d5df
}

.dialog-areas li {
    width: 100%;
    overflow: hidden
}

.dialog-areas li span.group-label2 {
    margin-right: 5px;
    padding: 5px 0 5px 10px;
    display: inline-block;
    width: 70px;
    font-weight: 700
}

.dialog-areas button {
    margin-right: 5px;
    padding: 2px 3px
}

.dialog-areas .btns .msg {
    position: absolute
}

.dialog-areas input,
.dialog-areas button {
    vertical-align: middle
}

.dialog-areas .ks-ext-close {
    position: absolute;
    top: 5px;
    right: 5px;
    font-size: 10px
}

.dialog-areas .areas2 b {
    display: none;
    width: 0;
    height: 0;
    line-height: 0;
    font-size: 0;
    border-width: 4px;
    border-color: #666 #fff #fff;
    border-style: solid;
    vertical-align: middle;
    overflow: hidden
}

.ks-ext-mask,
.ks-dialog-mask2 {
    background-color: #fff;
    opacity: .3;
    filter: alpha(opacity=30)
}

.dialog-address {
    position: absolute;
    width: 580px;
    border: 1px solid #95d2ff;
    background-color: #f1fafe
}

.dialog-address .hd {
    padding: 0 5px;
    height: 30px;
    line-height: 30px;
    overflow: hidden
}

.dialog-address h3 {
    display: inline
}

.dialog-address .action {
    float: right
}

.dialog-address table {
    width: 100%;
    border-left: 1px solid #fff
}

.dialog-address caption {
    display: none
}

.dialog-address .col-uid {
    width: 30px
}

.dialog-address .col-name {
    width: 80px
}

.dialog-address .col-postcode {
    width: 60px
}

.dialog-address .col-phone,
.dialog-address .col-mobile {
    width: 90px
}

.dialog-address td {
    padding: 5px;
    height: 34px;
    border-width: 0 1px 1px 0;
    border-style: solid;
    border-color: #fff;
    background-color: #e2efff;
    word-wrap: break-word
}

.dialog-address .even td {
    background-color: #cbe1ff
}

.dialog-address input {
    vertical-align: middle
}

.dialog-address .ft {
    padding: 5px
}

.dialog-batch {
    position: absolute;
    width: 620px;
    padding: 10px;
    border: 1px solid #95d2ff;
    background-color: #fff
}

.dialog-batch .input-text {
    width: 6em;
    height: 15px;
    line-height: 15px;
    padding: 1px 0;
    border: 1px solid #7f9db9
}

.dialog-batch .input-readonly {
    background-color: #eee;
    border: 1px solid #ccc
}

.dialog-batch .btns {
    padding-top: 10px;
    text-align: center
}

.dialog-batch button {
    margin: 0 5px
}

.dialog-batch .ks-ext-close {
    position: absolute;
    top: 5px;
    right: 5px;
    font-size: 10px
}

.clearfix:after {
    display: block;
    height: 0;
    clear: both;
    content: ''
}

.clearfix {
    zoom: 1
}

.citys2 {
    background-color: #FFFEC6;
    position: absolute;
    float: right;
    border: solid 1px #f7e4a5;
    z-index: 20000;
    top: 23px;
    left: 0;
    width: 214px;
    display: none;
    white-space: wrap
}

.styblock {
    display: block
}

.citys2 span {
    line-height: 2;
    margin-right: 2px
}

.dialog-areas li {
    overflow: visible
}

.dcity2 {
    vertical-align: middle;
    display: block;
    z-index: 1
}

.ecity2 {
    position: relative;
    float: left;
    margin-right: 1px;
    padding-right: 8px;
    height: 30px;
    width: 80px
}

.gcity2,
.province-list2 {
    display: inline-block
}

.province-list2 {
    width: 450px
}

.trigger2 {
    width: 8px;
    height: 8px;
    padding: 2px;
    cursor: pointer
}

.dialog-areas li span.areas2 {
    margin-right: 3px;
    padding: 4px 0 1px 4px;
    display: inline-block
}

.dialog-areas span.gareas2 {
    white-space: nowrap;
    margin-right: 3px;
    padding: 4px 4px 1px;
    display: inline-block;
    position: relative;
    height: 17px;
    border: 1px solid #fff
}

.dialog-areas .even span.gareas2 {
    background-color: #ECF4FF;
    border-color: #ecf4ff
}

.dialog-areas li span.egareas {
    margin-right: 3px;
    padding: 3px 0 1px;
    display: inline-block;
    background-color: #FFFEC6;
    border: solid 1px #f7e4a5;
    width: 70px
}

.showCityPop {
    z-index: 55556
}

.dialog-areas .showCityPop .gareas2 {
    background-color: #FFFEC6;
    border: solid 1px #f7e4a5;
    border-bottom: solid 1px #FFFEC6;
    z-index: 56000
}

.dialog-areas .even .showCityPop .gareas2 {
    background-color: #FFFEC6;
    border-color: #f7e4a5 #f7e4a5 #FFFEC6
}

.showCityPop .citys2 {
    display: block;
    z-index: 55900
}

.checkbox {
    vertical-align: middle;
    padding: 0
}

.dialog-areas label {
    margin: 0 1px
}

.check_num2 {
    color: #F60;
    font-size: 12px;
    letter-spacing: -1px
}

.j_sellerBearFrePrice {
    display: none;
    width: 4em;
    height: 15px;
    line-height: 15px;
    padding: 1px 0;
    border: 1px solid #7f9db9;
    text-align: right
}

.default .j_sellerBearFrePrice {
    text-align: left
}

.select-by-custom .section {
    margin-left: 20px
}

.fare-time {
    float: left;
    margin-right: 10px
}

.dialog2 {
    display: none;
}

.set-free {
    padding-left: 28px!important;
    margin-bottom: 20px;
    display: none
}

.set-free p.free-title {
    padding-top: 15px;
    margin-top: 15px;
    border-top: 1px solid #ccc
}

.set-free .table {
    width: 100%;
    margin-top: 15px;
    border-spacing: 0;
    border-collapse: collapse;
    display: none
}

.set-free .table th {
    height: 28px;
    font-weight: 400;
    background-color: #f5f5f5;
    text-align: center;
    border: 1px solid #bbb
}

.set-free .table td {
    border: 1px solid #bbb;
    background: #fcfcfc;
    padding: 10px
}

.set-free .table td select {
    width: 87px
}

.set-free .small-icon {
    display: inline-block;
    width: 14px;
    height: 14px;
    margin-left: 15px;
    background: url(//img.alicdn.com/tps/i4/TB1TlaNFVXXXXXLXpXXCKzvHXXX-48-14.png) 0 0 no-repeat
}

.set-free .free-contion {
    margin-top: 15px;
}

.set-free .free-contion input {
    width: 36px
}

.set-free .J_DelateItem {
    background-position: -34px 0
}

.set-free .edit {
    float: right
}

.set-free .area-group2 {
    padding-right: 3em;
    display: block
}

.set-free .hidden {
    display: none
}

.set-free .table td.hui-error {
    padding: 10px 0;
    border: 0
}

.set-free .table td select.J_ChageContion {
    width: 100px
}

.set-free .free-contion input.input-65 {
    width: 65px
}

.noDeliver {
     font-size: 12px;
}
.noDeliver {
 font-size: 12px;
}
.noDeliver sup{
    color: #e7505a;
    vertical-align: middle;
}
.noDeliver a {
    color: #38f;
}
.noDeliver a {
    margin-left: 5px;
}
.deliverWrap {
    display: none;
}
.noDeliver .table2 {
    width: 50%;
}
.set-free2 .table2 {
    width: 50%;
    margin-top: 15px;
    border-spacing: 0;
    border-collapse: collapse;
    display: table;
}
.set-free2 {
    padding-left: 28px!important;
    margin-bottom: 20px;
    display: list-item;
}

.set-free2 .table2 th {
    height: 28px;
    font-weight: 400;
    background-color: #f5f5f5;
    text-align: center;
    border: 1px solid #bbb
}

.set-free2 .table2 td {
    border: 1px solid #bbb;
    background: #fcfcfc;
    padding: 10px;
    text-align: center;
}

.set-free2 .table2 td select {
    width: 87px
}
.set-free2 .small-icon {
    display: inline-block;
    width: 14px;
    height: 14px;
    margin-left: 15px;
    background: url(//img.alicdn.com/tps/i4/TB1TlaNFVXXXXXLXpXXCKzvHXXX-48-14.png) 0 0 no-repeat
}

.set-free2 .free-contion {
    margin-top: 15px
}
.set-free2 .J_DelateItem2 {
    background-position: -34px 0
}
.set-free2 .area-group2 {
    padding-right: 3em;
    display: block
}
.set-free2 .edit2 {
    float: right
}
.deliverShow {
    display: block;
}
</style>
<div>
<li class="form-elem set-free2">
    <div class="noDeliver">
        <span>指定不发货地区:</span>
        <a href="javascript:;" class="noDeliverEdit">编辑</a>
    </div>
    <div class="deliverWrap">
        <table class="table2">
            
            <thead>
            <tr>
                <th>选择地区</th>
                <th>操作</th>
            </tr>
            </thead>
            <tbody id="J_Tbody2">
            </tbody>
        </table>
    </div>
</li>
<div style="width: 100%; left: 0px; top: 0px; height: 100%; position: fixed; z-index: 3; display: none;" class="ks-dialog-mask2"></div>

<div class="dialog-areas dialog2" style="visibility: visible; right: 450px; top: 480px;z-index: 5;"><a tabindex="0" href='javascript:void("关闭")' style="z-index:9" class="ks-ext-close"><span class="ks-ext-close-x">关闭</span></a>
        <div class="ks-contentbox">
            <div class="ks-stdmod-header">
                <div class="title">选择区域</div>
            </div>
            <div class="ks-stdmod-body">
                <form id="form2">
                    <ul id="J_City2List2">
                        <li>
                            <div class=" dcity2 clearfix">
                                <div class="ecity2 gcity2">
                                    <span class="group-label2"><input type="checkbox" value="310000,320000,330000,340000,360000" class="J_Group2" id="J_Group2_0">
            <label for="J_Group2_0">华东</label></span>
                                </div>
                                <div class="province-list2">
                                    <div class="ecity2">
                                        <span class="gareas2"><input type="checkbox" value="310000" id="J_Province2_310000" class="J_Province22">
        <label for="J_Province2_310000">上海</label><span class="check_num2"></span><img class="trigger2" src="//gtd.alicdn.com/tps/i1/T1XZCWXd8iXXXXXXXX-8-8.gif"></span>
                                        <div class="citys2">
                                            <span class="areas2"><input type="checkbox" value="310100" id="J_City2_310100" class="J_City2">
        <label for="J_City2_310100">上海</label></span>
                                            <p style="text-align:right;"><input type="button" value="关闭" class="close_button2"></p>
                                        </div>
                                    </div>
                                    <div class="ecity2">
                                        <span class="gareas2"><input type="checkbox" value="320000" id="J_Province2_320000" class="J_Province22">
        <label for="J_Province2_320000">江苏</label><span class="check_num2"></span><img class="trigger2" src="//gtd.alicdn.com/tps/i1/T1XZCWXd8iXXXXXXXX-8-8.gif"></span>
                                        <div class="citys2">
                                            <span class="areas2"><input type="checkbox" value="320100" id="J_City2_320100" class="J_City2">
        <label for="J_City2_320100">南京</label></span>
                                            <span class="areas2"><input type="checkbox" value="320200" id="J_City2_320200" class="J_City2">
        <label for="J_City2_320200">无锡</label></span>
                                            <span class="areas2"><input type="checkbox" value="320300" id="J_City2_320300" class="J_City2">
        <label for="J_City2_320300">徐州</label></span>
                                            <span class="areas2"><input type="checkbox" value="320400" id="J_City2_320400" class="J_City2">
        <label for="J_City2_320400">常州</label></span>
                                            <span class="areas2"><input type="checkbox" value="320500" id="J_City2_320500" class="J_City2">
        <label for="J_City2_320500">苏州</label></span>
                                            <span class="areas2"><input type="checkbox" value="320600" id="J_City2_320600" class="J_City2">
        <label for="J_City2_320600">南通</label></span>
                                            <span class="areas2"><input type="checkbox" value="320700" id="J_City2_320700" class="J_City2">
        <label for="J_City2_320700">连云港</label></span>
                                            <span class="areas2"><input type="checkbox" value="320800" id="J_City2_320800" class="J_City2">
        <label for="J_City2_320800">淮安</label></span>
                                            <span class="areas2"><input type="checkbox" value="320900" id="J_City2_320900" class="J_City2">
        <label for="J_City2_320900">盐城</label></span>
                                            <span class="areas2"><input type="checkbox" value="321000" id="J_City2_321000" class="J_City2">
        <label for="J_City2_321000">扬州</label></span>
                                            <span class="areas2"><input type="checkbox" value="321100" id="J_City2_321100" class="J_City2">
        <label for="J_City2_321100">镇江</label></span>
                                            <span class="areas2"><input type="checkbox" value="321200" id="J_City2_321200" class="J_City2">
        <label for="J_City2_321200">泰州</label></span>
                                            <span class="areas2"><input type="checkbox" value="321300" id="J_City2_321300" class="J_City2">
        <label for="J_City2_321300">宿迁</label></span>
                                            <p style="text-align:right;"><input type="button" value="关闭" class="close_button2"></p>
                                        </div>
                                    </div>
                                    <div class="ecity2">
                                        <span class="gareas2"><input type="checkbox" value="330000" id="J_Province2_330000" class="J_Province2">
        <label for="J_Province2_330000">浙江</label><span class="check_num2"></span><img class="trigger2" src="//gtd.alicdn.com/tps/i1/T1XZCWXd8iXXXXXXXX-8-8.gif"></span>
                                        <div class="citys2">
                                            <span class="areas2"><input type="checkbox" value="330100" id="J_City2_330100" class="J_City2">
        <label for="J_City2_330100">杭州</label></span>
                                            <span class="areas2"><input type="checkbox" value="330200" id="J_City2_330200" class="J_City2">
        <label for="J_City2_330200">宁波</label></span>
                                            <span class="areas2"><input type="checkbox" value="330300" id="J_City2_330300" class="J_City2">
        <label for="J_City2_330300">温州</label></span>
                                            <span class="areas2"><input type="checkbox" value="330400" id="J_City2_330400" class="J_City2">
        <label for="J_City2_330400">嘉兴</label></span>
                                            <span class="areas2"><input type="checkbox" value="330500" id="J_City2_330500" class="J_City2">
        <label for="J_City2_330500">湖州</label></span>
                                            <span class="areas2"><input type="checkbox" value="330600" id="J_City2_330600" class="J_City2">
        <label for="J_City2_330600">绍兴</label></span>
                                            <span class="areas2"><input type="checkbox" value="330700" id="J_City2_330700" class="J_City2">
        <label for="J_City2_330700">金华</label></span>
                                            <span class="areas2"><input type="checkbox" value="330800" id="J_City2_330800" class="J_City2">
        <label for="J_City2_330800">衢州</label></span>
                                            <span class="areas2"><input type="checkbox" value="330900" id="J_City2_330900" class="J_City2">
        <label for="J_City2_330900">舟山</label></span>
                                            <span class="areas2"><input type="checkbox" value="331000" id="J_City2_331000" class="J_City2">
        <label for="J_City2_331000">台州</label></span>
                                            <span class="areas2"><input type="checkbox" value="331100" id="J_City2_331100" class="J_City2">
        <label for="J_City2_331100">丽水</label></span>
                                            <p style="text-align:right;"><input type="button" value="关闭" class="close_button2"></p>
                                        </div>
                                    </div>
                                    <div class="ecity2">
                                        <span class="gareas2"><input type="checkbox" value="340000" id="J_Province2_340000" class="J_Province22">
        <label for="J_Province2_340000">安徽</label><span class="check_num2"></span><img class="trigger2" src="//gtd.alicdn.com/tps/i1/T1XZCWXd8iXXXXXXXX-8-8.gif"></span>
                                        <div class="citys2">
                                            <span class="areas2"><input type="checkbox" value="340100" id="J_City2_340100" class="J_City2">
        <label for="J_City2_340100">合肥</label></span>
                                            <span class="areas2"><input type="checkbox" value="340200" id="J_City2_340200" class="J_City2">
        <label for="J_City2_340200">芜湖</label></span>
                                            <span class="areas2"><input type="checkbox" value="340300" id="J_City2_340300" class="J_City2">
        <label for="J_City2_340300">蚌埠</label></span>
                                            <span class="areas2"><input type="checkbox" value="340400" id="J_City2_340400" class="J_City2">
        <label for="J_City2_340400">淮南</label></span>
                                            <span class="areas2"><input type="checkbox" value="340500" id="J_City2_340500" class="J_City2">
        <label for="J_City2_340500">马鞍山</label></span>
                                            <span class="areas2"><input type="checkbox" value="340600" id="J_City2_340600" class="J_City2">
        <label for="J_City2_340600">淮北</label></span>
                                            <span class="areas2"><input type="checkbox" value="340700" id="J_City2_340700" class="J_City2">
        <label for="J_City2_340700">铜陵</label></span>
                                            <span class="areas2"><input type="checkbox" value="340800" id="J_City2_340800" class="J_City2">
        <label for="J_City2_340800">安庆</label></span>
                                            <span class="areas2"><input type="checkbox" value="341000" id="J_City2_341000" class="J_City2">
        <label for="J_City2_341000">黄山</label></span>
                                            <span class="areas2"><input type="checkbox" value="341100" id="J_City2_341100" class="J_City2">
        <label for="J_City2_341100">滁州</label></span>
                                            <span class="areas2"><input type="checkbox" value="341200" id="J_City2_341200" class="J_City2">
        <label for="J_City2_341200">阜阳</label></span>
                                            <span class="areas2"><input type="checkbox" value="341300" id="J_City2_341300" class="J_City2">
        <label for="J_City2_341300">宿州</label></span>
                                            <span class="areas2"><input type="checkbox" value="341500" id="J_City2_341500" class="J_City2">
        <label for="J_City2_341500">六安</label></span>
                                            <span class="areas2"><input type="checkbox" value="341600" id="J_City2_341600" class="J_City2">
        <label for="J_City2_341600">亳州</label></span>
                                            <span class="areas2"><input type="checkbox" value="341700" id="J_City2_341700" class="J_City2">
        <label for="J_City2_341700">池州</label></span>
                                            <span class="areas2"><input type="checkbox" value="341800" id="J_City2_341800" class="J_City2">
        <label for="J_City2_341800">宣城</label></span>
                                            <p style="text-align:right;"><input type="button" value="关闭" class="close_button2"></p>
                                        </div>
                                    </div>
                                    <div class="ecity2">
                                        <span class="gareas2"><input type="checkbox" value="360000" id="J_Province2_360000" class="J_Province22">
        <label for="J_Province2_360000">江西</label><span class="check_num2"></span><img class="trigger2" src="//gtd.alicdn.com/tps/i1/T1XZCWXd8iXXXXXXXX-8-8.gif"></span>
                                        <div class="citys2">
                                            <span class="areas2"><input type="checkbox" value="360100" id="J_City2_360100" class="J_City2">
        <label for="J_City2_360100">南昌</label></span>
                                            <span class="areas2"><input type="checkbox" value="360200" id="J_City2_360200" class="J_City2">
        <label for="J_City2_360200">景德镇</label></span>
                                            <span class="areas2"><input type="checkbox" value="360300" id="J_City2_360300" class="J_City2">
        <label for="J_City2_360300">萍乡</label></span>
                                            <span class="areas2"><input type="checkbox" value="360400" id="J_City2_360400" class="J_City2">
        <label for="J_City2_360400">九江</label></span>
                                            <span class="areas2"><input type="checkbox" value="360500" id="J_City2_360500" class="J_City2">
        <label for="J_City2_360500">新余</label></span>
                                            <span class="areas2"><input type="checkbox" value="360600" id="J_City2_360600" class="J_City2">
        <label for="J_City2_360600">鹰潭</label></span>
                                            <span class="areas2"><input type="checkbox" value="360700" id="J_City2_360700" class="J_City2">
        <label for="J_City2_360700">赣州</label></span>
                                            <span class="areas2"><input type="checkbox" value="360800" id="J_City2_360800" class="J_City2">
        <label for="J_City2_360800">吉安</label></span>
                                            <span class="areas2"><input type="checkbox" value="360900" id="J_City2_360900" class="J_City2">
        <label for="J_City2_360900">宜春</label></span>
                                            <span class="areas2"><input type="checkbox" value="361000" id="J_City2_361000" class="J_City2">
        <label for="J_City2_361000">抚州</label></span>
                                            <span class="areas2"><input type="checkbox" value="361100" id="J_City2_361100" class="J_City2">
        <label for="J_City2_361100">上饶</label></span>
                                            <p style="text-align:right;"><input type="button" value="关闭" class="close_button2"></p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </li>
                        <li class="even">
                            <div class=" dcity2 clearfix">
                                <div class="ecity2 gcity2">
                                    <span class="group-label2"><input type="checkbox" value="110000,120000,140000,370000,130000,150000" class="J_Group" id="J_Group2_1">
            <label for="J_Group_1">华北</label></span>
                                </div>
                                <div class="province-list2">
                                    <div class="ecity2">
                                        <span class="gareas2"><input type="checkbox" value="110000" id="J_Province2_110000" class="J_Province22">
        <label for="J_Province2_110000">北京</label><span class="check_num2"></span><img class="trigger2" src="//gtd.alicdn.com/tps/i1/T1XZCWXd8iXXXXXXXX-8-8.gif"></span>
                                        <div class="citys2">
                                            <span class="areas2"><input type="checkbox" value="110100" id="J_City2_110100" class="J_City2">
        <label for="J_City2_110100">北京</label></span>
                                            <p style="text-align:right;"><input type="button" value="关闭" class="close_button2"></p>
                                        </div>
                                    </div>
                                    <div class="ecity2">
                                        <span class="gareas2"><input type="checkbox" value="120000" id="J_Province2_120000" class="J_Province22">
        <label for="J_Province2_120000">天津</label><span class="check_num2"></span><img class="trigger2" src="//gtd.alicdn.com/tps/i1/T1XZCWXd8iXXXXXXXX-8-8.gif"></span>
                                        <div class="citys2">
                                            <span class="areas2"><input type="checkbox" value="120100" id="J_City2_120100" class="J_City2">
        <label for="J_City2_120100">天津</label></span>
                                            <p style="text-align:right;"><input type="button" value="关闭" class="close_button2"></p>
                                        </div>
                                    </div>
                                    <div class="ecity2">
                                        <span class="gareas2"><input type="checkbox" value="140000" id="J_Province2_140000" class="J_Province22">
        <label for="J_Province2_140000">山西</label><span class="check_num2"></span><img class="trigger2" src="//gtd.alicdn.com/tps/i1/T1XZCWXd8iXXXXXXXX-8-8.gif"></span>
                                        <div class="citys2">
                                            <span class="areas2"><input type="checkbox" value="140100" id="J_City2_140100" class="J_City2">
        <label for="J_City2_140100">太原</label></span>
                                            <span class="areas2"><input type="checkbox" value="140200" id="J_City2_140200" class="J_City2">
        <label for="J_City2_140200">大同</label></span>
                                            <span class="areas2"><input type="checkbox" value="140300" id="J_City2_140300" class="J_City2">
        <label for="J_City2_140300">阳泉</label></span>
                                            <span class="areas2"><input type="checkbox" value="140400" id="J_City2_140400" class="J_City2">
        <label for="J_City2_140400">长治</label></span>
                                            <span class="areas2"><input type="checkbox" value="140500" id="J_City2_140500" class="J_City2">
        <label for="J_City2_140500">晋城</label></span>
                                            <span class="areas2"><input type="checkbox" value="140600" id="J_City2_140600" class="J_City2">
        <label for="J_City2_140600">朔州</label></span>
                                            <span class="areas2"><input type="checkbox" value="140700" id="J_City2_140700" class="J_City2">
        <label for="J_City2_140700">晋中</label></span>
                                            <span class="areas2"><input type="checkbox" value="140800" id="J_City2_140800" class="J_City2">
        <label for="J_City2_140800">运城</label></span>
                                            <span class="areas2"><input type="checkbox" value="140900" id="J_City2_140900" class="J_City2">
        <label for="J_City2_140900">忻州</label></span>
                                            <span class="areas2"><input type="checkbox" value="141000" id="J_City2_141000" class="J_City2">
        <label for="J_City2_141000">临汾</label></span>
                                            <span class="areas2"><input type="checkbox" value="141100" id="J_City2_141100" class="J_City2">
        <label for="J_City2_141100">吕梁</label></span>
                                            <p style="text-align:right;"><input type="button" value="关闭" class="close_button2"></p>
                                        </div>
                                    </div>
                                    <div class="ecity2">
                                        <span class="gareas2"><input type="checkbox" value="370000" id="J_Province2_370000" class="J_Province22">
        <label for="J_Province2_370000">山东</label><span class="check_num2"></span><img class="trigger2" src="//gtd.alicdn.com/tps/i1/T1XZCWXd8iXXXXXXXX-8-8.gif"></span>
                                        <div class="citys2">
                                            <span class="areas2"><input type="checkbox" value="370100" id="J_City2_370100" class="J_City2">
        <label for="J_City2_370100">济南</label></span>
                                            <span class="areas2"><input type="checkbox" value="370200" id="J_City2_370200" class="J_City2">
        <label for="J_City2_370200">青岛</label></span>
                                            <span class="areas2"><input type="checkbox" value="370300" id="J_City2_370300" class="J_City2">
        <label for="J_City2_370300">淄博</label></span>
                                            <span class="areas2"><input type="checkbox" value="370400" id="J_City2_370400" class="J_City2">
        <label for="J_City2_370400">枣庄</label></span>
                                            <span class="areas2"><input type="checkbox" value="370500" id="J_City2_370500" class="J_City2">
        <label for="J_City2_370500">东营</label></span>
                                            <span class="areas2"><input type="checkbox" value="370600" id="J_City2_370600" class="J_City2">
        <label for="J_City2_370600">烟台</label></span>
                                            <span class="areas2"><input type="checkbox" value="370700" id="J_City2_370700" class="J_City2">
        <label for="J_City2_370700">潍坊</label></span>
                                            <span class="areas2"><input type="checkbox" value="370800" id="J_City2_370800" class="J_City2">
        <label for="J_City2_370800">济宁</label></span>
                                            <span class="areas2"><input type="checkbox" value="370900" id="J_City2_370900" class="J_City2">
        <label for="J_City2_370900">泰安</label></span>
                                            <span class="areas2"><input type="checkbox" value="371000" id="J_City2_371000" class="J_City2">
        <label for="J_City2_371000">威海</label></span>
                                            <span class="areas2"><input type="checkbox" value="371100" id="J_City2_371100" class="J_City2">
        <label for="J_City2_371100">日照</label></span>
                                            <span class="areas2"><input type="checkbox" value="371200" id="J_City2_371200" class="J_City2">
        <label for="J_City2_371200">莱芜</label></span>
                                            <span class="areas2"><input type="checkbox" value="371300" id="J_City2_371300" class="J_City2">
        <label for="J_City2_371300">临沂</label></span>
                                            <span class="areas2"><input type="checkbox" value="371400" id="J_City2_371400" class="J_City2">
        <label for="J_City2_371400">德州</label></span>
                                            <span class="areas2"><input type="checkbox" value="371500" id="J_City2_371500" class="J_City2">
        <label for="J_City2_371500">聊城</label></span>
                                            <span class="areas2"><input type="checkbox" value="371600" id="J_City2_371600" class="J_City2">
        <label for="J_City2_371600">滨州</label></span>
                                            <span class="areas2"><input type="checkbox" value="371700" id="J_City2_371700" class="J_City2">
        <label for="J_City2_371700">菏泽</label></span>
                                            <p style="text-align:right;"><input type="button" value="关闭" class="close_button2"></p>
                                        </div>
                                    </div>
                                    <div class="ecity2">
                                        <span class="gareas2"><input type="checkbox" value="130000" id="J_Province2_130000" class="J_Province22">
        <label for="J_Province2_130000">河北</label><span class="check_num2"></span><img class="trigger2" src="//gtd.alicdn.com/tps/i1/T1XZCWXd8iXXXXXXXX-8-8.gif"></span>
                                        <div class="citys2">
                                            <span class="areas2"><input type="checkbox" value="130100" id="J_City2_130100" class="J_City2">
        <label for="J_City2_130100">石家庄</label></span>
                                            <span class="areas2"><input type="checkbox" value="130200" id="J_City2_130200" class="J_City2">
        <label for="J_City2_130200">唐山</label></span>
                                            <span class="areas2"><input type="checkbox" value="130300" id="J_City2_130300" class="J_City2">
        <label for="J_City2_130300">秦皇岛</label></span>
                                            <span class="areas2"><input type="checkbox" value="130400" id="J_City2_130400" class="J_City2">
        <label for="J_City2_130400">邯郸</label></span>
                                            <span class="areas2"><input type="checkbox" value="130500" id="J_City2_130500" class="J_City2">
        <label for="J_City2_130500">邢台</label></span>
                                            <span class="areas2"><input type="checkbox" value="130600" id="J_City2_130600" class="J_City2">
        <label for="J_City2_130600">保定</label></span>
                                            <span class="areas2"><input type="checkbox" value="130700" id="J_City2_130700" class="J_City2">
        <label for="J_City2_130700">张家口</label></span>
                                            <span class="areas2"><input type="checkbox" value="130800" id="J_City2_130800" class="J_City2">
        <label for="J_City2_130800">承德</label></span>
                                            <span class="areas2"><input type="checkbox" value="130900" id="J_City2_130900" class="J_City2">
        <label for="J_City2_130900">沧州</label></span>
                                            <span class="areas2"><input type="checkbox" value="131000" id="J_City2_131000" class="J_City2">
        <label for="J_City2_131000">廊坊</label></span>
                                            <span class="areas2"><input type="checkbox" value="131100" id="J_City2_131100" class="J_City2">
        <label for="J_City2_131100">衡水</label></span>
                                            <p style="text-align:right;"><input type="button" value="关闭" class="close_button2"></p>
                                        </div>
                                    </div>
                                    <div class="ecity2">
                                        <span class="gareas2"><input type="checkbox" value="150000" id="J_Province2_150000" class="J_Province22">
        <label for="J_Province2_150000">内蒙古</label><span class="check_num2"></span><img class="trigger2" src="//gtd.alicdn.com/tps/i1/T1XZCWXd8iXXXXXXXX-8-8.gif"></span>
                                        <div class="citys2">
                                            <span class="areas2"><input type="checkbox" value="150100" id="J_City2_150100" class="J_City2">
        <label for="J_City2_150100">呼和浩特</label></span>
                                            <span class="areas2"><input type="checkbox" value="150200" id="J_City2_150200" class="J_City2">
        <label for="J_City2_150200">包头</label></span>
                                            <span class="areas2"><input type="checkbox" value="150300" id="J_City2_150300" class="J_City2">
        <label for="J_City2_150300">乌海</label></span>
                                            <span class="areas2"><input type="checkbox" value="150400" id="J_City2_150400" class="J_City2">
        <label for="J_City2_150400">赤峰</label></span>
                                            <span class="areas2"><input type="checkbox" value="150500" id="J_City2_150500" class="J_City2">
        <label for="J_City2_150500">通辽</label></span>
                                            <span class="areas2"><input type="checkbox" value="150600" id="J_City2_150600" class="J_City2">
        <label for="J_City2_150600">鄂尔多斯</label></span>
                                            <span class="areas2"><input type="checkbox" value="150700" id="J_City2_150700" class="J_City2">
        <label for="J_City2_150700">呼伦贝尔</label></span>
                                            <span class="areas2"><input type="checkbox" value="150800" id="J_City2_150800" class="J_City2">
        <label for="J_City2_150800">巴彦淖尔</label></span>
                                            <span class="areas2"><input type="checkbox" value="150900" id="J_City2_150900" class="J_City2">
        <label for="J_City2_150900">乌兰察布</label></span>
                                            <span class="areas2"><input type="checkbox" value="152200" id="J_City2_152200" class="J_City2">
        <label for="J_City2_152200">兴安</label></span>
                                            <span class="areas2"><input type="checkbox" value="152500" id="J_City2_152500" class="J_City2">
        <label for="J_City2_152500">锡林郭勒</label></span>
                                            <span class="areas2"><input type="checkbox" value="152900" id="J_City2_152900" class="J_City2">
        <label for="J_City2_152900">阿拉善</label></span>
                                            <p style="text-align:right;"><input type="button" value="关闭" class="close_button2"></p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </li>
                        <li>
                            <div class=" dcity2 clearfix">
                                <div class="ecity2 gcity2">
                                    <span class="group-label2"><input type="checkbox" value="430000,420000,410000" class="J_Group" id="J_Group2_2">
            <label for="J_Group_2">华中</label></span>
                                </div>
                                <div class="province-list2">
                                    <div class="ecity2">
                                        <span class="gareas2"><input type="checkbox" value="430000" id="J_Province2_430000" class="J_Province22">
        <label for="J_Province2_430000">湖南</label><span class="check_num2"></span><img class="trigger2" src="//gtd.alicdn.com/tps/i1/T1XZCWXd8iXXXXXXXX-8-8.gif"></span>
                                        <div class="citys2">
                                            <span class="areas2"><input type="checkbox" value="430100" id="J_City2_430100" class="J_City2">
        <label for="J_City2_430100">长沙</label></span>
                                            <span class="areas2"><input type="checkbox" value="430200" id="J_City2_430200" class="J_City2">
        <label for="J_City2_430200">株洲</label></span>
                                            <span class="areas2"><input type="checkbox" value="430300" id="J_City2_430300" class="J_City2">
        <label for="J_City2_430300">湘潭</label></span>
                                            <span class="areas2"><input type="checkbox" value="430400" id="J_City2_430400" class="J_City2">
        <label for="J_City2_430400">衡阳</label></span>
                                            <span class="areas2"><input type="checkbox" value="430500" id="J_City2_430500" class="J_City2">
        <label for="J_City2_430500">邵阳</label></span>
                                            <span class="areas2"><input type="checkbox" value="430600" id="J_City2_430600" class="J_City2">
        <label for="J_City2_430600">岳阳</label></span>
                                            <span class="areas2"><input type="checkbox" value="430700" id="J_City2_430700" class="J_City2">
        <label for="J_City2_430700">常德</label></span>
                                            <span class="areas2"><input type="checkbox" value="430800" id="J_City2_430800" class="J_City2">
        <label for="J_City2_430800">张家界</label></span>
                                            <span class="areas2"><input type="checkbox" value="430900" id="J_City2_430900" class="J_City2">
        <label for="J_City2_430900">益阳</label></span>
                                            <span class="areas2"><input type="checkbox" value="431000" id="J_City2_431000" class="J_City2">
        <label for="J_City2_431000">郴州</label></span>
                                            <span class="areas2"><input type="checkbox" value="431100" id="J_City2_431100" class="J_City2">
        <label for="J_City2_431100">永州</label></span>
                                            <span class="areas2"><input type="checkbox" value="431200" id="J_City2_431200" class="J_City2">
        <label for="J_City2_431200">怀化</label></span>
                                            <span class="areas2"><input type="checkbox" value="431300" id="J_City2_431300" class="J_City2">
        <label for="J_City2_431300">娄底</label></span>
                                            <span class="areas2"><input type="checkbox" value="433100" id="J_City2_433100" class="J_City2">
        <label for="J_City2_433100">湘西</label></span>
                                            <p style="text-align:right;"><input type="button" value="关闭" class="close_button2"></p>
                                        </div>
                                    </div>
                                    <div class="ecity2">
                                        <span class="gareas2"><input type="checkbox" value="420000" id="J_Province2_420000" class="J_Province22">
        <label for="J_Province2_420000">湖北</label><span class="check_num2"></span><img class="trigger2" src="//gtd.alicdn.com/tps/i1/T1XZCWXd8iXXXXXXXX-8-8.gif"></span>
                                        <div class="citys2">
                                            <span class="areas2"><input type="checkbox" value="420100" id="J_City2_420100" class="J_City2">
        <label for="J_City2_420100">武汉</label></span>
                                            <span class="areas2"><input type="checkbox" value="420200" id="J_City2_420200" class="J_City2">
        <label for="J_City2_420200">黄石</label></span>
                                            <span class="areas2"><input type="checkbox" value="420300" id="J_City2_420300" class="J_City2">
        <label for="J_City2_420300">十堰</label></span>
                                            <span class="areas2"><input type="checkbox" value="420500" id="J_City2_420500" class="J_City2">
        <label for="J_City2_420500">宜昌</label></span>
                                            <span class="areas2"><input type="checkbox" value="420600" id="J_City2_420600" class="J_City2">
        <label for="J_City2_420600">襄阳</label></span>
                                            <span class="areas2"><input type="checkbox" value="420700" id="J_City2_420700" class="J_City2">
        <label for="J_City2_420700">鄂州</label></span>
                                            <span class="areas2"><input type="checkbox" value="420800" id="J_City2_420800" class="J_City2">
        <label for="J_City2_420800">荆门</label></span>
                                            <span class="areas2"><input type="checkbox" value="420900" id="J_City2_420900" class="J_City2">
        <label for="J_City2_420900">孝感</label></span>
                                            <span class="areas2"><input type="checkbox" value="421000" id="J_City2_421000" class="J_City2">
        <label for="J_City2_421000">荆州</label></span>
                                            <span class="areas2"><input type="checkbox" value="421100" id="J_City2_421100" class="J_City2">
        <label for="J_City2_421100">黄冈</label></span>
                                            <span class="areas2"><input type="checkbox" value="421200" id="J_City2_421200" class="J_City2">
        <label for="J_City2_421200">咸宁</label></span>
                                            <span class="areas2"><input type="checkbox" value="421300" id="J_City2_421300" class="J_City2">
        <label for="J_City2_421300">随州</label></span>
                                            <span class="areas2"><input type="checkbox" value="422800" id="J_City2_422800" class="J_City2">
        <label for="J_City2_422800">恩施</label></span>
                                            <span class="areas2"><input type="checkbox" value="429004" id="J_City2_429004" class="J_City2">
        <label for="J_City2_429004">仙桃</label></span>
                                            <span class="areas2"><input type="checkbox" value="429005" id="J_City2_429005" class="J_City2">
        <label for="J_City2_429005">潜江</label></span>
                                            <span class="areas2"><input type="checkbox" value="429006" id="J_City2_429006" class="J_City2">
        <label for="J_City2_429006">天门</label></span>
                                            <span class="areas2"><input type="checkbox" value="429021" id="J_City2_429021" class="J_City2">
        <label for="J_City2_429021">神农架</label></span>
                                            <p style="text-align:right;"><input type="button" value="关闭" class="close_button2"></p>
                                        </div>
                                    </div>
                                    <div class="ecity2">
                                        <span class="gareas2"><input type="checkbox" value="410000" id="J_Province2_410000" class="J_Province22">
        <label for="J_Province2_410000">河南</label><span class="check_num2"></span><img class="trigger2" src="//gtd.alicdn.com/tps/i1/T1XZCWXd8iXXXXXXXX-8-8.gif"></span>
                                        <div class="citys2">
                                            <span class="areas2"><input type="checkbox" value="410100" id="J_City2_410100" class="J_City2">
        <label for="J_City2_410100">郑州</label></span>
                                            <span class="areas2"><input type="checkbox" value="410200" id="J_City2_410200" class="J_City2">
        <label for="J_City2_410200">开封</label></span>
                                            <span class="areas2"><input type="checkbox" value="410300" id="J_City2_410300" class="J_City2">
        <label for="J_City2_410300">洛阳</label></span>
                                            <span class="areas2"><input type="checkbox" value="410400" id="J_City2_410400" class="J_City2">
        <label for="J_City2_410400">平顶山</label></span>
                                            <span class="areas2"><input type="checkbox" value="410500" id="J_City2_410500" class="J_City2">
        <label for="J_City2_410500">安阳</label></span>
                                            <span class="areas2"><input type="checkbox" value="410600" id="J_City2_410600" class="J_City2">
        <label for="J_City2_410600">鹤壁</label></span>
                                            <span class="areas2"><input type="checkbox" value="410700" id="J_City2_410700" class="J_City2">
        <label for="J_City2_410700">新乡</label></span>
                                            <span class="areas2"><input type="checkbox" value="410800" id="J_City2_410800" class="J_City2">
        <label for="J_City2_410800">焦作</label></span>
                                            <span class="areas2"><input type="checkbox" value="410881" id="J_City2_410881" class="J_City2">
        <label for="J_City2_410881">济源</label></span>
                                            <span class="areas2"><input type="checkbox" value="410900" id="J_City2_410900" class="J_City2">
        <label for="J_City2_410900">濮阳</label></span>
                                            <span class="areas2"><input type="checkbox" value="411000" id="J_City2_411000" class="J_City2">
        <label for="J_City2_411000">许昌</label></span>
                                            <span class="areas2"><input type="checkbox" value="411100" id="J_City2_411100" class="J_City2">
        <label for="J_City2_411100">漯河</label></span>
                                            <span class="areas2"><input type="checkbox" value="411200" id="J_City2_411200" class="J_City2">
        <label for="J_City2_411200">三门峡</label></span>
                                            <span class="areas2"><input type="checkbox" value="411300" id="J_City2_411300" class="J_City2">
        <label for="J_City2_411300">南阳</label></span>
                                            <span class="areas2"><input type="checkbox" value="411400" id="J_City2_411400" class="J_City2">
        <label for="J_City2_411400">商丘</label></span>
                                            <span class="areas2"><input type="checkbox" value="411500" id="J_City2_411500" class="J_City2">
        <label for="J_City2_411500">信阳</label></span>
                                            <span class="areas2"><input type="checkbox" value="411600" id="J_City2_411600" class="J_City2">
        <label for="J_City2_411600">周口</label></span>
                                            <span class="areas2"><input type="checkbox" value="411700" id="J_City2_411700" class="J_City2">
        <label for="J_City2_411700">驻马店</label></span>
                                            <p style="text-align:right;"><input type="button" value="关闭" class="close_button2"></p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </li>
                        <li class="even">
                            <div class=" dcity2 clearfix">
                                <div class="ecity2 gcity2">
                                    <span class="group-label2"><input type="checkbox" value="440000,450000,350000,460000" class="J_Group" id="J_Group2_3">
            <label for="J_Group_3">华南</label></span>
                                </div>
                                <div class="province-list2">
                                    <div class="ecity2">
                                        <span class="gareas2"><input type="checkbox" value="440000" id="J_Province2_440000" class="J_Province22">
        <label for="J_Province2_440000">广东</label><span class="check_num2"></span><img class="trigger2" src="//gtd.alicdn.com/tps/i1/T1XZCWXd8iXXXXXXXX-8-8.gif"></span>
                                        <div class="citys2">
                                            <span class="areas2"><input type="checkbox" value="440100" id="J_City2_440100" class="J_City2">
        <label for="J_City2_440100">广州</label></span>
                                            <span class="areas2"><input type="checkbox" value="440200" id="J_City2_440200" class="J_City2">
        <label for="J_City2_440200">韶关</label></span>
                                            <span class="areas2"><input type="checkbox" value="440300" id="J_City2_440300" class="J_City2">
        <label for="J_City2_440300">深圳</label></span>
                                            <span class="areas2"><input type="checkbox" value="440400" id="J_City2_440400" class="J_City2">
        <label for="J_City2_440400">珠海</label></span>
                                            <span class="areas2"><input type="checkbox" value="440500" id="J_City2_440500" class="J_City2">
        <label for="J_City2_440500">汕头</label></span>
                                            <span class="areas2"><input type="checkbox" value="440600" id="J_City2_440600" class="J_City2">
        <label for="J_City2_440600">佛山</label></span>
                                            <span class="areas2"><input type="checkbox" value="440700" id="J_City2_440700" class="J_City2">
        <label for="J_City2_440700">江门</label></span>
                                            <span class="areas2"><input type="checkbox" value="440800" id="J_City2_440800" class="J_City2">
        <label for="J_City2_440800">湛江</label></span>
                                            <span class="areas2"><input type="checkbox" value="440900" id="J_City2_440900" class="J_City2">
        <label for="J_City2_440900">茂名</label></span>
                                            <span class="areas2"><input type="checkbox" value="441200" id="J_City2_441200" class="J_City2">
        <label for="J_City2_441200">肇庆</label></span>
                                            <span class="areas2"><input type="checkbox" value="441300" id="J_City2_441300" class="J_City2">
        <label for="J_City2_441300">惠州</label></span>
                                            <span class="areas2"><input type="checkbox" value="441400" id="J_City2_441400" class="J_City2">
        <label for="J_City2_441400">梅州</label></span>
                                            <span class="areas2"><input type="checkbox" value="441500" id="J_City2_441500" class="J_City2">
        <label for="J_City2_441500">汕尾</label></span>
                                            <span class="areas2"><input type="checkbox" value="441600" id="J_City2_441600" class="J_City2">
        <label for="J_City2_441600">河源</label></span>
                                            <span class="areas2"><input type="checkbox" value="441700" id="J_City2_441700" class="J_City2">
        <label for="J_City2_441700">阳江</label></span>
                                            <span class="areas2"><input type="checkbox" value="441800" id="J_City2_441800" class="J_City2">
        <label for="J_City2_441800">清远</label></span>
                                            <span class="areas2"><input type="checkbox" value="441900" id="J_City2_441900" class="J_City2">
        <label for="J_City2_441900">东莞</label></span>
                                            <span class="areas2"><input type="checkbox" value="442000" id="J_City2_442000" class="J_City2">
        <label for="J_City2_442000">中山</label></span>
                                            <span class="areas2"><input type="checkbox" value="442101" id="J_City2_442101" class="J_City2">
        <label for="J_City2_442101">东沙</label></span>
                                            <span class="areas2"><input type="checkbox" value="445100" id="J_City2_445100" class="J_City2">
        <label for="J_City2_445100">潮州</label></span>
                                            <span class="areas2"><input type="checkbox" value="445200" id="J_City2_445200" class="J_City2">
        <label for="J_City2_445200">揭阳</label></span>
                                            <span class="areas2"><input type="checkbox" value="445300" id="J_City2_445300" class="J_City2">
        <label for="J_City2_445300">云浮</label></span>
                                            <p style="text-align:right;"><input type="button" value="关闭" class="close_button2"></p>
                                        </div>
                                    </div>
                                    <div class="ecity2">
                                        <span class="gareas2"><input type="checkbox" value="450000" id="J_Province2_450000" class="J_Province22">
        <label for="J_Province2_450000">广西</label><span class="check_num2"></span><img class="trigger2" src="//gtd.alicdn.com/tps/i1/T1XZCWXd8iXXXXXXXX-8-8.gif"></span>
                                        <div class="citys2">
                                            <span class="areas2"><input type="checkbox" value="450100" id="J_City2_450100" class="J_City2">
        <label for="J_City2_450100">南宁</label></span>
                                            <span class="areas2"><input type="checkbox" value="450200" id="J_City2_450200" class="J_City2">
        <label for="J_City2_450200">柳州</label></span>
                                            <span class="areas2"><input type="checkbox" value="450300" id="J_City2_450300" class="J_City2">
        <label for="J_City2_450300">桂林</label></span>
                                            <span class="areas2"><input type="checkbox" value="450400" id="J_City2_450400" class="J_City2">
        <label for="J_City2_450400">梧州</label></span>
                                            <span class="areas2"><input type="checkbox" value="450500" id="J_City2_450500" class="J_City2">
        <label for="J_City2_450500">北海</label></span>
                                            <span class="areas2"><input type="checkbox" value="450600" id="J_City2_450600" class="J_City2">
        <label for="J_City2_450600">防城港</label></span>
                                            <span class="areas2"><input type="checkbox" value="450700" id="J_City2_450700" class="J_City2">
        <label for="J_City2_450700">钦州</label></span>
                                            <span class="areas2"><input type="checkbox" value="450800" id="J_City2_450800" class="J_City2">
        <label for="J_City2_450800">贵港</label></span>
                                            <span class="areas2"><input type="checkbox" value="450900" id="J_City2_450900" class="J_City2">
        <label for="J_City2_450900">玉林</label></span>
                                            <span class="areas2"><input type="checkbox" value="451000" id="J_City2_451000" class="J_City2">
        <label for="J_City2_451000">百色</label></span>
                                            <span class="areas2"><input type="checkbox" value="451100" id="J_City2_451100" class="J_City2">
        <label for="J_City2_451100">贺州</label></span>
                                            <span class="areas2"><input type="checkbox" value="451200" id="J_City2_451200" class="J_City2">
        <label for="J_City2_451200">河池</label></span>
                                            <span class="areas2"><input type="checkbox" value="451300" id="J_City2_451300" class="J_City2">
        <label for="J_City2_451300">来宾</label></span>
                                            <span class="areas2"><input type="checkbox" value="451400" id="J_City2_451400" class="J_City2">
        <label for="J_City2_451400">崇左</label></span>
                                            <p style="text-align:right;"><input type="button" value="关闭" class="close_button2"></p>
                                        </div>
                                    </div>
                                    <div class="ecity2">
                                        <span class="gareas2"><input type="checkbox" value="350000" id="J_Province2_350000" class="J_Province22">
        <label for="J_Province2_350000">福建</label><span class="check_num2"></span><img class="trigger2" src="//gtd.alicdn.com/tps/i1/T1XZCWXd8iXXXXXXXX-8-8.gif"></span>
                                        <div class="citys2">
                                            <span class="areas2"><input type="checkbox" value="350100" id="J_City2_350100" class="J_City2">
        <label for="J_City2_350100">福州</label></span>
                                            <span class="areas2"><input type="checkbox" value="350200" id="J_City2_350200" class="J_City2">
        <label for="J_City2_350200">厦门</label></span>
                                            <span class="areas2"><input type="checkbox" value="350300" id="J_City2_350300" class="J_City2">
        <label for="J_City2_350300">莆田</label></span>
                                            <span class="areas2"><input type="checkbox" value="350400" id="J_City2_350400" class="J_City2">
        <label for="J_City2_350400">三明</label></span>
                                            <span class="areas2"><input type="checkbox" value="350500" id="J_City2_350500" class="J_City2">
        <label for="J_City2_350500">泉州</label></span>
                                            <span class="areas2"><input type="checkbox" value="350600" id="J_City2_350600" class="J_City2">
        <label for="J_City2_350600">漳州</label></span>
                                            <span class="areas2"><input type="checkbox" value="350700" id="J_City2_350700" class="J_City2">
        <label for="J_City2_350700">南平</label></span>
                                            <span class="areas2"><input type="checkbox" value="350800" id="J_City2_350800" class="J_City2">
        <label for="J_City2_350800">龙岩</label></span>
                                            <span class="areas2"><input type="checkbox" value="350900" id="J_City2_350900" class="J_City2">
        <label for="J_City2_350900">宁德</label></span>
                                            <p style="text-align:right;"><input type="button" value="关闭" class="close_button2"></p>
                                        </div>
                                    </div>
                                    <div class="ecity2">
                                        <span class="gareas2"><input type="checkbox" value="460000" id="J_Province2_460000" class="J_Province22">
        <label for="J_Province2_460000">海南</label><span class="check_num2"></span><img class="trigger2" src="//gtd.alicdn.com/tps/i1/T1XZCWXd8iXXXXXXXX-8-8.gif"></span>
                                        <div class="citys2">
                                            <span class="areas2"><input type="checkbox" value="460100" id="J_City2_460100" class="J_City2">
        <label for="J_City2_460100">海口</label></span>
                                            <span class="areas2"><input type="checkbox" value="460200" id="J_City2_460200" class="J_City2">
        <label for="J_City2_460200">三亚</label></span>
                                            <span class="areas2"><input type="checkbox" value="460300" id="J_City2_460300" class="J_City2">
        <label for="J_City2_460300">三沙</label></span>
                                            <span class="areas2"><input type="checkbox" value="469001" id="J_City2_469001" class="J_City2">
        <label for="J_City2_469001">五指山</label></span>
                                            <span class="areas2"><input type="checkbox" value="469002" id="J_City2_469002" class="J_City2">
        <label for="J_City2_469002">琼海</label></span>
                                            <span class="areas2"><input type="checkbox" value="469003" id="J_City2_469003" class="J_City2">
        <label for="J_City2_469003">儋州</label></span>
                                            <span class="areas2"><input type="checkbox" value="469005" id="J_City2_469005" class="J_City2">
        <label for="J_City2_469005">文昌</label></span>
                                            <span class="areas2"><input type="checkbox" value="469006" id="J_City2_469006" class="J_City2">
        <label for="J_City2_469006">万宁</label></span>
                                            <span class="areas2"><input type="checkbox" value="469007" id="J_City2_469007" class="J_City2">
        <label for="J_City2_469007">东方</label></span>
                                            <span class="areas2"><input type="checkbox" value="469025" id="J_City2_469025" class="J_City2">
        <label for="J_City2_469025">定安</label></span>
                                            <span class="areas2"><input type="checkbox" value="469026" id="J_City2_469026" class="J_City2">
        <label for="J_City2_469026">屯昌</label></span>
                                            <span class="areas2"><input type="checkbox" value="469027" id="J_City2_469027" class="J_City2">
        <label for="J_City2_469027">澄迈</label></span>
                                            <span class="areas2"><input type="checkbox" value="469028" id="J_City2_469028" class="J_City2">
        <label for="J_City2_469028">临高</label></span>
                                            <span class="areas2"><input type="checkbox" value="469030" id="J_City2_469030" class="J_City2">
        <label for="J_City2_469030">白沙</label></span>
                                            <span class="areas2"><input type="checkbox" value="469031" id="J_City2_469031" class="J_City2">
        <label for="J_City2_469031">昌江</label></span>
                                            <span class="areas2"><input type="checkbox" value="469033" id="J_City2_469033" class="J_City2">
        <label for="J_City2_469033">乐东</label></span>
                                            <span class="areas2"><input type="checkbox" value="469034" id="J_City2_469034" class="J_City2">
        <label for="J_City2_469034">陵水</label></span>
                                            <span class="areas2"><input type="checkbox" value="469035" id="J_City2_469035" class="J_City2">
        <label for="J_City2_469035">保亭</label></span>
                                            <span class="areas2"><input type="checkbox" value="469036" id="J_City2_469036" class="J_City2">
        <label for="J_City2_469036">琼中</label></span>
                                            <span class="areas2"><input type="checkbox" value="469037" id="J_City2_469037" class="J_City2">
        <label for="J_City2_469037">西沙</label></span>
                                            <span class="areas2"><input type="checkbox" value="469038" id="J_City2_469038" class="J_City2">
        <label for="J_City2_469038">南沙</label></span>
                                            <span class="areas2"><input type="checkbox" value="469039" id="J_City2_469039" class="J_City2">
        <label for="J_City2_469039">中沙</label></span>
                                            <p style="text-align:right;"><input type="button" value="关闭" class="close_button2"></p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </li>
                        <li>
                            <div class=" dcity2 clearfix">
                                <div class="ecity2 gcity2">
                                    <span class="group-label2"><input type="checkbox" value="210000,220000,230000" class="J_Group" id="J_Group2_4">
            <label for="J_Group_4">东北</label></span>
                                </div>
                                <div class="province-list2">
                                    <div class="ecity2">
                                        <span class="gareas2"><input type="checkbox" value="210000" id="J_Province2_210000" class="J_Province22">
        <label for="J_Province2_210000">辽宁</label><span class="check_num2"></span><img class="trigger2" src="//gtd.alicdn.com/tps/i1/T1XZCWXd8iXXXXXXXX-8-8.gif"></span>
                                        <div class="citys2">
                                            <span class="areas2"><input type="checkbox" value="210100" id="J_City2_210100" class="J_City2">
        <label for="J_City2_210100">沈阳</label></span>
                                            <span class="areas2"><input type="checkbox" value="210200" id="J_City2_210200" class="J_City2">
        <label for="J_City2_210200">大连</label></span>
                                            <span class="areas2"><input type="checkbox" value="210300" id="J_City2_210300" class="J_City2">
        <label for="J_City2_210300">鞍山</label></span>
                                            <span class="areas2"><input type="checkbox" value="210400" id="J_City2_210400" class="J_City2">
        <label for="J_City2_210400">抚顺</label></span>
                                            <span class="areas2"><input type="checkbox" value="210500" id="J_City2_210500" class="J_City2">
        <label for="J_City2_210500">本溪</label></span>
                                            <span class="areas2"><input type="checkbox" value="210600" id="J_City2_210600" class="J_City2">
        <label for="J_City2_210600">丹东</label></span>
                                            <span class="areas2"><input type="checkbox" value="210700" id="J_City2_210700" class="J_City2">
        <label for="J_City2_210700">锦州</label></span>
                                            <span class="areas2"><input type="checkbox" value="210800" id="J_City2_210800" class="J_City2">
        <label for="J_City2_210800">营口</label></span>
                                            <span class="areas2"><input type="checkbox" value="210900" id="J_City2_210900" class="J_City2">
        <label for="J_City2_210900">阜新</label></span>
                                            <span class="areas2"><input type="checkbox" value="211000" id="J_City2_211000" class="J_City2">
        <label for="J_City2_211000">辽阳</label></span>
                                            <span class="areas2"><input type="checkbox" value="211100" id="J_City2_211100" class="J_City2">
        <label for="J_City2_211100">盘锦</label></span>
                                            <span class="areas2"><input type="checkbox" value="211200" id="J_City2_211200" class="J_City2">
        <label for="J_City2_211200">铁岭</label></span>
                                            <span class="areas2"><input type="checkbox" value="211300" id="J_City2_211300" class="J_City2">
        <label for="J_City2_211300">朝阳</label></span>
                                            <span class="areas2"><input type="checkbox" value="211400" id="J_City2_211400" class="J_City2">
        <label for="J_City2_211400">葫芦岛</label></span>
                                            <p style="text-align:right;"><input type="button" value="关闭" class="close_button2"></p>
                                        </div>
                                    </div>
                                    <div class="ecity2">
                                        <span class="gareas2"><input type="checkbox" value="220000" id="J_Province2_220000" class="J_Province22">
        <label for="J_Province2_220000">吉林</label><span class="check_num2"></span><img class="trigger2" src="//gtd.alicdn.com/tps/i1/T1XZCWXd8iXXXXXXXX-8-8.gif"></span>
                                        <div class="citys2">
                                            <span class="areas2"><input type="checkbox" value="220100" id="J_City2_220100" class="J_City2">
        <label for="J_City2_220100">长春</label></span>
                                            <span class="areas2"><input type="checkbox" value="220200" id="J_City2_220200" class="J_City2">
        <label for="J_City2_220200">吉林</label></span>
                                            <span class="areas2"><input type="checkbox" value="220300" id="J_City2_220300" class="J_City2">
        <label for="J_City2_220300">四平</label></span>
                                            <span class="areas2"><input type="checkbox" value="220400" id="J_City2_220400" class="J_City2">
        <label for="J_City2_220400">辽源</label></span>
                                            <span class="areas2"><input type="checkbox" value="220500" id="J_City2_220500" class="J_City2">
        <label for="J_City2_220500">通化</label></span>
                                            <span class="areas2"><input type="checkbox" value="220600" id="J_City2_220600" class="J_City2">
        <label for="J_City2_220600">白山</label></span>
                                            <span class="areas2"><input type="checkbox" value="220700" id="J_City2_220700" class="J_City2">
        <label for="J_City2_220700">松原</label></span>
                                            <span class="areas2"><input type="checkbox" value="220800" id="J_City2_220800" class="J_City2">
        <label for="J_City2_220800">白城</label></span>
                                            <span class="areas2"><input type="checkbox" value="222400" id="J_City2_222400" class="J_City2">
        <label for="J_City2_222400">延边朝鲜族</label></span>
                                            <p style="text-align:right;"><input type="button" value="关闭" class="close_button2"></p>
                                        </div>
                                    </div>
                                    <div class="ecity2">
                                        <span class="gareas2"><input type="checkbox" value="230000" id="J_Province2_230000" class="J_Province22">
        <label for="J_Province2_230000">黑龙江</label><span class="check_num2"></span><img class="trigger2" src="//gtd.alicdn.com/tps/i1/T1XZCWXd8iXXXXXXXX-8-8.gif"></span>
                                        <div class="citys2">
                                            <span class="areas2"><input type="checkbox" value="230100" id="J_City2_230100" class="J_City2">
        <label for="J_City2_230100">哈尔滨</label></span>
                                            <span class="areas2"><input type="checkbox" value="230200" id="J_City2_230200" class="J_City2">
        <label for="J_City2_230200">齐齐哈尔</label></span>
                                            <span class="areas2"><input type="checkbox" value="230300" id="J_City2_230300" class="J_City2">
        <label for="J_City2_230300">鸡西</label></span>
                                            <span class="areas2"><input type="checkbox" value="230400" id="J_City2_230400" class="J_City2">
        <label for="J_City2_230400">鹤岗</label></span>
                                            <span class="areas2"><input type="checkbox" value="230500" id="J_City2_230500" class="J_City2">
        <label for="J_City2_230500">双鸭山</label></span>
                                            <span class="areas2"><input type="checkbox" value="230600" id="J_City2_230600" class="J_City2">
        <label for="J_City2_230600">大庆</label></span>
                                            <span class="areas2"><input type="checkbox" value="230700" id="J_City2_230700" class="J_City2">
        <label for="J_City2_230700">伊春</label></span>
                                            <span class="areas2"><input type="checkbox" value="230800" id="J_City2_230800" class="J_City2">
        <label for="J_City2_230800">佳木斯</label></span>
                                            <span class="areas2"><input type="checkbox" value="230900" id="J_City2_230900" class="J_City2">
        <label for="J_City2_230900">七台河</label></span>
                                            <span class="areas2"><input type="checkbox" value="231000" id="J_City2_231000" class="J_City2">
        <label for="J_City2_231000">牡丹江</label></span>
                                            <span class="areas2"><input type="checkbox" value="231100" id="J_City2_231100" class="J_City2">
        <label for="J_City2_231100">黑河</label></span>
                                            <span class="areas2"><input type="checkbox" value="231200" id="J_City2_231200" class="J_City2">
        <label for="J_City2_231200">绥化</label></span>
                                            <span class="areas2"><input type="checkbox" value="232700" id="J_City2_232700" class="J_City2">
        <label for="J_City2_232700">大兴安岭</label></span>
                                            <p style="text-align:right;"><input type="button" value="关闭" class="close_button2"></p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </li>
                        <li class="even">
                            <div class=" dcity2 clearfix">
                                <div class="ecity2 gcity2">
                                    <span class="group-label2"><input type="checkbox" value="610000,650000,620000,640000,630000" class="J_Group" id="J_Group2_5">
            <label for="J_Group_5">西北</label></span>
                                </div>
                                <div class="province-list2">
                                    <div class="ecity2">
                                        <span class="gareas2"><input type="checkbox" value="610000" id="J_Province2_610000" class="J_Province22">
        <label for="J_Province2_610000">陕西</label><span class="check_num2"></span><img class="trigger2" src="//gtd.alicdn.com/tps/i1/T1XZCWXd8iXXXXXXXX-8-8.gif"></span>
                                        <div class="citys2">
                                            <span class="areas2"><input type="checkbox" value="610100" id="J_City2_610100" class="J_City2">
        <label for="J_City2_610100">西安</label></span>
                                            <span class="areas2"><input type="checkbox" value="610200" id="J_City2_610200" class="J_City2">
        <label for="J_City2_610200">铜川</label></span>
                                            <span class="areas2"><input type="checkbox" value="610300" id="J_City2_610300" class="J_City2">
        <label for="J_City2_610300">宝鸡</label></span>
                                            <span class="areas2"><input type="checkbox" value="610400" id="J_City2_610400" class="J_City2">
        <label for="J_City2_610400">咸阳</label></span>
                                            <span class="areas2"><input type="checkbox" value="610500" id="J_City2_610500" class="J_City2">
        <label for="J_City2_610500">渭南</label></span>
                                            <span class="areas2"><input type="checkbox" value="610600" id="J_City2_610600" class="J_City2">
        <label for="J_City2_610600">延安</label></span>
                                            <span class="areas2"><input type="checkbox" value="610700" id="J_City2_610700" class="J_City2">
        <label for="J_City2_610700">汉中</label></span>
                                            <span class="areas2"><input type="checkbox" value="610800" id="J_City2_610800" class="J_City2">
        <label for="J_City2_610800">榆林</label></span>
                                            <span class="areas2"><input type="checkbox" value="610900" id="J_City2_610900" class="J_City2">
        <label for="J_City2_610900">安康</label></span>
                                            <span class="areas2"><input type="checkbox" value="611000" id="J_City2_611000" class="J_City2">
        <label for="J_City2_611000">商洛</label></span>
                                            <p style="text-align:right;"><input type="button" value="关闭" class="close_button2"></p>
                                        </div>
                                    </div>
                                    <div class="ecity2">
                                        <span class="gareas2"><input type="checkbox" value="650000" id="J_Province2_650000" class="J_Province22">
        <label for="J_Province2_650000">新疆</label><span class="check_num2"></span><img class="trigger2" src="//gtd.alicdn.com/tps/i1/T1XZCWXd8iXXXXXXXX-8-8.gif"></span>
                                        <div class="citys2">
                                            <span class="areas2"><input type="checkbox" value="650100" id="J_City2_650100" class="J_City2">
        <label for="J_City2_650100">乌鲁木齐</label></span>
                                            <span class="areas2"><input type="checkbox" value="650200" id="J_City2_650200" class="J_City2">
        <label for="J_City2_650200">克拉玛依</label></span>
                                            <span class="areas2"><input type="checkbox" value="652100" id="J_City2_652100" class="J_City2">
        <label for="J_City2_652100">吐鲁番</label></span>
                                            <span class="areas2"><input type="checkbox" value="652200" id="J_City2_652200" class="J_City2">
        <label for="J_City2_652200">哈密</label></span>
                                            <span class="areas2"><input type="checkbox" value="652300" id="J_City2_652300" class="J_City2">
        <label for="J_City2_652300">昌吉</label></span>
                                            <span class="areas2"><input type="checkbox" value="652700" id="J_City2_652700" class="J_City2">
        <label for="J_City2_652700">博尔塔拉</label></span>
                                            <span class="areas2"><input type="checkbox" value="652800" id="J_City2_652800" class="J_City2">
        <label for="J_City2_652800">巴音郭楞</label></span>
                                            <span class="areas2"><input type="checkbox" value="652900" id="J_City2_652900" class="J_City2">
        <label for="J_City2_652900">阿克苏</label></span>
                                            <span class="areas2"><input type="checkbox" value="653000" id="J_City2_653000" class="J_City2">
        <label for="J_City2_653000">克孜勒苏柯尔克孜</label></span>
                                            <span class="areas2"><input type="checkbox" value="653100" id="J_City2_653100" class="J_City2">
        <label for="J_City2_653100">喀什</label></span>
                                            <span class="areas2"><input type="checkbox" value="653200" id="J_City2_653200" class="J_City2">
        <label for="J_City2_653200">和田</label></span>
                                            <span class="areas2"><input type="checkbox" value="654000" id="J_City2_654000" class="J_City2">
        <label for="J_City2_654000">伊犁</label></span>
                                            <span class="areas2"><input type="checkbox" value="654200" id="J_City2_654200" class="J_City2">
        <label for="J_City2_654200">塔城</label></span>
                                            <span class="areas2"><input type="checkbox" value="654300" id="J_City2_654300" class="J_City2">
        <label for="J_City2_654300">阿勒泰</label></span>
                                            <span class="areas2"><input type="checkbox" value="659001" id="J_City2_659001" class="J_City2">
        <label for="J_City2_659001">石河子</label></span>
                                            <span class="areas2"><input type="checkbox" value="659002" id="J_City2_659002" class="J_City2">
        <label for="J_City2_659002">阿拉尔</label></span>
                                            <span class="areas2"><input type="checkbox" value="659003" id="J_City2_659003" class="J_City2">
        <label for="J_City2_659003">图木舒克</label></span>
                                            <span class="areas2"><input type="checkbox" value="659004" id="J_City2_659004" class="J_City2">
        <label for="J_City2_659004">五家渠</label></span>
                                            <p style="text-align:right;"><input type="button" value="关闭" class="close_button2"></p>
                                        </div>
                                    </div>
                                    <div class="ecity2">
                                        <span class="gareas2"><input type="checkbox" value="620000" id="J_Province2_620000" class="J_Province22">
        <label for="J_Province2_620000">甘肃</label><span class="check_num2"></span><img class="trigger2" src="//gtd.alicdn.com/tps/i1/T1XZCWXd8iXXXXXXXX-8-8.gif"></span>
                                        <div class="citys2">
                                            <span class="areas2"><input type="checkbox" value="620100" id="J_City2_620100" class="J_City2">
        <label for="J_City2_620100">兰州</label></span>
                                            <span class="areas2"><input type="checkbox" value="620200" id="J_City2_620200" class="J_City2">
        <label for="J_City2_620200">嘉峪关</label></span>
                                            <span class="areas2"><input type="checkbox" value="620300" id="J_City2_620300" class="J_City2">
        <label for="J_City2_620300">金昌</label></span>
                                            <span class="areas2"><input type="checkbox" value="620400" id="J_City2_620400" class="J_City2">
        <label for="J_City2_620400">白银</label></span>
                                            <span class="areas2"><input type="checkbox" value="620500" id="J_City2_620500" class="J_City2">
        <label for="J_City2_620500">天水</label></span>
                                            <span class="areas2"><input type="checkbox" value="620600" id="J_City2_620600" class="J_City2">
        <label for="J_City2_620600">武威</label></span>
                                            <span class="areas2"><input type="checkbox" value="620700" id="J_City2_620700" class="J_City2">
        <label for="J_City2_620700">张掖</label></span>
                                            <span class="areas2"><input type="checkbox" value="620800" id="J_City2_620800" class="J_City2">
        <label for="J_City2_620800">平凉</label></span>
                                            <span class="areas2"><input type="checkbox" value="620900" id="J_City2_620900" class="J_City2">
        <label for="J_City2_620900">酒泉</label></span>
                                            <span class="areas2"><input type="checkbox" value="621000" id="J_City2_621000" class="J_City2">
        <label for="J_City2_621000">庆阳</label></span>
                                            <span class="areas2"><input type="checkbox" value="621100" id="J_City2_621100" class="J_City2">
        <label for="J_City2_621100">定西</label></span>
                                            <span class="areas2"><input type="checkbox" value="621200" id="J_City2_621200" class="J_City2">
        <label for="J_City2_621200">陇南</label></span>
                                            <span class="areas2"><input type="checkbox" value="622900" id="J_City2_622900" class="J_City2">
        <label for="J_City2_622900">临夏</label></span>
                                            <span class="areas2"><input type="checkbox" value="623000" id="J_City2_623000" class="J_City2">
        <label for="J_City2_623000">甘南</label></span>
                                            <p style="text-align:right;"><input type="button" value="关闭" class="close_button2"></p>
                                        </div>
                                    </div>
                                    <div class="ecity2">
                                        <span class="gareas2"><input type="checkbox" value="640000" id="J_Province2_640000" class="J_Province22">
        <label for="J_Province2_640000">宁夏</label><span class="check_num2"></span><img class="trigger2" src="//gtd.alicdn.com/tps/i1/T1XZCWXd8iXXXXXXXX-8-8.gif"></span>
                                        <div class="citys2">
                                            <span class="areas2"><input type="checkbox" value="640100" id="J_City2_640100" class="J_City2">
        <label for="J_City2_640100">银川</label></span>
                                            <span class="areas2"><input type="checkbox" value="640200" id="J_City2_640200" class="J_City2">
        <label for="J_City2_640200">石嘴山</label></span>
                                            <span class="areas2"><input type="checkbox" value="640300" id="J_City2_640300" class="J_City2">
        <label for="J_City2_640300">吴忠</label></span>
                                            <span class="areas2"><input type="checkbox" value="640400" id="J_City2_640400" class="J_City2">
        <label for="J_City2_640400">固原</label></span>
                                            <span class="areas2"><input type="checkbox" value="640500" id="J_City2_640500" class="J_City2">
        <label for="J_City2_640500">中卫</label></span>
                                            <p style="text-align:right;"><input type="button" value="关闭" class="close_button2"></p>
                                        </div>
                                    </div>
                                    <div class="ecity2">
                                        <span class="gareas2"><input type="checkbox" value="630000" id="J_Province2_630000" class="J_Province22">
        <label for="J_Province2_630000">青海</label><span class="check_num2"></span><img class="trigger2" src="//gtd.alicdn.com/tps/i1/T1XZCWXd8iXXXXXXXX-8-8.gif"></span>
                                        <div class="citys2">
                                            <span class="areas2"><input type="checkbox" value="630100" id="J_City2_630100" class="J_City2">
        <label for="J_City2_630100">西宁</label></span>
                                            <span class="areas2"><input type="checkbox" value="632100" id="J_City2_632100" class="J_City2">
        <label for="J_City2_632100">海东</label></span>
                                            <span class="areas2"><input type="checkbox" value="632200" id="J_City2_632200" class="J_City2">
        <label for="J_City2_632200">海北</label></span>
                                            <span class="areas2"><input type="checkbox" value="632300" id="J_City2_632300" class="J_City2">
        <label for="J_City2_632300">黄南</label></span>
                                            <span class="areas2"><input type="checkbox" value="632500" id="J_City2_632500" class="J_City2">
        <label for="J_City2_632500">海南藏族</label></span>
                                            <span class="areas2"><input type="checkbox" value="632600" id="J_City2_632600" class="J_City2">
        <label for="J_City2_632600">果洛</label></span>
                                            <span class="areas2"><input type="checkbox" value="632700" id="J_City2_632700" class="J_City2">
        <label for="J_City2_632700">玉树</label></span>
                                            <span class="areas2"><input type="checkbox" value="632800" id="J_City2_632800" class="J_City2">
        <label for="J_City2_632800">海西</label></span>
                                            <p style="text-align:right;"><input type="button" value="关闭" class="close_button2"></p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </li>
                        <li>
                            <div class=" dcity2 clearfix">
                                <div class="ecity2 gcity2">
                                    <span class="group-label2"><input type="checkbox" value="500000,530000,520000,540000,510000" class="J_Group" id="J_Group2_6">
            <label for="J_Group_6">西南</label></span>
                                </div>
                                <div class="province-list2">
                                    <div class="ecity2">
                                        <span class="gareas2"><input type="checkbox" value="500000" id="J_Province2_500000" class="J_Province22">
        <label for="J_Province2_500000">重庆</label><span class="check_num2"></span><img class="trigger2" src="//gtd.alicdn.com/tps/i1/T1XZCWXd8iXXXXXXXX-8-8.gif"></span>
                                        <div class="citys2">
                                            <span class="areas2"><input type="checkbox" value="500100" id="J_City2_500100" class="J_City2">
        <label for="J_City2_500100">重庆</label></span>
                                            <p style="text-align:right;"><input type="button" value="关闭" class="close_button2"></p>
                                        </div>
                                    </div>
                                    <div class="ecity2">
                                        <span class="gareas2"><input type="checkbox" value="530000" id="J_Province2_530000" class="J_Province22">
        <label for="J_Province2_530000">云南</label><span class="check_num2"></span><img class="trigger2" src="//gtd.alicdn.com/tps/i1/T1XZCWXd8iXXXXXXXX-8-8.gif"></span>
                                        <div class="citys2">
                                            <span class="areas2"><input type="checkbox" value="530100" id="J_City2_530100" class="J_City2">
        <label for="J_City2_530100">昆明</label></span>
                                            <span class="areas2"><input type="checkbox" value="530300" id="J_City2_530300" class="J_City2">
        <label for="J_City2_530300">曲靖</label></span>
                                            <span class="areas2"><input type="checkbox" value="530400" id="J_City2_530400" class="J_City2">
        <label for="J_City2_530400">玉溪</label></span>
                                            <span class="areas2"><input type="checkbox" value="530500" id="J_City2_530500" class="J_City2">
        <label for="J_City2_530500">保山</label></span>
                                            <span class="areas2"><input type="checkbox" value="530600" id="J_City2_530600" class="J_City2">
        <label for="J_City2_530600">昭通</label></span>
                                            <span class="areas2"><input type="checkbox" value="530700" id="J_City2_530700" class="J_City2">
        <label for="J_City2_530700">丽江</label></span>
                                            <span class="areas2"><input type="checkbox" value="530800" id="J_City2_530800" class="J_City2">
        <label for="J_City2_530800">普洱</label></span>
                                            <span class="areas2"><input type="checkbox" value="530900" id="J_City2_530900" class="J_City2">
        <label for="J_City2_530900">临沧</label></span>
                                            <span class="areas2"><input type="checkbox" value="532300" id="J_City2_532300" class="J_City2">
        <label for="J_City2_532300">楚雄</label></span>
                                            <span class="areas2"><input type="checkbox" value="532500" id="J_City2_532500" class="J_City2">
        <label for="J_City2_532500">红河</label></span>
                                            <span class="areas2"><input type="checkbox" value="532600" id="J_City2_532600" class="J_City2">
        <label for="J_City2_532600">文山</label></span>
                                            <span class="areas2"><input type="checkbox" value="532800" id="J_City2_532800" class="J_City2">
        <label for="J_City2_532800">西双版纳</label></span>
                                            <span class="areas2"><input type="checkbox" value="532900" id="J_City2_532900" class="J_City2">
        <label for="J_City2_532900">大理</label></span>
                                            <span class="areas2"><input type="checkbox" value="533100" id="J_City2_533100" class="J_City2">
        <label for="J_City2_533100">德宏</label></span>
                                            <span class="areas2"><input type="checkbox" value="533300" id="J_City2_533300" class="J_City2">
        <label for="J_City2_533300">怒江</label></span>
                                            <span class="areas2"><input type="checkbox" value="533400" id="J_City2_533400" class="J_City2">
        <label for="J_City2_533400">迪庆</label></span>
                                            <p style="text-align:right;"><input type="button" value="关闭" class="close_button2"></p>
                                        </div>
                                    </div>
                                    <div class="ecity2">
                                        <span class="gareas2"><input type="checkbox" value="520000" id="J_Province2_520000" class="J_Province22">
        <label for="J_Province2_520000">贵州</label><span class="check_num2"></span><img class="trigger2" src="//gtd.alicdn.com/tps/i1/T1XZCWXd8iXXXXXXXX-8-8.gif"></span>
                                        <div class="citys2">
                                            <span class="areas2"><input type="checkbox" value="520100" id="J_City2_520100" class="J_City2">
        <label for="J_City2_520100">贵阳</label></span>
                                            <span class="areas2"><input type="checkbox" value="520200" id="J_City2_520200" class="J_City2">
        <label for="J_City2_520200">六盘水</label></span>
                                            <span class="areas2"><input type="checkbox" value="520300" id="J_City2_520300" class="J_City2">
        <label for="J_City2_520300">遵义</label></span>
                                            <span class="areas2"><input type="checkbox" value="520400" id="J_City2_520400" class="J_City2">
        <label for="J_City2_520400">安顺</label></span>
                                            <span class="areas2"><input type="checkbox" value="522200" id="J_City2_522200" class="J_City2">
        <label for="J_City2_522200">铜仁</label></span>
                                            <span class="areas2"><input type="checkbox" value="522300" id="J_City2_522300" class="J_City2">
        <label for="J_City2_522300">黔西南</label></span>
                                            <span class="areas2"><input type="checkbox" value="522400" id="J_City2_522400" class="J_City2">
        <label for="J_City2_522400">毕节</label></span>
                                            <span class="areas2"><input type="checkbox" value="522600" id="J_City2_522600" class="J_City2">
        <label for="J_City2_522600">黔东南</label></span>
                                            <span class="areas2"><input type="checkbox" value="522700" id="J_City2_522700" class="J_City2">
        <label for="J_City2_522700">黔南</label></span>
                                            <p style="text-align:right;"><input type="button" value="关闭" class="close_button2"></p>
                                        </div>
                                    </div>
                                    <div class="ecity2">
                                        <span class="gareas2"><input type="checkbox" value="540000" id="J_Province2_540000" class="J_Province22">
        <label for="J_Province2_540000">西藏</label><span class="check_num2"></span><img class="trigger2" src="//gtd.alicdn.com/tps/i1/T1XZCWXd8iXXXXXXXX-8-8.gif"></span>
                                        <div class="citys2">
                                            <span class="areas2"><input type="checkbox" value="540100" id="J_City2_540100" class="J_City2">
        <label for="J_City2_540100">拉萨</label></span>
                                            <span class="areas2"><input type="checkbox" value="542100" id="J_City2_542100" class="J_City2">
        <label for="J_City2_542100">昌都</label></span>
                                            <span class="areas2"><input type="checkbox" value="542200" id="J_City2_542200" class="J_City2">
        <label for="J_City2_542200">山南</label></span>
                                            <span class="areas2"><input type="checkbox" value="542300" id="J_City2_542300" class="J_City2">
        <label for="J_City2_542300">日喀则</label></span>
                                            <span class="areas2"><input type="checkbox" value="542400" id="J_City2_542400" class="J_City2">
        <label for="J_City2_542400">那曲</label></span>
                                            <span class="areas2"><input type="checkbox" value="542500" id="J_City2_542500" class="J_City2">
        <label for="J_City2_542500">阿里</label></span>
                                            <span class="areas2"><input type="checkbox" value="542600" id="J_City2_542600" class="J_City2">
        <label for="J_City2_542600">林芝</label></span>
                                            <p style="text-align:right;"><input type="button" value="关闭" class="close_button2"></p>
                                        </div>
                                    </div>
                                    <div class="ecity2">
                                        <span class="gareas2"><input type="checkbox" value="510000" id="J_Province2_510000" class="J_Province22">
        <label for="J_Province2_510000">四川</label><span class="check_num2"></span><img class="trigger2" src="//gtd.alicdn.com/tps/i1/T1XZCWXd8iXXXXXXXX-8-8.gif"></span>
                                        <div class="citys2">
                                            <span class="areas2"><input type="checkbox" value="510100" id="J_City2_510100" class="J_City2">
        <label for="J_City2_510100">成都</label></span>
                                            <span class="areas2"><input type="checkbox" value="510300" id="J_City2_510300" class="J_City2">
        <label for="J_City2_510300">自贡</label></span>
                                            <span class="areas2"><input type="checkbox" value="510400" id="J_City2_510400" class="J_City2">
        <label for="J_City2_510400">攀枝花</label></span>
                                            <span class="areas2"><input type="checkbox" value="510500" id="J_City2_510500" class="J_City2">
        <label for="J_City2_510500">泸州</label></span>
                                            <span class="areas2"><input type="checkbox" value="510600" id="J_City2_510600" class="J_City2">
        <label for="J_City2_510600">德阳</label></span>
                                            <span class="areas2"><input type="checkbox" value="510700" id="J_City2_510700" class="J_City2">
        <label for="J_City2_510700">绵阳</label></span>
                                            <span class="areas2"><input type="checkbox" value="510800" id="J_City2_510800" class="J_City2">
        <label for="J_City2_510800">广元</label></span>
                                            <span class="areas2"><input type="checkbox" value="510900" id="J_City2_510900" class="J_City2">
        <label for="J_City2_510900">遂宁</label></span>
                                            <span class="areas2"><input type="checkbox" value="511000" id="J_City2_511000" class="J_City2">
        <label for="J_City2_511000">内江</label></span>
                                            <span class="areas2"><input type="checkbox" value="511100" id="J_City2_511100" class="J_City2">
        <label for="J_City2_511100">乐山</label></span>
                                            <span class="areas2"><input type="checkbox" value="511300" id="J_City2_511300" class="J_City2">
        <label for="J_City2_511300">南充</label></span>
                                            <span class="areas2"><input type="checkbox" value="511400" id="J_City2_511400" class="J_City2">
        <label for="J_City2_511400">眉山</label></span>
                                            <span class="areas2"><input type="checkbox" value="511500" id="J_City2_511500" class="J_City2">
        <label for="J_City2_511500">宜宾</label></span>
                                            <span class="areas2"><input type="checkbox" value="511600" id="J_City2_511600" class="J_City2">
        <label for="J_City2_511600">广安</label></span>
                                            <span class="areas2"><input type="checkbox" value="511700" id="J_City2_511700" class="J_City2">
        <label for="J_City2_511700">达州</label></span>
                                            <span class="areas2"><input type="checkbox" value="511800" id="J_City2_511800" class="J_City2">
        <label for="J_City2_511800">雅安</label></span>
                                            <span class="areas2"><input type="checkbox" value="511900" id="J_City2_511900" class="J_City2">
        <label for="J_City2_511900">巴中</label></span>
                                            <span class="areas2"><input type="checkbox" value="512000" id="J_City2_512000" class="J_City2">
        <label for="J_City2_512000">资阳</label></span>
                                            <span class="areas2"><input type="checkbox" value="513200" id="J_City2_513200" class="J_City2">
        <label for="J_City2_513200">阿坝</label></span>
                                            <span class="areas2"><input type="checkbox" value="513300" id="J_City2_513300" class="J_City2">
        <label for="J_City2_513300">甘孜</label></span>
                                            <span class="areas2"><input type="checkbox" value="513400" id="J_City2_513400" class="J_City2">
        <label for="J_City2_513400">凉山</label></span>
                                            <p style="text-align:right;"><input type="button" value="关闭" class="close_button2"></p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </li>
                        <li class="even">
                            <div class=" dcity2 clearfix">
                                <div class="ecity2 gcity2">
                                    <span class="group-label2"><input type="checkbox" value="810000,820000,710000" class="J_Group" id="J_Group2_7">
            <label for="J_Group_7">港澳台</label></span>
                                </div>
                                <div class="province-list2">
                                    <div class="ecity2">
                                        <span class="gareas2"><input type="checkbox" value="810000" id="J_Province2_810000" class="J_Province22">
        <label for="J_Province2_810000">香港</label><span class="check_num2"></span><img class="trigger2" src="//gtd.alicdn.com/tps/i1/T1XZCWXd8iXXXXXXXX-8-8.gif"></span>
                                        <div class="citys2">
                                            <span class="areas2"><input type="checkbox" value="810100" id="J_City2_810100" class="J_City2">
        <label for="J_City2_810100">香港岛</label></span>
                                            <span class="areas2"><input type="checkbox" value="810200" id="J_City2_810200" class="J_City2">
        <label for="J_City2_810200">九龙</label></span>
                                            <span class="areas2"><input type="checkbox" value="810300" id="J_City2_810300" class="J_City2">
        <label for="J_City2_810300">新界</label></span>
                                            <p style="text-align:right;"><input type="button" value="关闭" class="close_button2"></p>
                                        </div>
                                    </div>
                                    <div class="ecity2">
                                        <span class="gareas2"><input type="checkbox" value="820000" id="J_Province2_820000" class="J_Province22">
        <label for="J_Province2_820000">澳门</label><span class="check_num2"></span><img class="trigger2" src="//gtd.alicdn.com/tps/i1/T1XZCWXd8iXXXXXXXX-8-8.gif"></span>
                                        <div class="citys2">
                                            <span class="areas2"><input type="checkbox" value="820100" id="J_City2_820100" class="J_City2">
        <label for="J_City2_820100">澳门半岛</label></span>
                                            <span class="areas2"><input type="checkbox" value="820200" id="J_City2_820200" class="J_City2">
        <label for="J_City2_820200">离岛</label></span>
                                            <p style="text-align:right;"><input type="button" value="关闭" class="close_button2"></p>
                                        </div>
                                    </div>
                                    <div class="ecity2">
                                        <span class="gareas2"><input type="checkbox" value="710000" id="J_Province2_710000" class="J_Province22">
        <label for="J_Province2_710000">台湾</label><span class="check_num2"></span><img class="trigger2" src="//gtd.alicdn.com/tps/i1/T1XZCWXd8iXXXXXXXX-8-8.gif"></span>
                                        <div class="citys2">
                                            <span class="areas2"><input type="checkbox" value="710100" id="J_City2_710100" class="J_City2">
        <label for="J_City2_710100">台北</label></span>
                                            <span class="areas2"><input type="checkbox" value="710200" id="J_City2_710200" class="J_City2">
        <label for="J_City2_710200">高雄</label></span>
                                            <span class="areas2"><input type="checkbox" value="710300" id="J_City2_710300" class="J_City2">
        <label for="J_City2_710300">台南</label></span>
                                            <span class="areas2"><input type="checkbox" value="710400" id="J_City2_710400" class="J_City2">
        <label for="J_City2_710400">台中</label></span>
                                            <span class="areas2"><input type="checkbox" value="710500" id="J_City2_710500" class="J_City2">
        <label for="J_City2_710500">金门</label></span>
                                            <span class="areas2"><input type="checkbox" value="710600" id="J_City2_710600" class="J_City2">
        <label for="J_City2_710600">南投</label></span>
                                            <span class="areas2"><input type="checkbox" value="710700" id="J_City2_710700" class="J_City2">
        <label for="J_City2_710700">基隆</label></span>
                                            <span class="areas2"><input type="checkbox" value="710800" id="J_City2_710800" class="J_City2">
        <label for="J_City2_710800">新竹</label></span>
                                            <span class="areas2"><input type="checkbox" value="710900" id="J_City2_710900" class="J_City2">
        <label for="J_City2_710900">嘉义</label></span>
                                            <span class="areas2"><input type="checkbox" value="711100" id="J_City2_711100" class="J_City2">
        <label for="J_City2_711100">新北</label></span>
                                            <span class="areas2"><input type="checkbox" value="711200" id="J_City2_711200" class="J_City2">
        <label for="J_City2_711200">宜兰</label></span>
                                            <span class="areas2"><input type="checkbox" value="711300" id="J_City2_711300" class="J_City2">
        <label for="J_City2_711300">新竹</label></span>
                                            <span class="areas2"><input type="checkbox" value="711400" id="J_City2_711400" class="J_City2">
        <label for="J_City2_711400">桃园</label></span>
                                            <span class="areas2"><input type="checkbox" value="711500" id="J_City2_711500" class="J_City2">
        <label for="J_City2_711500">苗栗</label></span>
                                            <span class="areas2"><input type="checkbox" value="711700" id="J_City2_711700" class="J_City2">
        <label for="J_City2_711700">彰化</label></span>
                                            <span class="areas2"><input type="checkbox" value="711900" id="J_City2_711900" class="J_City2">
        <label for="J_City2_711900">嘉义</label></span>
                                            <span class="areas2"><input type="checkbox" value="712100" id="J_City2_712100" class="J_City2">
        <label for="J_City2_712100">云林</label></span>
                                            <span class="areas2"><input type="checkbox" value="712400" id="J_City2_712400" class="J_City2">
        <label for="J_City2_712400">屏东</label></span>
                                            <span class="areas2"><input type="checkbox" value="712500" id="J_City2_712500" class="J_City2">
        <label for="J_City2_712500">台东</label></span>
                                            <span class="areas2"><input type="checkbox" value="712600" id="J_City2_712600" class="J_City2">
        <label for="J_City2_712600">花莲</label></span>
                                            <span class="areas2"><input type="checkbox" value="712700" id="J_City2_712700" class="J_City2">
        <label for="J_City2_712700">澎湖</label></span>
                                            <span class="areas2"><input type="checkbox" value="712800" id="J_City2_712800" class="J_City2">
        <label for="J_City2_712800">连江</label></span>
                                            <p style="text-align:right;"><input type="button" value="关闭" class="close_button2"></p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </li>
                        <li>
                            <div class=" dcity2 clearfix">
                                <div class="ecity2 gcity2">
                                    <span class="group-label2"><input type="checkbox" value="990000" class="J_Group" id="J_Group_8">
            <label for="J_Group2_8">海外</label></span>
                                </div>
                                <div class="province-list2">
                                    <div class="ecity2">
                                        <span class="gareas2"><input type="checkbox" value="990000" id="J_Province2_990000" class="J_Province22">
        <label for="J_Province2_990000">海外</label><span class="check_num2"></span><img class="trigger2" src="//gtd.alicdn.com/tps/i1/T1XZCWXd8iXXXXXXXX-8-8.gif"></span>
                                        <div class="citys2">
                                            <span class="areas2"><input type="checkbox" value="990100" id="J_City2_990100" class="J_City2">
        <label for="J_City2_990100">海外</label></span>
                                            <p style="text-align:right;"><input type="button" value="关闭" class="close_button2"></p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </li>
                    </ul>
                    <div class="btns"><button type="button" class="J_Submit2">确定</button><button type="button" class="J_Cancel2">取消</button></div>
                </form>
            </div>
            <div class="ks-stdmod-footer"></div>
        </div>
        <div tabindex="0" style="position:absolute;"></div>
 
</div>

<script>
    $(function() { 
        var allCityCodeArr = []; //存储城市id

        //初始化地区选择
        var dataList = <?=json_encode($arrNoDelivery)?>

        if( dataList.length !== 0 ) {
            var html = '';
           
            //循环数组结构拼接结构
            $('.deliverWrap').addClass('deliverShow');
            for( var i = 0;i < dataList.length;i ++ ) {
                var cityArr = [];
                var areaCity = '';
                for(var j = 0; j < dataList[i].length; j++) {
                    cityArr.push(dataList[i][j].id);
                    allCityCodeArr[i] = cityArr;//初始化城市id

                    areaCity = areaCity + dataList[i][j].area+',';
                    if( areaCity.length > 11 ) {
                        areaCity = areaCity.substring(0,11).concat('...');
                    }
                }
                html += '<tr><td><input type="hidden" class="address-area" name="areas2" value=""><a href="javascript:;" class="edit2 J_Edit2" title="编辑运送区域">编辑</a><div class="area-group2"><p>'+areaCity+'</p></div></td><td><a href="javascript:;" class="J_AddItem2 small-icon"></a><a href="javascript:;" class="J_DelateItem2 small-icon"></a></td></tr>';

            }
            $('#J_Tbody2').prepend(html);     
            inputAttr(); //存储为form表单
        }




        var J_index = 0;
        $('.noDeliverEdit').on('click',function() {
            $('.deliverWrap').toggleClass('deliverShow');

            var fhtml = '<tr><td><input type="hidden" class="address-area" name="areas2" value=""><a href="javascript:;" class="edit2 J_Edit2" title="编辑运送区域" >编辑</a><div class="area-group2"><p>未添加地区</p></div></td><td><a href="javascript:;" class="J_AddItem2 small-icon"></a><a href="javascript:;" class="J_DelateItem2 small-icon"></a></td></tr>'; 

            if( dataList.length == 0 ) {
               $('#J_Tbody2').append(fhtml); 
            }

        })

        $('.ks-ext-close-x').on('click',function() {
            $('.dialog2').hide();
            $('.ks-dialog-mask2').hide();
        })
        //取消
        $('.J_Cancel2').on('click',function() {
            $('.dialog2').hide();
            $('.ks-dialog-mask2').hide();
            $('#J_City2List2').find('input[type="checkbox"]').prop("checked", false);
        })


        //获取所有城市信息
        function getCitiesInfo(lIndex) {
            var cityNameArr = [];
            var cityCodeArr = [];
            $('.areas2').find('input[type="checkbox"]:checked').each(function(index, elm) {
                cityNameArr.push($(this).next().text());
                cityCodeArr.push($(this).val()); //城市id 事先赋值在value
            })

            if( cityNameArr.length == 0 ) {
                 $('.area-group2 p').eq(lIndex).html('未添加地区');
            }
            if(cityNameArr.length > 4) {

                 $('.area-group2 p').eq(lIndex).html( cityNameArr.slice(0,4).join(',').concat('...'));
            } else if( cityNameArr.length !== 0 )  {
                
                 $('.area-group2 p').eq(lIndex).html( cityNameArr.join(',') );
            }
            allCityCodeArr[lIndex] = cityCodeArr ; //对应的index的值变化;

            //将数据传给后端表单
            inputAttr();
            
        }
        //计算选中的数量
        function calcCheckedCityNum() {
            $('.check_num2').each(function(index, elm) {
                var allLen = $(this).parent().next().find('.areas2').find('input[type=checkbox]').length;
                var len = $(this).parent().next().find('.areas2').find('input[type=checkbox]:checked').length;
                if (len > 0) {
                    $(this).text('(' + len + ')').show();
                } else {
                    $(this).hide();
                }
                //全选
                if (allLen != len) {
                    $(this).parent().find('input[type="checkbox"]').prop('checked', false);
                } else {
                    $(this).parent().find('input[type="checkbox"]').prop('checked', true);
                }
            })
        }
        //全选
        function checkedAll() {
            $('.gareas2').find('input[type="checkbox"]').on('click', function() {
                if ($(this).is(":checked")) {
                    $(this).parent().next().find('input[type="checkbox"]').prop("checked", true);
                    calcCheckedCityNum();
                } else {
                    $(this).parent().next().find('input[type="checkbox"]').prop("checked", false);
                    calcCheckedCityNum();
                }
            })
        }
        //全选
        checkedAll()

        //切换城市选型
        $('.trigger2').on('click', function() {
            $('.citys2').hide();
            $(this).parent().next().toggle();
        })

        //关闭城市选型
        $('.close_button2').on('click', function() {
            $(this).closest('.citys2').hide();
        })

        //选中城市
        $('.areas2').on('click', function() {
            calcCheckedCityNum();
        })

        //点击确定
        $('.J_Submit2').on('click', function() {
            $('.dataInput').remove();
            getCitiesInfo(J_index); //获取城市id
            $('.dialog2').hide();
            $('.ks-dialog-mask2').hide();
            $('#J_City2List2').find('input[type="checkbox"]').prop("checked", false);
        })
        //编辑
        $('#J_Tbody2').on('click','.J_Edit2',function() {
            $('.dialog2').show();
            $('.ks-dialog-mask2').show();
            J_index = $(this).index('.J_Edit2');
            calcCheckedCityNum();

            //渲染选择的城市
            renderCheckedCity(allCityCodeArr[J_index]);
           
        })
        //添加表格
        $('#J_Tbody2').on('click','.J_AddItem2', function() {
           var length = $('.J_AddItem2').length;
           var html = '<tr><td><input type="hidden" class="address-area" name="areas2" value=""><a href="javascript:;" class="edit2 J_Edit2" title="编辑运送区域">编辑</a><div class="area-group2"><p>未添加地区</p></div></td><td><a href="javascript:;" class="J_AddItem2 small-icon"></a><a href="javascript:;" class="J_DelateItem2 small-icon"></a></td></tr>';

            $('#J_Tbody2').append(html);

        })
        //删除表格
        $('#J_Tbody2').on('click','.J_DelateItem2',function() {
                $('.dataInput').remove();
                var index = $(this).index('.J_DelateItem2'); 
                var length = $('#J_Tbody2 tr').length;

                if( length == 1) {
                    alert('不能再删了！');
                    return;
                }

                allCityCodeArr.splice(index,1);
                inputAttr();
                $(this).parents('tr').remove();
                
        })
        //大地区选择
        $('.group-label2').on('click',function() {
            var isTrue =  $(this).find('input[type="checkbox"]').is(":checked");

            if ( isTrue ) {
                $(this).parent().siblings('.province-list2').find('input[type="checkbox"]').prop("checked", true);
                checkedAll();
                calcCheckedCityNum();
            } else {
                $(this).parent().siblings('.province-list2').find('input[type="checkbox"]').prop("checked", false);
                checkedAll();
                calcCheckedCityNum();
            }
        })

         $('.gareas2').find('input[type="checkbox"]').on('click', function() {
             swithAreaAll();
        })

        //地区全选切换
        function swithAreaAll() {
            $('.group-label2').find('input[type="checkbox"]').each(function(index, elm) {
                var allLen = $(this).parents('.gcity2').siblings('.province-list2').find('.gareas2').find('input[type=checkbox]').length;
                var checkedLen = $(this).parents('.gcity2').siblings('.province-list2').find('.gareas2').find('input[type=checkbox]:checked').length;
                if (allLen == checkedLen) {
                    $(this).prop("checked", true);
                } else {
                    $(this).prop("checked", false);
                }
            })
        }
        //用于循环遍历传个后端数据
        function inputAttr() {
            var aLength = allCityCodeArr.length;
            var pHtml = '';
            for( var i = 0;i < aLength;i++) {  
                pHtml += '<input type="hidden" name="noDeliveryJson['+i+']" class="dataInput" id="J_NoPostData'+i+'" value="'+allCityCodeArr[i]+'"/>';
            }
            $('#J_TPLForm').prepend(pHtml);
        }
        //渲染选中城市
        function renderCheckedCity(cityCodes){
         
            var selectedCityName = [];
            if(Object.prototype.toString.call(cityCodes)!=="[object Array]"){
                return;
            }

            cityCodes.forEach(function(elm){
                var item = '#J_City2_'+elm;

                $('#J_City2_'+elm).prop('checked',true);

                selectedCityName.push($('#J_City2_'+elm).next().text());
            })
            calcCheckedCityNum();
            swithAreaAll();
        }
    })
</script>