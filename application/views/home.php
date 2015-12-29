<!DOCTYPE html>
<html>
<head>
    <title>聚雪球_网加金服_P2P理财首选互联网金融P2P网贷平台_100元即可投资!</title>
	<!--	加载头部样式文件-->
	<?php $this->load->view('common/head_file'); ?>
</head>
<body>
<!--	加载头部文件-->
	<?php $this->load->view('common/head'); ?>

<!--home start-->
    <!--banner-->
    <div class="banner" id="js_banner">
        <a href="" class="link_url" target="_blank"><div class="bannerbgdiv" style="width:100%; height:350px;"></div></a>
    </div>
    <!--banner-->
    <!--gg-->
    <div class="home_bulletin">
        <div class="row">
            <div class="row_1">
                <div class="con" id="announcement-top">
                    <p><a href="" class="link_url">【最新动态】<span class="title"></span></a></p>
                </div>
            </div>
        </div>
    </div>
    <!--gg-->
    <!--三个特点-->
    <div class="row">
        <ul class="home_three_c">
            <li>
                <p>优势股东</p>
                <p>国资平台 银行监管</p>

            </li>
            <li>
                <p>安全保障</p>
                <p>国资担保 本息保障</p>
            </li>
            <li>
                <p>高额收益</p>
                <p>预期收益率6%-13.2%</p>
            </li>
        </ul>
    </div>
    <!--三个特点-->
    <!--平台数据-->
    <div class="row">
        <ul class="home_platform_data">
            <li>累计注册人数：<font><?php echo rate_format(price_format($total['user_total'],2,false)); ?></font>人</li>
            <li class="sec">累计投资总额：<font><?php echo rate_format(price_format($total['invest_total'],2,false)); ?></font>元</li>
            <li class="thr">累计运行天数：<font><?php echo rate_format(price_format($total['days_total'],2,false)); ?></font>天</li>
            <li class="four">风险保证金：<font><?php echo rate_format(price_format($total['risk_total'],2,false)); ?></font>元</li>
        </ul>
    </div>
    <!--平台数据-->
    <div class="row pdt10">
        <!--左侧-->
        <div class="main_l product">
            <ul>
                <li>
                    <h1>聚在元旦趴  超值好标限时抢  APPLE watch等豪礼疯狂送<a href="/active/yd_2016/index.html" class="fr">查看活动详情 > </a></h1>
                    <div class="product_body">
                        <div class="top">
                            <div class="icon fl" style = 'background: #83bf73;'>稳</div>
                            <div class="title fl" style="color:#3cb5ec; margin-left:80px;">聚稳盈</div>
                            <div class="baozhi fl" style="min-width:300px">&nbsp;</div>
                        </div>
                        <p>“聚稳盈”是聚雪球网络平台推出的期限固定型投资产品，以聚雪球网络平台提取的部分融资项目作为投资标的，投资人购买本产品后，聚雪球后台系统将收到投资人指令，依照分散投资原则由系统自动进行投标，并在收到用户指令的情况下由系统自动发起债权转让，通过出让持有的债权实现流动性的需求，进而实现分散投资并获取收益的目的。</p>
                        <ul>
                            <li>
                                <div class="product_four_num_top tc">年化收益率</div>
                                <div class="product_four_num_bot tc ft_3cb5ec">9.6<i>%</i></div>
                            </li>
                            <li>
                                <div class="product_four_num_top tc">起投金额(元)</div>
                                <div class="product_four_num_bot tc">1000</div>
                            </li>
                            <li>
                                <div class="product_four_num_top tc">保障方式</div>
                                <div class="product_four_num_bot tc ft_14_my">本息保障</div>
                            </li>
                        </ul>
                        <div class="bot">
                           <div class="fr tc">
                                <button class="ls button1" onclick="window.location.href='https://www.zgwjjf.com/index.php/invest/detail_jbb?type_code=JBB04'">马上投资</button>
                            </div>
                        </div>
                        <div class="corner"></div>
                    </div>
                </li>
                <?php if($category): ?>
                <h1>
                    <ul class="sy_xm_tit">
                    <?php foreach($category as $k=>$v):?>
                            <li <?php if($k==0): ?>class="active"<?php endif; ?> data-cat-id="<?php echo $v['cat_id']; ?>"><?php echo $v['category']; ?></li>
                    <?php endforeach;?>
                    <a href="<?php echo site_url('invest/index?c=1') ?>" class="fr all-project-tag">查看全部项目 > </a>
                    </ul>
                </h1>
                <?php foreach($category as $key=>$val):?>
                    <?php if($val['project']):?>
                        <li class="bidi <?php if($key==0): ?>active<?php endif; ?>">
                            <?php  foreach($val['project'] as $k=>$v): ?>
                            <div class="product_body">
                                <div class="top">
                                    <div class="title fl"><a href="<?php echo site_url('invest/detail?borrow_no='.$v['borrow_no']); ?>"><?php echo $v['subject']; ?></a></div>
                                    <div class="baozhi fl">
                                    <?php if(!empty($v['company_name'])): ?>
                                        <span class="bao">
                                            <em><?php echo type_name_2($v['type']); ?></em>
                                            <font><?php echo $v['company_name']; ?></font>
                                        </span>
                                    <?php endif;?>
    <!--						            <span class="jia"><em>加</em><font>+0.9%</font></span>-->
                                    </div>
                                    <?php if($v['new_status'] == 1 || $v['new_status'] == 2): ?>
                                        <div class="djs fl time-down" data-start-time="<?php echo $v['buy_time']; ?>" data-end-time="<?php echo $v['due_date']; ?>" style="visibility: hidden;<?php if($v['new_status'] == 1): ?>display:none;<?php endif; ?>">
                                            还有<font class="d">00</font>天<font class="h">00</font>:<font class="m">00</font>:<font class="s">00</font><span class="js_flag">开始</span>
                                        </div>
                                    <?php endif; ?>
                                </div>
                                <p><?php echo mb_substr($v['summary'],0,120).'...'; ?></p>
                                <ul>
                                    <li>
                                        <div class="product_four_num_top tc">年化收益率</div>
                                        <div class="product_four_num_bot tc ft_3cb5ec"><?php echo $v['rate']; ?><i>%</i></div>
                                    </li>
                                    <li>
                                        <div class="product_four_num_top tc">借款期限(<?php echo $v['months']==0.9?'天':'月'; ?>)</div>
                                        <div class="product_four_num_bot tc"><?php echo $v['months']==0.9?$v['months']*30:$v['months']; ?></div>
                                    </li>
                                    <li>
                                        <div class="product_four_num_top tc">借款总额(万元)</div>
                                        <div class="product_four_num_bot tc"><?php echo price_format($v['amount'],3,false); ?></div>
                                    </li>
                                    <li>
                                        <div class="product_four_num_top tc">起投金额(元)</div>
                                        <div class="product_four_num_bot tc"><?php echo rate_format($v['lowest']); ?></div>
                                    </li>
                                    <li>
                                        <div class="product_four_num_top tc">还款方式</div>
                                        <div class="product_four_num_bot tc ft_14_my"><?php echo $v['mode']; ?></div>
                                    </li>
                                </ul>
                                <div class="bot">
                                    <div class="fl sy_jdt" jdt="<?php echo $v['receive_rate']; ?>" pos="<?php echo $v['receive_rate']-3; ?>">
                                        <span><i></i><font class="font"><?php echo $v['receive_rate']; ?>%</font></span>
                                    </div>
                                    <div class="fr tc">
                                        <?php if($v['can_invest']): ?>
                                            <h5>可投金额：<font><?php echo rate_format(price_format($v['amount']-$v['receive'],2,false)); ?></font>元</h5>
                                            <button class="invest-button ls button1" data-status="<?php echo $v['new_status']; ?>" onclick="window.location.href='<?php echo site_url('invest/detail?borrow_no='.$v['borrow_no']); ?>'">马上投资</button>
                                        <?php else: switch($v['new_status']){
                                            case '1':
                                                echo '<h5
                                                class="settime"
                                                data-start-time="'.$v['buy_time'].'"
                                                data-end-time="'.$v['due_date'].'"
                                                data-amount="'.rate_format(price_format($v['amount']-$v['receive'],2,false)).'"
                                                data-borrow_no="'.$v['borrow_no'].'">
                                                <span>距开标 还剩<span class="d">00</span>天<span class="h">00</span>小时</span>
                                                </h5>';
                                                echo '<button class="invest-button button1 ls_1" data-status="'.$v['new_status'].'"  onclick="window.location.href=\''.site_url('invest/detail?borrow_no='.$v['borrow_no']).'\'">即将开始</button>';
                                                break;
                                            case '3':
                                                echo '<h5></h5>';
                                                echo'<button class="invest-button button1 hs" data-status="'.$v['new_status'].'"  onclick="window.location.href=\''.site_url('invest/detail?borrow_no='.$v['borrow_no']).'\'">复审中</button>';
                                                break;
                                            case '4':
                                                echo '<h5></h5>';
                                                echo'<button class="invest-button button1 hs" data-status="'.$v['new_status'].'"  onclick="window.location.href=\''.site_url('invest/detail?borrow_no='.$v['borrow_no']).'\'">回款中</button>';
                                                break;
                                            case '5':
                                                echo '<h5></h5>';
                                                echo '<div class="hkwc"><div class="hkwc_top"></div><div class="hkwc_bot"></div> <div class="pos-a-a"><a href="'.site_url('invest/detail?borrow_no='.$v['borrow_no']).'">回款完成</a></div></div>';
                                                break;
                                            case '6':
                                                echo '<h5></h5>';
                                                echo '<div class="hkwc"> <div class="hkwc_top ygq"></div> <div class="hkwc_bot"></div> <div class="pos-a-a"><a href="'.site_url('invest/detail?borrow_no='.$v['borrow_no']).'" class="ygq">已过期</a></div> </div>';
                                                break;
                                        }?>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                <?php if($v['months'] <= 0.2):  ?><div class="corner"></div><?php endif;  ?>
                                <?php if($v['active'] == 1):  ?><div class="corner"></div><?php endif;  ?>
                            </div>
                            <?php endforeach; ?>
                        </li>
                    <?php else: ?>
                        <li style="text-align: center;">暂无相关信息</li>
	                <?php endif; ?>
	            <?php endforeach;else: ?>
                    <li style="text-align: center;">暂无相关信息</li>
                <?php endif; ?>
            </ul>
        </div>
        <!--左侧-->
        <!--右侧-->
        <div class="main_f">
            <div class="lcdrb">
                <h1>理财达人榜<!--<a href="<?php echo site_url('invest/ranking_list'); ?>" class="fr">查看详细榜单 > </a>--></h1>
                <ul class="tab_title">
                    <li class="active invest-all">总投资榜</li>
                    <li class="invest-month">月投资榜</li>
                </ul>
                <ul class="tab_con">
                    <li class="active">
                        <p class="title"><span>名次</span><span>用户名</span><span>累计投资金额（元）</span></p>
                        <div id="invest-total-list">
                            <p><span class="key"></span><span  class="mobile"></span><span class="invest_total"></span></p>
                        </div>
                    </li>
                    <li>
                        <p class="title"><span>名次</span><span>用户名</span><span>月累计投资金额（元）</span></p>
                        <div id="invest-total-list-month">
                            <p><span class="key"></span><span class="mobile"></span><span class="invest_total"></span></p>
                        </div>
                    </li>
                </ul>
            </div>
            <!--<div class="xssp">
                <h1>新手视频</h1>
                <a href=""><img src="../../../assets/images/bigpic/video_getin.jpg" alt=""></a>
                <p>投资人每个月会收到借款人相同金额的还款，一直到整个投资周期结束。月收益是由部分本金和当月利息组成的，这种方式可提高投资人的资金流动性。</p>
            </div>-->
            <div class="gfgg">
                <h1>官方动态<a href="<?php echo site_url('about/news'); ?>" class="fr">查看全部动态 > </a></h1>
                <div id="announcement-down">
                    <p class="gg"><a href="" class="link_url"><font class="key">1</font><span class="title"></span></a></p>
                </div>
            </div>
<!--
            <div class="axgyj">
                <h1>爱心公益基金</h1>
                <a href="">
                    <img src="../../../assets/images/bigpic/love_charity.jpg" alt="">
                    <p>
                        已筹集</br>
                        <font><?php echo price_format($public_fund,2,false) ?></font>
                        元
                    </p>
                </a>
            </div>
            -->
        </div>
        <!--右侧-->
    </div>
<!--home end-->

<!--	加载脚部文件-->
<?php $this->load->view('common/footer'); ?>
<script type="text/javascript">
	seajs.use(['jquery','sys','slider','wsb_sys'],function(){
		tab($(".lcdrb"));
        //标地TAB
        $('.sy_xm_tit').find('li').click(function(){
            $(this).addClass('active');
            $(this).siblings().removeClass('active');
            var i=$(this).index();
            $('.bidi').hide();
            $('.bidi').eq(i).fadeIn();
            $('.all-project-tag').attr('href','/index.php/invest/index?c='+$(this).data('catId'));
        });
        //标地TAB
		$(function(){
            //倒计时处理
            if($('.time-down').length){
                $('.time-down').count_down(function(obj){
                    obj.find('.js_flag').text('结束');
                },function(obj){
                    if(obj.parent().parent().find('.invest-button').attr('data-status') == '2')
                        obj.parent().parent().find('.invest-button').html('投资结束').attr('href','javascript:void(0);').addClass('ymbbut').siblings('H5').text('');
                    obj.remove();
                },'',<?php echo time(); ?>);
            }
            //按钮上面的倒计时
            if($('.settime').length) {
                $('.settime').count_down(function (obj) {
                    obj.html('可投金额：<font>' + obj.attr('data-amount') + '</font>元').siblings('a').removeClass('jjksbut').attr('href', '<?php echo site_url('invest/detail?borrow_no='); ?>' + obj.attr('data-borrow_no')).html('马上投资').attr('data-status', 2);
                    obj.parents('.home_product_body').find('.time-down').css('display', '');
                },'',function(flag,e,d,h,m,s){
                    if(d == 0 && h == 0){
                        e.find('span').html('距开标 还剩<span class="m">'+m+'</span>分<span class="s">'+s+'</span>秒');
                    }else{
                        e.find('.h').text(h);
                        e.find('.d').text(d);
                    }
                },<?php echo time(); ?>);
            }
            //投资榜
            var invest_html = $('#invest-total-list').clone(),invest_month_html = $('#invest-total-list-month').clone();
            each_html(invest_html,'/index.php/home/ajax_get_user_invest_total_list',{},{'invest_total':function(val){ return price_format(val,2,false);}},true,function(obj,v){
                if(v.key == 1)obj.find('.key').addClass('fri').html('');
                if(v.key == 2)obj.find('.key').addClass('two').html('');
                if(v.key == 3)obj.find('.key').addClass('tre').html('');
            });
            //投资榜绑定事件
            $(".invest-all").bind('click',function(){
                each_html(invest_html,'/index.php/home/ajax_get_user_invest_total_list',{},{'invest_total':function(val){ return price_format(val,2,false);}},true,function(obj,v){
                    if(v.key == 1)obj.find('.key').addClass('fri').html('');
                    if(v.key == 2)obj.find('.key').addClass('two').html('');
                    if(v.key == 3)obj.find('.key').addClass('tre').html('');
                });
            });
            $(".invest-month").bind('click',function(){
                each_html(invest_month_html,'/index.php/home/ajax_get_user_invest_total_list',{'type':'month'},{'invest_total':function(val){ return price_format(val,2,false);}},true,function(obj,v){
                    if(v.key == 1)obj.find('.key').addClass('fri').html('');
                    if(v.key == 2)obj.find('.key').addClass('two').html('');
                    if(v.key == 3)obj.find('.key').addClass('tre').html('');
                });
            });
            //上下部公告
            each_html('announcement-top','/index.php/about/ajax_get_news',{'page_id':1,'page_size':5,'category':'<?php echo item('announcement_home_top_cat_id')?item('announcement_home_top_cat_id'):0; ?>'},'',true,function(obj,v){
                obj.find('a').attr('href','<?php echo site_url('about/news_detail?id='); ?>'+ v.id);
            },function(){gg_pad();});
            each_html('announcement-down','/index.php/about/ajax_get_news',{'page_id':1,'page_size':5,'category':'<?php echo item('announcement_home_bottom_cat_id')?item('announcement_home_bottom_cat_id'):0; ?>'},'',true,function(obj,v){
                obj.find('a').attr('href','<?php echo site_url('about/news_detail?id='); ?>'+ v.id);
            });
            //banner
            each_html('js_banner','/index.php/about/ajax_get_news',{'page_id':1,'page_size':5,'category':'<?php echo item('banner_home_cat_id')?item('banner_home_cat_id'):0; ?>'},'',true,function(obj,v){
                    obj.find('.bannerbgdiv').css({'background':'url('+v.source+') center no-repeat'});
                },function(){
                $('#js_banner').slider({
                    height: 400,
                    start: 1,
                    navigation: {active: false},
                    play: { active: false,auto: true,restartDelay: 3000}
                });
            });
		});
	});
</script>
</body>
</html>