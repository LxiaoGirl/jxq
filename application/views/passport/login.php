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
                            <img src="../../../../assets/images/passport/yh_icon.png">
                            <input class="reg_sj js_mobile" type="text" name="sjh" value="<?php echo $mobile; ?>" placeholder="手机号/用户名/邮箱" maxlength="50" />
                        </div>
                        <div class="pit"></div>
                    </div>
                    <div class="inp_pit">
                        <div class="inp">
                            <img src="../../../../assets/images/passport/zc_suo.png">
                            <input class="reg_sj js_mm" type="password" name="sjh" value="<?php echo $password; ?>" placeholder="请输入密码" maxlength="20" />
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
seajs.use(['jquery','sys','jqform','validator'],function(){
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
        var tip = $('js_mm').parent().parent().find('.pit').eq(0),
            text = '';
        if ($('js_mm').val() == '') {
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
        if((pit_1+pit_2)==2){
            $.ajax({
                type: 'POST',
                async: false,
                url: '<?php echo site_url('login'); ?>',
                data: {'mobile':$('.js_mobile').val(),'password':$('.js_mm').val(),'remember':$('.js_agr').prop('checked')?1:0},
                dataType: 'json',
                error:function(){
                    wsb_alert('服务器繁忙请稍后重试!',2);
                },
                success: function (result) {
	                if(result.status == '10000') {
                        wsb_alert(result.msg,1,result.url);
	                }else{
                        wsb_alert(result.msg,2);
	                }
                }
            });
        }
    });
   /* $('#reg_1').validate({
        '.js_mobile': {
            filtrate: 'required mobile',
            callback: function (index) {
                var tip = this.parent().parent().find('.pit').eq(0),
                    text = '';
                if (index === 0) {
                    text = '<i class="icon-tip-no"></i>请输入手机号';
	                pit_1=0;
                } else if (index === 1) {
                    text = '<i class="icon-tip-no"></i>请输入正确的手机号码';
	                pit_1=0;
                } else {
                    pit_1=1;
                    text = '<i class="icon-tip-yes"></i>';
                }
                tip.html(text);
            }
        },
        '.js_mm': {
            filtrate: 'required',
            callback: function (index) {
                var tip = this.parent().parent().find('.pit').eq(0),
                    text = '';
                if (index === 0) {
                    text = '<i class="icon-tip-no"></i>请输入密码';
	                pit_2=0;
                } else {
                    pit_2=1;
                    text = '<i class="icon-tip-yes"></i>';
                }
                tip.html(text);
            }
        },
    });*/
});
</script>
</body>
</html>