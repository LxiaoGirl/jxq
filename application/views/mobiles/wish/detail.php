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
</head>
<body style="background:#fff;">
    <div><img src="/assets/activity_wish/images/14.jpg" alt="" width="100%"></div>
    <div>
        <p class="tc sdzlj"><?php echo (mb_strlen($wish['real_name'])==4?mb_substr($wish['real_name'],0,2):mb_substr($wish['real_name'],0,1)).str_repeat('*',mb_strlen($wish['real_name'])==4?2:1); ?>的“
            <?php if($wish['wish_type'] == 1): ?>
                许愿助力金
            <?php else: ?>
                新年行大运
            <?php endif; ?>
            ”愿望
        </p>
        <?php if($wish['wish_type'] == 1): ?>
            <img src="/assets/activity_wish/images/12.png" width="100%">
        <?php else: ?>
            <img src="/assets/activity_wish/images/13.png" width="100%">
        <?php endif; ?>
        <p class="tc dqpm">当前排名第<?php echo $ranking; ?>位</p>
        <button class="bz wish-help" data-loading-msg="帮Ta实现愿望">帮Ta实现愿望</button>
    </div>
    <div class="syjl">
        <h2>-收益记录-</h2>
        <ul class="help-log">
            <li>
                <div class="le">
                   <div class="tx">
                        <!--放头像的地方-->
                        <img class="weixin_avatar" src="/assets/activity_wish/images/1.jpg" alt="" width="100%">
                   </div>
                </div>
                <div class="ri">
                    <p class="time add_time"></p>
                    <p class="nr description"></p>
                </div>
            </li>
            <p class="jzgd" id="more-button">查看更多>></p>
        </ul>
    </div>
    <button class="wyl" onclick="window.location.href='/index.php/mobiles/wish?inviter_no=<?php echo $wish['inviter_no']; ?>'">我要许愿</button>
    <div><img id="down-load" src="/assets/activity_wish/images/15.jpg" alt="" width="100%"></div>
    <div class="gywm">
        <p>聚雪球是由沈阳市供销社、国资委以及沈阳网加互联网金融服务有限公司共同出资创办的一家专业互联网金融服务平台。</p>
        <p>"聚雪球"平台将通过广泛运用移动支付、云计算、搜索引擎等互联网信息技术，对当前成熟的金融产品进行组合优化，有针对性地为投融资方搭建沟通桥梁，促进双方交易。同时充分发挥媒介作用，汇集各类金融机构的产品信息，搭建网络金融产品超市，丰富客户选择。充分发挥专业优势，以专业团队为依托，利用网络平台和大数据，为中小企业客户提供高品质、个性化金融服务方案。</p>
    </div>
    <div><img src="/assets/activity_wish/images/16.jpg" alt="" width="100%"></div>
    <!--红包选择-->
    <div class="zlpop pop">
        <div class="popcon">
            <div class="popzl">
                <em class="close"></em>
                <p class="popzlimg"><img src="/assets/activity_wish/images/18.png" alt=""><!--<img src="images/17.png" alt="">--></p>
                <p class="hs"></p>
                <button class="ding">再顶一次</button>
            </div>
        </div>
    </div>
    <!--红包选择-->
</body>
<script src="/assets/activity_wish/js/jquery-1.8.5.min.js"></script>
<script src="/assets/js/app/jquery.list_data.1.1.js"></script>
<script src="/assets/js/app/wx-1.js"></script>
    <script>

        $('.close').click(function(){
            $('.pop').fadeOut();
        });
        var ls_refresh;
        var inviter_no = '<?php echo $wish['inviter_no']; ?>';
        var wish_help = function(src){
            $.ajax({
                url:'/index.php/mobiles/wish/ajax_help',
                data:{wish_id:'<?php echo $wish['wish_id']; ?>'},
                dataType:'json',
                btn:src,
                type:'post',
                error:function(a,b,c){console.log(a+b+c)},
                success:function(rs){
                    $("#no-count").remove();
                    if(rs.status == '10000'){
                        $('.zlpop').find('.popzlimg>img').attr('src','/assets/activity_wish/images/18.png');
                        $('.zlpop').find('.hs').html(rs.msg);
                        var rs_data = rs.data;
                        if(rs_data.have_count > 0){
                            $('.zlpop').find('.ding').text('再顶一次').unbind('click').bind('click',function(){
                                wish_help(true);
                            });
                        }else{
                            $('.zlpop').find('.ding').text('我也要许愿').unbind('click').bind('click',function(){
                                $('.zlpop').fadeOut();
                                window.location.replace('/index.php/mobiles/wish?inviter_no='+inviter_no);
                            }).before('<P class="ys" id="no-count">今天机会用进,明天再来吧</p>');
                        }
                        $('.zlpop').fadeIn();
                        if(typeof ls_refresh == "function")ls_refresh();
                    }else if(rs.status == '10002'){
                        $('.zlpop').find('.popzlimg>img').attr('src','/assets/activity_wish/images/17.png');
                        $('.zlpop').find('.hs').html(rs.msg);
                        var rs_data = rs.data;
                        if(rs_data.have_count > 0){
                            $('.zlpop').find('.ding').text('再顶一次').unbind('click').bind('click',function(){
                                wish_help(true);
                            });
                        }else{
                            $('.zlpop').find('.ding').text('我也要许愿').unbind('click').bind('click',function(){
                                $('.zlpop').fadeOut();
                                window.location.replace('/index.php/mobiles/wish?inviter_no='+inviter_no);
                            }).before('<P class="ys" id="no-count">今天机会用进,明天再来吧</p>');
                        }
                        $('.zlpop').fadeIn();
                    }else{
                        $('.zlpop').find('.popzlimg>img').attr('src','/assets/activity_wish/images/17.png');
                        $('.zlpop').find('.hs').html(rs.msg);
                        $('.zlpop').fadeIn();
                    }
                }
            });
        };
        $(function(){
            ajax_loading_style(1,1);
            $(".wish-help").bind('click',function(){
                wish_help('.wish-help');
            });
            $(".help-log").list_data({
                data:'/index.php/mobiles/wish/ajax_get_help_log',
                param:{wish_id:'<?php echo $wish['wish_id']; ?>'},
                page_size:2,
                event_type:'click',
                show_loading:'img-msg',
                btn:true,
                value_func:{
                    add_time:function(v){
                        var now = Date.parse(new Date())/1000;
                        var space = parseInt(now - v);
                        var str;
                        if(space < 10){
                            str = '刚刚';
                        }else if(space < 60){
                            str = space+'秒前';
                        }else if(space < 3600){
                            str = (Math.floor(space/60))+'分钟前';
                        }else if(space < 24*3600){
                            str = unixtime_style(v,'H:i');
                        }else{
                            str = unixtime_style(v,'Y-m-d H:i');
                        }
                        return str;
                    }
                }
            },function(ls_func){
                ls_refresh = ls_func;
            });

            $("#down-load").click(function(){
                var u = navigator.userAgent;
                if (u.indexOf('Android') > -1 || u.indexOf('Linux') > -1) {//安卓手机
                    window.location.href='<?php echo site_url('snowballapp.apk'); ?>';
                    return;
                }
                if (u.indexOf('iPhone') > -1 || u.indexOf('iPad') > -1) {//苹果手机 ipad
                    window.location.href='https://appsto.re/cn/ApWE9.i';
                    return;
                }
            });
            wx_share.conf.img = 'https://www.juxueqiu.com//assets/activity_wish/images/14.jpg';
            wx_share.conf.ticket = '/index.php/mobiles/wish/ajax_get_ticket';
            wx_share.conf.decription = '<?php echo $title;?>';
            wx_share.share({
                trigger:function(){},
                success:function(){},
                cancle:function(){}
            })
        });
        var unixtime_style = function(unixtime,format){
            if(!unixtime)return '无';
            var timestr = new Date(parseInt(unixtime) * 1000);
            if(!format)return timestr.getFullYear()+"-"+timestr.getMonth()+1+"-"+timestr.getDate()+" "+timestr.getHours()+":"+timestr.getMinutes()+":"+timestr.getSeconds();
            format=format.replace("Y",timestr.getFullYear());
            format=format.replace("y",timestr.getYear());
            format=format.replace("m",timestr.getMonth()+1<10?'0'+(timestr.getMonth()+1):timestr.getMonth()+1);
            format=format.replace("d",timestr.getDate()<10?'0'+timestr.getDate():timestr.getDate());
            format=format.replace("w",timestr.getDay()||7);
            format=format.replace("H",timestr.getHours());
            format=format.replace("h",timestr.getHours());
            format=format.replace("i",timestr.getMinutes());
            format=format.replace("s",timestr.getSeconds());
            if(timestr.getHours() > 12){
                format=format.replace("am",'pm');
                format=format.replace("上午",'下午');
            }
            return format;
        };
    </script>
</html>