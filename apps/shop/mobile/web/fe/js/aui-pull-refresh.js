/**
 * aui-pull-refresh.js
 * @author 流浪男
 * @todo more things to abstract, e.g. Loading css etc.
 * Licensed under the MIT license.
 * http://www.opensource.org/licenses/mit-license.php
 */
(function(window) {
	'use strict';
	/**
	 * Extend obj function
	 *
	 * This is an object extender function. It allows us to extend an object
	 * by passing in additional variables and overwriting the defaults.
	 */
	var auiPullToRefresh = function (params,callback) {

		this.extend(this.params, params);
		this._init(callback);
	}
	var touchYDelta;
	var isLoading = false;
	var docElem = window.document.documentElement,
		loadWrapH,
		win = {width: window.innerWidth, height: window.innerHeight},
		winfactor= 0.2,
		translateVal,
		isMoved = false,
		firstTouchY, 
		initialScroll;
	auiPullToRefresh.prototype = {
		params: { //参数
           // container: document.querySelector('.aui-refresh-content'), //触发对象
			friction: 2.5, //模拟摩擦力值
			triggerDistance: 100, //触发距离
			callback:false //回调函数
        },
        _init : function(callback) {
			var self = this;
			/*var loadingHtml = '<div class="aui-refresh-load"><div class="aui-refresh-pull-arrow"></div></div>';
			self.params.container.insertAdjacentHTML('afterbegin', loadingHtml);*/ //将上面的结构加入到结构顶部
			self.params.container.addEventListener('touchstart', function(ev){
				self.touchStart(ev)
			});
			self.params.container.addEventListener('touchmove', function(ev){
				self.touchMove(ev)
			});
			self.params.container.addEventListener('touchend', function(ev){
				self.touchEnd(ev,callback);
			});
		},
		touchStart : function(ev) {
			// this.params.container.classList.remove("refreshing");
			if (isLoading) {
				return;
			}
			isMoved = false;
			//兼容写法 过渡效果需要花费的时间
			this.params.container.style.webkitTransitionDuration =
		    this.params.container.style.transitionDuration = '0ms';
			touchYDelta = '';
			var touchobj = ev.changedTouches[0]; //涉及当前(引发)事件的触摸点的列表
			// register first touch "y"
			firstTouchY = parseInt(touchobj.clientY); //一开始点击的屏幕的y点
			initialScroll = this.scrollY(); //垂直方向滚动的像素

			//console.log(ev);
		},
		touchMove : function (ev) {
			if (isLoading) {
				ev.preventDefault(); //阻止默认事件
				return;
			}
			var self = this;
			var moving = function() {
				var touchobj = ev.changedTouches[0], // reference first touch point for this event
					touchY = parseInt(touchobj.clientY);
					touchYDelta = touchY - firstTouchY; //鼠标移动的距离
				if ( self.scrollY() === 0 && touchYDelta > 0  ) { //如果滚动条距离==0且 滑动的距离大于0 
					ev.preventDefault(); //阻止默认事件
				}
			
				//正常滚动的情况
				if ( initialScroll > 0 || self.scrollY() > 0 || self.scrollY() === 0 && touchYDelta < 0 ) {
					//初始滚动条大于0 或 当前滚动条大于等于0 且 鼠标移动的距离小于0

					firstTouchY = touchY;//滑动的点和触发的点要一致
					return;
				}
				translateVal = Math.pow(touchYDelta, 0.85);//取幂值,根据滑动的距离确定下滑的距离
				self.params.container.style.webkitTransform = self.params.container.style.transform = 'translate3d(0, ' + translateVal + 'px, 0)';
				isMoved = true;
				//console.log(window.pageYOffset);
				//滑动值对比初始化的设置 大于的话 显示对应效果
				if(touchYDelta > self.params.triggerDistance){
					/*self.params.container.classList.add("aui-refresh-pull-up");
					self.params.container.classList.remove("aui-refresh-pull-down");*/
				}else{
					/*self.params.container.classList.add("aui-refresh-pull-down");
					self.params.container.classList.remove("aui-refresh-pull-up");*/
				}
			};
			this.throttle(moving(), 20);
		},
		touchEnd : function (ev,callback) {
			var self =this;
			if (isLoading|| !isMoved) {
				isMoved = false;
				return;
			}
			// 根据下拉高度判断是否加载
			if( touchYDelta >= this.params.triggerDistance) {
				isLoading = true; //正在加载中
				ev.preventDefault();
				this.params.container.style.webkitTransitionDuration =
		    	this.params.container.style.transitionDuration = '300ms';
				this.params.container.style.webkitTransform =
				this.params.container.style.transform = 'translate3d(0,60px,0)';
				/*document.querySelector(".aui-refresh-pull-arrow").style.webkitTransitionDuration =
		    	document.querySelector(".aui-refresh-pull-arrow").style.transitionDuration = '0ms';
				self.params.container.classList.add("aui-refreshing");*/
				if(callback){
					callback({
						status:"success"
					});
				}
			}else{
				this.params.container.style.webkitTransitionDuration =
		    	this.params.container.style.transitionDuration = '300ms';
				this.params.container.style.webkitTransform =
				this.params.container.style.transform = 'translate3d(0,0,0)';
				if(callback){
					callback({
						status:"fail"
					});
				}
			}
			isMoved = false;
			return;
		},
		cancelLoading : function () {
			var self =this;
			isLoading = false;
			self.params.container.classList.remove("aui-refreshing");
			/*document.querySelector(".aui-refresh-pull-arrow").style.webkitTransitionDuration =
		    	document.querySelector(".aui-refresh-pull-arrow").style.transitionDuration = '300ms';*/
			this.params.container.style.webkitTransitionDuration =
		    	this.params.container.style.transitionDuration = '0ms';
			self.params.container.style.webkitTransform =
			self.params.container.style.transform = 'translate3d(0,0,0)';
			
			/*self.params.container.classList.remove("aui-refresh-pull-up");
			self.params.container.classList.add("aui-refresh-pull-down");*/
			return;
		},
		scrollY : function() {
			return window.pageYOffset || docElem.scrollTop;
		},
		//滑动节流
		throttle : function(fn, delay) {
			var allowSample = true;
			return function(e) {
				if (allowSample) {
					allowSample = false;
					setTimeout(function() { allowSample = true; }, delay);
					fn(e);
				}
			};
		},
		winresize : function () {
			var resize = function() {
				win = {width: window.innerWidth, height: window.innerHeight};
			};
			throttle(resize(), 10);
		},
		extend: function(a, b) {
			//根据传进来的参数 修改原始参数
			for (var key in b) {
			  	if (b.hasOwnProperty(key)) {
			  		a[key] = b[key];
			  	}
		  	}
		  	return a;
		 }
	}
	window.auiPullToRefresh = auiPullToRefresh;

})(window);