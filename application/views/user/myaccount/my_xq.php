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
        <a href="/index.php">首页</a>&nbsp;>&nbsp;<a href="/index.php/user/user/account_home">我的账户</a>&nbsp;>&nbsp;<a href="javascript:void(0);">我的雪球</a>
    </div>
    <div class="row user">
        <!--左侧通用-->
		<?php $this->load->view('common/user_left');?> 
        <!--右侧-->
        <div class="user_right">
            <div class="my_level">
                <div class="h1">
                    <p class="top">我的雪球（个）<a href="<?php echo site_url('about/help'); ?>">如何获得雪球</a><a href="<?php echo site_url('about/help'); ?>">雪球能做什么</a></p>
                    <p class="bot"><?php echo isset($snowball_num)?$snowball_num:0?></p>
                </div>
                <p class="hbmx">雪球明细</p>
                    <div class="xqmx">
                        <p class="title"><span class="wid38">详情</span><span>类型</span><span>数量</span><span class="wid28">时间</span></p>
						<?php if($snowball_list['status']=='10000'):?>
						<?php foreach($snowball_list['data']['data'] as $k => $v):?>
						<?php if($v['flag']==1):?>
                        <p class="lie"><span class="wid38"><?php echo $v['active'];?><font><?php echo $v['Remark'];?></font></span><span>获得</span><span class="add"><?php echo $v['amount'];?></span><span class="wid28"><?php echo my_date($v['recordtime'],2);?></span></p>
						<?php else:?>
						<p class="lie"><span class="wid38"><?php echo $v['active'];?> <font><?php echo $v['Remark'];?></font></span><span>消耗</span><span class="redu"><?php echo $v['amount'];?></span><span class="wid28"><?php echo my_date($v['recordtime'],2);?></span></p>
						<?php endif;?>
                        <?php endforeach;?>
						<?php else:?>
						<p class="lie"><span class="wid38"><?php echo $snowball_list['msg'];?></span></p>
						<?php endif;?>
                    </div>
					<?php echo (isset($links))?$links:'';?>
            </div>
        </div>
        <!--右侧-->
    </div>
    <!--底部-->
	<?php $this->load->view('common/footer');?> 
    <!--底部-->       
</body> 
</html>