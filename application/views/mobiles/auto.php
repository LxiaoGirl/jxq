<!DOCTYPE html>
<html>
<head lang="en">
    <title>聚雪球</title>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <!--H5页面窗口自动调整到设备宽度，并禁止用户缩放页面-->
    <meta name="viewport"
          content="width=device-width,initial-scale=1.0,minimum-scale=1.0,maximum-scale=1.0,user-scalable=no"/>
    <!-- 忽略将页面中的数字识别为电话号码 -->
    <meta name="format-detection" content="telephone=no"/>
    <!-- 忽略Android平台中对邮箱地址的识别 -->
    <meta name="format-detection" content="email=no"/>
    <!-- 当网站添加到主屏幕快速启动方式，可隐藏地址栏，仅针对ios的safari -->
    <!-- ios7.0版本以后，safari上已看不到效果 -->
    <meta name="apple-mobile-web-app-capable" content="yes"/>
    <!-- 将网站添加到主屏幕快速启动方式，仅针对ios的safari顶端状态条的样式 -->
    <!-- 可选default、black、black-translucent -->
    <meta name="apple-mobile-web-app-status-bar-style" content="black"/>
    <!-- winphone系统a、input标签被点击时产生的半透明灰色背景怎么去掉 -->
    <meta name="msapplication-tap-highlight" content="no">
    <link rel="stylesheet" type="text/css" href="/assets/js/app/flexslide/css/flexslider-m.css">
    <link rel="stylesheet" type="text/css" href="/assets/css/app/radialindicator.css">
    <link rel="stylesheet" type="text/css" href="/assets/css/app/head.css">
    <link rel="stylesheet" type="text/css" href="/assets/css/app/m-common.css">
    <link rel="stylesheet" type="text/css" href="/assets/css/app/m_zdtb.css">
</head>
<body>
<!-- 公共头部导航-->
<?php $this->load->view('common/mobiles/app_common_head') ?>
<div class="placehold"></div>
<div class="con_wap">
    <!--未开启-->
    <div class="row zdkg">
        <p class="tot">自动投标：已关闭</p>
        <p>账户有余额时是否自动进行投标</p>
        <a href="<?php echo site_url('mobiles/home/auto_form');?>">
            <img class="no" src="../../../assets/images/app/zdtb_n_n.jpg" style="display:block">
        </a>
    </div>
    <div class="row zdtbsm">
        <div class="smt">
            <h2 zk="0">什么是自动投标？<img src="../../../assets/images/app/zdtb_yjt.jpg"></h2>
            <p>聚雪球平台根据用户设定的自动投标配置，在发布标的的时候优先进行标的投资。</p>
        </div>
        <div class="smt">
            <h2 zk="0">自动投标收费吗？<img src="../../../assets/images/app/zdtb_yjt.jpg"></h2>
            <p>平台暂时免费为用户开通自动投资服务，如今后需要收取服务费时，会进行通告。</p>
        </div>
        <div class="smt">
            <h2 zk="0">如何取消自动投标？<img src="../../../assets/images/app/zdtb_yjt.jpg"></h2>
            <p>1. 按照用户在自动投标中设置的有效期，有效期到期后，系统将不再为用户提供自动投资服务。</p>
			<p>2. 用户主动关闭自动投标功能后，系统将不再为用户提供自动投资服务。</p>
        </div>
    </div>
    <!--未开启-->


</div>
<?php $this->load->view('common/mobiles/app_alert') ?>
</body>
<?php $this->load->view('common/mobiles/app_footer') ?>
<script>
    //1111
    $('.smt').find('h2').click(function(){
        if($(this).attr("zk")==0){
            $(this).parent().find('p').slideDown();
            $(this).find('img').addClass('rot90'); 
            $(this).attr("zk","1"); 
        }else{
            $(this).parent().find('p').slideUp();
            $(this).find('img').removeClass('rot90');
            $(this).attr("zk","0"); 
        }
    })
    //1111
</script>
</html>