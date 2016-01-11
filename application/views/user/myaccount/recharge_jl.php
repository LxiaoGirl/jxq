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
        <a href="/index.php">首页</a>&nbsp;>&nbsp;<a href="/index.php/user/user/account_home">我的账户</a>&nbsp;>&nbsp;<a href="javascript:void(0);">充值记录</a>
    </div>
    <div class="row user">
        <!--左侧通用-->
		<?php $this->load->view('common/user_left');?>
        <!--右侧-->
        <div class="user_right">
            <h1>充值</h1>
            <p class="border_bot">帐户余额（元）：<b id="balance"><?php echo $balance['data']['balance']?></b></p>
            <div class="tra_note">
                <ul class="tab_title ">
					<a href='<?php echo site_url('user/user/recharge');?>'><li>充值<font class="fr">|</font></li></a>
                    <li class="active">充值记录<font class="fr">|</font></li>
                    <a href='<?php echo site_url('user/user/withdrawals');?>'><li>提现<font class="fr">|</font></li></a>
                    <a href='<?php echo site_url('user/user/withdrawals_jl');?>'><li>提现记录</li></a>
                </ul>
                <ul class="tab_con">
                    <li class="active">
                        <div class="section">
                            <a href="<?php echo site_url('user/user/recharge_jl')?>"><span class="spse <?php echo (@$_GET['time_limit']=='')?'select':''?> ">全部</span></a>|
                            <a href="<?php echo site_url('user/user/recharge_jl').'?time_limit=1'?>"><span class="spse <?php echo (@$_GET['time_limit']==1)?'select':''?>">一个月内</span></a>|
                            <a href="<?php echo site_url('user/user/recharge_jl').'?time_limit=2'?>"><span class="spse <?php echo (@$_GET['time_limit']==2)?'select':''?>"> 三个月内</span></a>|
                            <a href="<?php echo site_url('user/user/recharge_jl').'?time_limit=3'?>"><span class="spse <?php echo (@$_GET['time_limit']==3)?'select':''?>"> 半年内</span></a>|
                            <a href="<?php echo site_url('user/user/recharge_jl').'?time_limit=4'?>"><span class="spse <?php echo (@$_GET['time_limit']==4)?'select':''?>"> 一年内</span></a>
                            <font style="margin-left:30px;">选择日期：</font><input type="text" class="date_picker_1 ifhav" id="start" value="<?php echo (isset($_GET['start']))?$_GET['start']:'';?>">
                            <font>至&nbsp;&nbsp;</font><input type="text" class="date_picker_2 ifhav"  id="end" value="<?php echo (isset($_GET['end']))?$_GET['end']:'';?>">
                            <button id="sub" class="ls">查询</button>
                        </div>
                        <p class="title"><span class="wid201">流水号</span><span class="wid149">金额（元）</span><span class="wid187">时间</span><span class="wid177">备注</span><span class="wid181">当前状态</span></p>
						<?php if($status=='10000'):?>
						<?php if(!empty($data['data'])):?>
						<?php foreach($data['data'] as $k => $v):?>
						<p class="lie"><span class="wid201"><?php echo $v['recharge_no'];?></span><span class="wid149"><?php echo $v['amount'];?></span><span class="wid187"><?php echo date('Y-m-d H:i',$v['add_time']);?></span><span class="wid177"><?php echo $v['remarks'];?></span><span class="wid181 green"><?php echo $v['status'];?><?php if($v['type'] == 2 && $v['status'] == '充值失败'): ?>[<a href="javascript:void(0);" style="text-decoration: underline;" class="recharge-refresh ajax-submit-button" data-recharge-no="<?php echo authcode($v['recharge_no']); ?>" data-loading-msg="刷新中...">刷新</a>] <?php endif; ?></span></p>
						<?php endforeach;?>
						<?php else:?>
						<?php echo  $msg?>
						<?php endif;?>
						<?php else:?>
						<?php  echo  $msg?>
						<?php endif;?>
						<?php echo $links;?>
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
<script type="text/javascript" src="<?php echo base_url('assets/js/plugins/jquery.date_input.pack.js')?>"></script> 
<script type="text/javascript">
    seajs.use(['jquery','sys'],function(){
        //INPUT框变色
        $('.ifhav').focus(function(){
            $(this).addClass('hav');
        });
        $('.ifhav').blur(function(){
            if($.trim($(this).val())==''){
                $(this).removeClass('hav');
            }
        });
        $(function () {
            var refresh_func = function(obj){
                $.post('/index.php/user/user/ajax_recharge_auto_refresh',{'recharge_no':obj.data('rechargeNo')},function(rs){
                    if(rs.status == '10000'){
                        $("#balance").text(rs.data);
                        obj.parent().html('充值成功');
                        wsb_alert('充值已成功',1);
                    }else if(rs.status == '10002' || rs.status == '10003'){
                        wsb_alert(rs.msg,2);
                    }else{
                        wsb_alert('充值尚未成功,如确认已充值扣费请稍后查询或联系客服人员!',3);

                        $(".recharge-refresh").unbind('click').bind('click',function(){
                            $(".recharge-refresh").unbind('click');
                            refresh_func($(this));
                        });
                    }
                },'json');
            };
            $(".recharge-refresh").unbind('click').bind('click',function(){
                $(".recharge-refresh").unbind('click');
                refresh_func($(this));
            });
        });
    });
    $('.date_picker_1').date_input();
    $('.date_picker_2').date_input();
	$('#sub').click(function(){
		var start = $('#start').val();
		var end = $('#end').val();
		var contion = '';
		
		if(start==''){
			alert("请选择开始搜索时间！");
			return;
		}
		if(end==''){
			alert("请选择结束搜索时间！");
			return;
		}
		contion = '?start='+start+'&end='+end;
		window.location.href=window.location.href.substr(0,window.location.href.indexOf("?"))+contion; 
	});
</script>
</body> 
</html>