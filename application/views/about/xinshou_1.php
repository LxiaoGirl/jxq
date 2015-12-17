<!DOCTYPE html>
<html>
<head>
    <title>聚雪球_网加金服_P2P理财首选互联网金融P2P网贷平台_100元即可投资!</title>
	<?php $this->load->view('common/head_file'); ?>
</head>
<body>

<?php $this->load->view('common/head'); ?>

<div class="help_search xszy">
    
</div>
<div style="background:#f3f3f3">
    <div class="mnx2">
        <div class="help_w">
            <img src="../../../../assets/images/common/mb_ico.png">您当前所在的位置：<a href="<?php echo site_url(); ?>">首页</a> ><span class="mb_blue"> 新手指引</span>
        </div>
    </div>
    <div class="help_w end-hidden bor_bot">
        <a href="<?php echo site_url('about/guide') ?>">
        <div class="fl fl1 active">
            新手指引
            <em></em>
        </div>
        </a>
        <a href="<?php echo site_url('about/guide_common_problem') ?>">
        <div class="fl fl2">
            新手常见问题
            <em></em>
        </div>
        </a>
    </div>
    <div class="help_w end-hidden">
        <div class="help_man1 fl">
            <p class="help_h1">成为会员</p>
            <ul class="help_list2 help_man">
                <a href="<?php echo site_url('about/help_list?cat_id=30'); ?>">
                    <li>
                        <img src="../../../../assets/images/about/help/help1_1.png"> 
                        <p>注册</p>
                    </li>
                </a>
                <a href="<?php echo site_url('about/help_list?cat_id=46'); ?>">
                    <li>
                        <img src="../../../../assets/images/about/help/help1_2.png"> 
                        <p>认证</p>
                    </li>
                </a>
            </ul>
        </div>
        <div class="help_man2 fr">
            <p class="help_h1">投资流程</p>
            <ul class="help_list3 help_man">
                <a href="<?php echo site_url('about/help_list?cat_id=34'); ?>">
                    <li>
                        <img src="../../../../assets/images/about/help/help1_1.png"> 
                        <p>充值</p>
                    </li>
                </a>
                <a href="<?php echo site_url('about/help_list?cat_id=36'); ?>">
                    <li>
                        <img src="../../../../assets/images/about/help/help2_2.png"> 
                        <p>投资</p>
                    </li>
                </a>
                <a href="<?php echo site_url('about/help_list?cat_id=35'); ?>">
                    <li>
                        <img src="../../../../assets/images/about/help/help2_4.png"> 
                        <p>提现</p>
                    </li>
                </a>
            </ul> 
        </div>
        <div class="clear"></div>
        <div class="help_man3">
            <p class="help_h1">自助服务</p>
            <ul class="help_list4 end-hidden">
                <a href="<?php echo site_url('user/user/account_information?type=name'); ?>">
                    <li>
                        <img src="../../../../assets/images/about/help/help3_1.png"> 
                        <p>修改用户名</p>
                    </li>
                </a>
                <a href="<?php echo profile('uid')?site_url('user/user/account_security?type=find_password'):site_url('login/forget'); ?>">
                    <li>
                        <img src="../../../../assets/images/about/help/help3_2.png"> 
                        <p>找回登录密码</p>
                    </li>
                </a>
                <a href="<?php echo site_url('user/user/account_security?type=find_security'); ?>">
                    <li>
                        <img src="../../../../assets/images/about/help/help3_3.png">
                        <p>找回交易密码</p>
                    </li>
                </a>
                <a href="<?php echo site_url('user/user/account_information?type=phone'); ?>">
                    <li class="last">
                        <img src="../../../../assets/images/about/help/help3_4.png"> 
                        <p>修改手机号码</p>
                    </li>
                </a>
                <!--
                <a href="<?php echo site_url('user/user/account_security?type=change_password'); ?>">
                    <li>
                        <img src="../../../../assets/images/about/help/help3_5.png"> 
                        <p>修改登录密码</p>
                    </li>
                </a>
                <a href="<?php echo site_url('user/user/account_security?type=change_security'); ?>">
                    <li>
                        <img src="../../../../assets/images/about/help/help3_6.png"> 
                        <p>修改交易密码</p>
                    </li>
                </a>
                -->
            </ul>
        </div>
    </div>
</div>

<!--footer start-->
<?php $this->load->view('common/footer'); ?>
<!--footer end-->
</body>
</html>