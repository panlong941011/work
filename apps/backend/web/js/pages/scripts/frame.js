
var idArray = ["首页"];
var leftUl = 0;

/**添加tab
*
*/
function addTab(title,url,id){
	var text = title;
	//将获取到的title转换为ASCII码
	var id = id || changeStrToAsc(text);
	if(text === ""){
		text = "未定义";
	}
	if(text.length>5){
		text = text.substring(0,5)+"...";
	}
	var url = url || "index.html";
	var index = idArray.indexOf(id);
	//已存在
	if(index >= 0){
		//如果有则删除原有插入末尾(保证最后一项始终未当前项)
		pushIdToArray(id);
		idArray.splice(index,1);
		//切换
		showApendedTab(url);

	}else{//不存在则追加
		//创建tab（标题，容器，下拉菜单）
		var tabHead = $("<li id='tt-"+id+"' class='tabs-title' onclick='switchTab(this)'><a data-toggle='tab' href='#con-"+id+"' title='"+text+"'>"+text+"</a><span class='tabs-close-btn' onclick=closeTab(this)>×</span></li>");
		var tabCon = $("<div class='tab-pane' id='con-"+id+"'><iframe id='ifr-"+id+"' name='ifr-"+id+"' scrolling='' frameborder='0' src='"+url+"' style='width:100%;height:100%;'></iframe></div>");
		var dropItem = $("<li id='di-"+id+"' onclick='showTab(this)'><a href='#con-"+id+"' data-toggle='tab'>"+text+"</a></li>");

		//追加tab（标题，tab容器，下拉菜单）
		$(".nav-pills").append(tabHead);
		$(".tab-content").append(tabCon);
		$(".tab-dropdow-box .dropdown-menu").prepend(dropItem);

		//追加active样式(框架库控制显示)
		tabHead.addClass("active").siblings().removeClass("active");
		tabCon.addClass("active").siblings().removeClass("active");

		//存储id
		pushIdToArray(id);

		//控制iframe自适应高度
		setIframesHeight();

		//控制tab移动
		controlTabTitle();
	}
	//取消默认操作
	return false;
}


/*将index追加到数组中
 *@title:标题名
 *@id:当前id
 *@url:跳转url
*/
function pushIdToArray(id){
	idArray.push(id);
}


/*切换已经追加过的
 *@
*/
function showApendedTab(url){
	//获取当前项(数组最后一项存储)
	var currentId = idArray[idArray.length-1];
	//显示
	$("#tt-"+currentId).addClass("active").siblings().removeClass("active");
	$("#con-"+currentId).addClass("active").siblings().removeClass("active");
	//替换frame的url并刷新
	$("#ifr-"+currentId).get(0).src = url;
}

/*默认关闭tab方法
 *鼠标点击关闭按钮触发
*/
function closeTab(obj) {
	//获取当前点击对象
	var $tabTitle = $(obj).parent();
	var tabTitle = $tabTitle.get(0);
	//当前点击元素id
	var currentClickId = tabTitle.id;
	//获取数组中存储的当前tab的ID
	var lastArrayItem = idArray[idArray.length-1];
	var currentTabId = "tt-"+lastArrayItem;

	//判断是否关闭的是当前Tab
	if(currentClickId == currentTabId){
		//移除当前tab
		$("#"+currentTabId).remove();
		$("#con-"+lastArrayItem).remove();
		$("#di-"+lastArrayItem).remove();
		//切换到下一个tab
		if(idArray.length == 1){
			//从数组中删除
			idArray.pop();
			return;
		}
		var showTabId = idArray[idArray.length-2];
		$("#tt-"+showTabId).addClass("active").siblings().removeClass("active");
		$("#con-"+showTabId).addClass("active").siblings().removeClass("active");

		//从数组中删除
		idArray.pop();
	}else{
		//移除当前tab
		$("#"+currentClickId).remove();
		var id = currentClickId.split('-')[1];
		$("#con-"+id).remove();
		$("#di-"+id).remove();

		//从数组中删除
		var index = idArray.indexOf(id);
		idArray.splice(index,1);
	}
}

function closeCurrTab()
{
	$(".tab-table-box li.active .tabs-close-btn").click();
}

/*点击标题切换，框架默认
 *
*/
function switchTab(obj) {
	//获取点击tab的id
	var currentClickId = obj.id;
	var arrayId = currentClickId.split('-')[1];

	//删除数组中原有的，插入最后
	var index = idArray.indexOf(arrayId);
	if(index < 0){
		return;
	}else{
		idArray.splice(index,1);
		idArray.push(arrayId);
	}

}

/*点击标题切换，框架默认
 *
*/
function showTab(obj){
	//获取当前项id
	var currentClickId = obj.id;
	var currentTabId = currentClickId.split('-')[1];

	//通过setTimeout实现异步，避开与框架默认click事件冲突
	setTimeout(function(){
		$("#tt-"+currentTabId).addClass("active").siblings().removeClass("active");
		$("#con-"+currentTabId).addClass("active").siblings().removeClass("active");
	},10);


	//切换数组元素
	var index = idArray.indexOf(currentTabId);
	if(index < 0){
		return;
	}
	idArray.splice(index,1);
	idArray.push(currentTabId);
}

/*tab标题控制
 *
*/

function controlTabTitle(){
	var $titleContainer = $(".nav-pills");
	//获取父容器宽度
	var pWidth = $titleContainer.parent().width();
	var maxWidth = pWidth;
	var liAllWidth = 0;
	var lastLiWidth = $titleContainer.find("li").last().width();
	$titleContainer.find("li").each(function(){
		var liWidth = $(this).width();
		liAllWidth += liWidth;
	});
	if(liAllWidth > maxWidth){
		$(".tab-dropdow-box").show();
		controlTabWidth();
	}
}

/**控制tab宽度
*
*/
function controlTabWidth(){
	//获取tab容器总宽度
	var allWidth = $(".tabbable").width();
	//获取tab标题数量
	var $tabs = $(".nav-pills").find("li");
	var tabNum = $tabs.length;
	//计算单个tab宽度
	var tabWidth = allWidth/tabNum;
	if(tabWidth>70){
		tabWidth = 70;
	}
	//设置每个tab宽度
	$tabs.each(function(){
		$(this).width(tabWidth);
	})
}
/*ifarme自适应窗口高度
*@
*/

function setIframesHeight() {
	$("iframe").each
	(
		function()
		{
			var iframe = $(this).get(0);
			var height = $(document.body).height() - $(".page-header").outerHeight() - $(".page-footer").outerHeight();
			//var height = $(".page-content").css("min-height");
			iframe.style.height = height + "px";
		}
	);
}

/**设置tab容器宽度
*@
*/
function setTabsContainerWidth(){
	//获取左侧相邻容器宽度
	var leftElemWidth = $(".page-header .page-logo").outerWidth();
	//获取右侧相邻容器 宽度
	var rightElemWidth = $(".page-header .top-menu").outerWidth();
	//获取总宽度
	var allWidth = $("body>.page-header").outerWidth();
	//预留宽度
	var offset = 40;
	//计算
	var tabsConWidth = parseInt(allWidth - leftElemWidth - rightElemWidth - offset);
	//设置tabs容器宽度
	$(".tab-table-box").width(tabsConWidth+"px");
}


$(window).resize
(
	function () {
		setIframesHeight();
		setTabsContainerWidth();
		controlTabWidth();
		if($(".tab-table-box").width()<600){
			 $(".tabdrop").hide();
		}else{
			 $(".tabdrop").show();
		}
	}
);

$(document).ready
(
	function () {
		setIframesHeight();
		//设置tab容器宽度
		setTabsContainerWidth();
	}

);

/*将字符串转换为ASCII码*/
function changeStrToAsc(str){
	  var strArray = [];
	  for (var i = 0; i < str.length; i++) {
	    var ret = str.charCodeAt(i);
	    strArray.push(ret);
	  }
	  return strArray.join("");
}


/********************内页操作主框架接口********************************/

/*添加父窗口tab
 *@title:标题名
 *@id:当前id
 *@url:跳转url
*/
function addWindowTab(title,url,id){
	var text = title;
	if(text == ""){
		text = "未定义";
	}
	if(id == ""){
		 id = id || text;
	}
	if(text.length>5){
		text = text.substring(0,5)+"...";
	}
	var url = url || "index.html";
	var index = this.parent.idArray.indexOf(id);
	//已存在
	if(index >= 0){
		//如果有则删除原有插入末尾(保证最后一项始终未当前项)
		pushIdToWindowArray(id);
		this.parent.idArray.splice(index,1);
		//切换
		showWindowApendedTab();

	}else{//不存在则追加
		//创建tab（标题，容器，下拉菜单）
		var tabHead = $("<li id='tt-"+id+"' class='tabs-title' onclick='switchTab(this)'><a  data-toggle='tab' href='#con-"+id+"'>"+text+"</a><span class='tabs-close-btn' onclick=closeTab(this)>×</span></li>");
		var tabCon = $("<div class='tab-pane' id='con-"+id+"'><iframe id='ifr-"+id+"' name='ifr-"+id+"' scrolling='' frameborder='0' src='"+url+"' style='width:100%;height:800px;'></iframe></div>");
		var dropItem = $("<li id='di-"+id+"'><a href='#con-"+id+"' data-toggle='tab'>"+text+"</a></li>");

		//追加tab（标题，tab容器，下拉菜单）
		$(this.parent.document).find(".nav-pills").append(tabHead);
		$(this.parent.document).find(".tab-content").append(tabCon);
		$(this.parent.document).find(".tab-dropdow-box").find(".dropdown-menu").prepend(dropItem);

		//追加active样式(框架库控制显示)
		tabHead.addClass("active").siblings().removeClass("active");
		tabCon.addClass("active").siblings().removeClass("active");

		//存储id
		pushIdToWindowArray(id);

		//控制tab移动
		// tabControl();
		setIframesHeight("ifr-"+id);

	}
	//取消默认操作
	return false;
}


/*将index追加到父窗口数组中
 *@title:标题名
 *@id:当前id
 *@url:跳转url
*/
function pushIdToWindowArray(id){
	this.parent.idArray.push(id);
}

/*切换已经追加过的
 *@
*/
function showWindowApendedTab(){
	//获取当前项(数组最后一项存储)
	var currentId = this.parent.idArray[this.parent.idArray.length-1];
	//显示
	$(this.parent.document).find("#tt-"+currentId).addClass("active").siblings().removeClass("active");
	$(this.parent.document).find("#con-"+currentId).addClass("active").siblings().removeClass("active");
}


/**@修改当前窗口tabs标题
 * @text，修改的内容
*/
function editorWindowTabText(text){
	if(text.length>5){
		text = text.substring(0,5)+"...";
	}
    //获取当前窗口的父窗口tabs
    var $currentTab = $(window.parent.document).find(".nav-pills").find("li.active").find("a");
    //标题内容
    $currentTab.text(text);
}


/*默认关闭tab方法
 *鼠标点击关闭按钮触发
 *obj为点击对象
*/
function closeWindowTab() {

	//父窗口document对象
	var $doc = $(this.parent.document);
	var pArray = this.parent.idArray;

	//获取当前id
	var currentId = pArray[pArray.length-1];

	//删除tab的标题和容器
	$doc.find("#tt-"+currentId).remove();
	$doc.find("#con-"+currentId).remove();
	$doc.find("#di-"+currentId).remove();

	//如果关闭的是最后一项
	if(pArray.length == 1){
		//从数组中删除
		pArray.pop();
		return;
	}

	//获取下一个要显示的id
	var showTabId = pArray[pArray.length-2];
	$doc.find("#tt-"+showTabId).addClass("active").siblings().removeClass("active");
	$doc.find("#con-"+showTabId).addClass("active").siblings().removeClass("active");

	//从数组中删除当前项id
	pArray.pop();

}


/*
 *@
*/
function showWindowTab(){
	//获取当前项(数组最后一项存储)
	var currentId = this.parent.idArray[this.parent.idArray.length-2];
	//显示
	$(this.parent.document).find("#tt-"+currentId).addClass("active").siblings().removeClass("active");
	$(this.parent.document).find("#con-"+currentId).addClass("active").siblings().removeClass("active");
}



/**@修改当前窗口tabs标题
 * @text，修改的内容
*/
function editorWindowTabText(text){
	if(!text||""){
		return;
	}

	if(text.length>5){
		text = text.substring(0,5)+"...";
	}

	//获取父窗口document
	var $doc = $(window.parent.document);
	var pArray = this.parent.idArray;

	//当前tab
	var currentTabId = pArray[pArray.length-1];

	//修改标题
	var $currentTab = $doc.find("#tt-"+currentTabId).find("a");
	$currentTab.text(text);

}


/**@自定义tab切换
*/
function tabClick(obj){
	var index = $(".tab-title>li").index(obj);
	$(obj).addClass("tab-bg").siblings().removeClass("tab-bg");
	$(".tab-con >div").eq(index).show().siblings().hide();
}
