<!DOCTYPE html>
<html>
<head>
    <title>居间人申请</title>
    <meta name="description" content=""/>
    <meta name="viewport" content="width=device-width, initial-scale=1,user-scalable=no"/>
    <meta http-equiv="X-UA-Compatible" content="IE=Edge,chrome=1"/>
    <meta http-equiv="Cache-Control" content="no-siteapp"/>
    <meta charset="utf-8"/>
	<meta name="baidu-site-verification" content="NWiIzGM1AG" />
    <link href="/assets/css/pages/mycss.css" rel="stylesheet" type="text/css">
    <script src="/assets/js/jquery/jquery-1.11.1.min.js" type="text/javascript"></script>
</head>
<body>
  <div class="wrap" id="page1">
    <div id="mask"></div>
    <div class="header">
       <h1>居间人申请</h1>
        <a class="hlogo" href="#"><span>聚雪球</span></a>
    </div>
    <form id="myform" action="" method="post" onsubmit="return false;">
        <div class="box">
            <input id="username"  maxlength="11"  name="mobile" class="shuru" type="text" value="<?php echo profile('mobile'); ?>" placeholder="请输入电话号码"/>
            <p class="tishi"></p>
        </div>
       <div class="box">
            <input id="code" name="captcha" maxlength="6"  class="shuru code" type="text" value="" placeholder="请输入验证码" /><span class="codepic"> <img id="imgCode" src="<?php echo site_url('send/captcha');?>" width="79px"  style="float:left;margin-top:10px;" alt="验证码" onclick="javascript:this.src = '<?php echo site_url('send/captcha');?>?t='+ new Date().valueOf()" title="点击更换验证码"/>
            <a style="float:left;color:#7c7c7c;margin-top:12px;" onclick="ref_code();">看不清验证码</a></span>
            <p class="clear" id="chkcode"></p>
       </div>
        <div class="box" style="display:none">
            <input type="checkbox" id="agree" checked style="    height: auto;width: auto;border: none;padding-left: 0;border: 0;"><a style="color:#639BFD;" href="<?php echo site_url('mobiles/intermediary/agree'); ?>">申请协议</a>
            <p class="agree"></p>
        </div>
        <div class="box" style="padding:0px;">
            <button id="tijiao" type="button" class="send">下一步 </button>
        </div>
        <div id="tanchu" style="display: none; width:90%; margin-left:-45%;">
            <div class="tanchu1">
                <div class="tanchu2">
                    <h2>请输入语音验证码<a id="close" href="javacript:void(0)"></a></h2>
                    <div id="yanzheng" >
                        <div class="box2">
                            <input id="yuyin" name = "authcode" maxlength="6" type="text"  style="width:30%;"/><input  style="width:30%;" type="button" id="send-sms" value="获取语音验证码"/><input  style=" margin-right:4%; width:30%; height: 39px;background: #fe7000;color: #FFF;border: none;float: right;display: inline-block;" type="button" id="send-sms-1" value="获取短信验证码"/>
                            <div class="clear" id="chkvoice" style="  padding-top: 16px;"></div>
                        </div>
                        <div class="box2">
                            <button id="tijiao2" type="button">下一步 </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
  </div>

  <div class="wrap" id="page2"  style="display: none;" >
      <div class="header">
          <h1 id="pwd-title">设置密码</h1>
      </div>
      <form id="passform" action="" method="post" onsubmit="return false;">
          <div class="box">
              <input id="pwd" name = "password" class="shuru" type="password" value="" placeholder="请输入密码"/>
              <p id="chkpwd"></p>
          </div>
          <div class="box" id="pwd-retry">
              <input id="cpwd" class="shuru" type="password" value="" placeholder="请再次输入密码" />
              <p id="chkcpwd"></p>
          </div>
          <div class="box" style="padding:0px;">
              <button id="pass_btn" type="button">确定</button>
          </div>
      </form>
  </div>
</body>
<script type="text/javascript">
function ref_code () {
    $('#imgCode').click();
}

function istel(str){
    var ret = /^1[3|4|5|7|8|9][0-9]\d{4,8}$/
    return ret.test(str);
}
$(function(){
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
    var userinfo = '';
    var isLogin = ('<?php echo profile('uid'); ?>' > 0)?true:false;
    var button1_status =function(flag){
        if(flag){
            $("#tijiao").removeAttr("disabled");
            $("#tijiao").css({
                background:"#da251c",
                cursor:"pointer",
                color:"#fff"
            });
        }else{
            if(!$("#tijiao").prop("disabled"))$("#tijiao").css("background","#CCC").attr("disabled",true);
        }
    };
    var button2_status =function(flag){
        if(flag){
            $("#tijiao2").css("background","#fe7000").removeAttr("disabled");
        }else{
            if(!$("#tijiao2").prop("disabled"))$("#tijiao2").css("background","#CCC").attr("disabled",true);
        }
    };
    var button3_status =function(flag){
        if(flag){
            $("#pass_btn").css({
                background:"#da251c",
                cursor:"pointer"
            });
            $("#pass_btn").removeAttr("disabled");
        }else{
            if(!$("#pass_btn").prop("disabled"))$("#pass_btn").css("background","#CCC").attr("disabled",true);
        }
    };
    var check_phone = function(){
        if($('input[name="mobile"]').val()==""){
            $(".tishi").show().addClass("error").html("请输入手机号码!");
            button1_status(false);
            chkusername = false;
        }else if(!istel($('input[name="mobile"]').val())){
            $(".tishi").show().addClass("error").html("请输入正确的手机号码!");
            button1_status(false);
            chkusername = false
        }else{
            //手机号验证开始
            //验证手机号是否注册
            $.ajax({
                type: 'POST',
                url: '/index.php/mobiles/intermediary/ajax_intermediary_check',
                data: {mobile:$('input[name="mobile"]').val()},
                dataType:'json',
                success: function(result){
                    if(result.code ==0 ){
                        $(".tishi").show().removeClass("error").addClass("success").html("这个号码可以申请!");
                        userinfo = result.data;
                        chkusername = true
                        if(chkusername==true&&chkcode==true){
                            button1_status(true);
                        }
                    }else{
                        $(".tishi").show().removeClass("success").addClass("error").html(result.msg);
                        chkusername = false;
                    }
                }
            });
            //手机号验证结束
        }
    };
    if('<?php echo profile('mobile'); ?>' != ''){
        check_phone();
    }
    $("#username").on('keyup',function(){
        check_phone();
    });//用户名验证结束
    $("#code").on('keyup',function(){
        if($(this).val()==""){
            $("#chkcode").show().addClass("error").html("请输入验证码!");
            button1_status(false);
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
                        $("#chkcode").val("0");
                        chkcode = true
                        if(chkusername==true&&chkcode==true){
                            button1_status(true);
                        }
                    }else{
                        $("#chkcode").val("1");
                        $("#chkcode").show().removeClass("success").addClass("error").html("验证码错误！");

                        button1_status(false);
                        chkcode = false
                    }
                }
            });
            //验证码AJAX结束

        }
    })
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
            setTimeout(function() { time(o)},1000);
        }
    }
    var wait1=60;
    function time1(o) {
        if (wait1 == 0) {
            o.removeAttribute("disabled");
            o.value="获取短信验证码";
            wait1 = 60;
        } else {
            o.setAttribute("disabled", true);
            o.value="" + wait1 + "";
            wait1--;
            setTimeout(function() { time1(o)},1000);
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
            type: 'POST',
            url:'/index.php/send/index',
            dataType:'json',
            data:{action:'jujianren',captcha:$('input[name="captcha"]').val(),mobile:$('input[name="mobile"]').val(),'type':'voice'},
            success:function(resut){
                // sys.alert(resut.msg);
            }
        })
    });
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
            type: 'POST',
            url:'/index.php/send/index',
            dataType:'json',
            data:{action:'jujianren',captcha:$('input[name="captcha"]').val(),mobile:$('input[name="mobile"]').val(),'type':'sms'},
            success:function(resut){
                // sys.alert(resut.msg);
            }
        })
    });
    //短信触发结束
    $("#agree").click(function(){
        if($(this).prop('checked')){
            $(".agree").show().removeClass("error").addClass("success").html('&nbsp;');
        }else{
            $(".agree").show().removeClass("success").addClass("error").html("请同意协议！");
        }
    });

    $("#tijiao").click(function(){
        if(!$("#agree").prop('checked')){
            $(".agree").show().removeClass("success").addClass("error").html("请同意协议！");
            return false;
        }else{
            $(".agree").show().removeClass("error").addClass("success").html('&nbsp;');
        }
        if(chkusername==true&&chkcode==true){
            $("#tanchu").css({visibility:"visible",zIndex:"200"}).fadeIn(600);
            $("#mask").css({visibility:"visible",zIndex:"100",height:maskheight+'px'}).animate({opacity:"0.5"})
        }
    });

    $("#yuyin").keyup(function(){
        if($(this).val()==""){
            $("#chkvoice").html("请输入验证码！");
            button2_status(false);
            chkvoice =false
        }else if($(this).val().length!=6){
            $("#chkvoice").html("验证码错误！");
            button2_status(false);
            chkvoice =false
        }else{
            $.ajax({
                type: 'POST',
                url: '/index.php/mobiles/intermediary/ajax_authcode_check',
                data: {authcode:$(this).val(),mobile:$('input[name="mobile"]').val()},
                dataType:'json',
                success: function(result){
                    $("#chkvoice").html(result.msg);
                    if(result.code ==0 ){
                        button2_status(true);
                        chkvoice = true
                    }else{
                        button2_status(false);
                        chkvoice =false
                    }
                }
            });
        }
    })
    $("#tijiao2").click(function(){
        if(chkusername==true&&chkcode==true&&chkvoice==true){
            var mobiel = $('input[name="mobile"]').val();
            var authcode = $("#yuyin").val();
            if(isLogin){
                $("#tijiao2").html('提交中...');
                button2_status(false);
                $.post('/index.php/mobiles/intermediary/ajax_intermediary_apply',{'mobile':mobiel,'password':'','authcode':authcode,'source':'<?php echo $inviter_no; ?>'},function(rs){
                    $("#tijiao2").html('下一步');
                    button2_status(true);
                    if(rs.code == 0){
                        window.location.replace(rs.data);
                    }else{
                        $("#chkvoice").html(rs.msg);
                    }
                },'json');
            }else{
                $("#page1").hide();
                if(userinfo.password){
                    $("#pwd-retry").remove();
                    $('#pwd-title').text('确认密码');
                }
                $("#page2").show();
            }
        }
    });
    //密码验证开始
    button3_status(false);
    var chkpwd = false;
    var chkcpwd = false;
    $("#pwd").keyup(function(){
        if($(this).val()==""){
            $("#chkpwd").html("请填写密码！").addClass("error").show();
            button3_status(false);
            chkpwd = false;
        }else if($(this).val().length<6||$(this).val().length>16){
            $("#chkpwd").html("密码在6~16位之间！").addClass("error").show();
            button3_status(false);
            chkpwd = false;
        }else{
            $("#chkpwd").html("&nbsp;").removeClass("error").addClass("success").show();
            chkpwd = true;
            if(userinfo.password) {  //有密码时  不要验证第二次密码
                chkcpwd = true;
                button3_status(true);
                return;
            }
        }
    });
    //密码验证结束
    $("#cpwd").keyup(function(){
        if($(this).val()==""){
            $("#chkcpwd").html("请再次输入密码！").addClass("error").show();
            button3_status(false);
            chkcpwd = false;
        }else if($(this).val()!=$("#pwd").val()){
            $("#chkcpwd").html("两次密码输入不一致！").addClass("error").show();
            button3_status(false);
            chkcpwd = false;
        }else{
            $("#chkcpwd").html("&nbsp;").removeClass("error").addClass("success").show();
            chkcpwd = true;
            button3_status(true);
        }
    });//重复密码验证
    $("#pass_btn").click(function(){
        if(chkpwd==true&&chkcpwd==true){
            button3_status(false);
            $("#pass_btn").html('提交中...');
            $.post('/index.php/mobiles/intermediary/ajax_intermediary_apply',{'mobile':mobiel,'password':$("#pwd").val(),'authcode':authcode,'source':'<?php echo $inviter_no; ?>'},function(rs){
                button3_status(true);
                $("#pass_btn").html('确定');
                if(rs.code == 0){
                    window.location.replace(rs.data);
                }else{
                    $("#chkpwd").html(rs.msg).removeClass("success").addClass("error").show();
                }
            },'json');
        }
    });
});
</script>
</html>