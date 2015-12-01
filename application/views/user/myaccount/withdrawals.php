<!DOCTYPE html>
<html>
<head>
    <title>聚雪球_网加金服_P2P理财首选互联网金融P2P网贷平台_100元即可投资!</title>
    <?php $this->load->view('common/head_file'); ?>
</head>
<body>
<body>
<!--head start-->
 <?php $this->load->view('common/head');?>         
    <!--head end-->
    <!--user start-->
	<div class="user_nav row">
        <a href="">首页</a>&nbsp;>&nbsp;<a href="">我的账户</a>&nbsp;>&nbsp;<a href="">提现</a>
    </div>
    <div class="row user">
        <!--左侧通用-->
		<?php $this->load->view('common/user_left');?>
        <!--右侧-->
        <div class="user_right">
            <h1>提现</h1>
            <p class="border_bot">帐户余额（元）：<b><?php echo $balance['data']['balance']?></b></p>
            <div class="tra_note cztx">
                <ul class="tab_title ">
                    <a href='<?php echo site_url('user/user/recharge');?>'><li>充值<font class="fr">|</font></li></a>
                    <a href='<?php echo site_url('user/user/recharge_jl');?>'><li>充值记录<font class="fr">|</font></li></a>
                    <li class="active">提现<font class="fr">|</font></li>
                    <a href='<?php echo site_url('user/user/withdrawals_jl');?>'><li>提现记录</li></a>
                </ul>
                <ul class="tab_con">
                    <li class="active">
                    <form id="tx" action="" method="" accept-charset="utf-8">    
					
                        <div class="zysx">
						<?php if($balance['status']!='10001'): ?>
                            <p>温馨提示：16点前申请提现款项，提现金额T+1个工作日到帐，16点后申请的提现款项T+2个工作日到帐，每笔提现扣除银行转账费2块。如有疑问请联系客服，客服电话4007-918-333。
                            </p>
						<?php else:?>
							<p><?php echo $msg?></p>
						<?php endif;?>
                        </div>
						<div <?php if($balance['status']=='10001'):?>style="display:none"<?php endif;?>>
                        <div class="qx_yhk">
                            <div class="left">提现的银行卡：</div>
                            <div class="right"><img src="<?php echo base_url('assets/images/bank/'.$bank['data']['code'].'.png')?>" style="float:left"><font style="text-indent:0px">尾号：<?php echo substr($user_bank['data']['account'],-4);?></font></div>
                        </div>
                        <div class="qx_inp">
                            <div class="left">请输入提现金额：</div>
                            <div class="right">
                                <input class="txje" type="text" / placeholder="最大可提现100元">
                                <div class="tip_qx"></div>
                            </div>
                        </div>
                        <div class="qx_inp">
                            <div class="left">输入语音验证码：</div>
                            <div class="right">
                                <input class="tx_yzm" type="text" placeholder="输入验证码" / >
                                <input class="hqyzm sms" type="button" value="短信验证码" />
                                <input class="hqyzm voice" type="button"
                                       data-wait-time="<?php echo item("sms_space_time")?item("sms_space_time"):60; ?>"
                                       data-last-time="<?php echo profile("voice_last_send_time")?profile("voice_last_send_time"):0; ?>"
                                       value="语音验证码" />
                                <div class="tip_qx_1"></div>
                            </div>
                        </div>
                        <div class="qx_inp">
                            <div class="left">输入资金密码：</div>
                            <div class="right">
                                <input class="tx_zjmm" type="password" / placeholder="输入资金密码">
                                <div class="tip_qx_2"></div>
                            </div>
                        </div>
                        <p class="but_qx"><button class="but_qx_but" type="button" id="sub">确认提现<i></i></button></p>
                        <div class="pop"><img src="<?php echo base_url('assets/images/user/txcg.png')?>" height="135" width="165"></div><!--不可以用图片做-->
						<input type="hidden" value="<?php echo (profile('mobile'))?profile('mobile'):'123'?>" id="mobile"/>
						</div>
                    </form>
                    </li>
                </ul>
            </div>
        </div>
        <!--右侧-->
    </div>
    <!--底部-->
	<?php $this->load->view('common/footer');?>
    <!--底部-->       

<!--userjs start-->
<script type="text/javascript">
    seajs.use(['jquery','sys','jqform','validator'],function(){
        $('.sms').click(function () {
            // body...
			dxdjs($(this));
			var mobile = $('#mobile').val();
			$.post('/index.php/user/user/send_sms?action=transfer&mobile='+mobile,{},function(result){
				result = JSON.parse(result);
				if(result.status=='10000'){
				dxdjs($(this));
				var text = '注意查收来自手机'+mobile.substring(0, 3) + "*****" + mobile.substring(8, 11)+'的短信';
				$('.tip_qx_1').html(text);
				}else{	
					var text = result.msg;
					$('.tip_qx_1').html(text);		
				}
				
			});
            
        });
        $(function(){
            $('.voice').send_sms('voice','<?php echo profile("mobile");?>','transfer',<?php echo item("sms_space_time")?item("sms_space_time"):60; ?>,'<?php echo profile("sms_last_send_time")?time()-profile("sms_last_send_time"):0; ?>');
        });
        var pit_1=0,pit_2=0,pit_3=0;
        $(".but_qx_but").click(function (){		
            if((pit_1+pit_2+pit_3)==3){
				var authcode = $('.tx_yzm').val();
				var security = $('.tx_zjmm').val();
				var amount = $('.txje').val();
				var contion = '?authcode='+authcode+'&security='+security+'&amount='+amount;
				$.post('/index.php/user/user/user_transfer'+contion,{},function(result){
					result = JSON.parse(result);
					if(result.status=='10000'){
						$(".pop").fadeIn(2000).fadeOut(2000);
						window.location.href="/index.php/user/user/withdrawals_jl"; 
						return;
					}
					if(result.status=='10002'){
						//跳登录页面
						alert(result.msg);
						return;
					}
					if(result.status=='10003'){
						//交易密码未设置
						alert(result.msg);
						return;
					}
					if(result.status=='10004'){
						//余额不足
						var text = '<i class="icon-tip-no"></i>'+result.msg;
						$('.tip_qx').html(text);
						return;
					}
					if(result.status=='10005'){
						var text = '<i class="icon-tip-no"></i>'+result.msg;
						$('.tip_qx_2').html(text);
						return;
					}
					if(result.status=='10006'){
						var text = '<i class="icon-tip-no"></i>'+result.msg;
						$('.tip_qx_1').html(text);
						return;
					}
				 });
                
            }
        });
                $('#tx').validate({
                    '.txje': {
                        filtrate: 'required number',
                        callback: function (index) {
                            var tip = this.parent().find('.tip_qx').eq(0),
                                text = '';
                            if (index === 0) {
                                text = '<i class="icon-tip-no"></i>请输入提现金额';
                            } else if (index === 1) {
                                text = '<i class="icon-tip-no"></i>请输入正确金额';
                            } else if ($('.txje').val()<100) {
                                text = '<i class="icon-tip-no"></i>最少提现100元';
                            } else {
                               pit_1=1;                                 
                            }
                            tip.html(text);
                        }
                    },
                    '.tx_yzm': {
                        filtrate: 'required',
                        callback: function (index) {
                            var tip = this.parent().find('.tip_qx_1').eq(0),
                                text = '';
                            if (index === 0) {
                                text = '<i class="icon-tip-no"></i>请输入验证码';
                            } else {
                                //验证验证码
                                 pit_2=1;   
                            }
                            tip.html(text);
                        }
                    },
                    '.tx_zjmm': {
                        filtrate: 'required',
                        callback: function (index) {
                            var tip = this.parent().find('.tip_qx_2').eq(0),
                                text = '';
                            if (index === 0) {
                                text = '<i class="icon-tip-no"></i>请输入资金密码';
                            } else {
                                //验证验证码
                                 pit_3=1;   
                               
                            }
                            tip.html(text);
                        }
                    }
                });
    });
	//$('#sub').click(function(){
		
	//});
</script>
</body> 
</html>