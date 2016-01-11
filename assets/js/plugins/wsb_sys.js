define(function (require, exports, module) {
    var $ = require('jquery');
    /**
     * jquery 倒计时 两个时间 放在对象内标签（data-start-time/data-end-time ） 开标时间 和截至时间 对应两个时间截至到处理函数
     * @param callback1 开标时间截至到处理函数
     * @param callback2 标的结束的处理函数
     * @param func 处理函数
     */
    $.fn.count_down = function(callback1,callback2,func,now_time){
        var curren_run_time = 1;
        if( ! now_time)now_time = Date.parse(new Date())/1000;
        var count_down = function() {this.tt=0;this.now_time=null};
        count_down.prototype = {
            'go':function(e,end_time,callback,deal_func) {
                if(this.now_time == null)this.now_time=now_time;
                var time = this.now_time;//Date.parse(new Date())/1000;
                var time_space =end_time-time;
                var s = 0,m = 0,h = 0,d = 0;
                if(time_space > 0){
                    s = time_space%60;
                    m = Math.floor(time_space/60)%60;
                    h = Math.floor(Math.floor(time_space/60)/60)%24;
                    d = Math.floor(Math.floor(Math.floor(time_space/60)/60)/24);
                    if(s<10)s="0"+s;
                    if(m<10)m="0"+m;
                    if(h<10)h="0"+h;
                    if(d<10)d="0"+d;
                    if(typeof deal_func == 'function'){
                        deal_func(curren_run_time,e,d,h,m,s);
                    }else {
                        e.find('.s').text(s);
                        e.find('.m').text(m);
                        e.find('.h').text(h);
                        e.find('.d').text(d);
                    }
                    var _this = this;
                    this.tt=setTimeout(function(){_this.go(e,end_time,callback,deal_func);},1000);
                }else{
                    if(typeof deal_func == 'function'){
                        deal_func(curren_run_time,e,d,h,m,s);
                    }else{
                        e.find('.s').text('00');
                        e.find('.m').text('00');
                        e.find('.h').text('00');
                        e.find('.d').text('00');
                    }

                    clearTimeout(this.tt);
                    if(typeof callback == 'function')callback();
                }
                this.now_time++;
            }
        };
        if(this.length > 1){
            $(this).each(function(i,v){
                var _this =$(v);
                var time1 = _this.attr('data-start-time') | 0;
                var time2 = _this.attr('data-end-time') | 0;
                var cd = new count_down();
                if(_this.css('visibility') == 'hidden')_this.css('visibility','visible');
                cd.go(_this,time1,function(){
                    if(typeof callback1 == 'function')callback1(_this);
                    curren_run_time = 2;
                    cd.go(_this,time2,function(){if(typeof callback2 == 'function')callback2(_this);},func);
                },func);
            });
        }else{
            var _this =this;
            var time1 = _this.attr('data-start-time') | 0;
            var time2 = _this.attr('data-end-time') | 0;
            var cd = new count_down();
            if(_this.css('visibility') == 'hidden')_this.css('visibility','visible');
            cd.go(_this,time1,function(){
                if(typeof callback1 == 'function')callback1(_this);
                curren_run_time = 2;
                cd.go(_this,time2,function(){if(typeof callback2 == 'function')callback2(_this);},func);
            },func);
        }
        return this;
    };

    $.fn.send_sms = function(type,mobile,action,sms_callback){
        var wait = 60,last_send_time_go = '',tag_default_msg = '',is_input = false;
        if(typeof g_sms_apace_time != 'undefined'){
            wait = g_sms_apace_time;
        }
        if(type == 'sms' && typeof g_sms_last_time != 'undefined' && g_sms_last_time > 0){
            last_send_time_go = Date.parse(new Date())/1000 - g_sms_last_time;
        }
        if(type == 'voice' && typeof g_voice_last_time != 'undefined' && g_voice_last_time > 0){
            last_send_time_go = Date.parse(new Date())/1000 - g_voice_last_time;
        }

        if( ! mobile){
            wsb_alert('电话号码不能为空!',2);
            return;
        }
        if(this.data('waitTime') != 'undefined' && parseInt(this.data('waitTime')) > 0){
            wait = parseInt(this.data('waitTime'));
        }
        if(this.data('lastTime') != 'undefined' && parseInt(this.data('lastTime')) > 0){
            last_send_time_go = Date.parse(new Date())/1000 - parseInt(this.data('lastTime'));
        }
        if(this.get(0).tagName == 'INPUT'){
            tag_default_msg = this.val();
            is_input = true;
        }else{
            tag_default_msg = this.html();
        }

        var _this = this;
        //倒计时 效果处理
        var sms_count_down = function(e,space_time,all_time,callback){
            var wait=space_time;
            var t = 0;
            var time = function(o){
                if (wait == 0) {
                    o.removeAttr("disabled");
                    if(is_input){
                        o.val(tag_default_msg);
                    }else{
                        o.html(tag_default_msg);
                    }

                    wait = all_time;
                    clearTimeout(t);
                    _this.bind('click',function(){send_event();});
                    if(typeof callback == "function"){
                        callback();
                    }
                } else {
                    o.attr("disabled","true");
                    _this.unbind('click');
                    if(is_input){
                        o.val("" + wait + "秒后再次发送");
                    }else{
                        o.html("" + wait + "秒后再次发送");
                    }

                    wait--;
                    t = setTimeout(function() {
                        time(o)
                    },1000)
                }
            };
            time(e);
        };

        //发送到ajax事件
        var send_event = function(){
            _this.unbind('click');
            $.ajax({
                type: 'POST',
                url: '/index.php/send/index',
                data: {'type':type,'mobile':mobile,'action':action},
                dataType: 'json',
                success: function (result) {
                    if(result.status == '10000'){
                        if(type == 'voice'){
                            wsb_alert('稍后聚雪球将通过电话4007-918-333拨打' +
                                '您的手机'+mobile+'告知验证码!',1);
                        }else{
                            wsb_alert('短信已发送,请注意查收!',1);
                        }
                        //发送成功 执行显示效果
                        sms_count_down(_this,wait,wait,function(){ _this.bind('click',function(){send_event();});});
                    }else{
                        wsb_alert(result.msg ,2);
                    }
                    if(typeof  sms_callback == "function")sms_callback(result);
                }
            });
        };
        //验证上一次发送到时间间隔
        if(last_send_time_go !== '' && last_send_time_go < wait){
            sms_count_down(this,wait-last_send_time_go,wait,function(){_this.bind('click',function(){send_event();});});
        }else{
            _this.bind('click',function(){send_event();});
        }
        return this;
    }
});

/**
 * 验证身份证
 * @param gets
 * @returns {*}
 */
var is_nric = function (gets) {

    var Wi = [7, 9, 10, 5, 8, 4, 2, 1, 6, 3, 7, 9, 10, 5, 8, 4, 2, 1];// 加权因子;
    var ValideCode = [1, 0, 10, 9, 8, 7, 6, 5, 4, 3, 2];// 身份证验证位值，10代表X;

    if (gets.length == 18) {
        var a_idCard = gets.split("");// 得到身份证数组
        if (isValidityBrithBy18IdCard(gets) && isTrueValidateCodeBy18IdCard(a_idCard)) {
            return true;
        }
        return false;
    }
    return false;

    function isTrueValidateCodeBy18IdCard(a_idCard) {
        var sum = 0; // 声明加权求和变量
        if (a_idCard[17].toLowerCase() == 'x') {
            a_idCard[17] = 10;// 将最后位为x的验证码替换为10方便后续操作
        }
        for (var i = 0; i < 17; i++) {
            sum += Wi[i] * a_idCard[i];// 加权求和
        }
        valCodePosition = sum % 11;// 得到验证码所位置
        if (a_idCard[17] == ValideCode[valCodePosition]) {
            return true;
        }
        return false;
    }

    function isValidityBrithBy18IdCard(idCard18) {
        var year = idCard18.substring(6, 10);
        var month = idCard18.substring(10, 12);
        var day = idCard18.substring(12, 14);
        var temp_date = new Date(year, parseFloat(month) - 1, parseFloat(day));
        // 这里用getFullYear()获取年份，避免千年虫问题
        if (temp_date.getFullYear() != parseFloat(year) || temp_date.getMonth() != parseFloat(month) - 1 || temp_date.getDate() != parseFloat(day)) {
            return false;
        }
        return true;
    }

    function isValidityBrithBy15IdCard(idCard15) {
        var year = idCard15.substring(6, 8);
        var month = idCard15.substring(8, 10);
        var day = idCard15.substring(10, 12);
        var temp_date = new Date(year, parseFloat(month) - 1, parseFloat(day));
        // 对于老身份证中的你年龄则不需考虑千年虫问题而使用getYear()方法
        if (temp_date.getYear() != parseFloat(year) || temp_date.getMonth() != parseFloat(month) - 1 || temp_date.getDate() != parseFloat(day)) {
            return false;
        }
        return true;
    }

}

/*************************** 倒计时函数 wsb add*************************************************/