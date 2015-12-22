<!DOCTYPE html>
<html>
<head>
    <title></title>
    <meta name="description" content=""/>
    <meta name="viewport" content="width=device-width, initial-scale=1,user-scalable=no"/>
    <meta http-equiv="X-UA-Compatible" content="IE=Edge,chrome=1"/>
    <meta http-equiv="Cache-Control" content="no-siteapp"/>
    <meta charset="utf-8"/>
    <meta name="baidu-site-verification" content="NWiIzGM1AG"/>
    <link href="/assets/css/pages/mycss.css" rel="stylesheet" type="text/css">
</head>
<body>
<div  id="page-1" class="wrap">
    <div id="mask"></div>
    <div class="header">
        <h1>用户注册</h1>
        <a class="hlogo" href="#"><span>聚雪球</span></a>
    </div>
    <form id="myform" action="/index.php/jujianren/sign_up" method="post" onsubmit="return false;">
        <div class="box">
            <input id="username" maxlength="11" name="mobile" class="shuru" type="text" value="" placeholder="请输入电话号码"/>
            <p class="tishi"></p>
        </div>
        <div class="box">
            <input id="code" name="captcha" maxlength="6" class="shuru code" type="text" value="" placeholder="请输入验证码"/>
            <span class="codepic"> <img id="imgCode" src="<?php echo site_url('send/captcha'); ?>" width="79px"
                style="float:left;margin-top:10px;" alt="验证码"
                onclick="javascript:this.src = '<?php echo site_url('send/captcha'); ?>?t='+ new Date().valueOf()"
                title="点击更换验证码"/>
            <a style="float:left;color:#7c7c7c;margin-top:12px;" onclick="document.getElementById('imgCode').click();">看不清验证码</a></span>
            <p class="clear" id="chkcode"></p>
        </div>
        <div class="box" style="padding:0px;">
            <button id="tijiao" type="button" class="send">下一步</button>
        </div>

        <?php if (empty($inviter_no)): ?>
            <div><p style="text-align: center;"><?php echo $inviter_no_msg; ?></p></div>
        <?php endif; ?>

        <div id="tanchu">
            <h2>请输入语音验证码<a id="close" href="javacript:void(0)"></a></h2>

            <div id="yanzheng">
                <div class="box2" style="margin-bottom:24px;height: 71px;">
                    <input id="yuyin" name = "authcode" maxlength="6" type="text"  style="width:30%;"/><input  style="width:30%;" type="button" id="send-sms" value="语音验证码"/><input  style=" margin-right:4%; width:30%; height: 39px;background: #fe7000;color: #FFF;border: none;float: right;display: inline-block;" type="button" id="send-sms-1" value="短信验证码"/>
                    <div class="clear" id="chkvoice" style="  padding-top: 16px;"></div>
                </div>
                <div class="box2">
                    <button id="tijiao2" type="button">下一步</button>
                </div>
            </div>
        </div>
    </form>
</div>
<div id="page-2" class="wrap" style="display: none;">
    <div class="header">
        <h1>设置密码</h1>
    </div>
    <form id="passform" action="https://www.zgwjjf.com/index.php/jujianren/setmima" method="post">
        <div class="box">
            <input id="pwd" name="password" class="shuru" type="password" value="" placeholder="请输入密码"/>

            <p id="chkpwd"></p>
        </div>
        <div class="box">
            <input id="cpwd" class="shuru" type="password" value="" placeholder="请再次输入密码"/>

            <p id="chkcpwd"></p>
        </div>
        <div class="box" style="padding:0px;">
            <button id="pass_btn" type="button">注册</button>
        </div>
    </form>
</div>
<script type="text/javascript" src="/assets/js/jquery/jquery-2.1.1.min.js"></script>
<script type="text/javascript">
    var mobile = '',authcode='';
    function istel(str){
        var ret = /^1[3|4|5|7|8|9][0-9]\d{4,8}$/
        return ret.test(str);
    }
    $(document).ready(function() {
        //$("#tanchu").hide()
        var maskheight = $(window).height();
        $("#close").click(function(){
            $("#tanchu").fadeOut(600);
            $("#mask").css({visibility:"hidden",zIndex:"0",height:maskheight+'px'}).animate({
                opacity:"0.5"
            })
        });

        $("#tijiao").css("background","#CCC").attr("disabled",true);
        $("#tijiao2").css("background","#CCC").attr("disabled",true);

        var chkusername = false;
        var chkcode = false;
        var chkvoice = false;
        $("#username").keyup(function(){
            if($(this).val()==""){
                $(".tishi").show().addClass("error").html("请输入手机号码!");
                chkusername = false;
            }else if(!istel($(this).val())){
                $(".tishi").show().addClass("error").html("请输入正确的手机号码!")
                chkusername = false
            }else{
                //手机号验证开始
                //验证手机号是否注册
                $.ajax({
                    type: 'POST',
                    url: '/index.php/login/ajax_is_register',
                    data: {mobile:$('input[name="mobile"]').val()},
                    dataType:'json',
                    success: function(result){
                        if(result.status == '10000' ){
                            $(".tishi").show().removeClass("error").addClass("success").html("这个号码可以使用!");
                            chkusername = true;
                            if(chkusername==true&&chkcode==true){
                                $("#tijiao").removeAttr("disabled").css({
                                    background:"#da251c",
                                    cursor:"pointer",
                                    color:"#fff"
                                });
                            }
                        }else{
                            $(".tishi").show().removeClass("success").addClass("error").html("手机号码重复不能注册！");
                            chkusername = false;
                        }
                    }
                });
                //手机号验证结束
            }
        });
        //用户名验证结束
        $("#code").keyup(function(){
            if($(this).val()==""){
                $("#chkcode").show().addClass("error").html("请输入验证码!");
                chkcode = false;
            }else{
                //验证码AJAX开始
                $.ajax({
                    type: 'POST',
                    url: '/index.php/send/ajax_check_captcha',
                    data: {captcha:$('input[name="captcha"]').val()},
                    dataType:'json',
                    success: function(result){
                        if(result.status == '10000' ){
                            $("#chkcode").show().removeClass("error").addClass("success").html("验证码正确！");
                            chkcode = true
                            if(chkusername==true&&chkcode==true){
                                $("#tijiao").removeAttr("disabled").css({
                                    background:"#da251c",
                                    cursor:"pointer",
                                    color:"#fff"
                                });
                            }
                        }else{
                            $("#chkcode").show().removeClass("success").addClass("error").html("验证码错误！");
                            $("#tijiao").css("background","#CCC").attr("disabled",true);
                            chkcode = false
                        }
                    }
                });
                //验证码AJAX结束

            }
        });
        //点击后让按钮失效，60S后恢复
        var wait=60;
        function time(o) {
            if (wait == 0) {
                o.removeAttribute("disabled");
                o.value="获取语音验证码";
                wait = 60;
            } else {
                o.setAttribute("disabled", true);
                o.value="" + wait + "";
                wait--;
                setTimeout(function() {
                        time(o)
                    },
                    1000)
            }
        }
        //点击后让按钮失效，60S后恢复
        //短信触发开始
        $('#send-sms').click(function(){
            time(this);
            $(this).css({
                background:"#CCC"
            });
            setTimeout(function() {
                $('#send-sms').css({
                    background:"#fe7000",
                    cursor:"pointer"
                });
            },60000);

            $.ajax({
                type:'post',
                url:'/index.php/send/index',
                dataType:'json',
                data:{action:'register',type:'voice',mobile:$('input[name="mobile"]').val()},
                success:function(resut){
                    $("#chkvoice").html(resut.msg);
                }
            })
        });

        //点击后让按钮失效，60S后恢复
        var wait1=60;
        function time1(o) {
            if (wait1 == 0) {
                o.removeAttribute("disabled");
                o.value="获取语音验证码";
                wait1 = 60;
            } else {
                o.setAttribute("disabled", true);
                o.value="" + wait1 + "";
                wait1--;
                setTimeout(function() {
                        time1(o)
                    },
                    1000)
            }
        }
        //点击后让按钮失效，60S后恢复
        //短信触发开始
        $('#send-sms-1').click(function(){
            time1(this);
            $(this).css({
                background:"#CCC"
            });
            setTimeout(function() {
                $('#send-sms-1').css({
                    background:"#fe7000",
                    cursor:"pointer"
                });
            },60000);


            $.ajax({
                type:'post',
                url:'/index.php/send/index',
                dataType:'json',
                data:{action:'register',type:'sms',mobile:$('input[name="mobile"]').val()},
                success:function(resut){
                    $("#chkvoice").html(resut.msg);
                }
            })
        });
        //短信触发结束

        $("#tijiao").click(function(){
            if(chkusername==true&&chkcode==true){
                $("#tanchu").css({visibility:"visible",zIndex:"200"}).fadeIn(600);
                $("#mask").css({visibility:"visible",zIndex:"100",height:maskheight+'px'}).animate({opacity:"0.5"})
            }
        });

        $("#yuyin").keyup(function(){
            if($(this).val()==""){
                $("#chkvoice").html("请输入验证码！");
                $("#tijiao2").css("background","#CCC").attr("disabled",true);
                chkvoice =false
            }else if($(this).val().length != 6){
                $("#chkvoice").html("验证码错误！");
                $("#tijiao2").css("background","#CCC").attr("disabled",true);
                chkvoice =false
            }else{
                $("#chkvoice").html("输入正确！");
                $("#tijiao2").css("background","#fe7000").removeAttr("disabled");
                chkvoice = true
            }
        });
        $("#tijiao2").click(function(){
            if(chkusername==true&&chkcode==true&&chkvoice==true){
                $("#tijiao2").css("background","#CCC").attr("disabled",true).html('提交中...');
                $.ajax({
                    type:'post',
                    url:'/index.php/send/validate_authcode',
                    dataType:'json',
                    data:{action:'register',authcode:$("#yuyin").val(),mobile:$('input[name="mobile"]').val()},
                    success:function(resut){
                        if(resut.status == '10000'){
                            mobile = $('input[name="mobile"]').val();
                            authcode = $('#yuyin').val();
                            $('#page-1').hide();
                            $('#page-2').show();
                        }else{
                            $("#chkvoice").html(resut.msg);
                        }
                        $("#tijiao2").css("background","#fe7000").removeAttr("disabled").html('下一步');
                    }
                })
            }
        });

        //密码验证开始
        $("#pass_btn").css("background","#CCC").attr("disabled",true);
        var chkpwd = false;
        var chkcpwd = false;
        $("#pwd").keyup(function(){
            if($(this).val()==""){
                $("#chkpwd").html("请填写密码！").addClass("error").show();
                chkpwd = false;
            }else if($(this).val().length<6||$(this).val().length>16){
                $("#chkpwd").html("密码在6~16位之间！").addClass("error").show();
                chkpwd = false;
            }else{
                $("#chkpwd").html("设置成功").removeClass("error").addClass("success").show();
                chkpwd = true;
            }
        });
        //密码验证结束
        $("#cpwd").keyup(function(){
            if($(this).val()==""){
                $("#chkcpwd").html("请再次输入密码！").addClass("error").show();
                chkcpwd = false;
            }else if($(this).val()!=$("#pwd").val()){
                $("#chkcpwd").html("两次密码输入不一致！").addClass("error").show();
                chkcpwd = false;
            }else{
                $("#chkcpwd").html("设置成功").removeClass("error").addClass("success").show();
                chkcpwd = true;
                $("#pass_btn").css({
                    background:"#da251c",
                    cursor:"pointer"
                });
                $("#pass_btn").removeAttr("disabled");
            }
        });
        //重复密码验证
        $("#pass_btn").click(function(){
            if(chkpwd==true&&chkcpwd==true){
                var inviter_no = '<?php echo $inviter_no; ?>';
                $("#pass_btn").css("background","#CCC").attr("disabled",true).html('注册中...');
                $.ajax({
                    type:'post',
                    url:'/index.php/jujianren/sign_up',
                    dataType:'json',
                    data:{mobile:mobile,password:$("#pwd").val(),inviter_no:inviter_no,authcode:authcode},
                    error:function(a,b,c){
                        $("#chkcpwd").html('服务器繁忙,请稍后重试！').addClass("error").show();
                        $("#pass_btn").removeAttr("disabled").html('注册').css({
                            background:"#da251c",
                            cursor:"pointer"
                        });
                    },
                    success:function(resut){
                        if(resut.status == '10000'){
                            window.location.href='/index.php/jujianren/success';
                        }else{
                            $("#chkcpwd").html(resut.msg).addClass("error").show();
                        }
                        $("#pass_btn").css({
                            background:"#da251c",
                            cursor:"pointer"
                        });
                        $("#pass_btn").removeAttr("disabled").html('注册').css({
                            background:"#da251c",
                            cursor:"pointer"
                        });
                    }
                })
            }
        })
    });
</script>
</body>
</html>