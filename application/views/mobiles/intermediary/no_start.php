<!DOCTYPE !!>
<html>
<head>
    <title>提示</title>
    <meta name="description" content=""/>
    <meta name="viewport" content="width=device-width, initial-scale=1,user-scalable=no"/>
    <meta http-equiv="X-UA-Compatible" content="IE=Edge,chrome=1"/>
    <meta http-equiv="Cache-Control" content="no-siteapp"/>
    <meta charset="utf-8"/>
	<meta name="baidu-site-verification" content="NWiIzGM1AG" />
    <link href="https://www.zgwjjf.com/assets/css/pages/mycss.css" rel="stylesheet" type="text/css">
    <?php $this->load->view('common/mobiles/m_app_head') ?>
    <link rel="stylesheet" type="text/css" href="/assets/css/app/m-popularize.css">
</head>

<body class="content_wrap">
<!-- 公共头部导航-->
    <?php $this->load->view('common/mobiles/app_common_head') ?>

    <div class="wrap" style="margin-top:100px;">
    	
        <div style="width:256px;margin:0 auto;margin-top:10%;">
            <h3 style="text-align:center;">开始时间：<?php echo $start_time; ?></h3>
            <h3 style="text-align:center;margin-top:10px;">敬请期待！</h3>
        </div>
    </div>
</body>
<?php $this->load->view('common/mobiles/app_footer') ?>
</html>
