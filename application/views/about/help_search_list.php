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
            <input name="keyword"  id="help_key" type="text" class="help_input1" value="<?php echo $keyword?$keyword:'输入问题关键词' ?>"
                   onfocus="if(this.value == '输入问题关键词')value='';" onblur="if(this.value == '')this.value = '输入问题关键词';"
                   onkeyup="if(event.keyCode == '13')window.location.href='<?php echo site_url('about/help_search') ?>?keyword='+this.value;">
            <input name="" type="submit" class="help_btn1" value="搜索" onclick="window.location.href='<?php echo site_url('about/help_search') ?>?keyword='+document.getElementById('help_key').value;">
        </div>
    </div>
</div>
<div style="background:#f3f3f3">
<!--面包屑导航 start-->

<div class="mnx2">
<div class="help_w">
  <img src="../../../../assets/images/common/mb_ico.png">您当前所在的位置：<a href="<?php echo site_url(); ?>">首页</a> ><a href="<?php echo site_url('about/help'); ?>">帮助中心</a> >搜索结果
</div>
</div>
<!--面包屑导航 end-->
<div class="help_w">
<div class="help_sider fl">
    <p>常见问题分类</p>
    <?php if($category_list):foreach($category_list as $k=>$v): ?>
        <div class="subNav <?php if($v['cat_id'] == $cat_pid): ?>currentDd currentDt<?php endif; ?>"><?php echo $v['category']; ?></div>
        <?php if(isset($v['child'])): ?>
            <ul class="navContent " <?php if($v['cat_id'] == $cat_pid): ?>style="display:block"<?php endif; ?>>
                <?php foreach($v['child'] as $k1=>$v1): ?>
                    <li><a href="<?php echo site_url('about/help_list?cat_id='.$v1['cat_id']); ?>"><?php echo $v1['category']; ?></a></li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>
    <?php endforeach;endif; ?>
</div>
<div class="help_list_right fr">
<div class="help_list_main">
  <h2>搜索结果</h2>

        <?php if($news): ?>
        <ul>
        <?php foreach($news as $k=>$v): ?>
            <li <?php if($k%2 == 0): ?>class="bgf2" <?php endif; ?>><a href="<?php echo site_url('about/help_detail?cat_id='.$v['cat_id'].'&id='.$v['id']); ?>"><?php echo $k+1; ?> <?php echo str_replace($keyword,'<span style="color:red;">'.$keyword.'</span>',$v['title']); ?></a></li>
        <?php endforeach;?>
        </ul>
        <?php else: ?>
            <div style="color:#ff0000; padding-top:20px;">您搜索的关键词不存在，请重新输入！</div>
        <?php endif; ?>
</div>
<div class="help_ad"><a href="#"><img src="../../../../assets/images/bigpic/help_ad.jpg" width="750" height="136"></a></div>
</div>
<div class="clear"></div>
</div>
</div>

<!--footer start-->
<?php $this->load->view('common/footer'); ?>
<!--footer end-->

<script type="text/javascript" src="../../../../assets/js/jquery/jquery-1.11.1.min.js"></script>
<script type="text/javascript">
    $(function(){
        $(".subNav").click(function(){
            $(this).toggleClass("currentDd").siblings(".subNav").removeClass("currentDd")
            $(this).toggleClass("currentDt").siblings(".subNav").removeClass("currentDt")

            // 修改数字控制速度， slideUp(500)控制卷起速度
            $(this).next(".navContent").slideToggle(500).siblings(".navContent").slideUp(500);
        })
    })
</script>
</body>
</html>