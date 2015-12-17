<!DOCTYPE html>
<html>
<head lang="en">
    <title><?php echo $title; ?></title>
    <?php $this->load->view('common/apps/app_head') ?>
</head>
<body>
<div class="container-fluid">
    <!-- 选项卡标题 -->
    <div class="row">
        <ul class="index_tab_title clearfix" id="months">
            <li class="current" m=""><a href="#">全部</a></li>
            <li m="0-0.9"><a href="#">0.9个月</a></li>
            <li m="3-3"><a href="#">3个月</a></li>
            <li m="3-12"><a href="#">3-12个月</a></li>
        </ul>
    </div>
    <!-- 选项卡标题  end-->
    <!-- 选项内容 -->
    <div class="index_tab_nr" id="list" style="visibility: hidden">
        <div class="index_info">
            <span class="tap-span" onclick="">
                <table width="100%" border="0" cellspacing="0" cellpadding="0">
                    <tbody>
                    <tr>
                        <td valign="middle" height="60">
                            <span><strong class="c_red f18 vm rate"></strong><i>%</i></span>
                            <span class="ml10 mr10"><em class="f18 vm months"></em><i>个月</i></span>
                            <span><em class="f18 vm amount"></em><i>万元</i></span>
                        </td>
                        <td width="60"><!-- data-bfb 为百分比数字 -->
                            <div class="bfb   bfb80 r_rate" data-bfb="0"></div>
                            <!-- data-bfb 为百分比数字 -->
                        </td>
                    </tr>
                    </tbody>
                </table>
                <div class="clears line"></div>
                <div class="pd10">
                    <span class="mr10 vm category"></span>
                    <span class="mr10 vm subject"></span>
                    <span class="mr10 vm mode"><i class="dots_green"></i></span>
                </div>
            </span>
        </div>
    </div>
    <!-- 选项内容 end-->
</div>

<?php $this->load->view('common/apps/app_alert') ?>
</body>
<?php $this->load->view('common/apps/app_footer') ?>
<script>
    var g_m = '', page_id = 1, page_size = 10, category = '<?php echo $category; ?>';
    $(function () {
        var list_view = new wb_listview({
            'id': 'list',
            'showLoading': true,
            'funcDeal': {
                'rate': function (rate) {
                    return rate_format(rate);
                },
                'amount': function (price) {
                    return rate_format(price_format(price, 3, false));
                },
                'mode': function (mode) {
                    return '<i class="dots_green"></i>' + borrow_mode(mode);
                }
            }
        });
        var get_data = function () {
            var condition = '?category=' + category;
            if (g_m != '') {
                condition += '&m=' + g_m;
            }
            condition += (condition ? '&' : '?') + 'per_page=' + ((page_id - 1) * page_size) + '&limit=' + page_size;
            $(window).unbind('scroll');
            $.post('/index.php/20150707/home/get_project_list' + condition, {}, function (result) {
                list_view.set_pageid(page_id);
                list_view.list(result.data, function (obj, v) {
                    if (v.status == 4) {
                        obj.find('.r_rate').removeClass('bfb').addClass('index_huankuan').html('<span>还款中</span>');
                    } else if (v.status == 7) {
                        obj.find('.r_rate').removeClass('bfb').addClass('index_wancheng').html('<span>已完成</span>');
                    } else {
                        obj.find('.r_rate').attr('data-bfb', v.receive_rate);
                    }
                    obj.find('.tap-span').attr('onclick', 'window.location.href="<?php echo site_url("20150707/home/project_detail");?>?borrow_no=' + v.borrow_no + '"')
                }, function () {
                    $("canvas").remove();
                });
                if (result.data) {
                    page_id++;
                    bfbFun();
                    $(window).bind('scroll', function () {
                        scroll_fun(function () {
                            get_data();
                        });
                    });
                }
            }, 'json');
        };
        $("#months li").on('tap', function () {
            $("#months li").removeClass('current');
            $(this).addClass('current');
            if (g_m != $(this).attr('m')) {
                g_m = $(this).attr('m');
                page_id = 1;
                $('#list').html('');
                get_data();
            }
        });
        get_data();
    });
</script>
</html>