<!DOCTYPE html>
<html>
<head lang="en">
    <title>聚雪球</title>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <!--H5页面窗口自动调整到设备宽度，并禁止用户缩放页面-->
    <meta name="viewport"
          content="width=device-width,initial-scale=1.0,minimum-scale=1.0,maximum-scale=1.0,user-scalable=no"/>
    <!-- 忽略将页面中的数字识别为电话号码 -->
    <meta name="format-detection" content="telephone=no"/>
    <!-- 忽略Android平台中对邮箱地址的识别 -->
    <meta name="format-detection" content="email=no"/>
    <!-- 当网站添加到主屏幕快速启动方式，可隐藏地址栏，仅针对ios的safari -->
    <!-- ios7.0版本以后，safari上已看不到效果 -->
    <meta name="apple-mobile-web-app-capable" content="yes"/>
    <!-- 将网站添加到主屏幕快速启动方式，仅针对ios的safari顶端状态条的样式 -->
    <!-- 可选default、black、black-translucent -->
    <meta name="apple-mobile-web-app-status-bar-style" content="black"/>
    <!-- winphone系统a、input标签被点击时产生的半透明灰色背景怎么去掉 -->
    <meta name="msapplication-tap-highlight" content="no">
    <link rel="stylesheet" type="text/css" href="/assets/js/app/flexslide/css/flexslider-m.css">
    <link rel="stylesheet" type="text/css" href="/assets/css/app/radialindicator.css">
    <link rel="stylesheet" type="text/css" href="/assets/css/app/head.css">
    <link rel="stylesheet" type="text/css" href="/assets/css/app/m-common.css">
    <link rel="stylesheet" type="text/css" href="/assets/css/app/m-home.css">
</head>
<style type="text/css">
    .slides li {
        width: 320px;
        float: left;
        display: block;
    }
</style>
<body>
<!-- 公共头部导航-->
<?php $this->load->view('common/mobiles/app_common_head') ?>
<div class="placehold"></div>
<div class="con_wap">
    <!-- banner -->
    <div class="banner row">
        <div class="flexslider" id="homeslider">
            <div class="flex-viewport" style="overflow: hidden; position: relative;">
                <ul id="index-slider" class="list-unstyled slides"
                    style="visibility: hidden; width: 1000%; -webkit-transition-duration: 0.6s; transition-duration: 0.6s; -webkit-transform: translate3d(-960px, 0px, 0px); transform: translate3d(-960px, 0px, 0px);">
                    <li>
                        <a href="javascript:void(0);"><img src="" class="img-responsive source" alt="" draggable="false"></a>
                    </li>
                    <!-- 950*500 -->
                </ul>
            </div>
        </div>
    </div>
    <!-- banner end-->
    <div class="row" style="background:#fff; position: relative;">
        <img style=" border-bottom:1px solid #eeeeee;" src="../../../assets/images/app/201212191111.png" width="100%;">
        <div class="row" style="position: absolute; left:0; top:0; width:100%; height:100%;">
            <div style=" width:50%; height:100%; float:left;">
                <p style="font-size:1.6rem; color:#333; line-height:2rem; padding-top:1rem; text-indent:37%;">累计注册(人)</p>
                <p style="font-size:1.4rem; color:#da251c; line-height:2rem; text-indent:37%;"><?php echo rate_format(price_format($total['user_total'],2,false)); ?></p>
            </div>
            <div style=" width:50%; height:100%; float:left;">
                <p style="font-size:1.6rem; color:#333; line-height:2rem; padding-top:1rem; text-indent:35%;">累计投资(元)</p>
                <p style="font-size:1.4rem; color:#da251c; line-height:2rem; text-indent:35%;"><?php echo rate_format(price_format($total['invest_total'],2,false)); ?></p>
            </div>
        </div>
    </div>
    <div class="row" style="font-size:1.6rem; color:#333; line-height:4rem; text-align:center; margin-bottom:1rem; background:#fff;">
        <?php echo rate_format(price_format($total['risk_total'],2,false)); ?>元风险备用金保障您的资金安全
    </div>
    <!-- 选项卡标题 -->
    <div class="row row-1">
        <ul class="index_tab_title clearfix" id="months">
            <li class="current" data-month=""><a href="javascript:void (0);">全部</a></li>
            <li data-month="0-0.9"><a href="javascript:void (0);">0.9个月</a></li>
            <li data-month="3-3"><a href="javascript:void (0);">3个月</a></li>
            <li data-month="3-12"><a href="javascript:void (0);">3-12个月</a></li>
        </ul>
    </div>
    <!-- 选项卡标题  end-->
    <!-- 选项内容 -->
    <div class="index_tab_nr" id="list" style="visibility: hidden">
        <div class="index_info">
                <span class="tap-span" onclick="">
                    <div class="left-red"></div>
                    <div class="table">
                        <div class="tr">
                            <div class="td td-2">
                                <span style="width: 30%;display:block;overflow: hidden;float:left;"><strong
                                        class="c_red f18 vm rate"></strong><i>%</i></span>
                                <span style="width: 33%;display:block;overflow: hidden;float:left;"><em
                                        class="f18 vm months"></em><i>个月</i></span>
                                <span style="width: 37%;display:block;overflow: hidden;float:left;"><em
                                        class="f18 vm amount"></em><i>万元</i></span>
                            </div>
                            <div class="td td-1">
                                <div class="bfb   bfb80 r_rate" style="float:right" data-bfb="0"></div>
                                <!-- data-bfb 为百分比数字 -->
                            </div>
                        </div>
                    </div>
                    <div class="clears line"></div>
                    <div class="pd10">
                        <span class="vm category" style="display:block;width:22%;float:left;overflow: hidden;"></span>
                        <span class="vm subject"
                              style="display:block;width:43%;float:left;overflow: hidden;margin-left: 3%"></span>
                        <span class="vm mode"
                              style="display:block;width:32%;float:left;overflow: hidden;text-align: right;">●<i
                                class="dots_green"></i></span>
                    </div>
                </span>
        </div>
    </div>
    <!-- 选项内容 end-->
</div>
<?php $this->load->view('common/mobiles/app_alert') ?>
</body>
<?php $this->load->view('common/mobiles/app_footer') ?>
<script>
    $(function () {
        $("#index-slider").list_data({
            data:'/index.php/mobiles/home/ajax_get_slider_list',
            btn : true,
            list_func : function(obj, v){
                if (v.link_url && v.link_url != '#')obj.find('a').attr('href', v.link_url);
            },
            callback : function(){
                gdFun("#homeslider");
                $("#index-slider .clone").css('opacity',1);
            }
        });
        $("#list").list_data({
            data : '/index.php/mobiles/home/get_project_list',
            page_size : 10,
            event_type:'scroll',
            value_func : {
                'amount': function (price) {return rate_format(price_format(price, 3, false));},
                'mode': function (mode) {return '<i class="dots_green"></i>' + mode;}
            },
            list_func : function(obj,v){
                switch (parseInt(v.new_status)){
                    case 2:
                        obj.find('.r_rate').attr('data-bfb', v.receive_rate);
                        break;
                    case 4:
                        obj.find('.r_rate').removeClass('bfb').addClass('index_huankuan').html('<span>'+ v.status_name+'</span>');
                        break;
                    default:
                        obj.find('.r_rate').removeClass('bfb').addClass('index_wancheng').html('<span>'+ v.status_name+'</span>');
                }
                obj.find('.tap-span').attr('onclick', 'window.location.href="/index.php/mobiles/home/project_detail?borrow_no='+v.borrow_no+'"')
            },
            callback : function(){$("canvas").remove();bfbFun();}
        },function(ls_fun){
            $("#months li").on('tap', function () {
                $("#months li").removeClass('current');
                var month = $(this).data('month') || '';
                $(this).addClass('current');
                ls_fun('/index.php/mobiles/home/get_project_list?m='+month);
            });
        });
    });
</script>
</html>