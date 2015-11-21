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
    <!--银行卡管理-->
    <!--user start-->
    <div class="user_nav row">
        <a href="">首页</a>&nbsp;>&nbsp;<a href="">账户设置</a>&nbsp;>&nbsp;<a href="">银行卡管理</a>
    </div>
    <div class="row user">
        <!--左侧通用-->
        <?php $this->load->view('common/user_left');?>
        <!--左侧通用-->
        <!--右侧-->
        <div class="user_right">
            <div class="black_bg"></div>
            <h1>银行卡管理<a href="">常见问题</a></h1>
            <ul class="yhk">
			<?php if($bank['status']==10001):?>
                <li class="no_binding">
                    <div class="fl">
                        <div class="addcard">+</div>
                    </div>
                    <div class="fr">
                        <p>呃～这儿什么都没有</p>
                        <p class="font_18">快去<font class="addcard">绑定一张银行卡</font>吧</p>
                    </div>
                </li>
				<?php else:?>
                <li>
                    <div class="top"><i class="zhaoshang"></i><span>招商银行</span><!--<font>单次0.5万元 单日0.5万元 当月5万元</font>--></div>
                    <div class="center">
                        <p>开户姓名：<?php echo $bank['data']['real_name']?></p>
                        <p>银行卡号：<?php echo secret($bank['data']['account'],11)?></p>
                    </div>            
                    <div class="bottom">
                        <font class="fr">解除绑定</font>
                    </div>
                </li>
				<?php endif;?>
            </ul>
            <!--银行卡弹出-->
            <div class="yhk_pop user_data_pop">
                <div class="title">
                    <span>银行卡绑定</span><font class="fr close">×</font>
                </div>
                <form id="myform" action="" method="">
                <div class="yhk_popbody">
                    <div class="yhkxz_left fl">
                        选择银行：
                    </div>
                    <div class="yhkxz_right fl">
					<?php if($all_bank['status']=='10000'):?>
						<?php if(!empty($all_bank['data'])):?>
						<?php foreach($all_bank['data'] as $k => $v):?>
                        <div class="yhsection  active" id="<?php echo $v['bank_id'];?>"><img src="<?php echo base_url('assets/images/bank/'.$v['code'].'.png')?>"></div>
						<?php endforeach;?>
						<?php endif;?>
						<?php endif;?>
                    </div>
					
                    <div class="yhkxz_left_xian fl"></div>
                    <div class="yhkxz_right_xian fr"></div>
					<div class="tip_qx"></div>
                    <input class="yhks" type="hidden" name="" value="" />
                    <div class="yhk_left fl">
                        银行卡号：
                    </div>
                    <div class="yhk_right fl">
                        <input class="yhkh_inp" type="text" name="account" value="" placeholder="请输入银行卡号"/>
                    </div>
					<div class="tip_qx_1"></div>
                   <!--<div class="yhk_left fl">
                        预留手机号：
                    </div>
                    <div class="yhk_right fl">
                        <input class="yhk_sjh_inp" type="text" name="user_1" value="" placeholder="请输入预留手机号"/>
                    </div>-->
                    <div class="but"><button type="button" id="sub">绑定</button></div>
                </div>    
                </form>
            </div>
            <!--银行卡弹出-->
            <!--成功弹出-->
            <div class="bdyhk_cg">
                <div class="title">
                    <span>修改昵称</span><font class="fr close">X</font>
                </div>
                <div class="popbody tc">
                    <div class="p smrz_p">
                        <div class="fl tr">
                            <img src="<?php echo base_url('assets/images/user/xrt.png')?>">
                        </div>
                        <div class="fr tl">
                            <div class="popbody_p shb">银行卡添加成功</div>
                            <div class="popbody_p xb">飞翔的雪球<font>，您已成功添加尾号为</font>6413<font>的</font>招商银行<font>银行卡！</font></font></div>
                        </div>
                    </div>
                    <button type="" class="close" >完成</button>
                </div>
            </div>
            <!--成功弹出-->
        </div>
        <!--右侧-->
    </div>
    <!--银行卡管理-->
<!--footer-->
<?php $this->load->view('common/footer');?> 
<!--footer-->
<!--userjs start-->
<script type="text/javascript">
    seajs.use(['jquery','sys','validator'],function(){
        yhkxz_tab($('.yhkxz_right'),$('.yhks'));
        pop($('.addcard'),$('.yhk_pop'),$('.yhk_pop').find('.close'));
        //pop_sub($('.yhk_pop').find('.but'),$('.bdyhk_cg'),$('.bdyhk_cg').find('.close'));
		var submit_flag = false;
		$('#sub').click(function(){
			var $elements = $('.active');
			var len = $elements.length;
			if(len!=1){
				var text = '请选择所属银行！';
				$('.tip_qx').html(text);
			}else{
				
				
			$('.tip_qx').html('');
			var bank_id = $('.active').attr('id');
			$.post('/index.php/user/user/user_transfer'+contion,{},function(result){
				
			});
			}
		})
		 //验证银行卡号
    $("input[name='account']").bind('blur',function(){
        var account_reg = /^[1-9][0-9]{5,}$/;
        if (!account_reg.test($("input[name='account']").val())) {
			var text = '请输入正确格式的银行账号！';
			$('.tip_qx_1').html(text);
            return false;
        }
		//验证测试不了
//        $.post('index.php/user/user/ajax_check_card_bin', {'account': $("input[name='account']").val()}, function (rs) {
//            if (rs.status == '0000') {
//                if (rs.card_type == '3') {
//					var text = '不支持信用卡充值，请更换借记卡进行充值！';
//					$('.tip_qx_1').html(text);
//                } else {
//                    //核对银行名称
//                    if($("[bank_name='"+rs.bank_name+"']").length == 1 && $("#btn_yinhang span").text() != rs.bank_name){
//                        $("[bank_name='"+rs.bank_name+"']").parent().click()
//                    }
//                    submit_flag = true;
//                }
//            } else {
//                my_alert(rs.ret_msg + ',请检查你输入的卡号是否正确！');
//            }
//        }, 'json');
    });
			
    });
</script>
<!--userjs end-->
</body>   
</html>