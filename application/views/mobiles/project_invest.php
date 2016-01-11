<!DOCTYPE html>
<html>
<head lang="en">
    <title>项目投标</title>
    <?php $this->load->view('common/mobiles/app_head') ?>
    <link rel="stylesheet" type="text/css" href="/assets/css/app/m-common.css">
    <link rel="stylesheet" type="text/css" href="/assets/css/app/m-project_invest.css">
</head>
<body>
<!-- 公共头部导航-->
<?php $this->load->view('common/mobiles/app_common_head') ?>
<div class="placehold"></div>
<div class="con_wap">
    <!-- 顶部导航  -->
    <div class="row_top">
        <div class="use_name">
            <div class="tl half-img fl">
                <span class="wzjz tl"><?php echo $subject; ?> <!-- 名字 --></span>
            </div>
            <div class="tr half-img fl">
                <span class="wzjz tr">状态：<?php echo $status_name; ?>  <!-- 状态 --></span>
            </div>
        </div>
        <div class="rate">
            <div class="rate_big">
                <span class="wzjz tc"><?php echo rate_format($rate); ?><font>% </font></span> <!--年化收益率-->
            </div>
            <div class="rate_com"><span class="wzjz tc">年化收益率</span></div>
        </div>
        <div class="xgxx">
            <div class="wid49 bor-bot bor-ri">
                <div class="hei50">
                    <span
                        class="tc"><strong><?php echo rate_format(price_format($amount, 2, FALSE)); ?></strong>元</span>
                </div>
                <div class="hei50">
                    <span class="tc">融资金额</span>
                </div>
            </div>
            <div class="wid49 bor-bot">
                <div class="hei50">
                    <span
                        class="tc"><strong><?php echo rate_format(price_format($amount - $receive, 2, FALSE)); ?></strong>元</span>
                </div>
                <div class="hei50">
                    <span class="tc">剩余可投金额</span>
                </div>
            </div>
            <div class="wid49 bor-ri">
                <div class="hei50">
                    <span class="tc"><strong><?php echo $months; ?></strong>个月</span>
                </div>
                <div class="hei50">
                    <span class="tc">借款期限</span>
                </div>
            </div>
            <div class="wid49">
                <div class="hei50">
                    <span class="tc"><strong id="wysy"></strong>元</span>
                </div>
                <div class="hei50">
                    <span class="tc">万元收益</span>
                </div>
            </div>
        </div>
    </div>
    <!-- 顶部导航  end-->
    <p class="row_ptr">
        预期收益
    </p>

    <div class="row_cen">
        <div class="rel">
            <div class="rel_left">
                <span class="wzjz">聚雪球车贷宝</span>
                <span id="bg_a" class="shouyi_bg" style="background:#f86960"></span><!--背景-->
            </div>
            <div class="rel_rig">
                <span class="wzjz" id="v_a">0.00元</span>
            </div>
        </div>
        <div class="rel">
            <div class="rel_left">
                <span class="wzjz">货币基金</span>
                <span id="bg_b" class="shouyi_bg" style="background:#00acee"></span><!--背景-->
            </div>
            <div class="rel_rig">
                <span class="wzjz" id="v_b">0.00元</span>
            </div>
        </div>
        <div class="rel">
            <div class="rel_left">
                <span class="wzjz">银行活期</span>
                <span id="bg_c" class="shouyi_bg" style="background:#88cb5a"></span><!--背景-->
            </div>
            <div class="rel_rig">
                <span class="wzjz" id="v_c">0.00元</span>
            </div>
        </div>
    </div>
    <!-- 底部按钮  -->
    <div class="row_rel">
        <div class="row_left">
            <input id="jine" placeholder="投资金额需要是<?php echo $lowest; ?>元的整数倍" style="display:block; border:none;" type="text"
                   class="no-radius form-control  input-lg">
        </div>
        <div class="row_rig">
            <button id="submit" type="button" class="btn fr no-radius btn-danger btn-lg">投标</button>
        </div>
    </div>
    <!-- 底部按钮  end-->
</div>
<?php $this->load->view('common/mobiles/app_alert') ?>
</body>
<?php $this->load->view('common/mobiles/app_footer') ?>
<script type="text/javascript">
    //页面整页展示
    var hh = $(window).height();
    $("body").height(hh);
    $(".con_wap").height(hh - 50);
</script>
<script src="/assets/js/app/functions.js"></script>
<script>
    /**
     * 计算器
     * @param amount 总额
     * @param rate 年利率
     * @param months 月数
     * @param mode 类型
     */
    var calculator = function (amount, rate, months, mode) {

        if (!rate)rate = parseFloat('<?php echo $rate;?>');
        if (!months)months = parseFloat('<?php echo $months;?>');
        if (!mode)mode = parseInt('<?php echo $mode;?>');
        amount = parseInt(amount) | 0;
        var amounts = parseFloat('<?php echo $amount - $receive;?>');
//        if(amount > amounts) amount=amounts; //验证输入与总额
        var interest = 0;
        if (amount && rate && months && mode) {
            switch (mode) {
                case 1://先息后本 *100再/100是去整保留两位小数
                    interest = Math.round(amount * (rate / 100 / 360) * (months * 30) * 100) / 100;
                    break;
                case 2://等额本息
                    rate = rate / 100 / 12;//月利率
                    var m_amount = amount * rate * Math.pow((1 + rate), months) / (Math.pow((1 + rate), months) - 1);//每月金额
                    interest = Math.round((m_amount * months - amount) * 100) / 100;
                    break;
                case 3://一次性
                    interest = Math.round(amount * (rate / 100 / 360) * (months * 30) * 100) / 100;
                    break;
                case 4://等额本金
                    interest = Math.round(((months + 1) * amount * ((rate / 100) / 12) / 2) * 100) / 100
                    break;
                default :
            }
        }
        return (interest);
    };
    $(function () {
        $("#wysy").text(calculator(10000));
        var s_amount = '<?php echo $amount-$receive ?>';
        var lowest = '<?php echo $lowest; ?>';
        var max = '<?php echo $max; ?>';
        $("#submit").on('tap', function () {
            check_to_login();
            if ($('#jine').val() == '' || parseInt($('#jine').val()) < parseInt(lowest)) {
                my_alert('最低投资额度' + lowest + '元<br/>' +
                '请输入' + lowest + '元及以上的金额！', 2);
                return false;
            }
            if (parseInt($('#jine').val()) > parseInt(s_amount)) {
                my_alert('你的投资金额大于剩余可投金额！', 2);
                return false;
            }
            if (parseInt($('#jine').val()) > parseInt(max)) {
                my_alert('最大可投金额' + max + '元<br/>' +
                '你的投资金额已大于剩余最大可投金额！', 2);
                return false;
            }
			if (parseInt($('#jine').val())%parseInt(lowest) != 0) {
				var bei = Math.floor($('#jine').val()/lowest)*lowest;
				my_alert('投资金额需要是最低投资金额' + lowest + '元的整数倍', 2);
				var bei = Math.floor(parseInt($('#jine').val())/parseInt(lowest))*parseInt(lowest);
				$('#jine').val(bei);
                return false;
            }
			window.location.href = '<?php echo site_url('mobiles/home/project_invest_confirm?borrow_no='.$borrow_no.'&amount=') ?>' + $('#jine').val();
        })
        $("#jine").on('keyup', function () {
            if (isNaN($(this).val()))$(this).val(0);
            if (parseInt($("#jine").val()) > parseInt(s_amount)) {
                $(this).val(s_amount);
            }
            JS_calc(calculator($("#jine").val()), calculator($("#jine").val(), 6), calculator($("#jine").val(), 4));
        });
    });
</script>
</html>