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
        <a href="">首页</a>&nbsp;>&nbsp;<a href="">账户设置</a>&nbsp;>&nbsp;<a href="">个人资料</a>
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
                <a href="<?php echo site_url('user/user/account_information');?>"><li class="active">账户信息<font class="fr">|</font></li></a>
                <a href="<?php echo site_url('user/user/head_portrait');?>"><li>头像上传<font class="fr">|</font></li></a>
                <a href="<?php echo site_url('user/user/account_security');?>"><li>账号安全</li></a>
            </ul>
            <ul class="tab_con">
                <li class="zhxx active">
                    <p>
                        <font >用户名</font><font class="zj user_name"><?php echo $user['data']['user_name'];?></font><font class="yc"><?php if($user['data']['user_name'] == $user['data']['real_name'] || $user['data']['user_name'] == $user['data']['mobile']): ?><i class="xgnc">修改用户名</i><?php endif;?></font>
                    </p>
                        <!--修改昵称_1-->
                        <div class="user_data_pop xgnc_1">
                            <div class="title">
                                <span>修改用户名</span><font class="fr close">×</font>
                            </div>
                            <div class="popbody tc">
                                <div class="p xgnc_p">
                                    <div class="fl tr">
                                        输入新用户名：
                                    </div>
                                    <div class="fr tl">
                                        <input type="text" value="" placeholder="请输入用户名" id="name" maxlength="15"/>
                                    </div>
                                </div>
                                <div class="p xgnc_p">
                                    <div class="fr tl tip_pop" id="name_notes">
											<span style="color:red;">仅能修改一次，修改后将无法修改</span>
                                    </div>
                                </div>
                                <button type="button" class="user_data_pop_but sub " id="name_sub">提交</button>
                            </div>
                        </div>
                        <!--修改昵称_1-->
                        <!--修改昵称_2-->
                        <div class="user_data_pop xgnc_2">
                            <div class="title">
                                <span>修改用户名</span><font class="fr close">×</font>
                            </div>
                            <div class="popbody tc">
                                <div class="p xgnc_p">
                                    <div class="fl tr">
                                        <img src="<?php echo base_url('assets/images/user/xrt.png')?>">
                                    </div>
                                    <div class="fr tl">
                                        <div class="popbody_p shb">用户名修改成功</div>
                                        <div class="popbody_p xb" ><span id="new_name"></span>，<font>这名字听上去真带劲～！</font></div>
                                    </div>
                                </div>
                                <button type="" class="user_data_pop_but sub close">完成</button>
                            </div>
                        </div>
                        <!--修改昵称_2-->
                    <p>
                        <font>手机号</font><font class="zj" id="zj"><?php echo secret($user['data']['mobile'],5);?></font><font class="yc"><i class="xgsjh">更换绑定</i></font>
                    </p>
                        <!--修改手机号_1-->
                        <div class="user_data_pop xgsjh_1">
                            <div class="title">
                                <span>修改绑定的手机号</span><font class="fr close">×</font>
                            </div>
                            <div class="popbody tc">
                                <div class="p xgsjh_p">
                                    <div class="fl tr">
                                        手机验证码：
                                    </div>
                                    <div class="fl tl" style="width:34%">
                                        <input type="text" value="" placeholder="请输入验证码" id="authcode">
                                    </div>
                                    <div class="fl tl">
                                        <input type="button" class="but_sent" id="but_sent_xgsj" value="获取验证码">
                                    </div>
                                </div>
                                <div class="p xgsjh_p">
                                    <div class="fr tl tip_pop" id="mobile_explain">
                                        
                                    </div>
                                </div>
                                <button type="" class="user_data_pop_but sub " id="mobile_next" >下一步</button>
                            </div>
                        </div>
                        <!--修改手机号_1-->
                        <!--修改手机号_2-->
                        <div class="user_data_pop xgsjh_2">
                            <div class="title">
                                <span>修改绑定的手机号</span><font class="fr close">×</font>
                            </div>
                            <div class="popbody tc">
                                <div class="p xgsjh_p" style="margin-bottom:10px;">
                                    <div class="fl tr">
                                        要绑定的手机号：
                                    </div>
                                    <div class="fl tl" style="width:66%">
                                        <input type="text" value="" placeholder="请输入验证码" id="new_mobile">
                                    </div>
                                </div>
                                <div class="p xgsjh_p">
                                    <div class="fl tr">
                                        手机验证码：
                                    </div>
                                    <div class="fl tl" style="width:34%">
                                        <input type="text" value="" placeholder="请输入验证码" id="new_authcode">
                                    </div>
                                    <div class="fl tl">
                                        <input type="button" class="but_sent" id="but_sent_xgsj_1" value="获取验证码">
                                    </div>
                                </div>
                                <div class="p xgsjh_p">
                                    <div class="fr tl tip_pop " id="new_mobile_explain">
                                        
                                    </div>
                                </div>
                                <button type="" class="user_data_pop_but sub " id="new_mobile_next">下一步</button>
                            </div>
                        </div>
                        <!--修改手机号_2-->
                        <!--修改手机号_3-->
                        <div class="user_data_pop xgsjh_3">
                            <div class="title">
                                <span>修改绑定的手机号</span><font class="fr close">×</font>
                            </div>
                            <div class="popbody tc">
                                <div class="p xgsjh_p">
                                    <div class="fl tr">
                                        <img src="<?php echo base_url('assets/images/user/xrt.png')?>">
                                    </div>
                                    <div class="fr tl" style="width:60%;">
                                        <div class="popbody_p shb">手机号修改成功</div>
                                        <div class="popbody_p xb"><font>新的手机号码为：</font><span id="mobile_new">138****4564</span></div>
                                    </div>
                                </div>
                                <button type="" class="user_data_pop_but sub close">完成</button>
                            </div>
                        </div>
                        <!--修改手机号_3-->
                    <p>
                        <font>邮箱</font><font class="zj"><?php echo $user['data']['email'];?></font><font class="yc"><i class="xgyx"><?php echo (!empty($user['data']['email']))?'修改邮箱':'去绑定';?></i></font>
                    </p>
                        <!--修改邮箱_2-->
                        <div class="user_data_pop xgyx_2">
                            <div class="title">
                                <span>修改邮箱</span><font class="fr close">×</font>
                            </div>
                            <div class="popbody tc">
                                <div class="p xgyx_p">
                                    <div class="fl tr">
                                        <img src="<?php echo base_url('assets/images/user/xrt.png')?>">
                                    </div>
                                    <div class="fr tl" style="width:65%; overflow:hidden; height:180px">
                                        <div class="popbody_p shb">验证邮件已发送...</div>
                                        <div class="popbody_p xb user_name"><?php echo $user['data']['user_name']?><font>，我们已经向您的邮箱</font><span id="send_mail">jxq@zgwjjf.com</span><font>发送了一封验证邮件，请您尽快查收。</font><a href=""  id="a_mail" target="_Blank">去邮箱查收。</a><font>没收到邮件?请检查您的垃圾箱或者广告箱， 邮件可能被误认为垃圾或者广告邮件。</font></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!--修改邮箱_2-->
                        <!--修改邮箱_1-->
                        <div class="user_data_pop xgyx_1">
                            <div class="title">
                                <span>修改邮箱</span><font class="fr close">×</font>
                            </div>
                            <div class="popbody tc">
                                <div class="p xgyx_p">
                                    <div class="fl tr">
                                        输入邮箱：
                                    </div>
                                    <div class="fr tl">
                                        <input type="text" value="" placeholder="请输入邮箱" id="email">
                                    </div>
                                </div>
                                <div class="p xgyx_p">
                                    <div class="fr tl tip_pop" id="email_explain">
                                    </div>
                                </div>
                                <button type="" class="user_data_pop_but sub " id="next_email">提交</button>
                            </div>
                        </div>
                        <!--修改邮箱_1-->

                    <!--添加理财师邀请码-->
                    <p>
                        <font >理财师邀请码</font><font class="zj lcs-set-flag"><?php echo isset($user['data']['inviter'])&&$user['data']['inviter']?'已填写':'未填写';?></font><font class="yc lcs-set-name"><?php if(!$user['data']['inviter']):?><i class="xglcs lcs_e">添加</i><?php else:echo $user['data']['lcs_no']; endif; ?></font>
                    </p>
                    <div class="user_data_pop xglcs_1">
                        <div class="title">
                            <span>理财师邀请码</span><font class="fr close">×</font>
                        </div>
                        <div class="popbody tc">
                            <div class="p xgnc_p">
                                <div class="fl tr">
                                    理财师邀请码：
                                </div>
                                <div class="fr tl">
                                    <input type="text" value="" placeholder="请输入理财师邀请码" id="lcs"/>
                                </div>
                            </div>
                            <div class="p xgnc_p">
                                <div class="fr tl tip_pop" id="lcs_notes">

                                </div>
                            </div>
                            <button type="button" class="user_data_pop_but sub " id="lcs_sub">提交</button>
                        </div>
                    </div>
                    <!--添加公司邀请码-->

                    <!--添加公司邀请码-->
                    <p>
                        <font >公司邀请码</font><font class="zj company-set-flag"><?php echo isset($user['data']['company'])&&$user['data']['company']?'已填写':'未填写';?></font><font class="yc company-set-name"><?php if(!$user['data']['company']):?><i class="xggsyzm company_e">添加</i><?php else:echo $user['data']['company']; endif; ?></font>
                    </p>
                    <div class="user_data_pop xggsyzm_1">
                        <div class="title">
                            <span>公司邀请码</span><font class="fr close">×</font>
                        </div>
                        <div class="popbody tc">
                            <div class="p xgnc_p">
                                <div class="fl tr">
                                    公司邀请码：
                                </div>
                                <div class="fr tl">
                                    <input type="text" value="" placeholder="请输入公司邀请码" id="company"/>
                                </div>
                            </div>
                            <div class="p xgnc_p">
                                <div class="fr tl tip_pop" id="company_notes">

                                </div>
                            </div>
                            <button type="button" class="user_data_pop_but sub " id="company_sub">提交</button>
                        </div>
                    </div>
                    <!--添加公司邀请码-->
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
    seajs.use(['jquery','sys'],function(){
		//修改手机
        //修改呢称
        pop($('.xgnc'),$('.xgnc_1'),$('.xgnc_1').find('.close'));
        //修改呢称
        //换手机
        //pop_sub($('.xgsjh_1').find('.sub'),$('.xgsjh_2'),$('.xgsjh_2').find('.close'));
        pop($('.xgsjh'),$('.xgsjh_1'),$('.xgsjh_1').find('.close'));
		//点击验证码原手机
        $("#but_sent_xgsj").click(function(){
			var mobile = <?php echo $user['data']['mobile'];?>;
			$.post('/index.php/user/user/send_sms?action=unbindphone&mobile='+mobile,{},function(result){
				result = JSON.parse(result);
				if(result.status==1){
					$('#mobile_explain').html(result.msg);					
				}else{			
					$('#mobile_explain').html('验证码已发送至您的手机<?php echo secret($user['data']['mobile'],5);?>，请注意查收！');				
				}
			});  
			dxdjs($(this));
        })
		//验证码判断
		 $("#authcode").keyup(function(){
			 var mobile = <?php echo $user['data']['mobile'];?>;
			var authcode =  $('#authcode').val();
			if(authcode==''){
				$('#mobile_explain').html('验证码不能为空！');
				return;
			}  
			$.post('/index.php/user/user/Change_mobile_one?mobile='+mobile+'&authcode='+authcode,{},function(result){	
				result = JSON.parse(result);	
				if(result.status==10001){
					$('#mobile_explain').html(result.msg);
				}else{
					$('#mobile_explain').html(result.msg);
				}
			}); 
        })
		//第一下一步
		 $("#mobile_next").click(function(){
			var mobile = <?php echo $user['data']['mobile'];?>;
			var authcode =  $('#authcode').val();
			if(authcode==''){
				$('#mobile_explain').html('验证码不能为空！');
				return;
			}else{
				$.post('/index.php/user/user/Change_mobile_one?mobile='+mobile+'&authcode='+authcode,{},function(result){			
					result = JSON.parse(result);	
					if(result.status==10001){
						$('#mobile_explain').html(result.msg);
					}else{
						pop_sub_yzh($('.xgsjh_1'),$('.xgsjh_2'),$('.xgsjh_2').find('.close'));//关闭第一步 弹出第二步
					}
				});  
			}
        })
		//第二步验证
        $("#new_mobile_next").click(function(){	
			var mobile = $('#new_mobile').val();
			var authcode =  $('#new_authcode').val();	
			var reg = /^0?1[3|4|5|7|8][0-9]\d{8}$/;
			if(mobile==''){
				$('#new_mobile_explain').html('请输入手机号！');
				return;
			}	
			if (reg.test(mobile)) {
				if(authcode==''){
				$('#new_mobile_explain').html('验证码不能为空！');
				return;
			}else{
					$.post('/index.php/user/user/Change_mobile_two?mobile='+mobile+'&authcode='+authcode,{},function(result){			
						result = JSON.parse(result);	
						if(result.status==10001){
							$('#new_mobile_explain').html(result.msg);
						}else{
							$('#mobile_new').html(mobile);
							$('#zj').html(mobile.substring(0, 3) + "*****" + mobile.substring(8, 11));
							pop_sub_yzh($('.xgsjh_2'),$('.xgsjh_3'),$('.xgsjh_3').find('.close'));//关闭第二步 弹出第三步
						}
					});  
				}
			}else{
				$('#new_mobile_explain').html('手机号不正确！');
				return;
			}
			
        })
		//点击验证码新手机
        $("#but_sent_xgsj_1").click(function(){	
			var mobile = $('#new_mobile').val();
			if(mobile==''){
				$('#new_mobile_explain').html('请输入手机号！');
				return;
			}
			var reg = /^0?1[3|4|5|7|8][0-9]\d{8}$/;
			if (reg.test(mobile)) {
				if(mobile==<?php echo $user['data']['mobile'];?>){
					$('#new_mobile_explain').html('和原手机一样不需要修改！');
					return;
				}else{
					$.post('/index.php/user/user/send_sms?action=bindphone&mobile='+mobile,{},function(result){
						result = JSON.parse(result);
						if(result.status==1){
							$('#new_mobile_explain').html(result.msg);
						}else{		
							$('#new_mobile').attr("readonly","readonly")
							$('#new_mobile_explain').html('验证码已发送至您的手机'+mobile+'，请注意查收！');	
						}
					});
				}
			}else{
				$('#new_mobile_explain').html('手机号不正确！');
				return;
			}	
           dxdjs($(this));
        })
        //换手机
        pop($('.xgyx'),$('.xgyx_1'),$('.xgyx_1').find('.close'));
        //修改手机

		//修改邮箱
		/*
		$('#email').keyup(function(){
			var reg= /^[a-z0-9]+([._]*[a-z0-9]+)*@[a-z0-9]+([_.][a-z0-9]+)+$/;
			if(reg.test(this.value)){
				$('#email_explain').html('ok!');
			}else{
				$('#email_explain').html('邮箱格式好像不对，检查一下吧亲～');
			}
		});
		*/
		$('#next_email').click(function(){
			var reg= /^[a-z0-9]+([._]*[a-z0-9]+)*@[a-z0-9]+([_.][a-z0-9]+)+$/;
			var email = $('#email').val();
			if(reg.test(email)){
				$('#send_mail').html(email);
				var hash = {
					'qq.com': 'http://mail.qq.com',
					'gmail.com': 'http://mail.google.com',
					'sina.com': 'http://mail.sina.com.cn',
					'163.com': 'http://mail.163.com',
					'126.com': 'http://mail.126.com',
					'yeah.net': 'http://www.yeah.net/',
					'sohu.com': 'http://mail.sohu.com/',
					'tom.com': 'http://mail.tom.com/',
					'sogou.com': 'http://mail.sogou.com/',
					'139.com': 'http://mail.10086.cn/',
					'hotmail.com': 'http://www.hotmail.com',
					'live.com': 'http://login.live.com/',
					'live.cn': 'http://login.live.cn/',
					'live.com.cn': 'http://login.live.com.cn',
					'189.com': 'http://webmail16.189.cn/webmail/',
					'yahoo.com.cn': 'http://mail.cn.yahoo.com/',
					'yahoo.cn': 'http://mail.cn.yahoo.com/',
					'eyou.com': 'http://www.eyou.com/',
					'21cn.com': 'http://mail.21cn.com/',
					'188.com': 'http://www.188.com/',
					'foxmail.com': 'http://www.foxmail.com',
					'outlook.com': 'http://www.outlook.com'
					}
				var _mail = email.split('@')[1]; 
				for (var j in hash){
						if(j == _mail){
							$("#a_mail").attr("href", hash[_mail]);    //替换登陆链接
						}
					}
				$.post('/index.php/user/user/send_mail?email='+email,{},function(result){
				
					result = JSON.parse(result);
					if(result.status=='10000'){
						$('#email_explain').html(result.msg);
						pop_sub_yzh($('.xgyx_1'),$('.xgyx_2'),$('.xgyx_2').find('.close'));//关闭第一到第二

                        //关闭 刷新
                        $('.xgyx_2').find('.close').bind('click',function(){ window.location.reload();});
					}else{
						$('#email_explain').html(result.msg);
					}
				});
				$('#email_explain').html('ok!');
			}else{
				$('#email_explain').html('邮箱格式好像不对，检查一下吧亲～');
			}
		});
		//修改邮箱
/*
        //修改姓名
		$('#name').keyup(function(){
			var name=this.value;
            if( ! /^[a-zA-Z_][a-zA-Z_0-9]{4,14}$/.test(name)){
                $('#name_notes').html('请输入以字母或下划线开头由字母数字下划线组成的5到15位的用户名!');
                $('#name_sub').attr("disabled", true);
                return false;
            }else{
                $('#name_notes').html('');
            }
			var condition;
			condition='?name='+name;
			$.post('/index.php/user/user/Change_name'+condition,{},function(result){
					result = eval(result);
					$('#name_notes').html(result[0].msg);
					if(result[0].status=='10000'){
						$('#name_sub').removeAttr("disabled");
						
						pop_sub($('.xgnc_1').find('.sub'),$('.xgnc_2'),$('.xgnc_2').find('.close'));
					}else{
						$('#name_sub').attr("disabled", true);
					}
					
			});
		});
        */
		$('#name_sub').click(function(){
			var name = $('#name').val();
            if(name == ''){
                $('#name_notes').html('请输入用户名!');
                return false;
            }
			var condition;
			condition='?name='+name+'&f=1';
			$.post('/index.php/user/user/Change_name'+condition,{},function(result){
                if(result.status == '10000'){
                    $('#new_name').html(result[0].name);
                    $('#head_user_name_span').html(result[0].name);
                    $('#left_user_name_span').html(result[0].name);
                    $('.user_name').html(result[0].name);
                }else{
                    $('#name_notes').html(result.msg);
                }
			},'json');
		});

        //公司邀请码
        pop($('.xggsyzm'),$('.xggsyzm_1'),$('.xggsyzm_1').find('.close'));
        $('#company_sub').click(function(){
            var code = $('#company').val();
            if(code == ''){
                $('#company_notes').html('请输入公司邀请码!');
                return false;
            }
            $.post('/index.php/user/user/company_invite_code',{code:code},function(result){
                if(result.status == '10000'){
                    $('.company-set-name').html(result.data.company_code);
                    $('.company-set-flag').html('已设置');
                    $(".black_bg").fadeOut();
                    $('.xggsyzm_1').fadeOut();
                }else{
                    $('#company_notes').html(result.msg);
                }

            },'json');
        });
        //理财师邀请码
        pop($('.xglcs'),$('.xglcs_1'),$('.xglcs_1').find('.close'));
        $('#lcs_sub').click(function(){
            var code = $('#lcs').val();
            if(code == ''){
                $('#lcs_notes').html('请输入理财师邀请码!');
                return false;
            }
            $.post('/index.php/user/user/lcs_invite_code',{code:code},function(result){
                if(result.status == '10000'){
                    $('.lcs-set-name').html(code);
                    $('.lcs-set-flag').html('已设置');
                    $(".black_bg").fadeOut();
                    $('.xglcs_1').fadeOut();
                }else{
                    $('#lcs_notes').html(result.msg);
                }

            },'json');
        });
        //根据类型自动触发
        switch ('<?php echo $type; ?>'){
            case 'name':
                $('.xgnc').click();
                break;
            case 'phone':
                $('.xgsjh').click();
                break;
            default:
        }
    });
   //修改姓名

</script>
<!--userjs end-->                   
</body>
</html>