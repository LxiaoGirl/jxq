<script src="/assets/js/app/jquery-1.11.3.min.js"></script>
<script src="/assets/js/app/jquery.mobile.custom.min.js"></script>
<script src="/assets/css/app/bootstrap33/js/bootstrap.min.js"></script>
<script src="/assets/js/app/radialIndicator.min.js"></script>
<script src="/assets/js/app/flexslide/jquery.flexslider.js"></script>
<script src="/assets/js/app/custom.js"></script>
<script src="/assets/js/app/listview-1.1.min.js"></script>
<script src="/assets/js/app/layer/layer.js"></script>
<script>
    /**
     * 验证是否登录 再跳转
     * @param url 登陆后的跳转链接
     * @param flag true 时 验证登陆 再验证实名认证
     */
    var check_to_login = function(url,flag){
        var isLogin = '<?php echo profile('uid'); ?>';
        if( ! isLogin){
            window.location.href='/index.php/mobiles/home/login?return=<?php echo urlencode(current_url());?>';
//            var u = navigator.userAgent;
//            if (u.indexOf('Android') > -1 || u.indexOf('Linux') > -1) {//安卓手机
//                window.Jxq.login();
//                return;
//            }
//            if (u.indexOf('iPhone') > -1) {//苹果手机
//                window.location.href='objc://turnToSign';
//                return;
//            }
//            if (u.indexOf('Windows Phone') > -1) {//winphone手机
//                    alert("winphone手机");
//            }
//            window.location.reload();
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
     * 主页的验证登陆 打开方式不同
     * @param url
     * @param flag
     */
    var index_check_to_login = function(url,flag){
        var isLogin = '<?php echo profile('uid'); ?>';
        if( ! isLogin){
            window.location.href='/index.php/mobiles/home/login';
//            var u = navigator.userAgent;
//            if (u.indexOf('Android') > -1 || u.indexOf('Linux') > -1) {//安卓手机
//                window.Jxq.login();
//                return;
//            }
//            if (u.indexOf('iPhone') > -1) {//苹果手机
//                window.location.href='objc://turnToSign';
//                return;
//            }
//            if (u.indexOf('Windows Phone') > -1) {//winphone手机
//                    alert("winphone手机");
//            }
//            window.location.reload();
        }else{
            var isRealName = '<?php echo profile('clientkind'); ?>';
            var u = navigator.userAgent;
            if(typeof flag != "undefined" && flag==true && (isRealName != 1 && isRealName != 2)){
//                if (u.indexOf('Android') > -1 || u.indexOf('Linux') > -1) {//安卓手机
//                    to_app_view('<?php //echo site_url('mobiles/home/real_name'); ?>//');
//                }else{
                    window.location.href='<?php echo site_url('mobiles/home/real_name'); ?>';
//                }
            }else{
                if(typeof url != "undefined" &&  url != ''){
//                    if (u.indexOf('Android') > -1 || u.indexOf('Linux') > -1) {//安卓手机
//                        to_app_view(url);
//                    }else{
                        window.location.href=url;
//                    }
                }
            }
        }
    };

    /**
     * 返回主页
     */
    var check_to_index = function(){
//        var u = navigator.userAgent;
//        if (u.indexOf('Android') > -1 || u.indexOf('Linux') > -1) {//安卓手机
//            window.Jxq.goBack();
//        }else{
            window.location.href='<?php echo site_url('mobiles/home/index'); ?>';
//        }
    };

    /**
     * 安卓 打开新界面
     * @param url
     */
    var to_app_view = function(url){
        if(url){
            window.location.href=url;
//            var u = navigator.userAgent;
//            if (u.indexOf('Android') > -1 || u.indexOf('Linux') > -1) {//安卓手机
//                window.Jxq.toWebView(url);
//            }else{
//                window.location.href=url;
//            }
        }
    }

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
                    url:'/index.php/send/index?captcha=<?php echo $this->session->userdata('captcha'); ?>',
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
    }
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
        $(".right_head").click(function  () {
            $(".right_nav").animate({
                right:'+0%', width:'+100%', opacity: 'show'
            }, 300);
            $("body").animate({
                right:'+70%', opacity: 'show'
            }, 300);
        });
        $(".le_harf").click(function  () {
            $(".right_nav").animate({
                right:'+0%', width:'+0%', opacity: 'show'
            }, 300);
            $("body").animate({
                right:'+0%', opacity: 'show'
            }, 300);
        });
        ajax_loading();
    });

    /**
     * jquery ajax 提交的按钮设置  event.fromElement event.toElement
     * @param flag
     *              不传则开启loading 和 触发按钮的文本显示和禁用
     *              flag = 1 只开启 按钮禁用和文本提示
     *              flag = 2 只开启loading层
     *
     *              默认 提示文本  提交中...  可在标签内 data-loadMsg 属性设置文本
     */
    var ajax_loading = function(flag){
        var ajax_class_flag = 'ajax-submit-button';  //ajax 提交按钮的 class标识
        var ajax_submit_button_text = '';               //按钮的最初的文本或html
        var ajax_submit_button_obj = '';                //按钮obj
        var ajax_submit_button_load_msg = '提交中...'; //默认 ajax处理中 显示的文本

        switch (flag){
            case 1: //只开启 按钮禁用效果
                $(document).ajaxStart(function(){
                    if(event && event.srcElement)ajax_submit_button_obj = event.srcElement;
                    if(ajax_submit_button_obj && ajax_submit_button_obj.tagName){
                        if($(ajax_submit_button_obj).attr('data-loadMsg') != undefined)ajax_submit_button_load_msg = $(ajax_submit_button_obj).attr('data-loadMsg');

                        var class_str = $(ajax_submit_button_obj).attr('class');  //event.fromElement event.toElement
                        if(class_str && class_str.indexOf(ajax_class_flag) != -1) { //标签的class包含标识

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
                        }
                    }
                }).ajaxStop(function(){
                    if(ajax_submit_button_obj && ajax_submit_button_obj.tagName) {

                        var class_str = $(ajax_submit_button_obj).attr('class');  //event.fromElement event.toElement
                        if(class_str && class_str.indexOf(ajax_class_flag) != -1) { //标签的class包含标识

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
                        }
                    }
                });
                break;
            case 2://只开启loading层
                $(document).ajaxStart(function(){
                    layer.load(2);
                }).ajaxStop(function(){
                    var t= setTimeout(function(){
                        layer.closeAll('loading');
                        clearTimeout(t);
                    },1000);
                });
                break;
            default : //默认全局开启
                $(document).ajaxStart(function(){
                    if(event && event.srcElement)ajax_submit_button_obj = event.srcElement;
                    if(ajax_submit_button_obj && ajax_submit_button_obj.tagName){
                        if($(ajax_submit_button_obj).attr('data-loadMsg') != undefined)ajax_submit_button_load_msg = $(ajax_submit_button_obj).attr('data-loadMsg');

                        var class_str = $(ajax_submit_button_obj).attr('class');  //event.fromElement event.toElement
                        if(class_str && class_str.indexOf(ajax_class_flag) != -1) { //标签的class包含标识

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
                        }
                    }
                    layer.load(2);
                }).ajaxStop(function(){
                    if(ajax_submit_button_obj && ajax_submit_button_obj.tagName) {

                        var class_str = $(ajax_submit_button_obj).attr('class');  //event.fromElement event.toElement
                        if(class_str && class_str.indexOf(ajax_class_flag) != -1) { //标签的class包含标识

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
                        }
                    }

                    var t= setTimeout(function(){
                        layer.closeAll('loading');
                        clearTimeout(t);
                    },1000);
                });
        }
    };
</script>