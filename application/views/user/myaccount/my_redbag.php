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
                    <li class="active">未领取<font class="fr">|</font></li>
                    <a href="<?php echo site_url('user/user/my_redbag_lq');?>"><li>已领取<font class="fr">|</font></li></a>
                    <a href="<?php echo site_url('user/user/my_redbag_gq');?>"><li>已过期</li></a>
                </ul>
                <ul class="tab_con">
                    <li class="active">
                        <p class="title"><span class="wid15">金额（元）</span><span>描述</span><span>有效期</span><span class="wid15"></span></p>
						<?php if($redbag_noreceive['status']!='10001'):?>
						<?php foreach($redbag_noreceive['data']['data'] as $k => $v):?>
                        <p class="lie"><span class="wid15"><?php echo $v['amount'];?></span><span><?php echo $v['active'];?></span><span><?php if($v['deadline']!=0):?><?php echo my_date($v['contract_time'],2);?>至<?php echo my_date($v['deadline'],2);?><?php else:?>永久有效<?php endif;?></span><span class="wid15"><button class="cancl" type="" id="receive" value="<?php echo $v['id'];?>">领取</button></span></p>
						<?php endforeach;?>
						<?php echo (isset($links))?$links:'';?>
						<?php else:?>
						<p class="lie"><span><?php echo $redbag_noreceive['msg'];?></span></p>
						<?php endif;?>						
                    </li>
                    <li>
					</li>
                    <li></li>
                </ul>
            </div>
        </div>
		<!--红包弹窗start-->
<div class="hbtc">
<div class="bj"></div>
<div class="hbtc_con">
    <div class="close"><input type="button" class="btn_close"/></div>
    <div class="hong_h1"><span id="hb_1"></span><span style="font-size:40px;">元</span></div>
    <div class="hong_txt">
        <span id="hb_3"></span><br>
    </div>
    <div class="ensure_w"><button class="btn_ensure" id="hb_2" value=""></button></div>
</div>
</div>
<!--红包弹窗 end-->
        <!--右侧-->
    </div>
    <!--底部-->
	<?php $this->load->view('common/footer');?> 
    <!--底部-->       

<!--userjs start-->
<script type="text/javascript">
    seajs.use(['jquery','sys'],function(){
        tab($('.my_level'));
		$("button").click(function(){
			var condition;
			condition='?id='+this.value;
			$.post('/index.php/user/user/redbag_id'+condition,{},function(result){
				result = eval(result);
				if(result[0].status=='10000'){
					$('#hb_1').html(result[0].data.amount);	
					$('#hb_3').html(result[0].data.active);	
					$('#hb_2').val(result[0].data.id);
				}else{
					alert(result[0].msg);
				}
			});
		});
		$("#hb_2").click(function(){
			var condition;
			condition='?id='+this.value;
			$.post('/index.php/user/user/Receive_redbag'+condition,{},function(result){
				result = eval(result);
				if(result[0].status=='10000'){
				location.href='/index.php/user/user/my_redbag_lq';
				}else{
				alert(result[0].msg);
				}

			});
		});

		pop_hb($('.cancl'),$('.hbtc'),$('.close'),$('.btn_ensure'));
    });
</script>
</body> 
</html>