<!DOCTYPE html>
<html>
<head>
    <title>聚雪球_网加金服_P2P理财首选互联网金融P2P网贷平台_100元即可投资!</title>
    <?php $this->load->view('common/head_file'); ?>
</head>
<body>
<!--	加载头部文件-->
<?php $this->load->view('common/head'); ?>
    <div id="step-1" class="row gs_zc1 gs_zc5 step">
        <h1>企业用户注册</h1>
        <img src="../../../../assets/images/passport/step_1.jpg" alt="">
        <form action="" onsubmit="return false;">
            <p>
                <span class="z1">企业联系人手机</span>
                <span class="z2"><input type="text" class="ifhav" name="mobile" id="mobile" placeholder="请输入企业联系人的手机号码" maxlength="11" /></span>
                <span class="z3"></span>
            </p>
            <p class="tip">
                <span class="z1"></span>
                <span class="z2 mobile-tip"></span>
                <span class="z3"></span>
            </p>
            <p>
                <span class="z1">图片验证码</span>
                <span class="z2 sj"><input type="text" class="ifhav" name="captcha" id="captcha" placeholder="请输入右侧的验证码" maxlength="6" />
                    <font>
                        <img id="img-code" src="/index.php/send/captcha" title="点击刷新"
                             style="width: 96px;height: 36px;"
                             onclick="javascript:this.src = '/index.php/send/captcha?t='+ new Date().valueOf()"/>
                    </font>
                </span>
                <span class="z3" onclick="javascript:document.getElementById('img-code').src = '/index.php/send/captcha?t='+ new Date().valueOf()" style="cursor: pointer;">看不清？点此更换</span>
            </p>
            <p class="tip">
                <span class="z1"></span>
                <span class="z2 captcha-tip"></span>
                <span class="z3"></span>
            </p>
            <p>
                <span class="z1"></span>
                <span class="z2"><input type="checkbox" checked>我同意<a href="/index.php/about/register_agreement" class="ls">《聚雪球企业用户注册协议》</a></span>
                <span class="z3"></span>
            </p>
            <p>
                <span class="z1"></span>
                <span class="z2"><button type="button" class="ls" id="step-1-submit">下一步</button></span>
                <span class="z3"></span>
            </p>
        </form>
        <div class="popbj"></div>
        <div class="pop">
            <div class="popnr">
                <p class="title">企业用户注册须知<font class="fr close">×</font></p>
                <p>欢迎您注册聚雪球企业用户，为了让您更好的享受我们为企业用户提供的服务，在注册之前，您需要准备以下材料：</p>
                <p>1.企业营业执照（扫描件）</p>
                <p>2.企业银行开户许可证（扫描件）</p>
                <p class="ie-msg-after">3.企业联系人身份证复印件（正反面）</p>
                <p><button type="button" class="ls close">我准备好了</button></p>
            </div>
        </div>
    </div>

    <div id="step-2" style="display: none;" class="row gs_zc1 step">
    <h1>企业用户注册</h1>
    <img src="../../../../assets/images/passport/step_2.jpg" alt="">
    <form action="" onsubmit="return false;">
        <p>
            <span class="z1">手机验证码</span>
            <span class="z2 sj"><input type="text" name="authcode" id="authcode" class="ifhav" placeholder="请输入短信验证码"><input type="button" class="green send-sms" value="重新发送"></span>
            <span class="z3"></span>
        </p>
        <p>
            <span class="z1"></span>
            <span class="z2 ftls12st authcode-tip">短信验证码已发送至您的手机<span class="send-mobile-show">185****4564</span>上，请在输入框内填写收到的验证码，若未收到请在倒计时后点击重新发送按钮。</span>
            <span class="z3"></span>
        </p>
        <p>
            <span class="z1">密码</span>
            <span class="z2 sj"><input type="password" name="password" id="password" class="ifhav reg_sj" placeholder="请输入密码" /></span>
            <span class="z3"></span>
        </p>
        <p>
            <span class="z1"></span>
            <span class="z2 ftls12st password-tip"></span>
            <span class="z3"></span>
        </p>
        <p>
            <span class="z1">确认密码</span>
            <span class="z2 sj"><input type="password" name="password1" id="password1" class="ifhav reg_sj" placeholder="请输入确认密码" /></span>
            <span class="z3"></span>
        </p>
        <p>
            <span class="z1"></span>
            <span class="z2 ftls12st password1-tip"></span>
            <span class="z3"></span>
        </p>
        <p>
            <span class="z1"></span>
            <span class="z2 sbut"><button type="button" class="hs" onclick="goto_page(1);">上一步</button><button type="button" id="step-2-submit" class="ls ajax-submit-button" data-loading-msg="注册中...">下一步</button></span>
            <span class="z3"></span>
        </p>
    </form>
</div>
</body>
<!--	加载头部文件-->
<?php $this->load->view('common/footer'); ?>
<script type="text/javascript">
    //INPUT框变色
    $('.ifhav').focus(function(){
        $(this).addClass('hav');
    }).blur(function(){
        if($.trim($(this).val())=='') $(this).removeClass('hav');
    });

    //切换页面内容 num= 页面数字id 放在全局用于html内可调用
    var goto_page = function(num){
        num = parseInt(num) || 1;
        $('.step').hide();
        $('#step-'+num).show();
    };
    seajs.use(['jquery','sys','wsb_sys'],function(){
        $(function(){
            //弹窗的关闭
            $('.pop').find('.close').click(function(){
                $('.pop').fadeOut();
                $('.popbj').fadeOut();
            });
            //定义部分变量
            var mobile='',authcode='',authcode_msg='',password='',password1=false;
            //step-1
            var mobile_check = function(flag){
                if(mobile  && flag)return;
                if(/^1[345789][0-9]{9}$/.test($("#mobile").val())){
                    $.post('/index.php/login/ajax_is_company_register',{mobile:$("#mobile").val(),type:'company'},function(rs){
                        switch (rs.status){
                            case '10000':
                                $(".mobile-tip").text('可以注册!');
                                mobile = $("#mobile").val();
                                break;
                            case '10003':
                                mobile = '';
                                wsb_alert('你已完成注册内容，请登录完善申请资料!',1,'/index.php/login/index?redirect_url=<?php echo urlencode(site_url('login/company_apply')); ?>');
                                break;
                            case '10002':
                                mobile = '';
                                wsb_alert(rs.msg,1,'/index.php/login');
                                break;
                            default:
                                mobile = '';
                                $(".mobile-tip").text(rs.msg);
                        }
                    },'json');
                }else{
                    if(flag || mobile)$(".mobile-tip").text('请输入正确格式的手机号码!');//已经正确过或者是焦点离开才提示 正在按键输入时不提示
                    mobile = '';
                }
            };
            $("#mobile").keyup(function(){mobile_check();}).blur(function(){mobile_check(1);});
            $("#captcha").keyup(function(){$(".captcha-tip").text('');});
            $('#step-1-submit').click(function(){
                if(mobile){
                    if(/^[0-9]{5,}$/.test($("#captcha").val())){
                        if( ! $('input[type="checkbox"]').prop('checked')){
                            wsb_alert('请先阅读并同意注册协议!',2);
                            return false;
                        }
                        $.post('/index.php/send/ajax_check_captcha',{captcha:$("#captcha").val()},function(rs){
                            if(rs.status == '10000'){
                                $(".captcha-tip").text('验证码正确!');
                                $('.send-mobile-show').text(mobile.substr(0,3)+'****'+mobile.substr(7,10));
                                authcode_msg = $('.authcode-tip').clone();
                                $('.authcode-tip').html('');
                                goto_page(2);
                                $('.send-sms').send_sms('sms',mobile,'register',function(rs){
                                    if(rs.status == '10000'){
                                        $('.authcode-tip').html($(authcode_msg).html());
                                    }else{
                                        $('.authcode-tip').html(rs.msg);
                                    }
                                });
                            }else{
                                $(".captcha-tip").text(rs.msg);
                                $("#captcha").focus();
                            }
                        },'json');
                    }else{
                        $("#captcha").focus();
                        $(".captcha-tip").text('请输入正确格式的图片验证码!');
                    }
                }else{
                    mobile_check(1);
                    $("#mobile").focus();
                }
            });
            //step-2
            var password_check = function(flag){
                if(password  && flag)return;
                if(/^[a-zA-Z_0-9]{6,20}$/.test($("#password").val())){
                    $(".password-tip").text('ok!');
                    password = $("#password").val();
                }else{
                    password = '';
                    if(flag || $(".password-tip").text() == 'ok!')$(".password-tip").text('请输入6-20位数字字母下划线组成的密码!');
                }
            };
            var password1_check = function(flag){
                if(password1  && flag)return;
                if(/^[a-zA-Z_0-9]{6,20}$/.test($("#password1").val())){
                    if(password == $("#password1").val()){
                        $(".password1-tip").text('ok!');
                        password1 = true;
                    }else{
                        if(flag || $(".password1-tip").text() == 'ok!')$(".password1-tip").text('两次密码不一致!');
                        password1 = false;
                    }
                }else{
                    password1 = false;
                    if(flag || $(".password1-tip").text() == 'ok!')$(".password1-tip").text('请输入与密码相同的确认密码!');
                }
            };
            $("#password").keyup(function(){password_check();}).blur(function(){password_check(1);});
            $("#password1").keyup(function(){password1_check();}).blur(function(){password1_check(1);});
            $("#authcode").keyup(function(){$(".authcode-tip").text('');});
            $('#step-2-submit').click(function(){
                if(mobile){
                    if( !password){ password_check(1);return false;}
                    if( !password1){ password1_check(1);return false;}
                    if(/^[0-9]{6}$/.test($("#authcode").val())){
                        $.post('/index.php/login/company',{mobile:mobile,authcode:$("#authcode").val(),password:password},function(rs){
                            if(rs.status == '10000'){
                                wsb_alert('操作成功,请进行下一步资料提交完成申请!',1,'/index.php/login/company_apply');
                            }else{
                                wsb_alert(rs.msg,2);
                            }
                        },'json');
                    }else{
                        $(".authcode-tip").text('请输入正确格式的手机验证码!');
                    }
                }else{
                    goto_page(1);
                }
            });
        });
    });

</script>
</html>