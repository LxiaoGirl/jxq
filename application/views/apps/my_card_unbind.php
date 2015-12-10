<!DOCTYPE html>
<html>
<head lang="en">
    <title>解绑银行卡</title>
    <?php $this->load->view('common/apps/app_head') ?>
</head>
<body>

<div class="container-fluid">
    <!--    <div class="row">-->
    <!--        <table width="100%" border="0" cellspacing="0" cellpadding="0">-->
    <!--            <tbody>-->
    <!--            <tr>-->
    <!--                <td width="90">-->
    <!--                    真实姓名：-->
    <!--                </td>-->
    <!--                <td>-->
    <!--                    --><?php //echo $real_name; ?>
    <!--                </td>-->
    <!--            </tr>-->
    <!--            <tr>-->
    <!--                <td>-->
    <!--                    开户银行：-->
    <!--                </td>-->
    <!--                <td>-->
    <!--                    <img src="/assets/images/app/yingh/-->
    <?php //echo $code; ?><!--.png" width="20" height="20" alt="">-->
    <!--                    --><?php //echo $bank_name; ?>
    <!--                </td>-->
    <!--            </tr>-->
    <!---->
    <!--            <tr>-->
    <!--                <td>-->
    <!--                    银行卡号：-->
    <!--                </td>-->
    <!--                <td>-->
    <!--                    --><?php //echo $account; ?>
    <!--                </td>-->
    <!--            </tr>-->
    <!--            </tbody>-->
    <!--        </table>-->
    <!--    </div>-->
    <!--    <div action="#">-->
    <!--        <div class="row  tonglan_input  form-group-lg">-->
    <!--            <p class="ml10 mt10">-->
    <!--                资金密码：-->
    <!--            </p>-->
    <!--            <div class="bg_white">-->
    <!--                <input name="security" placeholder="请输入资金密码" type="password" class="input-group-lg form-control">-->
    <!--            </div>-->
    <!--        </div>-->
    <!--        <div class="row  tonglan_input  form-group-lg">-->
    <!--            <p class="ml10 mt10">-->
    <!--                手机验证码：-->
    <!--            </p>-->
    <!--            <div class="bg_white">-->
    <!--                <input name="authcode" placeholder="请输入手机验证码" type="number" class="input-group-lg form-control">-->
    <!--            </div>-->
    <!--        </div>-->
    <!--        <div class="row mb10">-->
    <!--            <div class="col-xs-6  mt10">-->
    <!--                <button class="btn btn-success btn-block send-authcode" send-type="sms">发送短信</button>-->
    <!--            </div>-->
    <!---->
    <!--            <div class="col-xs-6  mt10">-->
    <!--                <button class="btn    btn-info btn-block send-authcode" send-type="voice">收听语音验证码</button>-->
    <!--            </div>-->
    <!--        </div>-->
    <!--        <div class="col-lg-12 row  mb20">-->
    <!--            <button id="submit" class="btn btn-danger   btn-lg btn-block">-->
    <!--                完成-->
    <!--            </button>-->
    <!--        </div>-->
    <!--    </div>-->
    <h4 style="text-align: center;margin-top: 45%;">请拨打聚雪球电话4007-918-333进行银行卡解绑操作</h4>
</div>
<?php $this->load->view('common/apps/app_alert') ?>
</body>
<?php $this->load->view('common/apps/app_footer') ?>
<script type="text/javascript">
    //    var phone = '<?php //echo profile('mobile') ?>//';
    //    $(function(){
    //        //发送验证码处理
    //        send_authcode(phone,'unbindcard');
    //
    //        $("#submit").on('tap',function(){
    //            var auth=/^[0-9]{6}$/;
    //             if($("input[name='security']").val() == ''){
    //                my_alert('请输入资金密码！');
    //            }if(! auth.test($("input[name='authcode']").val())){
    //                my_alert('请输入正确格式的验证码！');return false;
    //            }else{
    //                $.post('/index.php/apps/home/my_card_unbind',{
    //                    'security':$("input[name='security']").val(),
    //                    'card_no':'<?php //echo $card_no; ?>//',
    //                    'authcode':$("input[name='authcode']").val()
    //                },function(rs){
    //                    if(rs.code == 0){
    //                        window.location.replace('/index.php/apps/home/my_card/success');
    //                    }else{
    //                        my_alert(rs.msg);
    //                    }
    //                },'json');
    //            }
    //            return false;
    //        })
    //    });
</script>
</html>