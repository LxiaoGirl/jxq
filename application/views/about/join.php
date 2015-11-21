<!DOCTYPE html>
<html>
<head>
    <title>聚雪球_网加金服_P2P理财首选互联网金融P2P网贷平台_100元即可投资!</title>
	<?php $this->load->view('common/head_file'); ?>
    <link rel="stylesheet" href="../../../../assets/css/jquery-ui-1.8.10.custom.css">
</head>
<body>
<?php $this->load->view('common/head'); ?>
<?php $this->load->view('common/head_about'); ?>

  <div class="news_wrap">
  <div class="news_mbx2 end-hidden">
  <div class="news_mbxl fl">
  <v>加入我们</v><span class="span2">我们期待你的加入！</span>
  </div>
  <div class="news_mbxr fr tr">
  <img src="../../../../assets/images/common/mb_ico.png">您当前所在的位置：<a href="<?php echo site_url('about') ?>">关于我们</a> ><span class="mb_blue"> 加入我们</span>
  </div>
  <div class="clear"></div>
  <div class="news_h2"><span>Join us</span></div>
  </div>
  <div class="join_p">高效的团队是由一群有精湛的技术、美好的人格，宽阔的胸襟，年轻的心态的成员组成的。<br>
他们具备实现理想目标所必需的技术和能力，而且相互之间有能够良好合作的个性品质，从而出色完成任务。<br>
聚雪球相信"应趋势、谋全局、意创新、求突破"的发展目标定会在竞争激烈的金融市场立于不败之地。<br>
在未来发展的道路上，我们会面临更多困难与挑战。<br>
为了一个更大的理想，我们现招集所有有理想、有抱负、愿奋斗并且认同我们理念的有志青年加入我们的队伍，与我们共进退，同成长。</div>

  <div class="news_img fl"><img src="../../../../assets/images/about/join/join_img.jpg" width="169" height="622"></div>
  <div class="join_right fr">
  <div id="vertical_container" >
  <ul>
      <li>
          <h1 class="accordion_toggle">理财顾问</h1>
        <div class="accordion_content">   
      <h2>岗位描述：</h2>
      <p>
        1. 为辽宁地区的高端客户提供全方面的金融理财服务<br>
2. 充分利用公司辅助提供的电话、活动等渠道，让客户了解公司的理财产品，最后促成客户；<br>
3. 根据客户的资产规模、预期收益目标和风险承受能力进行需求分析，出具专业的理财计划方案，推荐合适的理财产品；<br>
4. 定期与客户联系，报告理财产品的收益情况，向客户介绍新的金融服务、理财产品及金融市场动向，维护良好的信任关系
      </p>
<!--      <a href="#"><div class="fr clearfix">立即申请职位</div></a>-->
    </div>
      </li>
      <li>
          <h1 class="accordion_toggle">渠道经理</h1>
        <div class="accordion_content">
          <h2>岗位描述：</h2>
      <p>
        1. 为辽宁地区的高端客户提供全方面的金融理财服务<br>
2. 充分利用公司辅助提供的电话、活动等渠道，让客户了解公司的理财产品，最后促成客户；<br>
3. 根据客户的资产规模、预期收益目标和风险承受能力进行需求分析，出具专业的理财计划方案，推荐合适的理财产品；<br>
4. 定期与客户联系，报告理财产品的收益情况，向客户介绍新的金融服务、理财产品及金融市场动向，维护良好的信任关系
      </p>
<!--      <a href="#"><div class="fr clearfix">立即申请职位</div></a>-->
        </div>
      </li>
      <li>
          <h1 class="accordion_toggle">Why another Accordion?</h1>
        <div class="accordion_content">
          <h2>岗位描述：</h2>
      <p>
        1. 为辽宁地区的高端客户提供全方面的金融理财服务<br>
2. 充分利用公司辅助提供的电话、活动等渠道，让客户了解公司的理财产品，最后促成客户；<br>
3. 根据客户的资产规模、预期收益目标和风险承受能力进行需求分析，出具专业的理财计划方案，推荐合适的理财产品；<br>
4. 定期与客户联系，报告理财产品的收益情况，向客户介绍新的金融服务、理财产品及金融市场动向，维护良好的信任关系
      </p>
<!--      <a href="#"><div class="fr clearfix">立即申请职位</div></a>-->
        </div>
      </li>
      <li>
          <h1 class="accordion_toggle">A Vertical Nested Accordion!</h1>
        <div class="accordion_content">
          <h2>岗位描述：</h2>
      <p>
        1. 为辽宁地区的高端客户提供全方面的金融理财服务<br>
2. 充分利用公司辅助提供的电话、活动等渠道，让客户了解公司的理财产品，最后促成客户；<br>
3. 根据客户的资产规模、预期收益目标和风险承受能力进行需求分析，出具专业的理财计划方案，推荐合适的理财产品；<br>
4. 定期与客户联系，报告理财产品的收益情况，向客户介绍新的金融服务、理财产品及金融市场动向，维护良好的信任关系
      </p>
<!--      <a href="#"><div class="fr clearfix">立即申请职位</div></a>-->
        </div>
      </li>
  </ul>        					
	</div>
  </div>
  <div class="clear"></div>
  </div>
<!--footer start-->
<?php $this->load->view('common/footer'); ?>
<!--footer end-->
<SCRIPT type=text/javascript>
    $('.accordion_toggle').click(function() {
        i=$(this).parent('li').index();
        $(this).parent('li').siblings('li').find('h1').removeClass('accordion_toggle_active');
        $(this).parent('li').siblings('li').find('.accordion_content').slideUp();
        $(this).parent().find('.accordion_content').slideToggle();
        $(this).toggleClass('accordion_toggle_active');
    });
</SCRIPT>
</body>
</html>