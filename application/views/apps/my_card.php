<!DOCTYPE html>
<html>
<head lang="en">
    <title>我的银行卡</title>
    <?php $this->load->view('common/apps/app_head') ?>
</head>
<body>
<div class="container-fluid">
    <?php if (!empty($account)): ?>
        <?php foreach ($account as $v): ?>
            <div class="bg_white row">
                <table width="100%" border="0" cellspacing="0" cellpadding="0">
                    <tbody>
                    <tr>
                        <td width="50">
                            <img src="/assets/images/app/yingh/<?php echo $v['code']; ?>.png" width="50" height="50"
                                 alt="">
                        </td>
                        <td>
                            <div class="f18 c_333"
                                 style="height:30px; overflow:hidden; line-height:30px;"><?php echo $v['bank_name']; ?>
                                (尾号<?php echo substr($v['account'], -4); ?>)
                            </div>
                            <p class="c_888" style="height:20px; overflow:hidden; line-height:20px;">
                                <?php echo $v['content']; ?></p>
                        </td>
                        <td width="70">
                            <button type="button" class="btn btn-danger"
                                    onclick="check_to_login('<?php echo site_url('apps/home/my_card_unbind?card_no=' . $v['card_no']); ?>')">
                                解绑申请
                            </button>
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <p class="text-center  c_888 mt20">
            您还没有绑定银行卡呢~ :-(
        </p>
        <div class="col-lg-12 mb20">
            <a id="bind-card" href="#" class="btn btn-danger btn-lg btn-block">
                去绑定一张银行卡
            </a>
        </div>
    <?php endif; ?>
</div>
</body>
<?php $this->load->view('common/apps/app_footer') ?>
<script>
    $(function () {
        $('#bind-card').on('tap', function () {
            check_to_login('', true);
            //验证 资金密码
            if ('<?php echo profile('security') ?>') {
                window.location.replace('/index.php/apps/home/recharge_form');
            } else {
                window.location.replace('/index.php/apps/home/security');
            }
        })
    });
</script>
</html>