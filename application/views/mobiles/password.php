<!DOCTYPE html>
<html>
<head lang="en">
    <title>修改登陆密码</title>
    <?php $this->load->view('common/mobiles/app_head') ?>
</head>
<body>
<!-- 公共头部导航-->
<?php $this->load->view('common/mobiles/app_common_head') ?>

<div class="container-fluid">
    <div class="mt10">
        <div role="form">
            <div class="row  tonglan_input  form-group-lg">
                <p class="ml10">
                    原密码：
                </p>

                <div class="bg_white mb10">
                    <input name="password" placeholder="请输入原密码" type="password" class="input-group-lg form-control">
                </div>
                <p class="ml10">
                    新密码：
                </p>

                <div class="bg_white mb10">
                    <input name="new_password" placeholder="请输入新密码" type="password" class="input-group-lg form-control">
                </div>
                <p class="ml10">
                    确认新密码：
                </p>

                <div class="bg_white mb10">
                    <input name="new_password1" placeholder="确认新密码" type="password" class="input-group-lg form-control">
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
                        <button id="ok" class="btn    btn-link btn-block ajax-submit-button"><span
                                class="c_red">确认</span></button>
                    </div>
                </div>

            </div>
        </div>
    </div>
    <!-- cd-popup-container -->
</div>
<?php $this->load->view('common/mobiles/app_alert') ?>
</body>
<?php $this->load->view('common/mobiles/app_footer') ?>
<script>
    var phone = '<?php echo profile('mobile') ?>';
    var changPW = function () {
        var auth = /[0-9]{6}/;
        if (!auth.test($("input[name='authcode']").val())) {
            my_alert('请输入正确格式的验证码！');
            return false;
        }
        $('.cd-popup').removeClass('is-visible');
        $.post('<?php echo site_url('mobiles/home/password') ?>', {
            'password': $("input[name='password']").val(),
            'new_password': $("input[name='new_password']").val(),
            'authcode': $("input[name='authcode']").val()
        }, function (rs) {
            if (rs.code == 0) {
                window.location.replace('<?php echo site_url('mobiles/home/password_success') ?>');
            } else {
                //关闭 弹窗 然后提示
                my_alert(rs.msg);
            }
        }, 'json')
    }
    $(function () {
        //发送验证码处理
        send_authcode(phone, 'password');

        $("#submit").on('click', function () {
            check_to_login();
            if ($("input[name='password']").val() == '' || $("input[name='password']").val().length < 6) {
                my_alert('请输入正确的原始密码！');
            } else if ($("input[name='new_password']").val() == '' || $("input[name='password']").val().length < 6) {
                my_alert('请输入6位以上新密码！');
            } else if ($("input[name='new_password']").val() != $("input[name='new_password1']").val()) {
                my_alert('确认密码不一致！');
            } else {
                $('.cd-popup').addClass('is-visible');
            }
        })
        $(".JS_quit").click(function (event) {
            event.preventDefault();
            $(this).parents('.cd-popup').removeClass('is-visible');
        });
        $("#ok").on('tap', function () {
            changPW();
        });
    });
</script>
</html>