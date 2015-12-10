<!DOCTYPE html>
<html>
<head lang="en">
  <title>投资明细</title>
    <?php $this->load->view('common/mobiles/m_app_head') ?>
    <link rel="stylesheet" type="text/css" href="/assets/css/app/m-popularize.css">
</head>
<body>
<!-- 公共头部导航-->
    <?php $this->load->view('common/mobiles/app_common_head') ?>
    <div class="placehold"></div>
    <div class="con_wap">
       <div class="invest_top">
            <div class="left_invest_top">
                <div class="touxiang"><img src="../../../assets/images/app/invest_de.png"/></div>
            </div>
            <div class="rig_invest_top">
                <p class="mobile_self">张三<font>13555854988</font></p>
                <p class="mobile_self" style="margin-top:1rem;">投资总额</p>
                <p class="invest_nub"><font>¥</font>10000.00</p>
            </div>
       </div>
       <div class="invest_cen">
            <p class="title_top"><font class="frist">时间</font><font>项目</font><font>投资金额</font><font class="last">状态</font></p>
            <p><font class="frist">2015-08-24</font><font>车贷宝1号</font><font>20000.00</font><font class="last">还款中</font></p>
            <p><font class="frist">2015-08-24</font><font>车贷宝1号</font><font>20000.00</font><font class="last">还款中</font></p>
            <p><font class="frist">2015-08-24</font><font>车贷宝1号</font><font>20000.00</font><font class="last">还款中</font></p>
            <p><font class="frist">2015-08-24</font><font>车贷宝1号</font><font>20000.00</font><font class="last">还款中</font></p>
            <!--<p>无记录</p>-->
       </div>
    </div>
    <?php $this->load->view('common/mobiles/app_alert') ?>
</body>
  <?php $this->load->view('common/mobiles/app_footer') ?>
<script type="text/javascript">
    $(document).ready(function(){
        $(".tcnav.fl").click(function (){
            $(".tab-ctorl").addClass("tcnav1");
            $(".tab-ctorl").removeClass("tcnav2");
            $(".tab-con1").show();
            $(".tab-con2").hide();
        })
        $(".tcnav.fr").click(function (){
            $(".tab-ctorl").addClass("tcnav2");
            $(".tab-ctorl").removeClass("tcnav1");
            $(".tab-con2").show();
            $(".tab-con1").hide();
        })
    }); 
</script>
</html>