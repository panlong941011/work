<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <title>运费模板</title>

    <!-- <link rel="stylesheet" href="//g.alicdn.com/??tm/wuliu-freight/1.0.8/base/reset.css,tm/wuliu-freight/1.0.8/base/button.css,tm/wuliu-freight/1.0.8/base/message.css,tm/wuliu-freight/1.0.8/setting.css"/> -->
    <link rel="stylesheet" href="/css/tmplate.css"/>
    <link rel="stylesheet" href="/css/tmpPlus.css"/>
    <script src="//g.alicdn.com/??kissy/k/1.3.0/kissy-min.js,cnmui/seed/1.0.0/1.1.7/seed.js"></script>
    <!-- <script src="/js/mustache.min.js"></script> -->
    <!-- <link rel="stylesheet" href="//g.alicdn.com/cnmui/msg/1.0.0/1.0.3/msg.css"/> -->
    <link rel="stylesheet" href="/css/msg.css"/>
    <style media="screen">
        .msg{
            display: none;
        }
        .dialog-batch{
            width: 620px;
        }
    </style>
    <!-- <script src="//g.alicdn.com/ccc/address-select/1.0.2/index.js" charset="utf-8"></script> -->
    <script src="/js/address-select.js" charset="utf-8"></script>
    <!-- <script src="/js/index.js" charset="utf-8"></script> -->
</head>
<body>
<script>
    with(document)with(body)with(insertBefore(createElement("script"),firstChild))setAttribute("exparams","category=&userid=443932990&aplus&yunid=&asid=AAHlmipXDhV/UOKZwWw=",id="tb-beacon-aplus",src=(location>"https"?"//s":"//a")+".tbcdn.cn/s/aplus_v2.js")
</script>
<div id="page">
    <div id="content">
        <div id="postage-tpl">
            <div class="setting">
                <h2> 新增运费模板 </h2>
                <form method="post" id="J_TPLForm">
                    <input name='_tb_token_' type='hidden' value='ZBQ7lXsd22Ox835'>
                    <input type="hidden" name="action" value="user/template_setting_action" />
                    <input type="hidden" name="event_submit_do_create_template" value="1"/>
                    <input type="hidden" name="isUpdate" value="" />
                    <input id="J_Cube" type="hidden" value="m&#179" />
                    <input type="hidden" name="templateId" value="0" />
                    <input type="hidden" name="templateName" value="" />
                    <input type="hidden" name="auctionids" value="" />
                    <input type="hidden" name="forceSellerPay" value="false" />
                    <input type="hidden" name="unique" value="" />
                    <input type="hidden" name="type" value="" />
                    <input type="hidden" name="freeType" value="" id="J_FreeValue"/>
                    <input type="hidden" name="fromType" value="fromListAll" />
                    <input type="hidden" name="selectedPostageid" value="" />
                    <input type="hidden" name="postageid" value="" />
                    <input type="hidden" name="auctionid" value="" />
                    <input type="hidden" name="categoryid" value="" />
                    <input type="hidden" name="_csrf" value="<?=Yii::$app->request->getCsrfToken()?>">
                    <input type="hidden" name="ID" value="<?=$_GET['ID'] ? $_GET['ID'] : $_POST['ID']?>">
                    <ul>
                        <!--  模板名称设置  -->
                        <li class="form-elem">
                            <label class="label-like" for="J_TemplateTitle">模板名称：</label>
                            <input type="text" name="sName" value="" class="input-text input-address" autocomplete="off" id="J_TemplateTitle"/>
                            <p class="msg">
                                <span class="error">必须填写模板名称</span>
                            </p>
                        </li>

                        <!--  卖家地址设置  -->
                        <li class="form-elem">
                            <span class="label-like">宝贝地址：</span>
                            <span id="J_AddressChoose"></span>
                            <!--<span id="J_AddressChoose">
                               <select  id="J_LogisProvince" name="province" title="所在省"></select>&nbsp;省
                               <select  id="J_LogisCity" name="city" title="市"></select>&nbsp;市
                               <select  id="J_LogisArea" name="county" title="区"></select>&nbsp;区
                            </span>-->
                            <p class="msg">
                                <span class="error">请选择宝贝所在地</span>
                            </p>
                        </li>

                        <li class="form-elem" hidden>
                            <span class="label-like">发货时间：</span>
                            <span class="fare-time">
						<div id="J_FareTime"></div>
    					<input type="hidden" id="J_FareTimeResult" name="consignDate"  value="">
                                <!--
                                <select name="consignDate" id="J_prescription">
                                    <option>请选择..</option>							<option value="4">4小时内</option>
                                    <option value="8">8小时内</option>
                                    <option value="12">12小时内</option>
                                    <option value="16">16小时内</option>
                                    <option value="20">20小时内</option>
                                    <option value="24">1天内</option>
                                    <option value="48" >2天内</option>
                                    <option value="72">3天内</option>
                                    <option value="120">5天内</option>
                                    <option value="168">7天内</option>
                                    <option value="192">8天内</option>
                                    <option value="240">10天内</option>
                                    <option value="288">12天内</option>
                                    <option value="360">15天内</option>
                                    <option value="408">17天内</option>
                                    <option value="480">20天内</option>
                                    <option value="600">25天内</option>
                                    <option value="720">30天内</option>
                                    <option value="840">35天内</option>
                                    <option value="1080">45天内</option>
                                </select>
                                -->
    				</span>
                            <span>如实设定宝贝的发货时间，不仅可避免发货咨询和纠纷，还能促进成交！<a href="//service.taobao.com/support/knowledge-5609752.htm?spm=0.0.0.0.IajNfS&dkey=searchview" target="_blank">详情</a></span>
                        </li>
                        <!--  是否包邮设置  -->
                        <li class="form-elem J_Freight" hidden>
                            <span class="label-like">是否包邮：</span>
                            <span>
					    					<input type="radio" name="bearFreight"  checked   value="0" id="J_buyerBearFre"/>
    					<label for="J_buyerBearFre">自定义运费</label>
    					<input type="radio" name="bearFreight"  value="2" id="J_sellerBearFre"/>
    					<label for="J_sellerBearFre">卖家承担运费</label>
					    			</span>
                        </li>

                        <input type="hidden" name="bearFreight"  checked   value="0" id="J_buyerBearFre"/>
                        <!--  记价方式设置  -->
                        <li class="form-elem calc-method">
                            <span class="label-like">计价方式：</span>
                            <span>
					<input type="radio" name="valuation"  checked  value="0"  class="J_CalcRule" data-type="number" id="J_CalcRuleNumber" />
					<label for="J_CalcRuleNumber">&nbsp;按件数</label>
				</span>
                            <span style="display: none;">
						<input type="radio" name="valuation"  value="1" class="J_CalcRule" data-type="weight" id ="J_CalcRuleWeight"/>
						<label for="J_CalcRuleWeight">&nbsp;按重量</label>
					</span>
                            <span style="display: none;">
						<input type="radio" name="valuation"  value="2"  class="J_CalcRule" data-type="volume" id="J_CalcRuleVolume" />
						<label for="J_CalcRuleVolume">&nbsp;按体积</label>
					</span>
                        </li>
                        <!--  区域限售  -->

                        <!-- 运费方式设置  -->
                        <li class="form-elem express">
                            <span class="label-like">运送方式：</span>
                            <p>
                                <span class="field-note">除指定地区外，其余地区的运费采用“默认运费”</span>
                            </p>
                            <div class="section J_Section">
                                <p>
                                    <input type="checkbox" name="tplType"  value="-4"
                                           id="J_DeliveryEXPRESS" class="J_Delivery J_pecify" data-type="111"/>
                                    <label for="J_DeliveryEXPRESS">快递</label>
                                </p>
                                <div class="postage-detail hidden J_PostageDetail" data-delivery="express"></div>
                            </div>
                            <div class="section J_Section" style="display: none;">
                                <p>
                                    <input type="checkbox" name="tplType"  value="-7"
                                           id="J_DeliveryEMS" class="J_Delivery J_pecify"/>
                                    <label for="J_DeliveryEMS">EMS</label>
                                </p>
                                <div class="postage-detail hidden J_PostageDetail" data-delivery="ems"></div>
                            </div>
                            <div class="section J_Section" style="display: none;">
                                <p>
                                    <input type="checkbox" name="tplType"  value="-1"
                                           id="J_DeliveryPOST" class="J_Delivery J_pecify"/>
                                    <label for="J_DeliveryPOST">平邮</label>
                                </p>
                                <div class="postage-detail hidden J_PostageDetail" data-delivery="post"></div>
                            </div>
                            <p class="msg">
                                <span class="error">请至少选择一种运送方式</span>
                            </p>
                        </li>

                        <!-- 运费方式设置  -->
                        <li class="form-elem set-free" style="display:none">
                            <p class="free-title" style="display:none;">
                                <input type="checkbox" value="1" id="J_SetFree">
                                指定条件包邮
                                <i>
                                    <img src="//img.alicdn.com/tps/i1/TB1Sw5KFVXXXXb7XFXX1xhnFFXX-23-12.png">
                                </i>
                                可选
                            </p>
                            <table class="table">
                                <colgroup>
                                    <col width="21%"></col>
                                    <col width="16%"></col>
                                    <col width="16%"></col>
                                    <col width="32%"></col>
                                    <col width="13%"></col>
                                </colgroup>
                                <thead>
                                <tr>
                                    <th>选择地区</th>
                                    <th>选择运送方式</th>
                                    <th>选择快递</th>
                                    <th>设置包邮条件</th>
                                    <th>操作</th>
                                </tr>
                                </thead>
                                <tbody id="J_Tbody">
                                <tr>
                                    <td>
                                        <input type="hidden" class="address-area" name="areas" value="">
                                        <a href="#" class="acc_popup edit J_Edit"   title="编辑运送区域" data-areas="">编辑</a>
                                        <div class="area-group">
                                            <p>未添加地区</p>
                                        </div>
                                    </td>
                                    <td>
                                        <select name="transType" class="J_Service">
                                            <option value="0">快递运送</option>
                                        </select>
                                    </td>
                                    <td>
                                        <!-- <select name="serviceType">
                                        
                                        </select> -->
                                    </td>
                                    <td>
                                        <select class="J_ChageContion">
                                            <option value="0">件数</option>
                                                <option value="1">金额</option>
                                                <option value="2">件数+金额</option>
                                        </select>
                                        <p class="free-contion">
                                            满 <input type="text" value=""> 件 包邮
                                        </p>
                                    </td>
                                    <td>
                                        <a href="" class="J_AddItem small-icon"></a>
                                        <a href="" class="J_DelateItem small-icon"></a>
                                    </td>
                                </tr>
                                </tbody>
                            </table>

                        </li>
                    </ul>

                    <?= $this->render('nosend', ['arrNoDelivery'=>$arrNoDelivery]) ?>


                    <p class="btns">
                        <button type="submit">
                            保存
                        </button>

                    </p>
                    <input type="hidden" name="deliveryJson" id="J_TPLPostData" value=''/>
                    <input type="hidden" name="deliveryAddress" id="J_address" value='{"path":[1]}'/>
                    <input type="hidden" name="deliveryPrescription" id="J_deliveryPrescription" value=''/>
                    <input type="hidden" name="hasConsignDate" value='false'/>
                    <input type="hidden" name="consignDateValue" id="J_consignDateValue" value=''/>
                </form>
            </div>
        </div>
    </div>
</div>

<div id="J_check" class="">

</div>
<!--选择区域模板-->
<script id="J_AreaTemplate" type="text/template">
    <ul id="J_CityList">
        {{#group}}
        <li {{#even}}class="even"{{/even}}>
        <div class=" dcity clearfix">
            <div class="ecity gcity">
		<span class="group-label"><input type="checkbox" value="{{codes}}" class="J_Group" id="J_Group_{{gid}}"/>
			<label for="J_Group_{{gid}}">{{title}}</label></span>
            </div>
            <div class="province-list">
                {{#areas}}
                <div class="ecity">
	<span class="gareas"><input type="checkbox" value="{{code}}" id="J_Province_{{code}}" class="J_Province"/>
		<label for="J_Province_{{code}}">{{title}}</label><span class = "check_num"></span><img class="trigger" src="//gtd.alicdn.com/tps/i1/T1XZCWXd8iXXXXXXXX-8-8.gif" ></span>
                    <div class="citys">
                        {{#citys}}
                        <span class="areas"><input type="checkbox" value="{{code}}" id="J_City_{{code}}" class="J_City"/>
		<label for="J_City_{{code}}">{{title}}</label></span>
                        {{/citys}}<p style="text-align:right;"><input type="button" value="关闭"  class="close_button"></p>
                    </div>
                </div>
                {{/areas}}
            </div>
        </div>
        </li>
        {{/group}}
    </ul>
</script>
<!--运送方式-->
<script id="J_RuleTemplate" type="text/template">
    <div class="entity">
        <div class="default J_DefaultSet">
            默认运费：<input type="text" name="{{delivery}}_start" value="{{start}}" data-field="start" class="input-text {{#starterror}}input-error {{/starterror}}" autocomplete="off" maxlength="6" aria-label="默认运费{{typeName}}数" /> {{typeName}}内，
            <input type="text" class="j_sellerBearFrePrice" value="0.00" disabled="disabled"/><input type="text" data-field="postage" name="{{delivery}}_postage" value="{{postage}}" class="input-text {{#postageerror}}input-error {{/postageerror}}" autocomplete="off" maxlength="6" aria-label="默认运费价格" /> 元，
            每增加 <input type="text" name="{{delivery}}_plus" data-field="plus" value="{{plus}}" class="input-text {{#pluserror}}input-error {{/pluserror}}" autocomplete="off" maxlength="6" aria-label="每加{{typeName}}" /> {{typeName}}，
            增加运费 <input type="text" class="j_sellerBearFrePrice" value="0.00" disabled="disabled"/><input type="text" name="{{delivery}}_postageplus" data-field="postageplus" value="{{postageplus}}" class="input-text {{#postagepluserror}}input-error {{/postagepluserror}}" autocomplete="off" maxlength="6" aria-label="加{{typeName}}运费" />元
            {{#global}}, <br/>全国时效：<input type="text" id="J_GlobalWayDay" name="{{delivery}}_wayDay" value="{{wayDay}}" data-field="wayDay" class="input-text {{#wayDayerror}}input-error {{/wayDayerror}}" autocomplete="off" maxlength="6" aria-label="全国时效"/> 天{{/global}}
            <div class="J_DefaultMessage">{{#message}}<span class="msg J_Message"><span class="error">{{.}}</span></span>{{/message}}</div>
        </div>
        {{#withSpecial}}
        <div class="tbl-except">
            <table border="0" cellpadding="0" cellspacing="0">
                <colgroup>
                    <col class="col-area" />
                    <col class="col-start" />
                    <col class="col-postage" />
                    <col class="col-plus" />
                    <col class="col-postageplus" />
                    {{#global}}
                    <col class="col-wayDay" />
                    {{/global}}
                    <col class="col-action" />
                </colgroup>
                <thead>
                <tr>
                    <th>加运费地区</th>
                    <th>首{{typeNameExtra}}({{typeName}})</th>
                    <th>首费(元)</th>
                    <th>续{{typeNameExtra}}({{typeName}})</th>
                    <th>续费(元)</th>
                    {{#global}}
                    <th>时效(天)</th>
                    {{/global}}
                    <th>操作</th>
                </tr>
                </thead>
                <tbody id='main-tb'>
                {{#special}}
                <tr data-group="{{gid}}">
                    <td class="cell-area">
                        <a href="#" class="acc_popup edit J_EditArea" data-acc="event:enter" area-controls="J_DialogArea" area-haspopup="true" title="编辑运送区域">编辑</a>
                        <div class="area-group">
                            {{#isBatch}}<input type="checkbox" name="{{delivery}}_{{gid}}" value="" class="J_BatchField" {{#checked}}checked="checked"{{/checked}}/>{{/isBatch}}
                            {{#group}}
                            <p>{{str}}</p>
                            {{/group}}

                        </div>
                        <input type="hidden" name="{{delivery}}_areas_{{gid}}" value="{{areas}}">
                    </td>
                    <td><input type="text" name="{{delivery}}_start_{{gid}}" data-field="start" value="{{start}}" class="input-text {{#starterror}}input-error {{/starterror}}" autocomplete="off" maxlength="6" aria-label="首{{typeName}}" /></td>
                    <td><input type="text" class="j_sellerBearFrePrice" value="0.00" disabled="disabled"/><input type="text" name="{{delivery}}_postage_{{gid}}"  data-field="postage" value="{{postage}}" class="input-text {{#postageerror}}input-error {{/postageerror}}" autocomplete="off" maxlength="6" aria-label="首费" /></td>
                    <td><input type="text" name="{{delivery}}_plus_{{gid}}" data-field="plus" value="{{plus}}" class="input-text {{#pluserror}}input-error {{/pluserror}}" autocomplete="off" maxlength="6" aria-label="续{{typeName}}"/></td>
                    <td><input type="text" class="j_sellerBearFrePrice" value="0.00" disabled="disabled"/><input type="text" name="{{delivery}}_postageplus_{{gid}}" data-field="postageplus" value="{{postageplus}}" class="input-text {{#postagepluserror}}input-error {{/postagepluserror}}" autocomplete="off" maxlength="6" aria-label="续费" /></td>
                    {{#global}}
                    <td>
                        <input type="text" class="j_sellerBearFrePrice" value="0.00" disabled="disabled"/>
                        <input type="text" name="{{delivery}}_wayDay_{{gid}}" data-field="wayDay" value="{{wayDay}}" class="input-text J_ItemWayDay {{#wayDayerror}}input-error {{/wayDayerror}}" autocomplete="off" maxlength="6" aria-label="时效" />
                    </td>
                    {{/global}}
                    <td><a href="#" class="J_DeleteRule">删除</a></td>
                </tr>
                {{/special}}
                </tbody>
            </table>
        </div>
        {{#isBatch}}
        <div class="batch">
            <input type="checkbox" name="" value="" class="J_BatchCheck" aria-label="全选" /> <label>全选</label>
            <a href="#" class="J_BatchSet">批量设置</a>
            <a href="#" class="J_BatchDel">批量删除</a>
        </div>
        {{/isBatch}}
        {{/withSpecial}}
        <div class="tbl-attach">
            <div class="J_SpecialMessage">{{#specialmessage}}<span class="msg J_Message"><span class="error">{{.}}</span></span>{{/specialmessage}}</div>
            <a href="#" class="J_AddRule">为指定地区城市设置运费</a>
            {{#withSpecial}}
            {{#isBatch}}
            <a href="#" class="J_ToggleBatch">取消批量</a>
            {{/isBatch}}
            {{^isBatch}}
            <a href="#" class="J_ToggleBatch">批量操作</a>
            {{/isBatch}}
            {{/withSpecial}}
        </div>
    </div>
</script>

<!--条件包邮模版开始-->
<script id="J_FreeTemplate" type="text/template" >
    {{@each list}}
    <tr data-index="{{xindex}}">
        <td>
            <input type="hidden" class="address-area" name="areas" value="{{areas}}">
            <a href="#" class="acc_popup edit J_Edit"   title="编辑运送区域" data-areas="{{areas}}">编辑</a>
            <div class="area-group">
                <p>
                    {{@each texts}}
                    {{this}}
                    {{/each}}
                </p>
            </div>
        </td>
        <td>
            <select name="transType" class="J_Service">
                {{@each types}}
                <option value="{{value}}"{{@if value===transType}}selected{{/if}}>{{text}}</option>
                {{/each}}
            </select>

        </td>
        <td>
            <select name="serviceType" class="J_Trans{{@if transType!=='111'}} hidden{{/if}}">
                {{@each service}}
                <option value="{{value}}" {{@if value===serviceType}}selected{{/if}}>{{text}}</option>
                {{/each}}
            </select>
        </td>
        <td>
            {{@with condition}}
            <select class="J_ChageContion" name="designated">
                {{@each options}}
                <option value="{{value}}" {{@if value===designated}}selected{{/if}} >{{text}}</option>
                {{/each}}
            </select>
            <p class="free-contion">
                {{@if designated==="0"}}
                {{act}} <input type="text" value="{{typeValue}}" class="input-text {{@if preferentialStandardError}}input-error{{/if}}" name="{{type}}">
                {{style}}包邮{{/if}}
                {{@if designated==="1"}}
                {{act}} <input type="text" name="preferentialMoney" class="input-text input-65 {{@if preferentialMoneyError}}input-error{{/if}}" value="{{preferentialMoney}}"> 元以上{{/if}}
                {{@if designated==="2"}}
                {{act}} <input type="text" value="{{typeValue}}" class="input-text {{@if preferentialStandardError}}input-error{{/if}}" name="{{type}}">
                {{style}},<input type="text" name="preferentialMoney" class="input-text input-65 {{@if preferentialMoneyError}}input-error{{/if}}" value="{{preferentialMoney}}"> 元以上包邮{{/if}}
            </p>
            {{/with}}
        </td>
        <td>
            <a href="" class="J_AddItem small-icon"></a>
            <a href="" class="J_DelateItem small-icon"></a>
        </td>
    </tr>
    {{/each}}
    <tr>
        <td colspan="5" class="hui-error">
            <div class="J_DefaultMessage">
                {{@each message}}
                <span class="msg J_Message">
		     <span class="error">
		      {{this}}
		     </span>
		   </span>
                {{/each}}
            </div>
        </td>

    </tr>
</script>
<!--条件包邮模版结束-->
<script>
    var presetData = {"list":{},"type":"number"};
    var areaGroup = {
        "group" : [{
            "title" : "华东",
            "codes" : ["310000", "320000", "330000", "340000", "360000"]
        }, {
            "title" : "华北",
            "codes" : ["110000", "120000", "140000", "370000", "130000", "150000"]
        }, {
            "title" : "华中",
            "codes" : ["430000", "420000", "410000"]
        }, {
            "title" : "华南",
            "codes" : ["440000", "450000", "350000", "460000"]
        }, {
            "title" : "东北",
            "codes" : ["210000", "220000", "230000"]
        }, {
            "title" : "西北",
            "codes" : ["610000", "650000", "620000", "640000", "630000"]
        }, {
            "title" : "西南",
            "codes" : ["500000", "530000", "520000", "540000", "510000"]
        }, {
            "title" : "港澳台",
            "codes" : ["810000", "820000", "710000"]
        }, {
            "title" : "海外",
            "codes" : ["990000"]
        }]
    };
</script>
<script>
    var cityUrl ='http://www.taobao.com/go/rgn/globaldata2.php';
    var fareTimeData = [
        {"text": "4小时内",  "value": "4"},
        {"text": "8小时内",  "value": "8"},
        {"text": "12小时内",  "value": "12"},
        {"text": "16小时内",  "value": "16"},
        {"text": "20小时内",  "value": "20"},
        {"text": "1天内",  "value": "24"},
        {"text": "2天内",  "value": "48"},
        {"text": "3天内",  "value": "72"},
        {"text": "5天内",  "value": "120"},
        {"text": "7天内",  "value": "168"},
        {"text": "8天内",  "value": "192"},
        {"text": "10天内",  "value": "240"},
        {"text": "12天内",  "value": "288"},
        {"text": "15天内",  "value": "360"},
        {"text": "17天内",  "value": "408"},
        {"text": "20天内",  "value": "480"},
        {"text": "25天内",  "value": "600"},
        {"text": "30天内",  "value": "720"},
        {"text": "35天内",  "value": "840"},
        {"text": "45天内",  "value": "1080"}
    ];

    //条件包邮模拟数据
    var initJSON ={"list":[],"message":[]}
    //条件包邮模拟数据
    initJSON = {"message":[],"list":[]}
    var switchFlag = false
    switchFlag = true
</script>


<!-- <input type="hidden" id="J_AreaGrid" data-url="https://delivery.taobao.com/user/all_area_msg.do?callback=AreaGrid.load&version={version}" /> -->
<input type="hidden" id="J_AreaGrid" data-url="/js/address_allarea_json.php?callback=AreaGrid.load&version=4" />

<script src="/js/package.js"></script>
<script src="https://g.alicdn.com/sd/data_sufei/1.5.1/aplus/index.js" charset="utf-8"></script>
<script src="https://s.tbcdn.cn/s/aplus_v2.js"></script>
<!-- <script src="//g.alicdn.com/??tm/wuliu-freight/1.0.8/base/underscore.js,tm/wuliu-freight/1.0.8/base/backbone.js,tm/wuliu-freight/1.0.8/base/mustache.js,tm/wuliu-freight/1.0.8/areagrid.js,tm/wuliu-freight/1.0.8/setting.js,tm/wuliu-freight/1.0.8/address.js,tm/wuliu-freight/1.0.8/carriageFree.js"></script> -->
<script>

    var S = KISSY,DOM = S.DOM,EV = S.Event;
    var e = KISSY, t = e.DOM, a = e.Event, i = e.all, r, n;
    var address = DOM.get("#J_address");
    addressValue = address.value;
    var prescription = DOM.get("#J_deliveryPrescription"),
        prescriptionValue = prescription.value;
    if(prescriptionValue){
        S.each(DOM.query("#J_prescription option"),function(item){
            item.selected = item.value === prescriptionValue;
        });
    }

    (function() {
        var getRootDomain = function() {
            var host = arguments[1] || location.hostname, da = host.split('.'), len = da.length, deep = arguments[0] || (len < 3 ? 0 : 1);
            if(deep >= len || len - deep < 2) {
                deep = len - 2;
            }

            return da.slice(deep).join('.');
        };
        document.domain = getRootDomain(2);
    })();

    S.use("free",function(S,Free){
        window.Free = new Free();
    })

</script>
<!-- <script src="/js/carriageFree.js" charset="utf-8"></script> -->
<script type="text/javascript">
    var msgObj = {
        tmplateInp:"必须填写模板名称",
        address:"请选择宝贝所在地",
        special:"请至少选择一种运送方式",
        shoufei:"首费应输入0.00至999.99的数字",
        xufei:"续费应输入0.00至999.99的数字",
        byAdress:"地址信息设置错误!",
        jianshu:"件数应设置为1～9999!"

    };
    function getMsg(str){
        var message = "<p class='msg'><span class='error'>"+str+"</span></p>"
        return message;
    }
    var flag = true;
    $("#J_TPLForm").on("submit",function(){

        var flag1 =	checkTemplateTitle();
        var flag2 =	checkAdress();
        var flag3 =	checkPostageDetail();

        if( flag1 &&  flag2 && flag3) {

            flag = true;
            return flag ;
        }else {
            flag = false;
            return flag ;
        }
       
    })

    function checkTemplateTitle(){
        var J_TemplateTitle = $("#J_TemplateTitle");
        var msg = $("#J_TemplateTitle").siblings(".msg");
        if(J_TemplateTitle.val()==""){
            msg.show();
            return false;
        }else{
            msg.hide();
            return true;
        }

    }

    function checkAdress(){
        var J_AddressChoose = $("#J_AddressChoose");
        var J_AddressCN = $(".J_AddressCN");

        var msg =  $("#J_AddressChoose").siblings(".msg");
        //判断国家 省市
        if(!!J_AddressCN && J_AddressCN.val() == "" ){
            msg.show();
            return false;
        }else if ( J_AddressCN.eq(1).val() == "" ){
             msg.show();
             return false;
        }else if ( J_AddressCN.eq(2).val() =="" ) {
            msg.show();
            return false;
        } else {
            msg.hide();
             return true;
        }
    }

    function checkPostageDetail(){
        var J_PostageDetail = $(".J_PostageDetail");
        var msg = $(".J_PostageDetail").parent().siblings(".msg");
        if(J_PostageDetail.text() == ""){
            msg.show();
            return false;
        }else{
            msg.hide();
             return true;
        }
    }
</script>


</body>
</html>
