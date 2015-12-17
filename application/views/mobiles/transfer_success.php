<!DOCTYPE html>
<html>
<head lang="en">
    <title>提现成功</title>
    <?php $this->load->view('common/mobiles/app_head') ?>
</head>
<body>
<!-- 公共头部导航-->
<?php $this->load->view('common/mobiles/app_common_head') ?>

<div class="container-fluid">
    <div class="row">
        <table style="margin-left:auto; margin-right:auto;" border="0" cellspacing="0" cellpadding="0">
            <tbody>
            <tr>
                <td align="right">
                    <div style="background:#88cb5a; width:60px; height:60px; line-height:60px; border-radius:50%"
                         class="c_fff text-center">
                        <span class="iconfont icon-tick" style="font-size:40px;"></span>
                    </div>
                </td>
                <td>
                    <div class="f18">提现成功：<span class="c_red"><?php echo $amount; ?>元</span></div>
                    <div class="f16 c_666">实际到账：<?php echo $amount; ?>元</div>
                    <div class="c_999"><?php echo date('Y-m-d H:i:s', time()); ?></div>
                </td>
            </tr>
            </tbody>
        </table>
        <div class="text-center">预计到账时间：1-3个工作日</div>
    </div>
    <div class="col-lg-12 mt20  mb20">
        <a href="#" onclick="check_to_index();" class="btn btn-danger   btn-lg btn-block">
            完成
        </a>
    </div>
</div>
</body>
<?php $this->load->view('common/mobiles/app_footer') ?>
</html>