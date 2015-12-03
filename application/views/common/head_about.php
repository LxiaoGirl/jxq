<div class="news_nav_w">
	<div class="news_nav news_wrap end-hidden">
		<ul>
			<a href="<?php echo site_url('about/index'); ?>"> <li class="news_nav_li1 <?php if($this->router->fetch_method() == 'index'): ?>select<?php endif; ?>">公司简介</li></a>
<!--			<a href="--><?php //echo site_url('about/team'); ?><!--"> <li class="news_nav_li2 --><?php //if($this->router->fetch_method() == 'team'): ?><!--select--><?php //endif; ?><!--">管理团队</li></a>-->
			<a href="<?php echo site_url('about/partners'); ?>"> <li class="news_nav_li3 <?php if($this->router->fetch_method() == 'partners'): ?>select<?php endif; ?>">合作伙伴</li></a>
			<a href="<?php echo site_url('about/media'); ?>"> <li class="news_nav_li4 <?php if($this->router->fetch_method() == 'media'): ?>select<?php endif; ?>">媒体报道</li></a>
			<a href="<?php echo site_url('about/news'); ?>"> <li class="news_nav_li5 <?php if($this->router->fetch_method() == 'news' || $this->router->fetch_method() == 'news_detail'): ?>select<?php endif; ?>">公司动态</li></a>
			<a href="<?php echo site_url('about/certificates'); ?>"> <li class="news_nav_li6 <?php if($this->router->fetch_method() == 'certificates'): ?>select<?php endif; ?>">公司资质</li></a>
			<a href="<?php echo site_url('about/join'); ?>"> <li class="news_nav_li7 <?php if($this->router->fetch_method() == 'join'): ?>select<?php endif; ?>">加入我们</li></a>
			<a href="<?php echo site_url('about/contact'); ?>"> <li class="news_nav_li8 <?php if($this->router->fetch_method() == 'contact'): ?>select<?php endif; ?>">联系我们</li></a>
		</ul>
	</div>
</div>