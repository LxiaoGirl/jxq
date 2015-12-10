<!DOCTYPE !!>
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
<script src="/assets/js/seajs/sea.js" type="text/javascript"></script>
<script src="/assets/js/jquery/jquery-1.11.1.min.js" type="text/javascript"></script>
<script src="/assets/js/jquery/myjs.js" type="text/javascript"></script>
</head>

<body>
    <div class="wrap">
<!--    	<div class="header">-->
<!--        	<h1>实名认证</h1>-->
<!--        </div>-->
		<form id="passform" action="" method="post" onsubmit="return false;">
             <div class="box">
                <input id="name" name = "name" class="shuru js_name" type="text" value="" placeholder="请输入姓名"/>
                <p id="chkpwd"></p>
            </div>
            <div class="box">
                <input id="cardnub" name = "cardnub" class="shuru js_nric" type="text" value="" placeholder="请输入身份证号码" />
                <p id="chkcpwd"></p>
            </div>
            <div class="box" style="padding:0px;">
                <button id="pass_btn" type="button">下一步</button>
            </div>
        </form>
    </div>
</body>
  <script>
    var chkpwd = false;
    var chkcpwd = false;
    seajs.use(['jquery', 'sys','validator','endtime','iptTip'], function () {
        $(function () {
            $('#passform').validate({
                '.js_nric': {
                    filtrate: 'required nric',
                    callback: function (index) {                        
                            if (index === 0) {
                                $("#chkcpwd").html("请输入身份证号码").addClass("error").show();
                                chkpwd = false;
                            } else if (index === 1 || index === 2) {
                                $("#chkcpwd").html("请输入的正确的身份证号码").addClass("error").show();
                                chkpwd = false;
                            }else{
                                $("#chkcpwd").html("&nbsp;").removeClass("error").addClass("success").show();
                                chkpwd = true;
                                if(chkcpwd == true){
                                    $("#pass_btn").css({
                                     background:"#da251c",
                                     cursor:"pointer" 
                                     });
                                    $("#pass_btn").removeAttr("disabled");  
                                }
                            }
                    }
                }
            });

        });
    });
    $("#name").keyup(function(){
            if($(this).val().length<2)
            {
               $("#chkpwd").html("请输入真实姓名").addClass("error").show();
               chkcpwd = false;
            }
            else
            {
               $("#chkpwd").html("&nbsp;").removeClass("error").addClass("success").show();
               chkcpwd = true;
               if(chkpwd == true){
                                    $("#pass_btn").css({
                                     background:"#da251c",
                                     cursor:"pointer" 
                                     });
                                    $("#pass_btn").removeAttr("disabled");  
                                }
            }
        })//重复密码验证
    $(function(){
        $('#pass_btn').on('click',function(){
            $("#pass_btn").attr("disabled",true).html('提交中...');
            $.ajax({
                url:'/index.php/apps/intermediary/real_name',
                dataType:'json',
                type:'post',
                data:{
                    'real_name':$('#name').val(),
                    'nric':$("#cardnub").val()
                },
                success:function(resut){
                    $("#pass_btn").css({
                        background:"#da251c",
                        cursor:"pointer"
                    });
                    $("#pass_btn").removeAttr("disabled").html('下一步');
                    if(resut.code == 0){
                        window.location.replace('<?php echo site_url('apps/intermediary/apply_success'); ?>');
                    }else{
                        $("#chkcpwd").html(resut.msg).addClass("error").show();
                    }
                }
            });
            return false;
        });
    });
</script>
</html>
