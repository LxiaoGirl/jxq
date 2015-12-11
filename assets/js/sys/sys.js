/* 
* @Author: anchen
* @Date:   2015-10-12 15:17:23
* @Last Modified by:   anchen
* @Last Modified time: 2015-12-02 16:55:14
*/
function addnav (e) {
    //nav and main_nav 100%
    var wholewd=$("body").width();
    var halfpad=(wholewd-1200)/2;
    e.css({ left: "-"+halfpad+"px",padding:"0 "+halfpad+"px"}); 
};
function main_nav_pop (e) {
    //main_nav_pop
    
    e.hover(
        function () {
            $(".mnavtop").stop(true,true);
            $(this).find($(".mian_nav_li_pop")).slideDown();
            $(this).find($(".mnavtop")).animate({width:'100%'}); 
        },
        function () {
            $(".mnavtop").stop(true,true);
            $(this).find($(".mian_nav_li_pop")).slideUp();
            $(this).find($(".mnavtop")).animate({width:'0%'});
        }
    ); 
};
function nav_pop (e) {
    // nav_pop
    e.hover(function() {
        $(this).find($(".nav_pop")).toggle();
    });
};
function tab (e) {
    // nav_pop
    e.find('.tab_title').find('li').click(function() {
        $(this).siblings().removeClass('active')
        $(this).addClass('active');
        var i=$(this).index();
        e.find('.tab_con').find('li').removeClass('active');
        e.find('.tab_con').find('li').eq(i).addClass('active');
    });
};
function tab_1 (e) {
    // nav_pop
    e.find('.tab_title').find('li').click(function() {
        $(this).siblings().removeClass('active')
        $(this).addClass('active');
        var i=$(this).index();
        e.find('.tab_con').find('.li').removeClass('active');
        e.find('.tab_con').find('.li').eq(i).addClass('active');
    });
};
//4个倒计时
function djs_1 () {
        // body...
        var e=$(".djs_1");
        var endtime=e.find('.endtime').text();
        function js_djs (e,endtime) {
            var time = Date.parse(new Date())/1000;
            var jxsj =endtime-time;
            var s = jxsj%60;
            var m = parseInt(jxsj/60)%60;
            var h = parseInt(jxsj/60/60)%60;
            var d = parseInt(jxsj/60/60/24)%24;
            if(s<10){
                s="0"+s;
            }
            if(m<10){
                m="0"+m;
            }
            if(h<10){
                h="0"+h;
            }
            if(d<10){
                d="0"+d;
            }
            e.find('.s').text(s);
            e.find('.m').text(m);
            e.find('.h').text(h);
            e.find('.d').text(d);
        }
        function run () {
            js_djs(e,endtime);
        }
        run();
        setTimeout("djs_1()",1000);
}
function djs_2 () {
        // body...
        var e=$(".djs_2");
        var endtime=e.find('.endtime').text();
        function js_djs (e,endtime) {
            var time = Date.parse(new Date())/1000;
            var jxsj =endtime-time;
            var s = jxsj%60;
            var m = parseInt(jxsj/60)%60;
            var h = parseInt(jxsj/60/60)%60;
            var d = parseInt(jxsj/60/60/24)%24;
            if(s<10){
                s="0"+s;
            }
            if(m<10){
                m="0"+m;
            }
            if(h<10){
                h="0"+h;
            }
            if(d<10){
                d="0"+d;
            }
            e.find('.s').text(s);
            e.find('.m').text(m);
            e.find('.h').text(h);
            e.find('.d').text(d);
        }
        function run () {
            js_djs(e,endtime);
        }
        run();
        setTimeout("djs_2()",1000);
}
function djs_3 () {
        // body...
        var e=$(".djs_3");
        var endtime=e.find('.endtime').text();
        function js_djs (e,endtime) {
            var time = Date.parse(new Date())/1000;
            var jxsj =endtime-time;
            var s = jxsj%60;
            var m = parseInt(jxsj/60)%60;
            var h = parseInt(jxsj/60/60)%60;
            var d = parseInt(jxsj/60/60/24)%24;
            if(s<10){
                s="0"+s;
            }
            if(m<10){
                m="0"+m;
            }
            if(h<10){
                h="0"+h;
            }
            if(d<10){
                d="0"+d;
            }
            e.find('.s').text(s);
            e.find('.m').text(m);
            e.find('.h').text(h);
            e.find('.d').text(d);
        }
        function run () {
            js_djs(e,endtime);
        }
        run();
        setTimeout("djs_3()",1000);
}
function djs_4 () {
        // body...
        var e=$(".djs_4");
        var endtime=e.find('.endtime').text();
        function js_djs (e,endtime) {
            var time = Date.parse(new Date())/1000;
            var jxsj =endtime-time;
            var s = jxsj%60;
            var m = parseInt(jxsj/60)%60;
            var h = parseInt(jxsj/60/60)%60;
            var d = parseInt(jxsj/60/60/24)%24;
            if(s<10){
                s="0"+s;
            }
            if(m<10){
                m="0"+m;
            }
            if(h<10){
                h="0"+h;
            }
            if(d<10){
                d="0"+d;
            }
            e.find('.s').text(s);
            e.find('.m').text(m);
            e.find('.h').text(h);
            e.find('.d').text(d);
        }
        function run () {
            js_djs(e,endtime);
        }
        run();
        setTimeout("djs_4()",1000);
}
//4个倒计时
//首页和投资首页按钮倒计时
//updateEndTime();
//倒计时函数
function updateEndTime()
{
var date = new Date();
var time = date.getTime();


$(".settime").each(function(i){


var endDate =this.getAttribute("endTime"); //结束时间字符串
//转换为时间日期类型
var endDate1 = eval('new Date(' + endDate.replace(/\d+(?=-[^-]+$)/, function (a) { return parseInt(a, 10) - 1; }).match(/\d+/g) +')');


var endTime = endDate1.getTime(); //结束时间毫秒数


var lag = (endTime - time) / 1000; //当前时间和结束时间之间的秒数
if(lag > 0)
{
var second = Math.floor(lag % 60); 
var minite = Math.floor((lag / 60) % 60);
var hour = Math.floor((lag / 3600) % 24);
var day = Math.floor((lag / 3600) / 24);
    if(day<1){
        if(hour<1){
                $(this).html("距开标还剩"+minite+"分"+second+"秒");
        }else{
            $(this).html("距开标还剩"+hour+"小时"+minite+"分");
        }
    }else{
        $(this).html("距开标还剩"+day+"天"+hour+"小时");
    }
}else{
   $(this).hide(); 
}
});
setTimeout("updateEndTime()",1000);
}
//首页和投资首页按钮倒计时
//投资详情按钮倒计时
//updateEndTime1();
//倒计时函数
function updateEndTime1()
{
var date = new Date();
var time = date.getTime();


$(".settime1").each(function(i){


var endDate =this.getAttribute("endTime"); //结束时间字符串
//转换为时间日期类型
var endDate1 = eval('new Date(' + endDate.replace(/\d+(?=-[^-]+$)/, function (a) { return parseInt(a, 10) - 1; }).match(/\d+/g) +')');


var endTime = endDate1.getTime(); //结束时间毫秒数


var lag = (endTime - time) / 1000; //当前时间和结束时间之间的秒数
if(lag > 0)
{
var second = Math.floor(lag % 60); 
var minite = Math.floor((lag / 60) % 60);
var hour = Math.floor((lag / 3600) % 24);
var day = Math.floor((lag / 3600) / 24);
        $(this).html("距开标还剩"+day+"天"+hour+"小时"+minite+"分"+second+"秒");
}else{
   $(this).hide(); 
}
});
setTimeout("updateEndTime1()",1000);
}
//投资详情按钮倒计时
//投资详情页倒计时
function djs_5 () {
        // body...
        var e=$(".djs_5");
        var endtime=e.find('.endtime').text();
        function js_djs (e,endtime) {
            var time = Date.parse(new Date())/1000;
            var jxsj =endtime-time;
            var s = jxsj%60;
            var m = parseInt(jxsj/60)%60;
            var h = parseInt(jxsj/60/60)%60;
            var d = parseInt(jxsj/60/60/24)%24;
            if(s<10){
                s="0"+s;
            }
            if(m<10){
                m="0"+m;
            }
            if(h<10){
                h="0"+h;
            }
            if(d<10){
                d="0"+d;
            }
            e.find('.s').text(s);
            e.find('.m').text(m);
            e.find('.h').text(h);
            e.find('.d').text(d);
        }
        function run () {
            js_djs(e,endtime);
        }
        run();
        setTimeout("djs_5()",1000);
}
//投资详情页倒计时
//公告跑马灯
function gg_pad() {
    // body...
    var ggh=$(".home_gg_after_banner_con").height();
    if(ggh>28){
        run();
    }
    function run() {
                var top=$(".home_gg_after_banner_con").position().top;
                if(top+ggh==28){
                    top=0;
                    $(".home_gg_after_banner_con").css("top","0"); 
                }
                $(".home_gg_after_banner_con").animate({
                    top: (top-28)+'px'
                }, 1000);
                setTimeout(function() {
                    run()
                },3000)
            }   
}
//公告跑马灯
function gg_pad_1() {
    // body...
    var ggh=$(".invest_home_gg_con").height();
    if(ggh>18){
        run();
    }
    function run() {
                var top=$(".invest_home_gg_con").position().top;
                if(top+ggh==18){
                    top=0;
                    $(".invest_home_gg_con").css("top","0"); 
                }
                $(".invest_home_gg_con").animate({
                    top: (top-18)+'px'
                }, 1000);
                setTimeout(function() {
                    run()
                },3000)
            }   
}

//公告跑马灯
//投资详情页的input框验证 
function tzxx_yz_input (e) {
    var a=$(".hidden").find($(".syktje")).text();
    var ztsy=e*a;
        ztsy=parseInt(ztsy*100)/100;
    $(".yjsy").find($("font")).text(ztsy);
    $(".inpandbut").find($("input")).keyup(function  () {
        var inpval=$(".inpandbut").find($("input")).val();
        var sy=inpval*e;
            sy=parseInt(sy*100)/100;
        $(".yjsy").find($("font")).text(sy);
        if (inpval%100==0){
            $(".inpandbut").siblings('.tip').empty();
        }else{
            $(".inpandbut").siblings('.tip').text("请输入100的整数倍金额");
        }
    })
    $(".inpandbut").find($("input")).blur(function () {
        var inpval=$(".inpandbut").find($("input")).val();
        var sy=inpval*e;
        if(inpval==0){
            $(".yjsy").find($("font")).text(ztsy);
        }
    })
};
//投资详情页的input框验证 
//投资详情页的button框验证
function tzxx_yz_but () {
    i=0;
    var target1;
    $("button").click(function (e) {
        if(i==0){
            target1=e.target;
            $(this).parent("div").append("<div class='but_pop_tip'><p>您还没有以投资者身份登录</p><p><a href='home.html'>登录</a><a href=''>加入聚雪球</a></p></div>");
            i=1;
        }  
    });
    $(document).click(function (e) {
        if(i==1){
            var target = e.target;
            if (target1 !== target) {
                $(".but_pop_tip").remove();
                i=0;
            }
        }
    });
}
//投资详情页的button框验证
//个人信息
//弹出层
function pop (e,a,c) {
    // e触发
    // a弹出
    // c关闭
    // w完成关闭
    e.click(function () {
        $(".black_bg").fadeIn();
        a.fadeIn();
    })
    c.click(function () {
        $(".black_bg").fadeOut();
        a.fadeOut();
    })
}
//弹出层
//弹出层红包
function pop_hb (e,a,c,d) {
    // e触发
    // a弹出
    // c关闭
    // w完成关闭
    e.click(function () {
            a.fadeIn();
    })
    c.click(function () {
        a.fadeOut();
    })
    d.click(function () {
        a.fadeOut();
    })
    $('.hbtc').find('.bj').click(function () {
        a.fadeOut();
    })
}
//弹出层
//弹出层再次弹出
function pop_sub (e,a,c) {
    // e触发
    // a弹出
    // c关闭
    // w完成关闭
    e.click(function () {
        e.parents('.user_data_pop').fadeOut('normal',function () {
            $(".black_bg").fadeIn();
            a.fadeIn();
        }); 
    })
    c.click(function () {
        $(".black_bg").fadeOut();
        a.fadeOut();
    })
}
//弹出层再次弹出
//验证之后弹出层再次弹出
function pop_sub_yzh (e,a,c) {
        e.fadeOut('normal',function () {
            $(".black_bg").fadeIn();
            a.fadeIn();
        }); 
		c.click(function(){
			$(".black_bg").fadeOut();
			a.fadeOut();
		})
}
//验证之后弹出层再次弹出
//短信验证倒计时
function dxdjs(e){
    var wait=60;
    function time(o) {
        if (wait == 0) {
            o.removeAttr("disabled");                                       
            o.val("获取验证码");
            wait = 60;
        } else {
            o.attr("disabled","true");
            o.val("" + wait + "秒后再次发送");
            wait--;
            setTimeout(function() {
                time(o)
            },1000)
        }
    }
    time(e);
}
//短信验证倒计时
//银行卡
//银行卡选择
function yhkxz_tab (a,e) {
    // a为选择的外框 e为结果的hidden input
    a.find('.yhsection').click(function () {
        $(this).siblings().removeClass('active');
        $(this).addClass('active');
        i=$(this).index();
        e.val(i);
    })
}
//银行卡选择
//银行卡
//首页进度条
    jindutiao();
    //倒计时函数
    function jindutiao()
    {
        $(".sy_jdt").each(function(i){
            var jdt =this.getAttribute("jdt"); //目标进度条
            var pos =this.getAttribute("pos"); //目标位置
            $(this).find('i').css("width","0"); 
            $(this).find('i').animate({
                width: jdt+'%'
            }, 500);
            $(this).find('.font').css("left","0"); 
            $(this).find('.font').animate({
                left: pos+'%'
            }, 500); 
        });
    }
//首页进度条
//个人信息
//测试JS是否加载成功
function cs_js (e) {
    // nav_pop
    e.click(function() {
        alert("加载sys成功");
    });
};

/**
 * jquery ajax 提交的按钮设置
 * 提交按钮 加class ajax-submit-button
 * 提示信息 data-loadMsg =''
 * event.fromElement event.toElement
 * 不传则开启loading 和 触发按钮的文本显示和禁用
 * flag = 1 只开启 按钮禁用和文本提示
 * flag = 2 只开启loading层
 * 默认 提示文本  提交中...  可在标签内 data-load-msg 属性设置文本
 * @param flag
 * @param bg_flag
 * @param end_delay
 */
var ajax_loading = function(){
    this.src_tag_obj = false;
    this.ajax_class_flag = 'ajax-submit-button';
};
ajax_loading.prototype = {
    'init':function(flag,bg_flag,end_delay){
        var ajax_class_flag = this.ajax_class_flag;     //ajax 提交按钮的 class标识
        var ajax_submit_button_text = '';               //按钮的最初的文本或html
        var ajax_submit_button_obj = '';                //按钮obj
        var ajax_submit_button_load_msg = '提交中...';  //默认 ajax处理中 按钮显示的文本
        var ajax_submit_button_bg_color = '';           //背景
        var ajax_submit_button_bg_flag = bg_flag | false;//背景变化启用
        var ajax_submit_button_end_delay = end_delay | 0; //提交按钮回复延时

        /**
         * ajax开始的处理
         */
        var ajax_start_deal = function(){
            //取信息
            if($(ajax_submit_button_obj).data('loadingMsg') != undefined)ajax_submit_button_load_msg = $(ajax_submit_button_obj).data('loadingMsg');

            switch (ajax_submit_button_obj.tagName) {
                case 'INPUT':
                    if (!ajax_submit_button_text)ajax_submit_button_text = $(ajax_submit_button_obj).val();
                    $(ajax_submit_button_obj).removeAttr('disabled').attr('disabled', true).val(ajax_submit_button_load_msg);
                    break;
                case 'BUTTON':
                    if (!ajax_submit_button_text)ajax_submit_button_text = $(ajax_submit_button_obj).html();
                    $(ajax_submit_button_obj).removeAttr('disabled').attr('disabled', true).html(ajax_submit_button_load_msg);
                    break;
                default:
                    if (!ajax_submit_button_text)ajax_submit_button_text = $(ajax_submit_button_obj).html();
                    $(ajax_submit_button_obj).removeAttr('disabled').attr('disabled', true).html(ajax_submit_button_load_msg);
            }
            //处理按钮背景变化
            if(ajax_submit_button_bg_flag){
                ajax_submit_button_bg_color = $(ajax_submit_button_obj).css('background-color');
                $(ajax_submit_button_obj).css('background-color','gainsboro');
            }
        };
        /**
         * ajax结束的处理
         */
        var ajax_end_deal = function(){
            switch (ajax_submit_button_obj.tagName) {
                case 'INPUT':
                    $(ajax_submit_button_obj).removeAttr('disabled').val(ajax_submit_button_text);
                    break;
                case 'BUTTON':
                    $(ajax_submit_button_obj).removeAttr('disabled').html(ajax_submit_button_text);
                    break;
                default:
                    $(ajax_submit_button_obj).removeAttr('disabled').html(ajax_submit_button_text);
            }

            //恢复按钮背景变化
            if(ajax_submit_button_bg_flag)$(ajax_submit_button_obj).css('background-color',ajax_submit_button_bg_color);
        };
        var that = this;
        $(document).ajaxStart(function(){
            if(this.src_tag_obj != false){
                ajax_submit_button_obj = that.src_tag_obj;
            }else{
                var theEvent = window.event || arguments.callee.caller.arguments[0];
                if(theEvent && (theEvent.srcElement || theEvent.target))ajax_submit_button_obj = theEvent.srcElement?theEvent.srcElement:theEvent.target;
            }

            var ajax_button = false;
            if(ajax_submit_button_obj && ajax_submit_button_obj.tagName){
                var class_str = $(ajax_submit_button_obj).attr('class');
                //标签的class包含标识
                if(class_str && class_str.indexOf(ajax_class_flag) != -1) {
                    ajax_button = true;
                    if($(ajax_submit_button_obj).data('loadingFlag') != undefined)flag = parseInt($(ajax_submit_button_obj).data('loadingFlag'));
                }
            }
            if(flag != 2 && ajax_button){
                ajax_start_deal();
            }
            if(flag != 1){
                layer.load(2);
            }
        }).ajaxStop(function(){
            if(flag != 2 && ajax_submit_button_obj && ajax_submit_button_obj.tagName) {
                var class_str = $(ajax_submit_button_obj).attr('class');  //event.fromElement event.toElement
                if(class_str && class_str.indexOf(ajax_class_flag) != -1) { //标签的class包含标识
                    if(ajax_submit_button_end_delay > 0){
                        var endt = setTimeout(function(){
                            clearTimeout(endt);
                            ajax_end_deal();
                        },ajax_submit_button_end_delay*1000);
                    }else{
                        ajax_end_deal();
                    }
                }
            }
            if(flag != 1)var t= setTimeout(function(){
                layer.closeAll('loading');
                clearTimeout(t);
            },1000);
        });
    },
    'set_src':function(obj){
        if(obj){
            if(typeof obj == "string"){
                this.src_tag_obj = $("#"+obj).get(0)
            }else{
                this.src_tag_obj = obj.get(0);
            }
        }
    },
    'get_class_flag':function(){
        return this.ajax_class_flag;
    },
    'set_class_flag':function(class_name){
        this.ajax_class_flag = class_name;
    }
};

/**
 * 循环html数据
 * @param url ajax链接
 * @param option 参数
 * @param field_func 字段处理函数
 * @param id html容器id 或者该html的obj
 * @param flag 覆盖还是最佳html的标识 true则覆盖 false为追加
 * @param func 单条循环补充处理函数
 * @param callback 全部完成的补充处理函数 变量是否还有数据
 * @param hide_nodata_msg 隐藏没有信息的提示 true为隐藏 默认undefind 不隐藏
 * @returns {string}
 */
var each_html = function(id,url,option,field_func,flag,func,callback,hide_nodata_msg){
    if( ! url)return'';
    $.post(url,option,function(rs){
        var data = rs.data;
        var list_html = '';
        if(typeof id == "object"){
            list_html = id;
            id = $(list_html).attr('id');
        }else{
            list_html = $('#'+id).clone();
        }
        var htmls = '';//所有循环的html代码
        var no_data = false;
        if(data != ''){
            $(data).each(function(i,v){
                var html = $(list_html).clone();
                v['key'] = i+1;
                for(var key in v){
                    var val = v[key];

                    if(typeof field_func == "object"){
                        var _func=field_func[key];
                        if(_func)val=_func(val);//处理数据
                    }

                    //循环查询 带data键名为class的标签
                    var obj=$(html).find("."+key);
                    if(obj.length > 0){
                        if(obj.length >= 2){
                            $(obj).each(function(i1,v1){
                                switch ($(v1).get(0).tagName){
                                    case 'IMG':
                                        $(v1).attr('src',val);
                                        break;
                                    case 'A':
                                        if($(v1).attr('tel') == 'tel'){
                                            $(v1).attr('href','tel:'+val);
                                        }else{
                                            $(v1).attr('href',val);
                                        }
                                        break;
                                    default :
                                        $(v1).html(val);
                                }
                            });
                        }else{
                            switch (obj.get(0).tagName){
                                case 'IMG':
                                    obj.attr('src',val);
                                    break;
                                case 'A':
                                    if(obj.attr('tel') == 'tel'){
                                        obj.attr('href','tel:'+val);
                                    }else{
                                        obj.attr('href',val);
                                    }
                                    break;
                                default :
                                    obj.html(val);
                            }
                        }
                    }
                }
                if(typeof func == "function") func(html,v);
                htmls+=$(html).html();
            });
        }else{
            $(list_html).find(":first").html('暂无相关信息').css('text-align','center');
            if( ! hide_nodata_msg)htmls = $(list_html).html();
            no_data = true;
        }

        if(flag){
            $("#"+id).html(htmls);
        }else{
            $("#"+id).append(htmls);
        }
        if(typeof callback == "function") callback(no_data);
    },'json');
};

/**
 * 金额处理函数
 * @param price
 * @param format
 * @param flag
 * @returns {*}
 */
var price_format=function(price,format,flag){
    if(isNaN(price))price=0;
    switch (format){
        case 1:
            price=parseInt(price);
            break;
        case 2:
            var n=2;
            var s=price;
            n = n > 0 && n <= 20 ? n : 2;
            s = parseFloat((s + "").replace(/[^\d\.-]/g, "")).toFixed(n) + "";
            var l = s.split(".")[0].split("").reverse(), r = s.split(".")[1];
            var t = "";
            for (i = 0; i < l.length; i++) {
                t += l[i] + ((i + 1) % 3 == 0 && (i + 1) != l.length ? "," : "");
            }
            price = t.split("").reverse().join("") + "." + r;
            break;
        case 3:
            price = (price/10000);
            price=price.toFixed(2);
            break;
        case 4:
            break;
        default :
            price = Math.round(price,2);
    }
    return flag?'¥'+price:price;
};

/**
 * 小数处理函数
 * @param rate
 * @returns {*}
 */
var  rate_format=function(rate){
    if(rate > 0){
        rate= parseFloat(rate);
    }
    return rate;
};

/**
 * 时间格式化
 * @param unixtime
 * @param format
 * @returns {*}
 */
var  unixtime_style = function(unixtime,format){
    if(!unixtime)return '无';
    var timestr = new Date(parseInt(unixtime) * 1000);
    if(!format)return timestr.getFullYear()+"-"+timestr.getMonth()+1+"-"+timestr.getDate()+" "+timestr.getHours()+":"+timestr.getMinutes()+":"+timestr.getSeconds();
    format=format.replace("Y",timestr.getFullYear());
    format=format.replace("y",timestr.getYear());
    format=format.replace("m",timestr.getMonth()+1);
    format=format.replace("d",timestr.getDate());
    format=format.replace("w",timestr.getDay()||7);
    format=format.replace("H",timestr.getHours());
    format=format.replace("h",timestr.getHours());
    format=format.replace("i",timestr.getMinutes()<10?'0'+timestr.getMinutes():timestr.getMinutes());
    format=format.replace("s",timestr.getSeconds()<10?'0'+timestr.getSeconds():timestr.getSeconds());
    if(timestr.getHours() > 12){
        format=format.replace("am",'pm');
        format=format.replace("上午",'下午');
    }
    return format;
};

/**
 * 弹窗提示
 * @param msg
 * @param flag
 * @param url
 */
var wsb_alert = function(msg, flag, url){
    if($('.but_pop_tip_1').length == 0){
        $('body').append('<div class="but_pop_tip_1"><p>'+msg+'</p></div>');
    }else{
        $('.but_pop_tip_1>p').html(msg);
    }
    if(isNaN(flag))flag=2;

    $('.but_pop_tip_1').fadeIn(100,function(){
        var alert_t = setTimeout(function(){
            $('.but_pop_tip_1').fadeOut(500,function(){
                clearTimeout(alert_t);
                if(url)window.location.href=url;
            });
        },flag*1000);
    });     
};

/**
 * 计算器 计算项目投资的利息
 * @param amount 总额
 * @param rate 年利率
 * @param months 月数
 * @param mode 类型
 */
var calculator = function (amount, rate, months, mode) {
    amount = parseFloat(amount)| 0;
    rate = parseFloat(rate)| 0;
    months = parseFloat(months);
    mode = parseInt(mode);
    var interest = 0;
    if (amount && rate && months && mode) {
        switch (mode) {
            case 1://先息后本 *100再/100是去整保留两位小数
                interest = Math.round(amount * (rate / 100 / 360) * (months * 30) * 100) / 100;
                break;
            case 2://等额本息
                rate = rate / 100 / 12;//月利率
                var m_amount = amount * rate * Math.pow((1 + rate), months) / (Math.pow((1 + rate), months) - 1);//每月金额
                interest = Math.round((m_amount * months - amount) * 100) / 100;
                break;
            case 3://一次性
                interest = Math.round(amount * (rate / 100 / 360) * (months * 30) * 100) / 100;
                break;
            case 4://等额本金
                interest = Math.round(((months + 1) * amount * ((rate / 100) / 12) / 2) * 100) / 100
                break;
            default :
        }
    }
    return (interest);
};

