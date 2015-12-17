<!DOCTYPE html>
<html>
<head lang="en">
    <title>修改资金密码</title>
    <?php $this->load->view('common/apps/app_head') ?>
</head>
<body>
<div class="container-fluid">
    <div class="mt10">
        <div role="form">
            <div class="row  tonglan_input  form-group-lg">
                <p class="ml10">
                    输入新密码：
                </p>

                <div class="bg_white mb10">
                    <input name="security" placeholder="请输入新密码" type="password" class="input-group-lg form-control">
                </div>
                <p class="ml10">
                    确认新密码：
                </p>

                <div class="bg_white mb10">
                    <input name="retype" placeholder="确认新密码" type="password" class="input-group-lg form-control">
                </div>

            </div>

            <div class="container">
                <div class="row mt10 mb20">
                    <button id="submit" class="btn btn-lg btn-danger btn-block">确认修改</button>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="cd-popup" role="alert">
    <div class="cd-popup-container">
        <div class="f14 text-center mb10">输入验证码</div>
        <div class="text-center">
            <div>
                <div class="bg_white">
                    <input name="authcode" placeholder="请输入短信验证码" type="text" class="input-group-lg form-control">
                </div>
                <div class="row mb10">
                    <div class="col-xs-6  mt10">
                        <button class="btn btn-success btn-block send-authcode" send-type="sms">发送短信</button>
                    </div>

                    <div class="col-xs-6  mt10">
                        <button class="btn    btn-info btn-block send-authcode" send-type="voice">收听语音验证码</button>
                    </div>
                </div>
                <div class="row mb10">
                    <div class="col-xs-6  mt10">
                        <button class="btn JS_quit btn-link btn-block">取消</button>
                    </div>

                    <div class="col-xs-6  mt10">
                        <button id="ok" class="btn    btn-link btn-block"><span class="c_red">确认</span></button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- cd-popup-container -->
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
            if ($('input[name="security"]').val().length < 6) {
                my_alert('建议输入6位至20位数字、字母和特殊字符组成的资金密码');
                return false;
            }
            if ($(':input[name="retype"]').val() != $(':input[name="security"]').val()) {
                my_alert('两次密码输入不一致');
                return false;
            }
            //弹出弹窗
            $('.cd-popup').addClass('is-visible');
            return false;
        });

        $(".JS_quit").click(function (event) {
            event.preventDefault();
            $(this).parents('.cd-popup').removeClass('is-visible');
        });

        $("#ok").on('tap', function () {
            var authcode = /[0-9]{6}/;//6位至20位数字、字母和特殊字符组成
            if (!authcode.test($(':input[name="authcode"]').val())) {
                my_alert('请输入6位数字验证码！');
                return false;
            }
            $('.cd-popup').removeClass('is-visible');
            $.ajax({
                url: '<?php site_url('apps/home/security_change') ?>',
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
                        //隐藏弹窗  提示新
                        my_alert(resut.msg);
                    }
                }
            });
        })
    });
</script>
</html>