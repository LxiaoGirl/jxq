<?php if( ! profile('uid')): ?>
<div class="quick_f">
	<div class="quick_content">
		<p class="fl">想快速赚钱？快来</p>
		<a href="<?php echo site_url('login'); ?>"><div class="f_login tc fl">登录/注册</div></a>
		<p class="fl">吧</p>
	</div>
</div>
<?php endif; ?>

<div class="footer">
	<div class="row clearfix">
		<div class="fo1 clearfix">
			<div class="fo1_l fl clearfix">
				<dl class="fo_dl">
					<dt>我的钱包</dt>
					<a href="<?php echo site_url('about/help_tail?cat_id=30') ?>"><dd>如何注册</dd></a>
					<a href="#"><dd>修改或找回密码</dd></a>
					<a href="#"><dd>个人资料修改</dd></a>
					<a href="#"><dd>充值与提现</dd></a>
				</dl>
				<dl class="fo_dl fo_dd">
					<dt>投资和借款</dt>
					<a href="#"><dd>聚雪球投款标的类型</dd></a>
					<a href="#"><dd>聚雪球借款标的类型</dd></a>
					<a href="#"><dd>投资人的资格</dd></a>
					<a href="#"><dd>借款人的资格</dd></a>
					<a href="#"><dd>投资的额度</dd></a>
					<a href="#"><dd>借款人资料填写码</dd></a>
					<a href="#"><dd>投标后能否取消</dd></a>
					<a href="#"><dd>还款方式</dd></a>
				</dl>
				<dl class="fo_dl">
					<dt>资费说明</dt>
					<a href="#"><dd>第三方费用</dd></a>
					<a href="#"><dd>借款用户费用</dd></a>
					<a href="#"><dd>投资用户费用</dd></a>
				</dl>
				<dl class="fo_dl">
					<dt>还款</dt>
					<a href="#"><dd>如何还款</dd></a>
					<a href="#"><dd>借款到期后能否延期还款</dd></a>
					<a href="#"><dd>逾期还款的处理办法</dd></a>
					<a href="#"><dd>如何提前还款</dd></a>
				</dl>
			</div>
			<div class="fo1_r fr">
				<dl class="fo_dl"><dt>手机玩聚雪球</dt></dl>
				<div class="down_l fl">
					<a href="#"><div class="down_btn1">下载Android版</div></a>
					<a href="#"><div class="down_btn2">下载iPhone版</div></a>
				</div>
				<div class="down_2 fl"><img src="../../../../assets/images/common/footer_erm.jpg" width="100" height="100"></div>
			</div>
		</div>
		<div class="fo2">
			<p class="fo-p1 tc"><a href="<?php echo site_url('about/company'); ?>"><img src="../../../../assets/images/common/f_logo.png" width="21" height="15">关于聚雪球</a>   <a href="<?php echo site_url('about/certificate'); ?>">公司资质</a>   <a href="<?php echo site_url('about/media'); ?>">媒体报道</a>   <a href="<?php echo site_url('about/news'); ?>">新闻中心 </a>  <a href="<?php echo site_url('about/join'); ?>">加入我们</a>  <a href="#"> 法律声明</a>   <a href="<?php echo site_url('about/contact'); ?>">联系我们</a>   <a href="<?php echo site_url('about/help'); ?>">帮助中心</a>   <a href="<?php echo site_url('about/guide'); ?>">新手指引</a>   辽ICP备15006535号</p>
			<p class="fo-p1 tc"><span>Copyright © 2009-2015 ZGWJJF 沈阳网加互联网金融服务有限公司</span>   <span>服务热线：4007 918 333（个人/企业）</span>服务时间：9:00-21:00</p>
			<p class="fo-p2 tc">友情链接：<a href="http://ln.qq.com/" target="_new">腾讯·大辽网</a> ·<a href="http://www.ce.cn/" target="_new"> 中国经济网 </a>·<a href="http://www.163.com/" target="_new"> 网易</a> ·<a href="http://cn.chinadaily.com.cn/" target="_new"> 中国日报网</a> · <a href="http://www.wangdaizhijia.com/" target="_new">网贷之家</a> ·<a href="http://www.eastmoney.com/" target="_new"> 东方财富网</a> ·<a href="http://www.eeo.com.cn/" target="_new"> 经济观察网</a></p>
			<p class="fo-p3 tc"><img src="../../../../assets/images/common/footer1.jpg">
				<img src="../../../../assets/images/common/footer2.jpg">
				<img src="../../../../assets/images/common/footer3.jpg">
				<img src="../../../../assets/images/common/footer4.jpg" ></p>
		</div>
	</div>
</div>

<?php   if('http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'] != site_url('user/user/account_home')):?><script type="text/javascript" src="<?php echo base_url('assets/js/seajs/sea.js')?>"></script><?php endif;?>
<script type="text/javascript" src="<?php echo base_url('assets/js/jquery/jquery-1.8.5.min.js')?>"></script>
<script type="text/javascript">
    $(document).ready(function(){
        if($('.news_nav_w').length){
            var hei=$('.news_nav_w').offset().top;
            $(window).scroll(function(){
                if($(document).scrollTop() > hei){
                    $('.news_nav_w').addClass("news_nav_fix");
                }else{
                    $('.news_nav_w').removeClass("news_nav_fix");
                }
            });
        }
    }); 
        
</script>
<script type="text/javascript" src="/assets/js/about/tbhb.js"></script>
<script type="text/javascript">
	seajs.use(['jquery','sys'],function(){
		addnav($('.head').find(".nav"));
		addnav($(".main_nav"));
		main_nav_pop($(".main_nav").find($(".fr")).find($("li")));
		nav_pop($(".nav_have_son"));
		
		$(function(){
			ajax_loading(1,true,2);//ajax提交 禁用处理
			//覆盖登录 处理未读消息
			if('<?php echo profile('uid'); ?>'){
				$.ajax({
					type: 'POST',
					url: '<?php echo site_url('user/message/ajax_get_not_read_message_count'); ?>',
					data: {'mobile':$('.js_mobile').val()},
					dataType: 'json',
					success: function (result) {
						if(result.status == '10000') {
							var data = result.data;
							if(data.counts > 0){
								$('.msg-count').html('('+data.counts+')');
								$(".msg-count-flag").text('●');
							}
						}else{

						}
					}
				});
			}
		});
	});
</script>