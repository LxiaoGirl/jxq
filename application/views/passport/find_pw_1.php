<!DOCTYPE html>
<html>
<head>
    <title>聚雪球_网加金服_P2P理财首选互联网金融P2P网贷平台_100元即可投资!</title>
	<?php $this->load->view('common/head_file'); ?>
</head>
<body>
    <!--header-->
    <?php $this->load->view('common/head'); ?>
    <!--header-->
<div class="row">
    <div class="find_pw">
        <h1>找回密码</h1>
        <p class="sjhts">短信验证码发送至您的手机<?php echo secret($this->session->userdata('forget_mobile'),4) ?>上，请在输入框内填写您的验证码，若未收到请在倒计时后点击重新发送按钮。<span class="voice-tips"></span></p>
        <form id="reg_2" action="" method="" accept-charset="utf-8" onsubmit="return false;">
        <div class="inp_pit">
            <div class="inp">
                <input class="js_sjyzm" type="text" name="tpyzm" value="" placeholder="输入验证码" maxlength="6" />
                <input class="fsyzm ls" type="button" value="发送验证码"
                       data-wait-time="<?php echo item("sms_space_time")?item("sms_space_time"):60; ?>"
                       data-last-time="<?php echo profile("sms_last_send_time")?profile("sms_last_send_time"):0; ?>"/>
            </div>
            <div class="pit"></div>
        </div>
        <div class="but">
            <button  type="submit" id="reg_xyb" class="ajax-submit-button ls" data-load-msg="验证中...">下一步</button>
        </div>
        </form>
    </div>
</div>
<!--footer-->
    <?php $this->load->view('common/footer'); ?>
<!--footer-->
<script type="text/javascript">
seajs.use(['jquery','sys','jqform','validator','wsb_sys'],function(){
    //INPUT框变色
        $('.inp').find('input').focus(function(){
            $(this).siblings('img.ls').show();
            $(this).addClass('hav');
        });
        $('.inp').find('input').blur(function(){
            if($.trim($(this).val())==''){
                $(this).siblings('img.ls').hide();
                $(this).removeClass('hav');
            }
        });
        //INPUT框变色
    $(function(){
	    //发送短信 处理
	    $('.fsyzm').send_sms('sms','<?php echo profile("forget_mobile");?>','forget',function(rs){
            $('.voice-tips').html('.短信接不到?<a href="javascript:void(0);" style="text-decoration: underline;" id="voice">试试语音验证码</a>');
            $("#voice").send_sms('voice','<?php echo profile("forget_mobile");?>','forget');
        });
	    //$('.fsyzm').click();//直接触发
    });
    var pit_3=0;
    $('.but').find('#reg_xyb').click(function () {
        if(pit_3 == 0){
            $('.js_sjyzm').focus();
            return false;
        }
        if(pit_3==1){
            $.ajax({
	            type: 'POST',
                async: false,
	            url: '<?php echo site_url('login/forget_s1'); ?>',
	            data: {'authcode':$('.js_sjyzm').val()},
	            dataType: 'json',
                error:function(){
                    wsb_alert('服务器繁忙请稍后重试!',2);
                },
	            success: function (result) {
		            if(result.status == '10000') {
                        window.location.href="<?php echo site_url('login/forget_s2'); ?>";
		            }else{
                        wsb_alert(result.msg,2);
		            }
	            }
            });
        }
    });
    $('#reg_2').validate({
        '.js_sjyzm': {
            filtrate: 'required',
            callback: function (index) {
                var tip = this.parent().parent().find('.pit').eq(0),
                    text = '';
                if (index === 0) {
                    text = '<i class="icon-tip-no"></i>请输入验证码';
                } else {
                    //验证验证码
                    $.ajax({
                        type: 'POST',
                        async: false,
                        url: '<?php echo site_url('send/validate_authcode'); ?>',
                        data: {'mobile':'<?php echo profile("forget_mobile"); ?>','authcode':$('.js_sjyzm').val(),'action':'forget'},
                        dataType: 'json',
                        success: function (result) {
                            if (result.status != '10000') {
                                text = '<i class="icon-tip-no"></i>'+result.msg;
                                pit_3=0;
                            } else {
                                text = '<i class="icon-tip-yes"></i>';
                                pit_3=1;
                            }
                            tip.html(text);
                        }
                    });
                }
                tip.html(text);
            }
        }
    });
});
</script>

</body>
</html>