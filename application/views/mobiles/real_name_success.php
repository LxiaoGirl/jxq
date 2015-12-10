<!DOCTYPE html>
<html>
<head lang="en">
    <title>实名认证成功</title>
    <?php $this->load->view('common/mobiles/app_head') ?>
</head>
<body>
<!-- 公共头部导航-->
<?php $this->load->view('common/mobiles/app_common_head') ?>

<div class="container-fluid">
    <p class="text-center cartoon">
        <img width="80%" src="/assets/images/app/cartoon_ok.png" alt=""/>
    </p>

    <div class="col-lg-12 mb20">
        <a href="#" onclick="check_to_index();" class="btn btn-danger   btn-lg btn-block">现在去投资</a>
    </div>
</div>

</body>
<?php $this->load->view('common/mobiles/app_footer') ?>
</html>