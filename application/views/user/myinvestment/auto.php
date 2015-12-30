 <!DOCTYPE html>
<html>
<head>
    <title>聚雪球_网加金服_P2P理财首选互联网金融P2P网贷平台_100元即可投资!</title>
    <?php $this->load->view('common/head_file'); ?>
</head>
<body>
<!--head start-->
 <?php $this->load->view('common/head');?>
 <?php $zdt=(isset($auto['data']['statue']))?$auto['data']['statue']:'0';?>    
    <!--head end-->
    <!--user start-->
	<div class="user_nav row">
        <a href="/index.php">首页</a>&nbsp;>&nbsp;<a href="/index.php/user/user/account_home">我的投资</a>&nbsp;>&nbsp;<a href="javascript:void(0);">自动投标</a>
    </div>
    <div class="row user">
        <!--左侧通用-->
		<?php $this->load->view('common/user_left');?>
        <!--右侧-->

        <div class="user_right">
            <div class="black_bg"></div>
            <h1>自动投标</h1>
			
            <div class="ztxz">当前状态:<font class="wkq">未开启</font><font class="yqy">已开启</font><font class="szz">设置中</font>
                <div class="checkbox-toggle"><label class="toggle"><input id="checbox" type="checkbox" name="checkbox-toggle" <?php if($zdt==1):?>checked<?php endif;?>><i></i></label></div>
            </div>
            <div class="zdtb">
                <ul class="tab_con">
                    <li class="zdtbsz select">
                        <em class="jian"></em>
                        <div class="zdtsz">自动投设置</div>
                        <p class="sanfen"><input type="radio" name="tbms" class="tbms" value="ft" <?php if($auto['data']['mode']==1||$auto['data']['mode']==''): echo 'checked';endif;?>/>复投模式<input type="radio"  name="tbms"   class="tbms" value="ge" <?php if($auto['data']['mode']==2): echo 'checked';endif;?>/>固额投资模式<font class="fr tr font"><a href=""><i>?</i>有什么区别？</a></font><font class="fr tr font">自动投标模式</font></p>
                        <p class="sanfen"><input type="text" class="date_picker_1" value="<?php if(isset($auto['data']['pzsj_start'])):echo my_date($auto['data']['pzsj_start'],2);else:echo my_date(time(),2);endif;?>" readonly>至<input type="text" class="date_picker_2" value="<?php echo (isset($auto['data']['pzsj_end']))?my_date($auto['data']['pzsj_end'],2):date('Y-m-31',time());?>" readonly><font class="fr tr font"><a href=""><i>?</i>我该怎么选？</a></font><font class="fr tr font">本次设置有效期</font></p>
                        <div class="zdtsz">标的设置</div>
                        <p class="sanfen"><em <?php if($auto['data']['type']==0||$auto['data']['type']==''): ?>class="select"<?php endif;?> value="0">不限</em><?php foreach($project['data'] as $k => $v):?><em value="<?php echo $v['cat_id']?>" <?php if($auto['data']['type']==$v['cat_id']): ?>class="select"<?php endif;?>><?php echo $v['category']?></em><?php endforeach;?><font class="fr tr font">标的类别（单选）</font></p>
                        <p class="sanfen">最低收益<input class="wid60" type="text" name="" id = 'sy_min' value="<?php echo (isset($auto['data']['sy_min'])?$auto['data']['sy_min']:0)?>"/>%<font class="fr tr font"><a href=""><i>?</i>我该怎么选？</a></font><font class="fr tr font">标的最低范围</font></p>
                        <p class="sanfen">最长期限<input class="wid60" type="text" name="" id="jk_max" value="<?php echo (isset($auto['data']['jk_max'])?$auto['data']['jk_max']:12)?>"/>个月<font class="fr tr font"><a href=""><i>?</i>我该怎么选？</a></font><font class="fr tr font">标的最长期限</font></p>
                        <p class="sanfen">最大配额<input class="wid120" type="text" name="max_amount" id="max_amount"  readonly value="<?php echo ($auto['data']['mode']==2)?$auto['data']['balance_ze']:''?>"/>元<font class="fr tr font"><a href=""><i>?</i>我该怎么选？</a></font><font class="fr tr font">自动投标配额</font></p>
                        <p class="two_but"><button id="_bc">保存</button><button class="qx" id="_qx">取消</button></p>
                    </li>
					
                    <li class="wid400 select">
                        <em class="jian"></em>
                        <!--<p class="marbot_20"><a href="">如何修改设置？</a></p>-->
                        <p>模式：<font id="new_mode"><?php if($auto['data']['mode']==1): echo '复投模式';else: echo '固额模式';endif;?></font></p>
                        <p class="marbot_20">有效期限：<font id="new_date"><?php echo my_date($auto['data']['pzsj_start'],2)?>至<?php echo my_date($auto['data']['pzsj_end'],2)?></font></p>
                        <p>标的类别：<font id="new_type"><?php  if($auto['data']['type']==1): echo'车贷宝';elseif($auto['data']['type']==2): echo'聚农贷';elseif($auto['data']['type']==3): echo'聚惠理财';else: echo'不限';endif;?></font></p>
                        <p>标的收益范围：<font id="new_sy"><?php echo $auto['data']['sy_min']?>%以上(包括<?php echo $auto['data']['sy_min']?>%)</font></p>
                        <p class="marbot_20">标的收借款期限：<font id="new_qx"><?php echo $auto['data']['jk_max']?>个月以内</font></p>
                        <p>自动投资配额：<font id="balance"><?php if($auto['data']['mode']==2): echo $auto['data']['balance_ze'].'元人民币';else: echo '无限制';endif;?></font></p>
                    </li> 
					
                        <!--解除提示-->
                        <div class="user_data_pop"  style="width:400px; margin-left:-208px;">
                            <div class="title tc">
                                <span style="font-size:24px;">确定关闭自动投标吗？</span></font>
                            </div>
                            <div class="popbody">
                                <p  style="font-size:14px; line-height:20px; text-indent:24px;">关闭自动投标后，您将不会继续享受聚雪球标的自动匹配功能，更多高收益标的可能会被别人抢走哦。真的要关闭自动投标吗？</p>
                                <p class="tc"><button type="" class="user_data_pop_but zdtb" id="_gb">是，我要关闭</button><button type="" class="user_data_pop_but zdtb zdtb_gb" id="_bgb">我再考虑一下</button></p>
                            </div>
                        </div>
                        <!--解除提示-->
                </ul>
                <!--<p class="bt">1.什么是自动投标</p>
                <p class="nr">123</p>
                <p class="bt">1.什么是自动投标</p>
                <p class="nr">aaa</p>
                <p class="bt">1.什么是自动投标</p>
                <p class="nr">bbb</p>
                <p class="bt">1.什么是自动投标</p>
                <p class="nr">ccc</p>
                <p class="bt">1.什么是自动投标</p>
                <p class="nr">ddd</p>-->
            </div>
        </div>
        <!--右侧-->
    </div>
    <!--底部-->
	<?php $this->load->view('common/footer');?> 
    <!--底部-->       

<!--userjs start-->
<script src="<?php echo base_url('assets/js/jquery/jquery-1.8.5.min.js')?>"></script>
<script src="<?php echo base_url('assets/js/sys/sys.js')?>"></script>
<script type="text/javascript" src="<?php echo base_url('assets/js/plugins/jquery.date_input.pack.js')?>"></script> 
<script type="text/javascript">
    var zdtb=<?php echo $zdt?>;
    var dis=0;//设置按钮  0是可点  1是不可点 
    if(zdtb==1){
        $('.wid400').show();
        $('.ztxz').find('.yqy').addClass('run');
    }else{
        $('.ztxz').find('.wkq').addClass('run');
    }
	$(".tbms").change(function() {
		var selectedvalue = $("input[name='tbms']:checked").val();
		 if(selectedvalue=='ge'){
			$('#max_amount').removeAttr("readonly");
		 }
		 if(selectedvalue=='ft'){
			$('#max_amount').attr("readonly","readonly");
			$('#max_amount').val('');
		 }
	});
	var selectedvalue1 = $("input[name='tbms']:checked").val();
		 if(selectedvalue1=='ge'){
			$('#max_amount').removeAttr("readonly");
		 }
		 if(selectedvalue1=='ft'){
			$('#max_amount').attr("readonly","readonly");	
		 }	

    $('#checbox').click(function () {
        if(dis==1){
            return false;
        }else{
            var obj=$('#checbox');
            var value = obj.prop("checked"); 
            if(value){
                $('.tab_con').find('li').slideUp("slow",function(){
                    $('.tab_con').find('.zdtbsz').slideDown("slow",function () {
                        
                        $('.ztxz').find('font').removeClass('run');
                        $('.ztxz').find('.szz').addClass('run');
                    });
                });
				dis=1;
            }else{
                $('.black_bg').fadeIn();
                $('.user_data_pop').fadeIn();
            }  
        }   
    })
    $('#_bc').click(function () {
			var selectedvalue = $("input[name='tbms']:checked").val();
			var contion = '';
			var mode = 0;
			if(selectedvalue=='ge'){
				mode = 2;
			 }
			 if(selectedvalue=='ft'){
				mode = 1;
			 }
			var start_time = $('.date_picker_1').val();
			var start_end = $('.date_picker_2').val();
			var sy_min = $('#sy_min').val();
			var jk_max = $('#jk_max').val();
			var max_amount = $('#max_amount').val();
			var type = $('.sanfen').find('.select').attr('value');
			if(start_time==''){
				wsb_alert('请选择自动投开始时间');
				return;
			}
			if(start_end==''){
				wsb_alert('请选择自动投结束时间');
				return;
			}
			if(sy_min==''){
				wsb_alert('请填写最小收益利率');
				return;
			}
			if(jk_max==''){
				wsb_alert('请填写最大投资期限');
				return;
			}
			if(mode==2){
				if(max_amount==''){
					wsb_alert('请填写固定投资金额');
					return;
				}
			}
			contion = '?mode='+mode+'&start_time='+start_time+'&end_time='+start_end+'&sy_min='+sy_min+'&jk_max='+jk_max+'&type='+type+'&max_amount='+max_amount;
			$.post('/index.php/user/user/auto_sub'+contion,{},function(result){
			result = JSON.parse(result);
			if(result.status==10000){
				var new_mode = '';
				var new_type = '';
				var new_amount = '';
				if(mode==1){
					new_mode = '复投模式';
					new_amount = '无限制';
				}else{
					new_mode = '固投模式';
					new_amount = max_amount+'元人民币';
				}
				if(type==1){
					new_type = '车贷宝';
				}
				if(type==2){
					new_type = '聚农贷';
				}
				if(type==3){
					new_type = '聚惠理财';
				}
				if(type==0){
					new_type = '不限';
				}
				$('#new_mode').html(new_mode);
				$('#new_date').html(start_time+'至'+start_end);
				$('#new_type').html(new_type);	
				$('#new_sy').html(sy_min+'%以上(包括'+sy_min+'%)');
				$('#new_qx').html(jk_max+'个月以内');
				$('#balance').html(new_amount);	
				$('.tab_con').find('.zdtbsz').slideUp("slow",function(){
					$('.tab_con').find('.wid400').slideDown("slow",function () {
						dis=0;
						$('.ztxz').find('font').removeClass('run');
						$('.ztxz').find('.yqy').addClass('run');
					});
				});
			}else{
				wsb_alert('信息不完全，请仔细查看！');
			}
			});
    })
    $('#_qx').click(function () {
            $('.tab_con').find('.zdtbsz').slideUp("slow",function(){
                $('#checbox').removeAttr('checked');
                dis=0;
                $('.ztxz').find('font').removeClass('run');
                $('.ztxz').find('.wkq').addClass('run');
            });
    })
    $('#_gb').click(function () {
			$.post('/index.php/user/user/auto_out',{},function(result){
			result = JSON.parse(result);
			if(result.status == 10000){
				$('.black_bg').fadeOut();
				$('.user_data_pop').fadeOut();
				$('#checbox').removeAttr('checked');
				$('.tab_con').find('.wid400').slideUp("slow");
				dis=0;
				$('.ztxz').find('font').removeClass('run');
				$('.ztxz').find('.wkq').addClass('run');
			}else{
				wsb_alert('系统繁忙！');
			}
			});
    })
    $('#_bgb').click(function () {
            $('.black_bg').fadeOut();
            $('.user_data_pop').fadeOut();
            $('#checbox').prop("checked","ture");
            dis=0;
            $('.ztxz').find('font').removeClass('run');
            $('.ztxz').find('.yqy').addClass('run');
    })
	$('.sanfen').find('em').click(function(){
			$(this).siblings('em').removeClass('select');
			$(this).addClass('select');
	})
    $('.date_picker_1').date_input();
    $('.date_picker_2').date_input();
</script>
</body> 
</html>