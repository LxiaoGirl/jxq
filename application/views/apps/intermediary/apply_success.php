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
</head>

<body>
    <div class="wrap">
<!--    	<div class="header">-->
<!--        	<h1>申请成功</h1>-->
<!--            <a class="hlogo" href="#"><span>聚雪球</span></a>-->
<!--        </div>-->
        <div class="pic_wrapper">
        	<img src="/assets/images/jujianren/pic2.jpg" width="100%" alt="申请成功" />
        </div>
        <a class="zccg" href="javascript:check_to_index();">进入个人中心</a>
    </div>
</body>
<script>
    /**
     * 返回主页
     */
    var check_to_index = function(){
        var u = navigator.userAgent;
        if (u.indexOf('Android') > -1 || u.indexOf('Linux') > -1) {//安卓手机
            window.Jxq.goBack();
        }else{
            window.location.href='<?php echo site_url('apps/home/index'); ?>';
        }
    };
</script>
</html>
