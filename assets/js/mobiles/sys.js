/**
 * 弹窗提示
 * @param msg
 * @param flag
 * @param url
 */
var sys_alert = function(msg, flag, url){
    if($('.sys_alert').length == 0){
        $('body').append('<div class="sys_alert"><p>'+msg+'</p></div>');
    }else{
        $('.sys_alert>p').html(msg);
    }
    if(isNaN(flag))flag=2;
    $('.sys_alert').fadeIn(500,function(){
        var alert_t = setTimeout(function(){
            $('.sys_alert').fadeOut(500,function(){
                clearTimeout(alert_t);
                if(url)window.location.href=url;
            });
        },flag*1000);
    });     
};
/**
 *提示信息2
 */
var sys_tspop = function(msg, a1, url1,a2,url2){
    if($('.tspop').length == 0){
        $('body').append('<div class="tspop"><div class="tspop_close bj"></div><div class="tspopnr"><div class="tstop">提醒</div><div class="tscenter"></div><div class="tsbutt">/div></div></div>');
    };
    $('.tscenter').html(msg);
    $('.tsbutt').empty();
    if(a2){
        if(a1){
            if(url1){
                $('.tsbutt').append('<a class="half" href="'+url1+'">'+a1+'</a>');
            }else{
                $('.tsbutt').append('<a class="tspop_close half">'+a1+'</a>');
            }
        }
        if(a2){
            if(url2){
                $('.tsbutt').append('<a class="half" href="'+url2+'">'+a2+'</a>');
            }else{
                $('.tsbutt').append('<a class="tspop_close half">'+a2+'</a>');
            }
        }    
    }else{
        if(a1){
            if(url1){
                $('.tsbutt').append('<a href="'+url1+'">'+a1+'</a>');
            }else{
                $('.tsbutt').append('<a class="tspop_close">'+a1+'</a>');
            }
        }
    }
    
    $('.tspop').fadeIn();
    $('.tspop_close').click(function(){
        $('.tspop').fadeOut();
        setTimeout("$('.tspop').remove()",500) 
    })
};
/**
 * TAB
 * 
 * 
 * 
 */
var tab = function(e){
    var i;
    e.find('.tabbt').click(function(){
        i=$(this).index();
        $(this).siblings('.tabbt').removeClass('active');
        $(this).addClass('active');
        e.find('.tabcell').removeClass('active');
        e.find('.tabcell').eq(i).addClass('active');
    })
}
/**
 * 投资列表倒计时
 * 
 * 
 * 
 */
var djs = function(){
    var l= $('.djs').length;
    var run = function(){
        var i=0;
        for(i=0;i<l;i++){
            var e = $('.djs').eq(i);
            var html = "<span>开始时间：</span>剩 ";
            var end = e.attr("endtime");
            var nowtime = Date.parse(new Date())/1000;
            var cha = end-nowtime;
            var s = cha%60;
            var m = Math.floor(cha/60)%60;
            var h = Math.floor(cha/3600)%24;
            var d = Math.floor(cha/86400);
            if(d>0){ html = html+"<font>"+d+"</font> 天";}
            if(h>0){ html = html+" <font>"+h+"</font> 小时 ";}
            if(m>0){ html = html+"<font>"+m+"</font> 分 ";}
            if(s>0){ html = html+"<font>"+s+"</font> 秒";}
            e.html(html);
        }
    }
    setInterval(run,1000); 
}
/**
 *AJAX加载中
 */
var _ajax_lo = function(){
    //$('body').append('<div class="ajx"><div class="ajx_nr"><div class="ajx_logo"><div class="ajx_tu"><div class="ajx_quan"></div><div class="four_one"></div></div><div class="ajx_j">J</div></div><div class="ajx_wz">处理中...</div></div></div>')
    $('body').append('<div class="ajx"><div class="ajx_nr"><div class="ajx_logo"><div class="ajx_tu"><div class="ajx_quan"></div><div class="four_one"></div></div><div class="ajx_j">J</div></div><div class="ajx_wz">处理中...</div></div></div>')
}
/**
 *AJAX加载成功
 */
var _ajax_cg = function(){
    $('.ajx').remove();
}
/**
 *懒加载
 */
window.onload = function(){ 
    setTimeout(function(){$('.ajx_nr_1').hide(1);$('.lazy').hide(2);},500);
}
