<!DOCTYPE html>
<html>
<head lang="en">
    <title>忘记密码</title>
    <?php $this->load->view('common/mobiles/app_head') ?>
</head>
<body>
<!-- 公共头部导航-->
<?php $this->load->view('common/mobiles/app_common_head') ?>

<div class="container-hy" id="page-one">
    <div class="wrapper_find" style="margin-top: 20px;">
        <form id="find_form" action="#" method="post" onsubmit="return false;">
            <p>手机号：</p>
            <input id="hy_tel" type="text" value=""/>

            <p>短信验证码：</p>
            <input id="hy_pwd" type="text" value=""/>
            <input id="dx" class="harf sms" type="text" value="发送短信验证码" readonly><input id="yy" class="harf voice" type="text" value="收听语音验证码" readonly/>
            <input id="login_btn" type="submit" data-loading-msg="验证中..." value="下一步"/>
        </form>
    </div>
</div>

<div class="container-hy" style="display: none;" id="page-two">
    <form id="new_form" action="" method="post">
        <p>
            <label>输入新密码</label>
            <input id="new_pwd" type="password" maxlength=""/>
        </p>

        <p>
            <label>确认新密码</label>
            <input id="new_pwd1" type="password" maxlength=""/>
        </p>

        <p>
            <input id="new_btn" type="submit" data-loading-msg="提交中..." value="提交修改">
        </p>
    </form>
</div>
<?php $this->load->view('common/mobiles/app_alert') ?>
</body>
<?php $this->load->view('common/mobiles/app_footer') ?>
<script type="text/javascript">
    var my_mobile = '', authcode = '', new_password = '', retry_password = '';
    $(function () {
        $('#hy_tel').on('blur', function () {
            $('.sms').send_sms('sms',$('#hy_tel').val(), 'forget');
            $('.voice').send_sms('voice',$('#hy_tel').val(), 'forget');
        });
        $("#login_btn").on('click', function () {
            var auth = /^\d{6}$/;
            var phone = /^1[3456789](\d){9}$/;
            if (!phone.test($("#hy_tel").val())) {
                my_alert('请输入正确格式手机号码！');
                return false;
            }
            if (!auth.test($("#hy_pwd").val())) {
                my_alert('请输入正确格式的手机验证码！');
                return false;
            }
            $.ajax({
                url: '/index.php/send/validate_authcode',
                dataType: 'json',
                type: 'post',
                btn : '#login_btn',
                data: {
                    'mobile': $("#hy_tel").val(),
                    'authcode': $("#hy_pwd").val(),
                    'action': 'forget'
                },
                success: function (resut) {
                    if (resut.status == '10000') {
                        my_mobile = $("#hy_tel").val(), authcode = $("#hy_pwd").val();
                        $("#page-one").hide();
                        $("#page-two").show();
                    } else {
                        my_alert(resut.msg);
                    }
                }
            });
            return false;
        });
        $("#new_btn").on('click', function () {
            var password = /^\d{6}$/;
            if ($('#new_pwd').val().length < 6) {
                my_alert('建议输入6位至20位数字、字母和特殊字符组成的密码');
                return false;
            }
            if ($('#new_pwd1').val() != $('#new_pwd').val()) {
                my_alert('两次密码输入不一致');
                return false;
            }
            $.ajax({
                url: '/index.php/mobiles/home/forget',
                dataType: 'json',
                type: 'post',
                btn : '#new_btn',
                data: {
                    'mobile': $("#hy_tel").val(),
                    'new_password': $("#new_pwd").val(),
                    'authcode': $("#hy_pwd").val()
                },
                success: function (resut) {
                    my_alert(resut.msg);
                    if (resut.status == '10000') {
                        var tt = setTimeout(function () {
                            window.location.replace('/index.php/mobiles/home/login');
                        }, 1000);
                    }
                }
            });
            return false;
        })
    });
</script>
</html>