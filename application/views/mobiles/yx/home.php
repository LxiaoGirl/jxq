<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="renderer" content="webkit">
    <meta http-equiv="Cache-Control" content="no-siteapp">
    <meta name="viewport" content="initial-scale=1, maximum-scale=3, minimum-scale=1, user-scalable=no">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <meta name="format-detection" content="telephone=no">
    <link rel="stylesheet" type="text/css" href="/assets/activity_wish/active_yx/css/index.css">
    <title>阖家团圆</title>
</head>
<body>
    <!--懒加载-->
    <div class="lazy">
        <div class="ajx_nr ajx_nr_1">
            <div class="ajx_logo">
                <div class="ajx_tu">
                    <div class="ajx_quan"></div>
                    <div class="four_one"></div>
                </div>
                <div class="ajx_j">J</div>
            </div>
            <div class="ajx_wz">处理中...</div>
        </div>
        <!--有的页面要底部-->
        <div class="footer tc">
            <span class="ft_sp1 active"></span><span class="ft_sp2"></span><span class="ft_sp3"></span><span class="ft_sp4"></span>
        </div>
        <!--有的页面要底部-->
    </div>
    <!--懒加载-->

    <div class="body">
        <div class="con">
            <img src="/assets/activity_wish/active_yx/images/14.jpg" width="100%" alt="">
        </div>
        <div class="con">
            <a href="javascript:void(0);"><img src="/assets/activity_wish/active_yx/images/15.jpg" width="100%" alt="" id="set-wish"></a>
        </div>
        <div class="con">
            <img src="/assets/activity_wish/active_yx/images/16.jpg" width="100%" alt="">
        </div>
        <div class="con">
            <img src="/assets/activity_wish/active_yx/images/17.jpg" width="100%" alt="">
        </div>
        <div class="con">
            <img src="/assets/activity_wish/active_yx/images/171.jpg" width="100%" alt="">
        </div>
        <div class="con conbj conbj1">
            <p>凡是参与本次活动的朋友们，皆可有机会获得幸运奖！（随机抽取产生）。排行榜前10名用户，在2月23日-3月23日期间内投资聚稳盈产品即可获得劲爆大奖（年化3%加息红包，售完为止先到先得哟）。</p>
            <p>1.小伙伴们可通过：</p>
            <p>a) “聚雪球”微信公众号中的【阖家团圆】直达活动页面；</p>
            <p>b) 在聚雪球官网的活动页扫【阖家团圆】二维码直达活动页面；</p>
            <p>c) 在朋友分享的【阖家团圆】页面内点“我也要玩”直达活动页面。</p>
            <p>2.进入活动页面后，小伙伴们只需点击“领取团圆饭”来参与活动。</p>
            <p>3.进入【阖家团圆】页面，分享到微信朋友圈邀请好友吃团圆饭，即可获得丰厚现金红包大奖，邀请人数越多，奖品越丰厚呦！数量有限，先到先得！(桌数不限，邀请人数不限)</p>
            <p>4、一、二、三等奖及劲爆大奖按桌数排名获得(一等奖：排名前5名；二等奖：排名6-15名；三等奖：排名16-35名；劲爆大奖：排名前10名）。</p>
            <p>5.排名在35名以后的小伙伴都有机会获得幸运奖，奖品为随机红包。</p>
        </div>
        <div class="con">
            <img src="/assets/activity_wish/active_yx/images/19.jpg" width="100%" alt="">
        </div>
    </div>
    <div class="pop">
        <div class="popnr">
            <img src="/assets/activity_wish/active_yx/images/11.png" alt="" width="100%">
            <div class="pab"></div>
            <div class="popnr_nr">
                <p>小伙伴们可通过：</p>
                <p>a) “聚雪球”微信公众号中的【阖家团圆】直达活动页面；</p>
                <p>b) 在聚雪球官网的活动页扫【阖家团圆】二维码直达活动页面；</p>
                <p>c) 在朋友分享的【阖家团圆】页面内点“我也要玩”直达活动页面。</p>
            </div>
            <img src="/assets/activity_wish/active_yx/images/13.png" style="margin-top:-1px;" width="100%" alt="">
        </div>
    </div>
</body>
<script src="/assets/activity_wish/active_yx/js/jquery-2.1.1.min.js"></script>
<script src="/assets/activity_wish/active_yx/js/sys.js"></script>
<script>
jQuery(function($) {
    $('.but').click(function(e){
        $('.pop').fadeIn()
    });
    $('.pop').click(function(){
        $(".pop").fadeOut();
    });

    //---------领取团年饭处理-------------------------
    $("#set-wish").click(function(){
        if('<?php echo $start; ?>' == 'N'){
            sys_alert('活动尚未开始!');return;
        }else{
            _ajax_lo();
            $.ajax({
                url:'/index.php/mobiles/yx/ajax_set_wish',
                type:'post',
                dataType:'json',
                error:function(){
                    var tt1 = setTimeout(function(){
                        clearTimeout(tt1);
                        _ajax_cg();
                        sys_alert('通信异常,请检查网络或关闭页面稍后重试!');
                    },500);
                },
                success:function(rs){
                    //加了500毫秒延迟 保证加载动画
                    var tt1 = setTimeout(function(){
                        clearTimeout(tt1);
                        var wish = rs.data;
                        if(rs.status == '10000'){
                            window.location.replace('/index.php/mobiles/yx/detail?wish_id='+wish['wish_id']);
                        }else{
                            _ajax_cg();
                            sys_alert(rs.msg);
                        }
                    },500);
                }
            });
        }
    });
})
</script>
</html>