<!DOCTYPE html>
<html>
<head lang="en">
    <title>我要投资</title>
    <?php $this->load->view('common/apps/app_head') ?>
    <style>
        html, body {
            background: #fff;
        }
    </style>
</head>
<body>

<div class="container-fluid">
    <table class="icons_table" width="100%" border="0" cellspacing="0" cellpadding="0">
        <tbody>
        <tr>
            <td width="50%" valign="middle">
                <a href="javascript:void(0);"
                   data-url="<?php echo site_url('apps/home/project_list?category=1'); ?>">
                    <span class="iconfont c1_blue2"><img src="../../../assets/images/app/icon1.png" height="100%" width="100%"></span>

                    <h2 class="c_blue2">车贷宝</h2>

                    <p>收益高 银行承兑</p>
                </a>
            </td>
            <td width="50%" valign="middle">
                <a href="javascript:void(0);"
                   data-url="<?php echo site_url('apps/home/project_list?category=2'); ?>">
                    <span style="font-size:34px;" class="iconfont c1_green"><img src="../../../assets/images/app/icon2.png" height="100%" width="100%"></span>

                    <h2 class="c_green">聚农贷</h2>

                    <p>农村互联网金融</p>
                </a>
            </td>
        </tr>
        <tr>
            <td width="50%" valign="middle">
                <a href="javascript:void(0);"
                   data-url="<?php echo site_url('apps/home/project_list?category=3'); ?>">
                    <span class="iconfont c1_orange"><img src="../../../assets/images/app/icon3.png" height="100%" width="100%"></span>

                    <h2 class="c_orange">聚惠理财</h2>

                    <p>企业经营资金周转</p>
                </a>
            </td>
			<td width="50%" valign="middle">
                <a href="javascript:void(0);"
                   data-url="<?php echo site_url('apps/home/jbb_one'); ?>">
                    <span class="iconfont c_red"><img src="../../../assets/images/app/jbb.png" height="100%" width="100%"></span>

                    <h2 class="c_red">聚保宝</h2>

                    <p>灵活型理财产品</p>
                </a>
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
            to_app_view($(this).attr('data-url'));
        })
    });
</script>
</html>