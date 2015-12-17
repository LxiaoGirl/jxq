<!DOCTYPE html>
<html>
<head lang="en">
    <title>修改成功</title>
    <?php $this->load->view('common/apps/app_head') ?>
</head>
<body>
<div class="container-fluid">
    <p class="text-center cartoon">
        <img width="80%" src="/assets/images/app/cartoon_ok.png" alt="">
    </p>

    <p class="text-center mb20">
        请妥善保管您的密码
    </p>

    <div class="col-lg-12 mb20">
        <button id="ok" type="button" class="btn btn-danger btn-lg btn-block">
            完 成
        </button>
    </div>
</div>
</body>
<?php $this->load->view('common/apps/app_footer') ?>
<script>
    $(function () {
        $("#ok").on('tap', function () {
            check_to_index();
        })
    });
</script>
</html>