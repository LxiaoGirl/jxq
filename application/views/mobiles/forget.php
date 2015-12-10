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
        <form id="find_form" action="####" method="post">
            <p>手机号：</p>
            <input id="hy_tel" type="text" value=""/>

            <p>短信验证码：</p>
            <input id="hy_pwd" type="text" value=""/>
            <input id="dx" class="harf" type="text" value="发送短信验证码" readonly><input id="yy" class="harf" type="text"
                                                                                    value="收听语音验证码" readonly/>
            <input id="login_btn" type="submit" value="下一步"/>
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
            <input id="new_btn" type="submit" value="提交修改">
        </p>
    </form>
</div>
<?php $this->load->view('common/mobiles/app_alert') ?>
</body>
<?php $this->load->view('common/mobiles/app_footer') ?>
<script type="text/javascript">
    var my_mobile = '', authcode = '', new_password = '', retry_password = '';
    var wait = 60;
    function time(o) {
        if (wait == 0) {
            o.removeAttribute("disabled");
            o.value = "发送短信验证码";
            $("#dx").css("background", "#00acee");
            wait = 60;
        } else {
            o.setAttribute("disabled", true);
            o.value = "" + wait + "";
            wait--;
            setTimeout(function () {
                    time(o)
                },
                1000)
        }
    }
    function time1(o) {
        if (wait == 0) {
            o.removeAttribute("disabled");
            o.value = "收听语音验证码";
            $("#yy").css("background", "#00acee");
            wait = 60;
        } else {
            o.setAttribute("disabled", true);
            o.value = "" + wait + "";
            wait--;
            setTimeout(function () {
                    time(o)
                },
                1000)
        }
    }
    var send_authcode = function (obj, mobile, type) {
        var phone = /^1[3456789](\d){9}$/;
        if (phone.test(mobile)) {
            $.ajax({
                url: '/index.php/send/index?captcha=<?php echo $this->session->userdata('captcha'); ?>',
                dataType: 'json',
                data: {'action': 'forget', 'mobile': mobile,'type':type},
                success: function (resut) {
                    if (resut.status == '10000') {
                        if (type == 'sms') {
                            my_alert(resut.msg);
                            time(obj);
                            $("#dx").css("background", "#777");
                        } else {
                            my_alert('稍后聚雪球将通过电话4007-918-333拨打' +
                            '您的手机' + mobile + '告知验证码');
                            time1(obj);
                            $("#yy").css("background", "#777");
                        }
                    } else {
                        my_alert(resut.msg);
                    }
                }
            })
        } else {
            my_alert('手机格式不正确！')
        }
    };
    $(function () {
        document.getElementById("dx").onclick = function () {
            send_authcode(this, $('#hy_tel').val(), 'sms');
        }
        document.getElementById("yy").onclick = function () {
            send_authcode(this, $('#hy_tel').val(), 'voice');
        }
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
            $("#login_btn").prop("disabled", true).val('验证中...');
            $.ajax({
                url: '/index.php/mobiles/home/ajax_forget_check',
                dataType: 'json',
                type: 'post',
                data: {
                    'mobile': $("#hy_tel").val(),
                    'authcode': $("#hy_pwd").val()
                },
                success: function (resut) {
                    $("#login_btn").prop("disabled", false).val('下一步');
                    if (resut.status == 0) {
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
            $("#new_btn").prop("disabled", true).val('提交中...');
            $.ajax({
                url: '/index.php/mobiles/home/forget',
                dataType: 'json',
                type: 'post',
                data: {
                    'mobile': $("#hy_tel").val(),
                    'new_password': $("#new_pwd").val(),
                    'authcode': $("#hy_pwd").val()
                },
                success: function (resut) {
                    $("#new_btn").prop("disabled", false).val('提交修改');
                    if (resut.status == 0) {
                        my_alert(resut.msg);
                        var tt = setTimeout(function () {
                            window.location.replace('<?php echo site_url('mobiles/home/login') ; ?>');
                        }, 2000);
                    } else {
                        my_alert(resut.msg);
                    }
                }
            });
            return false;
        })
    });
</script>
</html>