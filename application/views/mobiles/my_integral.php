<!DOCTYPE html>
<html>
<head lang="en">
    <title>我的雪球</title>
    <?php $this->load->view('common/mobiles/app_head') ?>
    <link rel="stylesheet" type="text/css" href="/assets/css/app/snowcss.css">
</head>
<body>
<!-- 公共头部导航-->
<?php $this->load->view('common/mobiles/app_common_head') ?>

<div class="container-fluid">

    <!-- 顶部导航  -->

    <div class="pd10 bg_red row navbar-fixed-top" style="top:20px;">


        <div id="my_snowball">
            <h1>我的雪球:</h1>

            <p><span><?php echo $balance; ?></span>个</p>
        </div>

        <div id="title" class="btn-group btn-group-justified" style="width:56.25%; margin:0px auto;z-index:999">
            <span class="tab_ts  btn btn-default current" m="1">获得</span>
            <span class="tab_ts  btn btn-default" m="2">消耗</span>
        </div>

    </div>
    <!-- 顶部导航  end-->
    <div class="mb20" style="padding-top:190px; ">
        <div class="tab_block" id="list" style="visibility: hidden;">
            <div class="mb10 bg_white">
                <div class="mb10_1 bg_white">
                    <a href="javascript:void(0);">

                        <table width="100%" border="0" style="font-family:'微软雅黑';">

                            <tr>
                                <td colspan="2" style="border-bottom:1px solid #ccc">
                                    <div class="f18"><span class="c_333_1 flag_xq "></span></div>
                                </td>
                            </tr>
                            <tr>
                                <td valign="middle" height="60">
                                    <div class="f18" style="line-height:30px; height:30px; overflow:hidden;">
                                        <span class="c_333_2 remarks"></span>
                                    </div>
                                    <div class="c_888_1 dateline"></div>
                                </td>
                                <td align="right">
                                    <div class="f18_1 text-right_1"
                                         style="line-height:30px; height:30px; overflow:hidden;">
                                        <span class="c_blue_1 amount_xq"><em style="font-size:1.2rem;">个</em></span>
                                    </div>
                                </td>

                            </tr>

                        </table>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

</body>
<?php $this->load->view('common/mobiles/app_footer') ?>
<script>
    var g_m = '', page_id = 1, page_size = 10;
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
            $.post('/index.php/mobiles/home/my_integral' + condition, {}, function (result) {
                list_view.set_pageid(page_id);
                list_view.list(result.data, function (obj, v) {
                    //var now = new Date(parseInt(v.recordtime) * 1000);
                    //var kkk =Date(v.recordtime);
                    var dateObj = new Date(v.recordtime * 1000);
                    var getMinutes = dateObj.getMinutes();
                    var getHours = dateObj.getHours();
                    var getSeconds = dateObj.getSeconds();

                    if (dateObj.getMinutes().toString().length == 1) {
                        getMinutes = '0' + dateObj.getMinutes().toString();
                    }
                    if (dateObj.getHours().toString().length == 1) {
                        getHours = '0' + dateObj.getHours().toString();
                    }
                    if (dateObj.getSeconds().toString().length == 1) {
                        getSeconds = '0' + dateObj.getSeconds().toString();
                    }

                    var UnixTimeToDate = dateObj.getFullYear() + '-' + (dateObj.getMonth() + 1) + '-' + dateObj.getDate() + ' ' + getHours + ':' + getMinutes + ':' + getSeconds;

                    obj.find('.remarks').prepend('<span>' + v.active + '</span>');

                    obj.find('.dateline').prepend('<span>' + UnixTimeToDate + '</span>');

                    obj.find('.amount_xq').prepend('<span>' + v.amount + '</span>');

                    if (v.flag == 1) {
                        obj.find('.flag_xq').prepend('<span>获取方式：' + v.source + '</span>');
                        obj.find('.amount_xq').prepend('<span>+</span>');
                    } else {
                        obj.find('.flag_xq').prepend('<span>使用对象：' + v.source + '</span>');
                        obj.find('.amount_xq').removeClass('c_blue_1').addClass('c_blue_2').prepend('<span>-</span>');
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
                $('#list').html('');
                get_data();
            }
        });
        get_data();
    });
</script>
</html>