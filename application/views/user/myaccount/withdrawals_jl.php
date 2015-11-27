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
        <a href="">首页</a>&nbsp;>&nbsp;<a href="">我的账户</a>&nbsp;>&nbsp;<a href="">提现记录</a>
    </div>
    <div class="row user">
        <!--左侧通用-->
		<?php $this->load->view('common/user_left');?>
        <!--右侧-->
        <div class="user_right">
            <h1>提现</h1>
            <p class="border_bot">帐户余额（元）：<b><?php echo $balance['data']['balance']?></b></p>
            <div class="tra_note">
                <ul class="tab_title ">
                    <a href='<?php echo site_url('user/user/recharge');?>'><li>充值<font class="fr">|</font></li></a>
                    <a href='<?php echo site_url('user/user/recharge_jl');?>'><li>充值记录<font class="fr">|</font></li></a>
                    <a href='<?php echo site_url('user/user/withdrawals');?>'><li>提现<font class="fr">|</font></li></a>
                    <li class="active">提现记录</li>
                </ul>
                <ul class="tab_con">
                    <li class="active">
                        <div class="section">
                            <a href='<?php echo site_url('user/user/withdrawals_jl');?>'><span class="spse  <?php echo (@$_GET['limit_time']=='')?'select':''?> ">全部</span></a>|
                            <a href='<?php echo site_url('user/user/withdrawals_jl').'?limit_time=1';?>'><span class="spse  <?php echo (@$_GET['limit_time']==1)?'select':''?> ">一个月内</span></a>|
                            <a href='<?php echo site_url('user/user/withdrawals_jl').'?limit_time=2';?>'><span class="spse  <?php echo (@$_GET['limit_time']==2)?'select':''?> ">三个月内</span></a>|
                            <a href='<?php echo site_url('user/user/withdrawals_jl').'?limit_time=3';?>'><span class="spse  <?php echo (@$_GET['limit_time']==3)?'select':''?> ">半年内</span></a>|
                            <a href='<?php echo site_url('user/user/withdrawals_jl').'?limit_time=4';?>'><span class="spse  <?php echo (@$_GET['limit_time']==4)?'select':''?> ">一年内</span></a>
                            <font style="margin-left:30px;">选择日期：</font><input type="text" class="date_picker_1" id="start" value="<?php echo (isset($_GET['start']))?$_GET['start']:'';?>">
                            <font>至&nbsp;&nbsp;</font><input type="text" class="date_picker_2"  id="end" value="<?php echo (isset($_GET['end']))?$_GET['end']:'';?>">
                            <button  id="sub">查询</button>
                        </div>
                        <p class="title"><span class="wid180">流水号</span><span class="wid128">金额（元）</span><span class="wid105">手续费（元）</span><span class="wid166">时间</span><span class="wid156">账户信息</span><span class="wid160">当前状态</span></p>
						<?php if($status=='10000'):?>
						<?php if(!empty($data['data'])):?>
						<?php foreach($data['data'] as $k => $v):?>
                        <p class="lie"><span class="wid180"><?php echo $v['transaction_no'];?></span><span class="wid128"><?php echo $v['amount'];?></span><span class="wid105"><?php echo $v['charge'];?></span><span class="wid166"><?php echo date('Y-m-d H:i',$v['add_time']);?></span><span class="wid156"><?php echo $v['remarks'];?></span><span class="wid160 green"><?php echo $v['status'];?></span></p>
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
<script src="<?php echo base_url('assets/js/jquery/jquery-1.8.5.min.js')?>"></script>
<script type="text/javascript" src="<?php echo base_url('assets/js/plugins/jquery.date_input.pack.js')?>"></script> 
<script type="text/javascript">
    seajs.use(['jquery','sys'],function(){

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