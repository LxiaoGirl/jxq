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
		<a href="/index.php">首页</a>&nbsp;>&nbsp;<a href="javascript:void(0);">我的投资</a>&nbsp;>&nbsp;<a href="javascript:void(0);">聚保宝</a>
    </div>
    <div class="row user">
        <!--左侧通用-->
        <?php $this->load->view('common/user_left');?>
        <!--左侧通用-->
        <!--右侧-->
        <div class="user_right jbb">
            <!--弹出部分-->
                <div class="pop_bj"></div>
                <div class="pop pop_1">
                    <p class="tit">债权转让<font class="fr close">×</font></p>
                    <p>退出金额：<span id="out_amount">0</span>元</p>
					<p>持有天数：<span id="day">0</span>天</p>
                    <p>手续费：<span id="out_fee">0元</span></p>
					<p>服务费：<span id="service">0元</span></p>
					<p>实收金额：<span id="real_amount">0</span>元</p>
                    <p class="blu">您可在排队信息中查看退出进度</p>
                    <p class="but"><button class="qd ls" value=''>确定</button><button class="qx ls_1 close">取消</button></p>
                </div>
                <div class="pop pop_2">
                    <p class="tit">提取<span id="zong"></span>收益<font class="fr close">×</font></p>
                    <p>您将提取<span id="amount">0</span>元的<span id="zong_1"></span>收益</p>
					<p>服务费：<span id="service_out">0元</span></p>
                    <p class="blu">提取收益后复利天数重新开始计算</p>
                    <p class="but"><button class="qd ls" value="">确定</button><button class="qx ls_1 close">取消</button></p>
                </div>
                <div class="pop_3">
                    <img src="../../../../assets/images/user/jbb_pop.png">
                </div>
				
            <!--弹出部分-->
            <p class="jbb_tit">可领取的总收益（元）<button class="tq ls" value="总">提取</button><button class="qgm ls_1"  onclick="window.location.href='<?php echo site_url('invest/index?c=4');?>'">去购买</button><!--<font>我可以中途撤资吗？</font>--></p>
            <p class="zsy"><?php echo round($jbb_receive['data']['receive'],2)?></p>
            <ul class="ul1">
                <li>
                    <p>累计提取收益（元）</p>
                    <p class="sz"><?php echo $cumulative_yield['data']['cumulative_yield']?></p>
                </li>
                <li>
                    <p>加入总金额（元）</p>
                    <p class="sz"><?php echo $add_amount['data']['add_amount']?></p>
                </li>
                <li>
                    <p>购买笔数</p>
                    <p class="sz"><?php echo $buy_nums['data']['buy_nums']?></p>
                </li>
                <li>
                    <p>匹配标数<em title="将投资分散投入到标的数量">?</em></p>
                    <p class="sz"><?php echo $mate_nums['data']['mate_nums']?></p>
                </li>
            </ul>
            <ul class="ul2">
                <a href=""><li class="active"><font>聚保宝活期理财</a></li></font>
                <a href="<?php echo site_url('user/user/jbb_line');?>"><li><font>排队信息</a></li></font>
                <a href="<?php echo site_url('user/user/jbb_history');?>"><li><font>历史退出</a></li></font>
            </ul>
            <ul class="ul3">
			<?php if($jbb_list['status']=='10000'):?>
				<?php foreach($jbb_list['data']['data'] as $k => $v):?>
                <li class="li">
                    <div class="fl yfw"><span><?php echo  jbb_word($v['product_type'])?></span><p><?php echo $v['type_name']?></p></div>
                    <div class="fr">
                        <p class="dd"><span>订单编号：<?php echo $v['order_code']?></span><span>有效期限：<?php echo ($v['allawexit']==1)?'可长期持有':my_date(($v['interest_day']+$v['time_limit']*3600*24),2)?><em title="分自动退出和可长期持有2种，自动退出的显示为退出日期
">?</em></span><button ><?php echo (ceil((strtotime(date('Y-m-d'))-$v['interest_day'])/3600/24)<$v['closeday'])?($v['closeday']-ceil((strtotime(date('Y-m-d'))-$v['interest_day'])/3600/24)).'天后可申请退出'.'(已持有'.ceil((strtotime(date('Y-m-d'))-$v['interest_day'])/3600/24).'天)':'<button class="sqtc" id=" '.$v['id'].'" amount="'.round($v['amount'],2).'">申请退出</button>'?></button><span class="fr"><button class="details" tab="<?php echo $v['order_code'];?>" id="<?php echo $v['product_code']?>">查看投资详情</button>计息日：<?php echo my_date($v['interest_day'],2);?></span></p>
                        <ul class="ul4">
                            <li>
                                <p>加入金额</p>
                                <p class="sz"><?php echo round($v['amount'],2)?><font>元</font></p>
                            </li>
                            <li>
                                <p><?php if($v['isrepeat']==0):?>年化<em title="360天的收益率">?</em><?php else:?>预计年化<em title="360天的预计复利收益率">?</em><?php endif;?></p>
                                <p class="sz"><?php if($v['isrepeat']==0): echo $v['rate']?><?php else:?><?php echo round($v['expected_rate'],2)?><?php endif;?><font>%</font></p>
                            </li>
                            <li>
                                <p><?php if($v['isrepeat']==0):?>产生收益<em title="产生总收益
">?</em><?php else:?>复利天数<em title="未提取收益的天数
">?</em><?php endif;?></p>
                                <p class="sz"><?php   $day =(ceil((strtotime(date('Y-m-d'))-$v['interest_day'])/3600/24)>$v['closeday'])?$v['closeday']:ceil((strtotime(date('Y-m-d'))-$v['interest_day'])/3600/24) ; if($v['isrepeat']==0): echo round(jbb_no_product_amount($day,$v['rate'],$v['amount']),2).'<font>元</font>';else: echo ($v['allawexit']==0&&ceil((strtotime(date('Y-m-d'))-$v['interest_day'])/3600/24)>$v['closeday'])?($v['closeday']-$v['receive_days']):ceil((strtotime(date('Y-m-d'))-$v['interest_day'])/3600/24)-$v['receive_days'].'<font>天</font>';endif;?></p>
                            </li>
                            <li>
                                <p><?php if($v['isrepeat']==0):?>已领取收益<em title="已领取收益">?</em><?php else:?>产品收益<em title="未提取的收益">?</em><?php endif;?></p>
                                <p class="sz"><?php	$days = ($v['allawexit']==0&&ceil((strtotime(date('Y-m-d'))-$v['interest_day'])/3600/24)>$v['closeday'])?($v['closeday']-$v['receive_days']):ceil((strtotime(date('Y-m-d'))-$v['interest_day'])/3600/24)-$v['receive_days']; if($v['isrepeat']==0): echo $v['gain']; else:echo round(jbb_product_amount($days,$v['rate'],$v['amount']),2);endif;?><font>元</font></p>
                            </li>
                            <li class="tr">
                                <?php if($v['isrepeat']==0 && (ceil((strtotime(date('Y-m-d'))-$v['interest_day'])/3600/24)-$v['receive_days'])<$v['intervaldays']):?><?php echo ($v['intervaldays']-(ceil((strtotime(date('Y-m-d'))-$v['interest_day'])/3600/24)-$v['receive_days']))?>天后可提取收益<?php else:?><button class="tq" font="<?php echo $v['isrepeat']?>" id="<?php echo $v['id']?>">提取收益</button><?php endif;?>
                            </li>
                        </ul>
                    </div>
					<div class="jbb_lie_pop" data-tab="<?php echo $v['order_code'];?>">   
               		 </div>
                </li>
				
				<?php endforeach;?>
                <?php else:?>
					 <li class="li">
						<?php echo $jbb_list['msg']?>
					 </li>
				<?php endif;?>
				
            </ul>
			
			<?php echo ($links)?$links:'';?>
        </div>
        <!--右侧-->
    </div>
    <!--底部-->
	<?php $this->load->view('common/footer'); ?>
    <!--底部-->                   
</body>
<script type="text/javascript">
    seajs.use(['jquery','sys'],function(){
	
		$('.details').click(function  () {
			var e = $(this);
			var name = $(this).attr('tab');
			$.post('/index.php/user/user/jbb_jbb_details?type_code='+e.attr('id'),{},function(result){
				    var text = '<p class="month_lie_poptitle">';
					text=text+'<span class="tc"><strong>项目名称</strong></span>';
					text=text+'<span class="tc"><strong>项目年利率</strong></span>';
					text=text+'<span class="tc"><strong>项目融资金额（元）</strong></span>';
					text=text+'<span class="tc"><strong>项目状态</strong></span>';
					text=text+'</p>';
					result = JSON.parse(result);
					if(result.status==10000){
						var status = '-';
					for(var i=0;i<result.data.length;i++){
						if(result.data[i].status==2){
							 status = '募集中';
						}
						if(result.data[i].status==3){
							 status = '复审中';
						}
						if(result.data[i].status==4){
							 status = '还款中';
						}
						if(result.data[i].status==5){
							 status = '还款完成';
						}
						if(result.data[i].status==6){
							 status = '流标';
						}
						if(result.data[i].status==7){
							 status = '还款完成';
						}
						text = text+'<p class="month_lie_popnr">';
						text=text+'<span class="tc">'+result.data[i].subject+'</span>';
						text=text+'<span class="tc">'+result.data[i].rate+'%</span>';
						text=text+'<span class="tc">'+result.data[i].amount+'元</span>';
						text=text+'<span class="tc">'+status+'</span>';
						text=text+'</p>';
					}
					}else{
						text = text+'<p class="month_lie_popnr" style="text-align:center">等待配标完成...';
						text=text+'</p>';
					}
					e.parents().find('>[data-tab='+name+']').html(text);
				});		
						
            $(this).toggleClass('select');
			$(this).parents().find('>[data-tab='+name+']').slideToggle();
        })
		
        $('.user_right').find('.tq').click(function () {
			var id = this.id;		
			var value = $(this).val();
			var font = $(this).attr('font');
			$.post('/index.php/user/user/jbb_interest?id='+id,{},function(result){
						result = JSON.parse(result);
						var service = '';
						if(result.status==10000){
							$('#amount').html(result.data.receive);
							if(result.data.service>0){
								service = result.data.service+'元';
							}else{
								service = '免费';
							}	
							if(font==1){
								$('.blu').html('提取收益后复利天数重新开始计算');
							}else if(font==0){
								$('.blu').html('<a href="<?php echo site_url('invest/detail_jbb?type_code=JBB03')?>" target="_blank">查看收益计算规则</a>');	
							}else{
								$('.blu').html('提取收益后复利天数重新开始计算');
							}
							$('#zong').html(value);	
							$('#zong_1').html(value);
							$('#service_out').html(service);				
							$('.pop_2').find('.qd').val(id);
							$('.pop_bj').fadeIn();
							$('.pop_2').fadeIn();
						}else{
							wsb_alert(result.msg);
						}
					});
					
            // body...
            $('.pop_2').find('.close').click(function () {
                // body...
                $('.pop_bj').fadeOut();
                $('.pop_2').fadeOut();
            });
        });
		$('.pop_2').find('.qd').click(function () {
			var id = this.value;
			if($('#amount').html()>0){
				$.post('/index.php/user/user/jbb_sub_receive?id='+id,{},function(result){
							result = JSON.parse(result);
							if(result.status==10000){						
								$('.pop_bj').fadeOut();
								$('.pop_2').fadeOut();
								wsb_alert(result.msg,'',result.url);
							}else{
								$('.pop_bj').fadeOut();
								$('.pop_2').fadeOut();
								wsb_alert(result.msg);
							}
						});	
			}else{
				$('.pop_bj').fadeOut();
				$('.pop_2').fadeOut();
				wsb_alert('暂无可领取收益！');
			}
        });
		
        $('.user_right').find('.sqtc').click(function () {
			var id = this.id;
			var amount = $(this).attr("amount");
			var amounts =0;
			var fee ='';
			var real_amount=0;
			var service=0;
            // body...
			$.post('/index.php/user/user/jbb_interest?id='+id,{},function(result){
						result = JSON.parse(result);
						if(result.status==10000){
							amounts = parseFloat(result.data.receive)+parseFloat(amount);
							$('#out_amount').html(amounts);
							$('.pop_1').find('.qd').val(id);
							$('.pop_bj').fadeIn();
							$('.pop_1').fadeIn();
							$.post('/index.php/user/user/jbb_poundage?id='+id,{},function(result_a){
								result_a = JSON.parse(result_a);
								real_amount = amounts-parseFloat(result_a.data.fee);
								if(result_a.data.fee==0){
									fee = '免费';
								}else{
									fee = result_a.data.fee+'元';
								}
								if(result_a.data.service==0){
									service = '免费';
								}else{
									service = result_a.data.service+'元';
								}
								$('#out_fee').html(fee);
								$('#service').html(service);
								$('#day').html(result_a.data.day);
								$('#real_amount').html(real_amount);
								
							});
							
						}else{
							wsb_alert(result.msg);
						}
					});
            $('.pop_1').find('.close').click(function () {
                // body...
                $('.pop_bj').fadeOut();
                $('.pop_1').fadeOut();
            });
        });
        $('.pop_1').find('.qd').click(function () {
			var id = this.value;
			if($('#out_amount').html()>0){
				$.post('/index.php/user/user/jbb_out?id='+id,{},function(result){
							result = JSON.parse(result);
							if(result.status==10000){						
								$('.pop_bj').fadeOut();
								$('.pop_1').fadeOut();
								wsb_alert(result.msg,'',result.url);
							}else{
								$('.pop_bj').fadeOut();
								$('.pop_1').fadeOut();
								wsb_alert(result.msg);
							}
						});	
			}else{
				$('.pop_bj').fadeOut();
				$('.pop_1').fadeOut();
				wsb_alert('系统繁忙，请刷新页面再尝试！');
			}
        });
    });
</script>
</html>