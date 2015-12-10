<!DOCTYPE html>
<html>
<head lang="en">
    <title>充值</title>
    <?php $this->load->view('common/apps/app_head') ?>
</head>
<body>
<div class="container-fluid">
    <!-- 顶部红色部分 -->
    <div class="bg_red_jb mb10 c_fff row">
        <table width="100%" border="0" cellspacing="0" cellpadding="0">
            <tbody>
            <tr>
                <td align="right" height="80">
                    <span class="f16">聚雪球充值：</span>
                </td>
                <td align="left">
                    <span>￥</span>
                    <span class="f24 c_fff"><?php echo $amount; ?></span>
                </td>
            </tr>
            </tbody>
        </table>
    </div>
    <!-- 顶部红色部分 end-->
    <div class="row">
        <table width="100%" class="table_pdtb5 borderb" border="0" cellspacing="0" cellpadding="0">
            <tbody>
            <tr>
                <td>
                    <span class="c_666">银行卡号：</span>
                </td>
                <td>
                    <span
                        class="f18 c_333"><?php echo substr($account, 0, 4) . '*****' . substr($account, -4); ?></span>
                </td>
            </tr>
            <tr>
                <td width="90">
                    <span class="c_666">真实姓名：</span>
                </td>
                <td>
                    <span class="f18 c_333"><?php echo profile('real_name'); ?></span>
                </td>
            </tr>
            <tr>
                <td>
                    <span class="c_666">身份证号：</span>
                </td>
                <td>
                    <span
                        class="f18 c_333"><?php echo substr(profile('nric'), 0, 4) . '*****' . substr(profile('nric'), -4); ?></span>
                </td>
            </tr>
            </tbody>
        </table>
    </div>
    <div>
        <div class="row  tonglan_input  form-group-lg">
            <p class="ml10 mt10">
                手机号：
            </p>

            <div class="bg_white">
                <input type="number" class="input-group-lg form-control" value="<?php echo profile('mobile'); ?>"
                       readonly>
            </div>
        </div>
        <div class="row  tonglan_input  form-group-lg">
            <p class="ml10 mt10">
                验证码：
            </p>

            <div class="bg_white">
                <input id="code" placeholder="请输入银行验证码" type="number" class="input-group-lg form-control">
            </div>
        </div>
        <!--        <div class="row mb10">-->
        <!--            <div class="col-xs-6  mt10">-->
        <!--                <button class="btn btn-success btn-block">发送短信</button>-->
        <!--            </div>-->
        <!---->
        <!--            <div class="col-xs-6  mt10">-->
        <!--                <button class="btn    btn-info btn-block">收听语音验证码</button>-->
        <!--            </div>-->
        <!--        </div>-->
        <div class="col-lg-12 row  mb10">
            <button type="submit" class="btn btn-danger   btn-lg btn-block" id="btn_tj">
                同意协议并支付
            </button>
        </div>
        <p class="text-center">
            <a href="#" class="c_blue">《聚雪球支付服务协议》</a>
        </p>
    </div>
</div>
<?php $this->load->view('common/apps/app_alert') ?>
</body>
<?php $this->load->view('common/apps/app_footer') ?>
<script>

    /*付款确认*/
    $("#btn_tj").click(function () {
        var code_reg = /^\d{6}$/;
        if (!code_reg.test($("#code").val())) {
            my_alert('请输入验证码！');
            return false;
        }
        window.location.href = '/index.php/apps/home/recharge_success?amount=<?php echo $amount; ?>';
    });
    /*付款确认 end*/
</script>
</html>