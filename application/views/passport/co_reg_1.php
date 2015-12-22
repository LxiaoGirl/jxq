<!DOCTYPE html>
<html>
<head>
    <title>聚雪球_网加金服_P2P理财首选互联网金融P2P网贷平台_100元即可投资!</title>
    <?php $this->load->view('common/head_file'); ?>
</head>
<body>
    <div class="row gs_zc1">
        <h1>企业用户注册</h1>
        <img src="../../../../assets/images/passport/step_2.jpg" alt="">
        <form action="">
            <p>
                <span class="z1">手机验证码</span>
                <span class="z2 sj"><input type="text" class="ifhav" placeholder="请输入右侧的验证码"><input type="button" class="green" value="重新发送"></span>
                <span class="z3"></span>
            </p>
            <p>
                <span class="z1"></span>
                <span class="z2 ftls12st">短信验证码已发送至您的手机185****4564上，请在输入框内填写收到的验证码，若未收到请在倒计时后点击下方重新发送按钮。</span>
                <span class="z3"></span>
            </p>
            <p>
                <span class="z1"></span>
                <span class="z2 sbut"><button type="button" class="hs">上一步</button><button type="button" class="ls">下一步</button></span>
                <span class="z3"></span>
            </p>
        </form>
    </div>
</body>
<script type="text/javascript">
        //INPUT框变色
        $('.ifhav').focus(function(){
            $(this).addClass('hav');
        });
        $('.ifhav').blur(function(){
            if($.trim($(this).val())==''){
                $(this).removeClass('hav');
            }
        });
        //INPUT框变色
</script>
</html>