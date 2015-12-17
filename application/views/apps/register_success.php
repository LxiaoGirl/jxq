<!DOCTYPE html>
<html lang="en">
<head>
    <title>注册成功</title>
    <?php $this->load->view('common/apps/app_head') ?>
</head>
<body>
<!-- 公共头部导航-->


<div class="container-fluid">
    <p class="text-center cartoon">
        <img width="80%" src="/assets/images/app/cartoon_ok.png" alt=""/>
    </p>

    <p class="text-center">是否立即进行实名认证？</p>

    <div class="col-lg-12 mt20  mb20" id="real-name">
        <a href="javascript:void(0);" class="btn btn-danger   btn-lg btn-block">是的，现在就去认证</a>
    </div>
    <p class="text-center  mb20">
        <a href="#" id="ok" class="c_blue">不了，我想先逛逛</a>
    </p>
</div>

<?php $this->load->view('common/apps/app_footer') ?>
</body>
<script>
    $(function () {
        $("#real-name").on('tap', function () {
            window.location.replace('<?php echo site_url('apps/home/real_name'); ?>');
        });
        $("#ok").on('tap', function () {
            check_to_index();
        });
    })
</script>
</html>
