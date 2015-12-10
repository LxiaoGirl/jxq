<!DOCTYPE html>
<html>
<head lang="en">
    <title>我要借款</title>
    <?php $this->load->view('common/apps/app_head') ?>
</head>
<body>
<style>
    html, body {
        background: #fff;
    }
</style>
<div class="container-fluid">
    <table class="icons_table" width="100%" border="0" cellspacing="0" cellpadding="0">
        <tbody>
        <tr>
            <td width="50%" valign="middle">
                <a href="javascript:void(0);" url="<?php echo site_url('apps/home/borrow?type=1'); ?>">
                    <span class="iconfont c_green icon-yinxingqia"></span>

                    <h2 class="c_green">信用借款</h2>

                    <p>收益高 银行承兑</p>
                </a>
            </td>
            <td width="50%" valign="middle">
                <a href="javascript:void(0);" url="<?php echo site_url('apps/home/borrow?type=3'); ?>">
                    <span class="iconfont c_blue2 icon-house"></span>

                    <h2 class="c_blue2">实物抵押</h2>

                    <p>专业投资首选</p>
                </a>
            </td>
        </tr>
        <tr>
            <td width="50%" valign="middle">
                <a href="javascript:void(0);" url="<?php echo site_url('apps/home/borrow?type=2'); ?>">
                    <span class="iconfont c_orange icon-dunpai"></span>

                    <h2 class="c_orange">担保借贷</h2>

                    <p>先消费 后买单</p>
                </a>
            </td>
            <td width="50%" valign="middle">&nbsp;

            </td>
        </tr>
        </tbody>
    </table>
</div>
</body>
<?php $this->load->view('common/apps/app_footer') ?>
<script>
    $(function () {
        $("td a").on('tap', function () {
            index_check_to_login($(this).attr('url'), true);
        })
    });
</script>
</html>