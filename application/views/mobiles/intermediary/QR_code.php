<!DOCTYPE html>
<html>
<head lang="en">
  <title>邀请分享</title>
    <?php $this->load->view('common/mobiles/m_app_head') ?>
    <link rel="stylesheet" type="text/css" href="/assets/css/app/m-yqfq.css">
</head>
<body>
<!-- 公共头部导航-->
    <?php $this->load->view('common/mobiles/app_common_head') ?>
    <div class="con-box">
        <img src="/assets/images/app/yjbj.jpg" width="100%">
        <div class="ewm" style="text-align:center;">
            <!-- 二维码摆放位置 -->
            <div id="qrcode" ></div>
        </div>
        <div class="yqr">邀请人：<?php echo $nickname; ?></div>
        <div class="yqsm">扫码>注册一起赚钱</div>
        <div class="yqfx">（把二维码分享到朋友圈，以获取更多的好友)</div>
    </div>
</body>
  <script type="text/javascript" src="/assets/js/jquery/jquery-1.11.1.min.js"></script>
  <script type="text/javascript" src="/assets/js/app/jquery.qrcode.min.js"></script>
  <script type="text/javascript">
        var w = $(".con-box").width()*0.64,h = w;

      $('#qrcode').qrcode({
          text:'<?php echo site_url("mobiles/intermediary/share_page?inviter_no=".$this->session->userdata("inviter_no")); ?>',
          height: h,
          width: w,
          src:'/assets/images/app/mrtx.png'
      });

  </script>
</html>