<!DOCTYPE html>
<html>
<head>
    <title>聚雪球_网加金服_P2P理财首选互联网金融P2P网贷平台_100元即可投资!</title>
	<?php $this->load->view('common/head_file'); ?>
</head>
<body>
<?php $this->load->view('common/head'); ?>

<div style="background:url(../../../../assets/images/banner/safe_ban.jpg) no-repeat center top; height:250px;"></div>
<!--面包屑导航 start-->
<div class="mnx">
<div class="row">
  <img src="../../../../assets/images/common/mb_ico.png">您当前所在的位置：<a href="<?php echo site_url(); ?>">首页</a> ><span class="mb_blue"> 安全保障</span>
</div>
</div>
<!--面包屑导航 end-->
<p class="safe-p tc">聚雪球平台为每一位投资人提供<span>5</span> 重保障本息安全固若金汤</p>
<div class="history">
        <div class="start-history">
            <div class="history_left">
                <p class="history_L tr safe_left1">
                <img src="../../../../assets/images/about/safe/safe1.png" width="260" height="130"> 
                </p>
                 <p class="history_L safe_left2">     
                 <span class="safe_h1">实名认证</span><br>
                 <span class="safe_p1"> <br>借款个人：实名认证，身份背景调查，银行信用资料审查，大数据综合分析还款能力，部分资产实地调查；<br>
    借款企业：专业机构对各类证件进行验证，经营情况审核，人行企业征信报告，抵押物产权调查，大数据综合分析还款能力；</span>
                </p>
                <p class="history_L tr safe_left3">
                <img src="../../../../assets/images/about/safe/safe3.png" width="262" height="185"> </p>
                <p class="history_L safe_left4">
               <span class="safe_h1">法律保障</span><br>
                 <span class="safe_p1"> <br>科技保障：使用的合同文本全部由专业律师起草，所有电子合同全部采用PDF防篡改技术；<br>
法律保障：三方律师事务所对所有纸质合同进行保管，如有纠纷，随时调取，保管期限2年；</span>
              </p>
                <p class="history_L tr safe_left5">
                <img src="../../../../assets/images/about/safe/safe5.png" width="290" height="186"> </p>
                
            </div>
            <div class="history-img">
                <img class="history_img" src="../../../../assets/images/about/safe/history.png" alt="">
            </div>
            <div class="history_right">
               <p class="history_R safe_right1">
               <span class="safe_h1">本金保障</span><br>
                 <span class="safe_p1"> <br>本息安全：借款人还款方式多样，四种方式任选，按时付息。如急需收回资金可将债权转出，匹配成功，即可收回本金；
资金安全：用户资金由第三方支付托管，银行全程监管，对投资人的资金全程跟踪，监督资金动向；</span>
                </p>
                <p class="history_R safe_right2">
                <img src="../../../../assets/images/about/safe/safe2.png">
                </p>
                <p class="history_R safe_right3">
                <span class="safe_h1">严格把关</span><br>
                 <span class="safe_p1"> <br>严格把关中的内容更改，对于借款方，我们通过利用大数据进行严格调查、审核通过后签订合作协议，如第三方担保机构在其所担保借款项目出现逾期、坏账时，聚雪球会责令第三方担保机构就本金和利息向投资人履行全额赔付义务</span>
                </p>
                <p class="history_R safe_right4">
                <img src="../../../../assets/images/about/safe/safe4.png" width="156" height="187"> </p>
                <p class="history_R safe_right5">
                  <span class="safe_h1">双重加密</span><br>
                 <span class="safe_p1"> <br>为保证用户的资金安全，聚雪球设计了登陆密码和资金密码双重密码保障机制，并且聚雪球自主研发服务平台，采用银行级别的软硬件系统安全机制。所有交易信息均实时同步备份，让您的资金免受其它非法聚集用户资金再转贷给不同借款人的P2P网贷平台的安全威胁；<br>
安全团队：服务器采用国内顶尖服务器，同时聘请国内知名团队，为网站安全运行全面保驾护航</span>
                </p>
               
            </div>
            <div class="clear"></div>
        </div>
        <div class="clear"></div>
    </div>

<?php $this->load->view('common/footer'); ?>
<script type="text/javascript">
$(window).scroll(function(){
	var msg = $(".history-img");
	var item = $(".history_L");
	var items = $(".history_R");
	var windowHeight = $(window).height();
	var Scroll = $(document).scrollTop();
	if((msg.offset().top - Scroll -windowHeight)<=0){
		msg.fadeIn(1500);
	}
	for(var i=0;i<item.length;i++){
		if(($(item[i]).offset().top - Scroll - windowHeight)<= -100){
			$(item[i]).animate({marginRight:'0px'},'50','swing');
		}
	}
	for(var i=0;i<items.length;i++){
		if(($(items[i]).offset().top - Scroll - windowHeight)<= -100){
			$(items[i]).animate({marginLeft:'0px'},'50','swing');
		}
	}
});
</script>
</body>
</html>