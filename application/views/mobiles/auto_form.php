<!DOCTYPE html>
<html>
<head lang="en">
    <title>聚雪球</title>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <!--H5页面窗口自动调整到设备宽度，并禁止用户缩放页面-->
    <meta name="viewport"
          content="width=device-width,initial-scale=1.0,minimum-scale=1.0,maximum-scale=1.0,user-scalable=no"/>
    <!-- 忽略将页面中的数字识别为电话号码 -->
    <meta name="format-detection" content="telephone=no"/>
    <!-- 忽略Android平台中对邮箱地址的识别 -->
    <meta name="format-detection" content="email=no"/>
    <!-- 当网站添加到主屏幕快速启动方式，可隐藏地址栏，仅针对ios的safari -->
    <!-- ios7.0版本以后，safari上已看不到效果 -->
    <meta name="apple-mobile-web-app-capable" content="yes"/>
    <!-- 将网站添加到主屏幕快速启动方式，仅针对ios的safari顶端状态条的样式 -->
    <!-- 可选default、black、black-translucent -->
    <meta name="apple-mobile-web-app-status-bar-style" content="black"/>
    <!-- winphone系统a、input标签被点击时产生的半透明灰色背景怎么去掉 -->
    <meta name="msapplication-tap-highlight" content="no">
    <link rel="stylesheet" type="text/css" href="/assets/js/app/flexslide/css/flexslider-m.css">
    <link rel="stylesheet" type="text/css" href="/assets/css/app/radialindicator.css">
    <link rel="stylesheet" type="text/css" href="/assets/css/app/head.css">
    <link rel="stylesheet" type="text/css" href="/assets/css/app/m-common.css">
    <link rel="stylesheet" type="text/css" href="/assets/css/app/m_zdtb.css">
</head>
<body>
<!-- 公共头部导航-->
<?php $this->load->view('common/mobiles/app_common_head') ?>
<div class="placehold"></div>
<div class="con_wap">
    <!--设置中-->
    <div class="row zdkg tbms">
        <p class="tot">自动投模式<font class="pop_n">什么是自动投模式？</font></p>
        <p><input type="radio" name="mos" value="1"  class="radioItem" <?php  if($automatic_info['mode']==1||$automatic_info['mode']==''): echo 'checked';endif;?>/> 复投模式   <input type="radio" name="mos" value="2"  class="radioItem" <?php  if($automatic_info['mode']==2): echo 'checked';endif;?>/> 固额投资模式</p>
        <div class="pop">
            <div class="con">
                <h1>什么是自动投模式？<font class="fr close">X</font></h1>
                <h2>1 复投模式?</h2>
                <p>复投模式：将用户的可用余额全部投入到标的中</p>
                <h2>2 固额投资模式?</h2>
                <p>固额模式：按照用户设定的金额投入到标的中</p>
            </div>
        </div>
    </div>
    <div class="row zdkg rq">
        <p class="tot">有效期限</p>
        <p><input type="date" class="qx_start"  value="<?php echo my_date($automatic_info['pzsj_start'],2)?>">&nbsp;&nbsp;&nbsp;至&nbsp;&nbsp;&nbsp;<input type="date"  class="qx_end"  value="<?php echo my_date($automatic_info['pzsj_end'],2)?>"></p>
        <font class="tip"  id="explain">请选择有效期</font>
    </div>
    <div class="row zdtbsm">
        <div class="zdxz">
            标的类型
            <select name="bdlx" class="bdlx">
                <option value="0" selected="selected">不限</option>
				<?php foreach($product as $k => $v):?>
                <option value="<?php echo $v['cat_id']?>"><?php echo $v['category']?></option>
				<?php endforeach;?>
            </select>
        </div>
        <div class="zdxz">
            最低收益
            <input type="text" class="sy"  value="<?php echo $automatic_info['sy_min']?>">
            %
            <font class="tip" id="sy_explain">请正确填写最低收益</font>
        </div>
        <div class="zdxz">
            标的期限
            <input type="text"  class="qx"  value="<?php echo $automatic_info['jk_max']?>">
            个月
            <font class="tip" id="qx_explain">请正确填写标的期限</font>
        </div>
        <div class="zdxz" id="zdpe" style="display:none;">
            最大配额
            <input class="pe" type="text"  value="<?php if($automatic_info['mode']==2): echo $automatic_info['balance_ze'];endif;?>">
            元
            <font class="tip" id="pe_explain">请正确填写配额</font>
        </div>
    </div>
    <div class="row tc">
        <button class="tj">保存</button>
    </div>
    <!--设置中-->
	</div>
<?php $this->load->view('common/mobiles/app_alert') ?>
</body>
<?php $this->load->view('common/mobiles/app_footer') ?>
<script>
    //2222
    $('.pop_n').click(function(){
        $('.pop').slideDown();
    })
    $('.pop').find('.close').click(function(){
        $('.pop').slideUp();
    })
    //2222
	var old_mode = <?php echo (isset($automatic_info['mode']))? $automatic_info['mode'] : '1'?>;
	if(old_mode==1){
		 $('.pe').val('');
		 $("#zdpe").hide();
	}
	if(old_mode==2){
		$("#zdpe").show();
	}
	$(".radioItem").change(function(){ 
		var mode = $('input:radio[name="mos"]:checked').val();
		if(mode==1){
			$('.pe').val('');
			$("#zdpe").hide();
		}
		if(mode==2){
			$("#zdpe").show();
		}
	}); 
	//提交
	$('.tj').click(function(){
		var con = '';
		var mode = $('input:radio[name="mos"]:checked').val();
		var type = $('.bdlx').val();
		var qx_start = $('.qx_start').val();
		var qx_end = $('.qx_end').val();
		var sy = $('.sy').val();
		var qx = $('.qx').val();
		var pe = $('.pe').val();
		con = con + '?mode='+mode+'&type='+type;
		if(qx_start==''||qx_end==''){
			$('#explain').css('display','inline'); 
			return;
		}else{
			$('#explain').css('display','none'); 
			con = con + '&qx_start='+qx_start+'&qx_end='+qx_end;
		}
		if(isNaN(sy)||sy==''){
			$('#sy_explain').css('display','inline'); 
			return;
		}else{
			$('#sy_explain').css('display','none'); 
			con = con+'&sy='+sy;
		}
		if(isNaN(qx)||qx==''){
			$('#qx_explain').css('display','inline'); 
			return;
		}else{
			$('#qx_explain').css('display','none'); 
			con = con+'&qx='+qx;
		}
		if(mode==2){
			if(isNaN(pe)||pe==''){
				$('#pe_explain').css('display','inline'); 
				return;
			}else{
				$('#pe_explain').css('display','none'); 
				con = con+'&pe='+pe;
			}
		}
		$.post('/index.php/mobiles/home/auto_sub'+con,{},function(result){
				if(result=='ok'){
					window.location.href="/index.php/mobiles/home/auto_info";
				}else{
					alert(result);
				}
			});	
	})
</script>
</html>