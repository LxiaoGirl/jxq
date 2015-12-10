<!DOCTYPE html>
<html>
<head lang="en">
    <title>提现</title>
    <?php $this->load->view('common/mobiles/app_head') ?>
</head>
<body>
<!-- 公共头部导航-->
<?php $this->load->view('common/mobiles/app_common_head') ?>

<div class="container-fluid">
    <div class="bg_white row">
        <table width="100%" border="0" cellspacing="0" cellpadding="0">
            <tbody>
            <tr>
                <td width="50">
                    <img src="/assets/images/app/yingh/<?php echo $card[0]['code']; ?>.png" width="50" height="50"
                         alt="">
                    <?php //if(!empty($card)):echo $card[0]['code']; endif; ?>
                </td>
                <td>
                    <div class="f18 c_333"
                         style="height:30px; overflow:hidden; line-height:30px;"><?php if (!empty($card)):echo $card[0]['bank_name']; endif; ?><?php if (!empty($card)):echo substr($card[0]['account'], -4); endif; ?></div>
                    <p class="c_888" style="height:20px; overflow:hidden; line-height:20px;">单笔20万;单日20万;单月无限额</p>
                </td>
            </tr>
            </tbody>
        </table>
    </div>
    <div role="form">
        <div class="row  tonglan_input  form-group-lg">
            <p class="ml10 mt10">
                输入提现金额(元)：
            </p>

            <div class="bg_white">
                <input placeholder="请输入提现金额" name="amount" id="amount" type="number" class="input-group-lg form-control">
            </div>

            <p class="ml10 mt10 f12 c_666">
                可提现金额：<?php echo $balance; ?>元
            </p>
            <p class="ml10 mt10 f12 c_666" style="visibility:hidden;" id="jtje-p">实际到账金额：<span id="jtje"></span>元</p>

        </div>

        <div class="container">
            <div class="row mt20 mb20">
                <button id="btn_tj" type="button" class="btn btn-lg btn-danger btn-block">确认提现</button>
            </div>
        </div>
        <p style="text-align:center;">每笔提现将扣除2元手续费</p>
    </div>
</div>

<div class="cd-popup" role="alert">
    <div class="cd-popup-container">
        <div class="f14 text-center mb10">输入资金密码</div>
        <div class="text-center">
            <div>
                <div class="bg_white mb10">
                    <input placeholder="请输入资金密码" id="security" type="password" class="input-group-lg form-control">
                </div>
                <div class="bg_white">
                    <input placeholder="请输入短信验证码" id="authcode" type="text" class="input-group-lg form-control">
                </div>
                <div class="row mb10">
                    <div class="col-xs-6  mt10">
                        <button class="btn btn-success btn-block send-authcode" send-type="sms">发送短信</button>
                    </div>

                    <div class="col-xs-6  mt10">
                        <button class="btn    btn-info btn-block send-authcode" send-type="voice">收听语音验证码</button>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-6">
                        <button class="btn JS_quit btn-link btn-block">取消</button>
                    </div>

                    <div class="col-xs-6">
                        <button class="btn ok   btn-link btn-block"><span class="c_red">确认</span></button>
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
    //alertFun("账户余额不足",2);/*文本内容，2s后消失*/
    var phone = '<?php echo profile('mobile') ?>';
    $(function () {
        check_to_login();
        //发送验证码处理
        send_authcode(phone, 'transfer');

        var balance = '<?php echo $balance; ?>';
        var card_no = '<?php echo $card?$card[0]['card_no']:''; ?>';
        $("#btn_tj").click(function (event) {
            event.preventDefault();
            if (!card_no) {
                my_alert('请先绑定银行卡！', 2);
                setTimeout(function () {
                    window.location.replace('<?php echo site_url('mobiles/home/my_card') ?>');
                }, 2000);
                return false;
            }
            if ($("input[name='amount']").val() == '' || isNaN($("input[name='amount']").val()) || parseInt($("input[name='amount']").val()) < 10) {
                my_alert('请输入大于10的提现金额', 2);
                return false;
            }
            if (parseInt($("input[name='amount']").val()) > parseInt(balance)) {
                my_alert('余额不足', 2);
                return false;
            }
            $('.cd-popup').addClass('is-visible');
        });
        $(".JS_quit").click(function (event) {
            event.preventDefault();
            $(this).parents('.cd-popup').removeClass('is-visible');
        });
        $(".ok").on('tap', function () {
            if ($("#security").val() == '') {
                my_alert('请输入资金密码！', 2);
                return false;
            }
            var authcode = /[0-9]{6}/;//6位至20位数字、字母和特殊字符组成
            if (!authcode.test($('#authcode').val())) {
                my_alert('请输入6位数字验证码！');
                return false;
            }
            $.ajax({
                url: '/index.php/mobiles/home/transfer',
                dataType: 'json',
                type: 'post',
                data: {
                    'card_no': card_no,
                    'amount': $(":input[name='amount']").val(),
                    'security': $("#security").val(),
                    'authcode': $("#authcode").val()
                },
                success: function (resut) {
                    if (resut.code == 0) {
                        window.location.replace('<?php echo site_url('mobiles/home/transfer_success?amount='); ?>' + $("input[name='amount']").val());
                    } else {
                        my_alert(resut.msg);
                    }
                }
            });
            return false;
        });
        $('#amount').bind('keyup',function(){
            if(!isNaN($(this).val()) && $(this).val() > 2){
                $('#jtje-p').css('visibility','visible');
                $('#jtje').text($(this).val()-2);
            }else{
                $('#jtje-p').css('visibility','hidden');
                $('#jtje').text(0);
            }
        });
    });
</script>
</html>