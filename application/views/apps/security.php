<!DOCTYPE html>
<html>
<head lang="en">
    <title>设置资金密码</title>
    <?php $this->load->view('common/apps/app_head') ?>
</head>
<body>
<div class="container-fluid">
    <div class="mt10">
        <div role="form">
            <div class="row  tonglan_input  form-group-lg">
                <p class="ml10">
                    输入资金密码：
                </p>

                <div class="bg_white">
                    <input name="security" placeholder="请输入您的资金密码" type="password" class="input-group-lg form-control">
                </div>
                <p class="ml10 mt10">
                    确认资金密码：
                </p>

                <div class="bg_white">
                    <input name="retype" placeholder="确认资金密码" type="password" class="input-group-lg form-control">
                </div>

                <p class="ml10 mt10">
                    手机号码：
                </p>

                <div class="bg_white">
                    <input readonly placeholder="请输入您的手机号码" type="number" class="input-group-lg form-control"
                           value="<?php echo profile('mobile'); ?>">
                </div>
                <p class="ml10 mt10">
                    短信验证码：
                </p>

                <div class="bg_white">
                    <input name="authcode" placeholder="请输入短信验证码" type="text" class="input-group-lg form-control">
                </div>
            </div>

            <div class="row">
                <div class="col-xs-6  mt20">
                    <button class="btn btn-success btn-block send-authcode" send-type="sms">发送短信</button>
                </div>

                <div class="col-xs-6  mt20">
                    <button class="btn    btn-info btn-block send-authcode" send-type="voice">收听语音验证码</button>
                </div>
            </div>

            <div class="container">
                <div class="row mt20 mb20">
                    <button id="submit" class="btn btn-lg btn-danger btn-block ajax-submit-button">确认提交</button>
                </div>
            </div>
        </div>

    </div>
</div>
<?php $this->load->view('common/apps/app_alert') ?>
</body>
<?php $this->load->view('common/apps/app_footer') ?>
<script>
    var phone = '<?php echo profile('mobile') ?>';
    $(function () {
        //发送验证码处理
        send_authcode(phone, 'security');
        $('#submit').on('tap', function () {
            check_to_login();
            var authcode = /[0-9]{6}/;//6位至20位数字、字母和特殊字符组成
            if ($('input[name="security"]').val().length < 6) {
                my_alert('建议输入6位至20位数字、字母和特殊字符组成的资金密码');
                return false;
            }
            if ($(':input[name="retype"]').val() != $(':input[name="security"]').val()) {
                my_alert('两次密码输入不一致');
                return false;
            }
            if (!authcode.test($(':input[name="authcode"]').val())) {
                my_alert('请输入6位数字验证码！');
                return false;
            }
            $.ajax({
                url: '/index.php/apps/home/security',
                dataType: 'json',
                type: 'post',
                data: {
                    'security': $(":input[name='security']").val(),
                    'retype': $(":input[name='retype']").val(),
                    'authcode': $(":input[name='authcode']").val()
                },
                success: function (resut) {
                    if (resut.code == 0) {
                        window.location.replace('<?php echo site_url('apps/home/security_success'); ?>');
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