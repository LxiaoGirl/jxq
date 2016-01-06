<!DOCTYPE html>
<html>
<head>

    <title>平台介绍</title>
    <?php $this->load->view('common/mobiles/app_head') ?>
    <?php echo load_file('app/m-common.css,app/m-ptjs.css'); ?>
    <style type="text/css">
        .xgxx p a.kfdh{
            color:#2d74b4;
        }
        .xgxx p a.kfdh:hover{
            color:#2d74b4;
        }
    </style>
</head>
<body>
<!-- 公共头部导航-->
<?php $this->load->view('common/mobiles/app_common_head') ?>
<div class="con_wap">
    <div class="log"><img src="<?php echo assets('images/app/ptjs/ptjs_1.png'); ?>"></div>
    <div class="wj">
        <p>聚雪球平台是沈阳网加互联网金融服务有限公司旗下的一家互联网金融平台，平台由沈阳市供销社、皇姑区国资委共同出资打造，专业提供互联网金融信息服务的公司。</p>

        <p>
            作为国内领先的金融信息服务平台，沈阳网加互联网金融服务有限公司通过对P2P借贷信息的收集、验证、整理、发布、对接、分析，以及第三方合作（银行、第三方支付公司）对借贷资金进行核算、结算、支付等方式，为借贷双方提供了一站式金融信息服务。</p>
    </div>
    <div class="log"><a href="/index.php/mobiles/home/ptjs" target="_blank"><img src="<?php echo assets('images/app/ptjs/ptjs_2.png'); ?>"></a></div>
    <div class="xgxx">
        <p><img src="<?php echo assets('images/app/ptjs/ptjs_3.png'); ?>"><font>企业QQ: 4007918333</font></p>

        <p><img src="<?php echo assets('images/app/ptjs/ptjs_4.png'); ?>"><font>微信服务号: juxueqiu</font></p>

        <p><img src="<?php echo assets('images/app/ptjs/ptjs_5.png'); ?>"><font>服务邮箱: service@zgwjjf.com</font></p>
        <a href="http://www.juxueqiu.com" target="_blank"><p><img
                    src="<?php echo assets('images/app/ptjs/ptjs_6.png'); ?>"><font>公司网址: <em>www.juxueqiu.com</em></font><font
                    class="fr">></font></p></a>

        <p><img src="<?php echo assets('images/app/ptjs/ptjs_7.png'); ?>"><font>沈阳市皇姑区北陵大街32号软件大厦</font></p>
        <p><img src="<?php echo assets('images/app/ptjs/ptjs_8.png'); ?>"><font>客服电话：<a class="kfdh" href="tel:4007918333">4007-918-333</a></font></p>
    </div>
</div>
<!-- 公共尾部-->
<?php $this->load->view('common/mobiles/app_footer') ?>
</body>
</html>