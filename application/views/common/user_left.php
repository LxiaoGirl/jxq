

    <!--user_left start-->
  <!-- <div class="user_nav row">
        <a href="">首页</a>&nbsp;>&nbsp;<a href="">个人中心</a>&nbsp;>&nbsp;<a href="">账户总览</a>
    </div>-->
        <div class="user_left">
            <div class="user_center_pro">
                <div class="user_icon" onclick="window.location.href='<?php echo profile('avatar')?site_url('user/user/account_home'):site_url('user/user/head_portrait'); ?>'" style="cursor: pointer;"><img src="<?php if( ! profile('uid') || !profile('avatar')): ?>/assets/images/common/my_icon.jpg<?php else:echo $this->c->get_oss_image(profile('avatar')); endif; ?>"" width="100"></div>
                <p class="tc">
                    <?php
                        Date_default_timezone_set("PRC");
                        $h=date("H");
                        if($h<11) echo "早上好!";
                        else if($h<13) echo "中午好！";
                        else if($h<17) echo "下午好！";
                        else echo "晚上好！";
                    ?>
                    <i id="left_user_name_span"><?php echo profile('user_name')?profile('user_name'):'-' ?></i>
                    <span>vip<font>0</font></span></p>
                <p class="str_img">
                    <a href="<?php echo site_url('user/user/account_information') ;?>"><img src="../../../assets/images/common/user_left_2_ok.png" title="手机绑定"></a>
                    <a href="<?php echo site_url('user/user/account_security') ;?>"><img id="real_name_image" src="<?php if(profile('clientkind') == '1'): ?>/assets/images/common/user_left_1_ok.png<?php else: ?>/assets/images/common/user_left_1.png<?php endif; ?>" title="实名认证"></a>
                    <a href="<?php echo site_url('user/user/account_information') ;?>"><img src="<?php if(profile('email')): ?>/assets/images/common/user_left_3_ok.png<?php else: ?>/assets/images/common/user_left_3.png<?php endif; ?>" title="邮箱绑定"></a>
                </p>
                <p>安全等级<a href="<?php echo site_url('user/user/account_information');?>">去提升</a><font class="fr"><?php echo safety();?></font></p>
                <p class="pre"><span><span style=" width:<?php echo Grade();?>;"></span></span></p>
            </div>
            <ul>
                <a class="leaders" href="<?php echo site_url('user/user/account_home');?>"><li class="leaders"><img src="../../../assets/images/common/user_left_icon_1.png">我的账户</li></a>
                <a href="<?php echo site_url('user/user/account_home');?>" <?php if($this->router->fetch_method() == 'account_home'): ?>class="selected"<?php endif; ?>><li>资金总览<font class="fr">></font></li></a>
            <!--<a href="<?php echo site_url('user/user/recharge');?>"><li>我的等级<font class="fr">></font></li></a>-->
                <a href="<?php echo site_url('user/user/recharge');?>" <?php if(in_array($this->router->fetch_method(),array('recharge','recharge_jl','withdrawals','withdrawals_jl'))): ?>class="selected"<?php endif; ?>><li>充值提现<font class="fr">></font></li></a>
                <a href="<?php echo site_url('user/user/my_xq');?>" <?php if($this->router->fetch_method() == 'my_xq'): ?>class="selected"<?php endif; ?>><li>我的雪球<font class="fr">></font></li></a>
                <a href="<?php echo site_url('user/user/my_redbag');?>" <?php if(in_array($this->router->fetch_method(),array('my_redbag','my_redbag_lq','my_redbag_gq'))): ?>class="selected"<?php endif; ?>><li>我的红包<font class="fr">></font></li></a>
                <!--<a href="<?php echo site_url('user/user/information');?>" <?php if($this->router->fetch_method() == 'information'): ?>class="selected"<?php endif; ?>><li>消息中心<font class="fr">></font></li></a>-->
                <a class="leaders" href="<?php echo site_url('user/user/jbb');?>"><li class="leaders"><img src="../../../assets/images/common/user_left_icon_2.png">我的投资</li></a>
				<a href="<?php echo site_url('user/user/jbb');?>" <?php if(in_array($this->router->fetch_method(),array('jbb','jbb_line','jbb_history'))): ?>class="selected"<?php endif; ?>><li>聚保宝管理<font class="fr">></font></li></a>
                <a href="<?php echo site_url('user/user/transaction_note');?>" <?php if($this->router->fetch_method() == 'transaction_note'): ?>class="selected"<?php endif; ?>><li>投资记录<font class="fr">></font></li></a>
                <a href="<?php echo site_url('user/user/transaction_details');?>" <?php if($this->router->fetch_method() == 'transaction_details'): ?>class="selected"<?php endif; ?>><li>交易明细<font class="fr">></font></li></a>
                <!--<a href="<?php echo site_url('user/user/auto');?>" <?php if($this->router->fetch_method() == 'auto'): ?>class="selected"<?php endif; ?>><li>自动投标<font class="fr">></font></li></a>-->
                <!--<a href="" class="leaders" href=""><li class="leaders"><img src="../../../assets/images/common/user_left_icon_3.png">我的借贷</li></a>
                <a href="<?php echo site_url('user/user/recharge');?>"><li>我的借款<font class="fr">></font></li></a>
                <a href="<?php echo site_url('user/user/recharge');?>"><li>申请借款<font class="fr">></font></li></a>-->
                <a class="leaders" href="<?php echo site_url('user/user/account_information');?>"><li class="leaders"><img src="../../../assets/images/common/user_left_icon_4.png">账户设置</li></a>
                <a href="<?php echo site_url('user/user/account_information');?>" <?php if(in_array($this->router->fetch_method(),array('account_information','head_portrait','account_security'))): ?>class="selected"<?php endif; ?>><li>基本信息<font class="fr">></font></li></a>
                <a href="<?php echo site_url('user/user/card');?>" <?php if($this->router->fetch_method() == 'card'): ?>class="selected"<?php endif; ?>><li>银行卡管理<font class="fr">></font></li></a>
                <a href="<?php echo site_url('user/user/invite');?>" <?php if(in_array($this->router->fetch_method(),array('invite','invite_customer'))): ?>class="selected"<?php endif; ?>><li>邀请好友<font class="fr">></font></li></a>
            </ul>
        </div>

    <!--user_left end-->

