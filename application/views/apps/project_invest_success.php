<!DOCTYPE html>
<html>
<head lang="en">
    <title>投资成功</title>
    <?php $this->load->view('common/apps/app_head') ?>
</head>
<body>
<div class="container-fluid">
    <p class="text-center cartoon">
        <img width="80%" src="/assets/images/app/cartoon_ok.png" alt="">
    </p>

    <div class="col-lg-12 mb20" id="ok">
        <a href="javascript:void(0);" class="btn btn-danger btn-lg btn-block">
            继续投标
        </a>
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