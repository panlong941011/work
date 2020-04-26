//弹出组件 两套组件 由于一些原因 没有做融合
/*   这组用于退款的组件 操作事件不完全
    props:  父级传进来的对象参数 { title: string, data: [ isMark: boolean, name: string ], }
 */
var popup = Vue.extend({
        template: '<div class="m-actionsheet popup" id="J_ActionSheet">' +
        '<h2>{{selectData.title}}</h2>' +
        '<ul>' +
        '<li class="reason_item" v-for="(item,index) in selectData.data" :class="{active: item.isMark}" @click="doSelect(item.name,index)">{{item.name}}</li>' +
        '</ul>' +
        '<div class="popup_cancel" id="J_Cancel">关闭</div>' +
        '</div>',
        props: ['selectname'],//父组件的传值 必须是小写
        data: function () {
            return {
                selectData: this.selectname,
            }
        },
        watch: {
            selectname: function () { //用于监听多个选项时 传进来的对象能及时变化
                this.selectData = this.selectname;
            }
        },
        mounted: function () {

        },
        methods: {
            doSelect: function (val, index) {
                this.$emit('selected', val); //自定义事件 返回结果
                var len = this.selectData.data.length;
                for (var i = 0; i < len; i++) {
                    this.selectData.data[i].isMark = false;
                }
                this.selectData.data[index].isMark = true;
            }
        }
})

/*   这组用于经销商的弹出框 事件完全 没有动画
    props:  父级传进来的数组参数 [ { name: string, isMark: boolean} ]
 */

 var commission = Vue.extend({
        template: '<div class="com_type_wrap" @click="closeCon">'+
        '<div class="com_type">'+
        '<h2>请选择</h2>' +
        '<ul>'+
        '<li class="com_type_item" v-for="(item,index) in commissionData" :class="{active: item.isMark}" @click="doSelect(item.name,index)">{{item.name}}</li>'+
        '</ul>'+
        '<div class="com_type_chose" @click="closeCon">关闭</div>'+
        '</div>'+
        ' </div>',

        props: ['slectdata'],//父组件的传值 必须是小写
        data: function () {
            return {
                commissionData: this.slectdata,
            }
        },
        watch: {

        },
        mounted: function () {
    
        },
        methods: {
            doSelect: function(name,val) {
                event.stopPropagation();
                this.$emit('selected', name);
                var len = this.commissionData.length;
                for (var i = 0; i < len; i++) {
                    this.commissionData[i].isMark = false;
                }
                this.commissionData[val].isMark = true;

                this.closeCon();
            },
            closeCon: function() { //关闭弹窗
                this.$emit('partopt',false);
            }
        }
});


// 倒计时组件 有两种形式的倒计时 用于首页
// 方格式 和 列表式
var squareClocker = Vue.extend({
    template: '<div class="buy_time" v-text="isContent" v-if="!timeOut">00天00时00分00秒</div>',
    props: ['datatime', 'index'],
    data: function () {
        return {
            isTime: this.datatime,
            isContent: '',
            isTime: null,
            timeOut: false,

        }
    },
    watch: {},
    mounted: function () {
        var nowTime = (new Date(dNow).getTime()) / 1000, //后端时间数据转成秒
            index = this.index; //获取当前是第几个

        /* window.localStorage.removeItem("oldTime");
         window.localStorage.removeItem("seckilltime0")*/

        var l_time = parseInt(window.sessionStorage.getItem("seckilltime" + index)); //是否有时间缓存
        if (l_time) {
            nowTime = l_time;
            var oldTime = window.sessionStorage.getItem("oldTime");
            var thisTime = new Date();
            var difference = parseInt((thisTime.getTime() - new Date(oldTime).getTime()) / 1e3);

            nowTime = nowTime + difference;
        }

        this.init(nowTime); //初始化 防止一显示为空
        var _this = this;
        setInterval(function () {

            var index = _this.index;

            var leftTime = _this.datatime.replace(/\-/g, "/"), //时间格式替换 IOS不支持 '-'
                endTime = new Date(leftTime),
                disTime = endTime.getTime() - nowTime * 1000, //这里时间数据转成毫秒
                day = Math.floor(disTime / (1000 * 60 * 60 * 24)),
                hour = Math.floor(disTime / (1000 * 60 * 60) % 24),
                minute = Math.floor(disTime / (1000 * 60) % 60),
                second = Math.floor(disTime / 1000 % 60);
            if (disTime <= 0) {
                _this.timeOut = true;
                window.sessionStorage.removeItem("seckilltime" + index);
            }
            day = _this.checkTime(day);
            hour = _this.checkTime(hour);
            minute = _this.checkTime(minute);
            second = _this.checkTime(second);
            _this.isContent = '距开抢:' + day + '天' + hour + '时' + minute + '分' + second + '秒';

            var momentTime = new Date();
            window.sessionStorage.setItem("oldTime", momentTime);

            nowTime = nowTime + 1;
            window.sessionStorage.setItem("seckilltime" + index, nowTime);

        }, 1000)
    },
    methods: {
        //倒计时函数
        init: function (nowTime) {
            var index = this.index;//存储当前是第几个倒计时

            var leftTime = this.datatime.replace(/\-/g, "/"), //时间格式替换 IOS不支持 '-'
                endTime = new Date(leftTime),
                disTime = endTime.getTime() - (nowTime - 1) * 1000, //这里时间数据转成毫秒
                day = Math.floor(disTime / (1000 * 60 * 60 * 24)),
                hour = Math.floor(disTime / (1000 * 60 * 60) % 24),
                minute = Math.floor(disTime / (1000 * 60) % 60),
                second = Math.floor(disTime / 1000 % 60);

            if (disTime <= 0) {
                this.timeOut = true;
                window.sessionStorage.removeItem("seckilltime" + index);
            }
            day = this.checkTime(day);
            hour = this.checkTime(hour);
            minute = this.checkTime(minute);
            second = this.checkTime(second);
            this.isContent = '距开抢:' + day + '天' + hour + '时' + minute + '分' + second + '秒';

        },
        checkTime: function (i) {
            if (i < 10) {
                i = '0' + i;
            }
            return i;
        },
    }
});

var listClocker = Vue.extend({
    template: '<div class="commodity_time_wrap flex" v-if="!timeOut">' +
    ' <em>距开抢</em>' +
    '<div class="commodity_time" >' +
    ' <span v-text="isDay"></span>' + ' 天 ' +
    '<span v-text="isHour"></span> : ' +
    '<span v-text="isMin"></span> : ' +
    ' <span v-text="isSecond"></span>' +
    '</div>' +
    ' </div>',
    props: ['datatime', 'index'],
    data: function () {
        return {
            isTime: this.datatime,
            isTime: null,
            timeOut: false,
            isDay: 0,
            isHour: 0,
            isMin: 0,
            isSecond: 0,
        }
    },
    watch: {},
    mounted: function () {

        var nowTime = (new Date(dNow).getTime()) / 1000, //后端时间数据转成秒
            index = this.index;

        var l_time = parseInt(window.sessionStorage.getItem("seckillListTime" + index));
        if (l_time) {
            nowTime = l_time;
            var oldTime = window.sessionStorage.getItem("oldListTime");
            var thisTime = new Date();
            var difference = parseInt((thisTime.getTime() - new Date(oldTime).getTime()) / 1e3);
            nowTime = nowTime + difference;
        }
        this.init(nowTime);

        var _this = this;
        setInterval(function () {

            var index = _this.index;

            var leftTime = _this.datatime.replace(/\-/g, "/"),
                endTime = new Date(leftTime),
                disTime = endTime.getTime() - nowTime * 1000, //这里时间数据转成毫秒
                day = Math.floor(disTime / (1000 * 60 * 60 * 24)),
                hour = Math.floor(disTime / (1000 * 60 * 60) % 24),
                minute = Math.floor(disTime / (1000 * 60) % 60),
                second = Math.floor(disTime / 1000 % 60);
            if (disTime <= 0) {
                this.timeOut = true;
                window.sessionStorage.removeItem("seckillListTime" + index);
            }
            _this.isDay = _this.checkTime(day);
            _this.isHour = _this.checkTime(hour);
            _this.isMin = _this.checkTime(minute);
            _this.isSecond = _this.checkTime(second);

            var momentTime = new Date();
            window.sessionStorage.setItem("oldListTime", momentTime);

            nowTime = nowTime + 1;
            window.sessionStorage.setItem("seckillListTime" + index, nowTime);

        }, 1000)
    },
    methods: {
        init: function (nowTime) {
            var _this = this;
            var index = _this.index;
            var leftTime = _this.datatime.replace(/\-/g, "/"),
                endTime = new Date(leftTime),
                disTime = endTime.getTime() - (nowTime - 1) * 1000, //这里时间数据转成毫秒
                day = Math.floor(disTime / (1000 * 60 * 60 * 24)),
                hour = Math.floor(disTime / (1000 * 60 * 60) % 24),
                minute = Math.floor(disTime / (1000 * 60) % 60),
                second = Math.floor(disTime / 1000 % 60);
            if (disTime <= 0) {
                _this.timeOut = true;
                window.sessionStorage.removeItem("seckillListTime" + index);
            }
            _this.isDay = _this.checkTime(day);
            _this.isHour = _this.checkTime(hour);
            _this.isMin = _this.checkTime(minute);
            _this.isSecond = _this.checkTime(second);

        },
        checkTime: function (i) {
            if (i < 10) {
                i = '0' + i;
            }
            return i;
        },
    }
});