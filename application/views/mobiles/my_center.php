<!DOCTYPE html>
<html>
<head lang="en">
    <title>个人中心</title>
    <?php $this->load->view('common/mobiles/app_head') ?>
</head>
<body>
<!-- 公共头部导航-->
<?php $this->load->view('common/mobiles/app_common_head') ?>

<div class="container-fluid" style="padding:0;">
    <div class="self_top"
         <?php if (profile('uid') > 0): ?>onclick="window.location.href='<?php echo site_url('mobiles/home/profile'); ?>';" <?php else: ?> onclick="window.location.href='<?php echo site_url('mobiles/home/login'); ?>';" <?php endif; ?>>
        <?php if (profile('uid') > 0): ?>
            <img src="<?php echo((profile('avatar') != '') ? $this->c->get_oss_image(profile('avatar')) : '/assets/images/app/mrtx.png'); ?>"
                 height="80">
            <p><?php echo profile('user_name'); ?></br><?php echo profile('mobile'); ?></p>
        <?php else: ?>
            <img src="/assets/images/app/mrtx.png" height="80">
            <p>用户名</br>电话</p>
        <?php endif; ?>
    </div>
    <div class="self_center">
        <a class="chozhi"
           href="javascript:check_to_login('<?php echo site_url('mobiles/home/recharge'); ?>',true);">充值</a>
        <a class="tixian"
           href="javascript:check_to_login('<?php echo site_url('mobiles/home/transfer'); ?>',true);">提现</a>
    </div>
    <div class="self_con">
        <p onclick="check_to_login('<?php echo site_url('mobiles/home/my_balance'); ?>');"><a
                href="javascript:void(0);">可用余额<font>></font><font class="my_balance list-value">0元</font></a></p>

        <p onclick="check_to_login('<?php echo site_url('mobiles/home/my_income'); ?>');"><a
                href="javascript:void(0); ?>');">累计收益<font>></font><font class="all_income list-value">0元</font></a>
        </p>

        <p onclick="check_to_login('<?php echo site_url('mobiles/home/my_card'); ?>',true);"><a
                href="javascript:void(0);">我的银行卡<font>></font></a></p>
                
        <p onclick="window.location.href='<?php echo site_url('mobiles/intermediary/index'); ?>';"><a
                href="javascript:void(0);">邀请好友<font>></font></a></p>

        <p onclick="check_to_login('<?php echo site_url('mobiles/home/my_project'); ?>');"><a
                href="javascript:void(0);">已投项目<font>></font></a></p>

        <p onclick="check_to_login('<?php echo site_url('mobiles/home/my_interest'); ?>');"><a
                href="javascript:void(0); ?>');">回款计划<font>></font></a></p>

        <p onclick="check_to_login('<?php echo site_url('mobiles/home/my_cash_log'); ?>');"><a
                href="javascript:void(0);">交易明细<font>></font></a></p>

        <p onclick="check_to_login('<?php echo site_url('mobiles/home/my_integral'); ?>');"><a
                href="javascript:void(0);">我的雪球<font>></font></a></p>
                
        <p onclick="check_to_login('/index.php/mobiles/home/redbag');"><a
                href="javascript:void(0);">我的红包<font>></font></a></p>
    </div>
</div>

</body>
<?php $this->load->view('common/mobiles/app_footer') ?>
<script>
    $(function(){
        $('.self_con').list_data({
            list_one:true,
            data:'/index.php/mobiles/home/my_center',
            show_loading:true,
            btn:true,
            value_func:{
                'my_balance':function(v){return v+'元';},
                'all_income':function(v){return v+'元';}
            }
        });
    });
</script>
</html>