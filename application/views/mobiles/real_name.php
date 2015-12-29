<!DOCTYPE html>
<html>
<head lang="en">
    <title>实名认证</title>
    <?php $this->load->view('common/mobiles/app_head') ?>
</head>
<body>
<!-- 公共头部导航-->
<?php $this->load->view('common/mobiles/app_common_head') ?>

<div class="container-fluid">
    <div class="mt10">
        <form action="#" role="form" method="post" onsubmit="return false;">
            <div class="row  tonglan_input  form-group-lg">
                <p class="ml10">
                    真实姓名：
                </p>

                <div class="bg_white">
                    <input placeholder="请输入您的姓名" name="real_name" type="text" class="input-group-lg form-control"
                           value="<?php echo $this->session->userdata('real_name')?$this->session->userdata('real_name'):''; ?>"
                           <?php if($this->session->userdata('real_name')):  ?>readonly<?php endif; ?> />
                </div>
                <p class="ml10 mt10">
                    身份证号码：
                </p>

                <div class="bg_white">
                    <input placeholder="请输入您的身份证号码" name="nric" type="text" class="input-group-lg form-control"
                           value="<?php echo $this->session->userdata('nric')?$this->session->userdata('nric'):''; ?>" maxlength="18"
                           <?php if($this->session->userdata('nric')):  ?>readonly<?php endif; ?> />
                </div>
            </div>

            <div class="container">
                <div class="row mt20 mb20">
                    <button id="submit" type="submit" class="btn btn-lg btn-danger btn-block ajax-submit-button"
                        <?php if (!empty($this->session->userdata('real_name')) && !empty($this->session->userdata('nric'))):echo 'disabled';endif; ?>>
                        <?php if (!empty($this->session->userdata('real_name')) && !empty($this->session->userdata('nric'))):?>
                            已认证
                        <?php else: ?>
                            提交认证
                        <?php endif;  ?>
                    </button>
                </div>
            </div>
            <p>系统将连接国家公安系统进行认证，请确保填写的为您最新的正确信息</p>
        </form>
        <p class="text-center mb20">
            <a href="javascript:void(0);" id="ok" class="c_blue">不了，我想先逛逛</a>
        </p>
    </div>

    <?php $this->load->view('common/mobiles/app_alert') ?>
</div>
</body>
<?php $this->load->view('common/mobiles/app_footer') ?>
<script>
    /**
     * 验证身份证
     * @param gets
     * @returns {*}
     */
    var idcard = function (gets) {

        var Wi = [7, 9, 10, 5, 8, 4, 2, 1, 6, 3, 7, 9, 10, 5, 8, 4, 2, 1];// 加权因子;
        var ValideCode = [1, 0, 10, 9, 8, 7, 6, 5, 4, 3, 2];// 身份证验证位值，10代表X;

        if (gets.length == 18) {
            var a_idCard = gets.split("");// 得到身份证数组
            if (isValidityBrithBy18IdCard(gets) && isTrueValidateCodeBy18IdCard(a_idCard)) {
                return true;
            }
            return false;
        }
        return false;

        function isTrueValidateCodeBy18IdCard(a_idCard) {
            var sum = 0; // 声明加权求和变量
            if (a_idCard[17].toLowerCase() == 'x') {
                a_idCard[17] = 10;// 将最后位为x的验证码替换为10方便后续操作
            }
            for (var i = 0; i < 17; i++) {
                sum += Wi[i] * a_idCard[i];// 加权求和
            }
            valCodePosition = sum % 11;// 得到验证码所位置
            if (a_idCard[17] == ValideCode[valCodePosition]) {
                return true;
            }
            return false;
        }

        function isValidityBrithBy18IdCard(idCard18) {
            var year = idCard18.substring(6, 10);
            var month = idCard18.substring(10, 12);
            var day = idCard18.substring(12, 14);
            var temp_date = new Date(year, parseFloat(month) - 1, parseFloat(day));
            // 这里用getFullYear()获取年份，避免千年虫问题
            if (temp_date.getFullYear() != parseFloat(year) || temp_date.getMonth() != parseFloat(month) - 1 || temp_date.getDate() != parseFloat(day)) {
                return false;
            }
            return true;
        }

        function isValidityBrithBy15IdCard(idCard15) {
            var year = idCard15.substring(6, 8);
            var month = idCard15.substring(8, 10);
            var day = idCard15.substring(10, 12);
            var temp_date = new Date(year, parseFloat(month) - 1, parseFloat(day));
            // 对于老身份证中的你年龄则不需考虑千年虫问题而使用getYear()方法
            if (temp_date.getYear() != parseFloat(year) || temp_date.getMonth() != parseFloat(month) - 1 || temp_date.getDate() != parseFloat(day)) {
                return false;
            }
            return true;
        }

    }
    $(function () {
        $('#submit').on('tap', function () {
            check_to_login();
            var nric = /[0-9]{6}/;//6位至20位数字、字母和特殊字符组成
            if ($(':input[name="real_name"]').val().length < 2) {
                my_alert('请输入您的真实姓名');
                return false;
            }
            if (!idcard($(':input[name="nric"]').val())) {
                my_alert('请输入正确格式的身份证号码！');
                return false;
            }
            $.ajax({
                url: '/index.php/mobiles/home/real_name',
                dataType: 'json',
                type: 'post',
                data: {
                    'real_name': $(':input[name="real_name"]').val(),
                    'nric': $(":input[name='nric']").val()
                },
                success: function (resut) {
                    if (resut.status == '10000') {
                        window.location.replace('/index.php/mobiles/home/real_name_success');
                    } else {
                        my_alert(resut.msg);
                    }
                }
            });
            return false;
        });
        $("#ok").on('tap', function () {
            check_to_index();
        })
    });
</script>
</html>