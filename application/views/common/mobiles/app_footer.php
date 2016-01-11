<script src="/assets/js/app/jquery-1.11.3.min.js"></script>
<script src="/assets/js/app/jquery.mobile.custom.min.js"></script>
<script src="/assets/css/app/bootstrap33/js/bootstrap.min.js"></script>
<script src="/assets/js/app/radialIndicator.min.js"></script>
<script src="/assets/js/app/flexslide/jquery.flexslider.js"></script>
<script src="/assets/js/app/custom.js"></script>
<script src="/assets/js/app/listview-1.1.min.js"></script>
<script src="/assets/js/app/layer/layer.js"></script>
<script src="/assets/js/app/jquery.list_data.1.1.js"></script>
<script>
    /**
     * 验证是否登录 再跳转
     * @param url 登陆后的跳转链接
     * @param flag true 时 验证登陆 再验证实名认证
     */
    var check_to_login = function(url,flag){
        var isLogin = '<?php echo profile('uid'); ?>';
        if( ! isLogin){
            window.location.href='/index.php/mobiles/home/login?redirect_url='+encodeURI(window.location.href);
        }else{
            var isRealName = '<?php echo profile('clientkind'); ?>';
            if(typeof flag != "undefined" && flag==true && (isRealName != 1 && isRealName != 2)){
                window.location.href='/index.php/mobiles/home/real_name';
            }else{
                if(typeof url != "undefined" &&  url != '')window.location.href=url;
            }
        }
    };

    /**
     * 返回主页
     */
    var check_to_index = function(){
        window.location.href='<?php echo site_url('mobiles/home/index'); ?>';
    };

    //发送短息 和语音的 两个按钮倒计时函数
    var wait_sms = 60;
    function time(o) {
        if (wait_sms == 0) {
            $(o).removeClass('btn-default').addClass('btn-success');
            $(o).prop("disabled",false);
            $(o).text("免费获取验证码");
            wait_sms = 60;
        } else {
            $(o).removeClass('btn-success').addClass('btn-default');
            $(o).prop("disabled",true);
            $(o).text("重新发送(" + wait_sms + ")");
            wait_sms--;
            setTimeout(function() {time(o)},1000)
        }
    }
    var wait_voice = 60;
    function time1(o) {
        if (wait_voice == 0) {
            $(o).removeClass('btn-default').addClass('btn-info');
            $(o).prop("disabled",false);
            $(o).text("收听语音验证码");
            wait_voice = 60;
        } else {
            $(o).removeClass('btn-info').addClass('btn-default');
            $(o).prop("disabled",true);
            $(o).text("重新收听(" + wait_voice + ")");
            wait_voice--;
            setTimeout(function() {time1(o)},1000)
        }
    }

    /**
     * 绑定发送验证码函数  id：send-sms  send-voice
     * attr send-type:sms  voice
     * @param mobile
     * @param act
     */
    var send_authcode = function(mobile,act){
        var phone = /^1[3456789](\d){9}$/;
        $('.send-authcode').on('tap',function(){
            var type = $(this).attr('send-type');
            var obj = this;
            if(phone.test(mobile)){
                $.ajax({
                    url:'/index.php/send/index',
                    dataType:'json',
                    type: 'POST',
                    data:{'action':act,'mobile':mobile,'type':type},
                    success:function(resut){
                        if(resut.status == '10000'){
                            if(type == 'sms'){
                                my_alert(resut.msg);
                                time(obj);
                            }else{
                                my_alert('稍后聚雪球将通过电话4007-918-333拨打' +
                                '您的手机'+mobile+'告知验证码');
                                time1(obj);
                            }
                        }else{
                            my_alert(resut.msg);
                        }
                    }
                })
            }else{
                my_alert('手机格式不正确！')
            }
        });
    };
    var borrow_status = function(s){
        var str='';
        switch (s){
            case '1':
                str = '已取消';
                break;
            case '2':
                str = '募集中';
                break;
            case '3':
                str = '融资完成';
                break;
            case '4':
                str = '还款中';
                break;
            case '5':
                str = '流标';
                break;
            case '6':
                str = '逾期';
                break;
            case '7':
                str = '交易结束';
                break;
            default:
                str = '待审核';
                break;
        }
        return str;
    };
    //公共 头js
    $(function($) {
        $(".right_nav").height($(window).height());
        $(".right_head").click(function(){
            $(".right_nav").animate({right:'+0%', width:'+100%', opacity: 'show'}, 300);
            $("body").animate({right:'+70%', opacity: 'show'}, 300);
        });
        $(".le_harf").click(function(){
            $(".right_nav").animate({right:'+0%', width:'+0%', opacity: 'show'}, 300);
            $("body").animate({right:'+0%', opacity: 'show'}, 300);
        });
        ajax_loading_style(3,1);
    });
    var g_sms_apace_time = parseInt('<?php echo item('sms_space_time')?item('sms_space_time'):60; ?>');
    var g_voice_last_time = parseInt('<?php echo profile('voice_last_send_time')?profile('voice_last_send_time'):0; ?>');
    var g_sms_last_time = parseInt('<?php echo profile('sms_last_send_time')?profile('sms_last_send_time'):0; ?>');
</script>