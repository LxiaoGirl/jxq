<!DOCTYPE !!>
<html>
<head>
    <title>居间人申请成功</title>
    <meta name="description" content=""/>
    <meta name="viewport" content="width=device-width, initial-scale=1,user-scalable=no"/>
    <meta http-equiv="X-UA-Compatible" content="IE=Edge,chrome=1"/>
    <meta http-equiv="Cache-Control" content="no-siteapp"/>
    <meta charset="utf-8"/>
    <meta name="baidu-site-verification" content="NWiIzGM1AG" />
    <link href="/assets/css/pages/mycss.css" rel="stylesheet" type="text/css">
    <style type="text/css" media="screen">
        p{
            text-align: center;
            margin-top: 2rem;
        }
        .zccg{
            width:40%;
            display: inline-block;
        }
    </style>
</head>

<body style="background:#fff;">
    <div class="wrap">
        <div class="header">
            <h1>申请成功</h1>
            <a class="hlogo" href="#"><span>聚雪球</span></a>
        </div>
        <div class="pic_wrapper" id="img">
            <img src="/assets/images/jujianren/pic2.jpg" width="100%" alt="申请成功" />
        </div>
        <div class="pic_wrapper" style="display:none;width:80%;margin:0 auto;" id="download-image">
            <img src="/assets/images/app/app.png" width="100%" alt="下载APP登陆赚钱更方便" />
            <p>按住二维码识别图片下载APP</p>
        </div>
        <p><a class="zccg" href="javascript:window.location.replace('<?php echo site_url('mobiles/home/my_center'); ?>')"><img src="../../../assets/images/app/jrgrzx.png" width="100%"></a></p>


        <p id="android-download-link"><a style="font-size: 14px;text-decoration: underline;color: #2E9CE3;" href="https://www.juxueqiu.com/snowballapp.apk">下载APP登陆赚钱更方便</a></p>
    </div>
</body>
<script type="text/javascript" src="/assets/js/jquery/jquery-2.1.1.min.js"></script>
<script type="text/javascript">
    $(function(){
        var u = navigator.userAgent;
        if (u.indexOf('iPhone') > -1 || u.indexOf('iPad') > -1) {//苹果手机 ipad
            $("#img").hide();
            $("#android-download-link").hide();
            $("#download-image").show();
        }
    });
</script>
</html>
