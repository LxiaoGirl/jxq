<!DOCTYPE html>
<html>
<head lang="en">
  <title>2015年7月收益明细</title>
    <?php $this->load->view('common/mobiles/m_app_head') ?>
    <link rel="stylesheet" type="text/css" href="/assets/css/app/m-popularize.css">
</head>
<body>
<!-- 公共头部导航-->
    <?php $this->load->view('common/mobiles/app_common_head') ?>
    <div class="placehold"></div>
    <div class="con_wap">
       <div class="invest_top">
            <div class="top_invest_top">
                客户投资列表
            </div>
            <div class="bot_invest_top">
                <font class="fl">总收益：<?php echo price_format($amount,2,false); ?>元</font><font class="fr" ><?php echo $real_month; ?></font>
            </div>
       </div>
       <div class="invest_cen widpf">
            <p class="title_top"><font style=" width:30%;">客户</font><font style=" width:40%;">投资金额</font><font style=" width:30%;">居间收益</font></p>
            <?php if($data):foreach ($data as $key => $value):?>
                <p><font class="frist"><?php echo $value['user_name']; ?></font><font class="last"><span class="blue"><?php echo $value['subject']; ?></span></br><?php echo $value['invest_amount']?$value['invest_amount']:0; ?></font><font><span class="blue">本月<?php echo $value['real_day']; ?>天</span></br><span class="red">获益：<?php echo $value['settle_amount']?$value['settle_amount']:0; ?></span></font></p>
            <?php endforeach;else: ?>
                <p style="text-align:center;">暂无相关数据.</p>
            <?php endif;?>
            <!--<p>无记录</p>-->
       </div>
    </div>
    <a href="<?php echo site_url('mobiles/intermediary/share_weixin'); ?>"><img class="fixbot" style="display:bolck; float:left" src="/assets/images/app/rejujianren.jpg" width="100%"></a>
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