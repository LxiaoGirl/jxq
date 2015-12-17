<!DOCTYPE html>
<html>
<head lang="en">
    <title>交易明细</title>
    <?php $this->load->view('common/mobiles/app_head') ?>
</head>
<body>
<!-- 公共头部导航-->
<?php $this->load->view('common/mobiles/app_common_head') ?>

<div class="container-fluid">
    <!-- 顶部导航  -->
    <div class="pd10 bg_red row navbar-fixed-top" style="  top: 50px;">
        <div style="padding-left:10px; padding-right:10px;">
            <div id="title" class="btn-group btn-group-justified">
                <span class="tab_ts btn btn-default current" m="0">全部</span>
                <span class="tab_ts btn btn-default" m="1">收入</span>
                <span class="tab_ts btn btn-default" m="2">支出</span>
                <span class="tab_ts btn btn-default" m="3">投资冻结</span>
                <span class="tab_ts btn btn-default" m="4">提现冻结</span>
            </div>
        </div>
    </div>
    <!-- 顶部导航  end-->

    <p class="total total-0 total-1" style="margin-top: 70px; text-align:center;">
    <span>收入合计：<span><?php echo $income_total; ?></span>元</span>
    <span>支出合计：<span><?php echo $pay_total; ?></span>元</span>
    <span>冻结合计：<span><?php echo $frozen_total; ?></span>元</span>
    </p>
    
    <div class="mb20">
        <div class="tab_block" id="list" style="visibility: hidden">
            <div class="mb10 bg_white">
                <a href="#">
                    <table class="borderb" width="100%" border="0" cellspacing="0" cellpadding="0">
                        <tbody>
                        <tr>
                            <td valign="middle" height="60">
                                <div class="f18" style="line-height:30px; height:30px; overflow:hidden;">
                                    <span class="c_333 remarks"></span>
                                </div>
                                <div class="c_888 dateline"></div>
                            </td>
                            <td align="right">
                                <div class="f18 text-right" style="line-height:30px; height:30px; overflow:hidden;">
                                    <span class="c_blue amount"></span>
                                </div>
                                <div class="c_888 log-type">支出</div>
                            </td>
                            <td width="30"><!-- data-bfb 为百分比数字 -->
                                <span class="iconfont icon-xiangyou1"></span>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </a>
            </div>
        </div>
    </div>
</div>
</body>
<?php $this->load->view('common/mobiles/app_footer') ?>
<script>
    var g_m = '', page_id = 1, page_size = 12;
    $(function () {
        var list_view = new wb_listview({
            'id': 'list',
            'showLoading': true,
            'funcDeal': {
                'dateline': function (time) {
                    return unixtime_style(time, 'Y-m-d')
                }
            }
        });
        var get_data = function () {
            var condition = '';
            if (g_m != '') {
                condition += '?status=' + g_m;
            }
            condition += (condition ? '&' : '?') + 'per_page=' + ((page_id - 1) * page_size) + '&limit=' + page_size;
            $(window).unbind('scroll');
            $.post('/index.php/mobiles/home/my_cash_log' + condition, {}, function (result) {
                list_view.set_pageid(page_id);
                list_view.list(result.data, function (obj, v) {
                    if (v.type == 1 || v.type == 7) {
                        obj.find('.amount').removeClass('c_blue').addClass('c_green').prepend('<span>+</span>');
                        obj.find('.log-type').html('收入');
                    } else {
                        obj.find('.amount').prepend('<span>-</span>');
                    }

                    if (v.remarks == '') {
                        var ramarks = '';
                        switch (v.type) {
                            case '1':
                                ramarks = '充值';
                                break;
                            case '2':
                                ramarks = '提现';
                                break;
                            case '3':
                                ramarks = '提现冻结';
                                break;
                            case '4':
                                ramarks = '投资冻结';
                                break;
                            case '5':
                                ramarks = '投资';
                                break;
                            case '7':
                                ramarks = '收益';
                                break;
                            case '10':
                                ramarks = '还款扣款';
                                break;
                        }
                        obj.find('.remarks').html(ramarks);
                    }
                });
                if (result.data) {
                    page_id++;
                    $(window).bind('scroll', function () {
                        scroll_fun(function () {
                            get_data();
                        });
                    });
                }
            }, 'json');
        };
        $("#title span").on('tap', function () {
            $("#title span").removeClass('current');
            $(this).addClass('current');
            if (g_m != $(this).attr('m')) {
                g_m = $(this).attr('m');
                page_id = 1;
                get_data();
            }
        });
        get_data();
    });
</script>
</html>