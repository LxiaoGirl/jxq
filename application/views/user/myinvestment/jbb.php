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
        <a href="">首页</a>&nbsp;>&nbsp;<a href="">我的投资</a>&nbsp;>&nbsp;<a href="">聚保宝</a>
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
                    <p class="but"><button class="qd" value=''>确定</button><button class="qx close">取消</button></p>
                </div>
                <div class="pop pop_2">
                    <p class="tit">提取收益<font class="fr close">×</font></p>
                    <p>您将提取<span id="amount">0</span>元的收益</p>
					<p>服务费：<span id="service_out">0元</span></p>
                    <p class="blu">提取收益后复利天数重新开始计算</p>
                    <p class="but"><button class="qd" value="">确定</button><button class="qx close">取消</button></p>
                </div>
                <div class="pop_3">
                    <img src="../../../../assets/images/user/jbb_pop.png">
                </div>
				
            <!--弹出部分-->
            <p class="jbb_tit">可领取的收益（元）<button class="tq">提取</button><a href="<?php echo site_url('invest/index?c=5');?>"><button class="qgm">去购买</button></a><font>我可以中途撤资吗？</font></p>
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
                    <p>匹配标数<em>?</em></p>
                    <p class="sz"><?php echo $mate_nums['data']['mate_nums']?></p>
                </li>
            </ul>
            <ul class="ul2">
                <a href=""><li class="active"><font>聚保宝活期理财</a></li></font>
                <a href="<?php echo site_url('user/user/jbb_line');?>"><li><font>排队信息</a></li></font>
                <a href="<?php echo site_url('user/user/jbb_history');?>"><li><font>历史预约</a></li></font>
            </ul>
            <ul class="ul3">
			<?php if($jbb_list['status']=='10000'):?>
				<?php foreach($jbb_list['data']['data'] as $k => $v):?>
                <li class="li">
                    <div class="fl yfw"><span><?php echo  jbb_word($v['product_type'])?></span><p><?php echo $v['type_name']?></p></div>
                    <div class="fr">
                        <p class="dd"><span>订单编号：<?php echo $v['order_code']?></span><span>有效期限：<?php echo ($v['allawexit']==1)?'可长期持有':my_date(($v['interest_day']+$v['time_limit']*3600*24),2)?><em>?</em></span><button ><?php echo (ceil((strtotime(date('Y-m-d'))-$v['interest_day'])/3600/24)<$v['closeday'])?($v['closeday']-ceil((strtotime(date('Y-m-d'))-$v['interest_day'])/3600/24)).'天后可申请退出':'<button class="sqtc" id=" '.$v['id'].'" amount="'.round($v['amount'],2).'">申请退出</button>'?></button><span class="fr">计息日：<?php echo my_date($v['interest_day'],2);?></span></p>
                        <ul class="ul4">
                            <li>
                                <p>加入金额<em>?</em></p>
                                <p class="sz"><?php echo round($v['amount'],2)?><font>元</font></p>
                            </li>
                            <li>
                                <p>预计年化<em>?</em></p>
                                <p class="sz"><?php echo round($v['expected_rate'],2)?><font>%</font></p>
                            </li>
                            <li>
                                <p>复利天数<em>?</em></p>
                                <p class="sz"><?php  echo ($v['allawexit']==0&&ceil((strtotime(date('Y-m-d'))-$v['interest_day'])/3600/24)>$v['closeday'])?($v['closeday']-$v['receive_days']):ceil((strtotime(date('Y-m-d'))-$v['interest_day'])/3600/24)-$v['receive_days']?><font>天</font></p>
                            </li>
                            <li>
                                <p>产品收益<em>?</em></p>
                                <p class="sz"><?php	$days = ($v['allawexit']==0&&ceil((strtotime(date('Y-m-d'))-$v['interest_day'])/3600/24)>$v['closeday'])?($v['closeday']-$v['receive_days']):ceil((strtotime(date('Y-m-d'))-$v['interest_day'])/3600/24)-$v['receive_days']; echo round(jbb_product_amount($days,$v['rate'],$v['amount']),2);?><font>元</font></p>
                            </li>
                            <li class="tr">
                                <button class="tq" id="<?php echo $v['id']?>">提取收益</button>
                            </li>
                        </ul>
                    </div>
                </li>	
				<?php endforeach;?>
                <?php else:?>
					 <li class="li">
						<?php echo $jbb_list['msg']?>
					 </li>
				<?php endif;?>
				<?php echo ($links)?$links:'';?>
                        </ul>
                    </div>
					
                </li>	
            </ul>
			
        </div>
        <!--右侧-->
    </div>
    <!--底部-->
	<?php $this->load->view('common/footer'); ?>
    <!--底部-->                   
</body>
<script type="text/javascript">
    seajs.use(['jquery','sys'],function(){
        $('.user_right').find('.tq').click(function () {
			var id = this.id;			
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