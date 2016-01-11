<!DOCTYPE html>
<html lang="en">
<head>
    <title>注册成功</title>
    <?php $this->load->view('common/mobiles/app_head') ?>
</head>
<body>
<!-- 公共头部导航-->
<?php $this->load->view('common/mobiles/app_common_head') ?>

<div class="container-fluid">
    <p class="text-center cartoon android">
        <img width="80%" src="/assets/images/app/cartoon_ok.png" alt="下载APP登陆赚钱更方便"/>
    </p>
    <p class="text-center android"><a style="font-size: 14px;text-decoration: underline;color: #2E9CE3;" href="https://www.juxueqiu.com/snowballapp.apk">下载APP登陆赚钱更方便</a></p>

    <p class="text-center cartoon ios" style="display:none;">
        <img width="80%" src="/assets/images/app/app.png" alt="按住二维码识别图片下载APP"/>
    </p>
    <p class="text-center ios" style="display:none;">按住二维码识别图片下载APP</p>


    <p class="text-center">是否立即进行实名认证？</p>

    <div class="col-lg-12 mt20  mb20" id="real-name">
        <a href="javascript:void(0);" class="btn btn-danger   btn-lg btn-block">是的，现在就去认证</a>
    </div>
    <p class="text-center  mb20">
        <a href="#" id="ok" class="c_blue">不了，我想先逛逛</a>
    </p>
</div>

<?php $this->load->view('common/mobiles/app_footer') ?>
</body>
<script>
    $(function () {
        //二维码的显示处理
        var u = navigator.userAgent;
        if (u.indexOf('iPhone') > -1 || u.indexOf('iPad') > -1) {//苹果手机 ipad
            $(".android").hide();
            $(".ios").show();
        }

        $("#real-name").on('tap', function () {
            window.location.replace('<?php echo site_url('mobiles/home/real_name'); ?>');
        });
        $("#ok").on('tap', function () {
            check_to_index();
        });
    })
</script>
</html>
