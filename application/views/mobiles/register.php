<!DOCTYPE html>
<html lang="en">
<head>
    <title>注册</title>
    <?php $this->load->view('common/mobiles/app_head') ?>
</head>
<body>
<!-- 公共头部导航-->
<?php $this->load->view('common/mobiles/app_common_head') ?>

<div class="container-fluid">
    <form action="#" role="form" method="post" onsubmit="return false;">
        <div class="container  mt10">
            <div class="row  app_inputs form-group-lg">
                <input placeholder="手机号/用户名" name="mobile" type="text" class="input-group-lg form-control">

                <div class="clears line"></div>
                <input placeholder="密码" name="password" type="password" class="input-group-lg form-control">

                <div class="clears line"></div>
                <input placeholder="确认密码" name="retype" type="password" class="input-group-lg form-control">

                <div class="clears line"></div>
                <input placeholder="验证码" name="authcode" type="text" class="input-group-lg form-control">

                <div class="clears line"></div>
                <input placeholder="邀请码(选填)" name="invite_code" type="text" class="input-group-lg form-control">
            </div>
        </div>

        <div class="row">
            <div class="col-xs-6  mt20">
                <a class="btn btn-success btn-block sms">发送短信</a>
            </div>

            <div class="col-xs-6  mt20">
                <a class="btn btn-info btn-block voice">收听语音验证码</a>
            </div>
        </div>
        <div class="container">
            <div class="row mt20 mb20">
                <button id="submit" type="submit" class="btn btn-lg btn-danger btn-block ajax-submit-button" data-loading-msg="注册中...">立即注册</button>
            </div>
        </div>
    </form>
    <p class="text-center mb20">
        注册即代表您同意<a href="/index.php/mobiles/home/register_agreement" class="c_blue">《聚雪球用户协议》</a>
    </p>

    <?php $this->load->view('common/mobiles/app_alert') ?>
</div>

<?php $this->load->view('common/mobiles/app_footer') ?>
</body>
<script>
    $(function () {
        $('input[name="mobile"]').on('blur', function () {
            $('.sms').send_sms('sms',$('input[name="mobile"]').val(), 'register');
            $('.voice').send_sms('voice',$('input[name="mobile"]').val(), 'register');
        });

        $('#submit').on('click', function () {
            var phone = /^1[3456789](\d){9}$/;
            var authcode = /[0-9]{6}/;//6位至20位数字、字母和特殊字符组成
            if (!phone.test($(':input[name="mobile"]').val())) {
                my_alert('请输入正确格式手机号码！');
                return false;
            }
            if ($(':input[name="password"]').val().length < 6) {
                my_alert('建议输入6位至20位数字、字母和特殊字符组成的密码');
                return false;
            }
            if ($(':input[name="retype"]').val() != $(':input[name="password"]').val()) {
                my_alert('两次密码输入不一致');
                return false;
            }
            if (!authcode.test($(':input[name="authcode"]').val())) {
                my_alert('请输入6位数字验证码！');
                return false;
            }
            $.ajax({
                url : '/index.php/mobiles/home/register',
                dataType: 'json',
                type: 'post',
//                btn :'#submit',
                data: {
                    'mobile': $(':input[name="mobile"]').val(),
                    'password': $(":input[name='password']").val(),
                    'authcode': $(":input[name='authcode']").val(),
                    'invite_code': $(":input[name='invite_code']").val()
                },
                success: function (resut) {
                    if (resut.status == '10000') {
                        window.location.replace('/index.php/mobiles/home/register_success');
                    } else {
                        my_alert(resut.msg);
                    }
                }
            });
            return false;
        });
    });
</script>
</html>
