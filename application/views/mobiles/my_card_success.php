<!DOCTYPE html>
<html>
<head lang="en">
    <title>解绑成功</title>
    <?php $this->load->view('common/mobiles/app_head') ?>
</head>
<body>
<!-- 公共头部导航-->
<?php $this->load->view('common/mobiles/app_common_head') ?>

<div class="container-fluid">
    <p class="text-center cartoon">
        <img width="80%" src="/assets/images/app/cartoon_ok.png" alt="">
    </p>

    <div class="col-lg-12 mb20">
        <a href="#" id="bind-card" class="btn btn-danger btn-lg btn-block">
            重新绑定银行卡
        </a>
    </div>
</div>

</body>
<?php $this->load->view('common/mobiles/app_footer') ?>
<script>
    $(function () {
        $('#bind-card').on('tap', function () {
            check_to_login('<?php echo site_url('mobiles/home/my_card_bind') ?>', true);
        })
    });
</script>
</html>