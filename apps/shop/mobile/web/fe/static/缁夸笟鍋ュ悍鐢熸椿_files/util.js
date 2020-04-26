Util = (function(){
	
})

/*将HTML标签字符转换成特殊HTML entities(如：'<div id="abc">&</div>'转换为'&lt;div id=&quot;abc&quot;&gt;&amp;&lt;/div&gt;')*/
Util.escape = function(str){
	return str.replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;');
}

/*将特殊HTML entities转换成HTML标签字符(如：'&lt;div id=&quot;abc&quot;&gt;&amp;&lt;/div&gt;'转换为'<div id="abc">&</div>')*/
Util.unescape = function(str){
	return str.replace(/&lt;/g, '<').replace(/&gt;/g, '>').replace(/&quot;/g, '"').replace(/&amp;/g, '&');
}

/*过滤掉字符串中所有的html标签和空格*/
Util.filterHtml = function(str){
	var start_ptn = /<\/?[^>]*>/g;//过滤标签开头      
    var end_ptn = /[ | ]*\n/g;//过滤标签结束  
    var space_ptn = /\s/g;//过滤所有的空格
    if(str.length==0)
    	return '';
    else
    	return Util.replaceStr(str.replace(start_ptn,"").replace(end_ptn,"").replace(space_ptn,""),"&nbsp;","");
}

/*将字符串中指定的字符或者字符子串全部替换掉
 * str：字符串
 * substring：要替换的目标字符或者字符子串
 * replaceWith：替换成的字符或者字符子串
 */
Util.replaceStr = function(str,substring,replaceWith){
		var e=new RegExp(substring,"g");
		var resultStr = str.replace(e, replaceWith);
		return resultStr;
} 

/*
 * 将数字number转换为货币字符串的格式(参数：数值，保留小数位数，货币符号，整数部分千位分隔符，小数分隔符)
 * */
Util.formatMoney = function(number, places, symbol, thousand, decimal) {
    number = number || 0;
    places = !isNaN(places = Math.abs(places)) ? places : 2;
    symbol = symbol !== undefined ? symbol : "$";
    //thousand = thousand || ",";
    decimal = decimal || ".";
    var negative = number < 0 ? "-" : "",
        i = parseInt(number = Math.abs(+number || 0).toFixed(places), 10) + "",
        j = (j = i.length) > 3 ? j % 3 : 0;
    return symbol + negative + (j ? i.substr(0, j) + thousand : "") + i.substr(j).replace(/(\d{3})(?=\d)/g, "$1" + thousand) + (places ? decimal + Math.abs(number - i).toFixed(places).slice(2) : "");
}

/*
 * 将小数转换为百分比的格式(参数：数值，保留小数位数)
 * */
Util.formatPercent = function(number, places) {
	number = number || 0;
	number = number*100;
	var str = Util.formatMoney(number,places,"",",",".");
	return str + "%";
}

/**
d为date对象、long
f为返回类型，如下：
返回：2015-12-10	f==1
2015-12-10 23:23:23	f==2
2015-12-10 23:23	f==3
*/
Util.getDateFormat = function(d,f){
	var date;
	if (d instanceof Date){
		date = d;
	}else {
		d = d + "";
		if(d.indexOf("-")>0)
			date = new Date(Date.parse(d.replace(/-/g, "/")));
		else
			date = new Date(parseInt(d));
	}
	var month = date.getMonth() + 1;
	if (month < 10)
		month = "0" + month;
	var day = date.getDate();
	if (day < 10)
		day = "0" + day;
	if (f > 1) {
		var h = date.getHours();
		if (h < 10)
			h = "0" + h;
		var m = date.getMinutes();
		if (m < 10)
			m = "0" + m;
		if (f == 3)
			return date.getFullYear() + "-" + month + "-" + day + " " + h + ":" + m;
		else {
			var s = date.getSeconds();
			if (s < 10)
				s = "0" + s;
			return date.getFullYear() + "-" + month + "-" + day + " " + h + ":" + m + ":" + s;
		}
	}
	return date.getFullYear() + "-" + month + "-" + day;
}
/**
 * 验证手机号码
 */
Util.checkTel = function(value){
    var isPhone = /^([0-9]{3,4}-)?[0-9]{7,8}$/;
    var isMob=/^((\+?86)|(\(\+86\)))?(13[0123456789][0-9]{8}|15[0123456789][0-9]{8}|18[0123456789][0-9]{8}|147[0-9]{8}|145[0-9]{8}|17[0135678][0-9]{8})$/;

    if(isMob.test(value)||isPhone.test(value)){
        return true;
    }
    else{
        return false;
    }
}
/**
 * 验证邮政编码
 */
Util.checkCode = function(value){
	var isCode = /^[1-9][0-9]{5}$/;
	
	if(isCode.test(value))
		return true;
	else
		return false;
	
}
/**
 * 验证价格
 */
Util.checkPrice = function(value){
	var isPrice = /^(0|[1-9][0-9]{0,9})(\.[0-9]{1,2})?$/;
	
	if(isPrice.test(value)){
		return true;
	}else{
		return false;
	}
}
/**
 * 验证正整数
 */
Util.checkNum = function(value){
	var isNum = /^[0-9]*[1-9][0-9]*$/;
	
	if(isNum.test(value)){
		return true;
	}else{
		return false;
	}
}
/**
 * 验证英文字符+数字
 */
Util.checkEnAndNum = function(value){
	var isEnAndNum = /^[a-zA-Z0-9]+$/;
	
	if(isEnAndNum.test(value)){
		return true;
	}else{
		return false;
	}
}
