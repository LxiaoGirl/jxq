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
        <a href="">首页</a>&nbsp;>&nbsp;<a href="">我的账户</a>&nbsp;>&nbsp;<a href="">消息中心</a>
    </div>
    <div class="row user">
        <!--左侧通用-->
		<?php $this->load->view('common/user_left');?> 
        <!--右侧-->
        <div class="user_right">
            <div class="my_level">
                <div class="h1">
                    <p class="top">消息中心</p>
                </div>
                    <div class="xxcenter">
					<?php if($user_messages['status']=='10000'):?>
					<?php foreach($user_messages['data']['data'] as $k => $v):?>
                        <div class="lie">
                            <div class="top">
                            <div class="left fl">
							<?php if($v['type']==1):?>
							<span class="blue">充值</span>
							<?php endif;?>
							<?php if($v['type']==2):?>
							<span class="green">提现</span>
							<?php endif;?>
							<?php if($v['type']==3):?>
							<span class="yellow">冻结</span>
							<?php endif;?>
							<?php if($v['type']==4):?>
							<span class="red">投标</span>
							<?php endif;?>
							<?php if($v['type']==5):?>
							<span class="blue">审核</span>
							<?php endif;?>
							<?php if($v['type']==6):?>
							<span class="blue">满标</span>
							<?php endif;?>
							</div>
                            <div class="con_in fl"><?php echo $v['content'];?></div>
                            </div>
                            <div class="bot"><font class="fr"><?php echo my_date($v['send_time'],0);?></font></div>
                        </div>
					<?php endforeach;?>
					 <?php else:?>
						<div class="con_in fl">暂无消息</div>
					 <?php endif;?>
                    </div>

					<?php echo $links?>
            </div>
        </div>
        <!--右侧-->
    </div>
    <!--底部-->
	<?php $this->load->view('common/footer');?> 
    <!--底部-->       
</body> 
</html>