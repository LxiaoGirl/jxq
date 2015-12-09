 <!DOCTYPE html>
<html>
<head>
    <title>聚雪球_网加金服_P2P理财首选互联网金融P2P网贷平台_100元即可投资!</title>
    <?php $this->load->view('common/head_file'); ?>
</head>
<body>
<!--head start-->
 <?php $this->load->view('common/head');?>
 <?php $zdt=0;?>    
    <!--head end-->
    <!--user start-->
	<div class="user_nav row">
        <a href="">首页</a>&nbsp;>&nbsp;<a href="">我的账户</a>&nbsp;>&nbsp;<a href="">我的雪球</a>
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
                        <p class="sanfen"><input type="radio" name="tbms" class="tbms" value="ft" checked/>复投模式<input type="radio"  name="tbms"   class="tbms" value="ge" />固额投资模式<font class="fr tr font"><a href=""><i>?</i>有什么区别？</a></font><font class="fr tr font">自动投标模式</font></p>
                        <p class="sanfen"><input type="text" class="date_picker_1">至<input type="text" class="date_picker_2"><font class="fr tr font"><a href=""><i>?</i>我该怎么选？</a></font><font class="fr tr font">本次设置有效期</font></p>
                        <div class="zdtsz">标的设置</div>
                        <p class="sanfen"><em class="select">不限</em><em>车贷宝</em><em>聚农贷</em><em>聚惠理财</em><em>债权转让</em><em>车贷宝2号</em><font class="fr tr font">标的类别（单选）</font></p>
                        <p class="sanfen">最低收益<input class="wid60" type="text" name=""/>%<font class="fr tr font"><a href=""><i>?</i>我该怎么选？</a></font><font class="fr tr font">标的最低范围</font></p>
                        <p class="sanfen">最长期限<input class="wid60" type="text" name=""/>个月<font class="fr tr font"><a href=""><i>?</i>我该怎么选？</a></font><font class="fr tr font">标的最长期限</font></p>
                        <p class="sanfen">最大配额<input class="wid120" type="text" name="" id="max_amount"  readonly/>元（可用余额800,000元）<font class="fr tr font"><a href=""><i>?</i>我该怎么选？</a></font><font class="fr tr font">自动投标配额</font></p>
                        <p class="two_but"><button id="_bc">保存</button><button class="qx" id="_qx">取消</button></p>
                    </li>
                    <li class="wid400 select">
                        <em class="jian"></em>
                        <p class="marbot_20"><a href="">如何修改设置？</a></p>
                        <p>模式：<font>复投模式</font></p>
                        <p class="marbot_20">有效期限：<font>2015-06-10至2015-10-10</font></p>
                        <p>标的类别：<font>聚农贷、聚惠理财、车贷宝2号</font></p>
                        <p>标的收益范围：<font>6.50%-12.00%</font></p>
                        <p class="marbot_20">标的收借款期限：<font>1个月-12个月</font></p>
                        <p>自动投资配额：<font>650000元人民币</font></p>
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
                <p class="bt">1.什么是自动投标</p>
                <p class="nr">什么是自动投标什么是自动投标什么是自动投标什么是自动投标什么是自动投标什么是自动投标</p>
                <p class="bt">1.什么是自动投标</p>
                <p class="nr">什么是自动投标什么是自动投标什么是自动投标什么是自动投标什么是自动投标什么是自动投标</p>
                <p class="bt">1.什么是自动投标</p>
                <p class="nr">什么是自动投标什么是自动投标什么是自动投标什么是自动投标什么是自动投标什么是自动投标</p>
                <p class="bt">1.什么是自动投标</p>
                <p class="nr">什么是自动投标什么是自动投标什么是自动投标什么是自动投标什么是自动投标什么是自动投标</p>
                <p class="bt">1.什么是自动投标</p>
                <p class="nr">什么是自动投标什么是自动投标什么是自动投标什么是自动投标什么是自动投标什么是自动投标</p>
            </div>
        </div>
        <!--右侧-->
    </div>
    <!--底部-->
	<?php $this->load->view('common/footer');?> 
    <!--底部-->       

<!--userjs start-->
<script src="<?php echo base_url('assets/js/jquery/jquery-1.8.5.min.js')?>"></script>
<script type="text/javascript" src="<?php echo base_url('assets/js/plugins/jquery.date_input.pack.js')?>"></script> 
<script type="text/javascript">
    var zdtb=<?php echo $zdt?>;
    var dis=0;
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
		 }
	});
		

    $('#checbox').click(function () {
        if(dis==1){
            return false;
        }else{
            var obj=$('#checbox');
            var value = obj.prop("checked"); 
            if(value){
                $('.tab_con').find('li').slideUp("slow",function(){
                    $('.tab_con').find('.zdtbsz').slideDown("slow",function () {
                        dis=1;
                        $('.ztxz').find('font').removeClass('run');
                        $('.ztxz').find('.szz').addClass('run');
                    });
                });
            }else{
                $('.black_bg').fadeIn();
                $('.user_data_pop').fadeIn();
            }  
        }   
    })
    $('#_bc').click(function () {
            $('.tab_con').find('.zdtbsz').slideUp("slow",function(){
                $('.tab_con').find('.wid400').slideDown("slow",function () {
                    dis=0;
                    $('.ztxz').find('font').removeClass('run');
                    $('.ztxz').find('.yqy').addClass('run');
                });
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
            $('.black_bg').fadeOut();
            $('.user_data_pop').fadeOut();
            $('#checbox').removeAttr('checked');
            $('.tab_con').find('.wid400').slideUp("slow");
            dis=0;
            $('.ztxz').find('font').removeClass('run');
            $('.ztxz').find('.wkq').addClass('run');
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