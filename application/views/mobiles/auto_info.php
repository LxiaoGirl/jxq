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
    <!--已开启-->
    <div class="row zdkg">
        <p class="tot">自动投标：已开启</p>
        <p>账户有余额时是否自动进行投标</p>
        <a href="javascript:void(0);" class="pop_y">
            <img class="yes" src="../../../assets/images/app/zdtb_n_y.jpg" style="display:block">
        </a>
        <div class="pop pop_y_k">
            <div class="con">
                <h1 class="tc">确定关闭自动投标吗？<font class="fr close">X</font></h1>
				<p id="close">关闭自动投标后，您将不会继续享受聚雪球标的自动匹配功能，更多高收益标的可能会被别人抢走哦。真的要关闭自动投标吗？</p>
                <p class="tc"><button class="qr" type="button">确定</button><button class="qx close" type="button">取消</button></p>
            </div>
        </div>
    </div>
    <div class="row zdtbsm">
        <div class="zdxz xzh">
            模式：<font><?php echo ($automatic_info['mode']==1)?'复投模式':'固额模式';?></font>
        </div>
        <div class="zdxz xzh">
            有效日期：<font><?php echo my_date($automatic_info['pzsj_start'],2)?></font> 至 <font><?php echo my_date($automatic_info['pzsj_end'],2)?></font>
        </div>
        <div class="zdxz xzh">
            标的类别：<font><?php  if($automatic_info['type']==1): echo'车贷宝';elseif($automatic_info['type']==2): echo'聚农贷';elseif($automatic_info['type']==3): echo'聚惠理财';else: echo'不限';endif;?></font>
        </div>
        <div class="zdxz xzh">
            标的最高收益率：<font><?php echo $automatic_info['sy_min']?>%</font>
        </div>
        <div class="zdxz xzh">
            标的最长期限：<font><?php echo $automatic_info['jk_max']?>个月</font>
        </div>
        <div class="zdxz xzh">
            自动投资配额：<font><?php if($automatic_info['mode']==2): echo $automatic_info['balance_ze'].'元人民币';else: echo '无限制';endif;?></font>
        </div>
        <div class="zdxz xzh fr">
            <a href="#"><font>修改设置?</font></a>
        </div>
    </div>
    <!--已开启-->
	</div>
<?php $this->load->view('common/mobiles/app_alert') ?>
</body>
<?php $this->load->view('common/mobiles/app_footer') ?>
<script>
//3333
    $('.pop_y').click(function(){
        $('.pop_y_k').fadeIn();
    })
    $('.pop_y_k').find('.close').click(function(){
        $('.pop_y_k').fadeOut();
    })
    //3333

	$('.qr').click(function(){
		$.post('/index.php/mobiles/home/auto_close',{},function(result){
				if(result=='ok'){
					window.location.href="/index.php/mobiles/home/auto_start";
				}else{
					alert(result);
				}
			});	
	})
</script>
</html>