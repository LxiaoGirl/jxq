<!DOCTYPE html>
<html>
<head lang="en">
    <title>累计收益</title>
    <?php $this->load->view('common/apps/app_head') ?>
</head>
<body>
<div class="container-fluid">
    <!-- 顶部导航  -->
    <div class="bg_red_jb row  c_fff">
        <div class="fl f16 mt10 ml10">
            今日收益：
        </div>
        <div class="clears"></div>

        <div class="text-right mr10 ml10">
            <div class="superbig"></div>
        </div>
    </div>
    <!-- 顶部导航  end-->
    <div class="row" id="my_income">
        <table class="f12  mt15 mb15" width="100%" border="0" cellspacing="0" cellpadding="0">
            <tbody>
            <tr>
                <td style="border-bottom:1px #ddd solid;border-right:1px #ddd solid;" valign="bottom"
                    class="text-center" width="50%">
                    <span>累计投资(元)</span>

                    <div>
                        <strong class="f18 my_invest"></strong>
                    </div>
                </td>

                <td style="border-bottom:1px #ddd solid" valign="bottom" class="text-center" width="50%">
                    <span>累计收益(元)</span>

                    <div>
                        <strong class="f18 my_interest"></strong>
                    </div>
                </td>
            </tr>

            <tr>
                <td style="border-right:1px #ddd solid;" valign="bottom" class="text-center" width="50%">
                    <span>待收本金(元)</span>

                    <div>
                        <strong class="f18 my_wait_principal"></strong>
                    </div>
                </td>

                <td valign="bottom" class="text-center" width="50%">
                    <span>预计收益(元)</span>

                    <div>
                        <strong class="f18 my_wait_interest"></strong>
                    </div>
                </td>
            </tr>
            </tbody>
        </table>
    </div>

    <div class="clears line"></div>
    <div id="list" style="visibility: hidden">
        <div class="row">
            <a href="#">
                <table class="borderb" width="100%" border="0" cellspacing="0" cellpadding="0">
                    <tbody>
                    <tr>
                        <td>
                            <div class="f18 c_333 subject"></div>
                            <div style="text-align: right;">
                                <span class="text-nowrap mr10">投资：<span class="c_blue amount"></span>元  </span>
                                <span class="text-nowrap interest-content"> <span class="title-text">预计收益</span> <span class="c_red interest"></span>元 </span> 
                            </div>
                        </td>
                        <td align="right">
                            <span class="iconfont icon-xiangyou1"></span>
                        </td>
                    </tr>
                    </tbody>
                </table>
            </a>
        </div>
    </div>
</div>
</body>
<?php $this->load->view('common/apps/app_footer') ?>
<script>
    /**
     * 处理精度的减法
     * @param arg1
     * @param arg2
     * @returns {string}
     */
    function accSub(arg1, arg2) {
        var r1, r2, m, n;
        try {
            r1 = arg1.toString().split(".")[1].length;
        }
        catch (e) {
            r1 = 0;
        }
        try {
            r2 = arg2.toString().split(".")[1].length;
        }
        catch (e) {
            r2 = 0;
        }
        m = Math.pow(10, Math.max(r1, r2)); //last modify by deeka //动态控制精度长度
        n = (r1 >= r2) ? r1 : r2;
        return ((arg1 * m - arg2 * m) / m).toFixed(n);
    }
    var page_id = 1, page_size = 15;
    $(function () {
        var list_view = new wb_listview({
            'id': 'list',
            'showLoading': true
        });
        var list_one = new wb_listview({
            'id': 'my_income',
            'listone': true,
            'funcDeal': {
                'my_invest': function (data) {
                    return data ? price_format(data, 2, false) : 0;
                },
                'my_interest': function (data) {
                    return data ? price_format(data, 2, false) : 0;
                },
                'my_wait_principal': function (data) {
                    return data ? price_format(data, 2, false) : 0;
                },
                'my_wait_interest': function (data) {
                    return data ? price_format(data, 2, false) : 0;
                }
            }
        });
        var get_data = function () {
            var condition = '';
            condition += '?per_page=' + ((page_id - 1) * page_size) + '&limit=' + page_size;
            $(window).unbind('scroll');
            $.post('/index.php/apps/home/ajax_get_interest_list' + condition, {}, function (result) {
                list_view.set_pageid(page_id);
                list_view.list(result.data, function (obj, v) {
                    if (v.type == 1) {
                        obj.find('.title-text').text('收益');
                    }
                    if(v.type == 2){
                        obj.find('.title-text').text('剩余收益');
                        obj.find('.interest-content').after('<div class="text-nowrap"> <span class="title-text">已收益</span> <span class="c_red">'+v.haved_interest+'</span>元 </div> ')
                    }

                    obj.find('a').attr('href', '<?php echo site_url("apps/home/project_detail?borrow_no=") ?>' + v.borrow_no);
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
        $.post('/index.php/apps/home/my_income', {}, function (rs) {
            list_one.list_one(rs, function (obj, v) {
                var my_today_invest = '';
                var wan = v.my_today_invest ? Math.round(parseFloat(v.my_balance) / 10000) : 0;
                if (v.my_today_invest && wan > 1) {
                    my_today_invest = '<span>' + wan + '</span>万' +
                    '<span>' + accSub(parseFloat(v.my_balance), wan * 10000) + '</span>元';
                } else {
                    if (!v.my_today_invest)v.my_today_invest = 0;
                    my_today_invest = '<span>' + parseFloat(v.my_today_invest) + '</span>元';
                }
                $(".superbig").html(my_today_invest);
            });
        }, 'json');
        get_data();
    });
</script>
</html>