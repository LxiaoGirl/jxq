<!DOCTYPE html>
<html>
<head>
    <title>聚雪球_网加金服_P2P理财首选互联网金融P2P网贷平台_100元即可投资!</title>
	<?php $this->load->view('common/head_file'); ?>
</head>
<body>

    <!--header-->
    <?php $this->load->view('common/head'); ?>
    <!--header-->
<div class="row">
    <div class="find_pw">
        <h1>找回密码</h1>
        <p>请输入一个新的密码</p>
        <form id="reg_2" action="" method="" accept-charset="utf-8" onsubmit="return false;">
        <div class="inp_pit">
            <div class="inp">
                <img src="../../../../assets/images/passport/zc_suo.png">
                <input class="reg_sj js_mm" type="password" name="sjh" value="" placeholder="请为您的账号设置一个密码" maxlength="20" />
            </div>
            <div class="pit"></div>
        </div>
        <div class="inp_pit">
            <div class="inp">
                <img src="../../../../assets/images/passport/zc_suo.png">
                <input class="reg_sj js_cfmm" type="password" name="sjh" value="" placeholder="请重复输入上面的密码" maxlength="20" />
            </div>
            <div class="pit"></div>
        </div>
        <div class="but">
            <button  type="submit" id="reg_xyb" class="ajax-submit-button" data-loading-msg="密码重置中...">下一步<i></i></button>
        </div>
        </form>
    </div>
</div>
<!--footer-->
    <?php $this->load->view('common/footer'); ?>
<!--footer-->
<script type="text/javascript">
	seajs.use(['jquery','sys','jqform','validator'],function(){
    var pit_4=0,pit_5=0;
    $('.but').find('#reg_xyb').click(function () {
        if(pit_4 == 0){
            $('.js_mm').focus();
            return false;
        }
        if(pit_5 == 0){
            $('.js_cfmm').focus();
            return false;
        }
        if((pit_4+pit_5)==2){
            $.ajax({
                type: 'POST',
                async: false,
                url: '<?php echo site_url('login/forget_s2'); ?>',
                data: {'password':$('.js_mm').val()},
                dataType: 'json',
                error:function(){
                    wsb_alert('服务器繁忙请稍后重试!',2);
                },
                success: function (result) {
                    if(result.status == '10000') {
                        window.location.href="<?php echo site_url('login/forget_s3'); ?>";
                    }else{
                        wsb_alert(result.msg,2);
                    }
                }
            });
        }
    });
    $('#reg_2').validate({
        '.js_mm':  {
            filtrate: 'required min6',
            degree: true,
            callback: function (index, other) {
                var tip = this.parent().parent().find('.pit').eq(0),
                    text = '<i class="icon-tip-yes"></i>';
                if (index === 0) {
                    text = '<i class="icon-tip-no"></i>请输入密码';
                    pit_4=0;
                } else if (index === 1) {
                    text = '<i class="icon-tip-no"></i>建议密码由6位及以上数字、字母和特殊字符组成。';
                    pit_4=0;
                } else {
                    text = '安全程度：<div class="item z-sel">弱</div><div class="item ' + (other !== 0 ? 'z-sel' : '') + '">中</div><div class="item ' + (other === 1 ? 'z-sel' : '') + '">强</div>';
                    pit_4=1;
                }
                tip.html(text);
            }
        },
        '.js_cfmm': {
            filtrate: 'required min6',
            relevance: '.js_mm',
            callback: function (index, other) {
                var tip = this.parent().parent().find('.pit').eq(0),
                    text = '<i class="icon-tip-yes"></i>';
                    pit_5=1;
                if (index === 0) {
                    text = '<i class="icon-tip-no"></i>请确认密码';
                    pit_5=0;
                } else if (!other) {
                    text = '<i class="icon-tip-no"></i>两次填写的密码不一致';
                    pit_5=0;
                }
                tip.html(text);
            }
        },
    });
});
</script>
</body>
</html>