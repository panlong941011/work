/**
 * 通用JS
 * Created by oyyz on 2017/10/10 0010.
 */
/*
 * 分享页面
 * @param {String} title, 分享标题
 * @param {String} imgUrl, 分享图片
 * @param {String} desc, 分享描述
 * @param {String} link, 分享链接，默认当前页面地址
 * @author oyyz <oyyz@3elephant.com>
 * @since 2017-10-10 11:16:22
 * */
function sharePage(title, imgUrl, desc, link) {
    if (typeof wx === 'undefined') {
        return false;
    }
    wx.ready(function () {
        var shareObj = {};

        shareObj.title = title;// 分享标题
        shareObj.link = link || location.href;// 分享链接
        shareObj.imgUrl = imgUrl;// 分享图标
        shareObj.desc = desc;// 分享描述
        shareObj.success = function () {
            // 用户确认分享后执行的回调函数
        };
        shareObj.cancel = function () {
            // 用户取消分享后执行的回调函数
        };

        wx.onMenuShareTimeline(shareObj);//分享朋友圈
        wx.onMenuShareAppMessage(shareObj);//分享给朋友
        wx.onMenuShareQQ(shareObj);//分享到QQ
        wx.onMenuShareWeibo(shareObj);//分享到腾讯微博
        wx.onMenuShareQZone(shareObj);//分享到QQ空间
    });
};

//返回键控制
function goBack() {
      document.referrer === '' ? window.location.href = 'http://m.shop.myerm.cn/shop0/home' : window.history.go(-1);
}


//通用显示组件设置
function isType(type) {  //函数判断
    return function (obj) { 
        return {}.toString.call(obj) == "[object " + type + "]"
    }
}
var isFunction = isType("Function"); //赋值给isFunction

//创建构造函数
function shopErm() {

}
shopErm.fn = shopErm.prototype; //原型赋值

shopErm.fn.showTip = function(word,callback) {  //显示提示框
    var tip = $('#tip');
    if( word ) {
        tip.text(word);
        tip.show();
        setTimeout(function() {
            tip.hide();
            if( callback && isFunction( callback ) ) {
                callback();
            }
        }, 2000);
    }
};

shopErm.fn.selection = function(word,callback,callback2) {  //显示选择框
     var sel = $('.selection_bar');
    var tipWord = $('.select_name');
    var sure = $('.select_sure');
    var cancel = $('.select_cancel');
    if(!word) { //错误优先
        return;
    }
    tipWord.text(word);
    sel.show();
    sure.on('click',function() {  //做函数判断
        if( callback && isFunction( callback ) ) {
            sel.hide();
            callback();

        }
        callback = null; //清除缓存事件 避免二次运行
    });
    cancel.on('click',function() {
        if( callback2 && isFunction( callback2 ) ) {
            sel.hide();
            callback2();
        }
        callback = null;
    })
}


var shoperm = new shopErm();