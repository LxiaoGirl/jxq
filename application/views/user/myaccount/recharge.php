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
        <a href="">首页</a>&nbsp;>&nbsp;<a href="">我的账户</a>&nbsp;>&nbsp;<a href="">我的雪球</a>
    </div>
    <div class="row user">
        <!--左侧通用-->
		<?php $this->load->view('common/user_left');?>
        <!--右侧-->
        <div class="user_right">
            <div class="black_bg"></div>
            <h1>充值</h1>
            <p class="border_bot">帐户余额（元）：<b id="balance"><?php echo $balance; ?></b></p>
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
                            <p><input id="recharge_amount" type="text" name=""/> <a href="javascript:void(0);" id="qrcz" target="_blank">确认充值</a></p>
                            <p><input type="checkbox" name="" checked id="recharge_agree"/>我同意<a href="<?php echo site_url(''); ?>">《聚雪球账户资金管理协议》</a></p>
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
<script type="text/javascript" src="<?php echo base_url('assets/js/plugins/jquery.date_input.pack.js')?>"></script> 
<script type="text/javascript">
    seajs.use(['jquery','sys'],function(){
        var recharge_min = <?php echo $recharge_min; ?>;
        //为输入框绑定事件
        $('#recharge_amount').bind('keyup',function(){
            if( ! $('#recharge_agree').prop('checked')){
                return false;
            }
            if(isNaN($(this).val())){
                $(this).val('');
            }else{
                if($(this).val() >= recharge_min){
                    $('#qrcz').attr('href','<?php echo site_url('pay/pay/index'); ?>' + '?amount=' + $(this).val()+'&recharge_no=<?php echo $recharge_no; ?>');
                }else{
                    $('#qrcz').attr('href','javascript:void(0);');
                }
            }

        });
        //为协议绑定事件
        $('#recharge_agree').bind('click',function(){
            if( $('#recharge_agree').prop('checked')){
                if($('#qrcz').attr('href') == 'javascript:void(0);' && !isNaN( $('#recharge_amount').val()) && $('#recharge_amount').val() >= recharge_min)
                $('#qrcz').attr('href','<?php echo site_url('pay/pay/index'); ?>' + '?amount=' + $('#recharge_amount').val()+'&recharge_no=<?php echo $recharge_no; ?>');
            }else{
                if($('#qrcz').attr('href') != 'javascript:void(0);')$('#qrcz').attr('href','javascript:void(0);');
            }
        });
        // 自动刷新订单结果
        var recharge_auto_refresh = function(recharge_no){
            var recharge_fresh_time = 0;
            var refresh_recharge = function(){
                $.post('<?php echo site_url('user/user/ajax_recharge_auto_refresh'); ?>',{'recharge_no':'<?php echo $recharge_no; ?>'},function(rs){
                    if(rs.status == '10000'){
                        $('#balance').html(rs.data);
                        clearTimeout(recharge_fresh_time);
                    } else if(rs.status == '10001'){
                        recharge_fresh_time = setTimeout(function(){refresh_recharge();},1000);
                    }else{
                        //什么也不做了
                    }
                },'json');
            };
            //5秒后开始执行刷新
            recharge_fresh_time = setTimeout(function(){refresh_recharge();},7000);
        };
        $('#qrcz').click(function(){
            if( ! $('#recharge_agree').prop('checked')){
                wsb_alert('你必须同意聚雪球账户资金管理协议才能充值!');
                return false;
            }
            if( !isNaN($('#recharge_amount').val()) && $('#recharge_amount').val() >= recharge_min){
                $('.black_bg').fadeIn();
                $('.cz_pop').fadeIn();
                recharge_auto_refresh();
            }else{
                wsb_alert('请输入'+recharge_min+'及以上充值金额!');
            }
        });

        $('.cz_pop').find('.close').click(function(){
            $('.black_bg').fadeOut();
            $('.cz_pop').fadeOut();
        });
        $('.cz_pop').find('button').click(function(){
            $('.black_bg').fadeOut();
            $('.cz_pop').fadeOut();
        });
    });
</script>
</body> 
</html>