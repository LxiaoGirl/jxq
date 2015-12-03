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
                <div class="step_1">
                <form id="reg_1" action="" method="" accept-charset="utf-8" onsubmit="return false;">
                    <div class="title">用户注册</div>
                    <div class="inp_pit">
                        <div class="inp">
                            <img class="inpr hs" src="../../../../assets/images/passport/zc_sj.png">
                            <img class="inpr ls" src="../../../../assets/images/passport/zc_sj_ok.png">
                            <input class="reg_sj js_mobile" type="text" name="sjh" value="" placeholder="请输入您的手机号码" maxlength="11" />
                        </div>
                        <div class="pit"></div>
                    </div>
                    <div class="inp_pit">
                        <div class="inp">
                            <input class="pic_yzm js_picyzm" type="text" name="tpyzm" value="" placeholder="输入验证码" maxlength="6"/>
                            <span class="pic_yzm">
	                            <img id="imgCode" src="<?php echo site_url('send/captcha'); ?>" width="78" height="34" alt="验证码"
	                                 onclick="javascript:this.src = '<?php echo site_url('send/captcha'); ?>?t='+ new Date().valueOf()"
	                                 title="点击更换验证码"/>
                            </span>
                            <span class="pic_reset"><img src="../../../../assets/images/passport/pic_reset.png" onclick="javascript:document.getElementById('imgCode').src = '<?php echo site_url('send/captcha'); ?>?t='+ new Date().valueOf()"></span>
                        </div>
                        <div class="pit"></div>
                    </div>
                    <div class="chick_xy">
                        <div class="nr_xy">
                            <input class="check js_agr" type="checkbox" name="" checked="checked">我同意<a href="<?php echo site_url('about/register_agreement');?>" target="_blank">《聚雪球用户注册协议》</a>
                        </div>
                    </div>
                    <div class="but">
                        <button  type="submit" id="reg_xyb" class="ajax-submit-button" data-loading-msg="提交中...">下一步</button>
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
        //INPUT框变色
        $('.inp').find('input').focus(function(){
            $(this).siblings('.ls').show();
            $(this).addClass('hav');
        });
        $('.inp').find('input').blur(function(){
            if($.trim($(this).val())==''){
                $(this).siblings('.ls').hide();
                $(this).removeClass('hav');
            }
        });
        //INPUT框变色
		var pit_1=0,pit_2=0;
		$('.but').find('#reg_xyb').click(function () {
			if(pit_1 == 0){
				$('.js_mobile').focus();
				return false;
			}
			if(pit_2 == 0){
				$('.js_picyzm').focus();
				return false;
			}
			if((pit_1+pit_2)==2){
				if( ! $('.js_agr').prop('checked')){
					wsb_alert('请阅读注册协议并同意才能注册!',2);
					return false;
				}
				$.ajax({
					type: 'POST',
					async: false,
					url: '<?php echo site_url('login/register'); ?>',
					data: {'mobile':$('.js_mobile').val()},
					dataType: 'json',
					error:function(){
						wsb_alert('服务器繁忙请稍后重试!',2);
					},
					success: function (result) {
						if(result.status == '10000') {
							window.location.href="<?php echo site_url('login/register_s1'); ?>";
						}else{
							wsb_alert(result.msg,2);
						}
					}
				});
			}
		});
		$('#reg_1').validate({
			'.js_mobile': {
				filtrate: 'required mobile',
				callback: function (index) {
					var tip = this.parent().parent().find('.pit').eq(0),
						text = '';
					if (index === 0) {
						text = '<i class="icon-tip-no"></i>请输入手机号码';
						pit_1=0;
					} else if (index === 1) {
						text = '<i class="icon-tip-no"></i>请输入正确的手机号码';
						pit_1=0;
					} else {
						//验证手机号是否注册
						$.ajax({
							type: 'POST',
							async: false,
							url: '<?php echo site_url('login/ajax_is_register'); ?>',
							data: {'mobile':$('.js_mobile').val()},
							dataType: 'json',
							success: function (result) {
								if (result.status != '10000') {
									text = '<i class="icon-tip-no"></i>'+result.msg;
									pit_1=0;
								} else {
									text = '<i class="icon-tip-yes"></i>';
									pit_1=1;
								}
								tip.html(text);
							}
						});
					}
					tip.html(text);
				}
			},
			'.js_picyzm': {
				filtrate: 'required',
				callback: function (index) {
					var tip = this.parent().parent().find('.pit').eq(0),
						text = '';
					if (index === 0) {
						text = '<i class="icon-tip-no"></i>请输入图片验证码';
						pit_2=0;
					} else{
						//验证图片验证码
						$.ajax({
							type: 'POST',
							async: false,
							url: '<?php echo site_url('send/ajax_check_captcha'); ?>',
							data: {'captcha':$('.js_picyzm').val()},
							dataType: 'json',
							success: function (result) {
								if (result.status != '10000') {
									text = '<i class="icon-tip-no"></i>'+result.msg;
									pit_2=0;
								} else {
									text = '<i class="icon-tip-yes"></i>';
									pit_2=1;
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