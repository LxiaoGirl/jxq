<!DOCTYPE html>
<html>
<head lang="en">
  <title>聚雪球</title>
    <?php $this->load->view('common/mobiles/m_app_head') ?>
    <link rel="stylesheet" type="text/css" href="/assets/css/app/m-popularize.css">
</head>
<body>
<!-- 公共头部导航-->
    <?php $this->load->view('common/mobiles/app_common_head') ?>
    <div class="placehold"></div>
    <div class="con_wap">
       <div class="popularize_center_top">
            <p class="ft28 tl">总收益(元)：</p>
            <p class="ft70 tr">10000.00<font>元</font></p>
       </div>
       <div class="popularize_center_tab">
            <div class="tab-ctorl tcnav1">
                <div class="tcnav tcnav1 fl tc">客户</div>
                <div class="tcnav tcnav2 fr tc">我的收益</div>
            </div>
            <div class="tab-con tab-con1">
                <p class="tc"><font>用户名</font><font>总投资(元)</font><font>活跃度</font></p>
                <p class="tc" onclick=""><font>张三</font><font>1000000</font><font>活跃</font></p>
            </div>
            <div class="tab-con tab-con2">
                <p class="tc"><font>时间</font><font>收益(元)</font><font>状态</font></p>
                <p class="tc" onclick=""><font>2015年9月</font><font>1000000</font><font>待结算</font></p>
            </div>
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