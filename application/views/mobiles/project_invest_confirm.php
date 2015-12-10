<!DOCTYPE html>
<html>
<head lang="en">
    <title>投资确认</title>
    <?php $this->load->view('common/mobiles/app_head') ?>
</head>
<body>
<!-- 公共头部导航-->
<?php $this->load->view('common/mobiles/app_common_head') ?>

<div class="container-fluid">
    <div class="mt10">
        <div class="borderb row infoheader">
            <h1 class="c_333 f18 mr10 mr10"><?php echo $subject; ?></h1>

            <p class="c_888"><?php echo borrow_mode($mode); ?></p>
        </div>

        <div class="borderb row mb10">
            <table class="infotable" width="100%" border="0" cellspacing="0" cellpadding="0">
                <tbody>
                <tr>
                    <th>
                        <span class="c_888">还款期限：</span>
                    </th>
                    <td class="text-right">
                        <span class="f20 c_333"><?php echo $months; ?>个月</span>
                    </td>
                </tr>
                <tr>
                    <th>
                        <span class="c_888">年化利率：</span>
                    </th>
                    <td class="text-right">
                        <span class="f20 c_333"><?php echo rate_format($rate); ?>%</span>
                    </td>
                </tr>
                <tr>
                    <th>
                        <span class="c_888">项目可投金额：</span>
                    </th>
                    <td class="text-right">
                        <span class="f20 c_333"><?php echo rate_format(price_format($amount - $receive, 2, FALSE)); ?>
                            元</span>
                    </td>
                </tr>
                </tbody>
            </table>
        </div>

        <form action="#" role="form">
            <div class="row  tonglan_input  form-group-lg">
                <p class="ml10 c_888">
                    请输入投资金额(100元起投)
                </p>

                <div class="bg_white mb10">
                    <table class="table_nopd" width="100%" border="0" cellspacing="0" cellpadding="0">
                        <tbody>
                        <tr>
                            <td>
                                <input id="zijin" placeholder="请输入投资金额" type="number"
                                       class="input-group-lg form-control"
                                       value="<?php if (!empty($to_invest)):echo $to_invest;endif; ?>"
                                       data-min="<?php echo $lowest; ?>"
                                       data-max="<?php echo round($amount - $receive); ?>">
                            </td>
                            <td>
                                <button id="max" type="button" class="btn-yuan btn-danger">MAX</button>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>
                <table width="100%" border="0" cellspacing="0" cellpadding="0">
                    <tbody>
                    <tr>
                        <td class="f18">
                            <span class="c_888 text-nowrap">账户余额：</span>
                            <span id="yue" class="c_333"><?php echo $balance; ?></span>
                        </td>
                        <td class="text-right">
                            <a href="<?php echo site_url('mobiles/home/recharge'); ?>"
                               class="btn btn-lg btn-info">去充值</a>
                        </td>
                    </tr>
                    </tbody>
                </table>

            </div>

            <div class="container">
                <div class="row mt10 mb20">
                    <button id="btn_toubiao" type="button" class="btn btn-lg btn-danger btn-block">投标</button>
                </div>
            </div>
        </form>
    </div>
</div>
<!-- 弹出消息层
<div class="info_black text-center  myalert">
账户余额不足
</div>
  弹出消息层 end-->
<div class="cd-popup" role="alert">
    <div class="cd-popup-container">
        <div class="f14 text-center mb10">输入资金密码</div>
        <div class="text-center">
            <div>
                <div class="form-group">
                    <input id="secu" name="security" placeholder="请输入资金密码" type="password"
                           class="input-group-lg form-control">
                </div>
                <button class="btn JS_quit btn-default">取消</button>
                <button id="ok" class="btn btn-info">确认</button>
            </div>
        </div>
    </div>
    <!-- cd-popup-container -->
</div>

<?php $this->load->view('common/mobiles/app_alert') ?>
</body>
<?php $this->load->view('common/mobiles/app_footer') ?>
<script>
    var isLogin = '<?php echo $master;?>';
    var security = '<?php echo (profile('security') != '') ? 1: 0;?>';
    var myInvest = function (mon) {
        if (isLogin) {
            if (security == '0') {
                my_alert('亲，您还没有设置资金密码哦！');
                setTimeout(function () {
                    window.location.href = '<?php echo site_url('mobiles/home/security');?>';
                }, 2000);
                return;
            }
            var money = $("#" + mon).val();
            var maxval = $("#" + mon).attr("data-max");
            var minval = $("#" + mon).attr("data-min");
            var balance = '<?php echo $balance; ?>';
            var reg = /^[1-9]\d*$/;
            if (money == "") {
                my_alert('请输入投资金额');
            } else if (!reg.test(money)) {
                my_alert('投资金额必须为正整数');
            } else if (parseInt(maxval) == 0) {
                my_alert('融资已完成 投资要趁早哦！');
            } else if (parseInt(maxval) < parseInt(money)) {
                my_alert('投资金额不能大于￥' + maxval + '元');
            } else if (parseInt(minval) > parseInt(money)) {
                my_alert('投资金额不能小于￥' + parseInt(minval) + '元');
            } else if (parseInt(money) % parseInt(minval) != 0) {
				var bei = Math.floor(money/minval)*minval;
				my_alert('投资金额需要是最低投资金额' + parseInt(minval) + '元的整数倍', 2);
				$('#zijin').val(bei);
            } else if (parseInt(money) - parseInt(balance) > 0) {
                my_alert('你的余额不足 请充值！');
            } else if ('<?php echo $master;?>' == '<?php echo $uid;?>') {
                my_alert('不能投自己的标哦！');
            } else {
                $('.cd-popup').addClass('is-visible');
            }
        } else {
            my_alert('亲，您还没有登录哦！');
            to_app_login();
        }
        return false;
    }
    var check_secrity = function (mon, borrow_no) {
        var secu = $("#secu").val();
        var money = $("#" + mon).val();
        var maxval = $("#" + mon).attr("data-max");
        var minval = $("#" + mon).attr("data-min");
        if (secu != '') {
            $('.cd-popup').removeClass('is-visible');
            $("#btn_toubiao").prop('disabled', true).text('投标中...');
            $.ajax({
                type: 'POST',
                url: '<?php echo site_url('mobiles/home/ajax_invest') ?>',
                data: {amount: money, borrow_no: borrow_no, password: secu},
                dataType: 'json',
                success: function (result) {
                    $("#btn_toubiao").prop('disabled', false).text('投标');
                    if (result.code == 0) {
                        window.location.href = '<?php echo site_url('mobiles/home/project_invest_success') ?>';
                    } else {
                        my_alert(result.msg);
                    }
                }
            });
        } else {
            my_alert('资金密码不能为空！');
        }
    }
    $(function () {
        var s_amount = '<?php echo $amount-$receive ?>';
        var balance = '<?php echo $balance; ?>';
        $(".JS_quit").click(function () {
            $(this).parents('.cd-popup').removeClass('is-visible');
        });
        $("#max").on('tap', function () {
            var max = balance > s_amount ? s_amount : balance;
            $("#zijin").val(max);
        });
        $("#btn_toubiao").on('click', function () {
            myInvest('zijin');
        });
        $("#ok").on('tap', function () {
            check_secrity('zijin', '<?php echo $borrow_no ?>');
        })
    });
</script>
</html>