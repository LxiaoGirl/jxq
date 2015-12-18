<!DOCTYPE html>
<html>
<head>
    <title>聚雪球_网加金服_P2P理财首选互联网金融P2P网贷平台_100元即可投资!</title>
    <?php $this->load->view('common/head_file'); ?>
	<script type="text/javascript" src="<?php echo base_url('assets/js/plugins/qrcode.js')?>"></script>
	<script>
    window.onload = function () {
        var qrcode = new QRCode(document.getElementById("qrcode"), {
            width: 150,//设置宽高
            height: 150
        });
        qrcode.makeCode("https://www.zgwjjf.com/index.php/jujianren/jieshao?inviter_no=<?php echo $invite_code;?>");
    }
</script>
</head>
<body>
<!--head start-->
<?php $this->load->view('common/head');?>       
    <!--head end-->
    <!--user start-->
	<div class="user_nav row">
        <a href="/index.php">首页</a>&nbsp;>&nbsp;<a href="/index.php/user/user/account_information">账户设置</a>&nbsp;>&nbsp;<a href="javascript:void(0);">邀请好友</a>
    </div>
    <div class="row user">
        <!--左侧通用-->
		<?php $this->load->view('common/user_left');?>
        <!--右侧-->
        <div class="user_right">
            <div class="my_level">
                <div class="h1">
                    <p class="top">邀请好友<font>当前居间人等级：<span>VIP<font><?php echo (!empty($lv))?$lv:0?></font></span></font><a href="javascript:void(0);" title="收益=您邀请的好友投资的项目总金额 * 0.01*有效投资天数">收益如何计算？</a><a href="javascript:void(0);" title="收益会每月25号已红包的形式发送给您，您可以在我的红包去领取收益。">收益如何提取？</a></p>
                    <p class="bot"><font>已结算总额（元）：</font><?php echo $jujian_amount['data']['jujian_amount']?></p>
                    <div class="ewm">
                        <div class="ewm_but">查看我的二维码</div>
                        <div class="ewm_pop" id="qrcode" style="padding:10px;"></div>
                    </div>
                </div>
                <p class="hbmx">居间收益明细</p>
                <ul class="tab_title ">
                    <li class="active">居间人账户收益<font class="fr">|</font></li>
                    <a href="<?php echo site_url('user/user/invite_customer');?>"><li>客户列表</li></a>
                </ul>
                <ul class="tab_con invite">
                    <li class="active">
					<?php if($jujian_list['status']=='10000'):?>
					<?php foreach($jujian_list['data']['data'] as $k => $v):?>
                        <div class="month_lie">
                            <div class="month_pos_a"><?php echo $v['real_month']?></div>
                            <div class="tit_k">
                                <div class="fl">
                                    <h5><font>金额（元）:</font><?php echo $v['amount']?></h5>
                                    <p>结算日：<?php echo $v['pay_time']?></p>
                                    <p>结算状态：<font class="ywc"><?php if($v['status'] == 1):echo '已结算';elseif($v['status'] == 2):echo '已失效';else:echo '未结算';endif;?></font></p>
                                </div>
                                <div class="fr">
                                    <button class="invite_ckxq ls" id="<?php echo  $v['real_month'];?>">查看详情<em><em></em></em></button>
                                </div>
                            </div>
                            <div class="month_lie_pop">
                                
                            </div>
                        </div>
					<?php endforeach;?>
					<?php endif;?>
                    </li>
					<?php echo (isset($links))?$links:'';?>
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
        $('.ewm').hover(function() {
            $('.ewm_pop').slideToggle();
        });
        $('.invite_ckxq').click(function(){
			var e = $(this);
			$.post('/index.php/user/user/get_settle_invest_list?real_month='+$(this).attr('id'),{},function(result){
				    var text = '<p class="month_lie_poptitle">';
					text=text+'<span class="tl wid_108">项目名称</span>';
					text=text+'<span class="tc wid_108">客户名</span>';
					text=text+'<span class="tc wid_108">投资时间</span>';
                    text=text+'<span class="tc wid_108">有效天数</span>';
					text=text+'<span class="tc wid_125">投资金额（元）</span>';
					text=text+'<span class="tc wid_180">获得收益（元）</span>';
					text=text+'</p>';	
					result = JSON.parse(result);
					for(var i=0;i<result.data.length;i++){
						var d=new Date(parseInt(result.data[i].pay_time) * 1000).toLocaleString().substr(0,10);
						text = text+'<p class="month_lie_popnr">';
						text=text+'<span class="tl wid_108">'+result.data[i].subject+'</span>';
						text=text+'<span class="tc wid_108">'+result.data[i].user_name+'</span>';
						text=text+'<span class="tc wid_108">'+d+'</span>';
                        text=text+'<span class="tc wid_108">'+result.data[i].real_day+'</span>';
						text=text+'<span class="tc wid_125">'+result.data[i].invest_amount+'</span>';
						text=text+'<span class="tc wid_180">'+result.data[i].settle_amount+'</span>';
						text=text+'</p>';						
					}
					e.parent().parent().parent().find('.month_lie_pop').html(text);
				});
            $(this).toggleClass('select');
            $(this).parent().parent().parent().find('.month_lie_pop').slideToggle();
        });
    });
</script>
</body> 
</html>