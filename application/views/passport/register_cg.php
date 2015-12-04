<!DOCTYPE html>
<html>
<head>
    <title>聚雪球_网加金服_P2P理财首选互联网金融P2P网贷平台_100元即可投资!</title>
	<!--	加载头部样式文件-->
	<?php $this->load->view('common/head_file'); ?>
</head>
<body>
<!--header-->
<!--	加载头部文件-->
<?php $this->load->view('common/head'); ?>
<!--header-->
<div class="row">
    <div class="reg_cg">
        <div class="left fl">
            <img src="/assets/images/user/xrt.png" alt="">
        </div>
        <div class="right fl">
            <p class="cg">已经注册成功啦～！</p>
            <p class="wz"><span><?php echo secret($mobiles,4); ?></span>，<font>98%</font>的用户选择了去提升自己的安全等级。</p>
            <p class="but">
	            <button type="button" onclick="window.location.href='<?php echo site_url('user/user/account_information'); ?>';">现在去提升<i></i></button>
	            <a href="<?php echo site_url(); ?>">稍后再去</a>
            </p>
        </div>
    </div>
</div>
<!--footer-->
    <?php $this->load->view('common/footer'); ?>
<!--footer-->

</body>
</html>