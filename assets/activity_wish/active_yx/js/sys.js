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

    $('.sys_alert').fadeIn(100,function(){
        var alert_t = setTimeout(function(){
            $('.sys_alert').fadeOut(500,function(){
                clearTimeout(alert_t);
                if(url)window.location.href=url;
            });
        },flag*1000);
    });     
};
/**
 *AJAX加载中
 */
var _ajax_lo = function(){
    //$('body').append('<div class="ajx"><div class="ajx_nr"><div class="ajx_logo"><div class="ajx_tu"><div class="ajx_quan"></div><div class="four_one"></div></div><div class="ajx_j">J</div></div><div class="ajx_wz">处理中...</div></div></div>')
    $('body').append('<div class="ajx"><div class="ajx_nr"><div class="ajx_logo"><div class="ajx_tu"><div class="ajx_quan"></div><div class="four_one"></div></div><div class="ajx_j">J</div></div><div class="ajx_wz">处理中...</div></div></div>')
};
/**
 *AJAX加载成功
 */
var _ajax_cg = function(){
    $('.ajx').remove();
};
/**
 *懒加载
 */
window.onload = function(){ 
    setTimeout(function(){$('.ajx_nr_1').hide(1);$('.lazy').hide(2);},500);
};
