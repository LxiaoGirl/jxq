<!DOCTYPE html>
<html>
<head>
    <title>聚雪球_网加金服_P2P理财首选互联网金融P2P网贷平台_100元即可投资!</title>
    <?php $this->load->view('common/head_file'); ?>
</head>
<body>

<?php $this->load->view('common/head'); ?>
<?php $this->load->view('common/head_about'); ?>

<div class="mnx2">
<div class="row">
  <img src="../../../../assets/images/common/mb_ico.png">您当前所在的位置：<a href="<?php echo site_url('about'); ?>">关于我们</a> ><a href="<?php echo site_url('about/news'); ?>">公司动态</a> ><span class="mb_blue"> <?php echo $data['title']; ?></span>
</div>
</div>
<div class="news_detail_w">
   <div class="news_detail_txt">
   <div style="font-size:24px;"><b><?php echo $data['title']; ?></b></div>
   <div style="font-size:14px; padding-bottom:40px; padding-top:10px;"><?php echo date('Y-m-d',$data['add_time']); ?>   来源：<?php echo $data['source']?$data['source']:'聚雪球'; ?></div>
   <p>
       <?php echo $data['content']; ?>
   </p>
   <div class="news_detail_sx">
   <p>上一篇：<?php if($prev): ?><a href="<?php echo site_url('about/news_detail?id='.$prev['id']); ?>"><?php echo $prev['title']; ?></a><?php else: ?><a href="javascript:void(0);">没有了</a><?php endif; ?></p>
   <p>下一篇：<?php if($next): ?><a href="<?php echo site_url('about/news_detail?id='.$next['id']); ?>"><?php echo $next['title']; ?></a><?php else: ?><a href="javascript:void(0);">没有了</a><?php endif; ?></p>
   </div>
   </div>
   
   <div class="news_detail_right">
   <div class="news_right1">
   <p class="clearfix">通知公告<span><a href="<?php echo site_url('about/news'); ?>">更多</a></span></p>
   <ul class="news_detail_list" id="gg">
   <li class="title"></li>
   </ul>
   <div style="padding-left:20px;">
    <dl class="contact_dl">
   <dt>获取关于聚雪球的最新信息与资讯</dt>
   <dd><img src="../../../../assets/images/about/contact/con_ico4.jpg">新浪微博：<a href="http://weibo.com/zgwjjf">http://weibo.com/zgwjjf</a></dd>
   <dd><img src="../../../../assets/images/about/contact/con_ico5.jpg">微信公众号：</dd>
  </dl>
  <div style="position:relative; height:160px; border-bottom:2px solid #246b8f;">
    <img src="../../../../assets/images/about/contact/con_erm.jpg" width="125" height="125">
    <img src="../../../../assets/images/about/contact/contact_sao.jpg" style="position:absolute; left:155px; top:10px;">
    <div style=" position:absolute; font-size:14px; left:150px; top:70px;">关注聚雪球，马上投资 <br>
掌上理财，快人一步</div>
    </div>
    </div>
    
   </div>
</div>
</div>
<!--footer start-->
<?php $this->load->view('common/footer'); ?>
<!--footer end-->

<script type="text/javascript">
    seajs.config({
        base: '/assets/js/',
        alias: {
            'jquery': ieVersion === -1 || ieVersion > 9 ? 'jquery/jquery-2.1.1.min.js' : 'jquery/jquery-1.11.1.min.js',
            'sys': 'sys/sys.js'
        }
    });
    seajs.use(['jquery','sys'],function(){
        $(function(){
            each_html('gg','/index.php/about/ajax_get_news',{'page_id':1,'page_size':3,'category':'<?php echo item('announcement_news_cat_id')?item('announcement_news_cat_id'):0; ?>'},'',true,function(obj,v){
                obj.find(':first').attr('onclick','window.location.href="<?php echo site_url('about/news_detail?id='); ?>'+ v.id+'"').css('cursor','pointer');
                if(1)obj.find('.title').append('<img src="../../../../assets/images/about/news/new_07.png" class="new">');
            });
        });
    });
</script>
</body>
</html>