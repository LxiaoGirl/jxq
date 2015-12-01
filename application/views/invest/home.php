<!DOCTYPE html>
<html>
<head>
    <title>聚雪球_网加金服_P2P理财首选互联网金融P2P网贷平台_100元即可投资!</title>
	<?php $this->load->view('common/head_file'); ?>
</head>
<body>

<?php $this->load->view('common/head'); ?>

<!--投资首页-->
<div class="invest_home row">
    <!--面包屑导航-->
    <p class="invest_nav"><a href="<?php echo site_url(); ?>"> 首页 </a>> <a href="<?php echo site_url('invest/index?c='.$category); ?>">我要投资</a></p>
    <!--面包屑导航-->
    <div class="invest_home_con">
        <!--左侧-->
        <div class="invest_home_left fl">
            <!--TAB头 标的名-->
            <ul class="invest_home_title tab_title">
	            <?php if($category_list):foreach($category_list as $k=>$v): ?>
		            <li onclick="window.location.href='<?php echo site_url('invest/index?c='.$v['cat_id']) ?>'"
			            class=" <?php if($v['cat_id'] == $category): ?>active<?php endif; ?>
		            <?php if($v['cat_id'] == 1):echo 'cdb';elseif($v['cat_id'] == 2):echo 'jnd';elseif($v['cat_id'] == 3):echo 'jhlc';elseif($v['cat_id'] == 4):echo 'xsb';endif; ?>">
			            <?php echo $v['category']; ?><font class="fr">|</font>
		            </li>
	            <?php endforeach;endif; ?>
                <li onclick="window.location.href='<?php echo site_url('invest/index?c=5') ?>'" class="jbb <?php if($category == 5): ?>active<?php endif; ?>" ><em>宝</em>聚保宝</li>
            </ul>
            <!--TAB头 标的名-->
            <!--公告-->
            <div class="invest_home_gg">
                <div class="row">
                    <div class="prepd40">
                        <div class="invest_home_gg_con" id="announcement-top">
                            <p><a href="" class="link_url">【最新动态】<span class="title"></span></a></p>
                        </div>
                    </div>
                </div>
            </div>
            <!--公告-->
            <!--标的主体-->
            <ul class="invest_home_body tab_con">
                <li class="active li">
                    <div class="invest_home_screening">
                        <p>年化收益率：
	                        <font <?php if($rate == ''): ?>class="selected"<?php endif; ?> onclick="window.location.href='<?php echo site_url('invest/index?c='.$category.'&r=0&m='.$months) ?>'">全部</font>
	                        <font <?php if($rate == '0-9'): ?>class="selected"<?php endif; ?> onclick="window.location.href='<?php echo site_url('invest/index?c='.$category.'&r=0-9&m='.$months) ?>'">9%以下</font>
	                        <font <?php if($rate == '9-10'): ?>class="selected"<?php endif; ?> onclick="window.location.href='<?php echo site_url('invest/index?c='.$category.'&r=9-10&m='.$months) ?>'">9%-10%</font>
	                        <font <?php if($rate == '10-11'): ?>class="selected"<?php endif; ?> onclick="window.location.href='<?php echo site_url('invest/index?c='.$category.'&r=10-11&m='.$months) ?>'">10%-11%</font>
	                        <font <?php if($rate == '11-12'): ?>class="selected"<?php endif; ?> onclick="window.location.href='<?php echo site_url('invest/index?c='.$category.'&r=11-12&m='.$months) ?>'">11%-12%</font>
	                        <font <?php if($rate == '12-100'): ?>class="selected"<?php endif; ?> onclick="window.location.href='<?php echo site_url('invest/index?c='.$category.'&r=12-100&m='.$months) ?>'">12%以上</font>
                        </p>
                        <p>&nbsp;&nbsp;&nbsp;借款期限：
	                        <font <?php if($months == ''): ?>class="selected"<?php endif; ?> onclick="window.location.href='<?php echo site_url('invest/index?c='.$category.'&r='.$rate.'&m=0') ?>'">全部</font>
	                        <font <?php if($months == '0-3'): ?>class="selected"<?php endif; ?> onclick="window.location.href='<?php echo site_url('invest/index?c='.$category.'&r='.$rate.'&m=0-3') ?>'">0-3个月</font>
	                        <font <?php if($months == '3-6'): ?>class="selected"<?php endif; ?> onclick="window.location.href='<?php echo site_url('invest/index?c='.$category.'&r='.$rate.'&m=3-6') ?>'">3-6个月</font>
	                        <font <?php if($months == '6-9'): ?>class="selected"<?php endif; ?> onclick="window.location.href='<?php echo site_url('invest/index?c='.$category.'&r='.$rate.'&m=6-9') ?>'">6-9个月</font>
	                        <font <?php if($months == '9-12'): ?>class="selected"<?php endif; ?> onclick="window.location.href='<?php echo site_url('invest/index?c='.$category.'&r='.$rate.'&m=9-12') ?>'">9-12个月</font>
	                        <font <?php if($months == '12-15'): ?>class="selected"<?php endif; ?> onclick="window.location.href='<?php echo site_url('invest/index?c='.$category.'&r='.$rate.'&m=12-15') ?>'">12-15个月</font>
	                        <font <?php if($months == '15-100'): ?>class="selected"<?php endif; ?> onclick="window.location.href='<?php echo site_url('invest/index?c='.$category.'&r='.$rate.'&m=15-100') ?>'">15个月以上</font>
                        </p>
                    </div>

                    <div class="invest_home_product">
                        <?php if($category == 5): ?>
                        <div class="product_of_invest_home product_of_invest_home_1">
                            <div class="fl yi zi">
                                <span>益</span>
                                <p>聚益盈</p>
                            </div>
                            <div class="lv fl">
                                <p class="yqb">预计年化收益率</p>
                                13.2<font>%</font>
                            </div>
                            <div class="qtje fl">
                                <p class="yqb">起投金额</p>
                                100元
                            </div>
                            <div class="bzfs fl">
                                <p class="yqb">保障方式</p>
                                本息保障
                            </div>
                            <div class="fr jbb_an">
                                <p>累计投资：1,203,264元</p>
                                <p class='mar0'>累计入团：152,211人次</p>
                                <a class="jb">立即投资</a>
                            </div>
                        </div>
                        <div class="product_of_invest_home product_of_invest_home_1">
                            <div class="fl wen zi">
                                <span>稳</span>
                                <p>聚稳盈</p>
                            </div>
                            <div class="lv fl">
                                <p class="yqb">预计年化收益率</p>
                                13.2<font>%</font>
                            </div>
                            <div class="qtje fl">
                                <p class="yqb">起投金额</p>
                                100元
                            </div>
                            <div class="bzfs fl">
                                <p class="yqb">保障方式</p>
                                本息保障
                            </div>
                            <div class="fr jbb_an">
                                <p>累计投资：1,203,264元</p>
                                <p class='mar0'>累计入团：152,211人次</p>
                                <a class="jb sq">已售罄</a>
                            </div>
                        </div>
                        <div class="product_of_invest_home product_of_invest_home_1">
                            <div class="fl fu zi">
                                <span>富</span>
                                <p>聚富盈</p>
                            </div>
                            <div class="lv fl">
                                <p class="yqb">预计年化收益率</p>
                                13.2<font>%</font>
                            </div>
                            <div class="qtje fl">
                                <p class="yqb">起投金额</p>
                                100元
                            </div>
                            <div class="bzfs fl">
                                <p class="yqb">保障方式</p>
                                本息保障
                            </div>
                            <div class="fr jbb_an">
                                <p>累计投资：1,203,264元</p>
                                <p class='mar0'>累计入团：152,211人次</p>
                                <a class="yy">立即预约</a>
                            </div>
                        </div>
                        <?php else: ?>

	                    <?php if($project):foreach($project as $k=>$v): ?>

                        <!--biao-->
                        <div class="product_of_invest_home">
                            <h1>
	                            <font>
                                    <?php if($v['category'] == '车贷宝'): ?>
                                        车
                                    <?php elseif($v['category'] == '聚农贷'): ?>
                                        农
                                    <?php elseif($v['category'] == '聚惠理财'): ?>
                                        惠
                                    <?php else: ?>
                                        不知
                                    <?php endif; ?>
                                </font>
	                            <a href="<?php echo site_url('invest/detail?borrow_no='.$v['borrow_no']); ?>"> <?php echo $v['subject']; ?></a>
	                            <b>（编号：<?php echo $v['borrow_no']; ?>）</b>
	                            <span class="bao">
		                            <em><?php echo type_name_2($v['type']); ?></em>
		                            <font><?php echo $v['company_name']; ?></font>
	                            </span>
<!--	                            <span class="zhi"><em>A</em><font>支持自动投资</font></span>-->
<!--	                            <span class="jia"><em>加</em><font>+0.9%</font></span>-->
                            </h1>
                            <div class="product_of_invest_home_top">
                                <p class="fl"><?php echo mb_substr($v['summary'],0,20).'...'; ?> </p>
                                <p class="fr sy_jdt" jdt="<?php echo $v['receive_rate']; ?>">
                                    <font>投资进度：</font><span><i></i></span><font style="color:#3cb5ec;"><?php echo $v['receive_rate']; ?>%</font>
                                </p>
                            </div>                            
                            <div class="bot">
                                <div class="fl">
                                    <ul>
                                        <li>
                                            <div class="product_of_invest_home_num_bot tc">年化收益率</div>
                                            <div class="product_of_invest_num_bot tc col_blu"><?php echo $v['rate']; ?><i>%</i></div>
                                        </li>
                                        <li>
                                            <div class="product_of_invest_home_num_bot tc">借款期限(月)</div>
                                            <div class="product_of_invest_num_bot tc"><?php echo $v['months']; ?></div>
                                        </li>
                                        <li>
                                            <div class="product_of_invest_home_num_bot tc">借款总额(万元)</div>
                                            <div class="product_of_invest_num_bot tc"><?php echo price_format($v['amount'],3,false); ?></div>
                                        </li>
                                        <li>
                                            <div class="product_of_invest_home_num_bot tc">起投金额(元)</div>
                                            <div class="product_of_invest_num_bot tc"><?php echo rate_format($v['lowest']); ?></div>
                                        </li>
                                        <li>
                                            <div class="product_of_invest_home_num_bot tc">还款方式</div>
                                            <div class="product_of_invest_num_bot tc hanzi"><?php echo $v['mode']; ?></div>
                                        </li>
                                    </ul>
                                </div>
                                <div class="fr tc">
                                    <?php switch($v['new_status']){
                                        case '1':
                                            echo '<h5
                                            class="settime"
                                            data-start-time="'.$v['buy_time'].'"
                                            data-end-time="'.$v['due_date'].'"
                                            data-amount="'.rate_format(price_format($v['amount']-$v['receive'],2,false)).'"
                                            data-borrow_no="'.$v['borrow_no'].'">
                                            <span>距开标 还剩<span class="d">00</span>天<span class="h">00</span>小时</span>
                                            </h5>';
                                            echo '<a class="invest-button jjksbut" data-status="'.$v['new_status'].'" href="'.site_url('invest/detail?borrow_no='.$v['borrow_no']).'">即将开始</a>';
                                            break;
                                        case '2':
                                            echo '<h5>可投金额：'.rate_format(price_format($v['amount']-$v['receive'],2,false)).'元</h5><a href="'.site_url('invest/detail?borrow_no='.$v['borrow_no']).'">热售中</a>';
                                            break;
                                        case '3':
                                            echo '<h5></h5>';
                                            echo '<a href="'.site_url('invest/detail?borrow_no='.$v['borrow_no']).'" class="ymbbut">已售罄</a>';
                                            break;
                                        case '4':
                                            echo '<h5></h5>';
                                            echo '<a href="'.site_url('invest/detail?borrow_no='.$v['borrow_no']).'" class="ymbbut">回款中</a>';
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
                                </div>
                            </div>
                        </div>
	                    <?php endforeach;else: ?>
	                    <div class="product_of_invest_home"><p style="text-align: center;">暂无相关信息</p></div>
	                    <?php endif; ?>

                        <?php endif; ?>
                    </div>
                    <!--分页-->
	                <?php echo $links; ?>
                </li>
            </ul>
            <!--标的主体-->
        </div>
        <!--左侧-->
        <!--右侧-->
        <div class="invest_home_right fr">
            <div class="tzxts">
                <h1>投资小贴士</h1>
                <div id="tips">
                    <p class="fs14colblue">Q：<span class="title"></span></p>
                    <p>A：<span class="content"></span></p>
                </div>
            </div>
        </div>
        <!--右侧-->
    </div>
</div>
<!--投资首页-->

<?php $this->load->view('common/footer'); ?>

<script type="text/javascript">
    seajs.use(['jquery','sys'],function(){
        $(function(){
            each_html('announcement-top','/index.php/about/ajax_get_news',{'page_id':1,'page_size':5,'category':'<?php echo item('announcement_home_top_cat_id')?item('announcement_home_top_cat_id'):0; ?>'},'',true,function(obj,v){
                obj.find('a').attr('href','<?php echo site_url('about/news_detail?id='); ?>'+ v.id);
            },function(){gg_pad_1();});
            //tab_1($(".invest_home_left"));
            //未开始的倒计时
            if($('.settime').length) {
                $('.settime').count_down(function (obj) {
                    obj.html('可投金额：<font>' + obj.attr('data-amount') + '</font>元').siblings('a').removeClass('jjksbut').attr('href', '<?php echo site_url('invest/detail?borrow_no='); ?>' + obj.attr('data-borrow_no')).html('马上投资').attr('data-status', 2);
                },'',function(flag,e,d,h,m,s){
                    if(d == 0 && h == 0){
                        e.find('span').html('距开标 还剩<span class="m">'+m+'</span>分<span class="s">'+s+'</span>秒');
                    }else{
                        e.find('.h').text(h);
                        e.find('.d').text(d);
                    }
                });
            }

            //小贴士
            each_html('tips','/index.php/about/ajax_get_news',{'page_id':1,'page_size':5,'category':'<?php echo item('invest_home_tips_cat_id')?item('invest_home_tips_cat_id'):0; ?>'},'',true,'','',true);
        });
    });
</script>
</body>
</html>