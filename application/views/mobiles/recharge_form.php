<!DOCTYPE html>
<html>
<head lang="en">
    <title>充值</title>
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
                    <span>真实姓名：</span>
                </td>
                <td>
                    <span class="f18"><?php echo profile('real_name'); ?></span>
                </td>
            </tr>
            <tr>
                <td>
                    <span class="">身份证号：</span>
                </td>
                <td>
                    <span
                        class="f18"><?php echo substr(profile('nric'), 0, 4) . '*****' . substr(profile('nric'), -4); ?></span>
                </td>
            </tr>
            </tbody>
        </table>
    </div>
    <!-- 顶部红色部分 end-->
    <form action="<?php echo site_url('mobiles/home/recharge_confirm'); ?>" role="form" onsubmit="return check_form();"
          method="post">
        <div class="row  tonglan_input  form-group-lg">
            <p class="ml10 mt10" style="margin-top:5px; margin-bottom:5px;">
                开户银行：<a href="<?php echo site_url('mobiles/home/recharge_notice'); ?>" style="color:#ff8700;"><font
                        style="display:inline-block;margin-top:2px; width:16px; height:16px;padding:0px 0px 0px 4px; border-radius: 50%; border:1px solid #ff8700; text-align:center; font-size:12px; color:#ff8700;">？</font>充值须知</a>
            </p>

            <div class="bg_white">
                <?php if (isset($card)): ?>
                    <button style="text-align:left;" class="btn btn-block btn-link btn-lg" type="button">
                        <img src="/assets/images/app/yingh/<?php echo $card['code']; ?>.png" width="30" height="30"
                             alt="">
                        <span><?php echo $card['bank_name']; ?></span>
                    </button>
                    <input name="bank_name" type="hidden" value="<?php echo $card['bank_name']; ?>"/>
                    <input name="bank_id" type="hidden" value="<?php echo $card['bank_id']; ?>"/>
                <?php else: ?>
                    <button id="btn_yinhang" style="text-align:left;" type="button"
                            class="btn btn-block btn-link btn-lg">
                        <img src="/assets/images/app/yingh/CMB.png" width="30" height="30" alt="">
                        <span>招商银行</span>
                    </button>
                    <input name="bank_id" type="hidden" id="yinhang" value="308"/>
                    <input name="bank_name" type="hidden" id="yinhang_name" value="招商银行"/>
                <?php endif; ?>
            </div>
        </div>
        <div class="row  tonglan_input  form-group-lg">
            <p class="ml10 mt10">
                银行卡号：
            </p>

            <div class="bg_white">
                <?php if (isset($card)): ?>
                    <input name="account" type="number" class="input-group-lg form-control"
                           value="<?php echo $card['account']; ?>" readonly/>
                    <input name="card_id" type="hidden" value="<?php echo $card['id']; ?>"/>
                <?php else: ?>
                    <input name="account" placeholder="请输入银行卡号" type="text" class="input-group-lg form-control"/>
                    <input name="card_id" type="hidden" value=""/>
                <?php endif; ?>
            </div>
        </div>

        <div class="row  tonglan_input  form-group-lg">
            <p class="ml10 mt10">
                充值金额(最小充值10元)：
            </p>

            <div class="bg_white">
                <input name="amount" placeholder="请输入充值金额" type="text" class="input-group-lg form-control"/>
            </div>
        </div>

        <div class="container">
            <div class="row mt20 mb20">
                <input type="submit" class="btn btn-lg btn-danger btn-block" value="确认充值"/>
            </div>
            <?php if (!(isset($card) && $card['remarks'] != '')): ?>
                <p style="text-align: center;">请使用本人银行卡，非本人银行卡不能完成充值</p>
            <?php endif; ?>
        </div>
    </form>
</div>

<div id="yinhang_tc" class="cd-popup" role="alert">
    <div class="cd-popup-container">
        <div class="f14 text-center mb10">选择开户行</div>
        <div class="text-center" id="yinhang_a" style="max-height: 378px;overflow: auto;">
            <?php if (isset($bank)): ?>
                <?php foreach ($bank as $v): ?>
                    <a style="text-align:left;" class="btn btn-block btn-link btn-lg" href="javascript:void(0)">
                        <img src="/assets/images/app/yingh/<?php echo $v['code'] ?>.png" width="30" height="30" alt="">
                        <span bank_id="<?php echo $v['bank_id']; ?>" bank_name="<?php echo $v['bank_name']; ?>"><?php echo $v['bank_name'] ?></span>
                    </a>
                <?php endforeach;endif; ?>
        </div>
        <div>
            <button class="btn JS_quit btn-link btn-block">取消</button>
        </div>
    </div>
</div>
<?php $this->load->view('common/mobiles/app_alert') ?>
</body>
<?php $this->load->view('common/mobiles/app_footer') ?>
<script>
    //alertFun("账户余额不足",2);/*文本内容，2s后消失*/
    /*选择银行*/
    var yinhang_a_h = $(window).height() - 190;
    $("#yinhang_a").css({
        maxHeight: yinhang_a_h
    });
    $("#btn_yinhang").click(function (event) {
        event.preventDefault();
        $('#yinhang_tc').addClass('is-visible');
    });
    $("#yinhang_a>a").click(function (event) {
        event.preventDefault();
        var yh = $(this).children("span").attr('bank_id');
        $("#yinhang").val(yh);
        $("#btn_yinhang").html($(this).html());
        $("#yinhang_name").val($(this).children("span").text());
        $('#yinhang_tc').removeClass('is-visible');
    });
    /*选择银行 end*/

    //验证银行卡号
    if('<?php echo $card?$card['account']:''; ?>'){
        var submit_flag = true;
    }else{
        var submit_flag = false;
        $("input[name='account']").bind('blur',function(){
            var account_reg = /^[1-9][0-9]{5,}$/;
            if (!account_reg.test($("input[name='account']").val())) {
                my_alert('请输入正确格式的银行账号！');
                return false;
            }
            $.post('<?php echo site_url('mobiles/home/ajax_check_card_bin'); ?>', {'account': $("input[name='account']").val()}, function (rs) {
                if (rs.ret_code == '0000') {
                    if (rs.card_type == '3') {
                        my_alert('不支持信用卡充值，请更换借记卡进行充值！');
                    } else {
                        //核对银行名称
                        if($("[bank_name='"+rs.bank_name+"']").length == 1 && $("#btn_yinhang span").text() != rs.bank_name){
                            $("[bank_name='"+rs.bank_name+"']").parent().click()
                        }
                        submit_flag = true;
                    }
                } else {
                    my_alert(rs.ret_msg + ',请检查你输入的卡号是否正确！');
                }
            }, 'json');
        });
    }

    /*付款确认*/
    var check_form = function () {
        var account_reg = /^[1-9][0-9]{5,}$/;
        var amount_reg = /^\d+(?:\.\d{1,2})?$/;
        if (!account_reg.test($("input[name='account']").val())) {
            my_alert('请输入正确格式的银行账号！');
            return false;
        }
        if (!amount_reg.test($("input[name='amount']").val())) {  //
            my_alert('请输入10元以上充值金额！');

            return false;
        }
        if ($("input[name='amount']").val() < 10) {
            my_alert('请输入10元以上充值金额！');
            return false;
        }
        return submit_flag;
    };
    /*付款确认 end*/

    $(".JS_quit").click(function (event) {
        event.preventDefault();
        $(this).parents('.cd-popup').removeClass('is-visible');
    });
</script>
</html>