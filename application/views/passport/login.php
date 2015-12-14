<!DOCTYPE html>
<html>
<head>
    <title>聚雪球_网加金服_P2P理财首选互联网金融P2P网贷平台_100元即可投资!</title>
	<!--	加载头部样式文件-->
	<?php $this->load->view('common/head_file'); ?>
</head>
<body>
<!--header-->
<!--	加载头部文件-->
<?php $this->load->view('common/head'); ?>
<!--header-->
<div class="register">
    <div class="row">
        <div class="reg_body">
            <div class="pic"><img src="../../../../assets/images/bigpic/mian.jpg"></div>
            <div class="kuangjia">
                <div class="step_1">
                <form id="reg_1" action="" method="" accept-charset="utf-8" onsubmit="return false;">
                    <div class="title">登录聚雪球</div>
                    <div class="inp_pit">
                        <div class="inp">
                            <img class="inpr hs" src="../../../../assets/images/passport/yh_icon.png">
                            <img class="inpr ls" src="../../../../assets/images/passport/yh_icon_ok.png">
                            <input class="reg_sj js_mobile" type="text" name="sjh" value="<?php echo $mobile; ?>" placeholder="手机号/用户名/邮箱" maxlength="50" />
                        </div>
                        <div class="pit"></div>
                    </div>
                    <div class="inp_pit">
                        <div class="inp">
                            <img class="inpr hs" src="../../../../assets/images/passport/zc_suo.png">
                            <img class="inpr ls" src="../../../../assets/images/passport/zc_suo_ok.png">
                            <input class="reg_sj js_mm" type="password" name="sjh" value="<?php echo $password; ?>" placeholder="请输入密码" maxlength="20" />
                        </div>
                        <div class="pit"></div>
                    </div>
                    <div class="inp_pit captcha" style="display: none;">
                        <div class="inp">
                            <input class="pic_yzm js_picyzm" type="text" name="tpyzm" value="" placeholder="输入验证码" maxlength="6"/>
                            <span class="pic_yzm">
	                            <img id="imgCode" src="<?php echo site_url('send/captcha'); ?>" width="78" height="34" alt="验证码"
                                     onclick="javascript:this.src = '<?php echo site_url('send/captcha'); ?>?t='+ new Date().valueOf()"
                                     title="点击更换验证码"/>
                            </span>
                            <span class="pic_reset"><img src="../../../../assets/images/passport/pic_reset.png" onclick="javascript:document.getElementById('imgCode').src = '<?php echo site_url('send/captcha'); ?>?t='+ new Date().valueOf()"></span>
                        </div>
                        <div class="pit"></div>
                    </div>
                    <div class="chick_xy">
                        <div class="nr_xy">
                            <input class="check js_agr" type="checkbox" name="" value="1" <?php if($mobile): ?>checked<?php endif; ?>>记住我<span class="fr">密码忘了？<a href="<?php echo site_url('login/forget') ?>">点此找回</a></span>
                        </div>
                    </div>
                    <div class="but">
                        <button  type="submit" id="reg_xyb" class="ajax-submit-button" data-loading-msg="登录中...">登  录<i></i></button>
                    </div>
                    <div class="yyzhdl"><a class="fr" href="<?php echo site_url('login/register') ?>">免费注册</a></div>
                </form>
                </div>
            </div>
        </div>
        <div class="bot_wz">
        国资参与 银行监管 ● 预期年化收益率13.2% ● 100%本息保障
        </div>
    </div>
</div>
<!--footer-->
    <?php $this->load->view('common/footer'); ?>
<!--footer-->
<script type="text/javascript">
    var captcha_enable = false;
    var captcha = '';
seajs.use(['jquery','sys'],function(){
    //INPUT框变色
    $('.inp').find('input').focus(function(){
        $(this).siblings('.ls').show();
        $(this).addClass('hav');
    });
    $('.inp').find('input').blur(function(){
        if($.trim($(this).val())==''){
            $(this).siblings('.ls').hide();
            $(this).removeClass('hav');
        }
    });
    //INPUT框变色
    var pit_1=0,pit_2=0;
    $('.but').find('#reg_xyb').click(function () {
	    //提交 的时候 验证一下 记住密码时的处理
        var tip = $('.js_mobile').parent().parent().find('.pit').eq(0),
            text = '';
        if ($('.js_mobile').val() == '') {
            text = '<i class="icon-tip-no"></i>请输入手机号/用户名/邮箱';
            pit_1=0;
        }
//        else if (! /^1[0-9]{10}$/.test($('.js_mobile').val())) {
//            text = '<i class="icon-tip-no"></i>请输入正确的手机号码';
//            pit_1=0;
//        }
        else {
            pit_1=1;
            text = '<i class="icon-tip-yes"></i>';
        }
        tip.html(text);
        if(pit_1 == 0){
            $('.js_mobile').focus();
            return false;
        }
        var tip = $('.js_mm').parent().parent().find('.pit').eq(0),
            text = '';
        if ($('.js_mm').val() == '') {
            text = '<i class="icon-tip-no"></i>请输入密码';
            pit_2=0;
        } else {
            pit_2=1;
            text = '<i class="icon-tip-yes"></i>';
        }
        tip.html(text);

        if(pit_2 == 0){
            $('.js_mm').focus();
            return false;
        }

        if(captcha_enable){
            var tip = $('.js_picyzm').parent().parent().find('.pit').eq(0),
                text = '';
            if($('.js_picyzm').val() == ''){
                text = '<i class="icon-tip-no"></i>请输入验证码';
                tip.html(text);
                $('.js_picyzm').focus();
                return false;
            }else{
                captcha = $('.js_picyzm').val();
            }
        }
        if((pit_1+pit_2)==2){
            $.ajax({
                type: 'POST',
                async: false,
                url: '<?php echo site_url('login'); ?>',
                data: {'mobile':$('.js_mobile').val(),'password':$('.js_mm').val(),'captcha':captcha,'remember':$('.js_agr').prop('checked')?1:0},
                dataType: 'json',
                error:function(){
                    wsb_alert('服务器繁忙请稍后重试!',2);
                },
                success: function (result) {
	                if(result.status == '10000') {
                        wsb_alert(result.msg,1,result.url);
	                }else{
                        if(result.status == '10002'){
                            captcha_enable = true;
                            $(".captcha").show();
                        }
                        wsb_alert(result.msg,2);
	                }
                }
            });
        }
    });

});
</script>
</body>
</html>