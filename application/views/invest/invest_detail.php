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
        <p class="invest_nav"><a href="<?php echo site_url(); ?>"> 首页 </a>> <a href="<?php echo site_url('invest/index'); ?>"> 我要投资 </a>> <a href=""> 投资详情 </a></p>
        <!--面包屑导航-->
        <!--标的主体-->
        <div class="invest_body">
            <div class="black_bg"></div>
            <h1>
	            <?php echo $project['subject'];?>
	            <b>编号：<?php echo $project['borrow_no']; ?></b>
	            <span class="bao">
		            <em><?php echo type_name_2($project['type']); ?></em>
		            <font><?php echo $project['company_name']; ?></font>
	            </span>
<!--	            <span class="zhi"><em>A</em><font>支持自动投资</font></span>-->
<!--	            <span class="jia"><em>加</em><font>+0.9%</font></span>-->
	            <i class="fr"><a href="<?php echo site_url('about/invest_agreement');?>" target="_blank">《聚雪球投资协议（范本）》</a></i>
            </h1>
            <!---->
            <div class="invest_body_bdxx">
                <div class="hy fl">
                    <ul>
                        <li>
                            <p><i><img src="../../../../assets/images/invest/tzxx_1.png" alt=""></i>年化收益率</p>
                            <p class="qdcn"><?php echo rate_format($project['rate']); ?>%</p>
                        </li>
                        <li>
                            <p><i><img src="../../../../assets/images/invest/tzxx_2.png" alt=""></i>借款期限</p>
                            <p class="qdcn"><?php echo $project['months']; ?><font>个月</font></p>
                        </li>
                        <li>
                            <p><i><img src="../../../../assets/images/invest/tzxx_3.png" alt=""></i>项目金额(元)</p>
                            <p class="qdcn"><?php echo price_format($project['amount'],2,false); ?></p>
                        </li>
                    </ul>
                    <div class="hfzq fl">
                        <div class="hfzq_sp danshu"><font></font>还款方式： <?php echo $project['mode_name']; ?></div>
                        <div class="hfzq_sp"><font></font>发布日期：<?php echo date('Y-m-d',$project['add_time']); ?></div>
                        <div class="hfzq_sp danshu"><font></font>资金保障：<font class="orgener">100%</font>本息保障</div>
                        <div class="hfzq_sp"><font></font>起投金额：<?php echo rate_format($project['lowest']); ?>元</div>
                    </div>
                    <div class="djs fr time-down" data-start-time="<?php echo $project['buy_time']; ?>" data-end-time="<?php echo $project['due_date']; ?>" style="visibility: hidden;">
                        <p>项目倒计时</p>
                        <div class="djs_con djs_5">
                            还有<font class="d">00</font>天<font class="h">00</font>:<font class="m">00</font>:<font class="s">00</font><span class="js_flag">开始</span>
                        </div>
                    </div>
                </div>
                <div class="hy fr">
                    <a class="yw" href="<?php echo site_url('about/help_list?cat_id=36'); ?>">投资有疑问？点此查看帮助</a>
                    <p class="ktje">可投金额</p>
                    <p class="ktjes"><span><?php echo price_format($project['amount']-$project['receive'],2,false); ?></span><font>元</font></p>
                    <p class="jdt sy_jdt" jdt="<?php echo $project['receive_rate']; ?>"><span><i></i></span></p>
                    <p class="xmjd">项目进度：<?php echo $project['receive_rate']; ?>%</p>
                    <p class="yjsy cal" style="display: none;">预计收益：<font>0.00</font>元</p>
                    <form action="" method="" accept-charset="utf-8">
                        <div class="inpandbut">
                            <?php  if($project['new_status'] == 1 || $project['new_status'] == 2): ?>
                            <input class="invest-amount" type="text" maxlength="10" value="" placeholder="输入投资金额" <?php  if($project['new_status'] == 1): ?>style="display: none;" <?php endif; ?>><button id="invest-all" type="button" <?php  if($project['new_status'] == 1): ?>style="display: none;" <?php endif; ?>>全投</button>
                            <?php endif; ?>
                        </div>
                        <div class="tip"></div>
                        <div class="but">
                            <?php switch($project['new_status']){
                                case '1':
                                    echo '<p class="yjsy tc settime1" data-start-time="'.$project['buy_time'].'" data-end-time="'.$project['due_date'].'">距开标还剩<span class="d">00</span>天<span class="h">00</span>小时<span class="m">00</span>分<span class="s">00</span>秒</p>';
                                    echo '<button type="button" id="invest-button" class="jjksbut" data-status="1">即将开始</button>';
                                    break;
                                case '2':
                                    echo '<button type="button"  id="invest-button" data-status="2">马上投标</button>';
                                    break;
                                case '3':
                                    echo '<button type="button" class="ymbbut">已售罄</button>';
                                    break;
                                case '4':
                                    echo '<button type="button" class="ymbbut">回款中</button>';
                                    break;
                                case '5':
                                    echo '<div class="hkwc"> <div class="hkwc_top"></div> <div class="hkwc_bot"></div> <div class="pos-a-a"><a href="">回款完成</a></div> </div>';
                                    break;
                                case '6':
                                    echo '<div class="hkwc"> <div class="hkwc_top ygq"></div> <div class="hkwc_bot"></div> <div class="pos-a-a"><a href="" class="ygq">已过期</a></div> </div>';
                                    break;
                            } ?>
                            <!--提示样式1-->
                            <div class="but_pop_tip msg" style="display: none;"><p></p></div>
                        </div>
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
                <ul class="tab_title">
                    <li class="active">借款人信息<font class="fr">|</font></li>
                    <li>风控信息<font class="fr">|</font></li>
                    <li class="invest-list">投资记录<font class="fr">|</font></li>
                    <li class="repay-list">还款计划</li>
                    <span></span>
                </ul>
                <ul class="tab_con">
                    <li class="active jqrxx">
                        <h2 style="color: #3CB5EC;">借款人介绍</h2>
                        <?php echo (!empty($project['content'])) ? nl2br($project['content']) : '-'; ; ?>
                        <h2 style="margin-top: 10px; color: #3CB5EC;">资金用途</h2>
                        <?php echo (!empty($project['summary'])) ? nl2br($project['summary']) : '-'; ; ?><br/>
                    </li>
                    <li class="fkxx">
                        <?php echo (!empty($project['repayment'])) ? nl2br($project['repayment']) : '-'; ; ?>
                    </li>
                    <li class="tzjl">
                        <h2><span>流水号</span><span>投资人</span><span>金额（元）</span><span>时间</span></h2>
                        <div id="invest-list">
                            <p><span class="payment_no">0</span><span><font class="mobile">0</font></span><span class="amount">0</span><span class="pay_time">0000-00-00 00:00:00</span></p>
                        </div>
                    </li>
                    <li class="hkjh">
                        <h2><span>期数</span><span>应还利息（元）</span><span>应还本金（元）</span><span>剩余本金（元）</span><span>还款时间</span></h2>
                        <div id="repay-list">
                            <p><span><span class="repay_index">0</span>期</span><span class="repay_interest">0</span><span class="repay_principal">0</span><span class="repay_surplus_principal">0</span><span class="rapay_time">0000-00-00 00:00:00</span></p>
                        </div>
                    </li>
                </ul>
            </div>
            <!--TAB-->
        </div>
        <!--标的主体-->
    </div>

<?php $this->load->view('common/footer'); ?>

</body>
<script type="text/javascript">
    seajs.use(['jquery','sys'],function(){
        tab($(".invest_detail_tab"));

	    $(function(){
            //必要数据
            var can_invest_amount = parseFloat('<?php echo $project['amount']-$project['receive']; ?>'),
                is_login = '<?php echo $this->session->userdata('uid')?'1':'0'; ?>',
                is_security = '<?php echo $this->session->userdata('security')?'1':'0'; ?>',
                is_real_name = '<?php echo $this->session->userdata('clientkind'); ?>',
                invest_max = parseFloat('<?php echo $project['max']; ?>'),
                invest_min = parseFloat('<?php echo $project['lowest']; ?>'),
                my_balance = parseFloat('<?php echo $balance; ?>'),
                can_invest = '<?php echo $project['can_invest']?'1':'0'; ?>';
            if(invest_min > can_invest_amount)invest_min = can_invest_amount;
            if(invest_max > can_invest_amount)invest_max = can_invest_amount;

            //添加最大投资为默认值
            $(".invest-amount").val(invest_max);

            //提交
            var invest_submit = function(){
                $.ajax({
                    type: 'POST',
                    url: '<?php echo site_url('invest/ajax_invest'); ?>',
                    data: {'amount':$(".invest-amount").val(),'security':$('.security').val(),'borrow_no':'<?php echo $project['borrow_no']; ?>'},
                    dataType: 'json',
                    success: function (result) {
                        if(result.status == '10000') {
                            $('.black_bg').hide();
                            $('.invest_zjmm_pop').hide();
                            wsb_alert(result.msg,1,result.url);
                            var tt=setTimeout(function(){
                                clearTimeout(tt);
                                window.location.reload();
                            },2000)
                        }else{
                            wsb_alert(result.msg,2);
                        }
                    }
                });
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
                        $(".msg").show().find('p').html('你的余额不足<a href="<?php echo site_url('user/user/recharge'); ?>">充值</a>');
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
            //为投资按钮绑定事件
            if(can_invest == '1'){
                $('#invest-button').bind('click',function(){invest();});
                //全投处理
                $('#invest-all').bind('click',function(){
                    $(".invest-amount").val(my_balance>invest_max?invest_max:my_balance);
                });
                $('.cal').css({'display':'','visibility':'hidden'});

                if(isNaN($('.invest-amount').val())){
                    $('.invest-amount').val('');
                }else{
                    if($('.invest-amount').val() > 0){
                        var interest = calculator($('.invest-amount').val(),'<?php echo $project['rate']; ?>','<?php echo $project['months']; ?>','<?php echo $project['mode']; ?>');
                        $('.cal').css('visibility','visible').find('font').text(interest);
                    }else{
                        $('.cal').css('visibility','hidden').find('font').text(0);
                    }
                }
            }

            //倒计时处理
            if($('.time-down').length && '<?php echo $project['new_status']; ?>' < '3'){
                $('.time-down').count_down(function(obj){
                    obj.find('.js_flag').text('结束');
                },function(obj){
                    if($('#invest-button').attr('data-status') == '2')
                        $('#invest-button').html('投资结束').unbind('click').addClass('ymbbut');
                    obj.remove();
                });
            }
            //开标倒计时处理
            if('<?php echo $project['new_status']; ?>' == '1'){
                $('.time-down').hide();
                if($('.settime1').length){
                    $('.settime1').count_down(function(obj){
                        $('.invest-amount').show();
                        $('#invest-all').show();
                        //全投处理
                        $('#invest-all').unbind('click').bind('click',function(){$(".invest-amount").val(my_balance>invest_max?invest_max:my_balance);});
                        $('.cal').css({'display':'','visibility':'hidden'});
                        obj.siblings('button').removeClass('jjksbut').addClass('ajax-submit-button').html('马上投标').attr('data-status',2).attr('data-loadMsg','投资中...').bind('click',function(){invest();});
                        obj.remove();
                        $('.time-down').show();
                        ajax_loading(1);
                    });
                }
            }

            //预计收益 处理
            $(".invest-amount").bind('keyup',function(){
                if(isNaN($(this).val())){
                    $(this).val('');
                }else{
                    if($(this).val() > 0){
                        if($(this).val() > invest_max){
                            $(this).val(invest_max);
                        }
                        var interest = calculator($(this).val(),'<?php echo $project['rate']; ?>','<?php echo $project['months']; ?>','<?php echo $project['mode']; ?>');
                        $('.cal').css('visibility','visible').find('font').text(interest);
                    }else{
                        $('.cal').css('visibility','hidden').find('font').text(0);
                    }
                }
            });

            //清空默认值
            var is_focus = false;
            $(".invest-amount").bind('focus',function(){
                if( ! is_focus){
                    $(".invest-amount").val('');
                    is_focus = true;
                }
            }).bind('blur',function(){
                if(is_focus && $(".invest-amount").val() == ''){
                    $(".invest-amount").val(invest_max);
                    is_focus = false;
                }
            });

            //投资记录和还款记录处理
            var invest_list = $("#invest-list").clone();
            var repay_list = $("#repay-list").clone();
            var is_click1 = false;
            var is_click2 = false;
            $('.invest-list').bind('click',function(){
                if( ! is_click1){
                    each_html(invest_list,'/index.php/invest/ajax_get_invest_list',{'borrow_no':'<?php echo $project['borrow_no'] ?>'},{
                        'pay_time':function(v){ return unixtime_style(v,'Y-m-d H:i:s')},
                        'amount':function(v){return price_format(v,2,false)}
                    },true,function(obj,v){
                        switch (v.automatic_type){
                            case '1'://自动
                                obj.find('.mobile').append('<img style="cursor: pointer;" src="/assets/images/invest/a_zhi.png" title="自动投">');
                                break;
                            case '2'://自动
                                obj.find('.mobile').append('<img style="cursor: pointer;" src="/assets/images/invest/a_zhi.png" title="自动投">');
                                break;
                            case '3'://app
                                obj.find('.mobile').append('<img style="cursor: pointer;" src="/assets/images/invest/a_app.png" title="APP端投资">');
                                break;
                            case '4'://m
                                obj.find('.mobile').append('<img style="cursor: pointer;" src="/assets/images/invest/a_wap.png" title="手机端投资">');
                                break;
                            default://pc
                        }
                    },function(){is_click1=true;});
                }
            });
            $('.repay-list').bind('click',function(){
                if( ! is_click2) {
                    each_html(repay_list, '/index.php/invest/ajax_get_repay_list', {'borrow_no': '<?php echo $project['borrow_no'] ?>'}, {
                        'rapay_time':function(v){ return unixtime_style(v,'Y-m-d H:i:s')}
                    }, true, function (obj, v) {
                        if(v.rapay_time == 0)obj.find('.rapay_time').html('-');
                        switch (v.status){
                            case '1':
                                obj.find('.rapay_time').append('<img class="ywc" src="/assets/images/invest/ywc_c.png">');
                                break;
                            case '2'://提前
                                obj.find('.rapay_time').append('<img class="ywc" src="/assets/images/invest/tq_c.png">');
                                break;
                            case '3'://预期
                                obj.find('.rapay_time').append('<img class="ywc" src="/assets/images/invest/yq_c.png">');
                                break;
                            case '4'://预付
                                obj.find('.rapay_time').append('<img class="ywc" src="/assets/images/invest/yf_c.png">');
                                break;
                            default:
                                if(v.repay_date < unixtime_style(Date.parse(new Date())/1000,'Ymd')){
                                    //逾期
                                    obj.find('.rapay_time').append('<img class="ywc" src="/assets/images/invest/yq_c.png">');
                                }
                                break;
                        }
                    },function(){is_click2=true;});
                }
            });
	    });
    });
</script>
</html>