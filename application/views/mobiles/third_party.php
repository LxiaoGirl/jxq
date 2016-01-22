<!DOCTYPE html>
<html>
<head lang="en">
    <title>托管账户开户信息</title>
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
    <link rel="stylesheet" type="text/css" href="/assets/css/app/head.css">
    <link rel="stylesheet" type="text/css" href="/assets/css/app/m-common.css">
    <style>
        body{
            background: #eee;
        }
        .con_wap .row{
            padding: 1.5rem;
            background: #fff;
        }
        .con_wap .row .p{
            line-height: 3.4rem;
            font-size: 1.6rem;
            color: #858585;
            border-bottom: 1px solid #eee;
            text-indent: 0.1rem;
            overflow: hidden;
        }
        .con_wap .row .p .font{
            display:block;
            float: right;
            width: 65%;
            font-size: 1.2rem;
            color: #858585;
            border-left: 1px solid #eee;
            overflow: hidden;
            padding-left: 0.4rem;
        }
        .con_wap .row .p a{
            color: #3cbaed;
        }
    </style>
</head>
<body>
<?php $this->load->view('common/mobiles/app_common_head') ?>

<!-- 公共头部导航-->
<div class="placehold"></div>
<div class="con_wap">
    <div class="row">
		<div class="p">                     
			
				托管账户户名
			<div class="font">		
				沈阳网加互联网金融服务有限公司
            </div>
		</div>
		<div class="p">                     
			
				托管账户客户
			<div class="font">		
				<?php echo $real_name?>
            </div>
		</div>
		<div class="p">                     
			
				托管账户银行
			<div class="font">		
				平安银行南京分行
            </div>
		</div>
		<div class="p">                     
			
				托管账户账号
			<div class="font">		
				<?php echo $vaccid?>
            </div>
		</div>
    </div>
</div>
<?php $this->load->view('common/mobiles/app_alert') ?>
</body>
<?php $this->load->view('common/mobiles/app_footer') ?>
<script>
    
</script>
</html>