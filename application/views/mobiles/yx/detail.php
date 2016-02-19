<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?php echo $title; ?></title>
    <meta charset="utf-8">
    <meta name="description" content="<?php echo $title; ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <!--H5页面窗口自动调整到设备宽度，并禁止用户缩放页面-->
    <meta name="viewport" content="width=device-width,initial-scale=1.0,minimum-scale=1.0,maximum-scale=1.0,user-scalable=no">
    <!-- 忽略将页面中的数字识别为电话号码 -->
    <meta name="format-detection" content="telephone=no">
    <!-- 忽略Android平台中对邮箱地址的识别 -->
    <meta name="format-detection" content="email=no">
    <!-- 当网站添加到主屏幕快速启动方式，可隐藏地址栏，仅针对ios的safari -->
    <!-- ios7.0版本以后，safari上已看不到效果 -->
    <meta name="apple-mobile-web-app-capable" content="yes">
    <!-- 将网站添加到主屏幕快速启动方式，仅针对ios的safari顶端状态条的样式 -->
    <!-- 可选default、black、black-translucent -->
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <!-- winphone系统a、input标签被点击时产生的半透明灰色背景怎么去掉 -->
    <meta name="msapplication-tap-highlight" content="no">
    <link rel="stylesheet" href="/assets/activity_wish/css/index.css">
    <style>
        .lazy{position: fixed; left: 0; top: 0; width: 100%;height: 100%;background:#fff;z-index: 99999;}
    </style>
</head>
<body style="background:#fff;">
    <div class="lazy">
        <div class="ajx_nr">
            <div class="ajx_logo">
                <div class="ajx_tu">
                    <div class="ajx_quan"></div>
                    <div class="four_one"></div>
                </div>
                <div class="ajx_j">J</div>
            </div>
            <div class="ajx_wz">处理中...</div>
        </div>
    </div>

    <div><img src="/assets/activity_wish/images/14.jpg" alt="" width="100%"></div>

    <div class="syjl">
        <h2>-助力记录-</h2>
        <ul class="help-log" style="visibility: hidden;">
            <li>
                <div class="le">
                   <div class="tx">
                        <!--放头像的地方-->
                        <img class="weixin_avatar" src="" alt="" width="100%">
                   </div>
                </div>
                <div class="ri">
                    <p class="nr weixin_name"></p>
                </div>
            </li>
        </ul>
    </div>
    <button class="wyl" onclick="window.location.href='/index.php/mobiles/yx/index'">我也要请客</button>
</body>
<script src="/assets/activity_wish/js/jquery-1.8.5.min.js"></script>
<script src="/assets/js/app/jquery.list_data.1.2.js"></script>
<script src="/assets/js/app/wx-1.js"></script>
    <script>
        $('.close').click(function(){
            $('.pop').fadeOut();
        });
        $(function(){
            window.onload = function(){
                setTimeout(function(){$('.lazy .ajx_nr').hide(1);$('.lazy').hide(2);},500);
            };

            ajax_loading_style(2,1);
            $(".help-log").list_data({
                data:'/index.php/mobiles/yx/ajax_get_help_log',
                param:{wish_id:'<?php echo $wish['wish_id']; ?>'},
                page_size:8,
                btn:true,
                callback:function(list_func){
                    //nodata  nomored 上一页 下一页
                }
            });
            wx_share.conf.img = 'https://www.juxueqiu.com//assets/activity_wish/images/14.jpg';
            wx_share.conf.ticket = '/index.php/mobiles/yx/ajax_get_ticket';
            wx_share.conf.decription = '谢谢谢谢谢谢';
            wx_share.share({
                trigger:function(){},
                success:function(){},
                cancle:function(){}
            })
        });
    </script>
</html>