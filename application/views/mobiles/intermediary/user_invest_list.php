<!DOCTYPE html>
<html>
<head lang="en">
  <title>投资明细</title>
    <?php $this->load->view('common/mobiles/m_app_head') ?>
    <link rel="stylesheet" type="text/css" href="/assets/css/app/m-popularize.css">
</head>
<body>
<!-- 公共头部导航-->
    <?php $this->load->view('common/mobiles/app_common_head') ?>
    <div class="placehold"></div>
    <div class="con_wap">
        <div class="invest_top">
            <div class="left_invest_top">
                <div class="touxiang"><img src="<?php echo $user['avatar']?assets($user['avatar']):'/assets/images/app/mrtx.png'; ?>"/></div>
            </div>
            <div class="rig_invest_top">
                <p class="mobile_self" style="margin-left: 5%;"><?php echo $user['user_name']; ?><font><?php echo $user['mobile']; ?></font></p>
                <p class="mobile_self" style="margin-top:1rem;">投资总额</p>
                <p class="invest_nub"><font>¥</font><?php echo price_format($all_invest,0,false); ?></p>
            </div>
        </div>
        <div class="invest_cen">
            <p class="title_top"><font class="frist" style="width:29%">时间</font><font style="width:45%">项目</font><font class="last" style="width:25%">状态</font></p>
            <div id="list" style="visibility: hidden;">
                <div class="nr"><div class="frist dateline">0</div><div class="subject" style="white-space: nowrap;"><span class="blue subject_1">车贷宝</span></br><span class="red invest_amount">0.00</span></div><div class="last r_rate">复审中</div></div>
            </div>
        </div>
    </div>
    <a href="<?php echo site_url('mobiles/intermediary/share_weixin'); ?>"><img class="fixbot" style="display:bolck; float:left" src="/assets/images/app/rejujianren.jpg" width="100%"></a>
    <?php $this->load->view('common/mobiles/app_alert') ?>
</body>
  <?php $this->load->view('common/mobiles/app_footer') ?>
<script type="text/javascript">
    var page_id= 1,page_size=15;
    $(document).ready(function(){
        $(".tcnav.fl").click(function (){
            $(".tab-ctorl").addClass("tcnav1");
            $(".tab-ctorl").removeClass("tcnav2");
            $(".tab-con1").show();
            $(".tab-con2").hide();
        })
        $(".tcnav.fr").click(function (){
            $(".tab-ctorl").addClass("tcnav2");
            $(".tab-ctorl").removeClass("tcnav1");
            $(".tab-con2").show();
            $(".tab-con1").hide();
        })
        var list_view = new wb_listview({
            'id':'list',
            'showLoading':true,
            'funcDeal':{
                'invest_amount':function(data){ return data?price_format(data,2,false):0;},
                'dateline':function(time){ return unixtime_style(time,'Y-m-d');}
            }
        });
        var get_data =function(){
            var condition='';
            condition+='?per_page='+((page_id-1)*page_size)+'&limit='+page_size;
            $(window).unbind('scroll');
            $.post('/index.php/mobiles/intermediary/user_invest_list'+condition,{'uid':'<?php echo $uid; ?>'},function(result){
                list_view.set_pageid(page_id);
                list_view.list(result.data,function(obj,v){
                    var now = Date.parse(new Date())/1000;
                    if(v.status == 2){
                        if(v.buy_time > now){
                            obj.find('.r_rate').html('未开始');
                        }else if(v.receive_rate == 100){
                            obj.find('.r_rate').html('复审中');
                        }else if(v.due_date < now){
                            obj.find('.r_rate').html('投标结束');
                        }else{
                            obj.find('.r_rate').html('融资中');
                        }
                    }else if(v.status == 4){
                        obj.find('.r_rate').html('还款中');
                    }else if(v.status == 7){
                        obj.find('.r_rate').html('交易结束');
                    }else{
                        obj.find('.r_rate').html(borrow_status(v.status));
                    }
                });
                if(result.data){
                    page_id++;
                    $(window).bind('scroll',function(){ scroll_fun(function(){get_data();}); });
                }
            },'json');
        };
        get_data();
    }); 
</script>
</html>