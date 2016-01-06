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
        qrcode.makeCode("https://www.juxueqiu.com/index.php/jujianren/jieshao?inviter_no=<?php echo $invite_code?>");
    }
</script>
</head>
<body>
<!--head start-->
<?php $this->load->view('common/head');?>       
    <!--head end-->
    <!--user start-->
    <div class="user_nav row">
        <a href="/index.php">首页</a>&nbsp;>&nbsp;<a href="/index.php/user/user/account_information">账户设置</a>&nbsp;>&nbsp;<a href="javascript:void(0);">邀请好友-客户列表</a>
    </div>
    <div class="row user">
        <!--左侧通用-->
        <?php $this->load->view('common/user_left');?>
        <!--右侧-->
        <div class="user_right">
            <div class="my_level">
                <div class="h1">
                    <p class="top">邀请好友<font>当前居间人等级：<span>VIP<font><?php echo ($lv)?$lv:0?></font></span></font><a href="javascript:void(0);" title="收益=您邀请的好友投资的项目总金额 * 0.01*有效投资天数">收益如何计算？</a><a href="javascript:void(0);" title="收益会每月25号已红包的形式发送给您，您可以在我的红包去领取收益。">收益如何提取？</a></p>
                    <p class="bot"><font>已结算总额（元）：</font><?php echo $jujian_amount['data']['jujian_amount']?></p>
                    <div class="ewm">
                        <div class="ewm_but">查看我的二维码</div>
                        <div class="ewm_pop"  id="qrcode" style="padding:10px;"></div>
                    </div>
                </div>
                <p class="hbmx">居间收益明细</p>
                <ul class="tab_title ">
                    <li><a href="<?php echo site_url('user/user/invite');?>">居间人账户收益</a><font class="fr">|</font></li>
                    <li class="active">客户列表</li>
                </ul>
                <ul class="tab_con invite">
                    <li class="active">
					<?php if($jujian['status']=='10000'):?>
					<?php foreach($jujian['data']['data'] as $k => $v):?>
                        <div class="month_lie" id="123">
                            <div class="kuhf_pos_a">
                                <div class="kh_tx"><img src="<?php echo base_url('assets/images/user/mrtx.png');?>"></div>
                                <h5><?php echo $v['real_name']?$v['real_name']:$v['user_name']; ?></h5>
								<h5><?php echo $v['ralation']; ?></h5>
                            </div>
                            <div class="tit_k">
                                <div class="fl">
                                    <h5 class="linh_gg"><font>累计投资金额金额（元）:</font><?php echo $v['amount']?></h5>
                                    <p>最近一次登录时间：<?php echo (my_date($v['last_date'],2))?my_date($v['last_date'],2):'很久没有登录过了哦'; ?></p>
                                </div>
                                <div class="fr">
                                    <button class="invite_ckxq ls" id="<?php echo $v['uid'] ;?>">查看详情<em><em></em></em></button>
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
        $('.ewm_but').hover(function  () {
            $('.ewm_pop').slideToggle();
        });
        $('.invite_ckxq').click(function  () {
			var e = $(this);
			$.post('/index.php/user/user/get_commission_list?uid='+$(this).attr('id'),{},function(result){
				    var text = '<p class="month_lie_poptitle">';
					text=text+'<span class="tc wid_159">投资时间</span>';
					text=text+'<span class="tc wid_159">项目名称</span>';
					text=text+'<span class="tc wid_159">项目状态</span>';
					text=text+'<span class="tc wid_159">投资金额（元）</span>';
					text=text+'</p>';
					result = JSON.parse(result);
					for(var i=0;i<result.data.length;i++){
						if(result.data[i].status==4){
							var status = '还款中';
						}
						if(result.data[i].status==7){
							var status = '还款完成';
						}
						text = text+'<p class="month_lie_popnr">';
						text=text+'<span class="tc wid_159">'+unixtime_style(result.data[i].dateline,'Y-m-d')+'</span>';
						text=text+'<span class="tc wid_159">'+result.data[i].subject_1+'</span>';
						text=text+'<span class="tc wid_159">'+status+'</span>';
						text=text+'<span class="tc wid_159">'+result.data[i].invest_amount+'</span>';
						text=text+'</p>';
					}
					e.parent().parent().parent().find('.month_lie_pop').html(text);
				});			
            $(this).toggleClass('select');
            $(this).parent().parent().parent().find('.month_lie_pop').slideToggle();
        })
    });
</script>
</body> 
</html>