
<div class="head">
    <div class="nav">
    <div class="row">
        <div class="fl">
            <ul>
                <li>客服：4007-918-333</li>
                <li>语音验证：4008-382-182</li>
                <li>嗨，欢迎来聚雪球</li>
                <li class="nav_have_son">
                    关注聚雪球<em>></em>
                    <div class="nav_pop">
                        <div class="nav_pop_body">
                            <div class="fl">
                                <img src="../../../../assets/images/ewm/wx.png" width="90">
                                <p>微信公众号</p>
                            </div>
                            <div class="fl">
                                <img src="../../../../assets/images/ewm/wb.png" width="90">
                                <p>聚雪球微博</p>
                            </div>
                        </div>
                    </div>
                </li>
            </ul>
        </div>
        <div class="fr">
            <ul>
	            <?php if( ! profile('uid')): ?>
		            <li onclick="window.location.href='<?php echo site_url('login'); ?>'">请登录</li>
		            <li class="org" onclick="window.location.href='<?php echo site_url('login/register'); ?>'">免费注册</li>
	            <?php else: ?>
                    <li class="nav_have_son">
                        <span onclick="window.location.href='<?php echo site_url('user/user/account_home'); ?>'"><span class="login" onclick="window.location.href='<?php echo site_url('user/user/account_information'); ?>'">您好！<span id="head_user_name_span"><?php echo profile('user_name'); ?></span></span></span><font class="msg-count-flag"></font><em>></em>
                        <div class="nav_pop">
                            <div class="nav_pop_body1">
                                <div class="top">
                                    <div class="my_icon" onclick="window.location.href='<?php echo profile('avatar')?site_url('user/user/account_home'):site_url('user/user/head_portrait'); ?>'"><img style="width: 50px;height: 50px;" src="<?php if(!profile('avatar')): ?>/assets/images/common/my_icon.jpg<?php else:echo $this->c->get_oss_image(profile('avatar')); endif; ?>"></div>
                                    <div class="right">
                                        <p><span><a href="<?php echo site_url('user/user/account_home'); ?>">我的账户</a></span>&nbsp;|&nbsp;<span><a href="<?php echo site_url('user/user/information'); ?>">消息</a></span></p>
                                        <p><span style="margin-left: 55px;"><a style="color:red;" href="<?php echo site_url('login/logout'); ?>">安全退出</a></span></p>
                                        <!--&nbsp;|&nbsp;<span><a href="<?php echo site_url('user/user/information'); ?>">消息<font class="msg-count">（0）</font></a></span>-->
                                    </div>
                                </div>
                                <div class="bottom">
                                    <a href="<?php echo site_url('user/user/my_xq'); ?>">我的雪球</a><a href="<?php echo site_url('user/user/my_redbag'); ?>">我的红包</a><a href="<?php echo site_url('user/user/transaction_note'); ?>">我的投资</a>
                                </div>
                            </div>
                        </div>
                    </li>
                <?php endif; ?>

                <li onclick="window.location.href='<?php echo site_url('about/help'); ?>'">帮助中心</li>
                <li class="nav_have_son">
                    <img src="../../../../assets/images/common/nav-icon.jpg">手机客户端下载<em>></em>
                    <div class="nav_pop">
                        <div class="nav_pop_body2">
                            <div class="ewm"><img src="../../../../assets/images/ewm/APPdown.png" width="90"></div>
                            <div class="two_but">
                                <p><a href="/snowballapp.apk">安卓版下载</a></p></br>
                                <p><a class="ios" href="https://appsto.re/cn/ApWE9.i">iOS版下载</a></p>
                            </div>
                        </div>
                    </div>
                </li>
            </ul>
        </div>
    </div>
    </div>
    <div class="main_nav">
    <div class="row">
        <div class="fl">
            <ul>
                <li onclick="window.location.href='/index.php'" style="cursor: pointer;"><img src="../../../../assets/images/common/logo.jpg"/></li>
                <?php if( !profile('logo_rate')): $logo_rate=$this->c->get_logo_rate_image();$logo_rate = $logo_rate?$logo_rate:'/assets/images/common/sec_logo.jpg';$this->session->set_userdata(array('logo_rate'=>$logo_rate));endif; ?>
                <li><img src="<?php echo profile('logo_rate'); ?>"/></li>
            </ul>
        </div>
        <div class="fr">
            <ul>
                <li>
                    <a href="<?php echo site_url(); ?>">网站首页</a>
                    <div class="mian_nav_li_pop"><div class="mnavtop"></div></div>
                </li>
                <li>
                    <a href="<?php echo site_url('invest?c=1'); ?>">我要投资</a><em><em></em></em>
                    <div class="mian_nav_li_pop">
                        <div class="mnavtop"></div>
                        <div class="mnav_pop_nav">
	                        <?php
	                        $category = $this->c->get_category();
	                        if($category){
		                        foreach($category as $k=>$v){
			                        echo '<p><a href="'.site_url('invest?c='.$v['cat_id']).'">'.$v['category'].'</a></p>';
		                        }
	                        }
	                        ?>
                            <p><a href="/index.php/invest/index?c=4">聚保宝</a></p>
                        </div>
                    </div>
                </li>
                <!--<li>
                    <a href="<?php /*echo site_url('borrow'); */?>">我要借款</a>
                    <div class="mian_nav_li_pop"><div class="mnavtop"></div></div>
                </li>-->
<!--                <li>-->
<!--                    <a href="--><?php //echo site_url('about/commonweal'); ?><!--">爱心公益</a>-->
<!--                    <div class="mian_nav_li_pop"><div class="mnavtop"></div></div>-->
<!--                </li>-->
                <li>
                    <a href="<?php echo site_url('about/safe'); ?>">安全保障</a>
                    <div class="mian_nav_li_pop"><div class="mnavtop"></div></div>
                </li>
                <li>
                    <a href="<?php echo site_url('about/guide'); ?>">新手指引</a>
                    <div class="mian_nav_li_pop"><div class="mnavtop"></div></div>
                </li>
                <li>
                    <a href="<?php echo site_url('about'); ?>">关于我们</a>
                    <div class="mian_nav_li_pop"><div class="mnavtop"></div></div>
                </li>
            </ul>
        </div>
    </div>
    </div>
</div>