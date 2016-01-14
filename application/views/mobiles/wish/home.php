<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>新年愿望</title>
    <meta charset="utf-8">
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
<body>
    <div><img src="/assets/activity_wish/images/1.jpg" alt="" width="100%"></div>
    <div class="hb1 hbxz" data-wish-type="1" data-wish-name="投资返现红包-50元"><img src="/assets/activity_wish/images/2.jpg" alt="" width="100%"></div>
    <div class="hb2 hbxz" data-wish-type="2" data-wish-name="注册祝福红包-20元"><img src="/assets/activity_wish/images/3.jpg" alt="" width="100%"></div>
    <div><img src="/assets/activity_wish/images/4.jpg" alt="" width="100%"></div>
    <div class="hdlc">
        <p class="top">1. 活动时间</p>
        <p><font>2016-1-25  10:00  </font>至<font>  2016-2-4  23:59</font></p>
        <p class="top">2. 参与资格</p>
        <p>无论新老客户，只要<font>注册并通过实名认证</font>就可以参加本次许愿活动</p>
        <p class="top">3. 如何参加活动？</p>
        <p>资格：注册并实名认证。老客户点击活动页面，新客户点击分享页面链接，注册实名认证。</p>
        <p class="top">4. 如何领取愿望并分享？</p>
        <p>老客户三种愿望皆可选，且只能选其中之一；新客户投资实名认证并投资可选投资返现红包/注册祝福红包；新客户注册实名认证未投资可选注册祝福红包。点进相应愿望并分享链接到朋友圈。</p>
        <p class="top">5. 如何获得好友助力？</p>
        <p>好友点击朋友圈链接，顺利进入助力页面，点击助力按钮。</p>
        <p class="top">6. 如何获得投资返现红包？</p>
        <p>每位好友每天只能助力两次，活动截止时助力<font>排名前100名</font>可发放红包，有实时排名显示。</p>
    </div>
    <div><img src="/assets/activity_wish/images/6.jpg" alt="" width="100%"></div>
    <!--登录弹出-->
    <div class="dlpop pop">
        <div class="popcon">
            <div class="popnr">
                <img src="/assets/activity_wish/images/7.png" alt="" width="100%">
                <div class="posab">
                    <em class="close"></em>
                    <p class="dlt">账号登录&nbsp;&nbsp;&nbsp;&nbsp;<font>登录后才可以选愿望</font></p>
                    <p class="inp">
                        <img src="/assets/activity_wish/images/9.jpg" alt="" height="100%">
                        <input type="text" id="mobile" placeholder="手机号/用户名/邮箱">
                    </p>
                    <p class="inp">
                        <img src="/assets/activity_wish/images/10.jpg" alt="" height="100%">
                        <input type="password" id="password" placeholder="登录密码">
                    </p>
                    <p class="inp yzm">
                        <img src="/assets/activity_wish/images/11.jpg" alt="" height="100%">
                        <input type="text" id="captcha" placeholder="验证码">
                    </p>
                    <div class="yzmtp">
                        <img id="imgCode" src="<?php echo site_url('send/captcha'); ?>" width="78" height="34" alt="验证码"
                             onclick="javascript:this.src = '<?php echo site_url('send/captcha'); ?>?t='+ new Date().valueOf()"
                             title="点击更换验证码"/>
                    </div>
                    <p class="but"><button id="login" data-loading-msg="登录中...">登录</button></p>
                    <p class="a"><a class="fl" href="/index.php/mobiles/home/login">立即注册</a><a class="fr" href="/index.php/mobiles/home/forget">忘记密码？</a></p>
                </div>
            </div>
        </div>
    </div>
    <!--登录弹出-->
    <!--红包选择-->
    <div class="hbpop pop">
        <div class="popcon">
            <div class="popnr">
                <img src="/assets/activity_wish/images/7.png" alt="" width="100%">
                <div class="posab">
                    <em class="close"></em>
                    <p class="hbimg">
                        <!--50--><img src="/assets/activity_wish/images/12.png"><!--20<img src="images/13.png">-->
                    </p>
                    <p class="pxcywtl">您的新春愿望是：</p>
                    <p class="pxcywtc">投资返现红包<font>50</font>元</p>
                    <button class="qr" id="wish" data-loading-msg="许愿中...">确认</button>
                </div>
            </div>
        </div>
    </div>
    <!--红包选择-->
    <div class="cwts">
        <p><font>xxxxxx</font></p>
    </div>
</body>
<script src="/assets/activity_wish/js/jquery-1.8.5.min.js"></script>
<script src="/assets/js/app/jquery.list_data.1.1.js"></script>
    <script>
        $('.close').click(function(){
            $('.pop').fadeOut();
        });

        var is_login = '<?php echo $uid; ?>';
        var is_real = '<?php echo $clientkind; ?>';
        var is_invested = parseInt('<?php echo $is_invested; ?>');
        var wish_type = 1;
        var wish_name = '';
        $(function () {
            ajax_loading_style(1,1);
            $(".hbxz").click(function(){
                wish_type = $(this).data('wishType');
                wish_name = $(this).data('wishName');
                $('.hbpop').find('.pxcywtc').html(wish_type==1?'投资返现红包<font>50</font>元':'注册祝福红包<font>20</font>元');
                $('.hbpop').find('.hbimg>img').attr('src',wish_type==1?'/assets/activity_wish/images/12.png':'/assets/activity_wish/images/13.png');
                if(is_login == '0'){
                    $('.dlpop').fadeIn();
                    return false;
                }else{
                    if(is_real != '1' && is_real != '2'){
                        my_alert('请先进行实名认证才能参加活动哦!','/index.php/mobiles/home/real_name');
                        return false;
                    }
                    if( !is_invested && wish_type == 1){
                        my_alert('你尚未投资过不能选择改类型愿望哦!');
                        return false;
                    }
                    $('.hbpop').fadeIn();
                }
            });
            $('#wish').click(function(){
                $.ajax({
                    url:'/index.php/mobiles/wish/ajax_set_wish',
                    type:'post',
                    dataType:'json',
                    btn:'#wish',
                    data:{wish_type:wish_type,wish_name:wish_name},
                    success:function(rs){
                        var wish = rs.data;
                        if(rs.status == '10000'){
                            window.location.href='/index.php/mobiles/wish/detail?wish_id='+wish['wish_id']+'&uid='+wish['uid'];
                        }else if(rs.status == '10002'){
                            my_alert(rs.msg,'/index.php/mobiles/wish/detail?wish_id='+wish[0]['wish_id']+'&uid='+wish[0]['uid'])
                        }else{
                            my_alert(rs.msg);
                        }
                    }
                });
            });
            $('#login').click(function(){
                if($("#mobile").val() == ''){
                    my_alert('请输入登录用户名!');
                   return false;
                }
                if($("#password").val() == '' || $("#password").val().length < 6){
                    my_alert('请输入6位以上登录密码!');
                   return false;
                }
                if($("#captcha").val() == ''){
                    my_alert('请输入验证码!');
                    return false;
                }
                $.ajax({
                    url:'/index.php/mobiles/wish/login',
                    type:'post',
                    dataType:'json',
                    btn:'#login',
                    data:{mobile:$("#mobile").val(),password:$("#password").val(),captcha:$("#captcha").val()},
                    success:function(rs){
                        if(rs.status == '10000'){
                            is_login = 1;
                            var rs_data = rs.data;
                            is_real = rs_data.clientkind;
                            is_invested = parseInt(rs_data.is_invested);
                            if(is_real != '1' && is_real != '2'){
                                my_alert('请先进行实名认证哦','/index.php/mobiles/home/real_name');
                                return;
                            }
                            if( !is_invested && wish_type == 1){
                                $('.dlpop').fadeOut();
                                my_alert('你尚未投资过不能选择改类型愿望哦!');
                                return false;
                            }
                            $('.dlpop').fadeOut();
                            $('.hbpop').fadeIn();
                        }else{
                            my_alert(rs.msg);
                        }
                    }
                });
            });
        });
        var my_alert = function (msg,url) {
            $('.cwts').find('p>font').text(msg);
            $('.cwts').fadeIn();
            var t = setTimeout(function(){
                $('.cwts').fadeOut();
                if(url)window.location.href = url;
            },2000);
        }
    </script>
</html>