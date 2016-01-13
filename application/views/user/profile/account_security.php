<!DOCTYPE html>
<html>
<head>
    <title>聚雪球_网加金服_P2P理财首选互联网金融P2P网贷平台_100元即可投资!</title>
    <?php $this->load->view('common/head_file'); ?>
</head>
<body>
    <!--head start-->
 <?php $this->load->view('common/head');?>    
    <!--head end-->
    <!--user start-->
    <div class="user_nav row">
        <a href="/index.php">首页</a>&nbsp;>&nbsp;<a href="/index.php/user/user/account_information">账户设置</a>&nbsp;>&nbsp;<a href="javascript:void(0);">基本信息-账号安全</a>
    </div>
    <div class="row user">
        <!--左侧通用-->
        <?php $this->load->view('common/user_left');?>
        <!--左侧通用-->
        <!--右侧-->
        <div class="user_right">
            <div class="black_bg"></div>
            <h1>个人资料</h1>
            <ul class="tab_title">
                <a href="<?php echo site_url('user/user/account_information');?>"><li>账户信息<font class="fr">|</font></li></a>
                <a href="<?php echo site_url('user/user/head_portrait');?>"><li>头像上传<font class="fr">|</font></li></a>
                <a href="<?php echo site_url('user/user/account_security');?>"><li  class="active">账号安全</li></a>
            </ul>
            <ul class="tab_con">
                <li class="zhxx active">
                    <p><font>实名认证</font><font class="zj real_name_flag"><?php echo in_array(profile('clientkind'),array('1','2','-3','-4','-5'))?'已认证':'未认证'; ?></font><font class="yc"><?php echo in_array(profile('clientkind'),array('1','2','-3','-4','-5'))?profile('real_name'):'<i class="smrz real_name_button">去认证</i>'; ?></font></p>
                        <!--实名认证_1-->
                        <div class="user_data_pop smrz_1">
                            <div class="title">
                                <span>实名认证</span><font class="fr close">×</font>
                            </div>
                            <div class="popbody tc">
                                <div class="p smrz_p" style="margin-bottom:10px;">
                                    <div class="fl tr">
                                        真实姓名：
                                    </div>
                                    <div class="fr tl">
                                        <input type="text" class="ifhav" id="real_name_input" value="" maxlength="5" placeholder="请输入姓名">
                                    </div>
                                </div>
                                <div class="p smrz_p">
                                    <div class="fl tr">
                                        身份证号码：
                                    </div>
                                    <div class="fr tl">
                                        <input type="text" class="ifhav" value="" id="nric_input" placeholder="请输入身份证号码" maxlength="18">
                                    </div>
                                </div>
                                <div class="p smrz_p">
                                    <div class="fr tl tip_pop" id="real_name_msg">
                                        
                                    </div>
                                </div>
                                <button type="submit" class="user_data_pop_but sub ajax-submit-button ls" id="real_name" data-loading-msg="提交认证中...">提交</button>
                            </div>
                        </div>
                        <!--实名认证_1-->
                        <!--实名认证_2-->
                        <div class="user_data_pop smrz_2">
                            <div class="title">
                                <span>实名认证</span><font class="fr close">×</font>
                            </div>
                            <div class="popbody tc">
                                <div class="p smrz_p" style="height:auto;">
                                    <div class="fl tr">
                                        <img src="<?php echo base_url('assets/images/user/xrt.png')?>">
                                    </div>
                                    <div class="fr tl">
                                        <div class="popbody_p shb">实名认证成功</div>
                                        <div class="popbody_p xb"><?php echo profile('user_name'); ?>，<font>您的安全等级已提升！</font></div>
                                    </div>
                                </div>
                                <button type="" class="user_data_pop_but sub close ls">完成</button>
                            </div>
                        </div>
                        <!--实名认证_2-->
                    <p><font>登录密码</font><font class="zj">已设置</font><font class="yc"><i class="xgmm">修改</i> | <i class="wjmm">重置</i></font></p>
                        <!--修改密码 1-->
                        <div class="user_data_pop xgmm_1">
                            <div class="title">
                                <span>修改密码</span><font class="fr close">×</font>
                            </div>
                            <div class="popbody tc">
                                <div class="p xgmm_p" style="margin-bottom:10px;">
                                    <div class="fl tr">
                                        输入原密码：
                                    </div>
                                    <div class="fr tl">
                                        <input type="password" class="ifhav" value="" placeholder="请输入密码" id="old_password">
                                    </div>
                                </div>
								<div class="p xgmm_p">
                                    <div class="fr tl tip_pop"  id="old_password_explain">
                                        
                                    </div>
                                </div>
                                <div class="p xgmm_p">
                                    <div class="fl tr">
                                        输入新密码：
                                    </div>
                                    <div class="fr tl">
                                        <input class="ifhav" type="password" value="" placeholder="请输入新密码" id="new_password">
                                    </div>
                                </div>
                                <div class="p xgmm_p">
                                    <div class="fr tl tip_pop" id="new_password_explain">
                                        
                                    </div>
                                </div>
                                <div class="p xgmm_p">
                                    <div class="fl tr">
                                        确认新密码：
                                    </div>
                                    <div class="fr tl">
                                        <input class="ifhav" type="password" value="" placeholder="请再次输入新密码"  id="new_password_two">
                                    </div>
                                </div>
                                <div class="p xgmm_p">
                                    <div class="fr tl tip_pop"  id="new_password_explain_two">
                                        
                                    </div>
                                </div>
                                <button type="" class="user_data_pop_but sub ls" id="passwoerd_sub" disabled >提交</button>
                            </div>
                        </div>
                        <!--修改密码 1-->
                        <!--修改密码 2-->
                        <div class="user_data_pop xgmm_2">
                            <div class="title">
                                <span>修改密码</span><font class="fr close">×</font>
                            </div>
                            <div class="popbody tc">
                                <div class="p xgmm_p">
                                    <div class="fl tr">
                                        <img src="<?php echo base_url('assets/images/user/xrt.png')?>">
                                    </div>
                                    <div class="fr tl">
                                        <div class="popbody_p shb">修改登录密码成功</div>
                                        <div class="popbody_p xb"><font>请妥善保管好您的密码！</font></div>
                                    </div>
                                </div>
                                <button type="" class="user_data_pop_but sub close ls">完成</button>
                            </div>
                        </div>
                        <!--修改密码 2-->
                        <!--忘记密码 1-->
                        <div class="user_data_pop xgmm_3">
                            <div class="title">
                                <span>重置登录密码</span><font class="fr close">×</font>
                            </div>
                            <div class="popbody tc">
                                <div class="p xgmm_p xgmm_p_1">
                                    <div class="fl tr">
                                        手机验证码：
                                    </div>
                                    <div class="fl tl" style="width:34%;line-height:45px;">
                                        <input type="text" class="ifhav" value="" placeholder="请输入验证码" id="reset_verification">
                                    </div>
                                    <div class="fl tl">
                                        <input type="button" class="but_sent ls" id="but_sent_wjmm" value="获取验证码">
                                    </div>
                                </div>
                                <div class="p xgmm_p">
                                    <div class="fr tl tip_pop" id="reset_password_explain">                                        
                                    </div>
                                </div>
                                <div class="p xgmm_p" style="margin-bottom:10px;">
                                    <div class="fl tr">
                                        设置登录密码：
                                    </div>
                                    <div class="fr tl">
                                        <input class="ifhav" type="password" value="" placeholder="请输入新密码" id="reset_password">
                                    </div>
                                </div>
								<div class="p xgmm_p">
                                    <div class="fr tl tip_pop" id="reset_mobile_explain">                                        
                                    </div>
                                </div>
                                <div class="p xgmm_p">
                                    <div class="fl tr">
                                        确认登录密码：
                                    </div>
                                    <div class="fr tl">
                                        <input class="ifhav" type="password" value="" placeholder="请再次输入新密码" id="reset_password_two">
                                    </div>
                                </div>
								<div class="p xgmm_p">
                                    <div class="fr tl tip_pop" id="reset_mobile_explain_two">                                        
                                    </div>
                                </div>
                                <button type="" class="user_data_pop_but sub ls" id="reset_sub">提交</button>
                            </div>
                        </div>
                        <!--忘记密码 1-->
                        <!--修改密码 2-->
                        <div class="user_data_pop xgmm_4">
                            <div class="title">
                                <span>重置登录密码</span><font class="fr close">×</font>
                            </div>
                            <div class="popbody tc">
                                <div class="p xgmm_p">
                                    <div class="fl tr">
                                        <img src="<?php echo base_url('assets/images/user/xrt.png')?>">
                                    </div>
                                    <div class="fr tl">
                                        <div class="popbody_p shb">登录密码重置成功</div>
                                        <div class="popbody_p xb"><font>请妥善保管好您的密码！</font></div>
                                    </div>
                                </div>
                                <button type="" class="user_data_pop_but sub close ls">完成</button>
                            </div>
                        </div>
                        <!--修改密码 2-->
                    <p><font>资金密码</font><font class="zj"><?php echo (!empty($data['security']))?'已设置':'未设置'?></font><font class="yc"><?php if(empty($data['security'])):?><i class="szzjmm">设置</i>  <?php else:?> <i class="xgzjmm">修改</i> | <i class="wjzjmm">重置</i><?php endif;?></font></p>
                        <!--设置资金密码 1-->
                        <div class="user_data_pop xgmm_5">
                            <div class="title">
                                <span>设置资金密码</span><font class="fr close">×</font>
                            </div>
                            <div class="popbody tc">
                                <div class="p xgmm_p">
                                    <div class="fl tr">
                                        输入登录密码：
                                    </div>
                                    <div class="fr tl">
                                        <input class="ifhav" type="password" value="" placeholder="请输入密码" id ="login_password">
                                    </div>
                                </div>
                                <div class="p xgmm_p">
                                    <div class="fr tl tip_pop" id="fund_password_explain">
                                        
                                    </div>
                                </div>
                                <div class="p xgmm_p xgmm_p_1">
                                    <div class="fl tr">
                                        手机验证码：
                                    </div>
                                    <div class="fl tl" style="width:34%; line-height:45px;">
                                        <input class="ifhav" type="text" value="" placeholder="请输入验证码" id="login_password_code">
                                    </div>
                                    <div class="fl tl">
                                        <input type="button" class="but_sent ls" id="but_sent_szzjmm" value="获取验证码">
                                    </div>
                                </div>
                                <div class="p xgmm_p">
                                    <div class="fr tl tip_pop"  id="fund_password_explain_one">
                                        
                                    </div>
                                </div>
                                <div class="p xgmm_p" style="margin-bottom:10px;">
                                    <div class="fl tr">
                                        输入资金密码：
                                    </div>
                                    <div class="fr tl">
                                        <input class="ifhav" type="password" value="" placeholder="请输入新密码" id="login_password_one">
                                    </div>
                                </div>
								<div class="p xgmm_p">
                                    <div class="fr tl tip_pop"  id="fund_password_explain_two">
                                        
                                    </div>
                                </div>
                                <div class="p xgmm_p">
                                    <div class="fl tr">
                                        确认资金密码：
                                    </div>
                                    <div class="fr tl">
                                        <input class="ifhav" type="password" value="" placeholder="请再次输入新密码"  id="login_password_two">
                                    </div>
                                </div>
								<div class="p xgmm_p">
                                    <div class="fr tl tip_pop"  id="fund_password_explain_three">
                                        
                                    </div>
                                </div>
                                <button type="" class="user_data_pop_but sub ls" id="fund_password_sub">提交</button>
                            </div>
                        </div>
                        <!--设置资金密码 1-->
                        <!--设置资金密码 2-->
                        <div class="user_data_pop xgmm_6">
                            <div class="title">
                                <span>设置资金密码</span><font class="fr close">×</font>
                            </div>
                            <div class="popbody tc">
                                <div class="p xgmm_p">
                                    <div class="fl tr">
                                        <img src="<?php echo base_url('assets/images/user/xrt.png')?>">
                                    </div>
                                    <div class="fr tl">
                                        <div class="popbody_p shb">资金密码设置成功</div>
                                        <div class="popbody_p xb"><font>请妥善保管好您的密码！</font></div>
                                    </div>
                                </div>
                                <button type="" class="user_data_pop_but sub close ls">完成</button>
                            </div>
                        </div>
                        <!--设置资金密码 2-->
                        <!--修改资金密码 1-->
                        <div class="user_data_pop xgmm_7">
                            <div class="title">
                                <span>修改资金密码</span><font class="fr close">×</font>
                            </div>
                            <div class="popbody tc">
                                <div class="p xgmm_p">
                                    <div class="fl tr">
                                        输入登录密码：
                                    </div>
                                    <div class="fr tl">
                                        <input class="ifhav" type="password" value="" placeholder="请输入密码"  id ="login_password_update">
                                    </div>
                                </div>
                                <div class="p xgmm_p">
                                    <div class="fr tl tip_pop"  id="fund_password_update_explain">
                                        
                                    </div>
                                </div>
                                <div class="p xgmm_p xgmm_p_1">
                                    <div class="fl tr">
                                        手机验证码：
                                    </div>
                                    <div class="fl tl" style="width:34%">
                                        <input class="ifhav" type="text" value="" placeholder="请输入验证码"  id="login_password_update_code">
                                    </div>
                                    <div class="fl tl">
                                        <input type="button" class="but_sent ls" id="but_sent_xgzjmm" value="获取验证码">
                                    </div>
                                </div>
                                <div class="p xgmm_p">
                                    <div class="fr tl tip_pop"  id="fund_password_update_explain_one">
                                        
                                    </div>
                                </div>
                                <div class="p xgmm_p" style="margin-bottom:10px;">
                                    <div class="fl tr">
                                        输入原资金密码：
                                    </div>
                                    <div class="fr tl">
                                        <input class="ifhav" type="password" value="" placeholder="请输入原密码" id="login_password_update_y">
                                    </div>
                                </div>
								<div class="p xgmm_p">
                                    <div class="fr tl tip_pop"  id="fund_password_update_explain_y">
                                        
                                    </div>
                                </div>
                                <div class="p xgmm_p" style="margin-bottom:10px;">
                                    <div class="fl tr">
                                        输入新资金密码：
                                    </div>
                                    <div class="fr tl">
                                        <input class="ifhav" type="password" value="" placeholder="请输入新密码" id ="login_password_update_one">
                                    </div>
                                </div>
								<div class="p xgmm_p">
                                    <div class="fr tl tip_pop"  id="fund_password_update_explain_two">
                                        
                                    </div>
                                </div>
                                <div class="p xgmm_p">
                                    <div class="fl tr">
                                        确认新资金密码：
                                    </div>
                                    <div class="fr tl">
                                        <input class="ifhav" type="password" value="" placeholder="请再次输入新密码" id ="login_password_update_two">
                                    </div>
                                </div>
								<div class="p xgmm_p">
                                    <div class="fr tl tip_pop"  id="fund_password_update_explain_three">
                                        
                                    </div>
                                </div>
                                <button type="" class="user_data_pop_but sub ls" id="login_password_update_sub">提交</button>
                            </div>
                        </div>
                        <!--修改资金密码 1-->
                        <!--修改资金密码 2-->
                        <div class="user_data_pop xgmm_8">
                            <div class="title">
                                <span>修改资金密码</span><font class="fr close">×</font>
                            </div>
                            <div class="popbody tc">
                                <div class="p xgmm_p">
                                    <div class="fl tr">
                                        <img src="<?php echo base_url('assets/images/user/xrt.png')?>">
                                    </div>
                                    <div class="fr tl">
                                        <div class="popbody_p shb">资金密码修改成功</div>
                                        <div class="popbody_p xb"><font>请妥善保管好您的密码！</font></div>
                                    </div>
                                </div>
                                <button type="" class="user_data_pop_but sub close ls" >完成</button>
                            </div>
                        </div>
                        <!--修改资金密码 2-->
                        <!--忘记资金密码 1-->
                        <div class="user_data_pop xgmm_9">
                            <div class="title">
                                <span>重置资金密码</span><font class="fr close">×</font>
                            </div>
                            <div class="popbody tc">
                                <div class="p xgmm_p">
                                    <div class="fl tr">
                                        输入登录密码：
                                    </div>
                                    <div class="fr tl">
                                        <input class="ifhav" type="password" value="" placeholder="请输入密码"  id ="login_password_forget">
                                    </div>
                                </div>
                                <div class="p xgmm_p">
                                    <div class="fr tl tip_pop"  id="fund_password_forget_explain">
                                        
                                    </div>
                                </div>
                                <div class="p xgmm_p xgmm_p_1">
                                    <div class="fl tr">
                                        手机验证码：
                                    </div>
                                    <div class="fl tl" style="width:34%; line-height:45px;">
                                        <input class="ifhav" type="text" value="" placeholder="请输入验证码"   id ="login_password_forget_code">
                                    </div>
                                    <div class="fl tl">
                                        <input type="button" class="but_sent ls" id="but_sent_wjzjmm" value="获取验证码">
                                    </div>
                                </div>
                                <div class="p xgmm_p">
                                    <div class="fr tl tip_pop"  id="fund_password_forget_explain_one">
                                        
                                    </div>
                                </div>
                                <div class="p xgmm_p" style="margin-bottom:10px;">
                                    <div class="fl tr">
                                        输入资金密码：
                                    </div>
                                    <div class="fr tl">
                                        <input class="ifhav" type="password" value="" placeholder="请输入新密码" id ="login_password_forget_one">
                                    </div>
                                </div>
								<div class="p xgmm_p">
                                    <div class="fr tl tip_pop"  id="fund_password_forget_explain_two">
                                        
                                    </div>
                                </div>
                                <div class="p xgmm_p">
                                    <div class="fl tr">
                                        确认资金密码：
                                    </div>
                                    <div class="fr tl">
                                        <input class="ifhav" type="password" value="" placeholder="请再次输入新密码" id ="login_password_forget_two">
                                    </div>
                                </div>
								<div class="p xgmm_p">
                                    <div class="fr tl tip_pop"  id="fund_password_forget_explain_three">
                                        
                                    </div>
                                </div>
                                <button type="" class="user_data_pop_but sub ls" id="login_password_forget_sub">提交</button>
                            </div>
                        </div>
                        <!--忘记资金密码 1-->
                        <!--忘记资金密码 2-->
                        <div class="user_data_pop xgmm_10">
                            <div class="title">
                                <span>重置资金密码</span><font class="fr close">×</font>
                            </div>
                            <div class="popbody tc">
                                <div class="p xgmm_p">
                                    <div class="fl tr">
                                        <img src="<?php echo base_url('assets/images/user/xrt.png')?>">
                                    </div>
                                    <div class="fr tl">
                                        <div class="popbody_p shb">资金密码重置成功</div>
                                        <div class="popbody_p xb"><font>请妥善保管好您的密码！</font></div>
                                    </div>
                                </div>
                                <button type="" class="user_data_pop_but sub close ls">完成</button>
                            </div>
                        </div>
                        <!--忘记资金密码 2-->
                </li>
            </ul>
        </div>
        <!--右侧-->
    </div>
    <!--user end-->
<!--footer-->
<?php $this->load->view('common/footer');?> 
<!--footer-->
<!--userjs start-->
<script type="text/javascript">
    seajs.use(['jquery','sys','wsb_sys'],function(){
        //INPUT框变色
        $('.ifhav').focus(function(){
            $(this).addClass('hav');
        });
        $('.ifhav').blur(function(){
            if($.trim($(this).val())==''){
                $(this).removeClass('hav');
            }
        });
        //实名认证
        pop($('.smrz'),$('.smrz_1'),$('.smrz_1').find('.close'));
        $('#real_name').click(function(){
            $('#real_name_msg').html('');
            var real_name = $('#real_name_input').val();
            var nric = $('#nric_input').val();

            if(real_name.length < 2){
                $("#real_name_msg").html('请输入两位及以上中文姓名!');
                return false;
            }
            if( ! is_nric(nric)){
                $("#real_name_msg").html('请输入正确格式的身份证号码!');
                return false;
            }
            $('#real_name_msg').html('实名认证视网络情况需要一到几分钟 请耐心等待');
            $.post('/index.php/user/user/real_name',{real_name:real_name,nric:nric},function(result){
                if(result.status=='10000'){
                    pop_sub($('.smrz_1').find('.sub'),$('.smrz_2'),$('.smrz_2').find('.close'));
//                    $(".black_bg").fadeOut();
                    $('.smrz_1').fadeOut();
                    $('.smrz_2').fadeIn();
                    $('.real_name_flag').html('已认证');
                    $('.real_name_button').html(real_name);
                    $('#real_name_image').attr('src','/assets/images/common/user_left_1_ok.png');
                }else{
                    $('#real_name_msg').html(result.msg);
                }
            },'json');
        });
        //实名认证
        //修改登录密码
		$('#old_password').keyup(function(){
			var old_password = $('#old_password').val();
			if(old_password==''){
				$('#old_password_explain').html('原密码不能为空！');
				return;
			}else{
				$('#old_password_explain').html('ok！');
			}
		})
		$('#new_password').keyup(function(){
			var new_password = $('#new_password').val();
			if(new_password==''){
				$('#new_password_explain').html('新密码不能为空！');
				return;
			}else{
				$('#new_password_explain').html('ok！');
			}
		})
		$('#new_password_two').keyup(function(){
			var new_password_two = $('#new_password_two').val();
			var new_password = $('#new_password').val();
			if(new_password_two==''){
				$('#new_password_explain_two').html('确认密码不能为空！');
				return;
			}else{
				if(new_password==new_password_two){
					$('#new_password_explain_two').html('ok！');
					$('#passwoerd_sub').attr("disabled",false); 
				}else{
					$('#passwoerd_sub').attr("disabled",true); 
					$('#new_password_explain_two').html('两次密码不一致!');
				}
			}
		})
		$('#passwoerd_sub').click(function(){
			var new_password = $('#new_password').val();
			var old_password = $('#old_password').val();
			$.post('/index.php/user/user/Change_login_password?password='+old_password+'&new_password='+new_password,{},function(result){
					result = JSON.parse(result);
					if(result.status=='10000'){
						pop_sub_yzh($('.xgmm_1'),$('.xgmm_2'),$('.xgmm_2').find('.close'));
					}else{
						$('#old_password_explain').html(result.msg);
					}
				});
		})
        pop($('.xgmm'),$('.xgmm_1'),$('.xgmm_1').find('.close'));
        //pop_sub($('.xgmm_1').find('.sub'),$('.xgmm_2'),$('.xgmm_2').find('.close'));
        //修改登录密码
        //忘记登录密码
        pop($('.wjmm'),$('.xgmm_3'),$('.xgmm_3').find('.close'));
        //pop_sub($('.xgmm_3').find('.sub'),$('.xgmm_4'),$('.xgmm_4').find('.close'));
        $("#but_sent_wjmm").click(function(){
			var mobile = <?php echo $data['mobile']?>;
			$.post('/index.php/user/user/send_sms?action=password&mobile='+mobile,{},function(result){
					result = JSON.parse(result);
					if(result.status=='10000'){
						dxdjs($(this));
						$('#reset_password_explain').html('验证码已发送至您的手机<?php echo secret($data['mobile'],5)?>，请注意查收');
					}else{
						$('#reset_password_explain').html(result.msg);
					}
				});   
        })
		$("#reset_verification").keyup(function(){
			var code = $('#reset_verification').val();
			if(code==''){
				$('#reset_password_explain').html('验证码不能为空！');
			}else{
				$('#reset_password_explain').html('ok！');
			}
		})
		$("#reset_password").keyup(function(){
			var reset_password = $('#reset_password').val();
			if(reset_password==''){
				$('#reset_mobile_explain').html('重置密码不能为空！');
			}else{
				$('#reset_mobile_explain').html('ok！');
			}
		})
		$("#reset_password_two").keyup(function(){
			var reset_password = $('#reset_password').val();
			var reset_password_two = $('#reset_password_two').val();
			if(reset_password_two==''){
				$('#reset_mobile_explain_two').html('确认重置密码不能为空！');
			}else{
				if(reset_password==reset_password_two){
					$('#reset_mobile_explain_two').html('ok！');
				}else{
					$('#reset_mobile_explain_two').html('两次输入的密码不一致！');
				}
			}
		})
		$("#reset_password_two").keyup(function(){
			var reset_password = $('#reset_password').val();
			var reset_password_two = $('#reset_password_two').val();
			if(reset_password_two==''){
				$('#reset_mobile_explain_two').html('确认重置密码不能为空！');
			}else{
				if(reset_password==reset_password_two){
					$('#reset_mobile_explain_two').html('ok！');
				}else{
					$('#reset_mobile_explain_two').html('两次输入的密码不一致！');
				}
			}
		})
		$("#reset_sub").click(function(){
			var reset_password = $('#reset_password').val();
			var reset_password_two = $('#reset_password_two').val();
			var code = $('#reset_verification').val();
			if(code==''){
				$('#reset_password_explain').html('验证码不能为空!');
			}
			if(reset_password!=reset_password_two){
				$('#reset_mobile_explain_two').html('两次输入的密码不一致！');
				return;
			}
			if(reset_password==''||reset_password_two==''){
				$('#reset_mobile_explain_two').html('密码不能为空！');
				return;
			}
			$.post('/index.php/user/user/Reset_login_password?new_password='+reset_password+'&code='+code,{},function(result){
					result = JSON.parse(result);
					if(result.status=='10000'){
						pop_sub_yzh($('.xgmm_3'),$('.xgmm_4'),$('.xgmm_4').find('.close'));
					}else{
						$('#reset_password_explain').html(result.msg);
					}
				});
		})
			
        //忘记登录密码
        //设置资金密码
        pop($('.szzjmm'),$('.xgmm_5'),$('.xgmm_5').find('.close'));
        //pop_sub($('.xgmm_5').find('.sub'),$('.xgmm_6'),$('.xgmm_6').find('.close'));
        $("#but_sent_szzjmm").click(function(){
			var mobile = <?php echo $data['mobile']?>;
			$.post('/index.php/user/user/send_sms?action=security&mobile='+mobile,{},function(result){
				result = JSON.parse(result);
				if(result.status=='10000'){
					$('#fund_password_explain_one').html('验证码已发送至您的手机<?php echo secret($data['mobile'],5)?>，请注意查收');
					dxdjs($(this));
				}else{
					$('#fund_password_explain_one').html(result.msg);
				}
				
			});
        })
		$("#fund_password_sub").click(function(){
			var login_password = $('#login_password').val();
			var password_code = $('#login_password_code').val();
			var password_one = $('#login_password_one').val();
			var password_two = $('#login_password_two').val();
			var mobile = <?php echo $data['mobile']?>;
			if(login_password==''){
				$('#fund_password_explain').html('登录密码不能为空！');
				return;
			}else{
				$('#fund_password_explain').html('');
			}
			if(password_code==''){
				$('#fund_password_explain_one').html('验证码不能为空！');
				return;
			}else{
				$('#fund_password_explain_one').html('');
			}
			if(password_one==''){
				$('#fund_password_explain_two').html('资金密码不能为空！');
			}else{
				$('#fund_password_explain_two').html('');
			}
			if(password_two==''){
				$('#fund_password_explain_three').html('确认资金密码不能为空！');
				return;
			}else{
				$('#fund_password_explain_three').html('');
			}
			if(password_one!=password_two){
				$('#fund_password_explain_three').html('两次密码不同！');
				return;
			}else{
				$.post('/index.php/user/user/Fund_password?mobile='+mobile+'&code='+password_code+'&security='+password_one+'&password='+login_password,{},function(result){
						result = JSON.parse(result);
						if(result.status=='10002'){
							$('#fund_password_explain_one').html(result.msg);
						}
						if(result.status=='10003'){
							$('#fund_password_explain').html(result.msg);
						}
						if(result.status=='10001'){
							$('#fund_password_explain_three').html(result.msg);
						}
						if(result.status=='10000'){
							pop_sub_yzh($('.xgmm_5'),$('.xgmm_6'),$('.xgmm_6').find('.close'));
						}
					});
			}
		})
        //设置资金密码
        //修改资金密码
        pop($('.xgzjmm'),$('.xgmm_7'),$('.xgmm_7').find('.close'));
       // pop_sub($('.xgmm_7').find('.sub'),$('.xgmm_8'),$('.xgmm_8').find('.close'));
        $("#but_sent_xgzjmm").click(function(){
            var mobile = <?php echo $data['mobile']?>;
            var _this = $(this);
			$.post('/index.php/user/user/send_sms?action=security&mobile='+mobile,{},function(result){
				result = JSON.parse(result);
				if(result.status=='10000'){
					$('#fund_password_update_explain_one').html('验证码已发送至您的手机<?php echo secret($data['mobile'],5)?>，请注意查收');
					dxdjs(_this);
                    $('#fund_password_update_explain_one').append('<br/>短信接不到？<a href="javascript:void(0);" style="text-decoration: underline;" id="xgzjmm-voice" ' +
                        'data-wait-time="<?php echo item("sms_space_time")?item("sms_space_time"):60; ?>" '+
                    'data-last-time="<?php echo profile("voice_last_send_time")?profile("voice_last_send_time"):0; ?>">试试语音验证码</a>');
                    $("#xgzjmm-voice").send_sms('voice',mobile,'security');
				}else{
					$('#fund_password_update_explain_one').html(result.msg);
				}		
			});
        })
		$("#login_password_update_sub").click(function(){
            var login_password = $('#login_password_update').val();
			var password_code = $('#login_password_update_code').val();
			var password_one = $('#login_password_update_one').val();
			var password_two = $('#login_password_update_two').val();
			var password_y = $('#login_password_update_y').val();
			var mobile = <?php echo $data['mobile']?>;
			if(login_password==''){
				$('#fund_password_update_explain').html('登录密码不能为空！');
				return;
			}else{
				$('#fund_password_update_explain').html('');
			}
			if(password_code==''){
				$('#fund_password_update_explain_one').html('验证码不能为空！');
				return;
			}else{
				$('#fund_password_update_explain_one').html('');
			}
			if(password_y==''){
				$('#fund_password_update_explain_y').html('原资金密码不能为空！');
				return;
			}else{
				$('#fund_password_update_explain_y').html('');
			}
			if(password_one==''){
				$('#fund_password_update_explain_two').html('资金密码不能为空！');
				return;
			}else{
				$('#fund_password_update_explain_two').html('');
			}
			if(password_two==''){
				$('#fund_password_update_explain_three').html('确认资金密码不能为空！');
				return;
			}else{
				$('#fund_password_update_explain_three').html('');
			}
			if(password_one!=password_two){
				$('#fund_password_update_explain_three').html('两次密码不同！');
				return;
			}else{
				$.post('/index.php/user/user/update_fund_password?mobile='+mobile+'&code='+password_code+'&security='+password_y+'&password='+login_password+'&security_new='+password_one,{},function(result){			
						result = JSON.parse(result);
						if(result.status=='10002'){
							$('#fund_password_update_explain_one').html(result.msg);
						}
						if(result.status=='10003'){
							$('#fund_password_update_explain').html(result.msg);
						}
						if(result.status=='10001'){
							$('#fund_password_update_explain_three').html(result.msg);
						}
						if(result.status=='10004'){
							$('#fund_password_update_explain_y').html(result.msg);
						}
						if(result.status=='10000'){
							pop_sub_yzh($('.xgmm_7'),$('.xgmm_8'),$('.xgmm_8').find('.close'));
						}
					});
			}
        })
        //修改资金密码
        //忘记资金密码
        pop($('.wjzjmm'),$('.xgmm_9'),$('.xgmm_9').find('.close'));
        //pop_sub($('.xgmm_9').find('.sub'),$('.xgmm_10'),$('.xgmm_10').find('.close'));
        $("#but_sent_wjzjmm").click(function(){
            var mobile = <?php echo $data['mobile']?>;
			$.post('/index.php/user/user/send_sms?action=security&mobile='+mobile,{},function(result){
				result = JSON.parse(result);
				if(result.status=='10000'){
					$('#fund_password_forget_explain_one').html('验证码已发送至您的手机<?php echo secret($data['mobile'],5)?>，请注意查收');
					dxdjs($(this));
				}else{
					$('#fund_password_forget_explain_one').html(result.msg);
				}
				
			});
        })
		$("#login_password_forget_sub").click(function(){
			var login_password = $('#login_password_forget').val();
			var password_code = $('#login_password_forget_code').val();
			var password_one = $('#login_password_forget_one').val();
			var password_two = $('#login_password_forget_two').val();
			var mobile = <?php echo $data['mobile']?>;
			if(login_password==''){
				$('#fund_password_forget_explain').html('登录密码不能为空！');
				return;
			}else{
				$('#fund_password_forget_explain').html('');
			}
			if(password_code==''){
				$('#fund_password_forget_explain_one').html('验证码不能为空！');
				return;
			}else{
				$('#fund_password_forget_explain_one').html('');
			}
			if(password_one==''){
				$('#fund_password_forget_explain_two').html('资金密码不能为空！');
			}else{
				$('#fund_password_forget_explain_two').html('');
			}
			if(password_two==''){
				$('#fund_password_forget_explain_three').html('确认资金密码不能为空！');
				return;
			}else{
				$('#fund_password_forget_explain_three').html('');
			}
			if(password_one!=password_two){
				$('#fund_password_forget_explain_three').html('两次密码不同！');
				return;
			}else{
				$.post('/index.php/user/user/Fund_password?mobile='+mobile+'&code='+password_code+'&security='+password_one+'&password='+login_password,{},function(result){
					
						result = JSON.parse(result);
						if(result.status=='10002'){
							$('#fund_password_forget_explain_one').html(result.msg);
						}
						if(result.status=='10003'){
							$('#fund_password_forget_explain').html(result.msg);
						}
						if(result.status=='10001'){
							$('#fund_password_forget_explain_three').html(result.msg);
						}
						if(result.status=='10000'){
							pop_sub_yzh($('.xgmm_9'),$('.xgmm_10'),$('.xgmm_10').find('.close'));
						}
					});
			}
		})
        //忘记资金密码

        //根据类型自动触发
        switch ('<?php echo $type; ?>'){
            case 'change_security':
                if($('.szzjmm').length == 1)$('.szzjmm').click();
                if($('.xgzjmm').length == 1)$('.xgzjmm').click();
                break;
            case 'find_security':
                $('.wjzjmm').click();
                break;
            case 'change_password':
                $('.xgmm').click();
                break;
            case 'find_password':
                $('.wjmm').click();
                break;
            case 'real_name':
                $('.smrz').click();
                break;
            default:
        }
    });
</script>
<!--userjs end-->                     
</body>
</html>