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
        <a href="">首页</a>&nbsp;>&nbsp;<a href="">我的账户</a>&nbsp;>&nbsp;<a href="">我的雪球</a>
    </div>
    <div class="row user">
        <!--左侧通用-->
		<?php $this->load->view('common/user_left');?>
        <!--右侧-->
        <div class="user_right">
            <h1>充值</h1>
            <p class="border_bot">帐户余额（元）：<b>0.00</b></p>
            <div class="tra_note">
                <ul class="tab_title ">
                    <li class="active">充值<font class="fr">|</font></li>
                    <a href='<?php echo site_url('user/user/recharge_jl');?>'><li>充值记录<font class="fr">|</font></li></a>
                    <a href='<?php echo site_url('user/user/withdrawals');?>'><li>提现<font class="fr">|</font></li></a>
                    <a href='<?php echo site_url('user/user/withdrawals_jl');?>'><li>提现记录</li></a>
                </ul>
                <ul class="tab_con">
                    <li class="active">
                        <div class="section">
                            <span class="select spse">全部</span>|
                            <span class="spse">一个月内</span>|
                            <span class="spse">三个月内</span>|
                            <span class="spse">半年内</span>|
                            <span class="spse">一年内</span>
                            <font style="margin-left:30px;">选择日期：</font><input type="text" class="date_picker_1">
                            <font>至&nbsp;&nbsp;</font><input type="text" class="date_picker_2">
                            <button>查询</button>
                        </div>
                        <p class="title"><span class="wid180">流水号</span><span class="wid128">金额（元）</span><span class="wid105">手续费（元）</span><span class="wid166">时间</span><span class="wid156">账户信息</span><span class="wid160">当前状态</span></p>
                        <p class="lie"><span class="wid180">R15092146247467</span><span class="wid128">100,000,000,000.00</span><span class="wid105">2,000.00</span><span class="wid166">2015-09-21 11:38:32</span><span class="wid156">我不知道这是啥</span><span class="wid160 green">提现成功</span></p>
                        <p class="lie"><span class="wid180">R15092146247467</span><span class="wid128">100,000,000,000.00</span><span class="wid105">2,000.00</span><span class="wid166">2015-09-21 11:38:32</span><span class="wid156">我不知道这是啥</span><span class="wid160 red">提现中</span></p>
                        <p class="lie"><span class="wid180">R15092146247467</span><span class="wid128">100,000,000,000.00</span><span class="wid105">2,000.00</span><span class="wid166">2015-09-21 11:38:32</span><span class="wid156">我不知道这是啥</span><span class="wid160 red">提现中</span></p>
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
</script>
</body> 
</html>