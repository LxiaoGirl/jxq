<!DOCTYPE html>
<html>
<head lang="en">
  <title>项目投标</title>
  <?php $this->load->view('common/mobiles/app_head') ?>
</head>
<body>
<!-- 公共头部导航-->
<?php $this->load->view('common/mobiles/app_common_head') ?>

  <div class="container-fluid">
    <!-- 顶部导航  -->
    <div class="bg_red_jb row mb10 c_fff">
        <div class="fl f16 mt10 ml10">
            <?php echo $subject; ?> <!-- 名字 -->
        </div>
        <div class="fr mt10 mr10">
            状态：<?php echo borrow_status($status); ?>  <!-- 状态 -->
        </div>
        <div class="clears"></div>

        <div class="text-center">
            <div class="superbig">
                <span><?php echo rate_format($rate); ?></span>%    <!--年化收益率-->
            </div>
            <span class="f16">年化收益率</span>
        </div>

        <table class="c_fff f12  mt15 mb15" width="100%" border="0" cellspacing="0" cellpadding="0">
            <tbody>
            <tr>
                <td style="border-bottom:1px #e97e79 solid;border-right:1px #e97e79 solid;" valign="bottom" class="text-center" width="50%">
                    <div>
                        <strong class="f18"><?php echo rate_format(price_format($amount, 2, FALSE)); ?></strong>元
                    </div>
                    <span>融资金额</span>
                </td>

                <td style="border-bottom:1px #e97e79 solid" valign="bottom" class="text-center" width="50%">
                    <div>
                        <strong class="f18"><?php echo rate_format(price_format($amount-$receive, 2, FALSE)); ?></strong>元
                    </div>
                    <span>剩余可投金额</span>
                </td>
            </tr>

            <tr>
                <td style="border-right:1px #e97e79 solid;" valign="bottom" class="text-center" width="50%">
                    <div>
                        <strong class="f18"><?php echo $months; ?></strong>个月
                    </div>
                    <span>借款期限</span>
                </td>

                <td valign="bottom" class="text-center" width="50%">
                    <div>
                        <strong class="f18" id="wysy"></strong>元
                    </div>
                    <span>万元收益</span>
                </td>
            </tr>
            </tbody>
        </table>
    </div>
    <!-- 顶部导航  end-->

    <p class="text-right">
        预期收益
    </p>
    <div class="row">
        <div class="clears borderb"></div>
        <table class="bijiao_jdt" width="100%" border="0" cellspacing="0" cellpadding="0">
            <tbody>
            <tr>
                <td>
                    <div class="rel">
                        <span class="abs mz">聚雪球车贷宝</span>
                        <span id="bg_a" class="shouyi_bg" style="background:#f86960"></span><!--背景-->
                    </div>
                </td>
                <td width="100" class="text-right">
                    <span id="v_a" class="f16 text-nowrap">0.00元</span>
                </td>
            </tr>
            <tr>
                <td>
                    <div class="rel">
                        <span class="abs mz">货币基金</span>
                        <span id="bg_b" class="shouyi_bg" style="background:#00acee"></span><!--背景-->
                    </div>
                </td>
                <td width="100" class="text-right">
                    <span id="v_b" class="f16 text-nowrap">0.00元</span>
                </td>
            </tr>
            <tr>
                <td>
                    <div class="rel">
                        <span class="abs mz">银行活期</span>
                        <span id="bg_c" class="shouyi_bg" style="background:#88cb5a"></span><!--背景-->
                    </div>
                </td>
                <td width="100" class="text-right">
                    <span id="v_c" class="f16 text-nowrap">0.00元</span>
                </td>
            </tr>
            </tbody>
        </table>
    </div>
    <!-- 底部按钮  -->
    <div class="row rel">
        <div class="bg_white">
            <table class="table_nopd" width="100%" border="0" cellspacing="0" cellpadding="0">
                <tbody>
                <tr>
                    <td>
                        <div class="bg_white">
                            <input id="jine" placeholder="请输入100元以上的金额" style="display:block; border:none;" type="text" class="no-radius form-control  input-lg">
                        </div>
                    </td>
                    <td width="80">
                        <button id="submit"  type="button" class="btn fr no-radius btn-danger btn-lg" >投标</button>
                        <!-- 车贷宝收益，基金收益，银行活期利率：我随便写的值 onclick="JS_calc(0.1288,0.1,0.03)" -->
                    </td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>
    <!-- 底部按钮  end-->
  </div>
  <?php $this->load->view('common/mobiles/app_alert') ?>
</body>
<?php $this->load->view('common/mobiles/app_footer') ?>
<script src="/assets/js/app/functions.js"></script>
<script>
    /**
     * 计算器
     * @param amount 总额
     * @param rate 年利率
     * @param months 月数
     * @param mode 类型
     */
    var calculator=function(amount,rate,months,mode){

        if(!rate)rate=parseFloat('<?php echo $rate;?>');
        if(!months)months=parseFloat('<?php echo $months;?>');
        if(!mode)mode=parseInt('<?php echo $mode;?>');
        amount=parseInt(amount) | 0;
        var amounts=parseFloat('<?php echo $amount - $receive;?>');
//        if(amount > amounts) amount=amounts; //验证输入与总额
        var interest=0;
        if(amount && rate && months && mode){
            switch (mode){
                case 1://先息后本 *100再/100是去整保留两位小数
                    interest=Math.round(amount*(rate/100/360)*(months*30) * 100)/100;
                    break;
                case 2://等额本息
                    rate=rate/100/12;//月利率
                    var m_amount=amount*rate*Math.pow((1+rate),months)/(Math.pow((1+rate),months)-1);//每月金额
                    interest=Math.round((m_amount*months-amount) * 100)/100;
                    break;
                case 3://一次性
                    interest=Math.round(amount*(rate/100/360)*(months*30) *100)/100;
                    break;
                case 4://等额本金
                    interest=Math.round(((months+1)*amount*((rate/100)/12)/2)*100)/100
                    break;
                default :
            }
        }
        return (interest);
    };
    $(function(){
        $("#wysy").text(calculator(10000));
        var s_amount = '<?php echo $amount-$receive ?>';
        var lowest = '<?php echo $lowest; ?>';
        var max = '<?php echo $max; ?>';
        $("#submit").on('tap',function(){
            check_to_login();
            if($('#jine').val() == '' || parseInt($('#jine').val()) < parseInt(lowest)){
                my_alert('最低投资额度'+lowest+'元<br/>' +
                '请输入'+lowest+'元及以上的金额！',2);
                return false;
            }
            if(parseInt($('#jine').val()) > parseInt(s_amount)){
                my_alert('你的投资金额大于剩余可投金额！',2);
                return false;
            }
            if(parseInt($('#jine').val()) > parseInt(max)){
                my_alert('最大可投金额'+max+'元<br/>' +
                '你的投资金额已大于剩余最大可投金额！',2);
                return false;
            }
            window.location.href='<?php echo site_url('mobiles/home/project_invest_confirm?borrow_no='.$borrow_no.'&amount=') ?>'+$('#jine').val();
        })
        $("#jine").on('keyup',function(){
            if(isNaN($(this).val()))$(this).val(0);
            if(parseInt($("#jine").val()) > parseInt(s_amount)){
                $(this).val(s_amount);
            }
            JS_calc(calculator($("#jine").val()),calculator($("#jine").val(),6),calculator($("#jine").val(),4));
        });
    });
</script>
</html>