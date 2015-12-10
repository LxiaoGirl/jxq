<!DOCTYPE html>
<html>
<head lang="en">
    <title>提现确认</title>
    <?php $this->load->view('common/mobiles/app_head') ?>
</head>
<body>
<!-- 公共头部导航-->
<?php $this->load->view('common/mobiles/app_common_head') ?>

<div class="container-fluid">
    <!-- 顶部红色部分 -->
    <div class="bg_red_jb  c_fff row">
        <table width="100%" class="table_pdtb5 mt10 mb10" border="0" cellspacing="0" cellpadding="0">
            <tbody>
            <tr>
                <td width="90">
                    <span>提现银行：</span>
                </td>
                <td>
                    <span class="f18">招商银行</span>
                </td>
            </tr>
            <tr>
                <td>
                    <span class="">提现金额：</span>
                </td>
                <td>
                    <span class="f18">1.00</span>元
                </td>
            </tr>
            <tr>
                <td>
                    <span class="">手续费：</span>
                </td>
                <td>
                    <span class="f18">1.00</span>元
                    <span class="f12">(每日首次提现免手续费)</span>
                </td>
            </tr>
            <tr>
                <td>
                    <span class="">实际到账：</span>
                </td>
                <td>
                    <span class="f18">1.00</span>元
                </td>
            </tr>
            </tbody>
        </table>
    </div>
    <!-- 顶部红色部分 end-->
    <div class="row alert-danger">
        <div class="pd10">
            为加强资金安全，需要在首次提现至该银行卡时补充开户行信息，如果无法确定，建议您致电银行客服咨询。
        </div>
    </div>

    <div>
        <div class="row  tonglan_input  form-group-lg">
            <p class="ml10 mt10">
                开户地点：
            </p>

            <div class="bg_white">
                <input id="city" readonly="readonly" placeholder="请输入开户地点" type="text"
                       class="input-group-lg form-control">
            </div>
        </div>
        <div class="row  tonglan_input  form-group-lg">
            <p class="ml10 mt10">
                支行名称：
            </p>

            <div class="bg_white">
                <input placeholder="请输入支行名称" type="text" class="input-group-lg form-control">
            </div>
        </div>

        <div class="row alert-danger">
            <div class="pd10">
                特别提醒：此处无需重复填写银行名称与省市，只需填写具体支行名称。例如：学院路支行
            </div>
        </div>

        <div class="row  tonglan_input  form-group-lg">
            <p class="ml10 mt10">
                手机号码：
            </p>

            <div class="ml10 mr10 f18">
                15888888888
            </div>
        </div>

        <div class="row  tonglan_input   form-group-lg">
            <p class="ml10 mt10">
                短信验证码：
            </p>
            <table class="table_nopd bg_white" width="100%" border="0" cellspacing="0" cellpadding="0">
                <tbody>
                <tr>
                    <td>
                        <input placeholder="请输入短信验证码" type="text" class="input-group-lg form-control">
                    </td>
                    <td class="text-right">
                        <button class="btn no-radius btn-lg   btn-info send-authcode" send-type="sms">获取验证码</button>
                    </td>
                </tr>
                </tbody>
            </table>

            <p class="text-center mt10">
                一直收不到短信？点此<a href="#" class="c_blue send-authcode" send-type="voice">语音获取验证码</a>
            </p>
        </div>

        <div class="col-lg-12 row  mb10">
            <button type="submit" class="btn btn-danger   btn-lg btn-block"
                    onclick="javascript:window.location.replace('/index.php/mobiles/home/transfer_success')">
                确认
            </button>
        </div>

    </div>

    <!-- 弹出消息层 -->
    <div class="info_black text-center">
        稍后聚雪球将通过电话4007-918-333拨打
        您的手机13358017639告知验证码
    </div>
    <!-- 弹出消息层 end-->

</div>

<div id="datePlugin"></div>
</body>
<?php $this->load->view('common/mobiles/app_footer') ?>
<script type="text/javascript">
    var cityone = "<li name='123'>&nbsp;</li><li name='123'>重庆</li><li name='123'>重庆</li><li name='123'>重庆</li><li>&nbsp;</li>"
    var citytwo = "<li>&nbsp;</li><li name='123'>重庆</li><li name='123'>重庆</li><li name='123'> 重庆</li><li>&nbsp;</li>"
    var citythree = "<li>&nbsp;</li><li>重庆</li><li>重庆</li><li>重庆</li><li>&nbsp;</li>"

</script>
<script src="/assets/js/app/addr/addr.js"></script>
<script src="/assets/js/app/addr/isscroll.js"></script>
<script type="text/javascript">
    $(function () {
        $('#city').addr();

        //发送验证码处理
        send_authcode(phone, 'transfer');
    });
</script>
</html>