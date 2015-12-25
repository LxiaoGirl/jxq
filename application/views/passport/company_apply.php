<!DOCTYPE html>
<html>
<head>
    <title>聚雪球_网加金服_P2P理财首选互联网金融P2P网贷平台_100元即可投资!</title>
    <?php $this->load->view('common/head_file'); ?>
</head>
<body>
<!--	加载头部文件-->
<?php $this->load->view('common/head'); ?>
    <div id="step-3" class="row gs_zc1 gs_zc2 step">
    <h1>企业用户注册</h1>
    <img src="../../../../assets/images/passport/step_3.jpg" alt="">
    <form action="" onsubmit="return false;">
        <h2>企业信息</h2>
        <p>
            <span class="z1">企业名称</span>
            <span class="z2"><input type="text" class="ifhav" name="company_name" id="company-name" placeholder="请输入企业名称" maxlength="50" value="<?php echo isset($info['company_name'])?$info['company_name']:''; ?>" <?php if($info['company_name']): ?>readonly disabled<?php endif; ?>></span>
            <span class="z3"></span>
        </p>
        <p class="tip">
            <span class="z1"></span>
            <span class="z2 company-name-tip">需与营业执照上的名称完全一致，信息审核成功后，此项不可修改。</span>
            <span class="z3"></span>
        </p>
        <p>
            <span class="z1">营业执照注册号</span>
            <span class="z2"><input type="text" class="ifhav" name="company_code" id="company-code" placeholder="请输入营业执照注册号" maxlength="25" value="<?php echo isset($info['company_code'])?$info['company_code']:''; ?>" <?php if($info['company_code']): ?>readonly disabled<?php endif; ?> /></span>
            <span class="z3"></span>
        </p>
        <p class="tip">
            <span class="z1"></span>
            <span class="z2 company-code-tip">请输入15位营业执照注册号或18位的统一社会信用代码，信息审核成功后，此项不可修改。</span>
            <span class="z3"></span>
        </p>
        <p>
            <span class="z1">企业开户银行</span>
            <span class="z2"><input type="text" class="ifhav" name="company_bank_name" id="company-bank-name" placeholder="请输入企业开户银行" maxlength="20" value="<?php echo isset($info['company_bank_name'])?$info['company_bank_name']:''; ?>" <?php if($info['company_bank_name']): ?>readonly disabled<?php endif; ?>/></span>
            <span class="z3"></span>
        </p>
        <p class="tip">
            <span class="z1"></span>
            <span class="z2 company-bank-name-tip">需与企业银行开户许可证的开户银行完全一致，信息审核成功后，此项不可修改。</span>
            <span class="z3"></span>
        </p>
        <p>
            <span class="z1">银行账号</span>
            <span class="z2"><input type="text" class="ifhav" name="company_bank_account" id="company-bank-account" placeholder="请输入企业开户银行账号" value="<?php echo isset($info['company_bank_account'])?$info['company_bank_account']:''; ?>" <?php if($info['company_bank_account']): ?>readonly disabled<?php endif; ?> /></span>
            <span class="z3"></span>
        </p>
        <p class="tip">
            <span class="z1"></span>
            <span class="z2 company-bank-account-tip">需与企业银行开户许可证的开户账号完全一致，信息审核成功后，此项不可修改。</span>
            <span class="z3"></span>
        </p>
        <h2>企业联系人资料</h2>
        <p>
            <span class="z1">联系人姓名</span>
            <span class="z2"><input type="text" class="ifhav" name="company_user_name" id="company-user-name" placeholder="请输入企业联系人姓名" maxlength="10" value="<?php echo profile('real_name')?profile('real_name'):''; ?>" <?php if(profile('real_name')): ?>readonly disabled<?php endif; ?> /></span>
            <span class="z3"></span>
        </p>
        <p class="tip">
            <span class="z1"></span>
            <span class="z2 company-user-name-tip">需与下一步提交的证照信息一致，点击下一步会保存此项信息，保存后不可修改。</span>
            <span class="z3"></span>
        </p>
        <p>
            <span class="z1">联系人身份证号码</span>
            <span class="z2"><input type="text" class="ifhav" name="company_user_nric" id="company-user-nric" placeholder="请输入企业联系人身份证号码" maxlength="18" value="<?php echo profile('nric')?profile('nric'):''; ?>"  <?php if(profile('nric')): ?>readonly disabled<?php endif; ?> /></span>
            <span class="z3"></span>
        </p>
        <p class="tip">
            <span class="z1"></span>
            <span class="z2 company-user-nric-tip">需与下一步提交的证照信息一致，点击下一步会保存此项信息，保存后不可修改。</span>
            <span class="z3"></span>
        </p>
        <p>
            <span class="z1"></span>
            <span class="z2 sbut"><button type="button" class="ls ajax_submit-button" id="step-3-submit" data-loading-msg="提交中...">下一步</button></span>
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
                <input type="text" class="ifhav" id="business-license-show" readonly value="<?php echo isset($info['business_license'])?$info['business_license']:''; ?>" />
                <input type="hidden" id="business-license-flag" value="<?php echo isset($info['business_license'])?1:0; ?>"  />
            </span>
            <span class="z3 business-license-tips">
            <a href="javascript:void(0);" class="green" style="">
            <input type="file" name="business_license" id="business-license" class="file-upload"/>选择文件
            </a>
            <i><i class="percent"></i></i>
            <em class="result"></em>
            </span>
        </p>
        <p class="business-license-cssb" style="line-height:20px;"></p>
        <p>
            <span class="z1">银行开户许可证</span>
            <span class="z2">
                <input type="text" class="ifhav" id="account-permit-show" readonly value="<?php echo isset($info['account_permit'])?$info['account_permit']:''; ?>" />
                <input type="hidden" id="account-permit-flag" value="<?php echo isset($info['account_permit'])?1:0; ?>"  />
            </span>
            <span class="z3 account-permit-tips">
            <a href="javascript:;" class="green" style="">
            <input type="file" name="account_permit" id="account-permit" class="file-upload" />选择文件
            </a>
            <i><i class="percent"></i></i>
            <em class="result"></em>
            </span>
        </p>
        <p class="account-permit-cssb" style="line-height:20px;"></p>
        <p>
            <span class="z1">企业联系人身份证复印件</span>
            <span class="z2">
                <input type="text" class="ifhav" id="nric-copy-show"  readonly value="<?php echo isset($info['nric_copy'])?$info['nric_copy']:''; ?>" />
                <input type="hidden" id="nric-copy-flag" value="<?php echo isset($info['nric_copy'])?1:0; ?>"  />
            </span>
            <span class="z3 nric-copy-tips">
            <a href="javascript:;" class="green" style="">
            <input type="file" name="nric_copy" id="nric-copy" class="file-upload" />选择文件
            </a>
            <i><i class="percent"></i></i>
            <em class="result"></em>
            </span>
        </p>
        <p class="nric-copy-cssb" style="line-height:20px;"></p>
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
                <p>企业名称：<font class="company-name-show"></font></p>
                <p>营业执照号码：<font class="company-code-show"></font></p>
                <p>企业开户银行：<font class="company-bank-name-show"></font></p>
                <p>企业银行账号：<font class="company-bank-account-show"></font></p>
            </div>
        </div>
        <div class="fr">
            <p class="p1">当前账户余额：<span id="balance"><?php echo isset($balance)?$balance:'0.00'; ?></span>元 <button class="recharge-button" type="button" onclick="window.location.href='/index.php/pay/pay/index?amount=300&recharge_no=<?php echo $recharge_no; ?>'">充值</button><button type="button" class="recharge-refresh">刷新余额</button></p>
            <p class="p2">企业信息审核由第三方公司负责审核，需要收取审核费300元。如果由于您提供的资料错误导致审核失败，再次提交审核时需再次缴纳300元审核费。</p>
        </div>
    </div>

    <p class="tc"><button class="hs" type="button" onclick="goto_page(4);">上一步</button><button class="ls" type="button" id="step-5-submit">提交审核</button></p>
    <div class="popbj recharge-confirm" style="display: none;"></div>
    <div class="pop recharge-confirm" style="display: none;">
        <div class="popnr">
            <p class="title">申请提示<font class="fr close">×</font></p>
            <p>请再次确认您的银行账号信息，一旦提交后，您将无法修改您的开户银行和开户银行账号。</p>
            <p>开户银行：<font class="company-bank-name-show"></font></p>
            <p>开户银行账号：<font class="company-bank-account-show"></font></p>
            <p class="red">认证费用：300元</p>
            <p><button type="button" class="ls ajax-submit-button" id="recharge-confirm-submit" data-loading-msg="申请提交中...">提交</button></p>
        </div>
    </div>
</div>

    <div id="step-6" style="display: none;" class="row gs_zc4 step">
    <h1>企业用户注册</h1>
    <img src="../../../../assets/images/passport/step_5.jpg" alt="">
    <h2>您已成功完成企业注册申请！</h2>
    <p class="ti411">企业名称：<font class="company-name-show"></font></p>
    <p class="ti411">营业执照号码：<font class="company-code-show"></font></p>
    <p class="ti411">企业开户银行：<font class="company-bank-name-show"></font></p>
    <p class="ti411">企业银行账号：<font class="company-bank-account-show"></font></p>
    <p class="tc"><button style="width:250px;" class="ls" type="button" onclick="window.location.href='/index.php/user/user/account_home'">进入个人中心</button></p>
</div>
</body>
<!--	加载头部文件-->
<?php $this->load->view('common/footer'); ?>
<script type="text/javascript">
    var goto_page = function(num){
        num = parseInt(num) || 1;
        $('.step').hide();
        $('#step-'+num).show();
    };
    seajs.use(['jquery','sys','wsb_sys'],function(){
        if(navigator.userAgent.indexOf("MSIE") > -1){
            wsb_alert('ie浏览器暂不支持本页文件上传,请使用谷歌火狐等其他浏览器',10);
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

            /*var goto_page = function(num){
                num = parseInt(num) || 1;
                $('.step').hide();
                $('#step-'+num).show();
            };*/

            $(function(){
                $('.pop').find('.close').click(function(){
                    $('.pop').fadeOut();
                    $('.popbj').fadeOut();
                });
                var company_name='',company_code='',company_bank_name='',company_bank_account='',
                    company_user_name='<?php echo profile('real_name')?profile('real_name'):''; ?>',company_user_nric='<?php echo profile('nric')?profile('nric'):''; ?>',nric_rz = false;
                var balance = parseFloat('<?php echo isset($balance)?$balance:0; ?>');
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
                        error:function(a,b,c){ }
                    });
                };
                //step-3
                var nric_check = function(flag){
                    if(company_user_nric  && flag)return;
                    if(/^[0-9]{15,18}$/.test($("#company-user-nric").val())){
                        if(is_nric($("#company-user-nric").val())){
                            $(".company-user-nric-tip").text('ok!');
                            company_user_nric = $("#company-user-nric").val();
                        }else{
                            company_user_nric = '';
                            $(".company-user-nric-tip").text('身份证号码格式不正确!');
                            $("#company-user-nric").focus();
                        }
                    }else{
			company_user_nric = '';
                        if(flag)$(".company-user-nric-tip").text('请输入正确格式的身份证号码!');
                    }
                };
                var user_name_check = function(flag){
                    if(company_user_name && flag)return;
                    if(/^[\u4E00-\u9FA5]{2,4}$/.test($('#company-user-name').val())){
                        $(".company-user-name-tip").text('ok!');
                        company_user_name = $("#company-user-name").val();
                    }else{
                        company_user_name = '';
                        if(flag)$(".company-user-name-tip").text('请输入正确的中文姓名!');
                    }
                };
                $("#company-user-nric").keyup(function(){nric_check();}).blur(function(){nric_check(1);});
                $("#company-user-name").keyup(function(){user_name_check();}).blur(function(){user_name_check(1);});
                $('#step-3-submit').click(function(){
                    if($("#company-name").val() == ''){
                        $(".company-name-tip").text('请输入公司名称!');
                        $("#company-name").focus();
                        return false;
                    }else{
                        company_name = $("#company-name").val();
                        $(".company-name-show").html(company_name);
                    }
                    if($("#company-code").val() == ''){
                        $(".company-code-tip").text('请输入公司执照注册号!');
                        $("#company-code").focus();
                        return false;
                    }else{
                        company_code = $("#company-code").val();
                        $(".company-code-show").html(company_code);
                    }
                    if($("#company-bank-name").val() == ''){
                        $(".company-bank-name-tip").text('请输入公司开户银行!');
                        $("#company-bank-name").focus();
                        return false;
                    }else{
                        company_bank_name = $("#company-bank-name").val();
                        $(".company-bank-name-show").html(company_bank_name);
                    }
                    if($("#company-bank-account").val() == ''){
                        $(".company-bank-account-tip").text('请输入公司开户银行帐号!');
                        $("#company-bank-account").focus();
                        return false;
                    }else{
                        company_bank_account = $("#company-bank-account").val();
                        $(".company-bank-account-show").html(company_bank_account);
                    }
                    if( !company_user_name){
                        user_name_check(1);
                        if(!company_user_name) return false;
                    }
                    if( !company_user_nric){
                        nric_check(1);
                        if(!company_user_nric) return false;
                    }
                    if(nric_rz){goto_page(4);return;}
                    $.post('/index.php/user/user/real_name',{
                        real_name:company_user_name,
                        nric:company_user_nric,
                        type:'company'
                    },function(rs){
                        if(rs.status == '10000'){
                            $('#company-user-name').attr('readonly',true).attr('disabled',true);
                            $('#company-user-nric').attr('readonly',true).attr('disabled',true);
                            nric_rz = true;
                            goto_page(4);
                        }else{
                            wsb_alert(rs.msg,2);
                        }
                    },'json');
                });
                //文件上传的处理
                $('.file-upload').change(function(){
                    var that = this;
	                var exp = $(that).val().substr($(that).val().lastIndexOf(".")+1).toLowerCase();
	                if('jpg,jpeg,png,gif'.indexOf(exp) == -1){
	                    wsb_alert('文件格式不正确,请选择jpg、jpeg、png、gif等格式文件',2);
	                    return false;
	                }
                    $('#'+$(that).attr('id')+'-show').val($(that).val());
                    if($(that).val() != ''){
                        $('.'+$(that).attr('id')+'-tips').find('.percent').width('0%');
                        $('.'+$(that).attr('id')+'-tips').find('.result').removeClass('cg').removeClass('sb');
                        $('.'+$(that).attr('id')+'-cssb').html('');
                        jquery_ajax_upload_file($(this).attr('id'),'/index.php/login/ajax_company_attachment_upload',function(per,rs){
                            $('.'+$(that).attr('id')+'-tips').find('.percent').width(per+'%');
                            if(rs !== false){
                                if(rs.status == '10000'){
                                    $('#'+$(that).attr('id')+'-flag').val(1);
                                    $('.'+$(that).attr('id')+'-tips').find('.percent').width('100%');;
                                    $('.'+$(that).attr('id')+'-tips').find('.result').addClass('cg');
                                }else{
                                    $('.'+$(that).attr('id')+'-tips').find('.result').addClass('sb');
					                $('.'+$(that).attr('id')+'-cssb').html(rs.msg.replace('<p>','').replace('</p>',''));
                                }
                            }
                        });
                    }
                });
                $("#step-4-submit").click(function(){
                    if( !company_name || !company_code || !company_bank_name || !company_bank_account || !company_user_name || !company_user_nric){ goto_page(3);return false;}
                        if($('#business-license-flag').val() == 0){
                            $('.business-license-tips').html('请选择文件!');
                            return false;
                        }
                        if($('#account-permit-flag').val() == 0){
                            $('.account-permit-tips').html('请选择文件!');
                            return false;
                        }
                        if($('#nric-copy-flag').val() == 0){
                            $('.nric-copy-tips').html('请选择文件!');
                            return false;
                        }
                    goto_page(5);
                });
                $("#step-5-submit").click(function(){
                    $('.recharge-confirm').fadeIn();
                });
                $("#recharge-confirm-submit").click(function(){
                    if(balance >= 300){
                        $.post('/index.php/login/company_apply',{
                            company_name:company_name,
                            company_code:company_code,
                            company_bank_name:company_bank_name,
                            company_bank_account:company_bank_account
                        },function(rs){
                            if(rs.status == '10000'){
                                $("#balance").text(rs.data);
                                goto_page(6);
                            }else{
                                wsb_alert(rs.msg,2);
                            }
                        },'json');
                    }else{
                        wsb_alert('余额不足请先充值后来提交!',2)
                    }
                });

                //充值刷新
                var refresh_func = function(obj){
                    $.post('/index.php/user/user/ajax_recharge_auto_refresh',{'recharge_no':'<?php echo $recharge_no; ?>'},function(rs){
                        if(rs.status == '10000'){
                            $("#balance").text(rs.data);
                            balance = rs.data;
                            wsb_alert('充值已成功',1);
                        }else if(rs.status == '10002' || rs.status == '10003'){
                            wsb_alert(rs.msg,2);
                        }else{
                            wsb_alert('充值尚未成功,如确认已充值扣费请稍后查询或联系客服人员!',3);
                            $(".recharge-refresh").unbind('click').bind('click',function(){
                                $(".recharge-refresh").unbind('click');
                                refresh_func($(this));
                            });
                        }
                    },'json');
                };
                var recharge_auto_refresh = function(recharge_no){
                    var recharge_fresh_time = 0;
                    var refresh_recharge = function(){
                        $('.cz_pop_msg').text('正在查询订单充值结果,请稍后...').show();
                        $.post('<?php echo site_url('user/user/ajax_recharge_auto_refresh'); ?>',{'recharge_no':'<?php echo $recharge_no; ?>'},function(rs){
                            if(rs.status == '10000'){
                                $('#balance').html(rs.data);
                                balance = rs.data;
                                clearTimeout(recharge_fresh_time);
                            } else if(rs.status == '10001'){
                                recharge_fresh_time = setTimeout(function(){refresh_recharge();},1000);
                            }else{
                                //什么也不做了
                            }
                        },'json');
                    };
                    //5秒后开始执行刷新
                    recharge_fresh_time = setTimeout(function(){refresh_recharge();},7000);
                };
                $('.recharge-button').click(function(){
                    $(".recharge-refresh").unbind('click').bind('click',function(){
                        $(".recharge-refresh").unbind('click');
                        refresh_func($(this));
                    });
                    recharge_auto_refresh();
                });
            });
        }
    });
</script>
</html>