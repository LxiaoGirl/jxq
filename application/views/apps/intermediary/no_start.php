<!DOCTYPE html>
<html>
<head>
    <title>提示</title>
    <meta name="description" content=""/>
    <meta name="viewport" content="width=device-width, initial-scale=1,user-scalable=no"/>
    <meta http-equiv="X-UA-Compatible" content="IE=Edge,chrome=1"/>
    <meta http-equiv="Cache-Control" content="no-siteapp"/>
    <meta charset="utf-8"/>
	<meta name="baidu-site-verification" content="NWiIzGM1AG" />
    <link href="/assets/css/pages/mycss.css" rel="stylesheet" type="text/css">
</head>

<body class="content_wrap">
    <div class="wrap">
    	<!-- <div class="header">
        	<h1>聚雪球理财平台</h1>
            <a class="hlogo" href="JavaScript:void(0);"><span>聚雪球</span></a>
        </div> -->
        <div style="width:256px;margin:0 auto;margin-top:10%;">
            <?php if(strtotime($start_time) > time()): ?>
                <h3 style="text-align:center;">开始时间：<?php echo $start_time; ?></h3>
                <h3 style="text-align:center;margin-top:10px;">敬请期待！</h3>
            <?php elseif(strtotime($end_time) < time()): ?>
                <h3 style="text-align:center;">结束时间：<?php echo $end_time; ?></h3>
                <h3 style="text-align:center;margin-top:10px;">感谢您的参与！</h3>
                <h3 style="text-align:center;margin-top:10px;">如果你已经申请过请登录查看相关信息！</h3>
            <?php else: ?>
                <h3 style="text-align:center;margin-top:10px;">活动进行中...</h3>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
