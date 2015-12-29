<!DOCTYPE html>
<html lang="en">
<head>
    <title>登录</title>
    <?php $this->load->view('common/mobiles/app_head') ?>
</head>
<body>
<!-- 公共头部导航-->
<?php $this->load->view('common/mobiles/app_common_head') ?>


<div class="container-hy">
    <div class="hlogo"><img src="/assets/images/app/logo.png"></div>
    <div class="wrapper">
        <form id="login_form" action="#" method="post">
            <div id="shuru_box">
                <p class="bottom_line">
                    <input id="hy_tel" type="text" name="mobile" value="" placeholder="手机号/用户名">
                </p>

                <p>
                    <input id="hy_pwd" type="password" name="password" value="" placeholder="密码">
                </p>
            </div>
            <p class="btn_box">
                <input id="login_btn" type="submit" value="登录" class="ajax-submit-button">
            </p>
        </form>
        <div id="link_box"><span><a href="<?php echo site_url('mobiles/home/register'); ?>">立即注册</a></span><a
                href="<?php echo site_url('mobiles/home/forget'); ?>">忘记密码</a></div>
    </div>
</div>

<?php $this->load->view('common/mobiles/app_alert') ?>
</body>
<?php $this->load->view('common/mobiles/app_footer') ?>
<script>
    $(function () {
        var phone = /^1[3456789](\d){9}$/;
        $('#login_btn').on('click', function () {
            var authcode = /[0-9]{6}/;//6位至20位数字、字母和特殊字符组成
            if (!phone.test($(':input[name="mobile"]').val())) {
                my_alert('请输入正确格式手机号码！');
                return false;
            }
            if ($(':input[name="password"]').val().length < 6) {
                my_alert('请输入6位以上的密码');
                return false;
            }
            $.ajax({
                url: '/index.php/mobiles/home/login',
                dataType: 'json',
                type: 'post',
                data: {
                    'mobile': $(':input[name="mobile"]').val(),
                    'password': $(":input[name='password']").val()
                },
                success: function (resut) {
                    my_alert(resut.msg);
                    if (resut.status == '10000') {
                        var tt = setTimeout(function () {
                            window.location.replace(resut.url);
                        }, 1000);
                    }
                }
            });
            return false;
        });
    });
</script>
</html>
