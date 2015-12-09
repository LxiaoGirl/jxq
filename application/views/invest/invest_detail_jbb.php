<!DOCTYPE html>
<html>
<head>
    <title>聚雪球_网加金服_P2P理财首选互联网金融P2P网贷平台_100元即可投资!</title>
	<?php $this->load->view('common/head_file'); ?>
</head>
<body>
<?php $this->load->view('common/head'); ?>

<!---->
<p class="hidden"><font class="syktje">1006000</font></p>
<!---->
    <div class="invest_detail row">
        <!--面包屑导航-->
        <p class="invest_nav"><a href="<?php echo site_url(); ?>"> 首页 </a>> <a href="<?php echo site_url('invest/index?c=4'); ?>"> 聚保宝 </a>> <a href=""> 投资详情 </a></p>
        <!--面包屑导航-->
        <!--标的主体-->
        <div class="invest_body">
            <div class="black_bg"></div>
            <h1>
	            <?php echo $jbb['data']['type_name']?>
	            <i class="fr"><a href="" target="_blank">《聚雪球聚保宝投资协议（范本）》</a></i>
            </h1>
            <!---->
            <div class="invest_body_bdxx invest_body_bdxx_1">
                <div class="hy fl">
                    <ul>
                        <li>
                            <span class='yi'><?php echo jbb_word(@$_GET['type_code'])?></span>
                        </li>
                        <li>
                            <p class='tl'>预期年化收益率</p>
                            <p class="tl qdcn"><?php echo $jbb_list['data']['rate']?>%</p>
                        </li>
                        <li>
                            <p class='tl'>分散投资于</p>
                            <p class="tl qdcn col_333"><?php echo $jbb_invest_nums['data']['jbb_invest_nums']?>标</p>
                        </li>
                        <li>
                            <p class='tl'>保障方式</p>
                            <p class="tl qdcn col_333">本息保障</p>
                        </li>
                    </ul>
                    <div class="hfzq fl">
                        <div class="hfzq_sp danshu">累计投资：<?php echo $jbb_all_invest['data']['jbb_all_invest']?>元 </div>
                        <div class="hfzq_sp">累计赚取：<?php echo round($jbb_all_Earn['data']['jbb_all_Earn'],2)?>元</div>
                        <div class="hfzq_sp danshu">累计入团：<?php echo $jbb_nums['data']['jbb_nums']?>人次</div>
                    </div>
                    <div class="djs fr time-down " data-start-time="<?php echo ($jbb_list['data']['start_day']+3600*$jbb_list['data']['start_time']); ?>" data-type="<?php echo $jbb_list['data']['type'];?>"  style="visibility: hidden;">
                        <p>项目倒计时</p>
                        <div class="djs_con djs_5">
                            还有<font class="d">00</font>天<font class="h">00</font>:<font class="m">00</font>:<font class="s">00</font><span class="js_flag">开始</span>
                        </div>
                    </div>
                </div>
                <div class="hy fr">
                    <a class="yw" href="<?php echo site_url('about/help_list?cat_id=36'); ?>">投资有疑问？点此查看帮助</a>
					<p class="jbb">可投额度:<span class="fr"><font><?php echo $jbb_list['data']['development_amount']-$jbb_list['data']['balance'];?></font>元</span></p>
                    <p class="jbb">投资上限:<span class="fr"><?php echo $jbb_list['data']['all_amount']?>元</span></p>
                    <p class="jbb">起投金额:<span class="fr"><?php echo $jbb_list['data']['start_amount']?>元</span></p>
					<?php if($this->session->userdata('uid')):?>
                    <!--<p class="jbb">我的可用余额:<span class="fr"><?php echo $balance;?>元</span></p>-->
					<?php else:?>
					<p class="jbb">您还未登录请点击<a href="<?php echo site_url('login'); ?>">登陆</a></p>
					<?php endif;?>
                    <form action="" method="" accept-charset="utf-8">
                        <p class="but">
							<?php if($jbb_list['data']['start_day']+3600*$jbb_list['data']['start_time']<=time()&&$jbb_list['data']['type']==1):?>
                            <input type="text" value="" placeholder="<?php echo $jbb_list['data']['start_amount']?>" class="invest-amount">
                            <input type="button"   id="invest-button" value="马上加入" >
							<p class="but_pop_tip msg" style="display: none;"></p>
							<?php elseif($jbb_list['data']['type']==2):?>
							<p class="tc"><input type="button" class="ymbbut" value="已售罄" ></p>
							<?php else:?>
							<p class="tc"><input type="button" class="jjks" value="即将开始" ></p>
							<?php endif;?>
                        </p>
						
                        <div class="invest_zjmm_pop">
                            <div class="invest_zjmm_pop_body">
                            <div class="title">
                                <span>输入资金密码</span><font class="fr">×</font>
                            </div>
                            <div class="popbody tc">
                                <input type="password" value="" class="security" placeholder="请输入资金密码"/>
                                <div class="fr tl tip_pop">
                                </div>
                                <button type="button" id="invest-submit" class="ajax-submit-button" data-loadMsg="投资中..." >提交</button>
                                <a href="<?php echo site_url('user/user/account_security'); ?>">不记得资金密码？点此找回</a>
                            </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <!---->
            <!--TAB-->
            <div class="invest_detail_tab">
                <ul class="tab_title tab_title_1">
                    <li class="active">投资详情<font class="fr">|</font></li>
                    <li class="invest">加入记录<font class="fr">|</font></li>
                    <li class="invest-list">常见问题</li>
                    <span></span>
                </ul>
                <ul class="tab_con">
                    <li class="active jbb_tzxq">
                        季季翻投资团1,000元起投，预计综合年化净收益率为8%+(利息复投功能开启时约为8.36%)。可随时退出，灵活性好（持有期退出费用最低）。3个月后退出（扣除退出费用）实得收益率约6%，6个月后退出实得收益约7%，1年后实得收益率约8.36%并免退出费用，可长期持有，投资时间越长，收益越高。
                        <p>
                            <span class="shen">
                                协议
                            </span>
                            <span class="qian">
                                <a href="">聚雪球聚保宝投资协议（范本）</a>
                            </span>
                        </p>
                        <p>
                            <span class="shen">
                                利息分配
                            </span>
                            <span class="qian">
                                入团当日开始生息, 天天付息。投资人在投资次日会收到前1日利息。投资人在年化净收益率6%基础上，每投资满90天后收到一笔分
红。预期分红金额为分红时点投资金额的0.5%（例如1月1日成功投资，则在1月2日收到首次利息，在4月2日收到首次分红，7月1日
收到第二次分红，以此类推，直至退出）, 相当于每第三个月年化收益率总计为12%, 使预期年化净收益率达到8% (利息复投功能开
启时约为8.36%)。 
                            </span>
                        </p>
                        <p>
                            <span class="shen">
                                费用说明
                            </span>
                            <span class="qian">
                                360天内退团：0.5%；360天后退团：0%。2014年12月11日前购买的团团赚债权，退团费率参照<a href="">聚雪球聚保宝投资协议（范本）</a>
                            </span>
                        </p>
                    </li>
                    <li class="tzjl">
                        <h2><span>流水号</span><span>投资人</span><span>金额（元）</span><span>时间</span></h2>
                        <div id="invest-lists">
                            
                        </div>
						<div class="invest_home_paging"></div>
                    </li>
                    <li class="jbb_cjwt">
                        <h2 style="font-size:16px; font-weight:100; line-height:40px;">1.我加入季季翻投资团并投资1万元，12个月后我能赚到多少钱？</h2>
                        季季翻投资团尊享预计年化8%的预期综合净收益。除了年化6%的利息收益之外，投资人每90天还可以得到0.5%的尊享分红。尊享分红的最终收益金额根据季季翻投资团所投的借款标的实际还款情况决定。假设1万元投资12个月，预期净收益为10,000*6%+10,000*0.5%*(12/3)=800元。其中600元为利息收益，200元为尊享分红。
                        <h2 style="font-size:16px; font-weight:100; line-height:40px;">2.季季翻投资团安全吗？</h2>
                        季季翻投资团投资享受本金保障计划，本息无忧。投资资金能够实现高度分散，并将6%以上的收益作为风险保证金，保障投资本金和6%的年化净收益。
                        <h2 style="font-size:16px; font-weight:100; line-height:40px;">3.我什么时候会收到利息和现金奖励？我该如何拿回本金？</h2>
                        投资后第1日起，投资人会收到前一天的利息（例如1月1日成功投资，则在1月2日收到首次利息）；此后每天，投资人会收到前一天的利息，直至期满退出。投资团收到的利息将先扣除10%的居间服务费后,按照年化6%的利率进行支付,超过6%的部分作为新产品投资团风险保证金。若当无借款人还款或借款人还款金额不足以向所有投资人分配年化6%的利息，则将在下一个收款日补足，以此类推。每投资满90天，投资人会额外收到一次来自风险保证金帐户的预计0.5%的尊享分红 (相当于每三个月年化收益率分别为6%, 6%, 12%) ，在后一天一次性发放到投资人的点融现金帐户中。投资期间收到的本金自动复投，团员可随时通过债权转让收回本金，退团费率为本金的0.50%。债权转让期间，投资人仍享受年化6%的收益率，债权转让成功当日的收益归于新的投资人。
                        <h2 style="font-size:16px; font-weight:100; line-height:40px;">4.我的钱投给了谁？我怎么知道还款情况如何？</h2>
                        我们坚持向投资人披露每一笔资金的去向，和每一笔借款的还款情况。投资完成后，请登陆投资人账号，在“我的账户>我的投资>团团赚”中，点击投资的投资团的“详情”进行查看。
                    </li>
                </ul>
            </div>
            <!--TAB-->
        </div>
        <!--标的主体-->
    </div>

<?php $this->load->view('common/footer'); ?>
<script type="text/javascript" src="<?php echo base_url('assets/js/page/jquery.page.js')?>"></script>
</body>
<script type="text/javascript">
    seajs.use(['jquery','sys','wsb_sys'],function(){
        tab($(".invest_detail_tab"));
		var is_login = '<?php echo $this->session->userdata('uid')?'1':'0'; ?>';
		var is_security = '<?php echo $this->session->userdata('security')?'1':'0'; ?>';
		var is_real_name = '<?php echo $this->session->userdata('clientkind'); ?>';
		var invest_max = parseFloat('<?php echo $jbb_list['data']['all_amount']; ?>');
		var invest_min = parseFloat('<?php echo $jbb_list['data']['start_amount']; ?>');
		var my_balance = parseFloat('<?php echo $balance; ?>');
		var old_type_code = '<?php echo $jbb_list['data']['type_code']?>';


		
		//倒计时处理
        if($('.time-down').length){
                $('.time-down').count_down(function(obj){
                  
                },function(obj){
					if($('.time-down').attr(' data-type') == 2){
					$('.time-down').html('已售罄！');
					return;
					}
                    if($('.time-down').attr('data-start-time') <= <?php echo time();?> && $('.time-down').attr(' data-type') == 1){
                    $('.time-down').html('标的已经开始！');
					return;
					}
                });
            }
			$('#invest-button').bind('click',function(){
				invest();
				});
		//提交
            var invest_submit = function(){
				$.post('/index.php/invest/ajax_jbb_sub?amount='+$(".invest-amount").val()+'&security='+$(".security").val()+'&type_code='+type_code,{},function(result){
					result = JSON.parse(result);
					$('.invest_zjmm_pop').fadeOut();
					$('.black_bg').fadeOut();
					if(result.status==10000){
						wsb_alert(result.msg,2,result.url);
					}else{
						wsb_alert(result.msg);
					}
				})
            };
		

		


		//投资处理
		var invest = function() {
               if (is_login == "1") {
                    if ( is_security == '0') {
                        $(".msg").show().find('p').html('您还没有设置资金密码哦<a href="<?php echo site_url('user/user/account_security'); ?>">设置资金密码</a>');
                        return false;
                    }
                    if ( is_real_name != '1') {
                        $(".msg").show().find('p').html('您还没有进行实名认证哦<a href="<?php echo site_url('user/user/account_security'); ?>">实名认证</a>');
                        return false;
                    }
					
                    var money = $(".invest-amount").val();
					if(money%invest_min!=0){
						money = $(".invest-amount").val(Math.floor(money/invest_min)*parseFloat(invest_min));
					}
                    if(money)money=parseFloat(money);
                    if (money == "") {
                        wsb_alert('请输入投资金额!',2);
                    }else if( ! /^[1-9]\d*$/.test(money)) {
                        wsb_alert('请输入正整数投资金额!',2);
                    }else if(money < invest_min) {
                        wsb_alert('投资金额不能小于￥' + invest_min + '元!',2);
                    }else if(money > invest_max) {
                        wsb_alert('投资金额不能大于￥' + invest_max + '元!',2);
                    }else if(money > my_balance) {
                        $(".msg").show().html('你的余额不足<a href="<?php echo site_url('user/user/recharge'); ?>">充值</a>');
                    }else{
                        $('.black_bg').fadeIn();
                        $('.invest_zjmm_pop').fadeIn("fast",function(){
                            $(".security").focus();
                            $('#invest-submit').bind('click',function(){
                                if($('.security').val().length < 6){
                                    wsb_alert('请输入正确格式的资金密码!',2);
                                }else{
                                    invest_submit();
                                }
                            });
                        });
                    }
                }else {
                    wsb_alert('您还没有登录哦！',2,'<?php echo site_url('login').'?redirect_url='.urlencode($this->c->show_url()); ?>');
                }
			};
			
			
    });
		$('.invest_zjmm_pop_body').find('.fr').click(function(){
			$('.invest_zjmm_pop').fadeOut();
			$('.black_bg').fadeOut();
		})
		
var page = 0;
var limit = 5;
var type_code = '<?php echo $jbb_list['data']['type_code']?>';
var pages = '<?php echo $total?>';
pages = Math.ceil(pages/limit);
var text ='<p>暂无相关数据</p>';
		$('.invest').click(function(){			
			$.post('/index.php/invest/detail_jbb_list?per_page='+limit*page+'&type_code='+type_code,{},function(result){
						result = JSON.parse(result);
						text = '';
						for(var i=0;i<result.data.data.length;i++){						
							text = text+'<p>';
							text=text+'<span class="payment_no">'+result.data.data[i].order_code+'</span>';
							text=text+'<span><font class="name">'+result.data.data[i].user_name.substring(0,1)+"**"+'</font></span>';
							text=text+'<span class="amount">'+result.data.data[i].amount+'</span>';
							text=text+'<span class="pay_time">'+new Date(parseInt(result.data.data[i].purchase_time) * 1000).toLocaleString().replace(/年|月/g, "-").replace(/日/g, " ")+'</span>';
							text=text+'</p>';	
						}
						$('#invest-lists').html(text);
					});	
		})
		//分页
if(pages!=0){
$(".invest_home_paging").createPage({
				pageCount:pages,
				current:1,
				backFn:function(p){
					$.post('/index.php/invest/detail_jbb_list?per_page='+limit*(p-1)+'&type_code='+type_code,{},function(result){
						page=p-1;
						result = JSON.parse(result);
						text = '';
						for(var i=0;i<result.data.data.length;i++){	
							text = text+'<p>';
							text=text+'<span class="payment_no">'+result.data.data[i].order_code+'</span>';
							text=text+'<span><font class="name">'+result.data.data[i].user_name.substring(0,1)+"**"+'</font></span>';
							text=text+'<span class="amount">'+result.data.data[i].amount+'</span>';
							text=text+'<span class="pay_time">'+new Date(parseInt(result.data.data[i].purchase_time) * 1000).toLocaleString().replace(/年|月/g, "-").replace(/日/g, " ")+'</span>';
							text=text+'</p>';	
						}
						$('#invest-lists').html(text);
					});					
				}
			});


	function   formatDate(now)   {     
              var   year=now.getYear();     
              var   month=now.getMonth()+1;     
              var   date=now.getDate();     
              var   hour=now.getHours();     
              var   minute=now.getMinutes();     
              var   second=now.getSeconds();     
              return   year+"-"+month+"-"+date+"   "+hour+":"+minute+":"+second;     
     }    
}
</script>
</html>