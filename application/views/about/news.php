<!DOCTYPE html>
<html>
<head>
    <title>聚雪球_网加金服_P2P理财首选互联网金融P2P网贷平台_100元即可投资!</title>
	<?php $this->load->view('common/head_file'); ?>
</head>
<body>
<?php $this->load->view('common/head'); ?>
<?php $this->load->view('common/head_about'); ?>

  <div class="news_wrap">
  <div class="news_mbx end-hidden">
  <div class="news_mbxl fl">
  <v>公司动态</v><span class="span2">第一时间了解聚雪球动态及行业动态</span>
  </div>
  <div class="news_mbxr fr tr">
  <img src="../../../../assets/images/common/mb_ico.png">您当前所在的位置：<a href="<?php echo site_url('about') ?>">关于我们</a> ><span class="mb_blue"> 公司动态</span>
  </div>
  <div class="clear"></div>
  <div class="news_h2"><span>Company dynamics</span></div>
  
  </div>
  <div class="news_img fl"><img src="../../../../assets/images/about/news/news_img.jpg" width="169" height="622"></div>
  <div class="news_right fr">
  <ul id="news-list">
    <a href="#" target="_blank">
      <li>
        <div class="news_time fl update_time">0000-00-00</div>
        <div class="news_content fl">
          <h2 class="title"></h2>
          <p class="description"></p>
        </div>
      </li>
    </a>
  </ul>
  <div class="news_more"><img src="../../../../assets/images/about/news/news_more.png" width="175" height="41"></div>
  </div>
  <div class="clear"></div>
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
      var page_id =1;
      var list_html = $("#news-list").clone();
      each_html(list_html,'/index.php/about/ajax_get_news',{'page_id':page_id,'page_size':8,'category':'<?php echo item('about_news_cat_id')?item('about_news_cat_id'):10;echo item('announcement_home_bottom_cat_id')?','.item('announcement_home_bottom_cat_id'):''; ?>'},{'update_time':function(v){ return unixtime_style(v,'Y-m-d')}},true,function(obj,v){
        obj.find('a').attr('href','<?php echo site_url('about/news_detail?id='); ?>'+ v.id)
      });
      page_id++;
      //加载更多的处理
      $('.news_more').bind('click',function(){
        each_html(list_html,'/index.php/about/ajax_get_news',{'page_id':page_id,'page_size':8,'category':'<?php echo item('about_news_cat_id')?item('about_news_cat_id'):10; echo item('announcement_home_bottom_cat_id')?','.item('announcement_home_bottom_cat_id'):''; ?>'},{'update_time':function(v){ return unixtime_style(v,'Y-m-d')}},false,function(obj,v){
          obj.find('a').attr('href','<?php echo site_url('about/news_detail?id='); ?>'+ v.id)
        },function(no_data){
          if(no_data)$('.news_more').unbind('click').remove();
        },true);
        page_id++;
      });

      //滑动自动加载
//      var get_more = function(){
//        each_html(list_html,'/index.php/about/ajax_get_news',{'page_id':page_id,'page_size':8,'category':'<?php //echo item('about_news_cat_id')?item('about_news_cat_id'):10; ?>//'},{'update_time':function(v){ return unixtime_style(v,'Y-m-d')}},false,function(obj,v){
//          obj.find('a').attr('href','<?php //echo site_url('about/news_detail?id='); ?>//'+ v.id)
//        },function(no_data){
//          if(no_data)$('.news_more').remove();
//          $(window).bind('scroll',function(){
//            var scrollTop = $(this).scrollTop(),scrollHeight = $(document).height(),windowHeight = $(this).height();
//            if(scrollTop + windowHeight >= scrollHeight-$('.footer').height()-$('.news-more').height()){
//              $(window).unbind('scroll');
//              get_more();
//            }
//          });
//        },true);
//        page_id++;
//      };
//      $(window).bind('scroll',function(){
//        var scrollTop = $(this).scrollTop(),scrollHeight = $(document).height(),windowHeight = $(this).height();
//        if(scrollTop + windowHeight >= scrollHeight-$('.footer').height()-$('.news-more').height()){
//          $(window).unbind('scroll');
//          get_more();
//        }
//      });
    });
  });
</script>
</body>
</html>