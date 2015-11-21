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
          <v>媒体报道</v><span class="span2">来自新闻媒体的关注与报道——第三方中立、客观、真实的评价</span>
      </div>
      <div class="news_mbxr fr tr">
          <img src="../../../../assets/images/common/mb_ico.png">
          您当前所在的位置：<a href="<?php echo site_url('about') ?>">关于我们</a> ><span class="mb_blue"> 媒体报道</span>
      </div>
      <div class="clear"></div>
      <div class="news_h2"><span>Media reports</span></div>
    </div>
    <div class="clear"></div>
    <div class="news_body">

        <?php if($page_id == 1 && $media && $media[0] && $media[0]['content'] != ''): ?>
            <div class="frist_news">
                <div class="lf fl">
                  <h3><?php echo $media[0]['title']; ?></h3>
                  <p><?php echo $media[0]['description']; ?></p>
                  <a href="<?php echo $media[0]['link_url']; ?>" target="_blank">查看详情</a>
                </div>
                <div class="rig fl">
                  <img src="<?php echo $media[0]['content']; ?>">
                </div>
            </div>
        <?php unset($media[0]); endif;?>

        <ul class="media_news">
            <?php if($media):foreach($media as $k=>$v): ?>
                <li>
                    <div class="lef">
                        <?php echo date('d',$v['update_time']); ?>
                        <font><?php echo date('Y.m',$v['update_time']); ?></font>
                    </div>
                    <div class="rigt">
                        <h4><a href="<?php echo $v['link_url']; ?>" target="_blank"><?php echo $v['title']; ?></a></h4>
                        <p><?php echo $v['description']?mb_substr($v['description'],0,40):''; ?></p>
                    </div>
                </li>
            <?php endforeach;else: ?>
                <li><p style="text-align: center;">暂无相关信息!</p></li>
            <?php endif; ?>
        </ul>
        <?php echo $links; ?>
    </div>
  </div>
<!--footer start-->
<?php $this->load->view('common/footer'); ?>
<!--footer end-->

<script type="text/javascript">
  seajs.use(['jquery','sys'],function(){
    $(function(){
      var page_id =1;
      var list_html = $("#news-list").clone();
      each_html(list_html,'/index.php/about/ajax_get_news',{'page_id':page_id,'page_size':8,'category':1},{'add_time':function(v){ return unixtime_style(v,'Y-m-d')}},true,function(obj,v){
        obj.find('a').attr('href','<?php echo site_url('about/news_detail?id='); ?>'+ v.id)
      });
      page_id++;
      $('.news_more').bind('click',function(){
        each_html(list_html,'/index.php/about/ajax_get_news',{'page_id':page_id,'page_size':8,'category':10},{'add_time':function(v){ return unixtime_style(v,'Y-m-d')}},false,function(obj,v){
          obj.find('a').attr('href','<?php echo site_url('about/news_detail?id='); ?>'+ v.id)
        },function(no_data){
          if(no_data)$('.news_more').unbind('click').remove();
        },true);
        page_id++;
      });
    });
  });
</script>
</body>
</html>