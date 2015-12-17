<!DOCTYPE html>
<html>
<head lang="en">
    <title>借款申请成功</title>
    <?php $this->load->view('common/apps/app_head') ?>
</head>
<body>
<div class="container-fluid">
    <p class="text-center cartoon">
        <img width="80%" src="/assets/images/app/cartoon_ok.png" alt="">
    </p>

    <p class="text-center mb20">
        您的借款申请已成功提交，我司风控人员会在48小时内与您取得联系。
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