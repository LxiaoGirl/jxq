<!DOCTYPE html>
<html>
<head lang="en">
    <title>个人信息</title>
    <?php $this->load->view('common/apps/app_head') ?>
    <style>
        body, html {
            background: #fff;
        }
    </style>
</head>
<body>
<div class="container-fluid">
    <!-- 顶部红色部分 -->
    <div class="bg_red_jb  c_fff row">
        <table width="100%" border="0" cellspacing="0" cellpadding="0">
            <tbody>
            <tr>
                <td align="right" width="50" height="50">
                    <a href="#">
                        <img style="border-radius:50%"
                             src="<?php echo profile('avatar') ? $this->c->get_oss_image(profile('avatar')) : assets('images/personal/default-faceimage.png'); ?>"
                             width="50" height="50" alt="">
                    </a>
                </td>
                <td align="left">
                    <div class="f18"><?php echo profile('user_name') ? profile('user_name') : '-'; ?></div>
                    <div><?php echo profile('mobile') ? profile('mobile') : '-'; ?></div>
                </td>
            </tr>
            </tbody>
        </table>
    </div>
    <!-- 顶部红色部分 end-->
    <div class="row">
        <!--        <a href="#" style="display:block;">-->
        <!--            <table width="100%" class="borderb" border="0" cellspacing="0" cellpadding="0">-->
        <!--                <tbody>-->
        <!--                <tr>-->
        <!--                    <td>-->
        <!--                        <span class="c_666">修改头像</span>-->
        <!--                    </td>-->
        <!--                    <td align="right">-->
        <!--                        <span class="iconfont icon-xiangyou1"></span>-->
        <!--                    </td>-->
        <!--                </tr>-->
        <!--                </tbody>-->
        <!--            </table>-->
        <!--        </a>-->

        <a href="<?php if (in_array(profile('clientkind'),array('1','2','-3','-4','-5')) && (profile('real_name') != '' || profile('nric') != '')):echo 'javascript:void(0);';
        else:echo site_url('apps/home/real_name');endif; ?>" style="display:block;">
            <table width="100%" class="borderb" border="0" cellspacing="0" cellpadding="0">
                <tbody>
                <tr>
                    <td>
                        <span class="c_666">实名认证</span>
                    </td>
                    <td align="right">
                        <?php if (in_array(profile('clientkind'),array('1','2','-3','-4','-5')) && (profile('real_name') != '' || profile('nric') != '')): ?>
                            <span class="c_blue"><?php echo profile('real_name'); ?></span>
                        <?php else: ?>
                            <span class="c_blue">未认证</span>
                            <span class="iconfont icon-xiangyou1"></span>
                        <?php endif; ?>
                    </td>
                </tr>
                </tbody>
            </table>
        </a>

        <a href="<?php echo site_url('apps/intermediary/index');?>" style="display:block;">
            <table width="100%" class="borderb" border="0" cellspacing="0" cellpadding="0">
                <tbody>
                <tr>
                    <td>
                        <span class="c_666">邀请好友</span>
                    </td>
                    <td align="right">
                        <span class="iconfont icon-xiangyou1"></span>
                    </td>
                </tr>
                </tbody>
            </table>
        </a>

        <a href="https://www.zgwjjf.com/index.php/apps/home/redbag" style="display:block;">
            <table width="100%" class="borderb" border="0" cellspacing="0" cellpadding="0">
                <tbody>
                <tr>
                    <td>
                        <span class="c_666">我的红包</span>
                    </td>
                    <td align="right">
                        <span class="iconfont icon-xiangyou1"></span>
                    </td>
                </tr>
                </tbody>
            </table>
        </a>
    </div>
</div>
</body>
<?php $this->load->view('common/apps/app_footer') ?>
</html>