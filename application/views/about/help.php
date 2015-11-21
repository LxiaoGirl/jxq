<!DOCTYPE html>
<html>
<head>
    <title>聚雪球_网加金服_P2P理财首选互联网金融P2P网贷平台_100元即可投资!</title>
	<?php $this->load->view('common/head_file'); ?>
</head>
<body>

<?php $this->load->view('common/head'); ?>

<div class="help_search">
    <div class="help_w end-hidden">
        <div class="help_top1 fl">帮助中心</div>
        <div class="help_top2 fr">
            <input name="keyword"  id="help_key" type="text" class="help_input1" value="输入问题关键词"
                   onfocus="if(this.value == '输入问题关键词')value='';"
                   onblur="if(this.value == '')this.value = '输入问题关键词';"
                   onkeyup="if(event.keyCode == '13')window.location.href='<?php echo site_url('about/help_search') ?>?keyword='+this.value;"
            >
            <input name="" type="submit" class="help_btn1" value="搜索"
                   onclick="if(document.getElementById('help_key').value != '' && document.getElementById('help_key').value != '输入问题关键词')window.location.href='<?php echo site_url('about/help_search') ?>?keyword='+document.getElementById('help_key').value;">
        </div>
    </div>
</div>
<div style="background:#f3f3f3">
<!--面包屑导航 start-->

<div class="mnx2">
<div class="help_w">
  <img src="../../../../assets/images/common/mb_ico.png">您当前所在的位置：<a href="<?php echo site_url(); ?>">首页</a> ><span class="mb_blue"> 帮助中心</span>
</div>
</div>
<!--面包屑导航 end-->
<div class="help_w">
<div class="help_sider fl">
    <p>常见问题分类</p>
    <?php if($category_list):foreach($category_list as $k=>$v): ?>
        <div class="subNav"><?php echo $v['category']; ?></div>
        <?php if(isset($v['child'])): ?>
            <ul class="navContent " >
                <?php foreach($v['child'] as $k1=>$v1): ?>
                <li><a href="<?php echo site_url('about/help_list?cat_id='.$v1['cat_id']); ?>"><?php echo $v1['category']; ?></a></li>
            <?php endforeach; ?>
            </ul>
        <?php endif; ?>
    <?php endforeach;endif; ?>
</div>
<div class="help_hot fr">  
   <ul class="help_list1">
   <div class="help_hot_h">热点问题</div>
       <div id="hot-help">
           <li><a href="#"><span class="title"></span></a></li>
       </div>
   <div class="clear"></div>  
   </ul>
   <div class="help_ad"><a href="#"><img src="../../../../assets/images/bigpic/help_ad.jpg" width="750" height="136"></a></div>
   </div>
<div class="clear"></div>
</div>
</div>

<!--footer start-->
<?php $this->load->view('common/footer'); ?>
<script type="text/javascript" src="../../../../assets/js/jquery/jquery-1.11.1.min.js"></script>
<script type="text/javascript" src="../../../../assets/js/sys/sys.js"></script>
<script type="text/javascript">
	$(function(){
		$(".subNav").click(function(){
			$(this).toggleClass("currentDd").siblings(".subNav").removeClass("currentDd")
			$(this).toggleClass("currentDt").siblings(".subNav").removeClass("currentDt")

			// 修改数字控制速度， slideUp(500)控制卷起速度
			$(this).next(".navContent").slideToggle(500).siblings(".navContent").slideUp(500);
		});
        each_html('hot-help','/index.php/about/ajax_get_news',{'page_id':1,'page_size':10,'category':'<?php echo $cat_id_str; ?>','order_by':'rank DESC'},'',true,function(obj,v){
            obj.find('a').attr('href','<?php echo site_url('about/help_detail?id='); ?>'+ v.id+'&cat_id='+ v.cat_id)
        });
	})
</script>
<!--footer end-->
</body>
</html>