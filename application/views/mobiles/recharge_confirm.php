<!DOCTYPE html>
<html>
<head lang="en">
    <title>充值</title>
    <?php $this->load->view('common/mobiles/app_head') ?>
    <style type="text/css">
        .container-fluid {
            margin-top: 8px;
        }
    </style>
</head>
<body>
<!-- 公共头部导航-->
<div class="header">
    <div class="heder">
        <?php if (in_array($this->uri->uri_string(), array('', 'mobiles/home/index', 'mobiles/home', 'mobiles', 'mobiles/home/project_category', 'mobiles/home/borrow_type'))): ?>
            <img class="left_head" src="/assets/images/app/top_nav/top_nav_1.png"
                 onclick="window.location.href='<?php echo $this->session->userdata('uid') > 0 ? site_url('mobiles/home/my_center') : site_url('mobiles/home/login'); ?>';"/>
        <?php else: ?>
            <a href="<?php echo site_url('mobiles/home/my_center'); ?>"> <img class="left_head"
                                                                              src="/assets/images/app/top_nav/top_nav_back.png"/></a>

        <?php endif; ?>
        <img class="logo_head" src="/assets/images/app/top_nav/top_nav_2.png"/>
        <img class="right_head" src="/assets/images/app/top_nav/top_nav_3.png"/>
    </div>
    <div class="right_nav">
        <div class="le_harf"></div>
        <div class="ri_harf">
            <div class="nav_box">
                <div class="p nav1">
                    <?php if (profile('uid') > 0): ?>
                        <a href="<?php echo site_url('mobiles/home/my_center'); ?>"><img
                                src="/assets/images/app/top_nav/top_nav_4.png">&nbsp;&nbsp;<?php echo profile('user_name'); ?>
                        </a>
                    <?php else: ?>
                        <a href="<?php echo site_url('mobiles/home/login'); ?>"><img
                                src="/assets/images/app/top_nav/top_nav_4.png">&nbsp;&nbsp;登录/注册</a>
                    <?php endif; ?>
                    <a class="self_cen"
                       href="<?php echo $this->session->userdata('uid') > 0 ? site_url('mobiles/home/my_center') : site_url('mobiles/home/login'); ?>"><font>￥我的钱包</font></a>
                </div>
                <a href="<?php echo site_url('mobiles/home/index'); ?>">
                    <div class="p <?php if ($this->router->fetch_method() == 'index'): ?>select<?php endif; ?>"><img
                            src="/assets/images/app/top_nav/top_nav_5.png">&nbsp;&nbsp;首页
                    </div>
                </a>
                <a href="<?php echo site_url('mobiles/home/project_category'); ?>">
                    <div
                        class="p <?php if (in_array($this->router->fetch_method(), array('project_category', 'project_list', 'project_detail', 'project_invest', 'project_confirm', 'project_success'))): ?>select<?php endif; ?>">
                        <img src="/assets/images/app/top_nav/top_nav_6.png">&nbsp;&nbsp;我要投资
                    </div>
                </a>
                <a href="<?php echo site_url('mobiles/home/borrow_type'); ?>">
                    <div
                        class="p <?php if (in_array($this->router->fetch_method(), array('borrow_type', 'borrow', 'borrow_success'))): ?>select<?php endif; ?>">
                        <img src="/assets/images/app/top_nav/top_nav_7.png">&nbsp;&nbsp;我要借款
                    </div>
                </a>
                <a href="<?php echo site_url('mobiles/home/ptjs'); ?>">
                    <div class="p <?php if ($this->router->fetch_method() == 'ptjs'): ?>select<?php endif; ?>"><img
                            src="/assets/images/app/top_nav/top_nav_8.png">&nbsp;&nbsp;平台介绍
                    </div>
                </a>
                <a href="<?php echo site_url('mobiles/home/aqbz'); ?>">
                    <div class="p <?php if ($this->router->fetch_method() == 'aqbz'): ?>select<?php endif; ?>"><img
                            src="/assets/images/app/top_nav/top_nav_11.png">&nbsp;&nbsp;安全保障
                    </div>
                </a>
                <a href="<?php echo site_url('mobiles/home/about_us'); ?>">
                    <div class="p <?php if ($this->router->fetch_method() == 'about_us'): ?>select<?php endif; ?>"><img
                            src="/assets/images/app/top_nav/top_nav_12.png">&nbsp;&nbsp;关于我们
                    </div>
                </a>
                <?php if (profile('uid') > 0): ?>
                    <a href="<?php echo site_url('mobiles/home/logout'); ?>">
                        <div class="p"><img src="/assets/images/app/top_nav/top_nav_9.png">&nbsp;&nbsp;注销</div>
                    </a>
                <?php endif; ?>
                <div class="p" style="height:auto; border:none;"><a href="tel:4007918333"><img
                            src="/assets/images/app/top_nav/top_nav_tel.png" style="height:50px;"></a></div>
            </div>
        </div>
    </div>
</div>
<div class="container-fluid">
    <iframe src="<?php echo site_url('mobiles/home/recharge_iframe'); ?>" frameborder="0" width="100%"></iframe>
</div>
<?php $this->load->view('common/mobiles/app_alert') ?>
</body>
<?php $this->load->view('common/mobiles/app_footer') ?>
<script>
    $(function () {
        $('iframe').css({
            'height': window.innerHeight - $(".header").height() - 6
        });
    });
</script>
</html>