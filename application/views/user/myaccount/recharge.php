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
            <div class="black_bg"></div>
            <h1>充值</h1>
            <p class="border_bot">帐户余额（元）：<b>0.00</b></p>
            <div class="tra_note cztx">
                <ul class="tab_title ">
                    <li class="active">充值<font class="fr">|</font></li>
                    <a href='<?php echo site_url('user/user/recharge_jl');?>'><li>充值记录<font class="fr">|</font></li></a>
                    <a href='<?php echo site_url('user/user/withdrawals');?>'><li>提现<font class="fr">|</font></li></a>
                    <a href='<?php echo site_url('user/user/withdrawals_jl');?>'><li>提现记录</li></a>
                </ul>
                <ul class="tab_con">
                    <li class="active">
                    <form id="tx" action="" method="" accept-charset="utf-8">                    
                        <div class="zysx">
                            <p>温馨提示：网加禁止信用卡充值、套现等行为，一经发现将予以处罚，包括但不限于：限制收款、冻结账户、永久停止服务，并会影响银行征信记录。网上银行充值过程中请耐心等待，充值成功后，请不要关闭浏览器，充值成功后返回网加，充值金额才能打入您的账号。如有问题，请联系客服。
                            </p>
                        </div>
                        <div class="fl rechar">请输入充值金额：</div>
                        <div class="fr rechar">
                            <p><input type="text" name=""/> <button type="button" id="qrcz">确认充值</button></p>
                            <p><input type="checkbox" name=""/>我同意<a href="">《聚雪球账户资金管理协议》</a></p>
                        </div>
                        <div class="cz_pop">
                            <div class="title">
                                <span>充值结果</span><font class="fr close">×</font>
                            </div>
                            <div class="popbody">
                                <button type="button">充值成功</button><button class="czsb" type="button">充值失败</button>
                            </div>
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
<script src="<?php echo base_url('assets/js/jquery/jquery-1.8.5.min.js')?>"></script>
<script type="text/javascript" src="<?php echo base_url('assets/js/plugins/jquery.date_input.pack.js')?>"></script> 
<script type="text/javascript">
    seajs.use(['jquery','sys'],function(){
        $('#qrcz').click(function(){
            $('.black_bg').fadeIn();
            $('.cz_pop').fadeIn();
        })
        $('.cz_pop').find('.close').click(function(){
            $('.black_bg').fadeOut();
            $('.cz_pop').fadeOut();
        })
        $('.cz_pop').find('button').click(function(){
            $('.black_bg').fadeOut();
            $('.cz_pop').fadeOut();
        })
    });
    $('.date_picker_1').date_input();
    $('.date_picker_2').date_input();
</script>
</body> 
</html>