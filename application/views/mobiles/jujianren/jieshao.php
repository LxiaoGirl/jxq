<!DOCTYPE !!>
<html>
<head>
    <title></title>
    <meta name="description" content=""/>
    <meta name="viewport" content="width=device-width, initial-scale=1,user-scalable=no"/>
    <meta http-equiv="X-UA-Compatible" content="IE=Edge,chrome=1"/>
    <meta http-equiv="Cache-Control" content="no-siteapp"/>
    <meta charset="utf-8"/>
	<meta name="baidu-site-verification" content="NWiIzGM1AG" />
    <link href="/assets/css/pages/mycss.css" rel="stylesheet" type="text/css">
<script src="/assets/js/seajs/sea.js" type="text/javascript"></script>
<script src="/assets/js/jquery/jquery-1.11.1.min.js" type="text/javascript"></script>
</head>

<body class="content_wrap">
    <div class="wrap">
    	<div class="header">
        	<h1>聚雪球理财平台</h1>
            <a class="hlogo" href="#"><span>聚雪球</span></a>
        </div>
        <div id="imgbox">
        	<img src="/assets/images/jujianren/pic1.jpg" width="100%" alt="聚雪球" />
        </div>
        <div class="reg_btn">
		<?php if(!empty($inviter_no)):?>
        	<a href="/index.php/jujianren/?inviter_no=<?php echo $inviter_no;?>"></a>
		<?php else:?>
		 <?php echo "您没有邀请人哦";?>
		<?php endif;?>
        </div>
        
    </div>
    
</body>
</html>
