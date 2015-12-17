<!DOCTYPE html>
<html>
<head lang="en">
    <title>充值</title>
    <?php $this->load->view('common/apps/app_head') ?>
</head>
<body>
<div class="container-fluid">
    <?php if (!empty($card)): ?>
        <?php foreach ($card as $v): ?>
            <div class="bg_white row"
                 onclick="javascript:window.location.href='<?php echo site_url('apps/home/recharge_form?card_no=' . $v['card_no']); ?>'">
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
                        <td width="40">
                            <span class="iconfont icon-xiangyou1"></span>
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <div class="col-lg-12 mt20  mb20">
            <a href="<?php echo site_url('apps/home/recharge_form'); ?>" class="btn btn-danger   btn-lg btn-block">
                添加银行卡
            </a>
        </div>
    <?php endif; ?>
</div>
</body>
<?php $this->load->view('common/apps/app_footer') ?>
</html>