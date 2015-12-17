<!DOCTYPE html>
<html>
<head lang="en">
    <title>充值</title>
    <?php $this->load->view('common/mobiles/app_head') ?>
</head>
<body>

<div class="container-fluid">
    <h4 style="text-align: center;margin-top: 45%;">正在返回个人中心...</h4>
</div>
</body>
<?php $this->load->view('common/mobiles/app_footer') ?>
<script>
    $(function () {
        window.top.location.replace('<?php echo site_url('mobiles/home/my_center'); ?>');
    })
</script>
</html>