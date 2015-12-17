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
                <p class="mobile_self">总投资</p>
                <p class="mobile_self" style="margin-top:3rem;">总收益</p>
            </div>
            <div class="rig_invest_top">
                <p class="invest_nub tr" style="margin-top:0.6rem;"><font>¥</font>10000.00</p>
                <p class="invest_nub tr" style="margin-top:0.6rem;"><font>¥</font>10000.00</p>
            </div>
       </div>
       <div class="invest_cen widpf">
            <p class="title_top"><font>名称</font><font>总投资(元)</font><font>时间</font></p>
            <p><font class="frist">张三</font><font>1000000</font><font class="last">2015-08-2413:25:09</font></p>
            <p><font class="frist">李四</font><font>1000000</font><font class="last">2015-08-2413:25:09</font></p>
            <p><font class="frist">王五</font><font>1000000</font><font class="last">2015-08-2413:25:09</font></p>
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