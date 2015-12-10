<!DOCTYPE html>
<html>
<head lang="en">
  <title>居间人</title>
    <?php $this->load->view('common/mobiles/m_app_head') ?>
    <link rel="stylesheet" type="text/css" href="/assets/css/app/m-popularize.css">
</head>
<body>
<!-- 公共头部导航-->
    <?php $this->load->view('common/mobiles/app_common_head') ?>
    <div class="placehold"></div>
    <div class="con_wap">
        
       <div class="popularize_center_top">
            <p class="ft28 tl">总收益(元)：</p>
            <p class="ft70 tr"><?php echo price_format($my_interest,0,false); ?><font>元</font></p>
       </div>
       <div class="popularize_center_tab">
            <div class="tab-ctorl tcnav1">
                <div class="tcnav tcnav2 fl tc">我的收益</div>
                <div class="tcnav tcnav1 fr tc">客户</div>
            </div>
            <div class="tab-con tab-con2">
                <p class="tc title"><font style="width:22%;">用户名</font><font style="width:44%;">累计投资(万元)</font><font style="width:33%; border:none">最近登录</font></p>
                <div id="my-user" style="visibility: hidden;">
                    <div class="tc nr">
                        <div class="user_name"></span></div>
                        <div class="amount"></div>
                        <div class="active_level"></div>
                    </div>
                </div>  
            </div>
            <div class="tab-con tab-con1">
                <p class="tc title"><font style="width:22%;">月份</font><font style="width:44%;">金额</font><font style="width:33%; border:none">结算日</font></p>
                <div id="my-income" style="visibility: hidden;">
                    <div class="tc nr" onclick="">
                        <div class="settle_time real_month"></div>
                        <div class="amounts amount"></div>
                        <div class="type pay_time"></div>
                    </div>
                </div>
            </div>
       </div>
    <a href="<?php echo site_url('mobiles/intermediary/share_weixin'); ?>"><img class="fixbot left100" style="display:bolck; float:left" src="/assets/images/app/rejujianren.jpg" width="100%"></a>
    </div>
    <?php $this->load->view('common/mobiles/app_alert') ?>
</body>
  <?php $this->load->view('common/mobiles/app_footer') ?>
<script type="text/javascript">
    var page_id= 1,page_size=15;
    var page_id1= 1,page_size1=15;
    $(document).ready(function(){
        var list_view = new wb_listview({
            'id':'my-user',
            'showLoading':true,
            'funcDeal':{
                'amount':function(data){ return data?price_format(data,3,false):0;}
            }
        });
        var list_view1 = new wb_listview({
            'id':'my-income',
            'showLoading':true,
            'funcDeal':{
                'start_time':function(time){ return unixtime_style(time,'Y-m');}
            }
        });
        var get_data =function(){
            var condition='';
            condition+='?type=user&per_page='+((page_id-1)*page_size)+'&limit='+page_size;
            $(window).unbind('scroll');
            $.post('/index.php/mobiles/intermediary/index'+condition,{},function(result){
                list_view.set_pageid(page_id);
                list_view.list(result.data,function(obj,v){
                    obj.find('.user_name').append('<br/><span>【 '+get_user_ralation(v.amount)+' 】</span>');
                    obj.find(':first').attr('onclick','window.location.href="<?php echo site_url("mobiles/intermediary/user_invest_list?uid=") ?>'+ v.uid+'"');
                    if(v.last_date == 0)obj.find('.active_level').html('- - -');
                });
                // if(result.data){
                //     page_id++;
                //     $(window).bind('scroll',function(){ scroll_fun(function(){get_data();}); });
                // }
            },'json');
        };
        var get_data1 =function(){
            var condition='';
            condition+='?type=income&per_page='+((page_id1-1)*page_size1)+'&limit='+page_size1;
            $(window).unbind('scroll');
            $.post('/index.php/mobiles/intermediary/index'+condition,{},function(result){
                list_view1.set_pageid(page_id1);
                list_view1.list(result.data,function(obj,v){
                    if(v.pay_time == 0)obj.find('.pay_time').html('- - -');
                    if(v.status == 1){
                        obj.find('.pay_time').append('</br><span>已结算</span>');
                    }else if(v.status == 2){
                        obj.find('.pay_time').append('</br><span>已失效</span>');
                    }else{
                        obj.find('.pay_time').append('</br><span>待结算</span>');
                    }
                    obj.find(':first').attr('onclick','window.location.href="<?php echo site_url("mobiles/intermediary/settle_invest_list?real_month=") ?>'+ v.real_month+'&amount='+v.amount+'"');
                });
                if(result.data){
                    page_id1++;
                    $(window).bind('scroll',function(){ scroll_fun(function(){get_data1();}); });
                }
            },'json');
        };
        get_data();
        get_data1();
        $(".tcnav.fl").click(function (){
            $(".tab-ctorl").addClass("tcnav1");
            $(".tab-ctorl").removeClass("tcnav2");
            $(".tab-con1").show();
            $(".tab-con2").hide();
        });
        $(".tcnav.fr").click(function (){
            $(".tab-ctorl").addClass("tcnav2");
            $(".tab-ctorl").removeClass("tcnav1");
            $(".tab-con2").show();
            $(".tab-con1").hide();
        });
    });

    var get_user_ralation = function(amount){
        var ralation = '--';
        if(amount>0&&amount<=10000){
            ralation = '过客';
        }else if(amount>10000&&amount<=100000){
            ralation = '朋友';
        }else if(amount>100000&&amount<=500000){
            ralation = '闺蜜';
        }else if (amount>500000&&amount<=5000000){
            ralation = '亲人';
        }else if(amount>5000000&&amount<=10000000){
            ralation = '挚友';
        }else if(amount>10000000&&amount<=50000000){
            ralation = '福将';
        }else if (amount>50000000){
            ralation = '财神';
        }
        return ralation;
    }
</script>
</html>