<!DOCTYPE html>
<html>
<head>
    <title>聚雪球_网加金服_P2P理财首选互联网金融P2P网贷平台_100元即可投资!</title>
    <?php $this->load->view('common/head_file'); ?>
</head>
<body>
<!--head start-->
<?php $this->load->view('common/head');?> 
    <!--head end-->
    <!--user start-->
	<div class="user_nav row">
        <a href="">首页</a>&nbsp;>&nbsp;<a href="">我的账户</a>&nbsp;>&nbsp;<a href="">我的红包</a>
    </div>
    <div class="row user">
        <!--左侧通用-->
		<?php $this->load->view('common/user_left');?> 
        <!--右侧-->
        <div class="user_right">
            <div class="my_level">
                <div class="h1">
                    <p class="top">获得的红包（个）<a href="<?php echo site_url('about/help'); ?>">如何获得红包</a><a href="<?php echo site_url('about/help'); ?>">红包能做什么</a></p>
                    <p class="bot"><?php echo (!empty($redbag_num['data']['num']))?$redbag_num['data']['num']:'0'?></p>
                </div>
                <p class="hbmx">红包明细</p>
                <ul class="tab_title ">
                    <a href="<?php echo site_url('user/user/my_redbag');?>"><li>未领取<font class="fr">|</font></li></a>
                    <li class="active">已领取<font class="fr">|</font></li>
                    <a href="<?php echo site_url('user/user/my_redbag_gq');?>"><li>已过期</li></a>
                </ul>
                <ul class="tab_con">
                    <li>
                    </li>
                    <li  class="active">
					<p class="title"><span class="wid15">金额（元）</span><span>描述</span><span>领取时间</span><span class="wid15"></span></p>
					<?php if($redbag_receive['status']!='10001'):?>
						<?php foreach($redbag_receive['data']['data'] as $k => $v):?>
                        <p class="lie"><span class="wid15"><?php echo $v['amount'];?></span><span><?php echo $v['active'];?></span><span><?php echo my_date($v['receive_time'],2);?></span><span class="wid15"><button type="">已领取</button></span></p>
						<?php endforeach;?>
						<?php echo (isset($links))?$links:'';?>
						<?php else:?>
						<p class="lie"><span><?php echo $redbag_receive['msg'];?></span></p>
						<?php endif;?>
						

					</li>
                    <li></li>
                </ul>
            </div>
        </div>

        <!--右侧-->
    </div>
    <!--底部-->
	<?php $this->load->view('common/footer');?> 
    <!--底部-->       

<!--userjs start-->
<script type="text/javascript">
    seajs.use(['jquery','sys'],function(){
        tab($('.my_level'));
		pop_hb($('.cancl'),$('.hbtc'),$('.close'),$('.btn_ensure'));
    });
</script>
</body> 
</html>