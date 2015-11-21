<!DOCTYPE html>
<html>
<head>
    <title>聚雪球_网加金服_P2P理财首选互联网金融P2P网贷平台_100元即可投资!</title>
	<!--	加载头部样式文件-->
	<?php $this->load->view('common/head_file'); ?>
</head>
<body>
<!--header-->
<!--	加载头部文件-->
<?php $this->load->view('common/head'); ?>
<!--header-->
<div class="register">
    <div class="row">
        <div class="reg_body">
            <div class="pic"><img src="../../../../assets/images/bigpic/mian.jpg"></div>
            <div class="kuangjia">
                <div class="step_2">
                <form id="reg_2" action="" method="" accept-charset="utf-8" onsubmit="return false;">
                    <div class="title">用户注册</div>
                    <p class="sjhts">短信验证码发送至您的手机<?php echo secret(profile('register_mobile'),4); ?>上，请在输入框内填写您的验证码，若未收到请在倒计时后点击重新发送按钮。</p>
                    <div class="inp_pit">
                        <div class="inp">
                            <input class="js_sjyzm" type="text" name="tpyzm" value="" placeholder="输入验证码" maxlength="6" />
                            <input class="fsyzm" type="button" value="发送验证码" />
                        </div>
                        <div class="pit"></div>
                    </div>
                    <div class="inp_pit">
                        <div class="inp">
                            <img src="../../../../assets/images/passport/zc_suo.png">
                            <input class="reg_sj js_mm" type="password" name="sjh" value="" placeholder="请为您的账号设置一个密码" maxlength="20" />
                        </div>
                        <div class="pit"></div>
                    </div>
                    <div class="inp_pit">
                        <div class="inp">
                            <img src="../../../../assets/images/passport/zc_suo.png">
                            <input class="reg_sj js_cfmm" type="password" name="sjh" value="" placeholder="请重复输入上面的密码" maxlength="20" />
                        </div>
                        <div class="pit"></div>
                    </div>
                    <div class="but">
                        <button  type="submit" id="reg_wczc" class="ajax-submit-button" data-loadMsg="注册中...">完成注册</button>
                    </div>
                    <div class="yyzhdl">已有帐号，<a href="<?php echo site_url('login');?>">立即登录</a></div>
                </form>
                </div>
            </div>
        </div>
        <div class="bot_wz">
        国资参与 银行监管 ● 预期年化收益率13.2% ● 100%本息保障
        </div>
    </div>
</div>
<!--footer-->
<?php $this->load->view('common/footer'); ?>
<!--footer-->
<script type="text/javascript">
    seajs.use(['jquery','sys','jqform','validator'],function(){
	    //发送短信 处理
        $(function(){
	        $('.fsyzm').send_sms('sms','<?php echo profile("register_mobile");?>','register',<?php echo item("sms_space_time")?item("sms_space_time"):60; ?>,'<?php echo profile("sms_last_send_time")?time()-profile("sms_last_send_time"):''; ?>');
	        //$('.fsyzm').click();//直接触发
        });
        var pit_3=0,pit_4=0,pit_5=0;
        $('.but').find('#reg_wczc').click(function () {
            if(pit_3 == 0){
                $('.js_sjyzm').focus();
                return false;
            }
            if(pit_4 == 0){
                $('.js_mm').focus();
                return false;
            }
            if(pit_5 == 0){
                $('.js_cfmm').focus();
                return false;
            }
            if((pit_3+pit_4+pit_5)==3){
	            $.ajax({
		            type: 'POST',
                    async: false,
		            url: '<?php echo site_url('login/register_s1'); ?>',
		            data: {'password':$('.js_mm').val(),'authcode':$('.js_sjyzm').val()},
		            dataType: 'json',
                    error:function(){
                        wsb_alert('服务器繁忙请稍后重试!',2);
                    },
		            success: function (result) {
			            if(result.status == '10000') {
                            window.location.href="<?php echo site_url('login/register_s2'); ?>";
			            }else{
                            wsb_alert(result.msg);
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
	                    pit_3=0;
                    } else {
                        //验证验证码
                        $.ajax({
                            type: 'POST',
                            async: false,
                            url: '<?php echo site_url('send/validate_authcode'); ?>',
                            data: {'mobile':'<?php echo profile("register_mobile"); ?>','authcode':$('.js_sjyzm').val(),'action':'register'},
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
            },
            '.js_mm':  {
                filtrate: 'required min6 max20',
                degree: true,
                callback: function (index, other) {
                    var tip = this.parent().parent().find('.pit').eq(0),
                        text = '<i class="icon-tip-yes"></i>';
                    if (index === 0) {
                        text = '<i class="icon-tip-no"></i>请输入密码';
	                    pit_4=0;
                    } else if (index === 1 || index === 2) {
                        text = '<i class="icon-tip-no"></i>建议密码由6位及以上20位内数字、字母和特殊字符组成。';
	                    pit_4=0;
                    } else {
                        text = '安全程度：<div class="item z-sel">弱</div><div class="item ' + (other !== 0 ? 'z-sel' : '') + '">中</div><div class="item ' + (other === 1 ? 'z-sel' : '') + '">强</div>';
                        pit_4=1;
                    }
                    tip.html(text);
                }
            },
            '.js_cfmm': {
                filtrate: 'required min6',
                relevance: '.js_mm',
                callback: function (index, other) {
                    var tip = this.parent().parent().find('.pit').eq(0),
                        text = '<i class="icon-tip-yes"></i>';
                        pit_5=1;
                    if (index === 0) {
                        text = '<i class="icon-tip-no"></i>请确认密码';
                        pit_5=0;
                    } else if (!other) {
                        text = '<i class="icon-tip-no"></i>两次填写的密码不一致';
                        pit_5=0;
                    }
                    tip.html(text);
                }
            }
        });
    });
</script>
</body>
</html>