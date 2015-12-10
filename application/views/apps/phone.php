<!DOCTYPE html>
<html>
<head lang="en">
    <title>解绑手机</title>
    <?php $this->load->view('common/apps/app_head') ?>
</head>
<body>
<div class="container-fluid">
    <div class="mt10">
        <div role="form">
            <div class="row  tonglan_input  form-group-lg">
                <p class="ml10">
                    原手机号：
                </p>

                <div class="bg_white">
                    <input placeholder="请输入您原来的手机号" type="number" class="input-group-lg form-control"
                           value="<?php echo profile('mobile'); ?>" readonly>
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
                    <button id="submit" class="btn btn-lg btn-danger btn-block ajax-submit-button">下一步</button>
                </div>
            </div>
        </div>
        <?php $this->load->view('common/apps/app_alert') ?>
    </div>
</div>
</body>
<?php $this->load->view('common/apps/app_footer') ?>
<script>
    var phone = '<?php echo profile('mobile') ?>';
    $(function () {
        //发送验证码处理
        send_authcode(phone, 'unbindphone');
        $('#submit').on('tap', function () {
            check_to_login();
            var authcode = /[0-9]{6}/;//6位至20位数字、字母和特殊字符组成
            if (!authcode.test($(':input[name="authcode"]').val())) {
                my_alert('请输入6位数字验证码！');
                return false;
            }
            $.ajax({
                url: '/index.php/apps/home/phone',
                dataType: 'json',
                type: 'post',
                data: {
                    'authcode': $(":input[name='authcode']").val()
                },
                success: function (resut) {
                    if (resut.code == 0) {
                        window.location.replace('<?php echo site_url('apps/home/phone_bind?token='); ?>' + resut.data);
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