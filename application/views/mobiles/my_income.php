<!DOCTYPE html>
<html>
<head lang="en">
    <title>累计收益</title>
    <?php $this->load->view('common/mobiles/app_head') ?>
</head>
<body>
<!-- 公共头部导航-->
<?php $this->load->view('common/mobiles/app_common_head') ?>

<div class="container-fluid">
    <!-- 顶部导航  -->
    <div class="bg_red_jb row  c_fff my-income">
        <div class="fl f16 mt10 ml10">
            今日收益：
        </div>
        <div class="clears"></div>

        <div class="text-right mr10 ml10">
            <div class="superbig my_today_invest list-value">0</div>
        </div>
    </div>
    <!-- 顶部导航  end-->
    <div class="row my-income" id="my_income">
        <table class="f12  mt15 mb15" width="100%" border="0" cellspacing="0" cellpadding="0">
            <tbody>
            <tr>
                <td style="border-bottom:1px #ddd solid;border-right:1px #ddd solid;" valign="bottom"
                    class="text-center" width="50%">
                    <span>累计投资(元)</span>

                    <div>
                        <strong class="f18 my_invest">0</strong>
                    </div>
                </td>

                <td style="border-bottom:1px #ddd solid" valign="bottom" class="text-center" width="50%">
                    <span>累计收益(元)</span>

                    <div>
                        <strong class="f18 my_interest">0</strong>
                    </div>
                </td>
            </tr>

            <tr>
                <td style="border-right:1px #ddd solid;" valign="bottom" class="text-center" width="50%">
                    <span>待收本金(元)</span>

                    <div>
                        <strong class="f18 my_wait_principal">0</strong>
                    </div>
                </td>

                <td valign="bottom" class="text-center" width="50%">
                    <span>预计收益(元)</span>

                    <div>
                        <strong class="f18 my_wait_interest">0</strong>
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
<?php $this->load->view('common/mobiles/app_footer') ?>
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
    $(function () {
        $('.my-income').list_data({
            list_one:true,
            data:'/index.php/mobiles/home/my_income',
            show_loading:true,
            btn:true,
            'value_func': {
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
                },
                'my_today_invest':function(data){
                    var my_balance = '';
                    var wan = data ? Math.floor(parseFloat(data) / 10000) : 0;
                    if (data && wan > 1) {
                        my_balance = '<span>' + wan + '</span>万' +
                            '<span>' + accSub(parseFloat(data), wan * 10000) + '</span>元';
                    } else {
                        if (!data)data = 0;
                        my_balance = '<span>' + parseFloat(data) + '</span>元';
                    }
                    return my_balance;
                }
            },
            callback:function(){
                $("#list").list_data({
                    data:'/index.php/mobiles/home/ajax_get_interest_list',
                    page_size:20,
//                    show_loading:true,
                    event_type:'scroll',
                    value_func: {
                        'add_time': function (time) {
                            return unixtime_style(time, 'Y-m-d');
                        }
                    },
                    list_func:function(obj,v){
                        if (v.interest == v.interest_receive) {
                            obj.find('.title-text').text('收益');
                        }else{
                            if(v.interest_receive > 0){
                                obj.find('.title-text').text('剩余收益');
                                obj.find('.interest').text(v.interest-v.interest_receive);
                                obj.find('.interest-content').after('<div class="text-nowrap"> <span class="title-text">已收益</span> <span class="c_red">'+v.interest_receive+'</span>元 </div> ')
                            }
                        }
                        obj.find('a').attr('href', '<?php echo site_url("mobiles/home/project_detail?borrow_no=") ?>' + v.borrow_no);
                    }
                });
            }
        });
    });
</script>
</html>