<!DOCTYPE html>
<html>
<head>
    <title>聚雪球_网加金服_P2P理财首选互联网金融P2P网贷平台_100元即可投资!</title>
	<?php $this->load->view('common/head_file'); ?>
</head>
<body>
<?php $this->load->view('common/head'); ?>
<div style=" background:url(/assets/images/about/about/about_ban.png) no-repeat center top; height:250px;"></div>
<?php $this->load->view('common/head_about'); ?>

<div class="about_w">
	<div class="about1">
		<h1>什么是聚雪球平台？</h1>
		<div class="tc">聚雪球是由<span>沈阳市供销社</span>、<span>国资委</span>以及<span>沈阳网加互联网金融服务有限公司</span>共同出资创办的一家专业互联网金融服务平台。</div>
		<p>"聚雪球"平台将通过广泛运用移动支付、云计算、搜索引擎等互联网信息技术，对当前成熟的金融产品进行组合优化，有针对性地为投融资方搭建沟通桥梁，促进双方交易。同时充分发挥媒介作用，汇集各类金融机构的产品信息，搭建网络金融产品超市，丰富客户选择。充分发挥专业优势，以专业团队为依托，利用网络平台和大数据，为中小企业客户提供高品质、个性化金融服务方案。</p>
	</div>
</div>
<div class="about_w">
	<div class="about_big_h1">聚雪球大事记</div>
	<div class="about_bigt">
		<DIV class="slide-content" id="J_slide">
			<DIV class="wrap">
				<UL class="ks-switchable-content">
					<?php if($data):$count_data = count($data);foreach($data as $k=>$v): ?>
						<?php if($k+1 == 1 || (($k+1)>1 && ($k+1)%5 == 1)): ?><LI> <?php endif; ?>
						<div class="about_bigt<?php echo (($k+1)%5 == 0)?5:($k+1)%5; ?>  <?php echo (in_array(($k+1)%5,array(1,2)))?'big_top':'big_bot'; ?> fl">
							<p class="about_p1"><?php echo date('Y.m.d',$v['update_time']); ?></p>
							<p class="about_p2"><?php echo $v['title']; ?></p>
						</div>
						<?php if( ($k+1 > 0 && ($k+1)%5 == 0) || $k+1 == $count_data): ?><div class="clear"></div></LI> <?php endif; ?>
					<?php endforeach;endif; ?>
				</UL>
			</DIV>
			<DIV class="ks-switchable-triggers">
				<A class="prev" id="J_prev" href="javascript:void(0);">
					<B class="corner"></B><SPAN></SPAN><B class="corner"></B></A>
				<A class="next" id="J_next" href="javascript:void(0);">
					<B class="corner"></B><SPAN></SPAN><B class="corner"></B></A>
			</DIV>
		</DIV>
	</div>
</div>

<div class="about_img1 tc">
	<img src="../../../../assets/images/about/about/about2.png" width="1140" height="200"></div>
<div class="about_img2 tc">
	<img src="../../../../assets/images/about/about/about1.png" width="1140" height="200"></div>
<div class="about_bottom">
	<div class="about_w1">
		<dl class="about_dl01 fl">
			<dt>客户服务</dt>
			<dd>客服电话：4007-918-333</dd>
			<dd>在线帮助：<a href="http://www.zgwjjf.com">www.zgwjjf.com</a></dd>
			<dd>服务邮箱：<a href="javascript:void(0);">service@zgwjjf.com</a></dd>
		</dl>
		<dl class="about_dl02 fl">
			<dt>媒体报道与采访</dt>
			<dd>如果你的需要与我们进行媒体合作，或有采访需要，请发送邮件至：</dd>
			<dd><a href="javascript:void(0);">chenxue@zgwjjf.com</a></dd>
			<dd>我们会在一个工作日你与你联系</dd>
		</dl>
		<dl class="about_dl03 fr">
			<dt>商务合作</dt>
			<dd>如果你有广告投放，商务合作的需求，请发送邮件</dd>
			<dd>邮件正文请简要介绍合作需求至：</dd>
			<dd><a href="javascript:void(0);">chenxue@zgwjjf.com</a></dd>
		</dl>
		<div class="clear"></div>
	</div>
</div>
<!--footer start-->
<?php $this->load->view('common/footer'); ?>
<SCRIPT type=text/javascript>
	$(function(){
		//处理 大事件 的时间排序与点的对应
		// 1->3 2->1 3->4 4->2  no 4 3->2
		$('.ks-switchable-content li').each(function(i,v){
			var html1 = $(v).find(".about_bigt1").length?$(v).find(".about_bigt1").html():'';
			var html2 = $(v).find(".about_bigt2").length?$(v).find(".about_bigt2").html():'';
			var html3 = $(v).find(".about_bigt3").length?$(v).find(".about_bigt3").html():'';
			var html4 = $(v).find(".about_bigt4").length?$(v).find(".about_bigt4").html():'';
			if($(v).find(".about_bigt3").length > 0){
				$(v).find(".about_bigt1").html(html2);
				$(v).find(".about_bigt3").html(html1);
				if($(v).find(".about_bigt4").length > 0){
					$(v).find(".about_bigt2").html(html4);
					$(v).find(".about_bigt4").html(html3);
				}else{
					$(v).find(".about_bigt2").html(html3);
				}
			}
		});


	});
	var D=YAHOO.util.Dom, E=YAHOO.util.Event;
	KISSY().use("*", function(S) {
		var el = D.get('J_slide'),
			activeIndex = parseInt(el.getAttribute('data-active-index')) || 0;

		var carousel = new S.Carousel(el, {
			hasTriggers: false,
			navCls: 'ks-switchable-nav',
			contentCls: 'ks-switchable-content',
			activeTriggerCls: 'current',
			effect: "scrollx",
			steps: 1,
			viewSize: [990],
			activeIndex: activeIndex
		});

		E.on('J_prev', 'click', carousel.prev, carousel, true);
		E.on('J_next', 'click', carousel.next, carousel, true);
	});

</SCRIPT>
<!--footer end-->
</body>
</html>