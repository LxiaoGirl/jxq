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
                <span class="z2"><input type="checkbox" checked>我同意<a href="" class="ls">《聚雪球企业用户注册协议》</a></span>
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
            <span class="z2 sj"><input type="text" name="authcode" id="authcode" class="ifhav" placeholder="请输入右侧的验证码"><input type="button" class="green send-sms" value="重新发送"></span>
            <span class="z3"></span>
        </p>
        <p>
            <span class="z1"></span>
            <span class="z2 ftls12st authcode-tip">短信验证码已发送至您的手机<span class="send-mobile-show">185****4564</span>上，请在输入框内填写收到的验证码，若未收到请在倒计时后点击重新发送按钮。</span>
            <span class="z3"></span>
        </p>
        <p>
            <span class="z1"></span>
            <span class="z2 sbut"><button type="button" class="hs" onclick="goto_page(1);">上一步</button><button type="button" id="step-2-submit" class="ls">下一步</button></span>
            <span class="z3"></span>
        </p>
    </form>
</div>

    <div id="step-3" style="display: none;" class="row gs_zc1 gs_zc2 step">
    <h1>企业用户注册</h1>
    <img src="../../../../assets/images/passport/step_3.jpg" alt="">
    <form action="" onsubmit="return false;">
        <h2>企业信息</h2>
        <p>
            <span class="z1">企业名称</span>
            <span class="z2"><input type="text" class="ifhav" name="company_name" id="company-name" placeholder="请输入企业名称" maxlength="50"></span>
            <span class="z3"></span>
        </p>
        <p class="tip">
            <span class="z1"></span>
            <span class="z2 company-name-tip">需与营业执照上的名称完全一致，信息审核成功后，此项不可修改。</span>
            <span class="z3"></span>
        </p>
        <p>
            <span class="z1">营业执照注册号</span>
            <span class="z2"><input type="text" class="ifhav" name="company_code" id="company-code" placeholder="请输入营业执照注册号" maxlength="25"></span>
            <span class="z3"></span>
        </p>
        <p class="tip">
            <span class="z1"></span>
            <span class="z2 company-code-tip">请输入15位营业执照注册号或18位的统一社会信用代码，信息审核成功后，此项可以修改。</span>
            <span class="z3"></span>
        </p>
        <p>
            <span class="z1">企业开户银行</span>
            <span class="z2"><input type="text" class="ifhav" name="company_bank_name" id="company-bank-name" placeholder="请输入企业开户银行" maxlength="20"></span>
            <span class="z3"></span>
        </p>
        <p class="tip">
            <span class="z1"></span>
            <span class="z2 company-bank-name-tip">需与企业银行开户许可证的开户银行完全一致，信息审核成功后，此项可以修改。</span>
            <span class="z3"></span>
        </p>
        <p>
            <span class="z1">银行账号</span>
            <span class="z2"><input type="text" class="ifhav" name="company_bank_account" id="company-bank-account" placeholder="请输入企业开户银行账号"></span>
            <span class="z3"></span>
        </p>
        <p class="tip">
            <span class="z1"></span>
            <span class="z2 company-bank-account-tip">需与企业银行开户许可证的开户账号完全一致，信息审核成功后，此项可以修改。</span>
            <span class="z3"></span>
        </p>
        <h2>企业联系人资料</h2>
        <p>
            <span class="z1">联系人姓名</span>
            <span class="z2"><input type="text" class="ifhav" name="company_user_name" id="company-user-name" placeholder="请输入企业联系人姓名" maxlength="10"></span>
            <span class="z3"></span>
        </p>
        <p class="tip">
            <span class="z1"></span>
            <span class="z2 company-user-name-tip">需与下一步提交的证照信息一致，信息审核成功后，此项可以修改。</span>
            <span class="z3"></span>
        </p>
        <p>
            <span class="z1">联系人身份证号码</span>
            <span class="z2"><input type="text" class="ifhav" name="company_user_nric" id="company-user-nric" placeholder="请输入企业联系人身份证号码" maxlength="18"></span>
            <span class="z3"></span>
        </p>
        <p class="tip">
            <span class="z1"></span>
            <span class="z2 company-user-nric-tip">需与下一步提交的证照信息一致，信息审核成功后，此项可以修改。</span>
            <span class="z3"></span>
        </p>
        <p>
            <span class="z1"></span>
            <span class="z2 sbut"><button type="button" class="hs" onclick="goto_page(2);">上一步</button><button type="button" class="ls" id="step-3-submit">下一步</button></span>
            <span class="z3"></span>
        </p>
    </form>
</div>

    <div id="step-4" style="display: none;" class="row gs_zc1 gs_zc3 step">
    <h1>企业用户注册</h1>
    <img src="../../../../assets/images/passport/step_4.jpg" alt="">
    <form action="" onsubmit="return false;">
        <p>
            <span class="z1">企业营业执照</span>
            <span class="z2">
                <input type="text" class="ifhav" readonly  />
                <input type="file" name="business_license" id="business-license" class="file-upload"  style="display: none;" />
                <input type="hidden" id="business-license-flag" value="0"  />
            </span>
            <span class="z3"><button type="button" class="green" onclick="document.getElementById('business-license').click();">选择文件</button></span>
        </p>
        <p class="tip business-license-tips"></p>
        <p>
            <span class="z1">银行开户许可证</span>
            <span class="z2">
                <input type="text" class="ifhav" readonly  />
                <input type="file" name="account_permit" id="account-permit" style="display: none;" class="file-upload" />
                <input type="hidden" id="account-permit-flag" value="0"  />
            </span>
            <span class="z3"><button type="button" class="green" onclick="document.getElementById('account-permit').click();">选择文件</button></span>
        </p>
        <p class="tip account-permit-tips"></p>
        <p>
            <span class="z1">企业联系人身份证复印件</span>
            <span class="z2">
                <input type="text" class="ifhav" readonly  />
                <input type="file" name="nric_copy" id="nric-copy" style="display: none;" class="file-upload" />
                <input type="hidden" id="nric-copy-flag" value="0"  />
            </span>
            <span class="z3"><button type="button" class="green" onclick="document.getElementById('nric-copy').click();">选择文件</button></span>
        </p>
        <p class="tip nric-copy-tips"></p>
        <p>
            <span class="z1"></span>
            <span class="z2 sbut"><button type="button" class="hs" onclick="goto_page(3);">上一步</button><button type="button" class="ls" id="step-4-submit">下一步</button></span>
            <span class="z3"></span>
        </p>
    </form>
</div>

    <div id="step-5" style="display: none;" class="row gs_zc4 gs_zc5 step">
    <h1>企业用户注册</h1>
    <img src="../../../../assets/images/passport/step_5.jpg" alt="">
    <div style="overflow:hidden;">
        <div class="fl">
            <p class="p1">以下为您提交的注册信息</p>
            <div>
                <p>企业名称：<font>沈阳网加互联网金融服务有限公司</font></p>
                <p>营业执照号码：<font>4464616667846513</font></p>
                <p>企业开户银行：<font>中国工商银行</font></p>
                <p>企业银行账号：<font>6261 4566 4568 1252</font></p>
            </div>
        </div>
        <div class="fr">
            <p class="p1">当前账户余额：0.00元 <button type="button">充值</button><button type="button">刷新余额</button></p>
            <p class="p2">企业信息审核由第三方公司负责审核，需要收取审核费300元。如果由于您提供的资料错误导致审核失败，再次提交审核时需再次缴纳300元审核费。</p>
        </div>
    </div>

    <p class="tc"><button class="hs" type="button">上一步</button><button class="ls" type="button" onclick="goto_page(6);">提交审核</button></p>
    <div class="popbj"></div>
    <div class="pop">
        <div class="popnr">
            <p class="title">充值提示<font class="fr close">×</font></p>
            <p>请再次确认您的银行账号信息，一旦充值后，您在注册过程中将无法修改您的开户银行和开户银行账号。</p>
            <p>开户银行：<font>中国工商银行</font></p>
            <p>开户银行账号：<font>6225 1236 5478 9546</font></p>
            <p class="red">充值金额：300元</p>
            <p><button type="button" class="ls">提交</button></p>
        </div>
    </div>
</div>

    <div id="step-6" style="display: none;" class="row gs_zc4 step">
    <h1>企业用户注册</h1>
    <img src="../../../../assets/images/passport/step_5.jpg" alt="">
    <h2>您已成功完成企业注册！</h2>
    <p class="ti411">企业名称：沈阳网加互联网金融服务有限公司</p>
    <p class="ti411">营业执照号码：4464616667846513</p>
    <p class="ti411">企业开户银行：中国工商银行</p>
    <p class="ti411">企业银行账号：6261 4566 4568 1252</p>
    <p class="tc"><button style="width:250px;" class="ls" type="button">进入个人中心</button></p>
</div>
</body>
<!--	加载头部文件-->
<?php $this->load->view('common/footer'); ?>
<script type="text/javascript">

        if(navigator.userAgent.indexOf("MSIE") > -1){
            $('.ie-msg-after').after('<p style="color: red;">ie浏览器暂不支持本页文件上传,可使用谷歌火狐等其他浏览器</p>');
        }else{
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
            //切换页面内容
            var goto_page = function(num){
                num = parseInt(num) || 1;
                $('.step').hide();
                $('#step-'+num).show();
            };
            seajs.use(['jquery','sys','wsb_sys'],function(){
                $(function(){
                    $('.pop').find('.close').click(function(){
                        $('.pop').fadeOut();
                        $('.popbj').fadeOut();
                    });
                    var mobile='',authcode='',authcode_msg='',company_name='',
                        company_code='',company_bank_name='',company_bank_account='',
                        company_user_name='',company_user_nric='';
                    //ajax上传
                    var jquery_ajax_upload_file = function(id,url,callback){
                        var file = $("#"+id).get(0).files[0];
                        var formData = new FormData();
                        var name = $("#"+id).attr('name') || 'file';
                        formData.append(name , file);
                        formData.append('file_name' , name);
                        /**
                         * 侦查附件上传情况 ,这个方法大概0.05-0.1秒执行一次
                         */
                        var per = 0;
                        function onprogress(evt){
                            var loaded = evt.loaded;     //已经上传大小情况
                            var tot = evt.total;      //附件总大小
                            per = Math.floor(100*loaded/tot);  //已经上传的百分比
                            if(per == 100)per=99;
                            if(typeof callback == "function")callback(per,false);
                        }
                        /**
                         * 必须false才会避开jQuery对 formdata 的默认处理
                         * XMLHttpRequest会对 formdata 进行正确的处理
                         */
                        $.ajax({
                            type: "POST",
                            dataType:'json',
                            url: url,
                            data: formData ,
                            processData : false,
                            //必须false才会自动加上正确的Content-Type
                            contentType : false ,
                            xhr: function(){
                                var xhr = $.ajaxSettings.xhr();
                                if(onprogress && xhr.upload) {
                                    xhr.upload.addEventListener("progress" , onprogress, false);
                                    return xhr;
                                }
                            },
                            success:function(rs){
                                if(typeof callback == "function")callback(per,rs);
                            },
                            error:function(a,b,c){ alert(a+b+c)}
                        });
                    };
                    //step-1
                    var mobile_check = function(flag){
                        if(mobile)return;
                        if(/^1[345789][0-9]{9}$/.test($("#mobile").val())){
                            $.post('/index.php/login/ajax_is_register',{mobile:$("#mobile").val(),type:'company'},function(rs){
                                if(rs.status == '10000'){
                                    $(".mobile-tip").text('可以注册!');
                                    mobile = $("#mobile").val();
                                }else{
                                    mobile = '';
                                    $(".mobile-tip").text(rs.msg);
                                }
                            },'json');
                        }else{
                            if(flag)$(".mobile-tip").text('请输入正确格式的手机号码!');
                        }
                    };
                    $("#mobile").keyup(function(){mobile_check();}).blur(function(){mobile_check(1);});
                    $('#step-1-submit').click(function(){
                        if(mobile){
                            if(/^[0-9]{5,}$/.test($("#captcha").val())){
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
                                    }
                                },'json');
                            }else{
                                $(".captcha-tip").text('请输入正确格式的图片验证码!');
                            }
                        }
                    });
                    //step-2
                    $('#step-2-submit').click(function(){
                        if(mobile){
                            if(/^[0-9]{6}$/.test($("#authcode").val())){
                                $.post('/index.php/send/validate_authcode',{mobile:mobile,authcode:$("#authcode").val(),action:'register'},function(rs){
                                    if(rs.status == '10000'){
                                        $(".authcode-tip").text('验证码正确!');
                                        authcode = $("#authcode").val();
                                        goto_page(3);
                                    }else{
                                        $(".authcode-tip").text(rs.msg);
                                    }
                                },'json');
                            }else{
                                $(".authcode-tip").text('请输入正确格式的手机验证码!');
                            }
                        }else{
                            goto_page(1);
                        }
                    });
                    //step-3
                    var nric_check = function(flag){
                        if(company_user_nric)return;
                        if(/^[0-9]{15,18}$/.test($("#company-user-nric").val())){
                            if(is_nric($("#company-user-nric").val())){
                                $(".company-user-nric-tip").text('ok!');
                                company_user_nric = $("#company-user-nric").val();
                            }else{
                                company_user_nric = '';
                                $(".company-user-nric-tip").text(rs.msg);
                                $("#company-user-nric").focus();
                            }
                        }else{
                            if(flag)$(".company-user-nric-tip").text('请输入正确格式的身份证号码!');
                        }
                    };
                    $("#company-user-nric").keyup(function(){nric_check();}).blur(function(){nric_check(1);});
                    $('#step-3-submit').click(function(){
                        if( !mobile){
                            goto_page(1);
                            return false;
                        }
                        if( !authcode){
                            goto_page(2);
                            return false;
                        }
                        if($("#company-name").val() == ''){
                            $(".company-name-tip").text('请输入公司名称!');
                            $("#company-name").focus();
                            return false;
                        }else{
                            company_name = $("#company-name").val();
                        }
                        if($("#company-code").val() == ''){
                            $(".company-code-tip").text('请输入公司执照注册号!');
                            $("#company-code").focus();
                            return false;
                        }else{
                            company_code = $("#company-code").val();
                        }
                        if($("#company-bank-name").val() == ''){
                            $(".company-bank-name-tip").text('请输入公司开户银行!');
                            $("#company-bank-name").focus();
                            return false;
                        }else{
                            company_bank_name = $("#company-bank-name").val();
                        }
                        if($("#company-bank-account").val() == ''){
                            $(".company-bank-account-tip").text('请输入公司开户银行帐号!');
                            $("#company-bank-account").focus();
                            return false;
                        }else{
                            company_name = $("#company-bank-account").val();
                        }
                        if($("#company-user-name").val() == ''){
                            $(".company-user-name-tip").text('请输入公司联系人姓名!');
                            $("#company-user-name").focus();
                            return false;
                        }else{
                            company_name = $("#company-user-name").val();
                        }
                        if( !company_user_nric){
                            nric_check(1);
                            if(!company_user_nric) return false;
                        }
                        goto_page(4);
                    });

                    //文件上传的处理
                    $('.file-upload').change(function(){
                        var that = this;
                        $(that).siblings('input[type="text"]').val($(that).val());
                        if($(that).val() != ''){
                            jquery_ajax_upload_file($(this).attr('id'),'/index.php/login/company_register',function(per,rs){
                                $('.'+$(that).attr('id')+'-tips').html(per+'%');
                                if(rs !== false){
                                    if(rs.status == '10000'){
                                        $('#'+$(that).attr('id')+'-flag').val(1);
                                        $('.'+$(that).attr('id')+'-tips').html('100%');
                                    }
                                    $('.'+$(that).attr('id')+'-tips').append(rs.msg);
                                }
                            });
                        }
                    });
                    $("#step-4-submit").click(function(){
                        if( !mobile){ goto_page(1);return false;}
                        if( !authcode){ goto_page(2);return false;}
                        if( !company_name || company_code || company_bank_name || company_bank_account || company_user_name || company_user_nric){ goto_page(3);return false;}
                        if($('#business-license-flag').val() == 0){
                            $('#business-license-tips').html('请选择文件!');
                            return false;
                        }
                        if($('#account-permit-flag').val() == 0){
                            $('#account-permit-tips').html('请选择文件!');
                            return false;
                        }
                        if($('#nric-copy-flag').val() == 0){
                            $('#nric-copy-tips').html('请选择文件!');
                            return false;
                        }
                        goto_page(5);
                    });
                    goto_page(5);
                });
            });
        }

</script>
</html>